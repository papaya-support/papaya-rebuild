<?php
/** Shared ACF Pro options and one-time preservation of the former Site Content values. */
defined('ABSPATH') || exit();

add_action('acf/init', function () {
    if (!function_exists('acf_add_options_page')) return;
    if (get_option('ps_site_options_tabs_v1')) {
        acf_add_options_page([
            'page_title' => 'Site Options', 'menu_title' => 'Site Options',
            'menu_slug' => 'papaya-header-options', 'post_id' => 'ps_site_options',
            'capability' => 'manage_options', 'redirect' => false,
        ]);
        return;
    }
    acf_add_options_page([
        'page_title' => 'Header', 'menu_title' => 'Site Options',
        'menu_slug' => 'papaya-header-options', 'post_id' => 'ps_header',
        'capability' => 'manage_options', 'redirect' => false,
    ]);
    acf_add_options_page([
        'page_title' => 'Footer', 'menu_title' => 'Footer',
        'menu_slug' => 'papaya-footer-options', 'post_id' => 'ps_footer',
        'parent_slug' => 'papaya-header-options', 'capability' => 'manage_options',
    ]);
    acf_add_options_page([
        'page_title' => 'Testimonials', 'menu_title' => 'Testimonials',
        'menu_slug' => 'papaya-testimonial-options', 'post_id' => 'ps_testimonials',
        'parent_slug' => 'papaya-header-options', 'capability' => 'manage_options',
    ]);
}, 5);

function ps_option_meta($scope, $name) {
    if (!get_option('ps_acf_options_v1')) return null;
    if (get_option('ps_site_options_tabs_v1')) {
        $value = get_option('ps_site_options_' . $name, null);
        if ($value !== null) return $value;
    }
    return get_option('ps_' . $scope . '_' . $name, null);
}

function ps_footer_option_value($legacy_name) {
    $binding = ps_grouped_field_binding($legacy_name);
    if ($binding && $binding[0] === 'section_footer') {
        $value = ps_option_meta('footer', 'section_footer_' . $binding[1]);
        if ($value !== null) return $value;
    }
    return ps_option_meta('footer', $legacy_name);
}

function ps_copy_site_meta_to_options($post_id, $scope) {
    if (!$post_id) return;
    foreach (get_post_meta($post_id) as $name => $_) {
        if (str_starts_with($name, '_edit_')) continue;
        $option = str_starts_with($name, '_')
            ? '_ps_' . $scope . '_' . substr($name, 1)
            : 'ps_' . $scope . '_' . $name;
        if (get_option($option, null) === null) {
            update_option($option, get_post_meta($post_id, $name, true), false);
        }
    }
}

function ps_write_testimonial_options($rows) {
    update_option('ps_testimonials_section_testimonials_items', count($rows), false);
    update_option('_ps_testimonials_section_testimonials_items', 'field_ps_testimonial_option_items', false);
    foreach ($rows as $index => $row) {
        foreach (['quote', 'client_name', 'business_name', 'client_role', 'image', 'image_url', 'quote_mark'] as $name) {
            $key = 'ps_testimonials_section_testimonials_items_' . $index . '_' . $name;
            update_option($key, $row[$name] ?? '', false);
            update_option('_' . $key, 'field_ps_testimonial_option_' . $name, false);
        }
    }
}

function ps_testimonial_import_rows() {
    $file = get_stylesheet_directory() . '/assets/testimonials-import.json';
    $items = json_decode((string) file_get_contents($file), true);
    $items = is_array($items) ? $items : [];
    $rows = [];
    $seen = [];
    foreach ($items as $item) {
        $quote = trim((string) ($item['quote'] ?? ''));
        $name = trim((string) ($item['client_name'] ?? ''));
        if ($quote === '' || $name === '' || isset($seen[strtolower($name)])) continue;
        $seen[strtolower($name)] = true;
        $rows[] = [
            'quote' => $quote,
            'client_name' => $name,
            'business_name' => '',
            'client_role' => (string) ($item['client_role'] ?? ''),
            'image' => 0,
            'image_url' => esc_url_raw((string) ($item['image_url'] ?? '')),
            'quote_mark' => '“',
        ];
    }
    foreach (ps_shared_testimonial_ids() as $id) {
        $quote = trim((string) get_post_meta($id, 'quote', true));
        $name = trim((string) get_post_meta($id, 'client_name', true));
        if ($quote === '' || ($name !== '' && isset($seen[strtolower($name)]))) continue;
        if ($name !== '') $seen[strtolower($name)] = true;
        $rows[] = [
            'quote' => $quote,
            'client_name' => $name,
            'business_name' => (string) get_post_meta($id, 'business_name', true),
            'client_role' => (string) get_post_meta($id, 'client_role', true),
            'image' => (int) get_post_meta($id, 'image', true),
            'image_url' => '',
            'quote_mark' => (string) get_post_meta($id, 'quote_mark', true),
        ];
    }
    return $rows;
}

