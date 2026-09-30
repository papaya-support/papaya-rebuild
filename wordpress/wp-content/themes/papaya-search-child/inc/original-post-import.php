<?php
/** Resumable, administrator-only import of the supplied September 2026 WXR. */
defined('ABSPATH') || exit;

class PS_Original_Post_Import
{
    const OPTION = 'ps_original_posts_20260930';
    const SOURCE_HASH = 'ba3c23d2c4c2dae16866fec71aeced320ad6abfbe7e2256a7146c23c0cb24b9f';
    const PREFIX = 'papaya-originals-20260930/';

    public static function source()
    {
        $xml = require dirname(__DIR__) . '/import-data/original-posts.php';
        if (hash('sha256', $xml) !== self::SOURCE_HASH) {
            throw new RuntimeException('The source export checksum does not match.');
        }
        if (!function_exists('simplexml_load_string')) {
            throw new RuntimeException('The PHP SimpleXML extension is required.');
        }
        return simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA)->channel;
    }

    public static function wp($node)
    {
        return $node->children('http://wordpress.org/export/1.2/');
    }

    public static function items()
    {
        $items = iterator_to_array(self::source()->item, false);
        // Media first; parent/thumbnail relationships are resolved after all records exist.
        usort($items, function ($a, $b) {
            return ((string) self::wp($a)->post_type !== 'attachment') <=> ((string) self::wp($b)->post_type !== 'attachment');
        });
        return $items;
    }

    public static function state()
    {
        return get_option(self::OPTION, ['step' => 0, 'posts' => [], 'authors' => [], 'comments' => [], 'complete' => false]);
    }

    public static function authors(&$state)
    {
        foreach (self::wp(self::source())->author as $author) {
            $login = (string) $author->author_login;
            $state['source_authors'][$login] = [
                'id' => (int) $author->author_id,
                'login' => $login,
                'email' => (string) $author->author_email,
                'display_name' => (string) $author->author_display_name,
                'first_name' => (string) $author->author_first_name,
                'last_name' => (string) $author->author_last_name,
            ];
            if (isset($state['authors'][$login])) {
                continue;
            }
            $existing = get_user_by('login', $login);
            if (!$existing && (string) $author->author_email) {
                $existing = get_user_by('email', (string) $author->author_email);
            }
            // Existing accounts belong to this site. Reuse their identity without
            // changing profile details, credentials, roles or capabilities.
            $id = $existing ? $existing->ID : wp_insert_user([
                'user_login' => $login,
                'user_email' => (string) $author->author_email,
                'display_name' => (string) $author->author_display_name,
                'first_name' => (string) $author->author_first_name,
                'last_name' => (string) $author->author_last_name,
                'user_pass' => wp_generate_password(48, true, true),
                'role' => 'subscriber',
            ]);
            if (is_wp_error($id)) {
                throw new RuntimeException($id->get_error_message());
            }
            $state['authors'][$login] = (int) $id;
            $state['author_ids'][(string) $author->author_id] = (int) $id;
            update_option(self::OPTION, $state, false);
        }
    }

    public static function media_files($item)
    {
        $wp = self::wp($item);
        $url = (string) $wp->attachment_url;
        $relative = explode('/wp-content/uploads/', wp_parse_url($url, PHP_URL_PATH), 2)[1] ?? '';
        if (!$relative || str_contains($relative, '..')) {
            throw new RuntimeException('Unsupported media path: ' . $url);
        }
        $files = [$relative => $url];
        foreach ($wp->postmeta as $meta) {
            if (!in_array((string) $meta->meta_key, ['_wp_attachment_metadata', '_wp_attachment_backup_sizes'], true)) {
                continue;
            }
            preg_match_all('/s:(?:4:"file"|14:"original_image");s:\d+:"([^"]+)"/', (string) $meta->meta_value, $matches);
            foreach ($matches[1] as $name) {
                $files[dirname($relative) . '/' . basename($name)] = dirname($url) . '/' . basename($name);
            }
        }
        $uploads = wp_upload_dir();
        if ($uploads['error']) {
            throw new RuntimeException($uploads['error']);
        }
        foreach ($files as $path => $remote) {
            $target = $uploads['basedir'] . '/' . self::PREFIX . $path;
            if (is_file($target) && filesize($target)) {
                continue;
            }
            wp_mkdir_p(dirname($target));
            $source_directory = defined('PS_ORIGINAL_MEDIA_PATH')
                ? PS_ORIGINAL_MEDIA_PATH
                : dirname(__DIR__) . '/import-data/media';
            if (is_dir($source_directory)) {
                $local = $source_directory . '/' . $path;
                if (!is_file($local) || !copy($local, $target)) {
                    throw new RuntimeException('Missing local media file: ' . $path);
                }
            } else {
                require_once ABSPATH . 'wp-admin/includes/file.php';
                $tmp = download_url($remote, 60);
                if (is_wp_error($tmp)) {
                    throw new RuntimeException('Media download failed: ' . $remote . ' — ' . $tmp->get_error_message());
                }
                $copied = copy($tmp, $target);
                unlink($tmp);
                if (!$copied) {
                    throw new RuntimeException('Could not save media: ' . $path);
                }
            }
        }
        return self::PREFIX . $relative;
    }

    public static function insert($item, &$state)
    {
        global $wpdb;
        $wp = self::wp($item);
        $old = (string) $wp->post_id;
        if (!empty($state['posts'][$old])) {
            return;
        }
        $existing = get_posts(['post_type' => ['post', 'attachment'], 'post_status' => 'any', 'fields' => 'ids', 'meta_key' => '_ps_original_wxr_id', 'meta_value' => $old]);
        if ($existing) {
            $state['posts'][$old] = (int) $existing[0];
            return;
        }
        $type = (string) $wp->post_type;
        $media = $type === 'attachment' ? self::media_files($item) : null;
        $guid = (string) $item->guid;
        if ($wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $guid))) {
            throw new RuntimeException('An existing record has the same source GUID. Import paused to avoid overwriting it: ' . (string) $item->title);
        }
        $content = $item->children('http://purl.org/rss/1.0/modules/content/');
        $excerpt = $item->children('http://wordpress.org/export/1.2/excerpt/');
        $author = (string) $item->children('http://purl.org/dc/elements/1.1/')->creator;
        $data = [
            'post_type' => $type,
            'post_title' => (string) $item->title,
            'post_content' => (string) $content->encoded,
            'post_excerpt' => (string) $excerpt->encoded,
            'post_status' => (string) $wp->status,
            'post_author' => $state['authors'][$author],
            'post_date' => (string) $wp->post_date,
            'post_date_gmt' => (string) $wp->post_date_gmt,
            'post_name' => (string) $wp->post_name,
            'post_password' => (string) $wp->post_password,
            'comment_status' => (string) $wp->comment_status,
            'ping_status' => (string) $wp->ping_status,
            'menu_order' => (int) $wp->menu_order,
            'guid' => $guid,
        ];
        if ($media) {
            $data['post_mime_type'] = wp_check_filetype($media)['type'];
        }
        $id = wp_insert_post(wp_slash($data), true);
        if (is_wp_error($id)) {
            throw new RuntimeException($id->get_error_message());
        }
        // Preserve source bytes/dates even if a plugin modifies WordPress insertion filters.
        $wpdb->update($wpdb->posts, array_merge($data, [
            'post_modified' => (string) $wp->post_modified,
            'post_modified_gmt' => (string) $wp->post_modified_gmt,
        ]), ['ID' => $id]);
        foreach ($wp->postmeta as $meta) {
            $key = (string) $meta->meta_key;
            // Keep serialized plugin objects byte-for-byte; wp_slash does not
            // recursively escape objects, so passing them through add_post_meta
            // can remove backslashes from saved SEO analysis data.
            $raw = (string) $meta->meta_value;
            if ($key === '_wp_attached_file') {
                $raw = $media;
            } elseif ($key === '_wp_attachment_metadata') {
                $value = maybe_unserialize($raw);
                if (is_array($value) && isset($value['file'])) {
                    $value['file'] = $media;
                    $raw = maybe_serialize($value);
                }
            }
            if (!$wpdb->insert($wpdb->postmeta, ['post_id' => $id, 'meta_key' => $key, 'meta_value' => $raw])) {
                throw new RuntimeException('Could not preserve metadata: ' . $key);
            }
        }
        $terms = [];
        foreach ($item->category as $category) {
            $taxonomy = (string) $category['domain'];
            $slug = (string) $category['nicename'];
            $term = get_term_by('slug', $slug, $taxonomy);
            if (!$term) {
                $result = wp_insert_term((string) $category, $taxonomy, ['slug' => $slug]);
                if (is_wp_error($result)) {
                    throw new RuntimeException($result->get_error_message());
                }
                $term_id = $result['term_id'];
            } else {
                $term_id = $term->term_id;
            }
            $terms[$taxonomy][] = (int) $term_id;
        }
        foreach ($terms as $taxonomy => $ids) {
            $assigned = wp_set_object_terms($id, $ids, $taxonomy);
            if (is_wp_error($assigned)) {
                throw new RuntimeException($assigned->get_error_message());
            }
        }
        if ((int) $wp->is_sticky) {
            stick_post($id);
        }
        foreach ($wp->comment as $comment) {
            $comment_data = ['comment_post_ID' => $id];
            foreach (['comment_author', 'comment_author_email', 'comment_author_url', 'comment_author_IP', 'comment_date', 'comment_date_gmt', 'comment_content', 'comment_approved', 'comment_type'] as $key) {
                $comment_data[$key] = (string) $comment->$key;
            }
            $comment_data['user_id'] = $state['author_ids'][(string) $comment->comment_user_id] ?? 0;
            $comment_id = wp_insert_comment($comment_data);
            if (!$comment_id) {
                throw new RuntimeException('Could not import comment ' . (string) $comment->comment_id);
            }
            $state['comments'][(string) $comment->comment_id] = $comment_id;
            foreach ($comment->commentmeta as $meta) {
                add_comment_meta($comment_id, (string) $meta->meta_key, wp_slash(maybe_unserialize((string) $meta->meta_value)));
            }
        }
        add_post_meta($id, '_ps_original_wxr_id', $old, true);
        $state['posts'][$old] = (int) $id;
        clean_post_cache($id);
    }

    public static function finalize(&$state)
    {
        global $wpdb;
        foreach (self::items() as $item) {
            $wp = self::wp($item);
            $id = $state['posts'][(string) $wp->post_id];
            $wpdb->update($wpdb->posts, ['post_parent' => $state['posts'][(string) $wp->post_parent] ?? 0], ['ID' => $id]);
            foreach ($wp->postmeta as $meta) {
                $key = (string) $meta->meta_key;
                $value = (string) $meta->meta_value;
                if (in_array($key, ['_thumbnail_id', '_seopress_social_fb_img_attachment_id'], true) && isset($state['posts'][$value])) {
                    update_post_meta($id, $key, $state['posts'][$value]);
                }
                if (in_array($key, ['_yoast_wpseo_primary_category', '_seopress_robots_primary_cat'], true)) {
                    $categories = wp_get_post_categories($id);
                    if (count($categories) === 1) {
                        update_post_meta($id, $key, $categories[0]);
                    }
                }
            }
            foreach ($wp->comment as $comment) {
                $wpdb->update($wpdb->comments, ['comment_parent' => $state['comments'][(string) $comment->comment_parent] ?? 0], ['comment_ID' => $state['comments'][(string) $comment->comment_id]]);
            }
            clean_post_cache($id);
        }
        flush_rewrite_rules(false);
        $state['complete'] = true;
    }

    public static function step()
    {
        if (!current_user_can('manage_options') || !current_user_can('import')) {
            throw new RuntimeException('Administrator import permission is required.');
        }
        if (!defined('WP_IMPORTING')) {
            define('WP_IMPORTING', true);
        }
        global $wpdb;
        $lock = (int) get_option(self::OPTION . '_lock');
        if ($lock && $lock < time() - 600) {
            delete_option(self::OPTION . '_lock');
        }
        if (!add_option(self::OPTION . '_lock', time(), '', false)) {
            throw new RuntimeException('Another import request is running. Wait before retrying.');
        }
        $wpdb->query('START TRANSACTION');
        try {
            $state = self::state();
            if (!$state['complete']) {
                self::authors($state);
                $items = self::items();
                if ($state['step'] < count($items)) {
                    self::insert($items[$state['step']], $state);
                    $state['step']++;
                } else {
                    self::finalize($state);
                }
                update_option(self::OPTION, $state, false);
            }
            $wpdb->query('COMMIT');
            return ['processed' => $state['step'], 'total' => count(self::items()), 'complete' => $state['complete']];
        } catch (Throwable $error) {
            $wpdb->query('ROLLBACK');
            throw $error;
        } finally {
            delete_option(self::OPTION . '_lock');
        }
    }
}

