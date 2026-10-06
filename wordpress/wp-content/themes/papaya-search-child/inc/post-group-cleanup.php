<?php
/** Remove legacy gray Group styling from saved post content once. */
defined('ABSPATH') || exit();

function ps_clean_gray_post_groups($blocks, &$count)
{
    foreach ($blocks as &$block) {
        if ($block['blockName'] === 'core/group') {
            $tag = new WP_HTML_Tag_Processor($block['innerHTML']);
            $has_wrapper = $tag->next_tag(['class_name' => 'wp-block-group']);
            $inline = $has_wrapper ? (string) $tag->get_attribute('style') : '';
            $background = $block['attrs']['style']['color']['background'] ?? '';
            if (
                strtolower(trim($background)) === '#f5f5f5' ||
                preg_match('/(?:^|;)\s*background-color\s*:\s*#f5f5f5\s*(?:!important\s*)?(?:;|$)/i', $inline)
            ) {
                unset($block['attrs']['style']);
                $clean_wrapper = function ($html) {
                    $processor = new WP_HTML_Tag_Processor($html);
                    if ($processor->next_tag(['class_name' => 'wp-block-group'])) {
                        $processor->remove_attribute('style');
                        $processor->remove_class('has-background');
                        $processor->remove_class('has-text-color');
                        $processor->remove_class('has-link-color');
                    }
                    return $processor->get_updated_html();
                };
                $block['innerHTML'] = $clean_wrapper($block['innerHTML']);
                // Only the opening wrapper fragment belongs to this Group.
                foreach ($block['innerContent'] as &$fragment) {
                    if (is_string($fragment)) {
                        $fragment = $clean_wrapper($fragment);
                        break;
                    }
                }
                unset($fragment);
                $count++;
            }
        }
        $block['innerBlocks'] = ps_clean_gray_post_groups($block['innerBlocks'], $count);
    }
    unset($block);
    return $blocks;
}

function ps_cleanup_post_group_styles()
{
    $done = get_option('ps_post_group_cleanup_v1');
    if ($done) {
        return $done;
    }
    global $wpdb;
    $report = ['posts' => 0, 'groups' => 0];
    $posts = $wpdb->get_results("SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type = 'post'");
    foreach ($posts as $post) {
        $count = 0;
        $blocks = ps_clean_gray_post_groups(parse_blocks($post->post_content), $count);
        if (!$count) {
            continue;
        }
        $content = serialize_blocks($blocks);
        $backup_key = '_ps_before_group_style_cleanup_v1';
        if (!metadata_exists('post', $post->ID, $backup_key)) {
            if (!add_post_meta($post->ID, $backup_key, wp_slash($post->post_content), true)) {
                return new WP_Error('group_backup_failed', 'Could not back up post ' . $post->ID . '.');
            }
        }
        // Change only content, preserving dates and avoiding unrelated save filters.
        $updated = $wpdb->update(
            $wpdb->posts,
            ['post_content' => $content],
            ['ID' => $post->ID, 'post_content' => $post->post_content],
            ['%s'],
            ['%d', '%s']
        );
        if ($updated !== 1) {
            return new WP_Error('group_cleanup_failed', 'Could not update post ' . $post->ID . '; please retry.');
        }
        clean_post_cache($post->ID);
        $report['posts']++;
        $report['groups'] += $count;
    }
    update_option('ps_post_group_cleanup_v1', $report, false);
    return $report;
}

/** Identify previously cleaned Groups from the preserved original content. */
function ps_cleaned_group_signatures($blocks)
{
    $signatures = [];
    foreach ($blocks as $block) {
        $original_children = $block['innerBlocks'];
        $block['innerBlocks'] = [];
        $count = 0;
        ps_clean_gray_post_groups([$block], $count);
        $block['innerBlocks'] = $original_children;
        if ($count) {
            $ignored = 0;
            $cleaned = ps_clean_gray_post_groups([$block], $ignored);
            $signatures[hash('sha256', serialize_block($cleaned[0]))] = true;
        }
        $signatures += ps_cleaned_group_signatures($original_children);
    }
    return $signatures;
}

function ps_label_post_groups($blocks, $cleaned, &$counts)
{
    foreach ($blocks as &$block) {
        // Match before changing descendant classes, which are part of serialization.
        if ($block['blockName'] === 'core/group') {
            $existing_classes = preg_split('/\s+/', trim($block['attrs']['className'] ?? ''));
            $class = in_array('green-post-block', $existing_classes, true) || isset($cleaned[hash('sha256', serialize_block($block))])
                ? 'green-post-block'
                : 'orange-post-block';
            $classes = preg_split('/\s+/', trim($block['attrs']['className'] ?? ''), -1, PREG_SPLIT_NO_EMPTY);
            $classes = array_diff($classes, ['green-post-block', 'orange-post-block']);
            $classes[] = $class;
            $block['attrs']['className'] = implode(' ', $classes);
            $label_wrapper = function ($html) use ($class) {
                $processor = new WP_HTML_Tag_Processor($html);
                if ($processor->next_tag(['class_name' => 'wp-block-group'])) {
                    $processor->remove_class('green-post-block');
                    $processor->remove_class('orange-post-block');
                    $processor->add_class($class);
                }
                return $processor->get_updated_html();
            };
            $block['innerHTML'] = $label_wrapper($block['innerHTML']);
            foreach ($block['innerContent'] as &$fragment) {
                if (is_string($fragment)) {
                    $fragment = $label_wrapper($fragment);
                    break;
                }
            }
            unset($fragment);
            $counts[$class]++;
        }
        $block['innerBlocks'] = ps_label_post_groups($block['innerBlocks'], $cleaned, $counts);
    }
    unset($block);
    return $blocks;
}