function ps_install_acf_options() {
    if (get_option('ps_acf_options_v1')) return true;
    if (!function_exists('acf_update_field') || !function_exists('acf_get_setting') || !acf_get_setting('pro')) return false;
    $groups = [
        ['group_ps_site_header', 'papaya-header-options', 'header', (int) get_option('ps_header_content_id')],
        ['group_ps_shared', 'papaya-footer-options', 'footer', ps_shared_id()],
        ['group_ps_shared_testimonials', 'papaya-testimonial-options', 'testimonials', ps_testimonial_site_id()],
    ];
    foreach ($groups as [$key, , , $post_id]) {
        if (!acf_get_field_group($key) || !$post_id || !get_post($post_id)) return false;
    }

    foreach ($groups as [$key, $page, $scope, $post_id]) {
        ps_copy_site_meta_to_options($post_id, $scope);
        $group = acf_get_field_group($key);
        $group['location'] = [[['param' => 'options_page', 'operator' => '==', 'value' => $page]]];
        $group['title'] = ucfirst($scope) . ' — Options';
        acf_update_field_group($group);
    }

    $parent = acf_get_field('field_ps_shared_testimonials');
    $repeater = acf_update_field([
        'key' => 'field_ps_testimonial_option_items', 'name' => 'items',
        'label' => 'Testimonials', 'type' => 'repeater', 'layout' => 'block',
        'button_label' => 'Add Testimonial', 'parent' => $parent['ID'],
        'menu_order' => 12,
    ]);
    foreach ([
        'quote' => ['Quote', 'textarea'],
        'client_name' => ['Client Name', 'text'],
        'business_name' => ['Business Name', 'text'],
        'client_role' => ['Client Role', 'text'],
        'image' => ['Image', 'image'],
        'quote_mark' => ['Quote Mark', 'text'],
    ] as $index => $field) {
        acf_update_field([
            'key' => 'field_ps_testimonial_option_' . $index,
            'name' => $index, 'label' => $field[0], 'type' => $field[1],
            'parent' => $repeater['ID'], 'return_format' => $index === 'image' ? 'id' : null,
        ]);
    }
    $old_selection = acf_get_field('field_ps_shared_testimonials_selected');
    if ($old_selection) {
        $archive = acf_get_field_group('group_ps_archived_testimonial_selection');
        if (!$archive) $archive = acf_update_field_group(['key' => 'group_ps_archived_testimonial_selection', 'title' => 'Archived Testimonial Selection', 'active' => false, 'location' => []]);
        $old_selection['parent'] = $archive['ID'];
        acf_update_field($old_selection);
    }
    if (get_option('ps_testimonials_section_testimonials_items', null) === null) {
        ps_write_testimonial_options(ps_testimonial_import_rows());
    }
    update_option('ps_acf_options_v1', 1, false);
    return true;
}

add_action('init', function () {
    if (get_option('ps_shared_testimonials_v1') && get_option('ps_site_content_parts_v1')) ps_install_acf_options();
}, 160);

// ACF may create a new options group when moving the former post-specific group.
// Keep its existing subfields attached to the group that owns the options editor.
add_action('init', function () {
    if (!get_option('ps_acf_options_v1') || get_option('ps_site_options_tabs_v1')) return;
    if (get_option('_ps_testimonials_section_testimonials', null) === null) {
        update_option('ps_testimonials_section_testimonials', '', false);
        update_option('_ps_testimonials_section_testimonials', 'field_ps_shared_testimonials', false);
    }
    $group = acf_get_field_group('group_ps_shared_testimonials');
    $field = acf_get_field('field_ps_shared_testimonials');
    if (!empty($group['ID']) && !empty($field['ID']) && (int) $field['parent'] !== (int) $group['ID']) {
        $field['parent'] = $group['ID'];
        acf_update_field(wp_slash($field));
    }
}, 165);

add_action('admin_menu', function () {
    if (get_option('ps_acf_options_v1')) remove_menu_page('edit.php?post_type=ps_site_content');
}, 99);

add_action('init', function () {
    if (!get_option('ps_acf_options_v1') || !get_option('ps_faq_repeater_v1') || get_option('ps_acf_options_duplicate_cleanup_v1')) return;
    foreach ([
        'acf-field-group' => ['group_ps_shared_testimonials'],
        'acf-field' => [
            'field_ps_shared_testimonials', 'field_ps_faq_items',
            'field_ps_faq_item_question', 'field_ps_faq_item_answer',
        ],
    ] as $type => $keys) {
        foreach ($keys as $key) {
            $posts = get_posts([
                'post_type' => $type, 'post_status' => 'publish', 'name' => $key,
                'numberposts' => -1, 'orderby' => 'ID', 'order' => 'DESC',
            ]);
            array_shift($posts);
            foreach ($posts as $post) wp_delete_post($post->ID, true);
        }
    }
    update_option('ps_acf_options_duplicate_cleanup_v1', 1, false);
}, 190);

