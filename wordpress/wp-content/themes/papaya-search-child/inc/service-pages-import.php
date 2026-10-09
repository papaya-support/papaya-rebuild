<?php
/** Import the public Services submenu into the existing Services-Individual ACF template. */
defined('ABSPATH') || exit();

function ps_service_import_data()
{
    static $data;
    if (!isset($data)) {
        $data = require get_stylesheet_directory() . '/import-data/service-pages.php';
        $data['pages'] = array_map('ps_service_template_content', $data['pages']);
    }
    return $data;
}

function ps_service_import_media($url)
{
    $asset = ps_service_import_data()['media'][$url] ?? null;
    if (!$asset) {
        return new WP_Error('service_asset_missing', 'Unknown service image.');
    }
    $key = 'ps_service_media_' . md5($url);
    $id = (int) get_option($key);
    if ($id && get_post($id)) {
        return $id;
    }
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $source = get_stylesheet_directory() . '/import-data/services-media/' . $asset['file'];
    $allow_svg = function ($types) {
        $types['svg'] = 'image/svg+xml';
        return $types;
    };
    add_filter('upload_mimes', $allow_svg);
    $upload = wp_upload_bits($asset['file'], null, file_get_contents($source));
    remove_filter('upload_mimes', $allow_svg);
    if (!empty($upload['error'])) {
        return new WP_Error('service_image_upload', $upload['error']);
    }
    $id = wp_insert_attachment([
        'post_title' => $asset['alt'] ?: pathinfo($asset['file'], PATHINFO_FILENAME),
        'post_mime_type' => str_ends_with($asset['file'], '.svg') ? 'image/svg+xml' : wp_check_filetype($upload['file'])['type'],
        'post_status' => 'inherit',
    ], $upload['file'], 0, true);
    if (is_wp_error($id)) {
        return $id;
    }
    update_post_meta($id, '_wp_attachment_image_alt', $asset['alt']);
    wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $upload['file']));
    update_option($key, $id, false);
    return $id;
}

function ps_service_import_html($html, $ids)
{
    $processor = new WP_HTML_Tag_Processor($html);
    while ($processor->next_tag()) {
        if ($processor->get_tag() === 'IMG') {
            $src = $processor->get_attribute('src');
            $id = ps_service_import_media($src);
            if (is_wp_error($id)) {
                return $id;
            }
            $processor->set_attribute('src', wp_get_attachment_url($id));
            $processor->set_attribute('alt', get_post_meta($id, '_wp_attachment_image_alt', true));
            $processor->set_attribute('loading', 'lazy');
        }
        if ($processor->get_tag() === 'A') {
            $href = $processor->get_attribute('href');
            if (is_string($href) && str_starts_with($href, '/')) {
                $href = str_starts_with($href, '//') ? 'https:' . $href : 'https://papayasearch.com' . $href;
                $processor->set_attribute('href', $href);
            }
            foreach (ps_service_import_data()['pages'] as $page) {
                // Form fragments intentionally keep pointing to the working source form.
                if ($href === $page['source'] && !empty($ids[$page['path']])) {
                    $processor->set_attribute('href', home_url('/' . $page['path'] . '/'));
                }
            }
        }
    }
    return $processor->get_updated_html();
}

function ps_service_import_step()
{
    if (!function_exists('acf_update_field')) {
        return new WP_Error('acf_missing', 'Activate ACF before importing Services.');
    }
    $state = get_option('ps_service_import_v1', ['ids' => [], 'completed' => []]);
    $data = ps_service_import_data();
    if (count($state['completed']) === count($data['pages'])) {
        return $state;
    }
    $installed = ps_install_section_groups();
    if (is_wp_error($installed)) {
        return $installed;
    }
    // Add the extra questions as native, editable subfields of the existing FAQ Group.
    $parent = acf_get_field('field_ps_section_search_engine_marketing_section_faqs');
    if (!$parent) {
        return new WP_Error('service_fields_missing', 'Install the Services-Individual ACF Groups first.');
    }
    for ($i = 7; $i <= 9; $i++) {
        foreach (['question' => 'text', 'answer' => 'wysiwyg'] as $kind => $type) {
            $key = 'field_search_engine_marketing_faq_' . $kind . '_' . $i;
            if (!acf_get_field($key)) {
                acf_update_field(['key' => $key, 'name' => $kind . '_' . $i,
                    'label' => ucfirst($kind) . ' ' . $i, 'type' => $type,
                    'parent' => $parent['ID'], 'menu_order' => 2 + ($i - 1) * 2 + ($kind === 'answer' ? 1 : 0)]);
            }
        }
    }
    wp_cache_delete(acf_cache_key("acf_get_field_posts:" . $parent['ID']), 'acf');
    acf_flush_field_cache($parent);
    // Create the page hierarchy first so cross-service links resolve locally.
    foreach ($data['pages'] as $page) {
        if (!empty($state['ids'][$page['path']])) {
            continue;
        }
        $existing = get_page_by_path($page['path']);
        if ($existing) {
            $id = $existing->ID;
            add_post_meta($id, '_ps_before_service_import_v1', ['post' => (array) $existing, 'meta' => get_post_meta($id)], true);
        } else {
            $parent_page = get_page_by_path(dirname($page['path']));
            if (!$parent_page) {
                return new WP_Error('service_parent_missing', 'Missing parent page: ' . dirname($page['path']));
            }
            $id = wp_insert_post(['post_type' => 'page', 'post_status' => 'draft', 'post_title' => $page['title'],
                'post_name' => basename($page['path']), 'post_parent' => $parent_page->ID], true);
            if (is_wp_error($id)) {
                return $id;
            }
        }
        $state['ids'][$page['path']] = $id;
        update_option('ps_service_import_v1', $state, false);
    }
    foreach ($data['pages'] as $page) {
        if (in_array($page['path'], $state['completed'], true)) {
            continue;
        }
        $id = $state['ids'][$page['path']];
        $saved = ps_save_service_fields($page, $id, $state['ids']);
        if (is_wp_error($saved)) {
            return $saved;
        }
        update_post_meta($id, '_wp_page_template', 'page-templates/search-engine-marketing.php');
        update_post_meta($id, '_ps_live_service_source', $page['source']);
        update_post_meta($id, '_ps_service_xd_template_v2', 1);
        $result = wp_update_post(['ID' => $id, 'post_status' => 'publish'], true);
        if (is_wp_error($result)) {
            return $result;
        }
        $state['completed'][] = $page['path'];
        update_option('ps_service_import_v1', $state, false);
        clean_post_cache($id);
        if (count($state['completed']) === count($data['pages'])) {
            flush_rewrite_rules(false);
            ps_install_service_navigation();
        }
        return $state;
    }
    return $state;
}

