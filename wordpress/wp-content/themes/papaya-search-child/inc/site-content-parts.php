<?php
/** Separate native Site Content editors without moving or replacing saved footer values. */
defined('ABSPATH') || exit();

function ps_install_site_content_parts()
{
    if (get_option('ps_site_content_parts_v1')) {
        return true;
    }
    if (!function_exists('acf_import_field_group')) {
        return new WP_Error('acf_missing', 'Activate Advanced Custom Fields first.');
    }
    $footer_id = ps_shared_id();
    $footer_group = acf_get_field_group('group_ps_shared');
    if (!$footer_id || !get_post($footer_id) || empty($footer_group['ID'])) {
        return new WP_Error('site_content_missing', 'Import the site content before separating Header and Footer.');
    }
    if (!get_option('ps_site_content_parts_backup_v1')) {
        update_option('ps_site_content_parts_backup_v1', [
            'title' => get_the_title($footer_id),
            'footer_id' => $footer_id,
            'field_group' => $footer_group,
        ], false);
    }
    $header_id = (int) get_option('ps_header_content_id');
    if (!$header_id || !get_post($header_id)) {
        $header_id = wp_insert_post([
            'post_type' => 'ps_site_content',
            'post_status' => 'publish',
            'post_title' => 'Header',
        ], true);
        if (is_wp_error($header_id)) {
            return $header_id;
        }
        update_option('ps_header_content_id', $header_id, false);
    }
    $result = wp_update_post(['ID' => $footer_id, 'post_title' => 'Footer'], true);
    if (is_wp_error($result)) {
        return $result;
    }
    // Reuse the original entry and field keys, so all existing footer edits survive.
    $footer_group['title'] = 'Footer — Site Content';
    $footer_group['location'] = [[['param' => 'post', 'operator' => '==', 'value' => (string) $footer_id]]];
    if (!acf_update_field_group($footer_group)) {
        return new WP_Error('footer_group_failed', 'Could not update the Footer field group.');
    }
    if (!acf_get_field_group('group_ps_site_header')) {
        $header_group = acf_import_field_group([
            'key' => 'group_ps_site_header',
            'title' => 'Header — Site Content',
            'active' => true,
            'location' => [[['param' => 'post', 'operator' => '==', 'value' => (string) $header_id]]],
            'fields' => [[
                'key' => 'field_ps_section_header',
                'name' => 'section_header',
                'label' => 'Header Content',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_ps_header_logo',
                        'name' => 'logo',
                        'label' => 'Logo',
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'instructions' => 'Optional replacement for the current header logo. Leave empty to keep the design logo. Alternative text comes from the Media Library.',
                    ],
                    [
                        'key' => 'field_ps_header_navigation_note',
                        'name' => '',
                        'label' => 'Navigation and Get Started Button',
                        'type' => 'message',
                        'message' => 'Edit the menu assigned to Header Navigation under Appearance → Menus. Its Get Started item controls the header button label and link.',
                    ],
                ],
            ]],
        ]);
        if (empty($header_group['ID'])) {
            return new WP_Error('header_group_failed', 'Could not create the Header field group.');
        }
    }
    update_option('ps_site_content_parts_v1', 1, false);
    return true;
}

function ps_header_logo()
{
    $logo = [
        'url' => get_stylesheet_directory_uri() . '/assets/brand.svg',
        'alt' => 'Papaya Search — Be seen. Stay ahead. Grow smarter.',
    ];
    $header_id = (int) get_option('ps_header_content_id');
    $image_id = $header_id ? (int) get_post_meta($header_id, 'section_header_logo', true) : 0;
    $url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : false;
    if ($url) {
        $logo['url'] = $url;
        $logo['alt'] = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    }
    return $logo;
}

add_action('admin_init', function () {
    if (!current_user_can('manage_options')) {
        return;
    }
    $result = ps_install_site_content_parts();
    if (is_wp_error($result)) {
        add_action('admin_notices', function () use ($result) {
            echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
        });
    }
}, 40);