add_action('init', function () {
    if (!get_option('ps_acf_options_duplicate_cleanup_v1') || get_option('ps_acf_options_trashed_field_cleanup_v1')) return;
    global $wpdb;
    foreach (['group_ps_shared_testimonials', 'field_ps_shared_testimonials', 'field_ps_faq_items', 'field_ps_faq_item_question', 'field_ps_faq_item_answer'] as $key) {
        $ids = $wpdb->get_col($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_status = 'trash' AND post_name LIKE %s",
            $wpdb->esc_like($key) . '%__trashed%'
        ));
        foreach ($ids as $id) wp_delete_post((int) $id, true);
    }
    update_option('ps_acf_options_trashed_field_cleanup_v1', 1, false);
}, 200);

add_action('init', function () {
    if (!get_option('ps_acf_options_v1') || get_option('ps_acf_options_field_hierarchy_v1')) return;
    $parent = acf_get_field('field_ps_shared_testimonials');
    $archive = acf_get_field_group('group_ps_archived_testimonial_selection');
    if (empty($parent['ID'])) return;
    global $wpdb;
    $fields = $wpdb->get_results($wpdb->prepare(
        "SELECT ID, post_name, post_parent FROM {$wpdb->posts} WHERE post_type = 'acf-field' AND post_status = 'publish' AND post_name LIKE %s",
        $wpdb->esc_like('field_ps_shared_testimonials_') . '%'
    ));
    foreach ($fields as $field) {
        $target = $field->post_name === 'field_ps_shared_testimonials_selected'
            ? (int) ($archive['ID'] ?? 0)
            : (int) $parent['ID'];
        if ($target && (int) $field->post_parent !== $target) {
            wp_update_post(['ID' => (int) $field->ID, 'post_parent' => $target]);
        }
    }
    update_option('ps_acf_options_field_hierarchy_v1', 1, false);
}, 210);

// Remove duplicate people from the first local seed while retaining the old CPT records.
add_action('init', function () {
    if (!get_option('ps_acf_options_v1') || get_option('ps_acf_testimonial_seed_deduplicated_v1')) return;
    $rows = ps_testimonial_rows();
    if (count($rows) === 15 &&
        ($rows[0]['client_name'] ?? '') === ($rows[2]['client_name'] ?? '') &&
        ($rows[1]['client_name'] ?? '') === ($rows[3]['client_name'] ?? '')) {
        ps_write_testimonial_options(array_slice($rows, 2));
    }
    update_option('ps_acf_testimonial_seed_deduplicated_v1', 1, false);
}, 220);

/** Copy the supplied photos into the Media Library and replace URL-only rows. */
add_action('init', function () {
    if (!get_option('ps_acf_options_v1') || get_option('ps_testimonial_media_v1')) return;
    require_once ABSPATH . 'wp-admin/includes/image.php';
    global $wpdb;
    $rows = ps_testimonial_rows();
    $complete = true;
    foreach ($rows as $index => $row) {
        if ((int) ($row['image'] ?? 0) || empty($row['image_url'])) continue;
        $filename = basename((string) wp_parse_url($row['image_url'], PHP_URL_PATH));
        $source = get_stylesheet_directory() . '/assets/testimonials/' . $filename;
        if (!$filename || !is_file($source) || !getimagesize($source)) {
            $complete = false;
            continue;
        }
        $checksum = md5_file($source);
        $asset_key = 'ps_testimonial_media_' . $checksum;
        $attachment = (int) get_option($asset_key);
        if (!$attachment || !wp_attachment_is_image($attachment)) {
            $matches = $wpdb->get_col($wpdb->prepare(
                "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s",
                '%' . $wpdb->esc_like($filename)
            ));
            foreach ($matches as $match) {
                $path = get_attached_file((int) $match);
                if (wp_attachment_is_image((int) $match) && $path && is_file($path) && md5_file($path) === $checksum) {
                    $attachment = (int) $match;
                    break;
                }
            }
        }
        if (!$attachment) {
            $bytes = file_get_contents($source);
            $upload = wp_upload_bits('papaya-testimonial-' . $filename, null, $bytes);
            if (!empty($upload['error'])) { $complete = false; continue; }
            $attachment = wp_insert_attachment([
                'post_title' => $row['client_name'] . ' testimonial image',
                'post_mime_type' => wp_check_filetype($upload['file'])['type'],
                'post_status' => 'inherit',
            ], $upload['file'], 0, true);
            if (is_wp_error($attachment)) { $complete = false; continue; }
            wp_update_attachment_metadata($attachment, wp_generate_attachment_metadata($attachment, $upload['file']));
        }
        if (!get_post_meta($attachment, '_wp_attachment_image_alt', true)) {
            update_post_meta($attachment, '_wp_attachment_image_alt', $row['client_name']);
        }
        update_option($asset_key, $attachment, false);
        $key = 'ps_testimonials_section_testimonials_items_' . $index . '_image';
        update_option($key, $attachment, false);
        update_option('_' . $key, 'field_ps_testimonial_option_image', false);
    }
    if (!$complete) return;
    $url_field = acf_get_field('field_ps_testimonial_option_image_url');
    if (!empty($url_field['ID'])) wp_delete_post((int) $url_field['ID'], true);
    update_option('ps_testimonial_media_v1', 1, false);
}, 230);