add_action('admin_menu', function () {
    add_management_page('Import Original Papaya Posts', 'Import Original Papaya Posts', 'manage_options', 'ps-original-posts', function () {
        $state = PS_Original_Post_Import::state();
        ?>
        <div class="wrap">
            <h1>Import Original Papaya Posts</h1>
            <p>Import the September 30 export: 27 posts (24 published, 3 drafts), 90 media records, authors, categories, metadata and comments. Existing site content is retained. Original media files are included in the deployment package.</p>
            <p>Original content is preserved. Database IDs and media locations are mapped to this site. The source export remains bundled for recovery.</p>
            <p id="ps-import-status"><?php echo $state['complete'] ? 'Import complete.' : esc_html($state['step'] . ' of 117 records imported.'); ?></p>
            <button class="button button-primary" id="ps-import-start" <?php disabled($state['complete']); ?>>Import / Resume</button>
        </div>
        <script>
        document.getElementById('ps-import-start').addEventListener('click', async function () {
            this.disabled = true;
            const status = document.getElementById('ps-import-status');
            try {
                while (true) {
                    const body = new URLSearchParams({action: 'ps_original_posts', _ajax_nonce: <?php echo wp_json_encode(wp_create_nonce('ps_original_posts')); ?>});
                    const response = await fetch(ajaxurl, {method: 'POST', body});
                    const result = await response.json();
                    if (!result.success) throw new Error(result.data || 'Import failed.');
                    status.textContent = result.data.complete ? 'Import complete.' : result.data.processed + ' of ' + result.data.total + ' records imported. Keep this page open.';
                    if (result.data.complete) break;
                }
            } catch (error) {
                status.textContent = 'Paused: ' + error.message + ' You can resume after resolving the error.';
                this.disabled = false;
            }
        });
        </script>
        <?php
    });
});
add_action('wp_ajax_ps_original_posts', function () {
    check_ajax_referer('ps_original_posts');
    try {
        wp_send_json_success(PS_Original_Post_Import::step());
    } catch (Throwable $error) {
        wp_send_json_error($error->getMessage());
    }
});

// Render imported upload URLs from local media without rewriting stored article HTML.
add_filter('the_content', function ($content) {
    if (!get_post_meta(get_the_ID(), '_ps_original_wxr_id', true)) {
        return $content;
    }
    $uploads = wp_upload_dir();
    return preg_replace_callback('~https?://papayasearch\.com/wp-content/uploads/([^\s"\'<>]+)~', function ($match) use ($uploads) {
        $path = html_entity_decode($match[1], ENT_QUOTES, 'UTF-8');
        return is_file($uploads['basedir'] . '/' . PS_Original_Post_Import::PREFIX . $path)
            ? $uploads['baseurl'] . '/' . PS_Original_Post_Import::PREFIX . $match[1]
            : $match[0];
    }, $content);
}, 20);
