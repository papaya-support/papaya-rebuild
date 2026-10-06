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

add_action('admin_init', function () {
    if (!current_user_can('manage_options')) {
        return;
    }
    $result = ps_cleanup_post_group_styles();
    if (is_wp_error($result)) {
        add_action('admin_notices', function () use ($result) {
            echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
        });
    }
}, 50);
