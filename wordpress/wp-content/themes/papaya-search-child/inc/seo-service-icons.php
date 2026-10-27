<?php
/** Solid brand SVGs, imported once and editable on each SEO service card. */
defined('ABSPATH') || exit();

function ps_seo_service_icons() {
    return [1 => 'tree', 2 => 'papaya-half', 3 => 'seeds', 4 => 'papaya-slice', 5 => 'papaya-stack', 6 => 'arrow'];
}

function ps_seo_service_icon_url($index) {
    $meta = 'section_seo_services_icon_' . $index;
    if (metadata_exists('post', get_the_ID(), $meta)) {
        return wp_get_attachment_url((int) get_post_meta(get_the_ID(), $meta, true)) ?: '';
    }
    $icons = ps_seo_service_icons();
    $path = '/assets/icons/seo-services/' . $icons[$index] . '.svg';
    return is_file(get_stylesheet_directory() . $path) ? get_stylesheet_directory_uri() . $path : '';
}

function ps_install_seo_service_icons() {
    if (get_option('ps_seo_service_icons_v1')) return true;
    if (!function_exists('acf_update_field')) return new WP_Error('acf_missing', 'ACF is required.');
    $parent = acf_get_field('field_ps_seo_services');
    $pages = get_posts(['post_type' => 'page', 'post_status' => 'any', 'name' => 'seo', 'numberposts' => -1]);
    if (!$parent || !$pages) return new WP_Error('seo_missing', 'SEO services fields are required.');
    foreach (ps_seo_service_icons() as $name) {
        if (!is_file(get_stylesheet_directory() . '/assets/icons/seo-services/' . $name . '.svg')) {
            return new WP_Error('icon_missing', 'Missing bundled icon: ' . $name);
        }
    }
    foreach (ps_seo_service_icons() as $index => $name) {
        $source = get_stylesheet_directory() . '/assets/icons/seo-services/' . $name . '.svg';
        if (!is_file($source)) return new WP_Error('icon_missing', 'Missing bundled icon: ' . $name);
        $option = 'ps_seo_service_icon_' . $index;
        $attachment = (int) get_option($option);
        if (!$attachment || !get_post($attachment)) {
            // This allowance is limited to these reviewed, bundled brand assets.
            $allow_svg = function ($types) { $types['svg'] = 'image/svg+xml'; return $types; };
            add_filter('upload_mimes', $allow_svg);
            $upload = wp_upload_bits('papaya-seo-' . $name . '-sage.svg', null, file_get_contents($source));
            remove_filter('upload_mimes', $allow_svg);
            if (!empty($upload['error'])) return new WP_Error('icon_upload', $upload['error']);
            $attachment = wp_insert_attachment([
                'post_title' => 'Papaya Search — Solid ' . ucwords(str_replace('-', ' ', $name)) . ' (Sage)',
                'post_mime_type' => 'image/svg+xml', 'post_status' => 'inherit',
            ], $upload['file'], 0, true);
            if (is_wp_error($attachment)) return $attachment;
            update_option($option, $attachment, false);
        }
        $key = 'field_ps_seo_services_icon_' . $index;
        $field = acf_update_field([
            'key' => $key, 'name' => 'icon_' . $index, 'label' => 'Card ' . $index . ' Icon',
            'type' => 'image', 'return_format' => 'id', 'preview_size' => 'thumbnail',
            'required' => 0, 'parent' => $parent['ID'], 'menu_order' => 2 + ($index - 1) * 3,
        ]);
        if (empty($field['ID'])) return new WP_Error('icon_field', 'Could not create the icon field.');
        foreach (['heading', 'description'] as $offset => $part) {
            $text_field = acf_get_field('field_ps_seo_services_' . $part . '_' . $index);
            if ($text_field) {
                $text_field['menu_order'] = 3 + ($index - 1) * 3 + $offset;
                acf_update_field($text_field);
            }
        }
        foreach ($pages as $page) {
            $meta = 'section_seo_services_icon_' . $index;
            if (!metadata_exists('post', $page->ID, $meta)) {
                update_post_meta($page->ID, $meta, $attachment);
                update_post_meta($page->ID, '_' . $meta, $key);
            }
        }
    }
    update_option('ps_seo_service_icons_v1', 1, false);
    return true;
}

add_action('admin_init', function () {
    if (current_user_can('manage_options')) ps_install_seo_service_icons();
}, 101);