function ps_install_post_group_classes()
{
    $done = get_option('ps_post_group_classes_v1');
    if ($done) {
        return $done;
    }
    $cleanup = ps_cleanup_post_group_styles();
    if (is_wp_error($cleanup)) {
        return $cleanup;
    }
    global $wpdb;
    $report = ['posts' => 0, 'green-post-block' => 0, 'orange-post-block' => 0];
    $posts = $wpdb->get_results("SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type = 'post'");
    foreach ($posts as $post) {
        $original = get_post_meta($post->ID, '_ps_before_group_style_cleanup_v1', true);
        $cleaned = ps_cleaned_group_signatures(parse_blocks($original));
        $counts = ['green-post-block' => 0, 'orange-post-block' => 0];
        $blocks = ps_label_post_groups(parse_blocks($post->post_content), $cleaned, $counts);
        if (!array_sum($counts)) {
            continue;
        }
        $backup_key = '_ps_before_group_classes_v1';
        if (!metadata_exists('post', $post->ID, $backup_key) &&
            !add_post_meta($post->ID, $backup_key, wp_slash($post->post_content), true)) {
            return new WP_Error('group_classes_backup', 'Could not back up post ' . $post->ID . '.');
        }
        $content = serialize_blocks($blocks);
        if ($content !== $post->post_content) {
            $updated = $wpdb->update($wpdb->posts, ['post_content' => $content],
                ['ID' => $post->ID, 'post_content' => $post->post_content], ['%s'], ['%d', '%s']);
            if ($updated !== 1) {
                return new WP_Error('group_classes_failed', 'Could not label Groups in post ' . $post->ID . '.');
            }
            clean_post_cache($post->ID);
        }
        $report['posts']++;
        $report['green-post-block'] += $counts['green-post-block'];
        $report['orange-post-block'] += $counts['orange-post-block'];
    }
    update_option('ps_post_group_classes_v1', $report, false);
    return $report;
}

function ps_clean_orange_post_groups($blocks, &$count)
{
    foreach ($blocks as &$block) {
        $classes = preg_split('/\s+/', trim($block['attrs']['className'] ?? ''));
        $tag = new WP_HTML_Tag_Processor($block['innerHTML']);
        $orange = $tag->next_tag(['class_name' => 'wp-block-group']) && $tag->has_class('orange-post-block');
        if ($block['blockName'] === 'core/group' && (in_array('orange-post-block', $classes, true) || $orange)) {
            $has_style = isset($block['attrs']['style']) || ($orange && $tag->get_attribute('style') !== null);
            unset($block['attrs']['style']);
            $clean = function ($html) {
                $processor = new WP_HTML_Tag_Processor($html);
                if ($processor->next_tag(['class_name' => 'wp-block-group'])) {
                    $processor->remove_attribute('style');
                    $processor->remove_class('has-background');
                    $processor->remove_class('has-text-color');
                    $processor->remove_class('has-link-color');
                }
                return $processor->get_updated_html();
            };
            $block['innerHTML'] = $clean($block['innerHTML']);
            foreach ($block['innerContent'] as &$fragment) {
                if (is_string($fragment)) {
                    $fragment = $clean($fragment);
                    break;
                }
            }
            unset($fragment);
            if ($has_style) {
                $count++;
            }
        }
        $block['innerBlocks'] = ps_clean_orange_post_groups($block['innerBlocks'], $count);
    }
    unset($block);
    return $blocks;
}

function ps_install_orange_post_group_styles()
{
    $done = get_option('ps_orange_group_cleanup_v1');
    if ($done) {
        return $done;
    }
    $labels = ps_install_post_group_classes();
    if (is_wp_error($labels)) {
        return $labels;
    }
    global $wpdb;
    $report = ['posts' => 0, 'groups' => 0];
    $posts = $wpdb->get_results("SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type = 'post'");
    foreach ($posts as $post) {
        $count = 0;
        $blocks = ps_clean_orange_post_groups(parse_blocks($post->post_content), $count);
        if (!$count) {
            continue;
        }
        $backup_key = '_ps_before_orange_group_cleanup_v1';
        if (!metadata_exists('post', $post->ID, $backup_key) &&
            !add_post_meta($post->ID, $backup_key, wp_slash($post->post_content), true)) {
            return new WP_Error('orange_group_backup', 'Could not back up post ' . $post->ID . '.');
        }
        $updated = $wpdb->update($wpdb->posts, ['post_content' => serialize_blocks($blocks)],
            ['ID' => $post->ID, 'post_content' => $post->post_content], ['%s'], ['%d', '%s']);
        if ($updated !== 1) {
            return new WP_Error('orange_group_cleanup', 'Could not update post ' . $post->ID . '.');
        }
        clean_post_cache($post->ID);
        $report['posts']++;
        $report['groups'] += $count;
    }
    update_option('ps_orange_group_cleanup_v1', $report, false);
    return $report;
}

add_action('admin_init', function () {
    if (!current_user_can('manage_options')) {
        return;
    }
    $result = ps_install_orange_post_group_styles();
    if (is_wp_error($result)) {
        add_action('admin_notices', function () use ($result) {
            echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
        });
    }
}, 50);