add_action('admin_menu', function () {
    add_management_page('Import Service Pages', 'Import Service Pages', 'manage_options', 'ps-service-import', function () {
        echo '<div class="wrap"><h1>Import Service Pages</h1><p>Copy the nine live Services pages into the Services Individual template and its ACF fields. Existing editor changes are preserved after each page is imported.</p>';
        echo '<button class="button button-primary" id="ps-import-services">Import / Resume</button><p id="ps-services-status" role="status"></p></div>';
        $nonce = wp_create_nonce('ps_service_import');
        ?>
        <script>
        document.getElementById('ps-import-services').addEventListener('click', async function () {
            this.disabled = true;
            const status = document.getElementById('ps-services-status');
            try {
                let count = 0;
                do {
                    status.textContent = `Importing Services: ${count} of 9 completed…`;
                    const response = await fetch(ajaxurl, {method: 'POST', body: new URLSearchParams({action: 'ps_import_service_page', _ajax_nonce: <?php echo wp_json_encode($nonce); ?>})});
                    const result = await response.json();
                    if (!result.success) throw new Error(result.data);
                    count = result.data.completed.length;
                } while (count < 9);
                status.textContent = 'Complete: all 9 Service pages are ready.';
            } catch (error) { status.textContent = error.message; }
            this.disabled = false;
        });
        </script>
        <?php
    });
});
add_action('wp_ajax_ps_import_service_page', function () {
    check_ajax_referer('ps_service_import');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Administrator access required.', 403);
    }
    $result = ps_service_import_step();
    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
    }
    wp_send_json_success($result);
});

/** Empty source sections should not display SEM demo copy or placeholder columns. */
function ps_live_service_section_args($args, $type)
{
    if (!get_post_meta(get_the_ID(), '_ps_live_service_source', true)) {
        return $args;
    }
    $filter = function ($items) {
        return array_values(array_filter($items, function ($item) {
            return !empty($item['field']) && (
                ps_field_value($item['field']) !== '' ||
                (($item['type'] ?? '') === 'image' && !empty(ps_default_content($item['field'])['asset']))
            );
        }));
    };
    foreach (['items', 'before', 'after', 'heading'] as $key) {
        if (isset($args[$key])) {
            $args[$key] = $filter($args[$key]);
        }
    }
    if (isset($args['columns'])) {
        foreach ($args['columns'] as &$column) {
            $column['items'] = $filter($column['items']);
        }
        unset($column);
        $args['columns'] = array_values(array_filter($args['columns'], fn($column) => !empty($column['items'])));
    }
    if ($type === 'image-text' && !empty($args['columns'])) {
        $has_copy = false;
        foreach ($args['columns'] as $column) {
            foreach ($column['items'] as $item) {
                $has_copy = $has_copy || ($item['type'] ?? '') !== 'image';
            }
        }
        if (!$has_copy) {
            $args['columns'] = [];
        }
    }
    if (isset($args['stats'])) {
        $args['stats'] = array_values(array_filter(array_map($filter, $args['stats'])));
    }
    $args['skip'] = !array_filter(array_intersect_key($args, array_flip(['items', 'before', 'after', 'heading', 'columns', 'stats'])));
    return $args;
}

function ps_save_service_fields($page, $id, $ids)
{
    foreach (ps_section_groups()['search-engine-marketing'] as $section) {
        $values = [];
        foreach ($section['fields'] as $legacy => $name) {
            $value = $page['groups'][$section['name']][$name] ?? '';
            if ($section['name'] === 'section_faqs' && preg_match('/^(question|answer)(?:_(\d+))?$/', $name, $match)) {
                $index = isset($match[2]) ? (int) $match[2] - 1 : 0;
                $value = $page['faqs'][$index][$match[1]] ?? '';
            }
            if ($name === 'image' && $value) {
                $value = ps_service_import_media($value);
            } elseif (is_string($value) && str_contains($value, '<')) {
                $value = ps_service_import_html($value, $ids);
            }
            if (is_wp_error($value)) {
                return $value;
            }
            $values['field_' . $legacy] = $value;
        }
        update_field('field_ps_section_search_engine_marketing_' . $section['name'], $values, $id);
    }
    return true;
}
