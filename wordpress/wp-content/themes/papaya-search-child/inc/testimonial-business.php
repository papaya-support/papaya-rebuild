<?php
/** Add optional business names to every testimonial field group. */
defined('ABSPATH') || exit();

function ps_install_testimonial_business()
{
    if (get_option('ps_testimonial_business_v1')) {
        return true;
    }
    if (!function_exists('acf_get_field')) {
        return new WP_Error('acf_missing', 'ACF is required for testimonial fields.');
    }
    foreach (ps_section_groups() as $slug => $sections) {
        foreach ($sections as $section) {
            if ($section['template'] !== 'testimonial') {
                continue;
            }
            $parent = acf_get_field('field_ps_section_' . str_replace('-', '_', $slug) . '_' . $section['name']);
            if (empty($parent['ID'])) {
                return new WP_Error('testimonial_missing', 'The testimonial group is not installed yet.');
            }
            $key = 'field_' . str_replace('-', '_', $slug) . '_testimonial_business_name';
            if (!acf_get_field($key)) {
                $field = acf_update_field([
                    'key' => $key,
                    'label' => 'Business Name',
                    'name' => 'business_name',
                    'type' => 'text',
                    'parent' => $parent['ID'],
                    'menu_order' => 3,
                    'required' => 0,
                    'instructions' => 'Optional business name displayed after the client name.',
                ]);
                if (empty($field['ID'])) {
                    return new WP_Error('field_creation_failed', 'Could not create the Business Name field.');
                }
            }
        }
    }
    foreach (get_posts(['post_type' => 'page', 'post_status' => 'any', 'numberposts' => -1,
        'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/home.php']) as $page) {
        if (!metadata_exists('post', $page->ID, 'section_testimonial_business_name')) {
            $client = get_post_meta($page->ID, 'section_testimonial_attribution', true);
            if (!$client) {
                $client = get_post_meta($page->ID, 'home_7393b65da373', true);
            }
            if (stripos(wp_strip_all_tags($client), 'Shmuel Pearl') !== false) {
                update_post_meta($page->ID, 'section_testimonial_business_name', 'Pearl Solutions');
                update_post_meta($page->ID, '_section_testimonial_business_name', 'field_home_testimonial_business_name');
            }
        }
    }
    update_option('ps_testimonial_business_v1', 1, false);
    return true;
}

add_action('admin_init', function () {
    if (current_user_can('manage_options')) {
        ps_install_testimonial_business();
    }
}, 75);
