<?php
/** Editable content for the SEO approach section. */
defined('ABSPATH') || exit();

function ps_seo_approach_defaults()
{
    return [
        'heading' => 'A Smarter, More Adaptable Approach to SEO',
        'description' => 'Effective SEO isn\'t a one-time checklist. As an experienced SEO agency, we start by understanding your website and business goals, identifying the opportunities that matter most, and continually refining your strategy based on performance and changes in search.',
        'heading_1' => 'Discover & Prioritize',
        'description_1' => 'We begin by reviewing your website, current search performance, competitive landscape, and business goals. These insights help us identify opportunities and establish a clear plan of action.',
        'heading_2' => 'Optimize & Implement',
        'description_2' => 'We turn strategy into action through prioritized technical, on-page, and content improvements. Our recommendations focus on creating a stronger website experience and improving organic search performance.',
        'heading_3' => 'Measure & Evolve',
        'description_3' => 'We monitor performance, evaluate results, and adjust priorities as your business and the search landscape evolve. Regular reporting and collaboration help keep your SEO strategy moving forward.',
    ];
}

function ps_install_seo_approach()
{
    if (get_option('ps_seo_approach_v1')) {
        return true;
    }
    if (!function_exists('acf_update_field_group')) {
        return new WP_Error('acf_missing', 'ACF is required.');
    }
    $pages = get_posts(['post_type' => 'page', 'post_status' => 'any', 'name' => 'seo', 'numberposts' => -1]);
    if (!$pages) {
        return new WP_Error('seo_missing', 'The SEO page is not available yet.');
    }
    $locations = [];
    foreach ($pages as $page) {
        $locations[] = [['param' => 'page', 'operator' => '==', 'value' => (string) $page->ID]];
    }
    $group = acf_update_field_group(['key' => 'group_ps_seo_approach', 'title' => 'SEO — Approach', 'location' => $locations, 'active' => true]);
    $parent = acf_update_field(['key' => 'field_ps_seo_approach', 'name' => 'section_seo_approach', 'label' => 'SEO Approach', 'type' => 'group', 'layout' => 'block', 'parent' => $group['ID']]);
    $position = 0;
    foreach (ps_seo_approach_defaults() as $name => $value) {
        $key = 'field_ps_seo_approach_' . $name;
        $field = acf_update_field(['key' => $key, 'name' => $name, 'label' => ucwords(str_replace('_', ' ', $name)), 'type' => str_starts_with($name, 'description') ? 'textarea' : 'text', 'rows' => 3, 'parent' => $parent['ID'], 'menu_order' => $position++]);
        if (empty($field['ID'])) {
            return new WP_Error('field_missing', 'Could not create SEO approach fields.');
        }
        foreach ($pages as $page) {
            $meta = 'section_seo_approach_' . $name;
            if (!metadata_exists('post', $page->ID, $meta)) {
                update_post_meta($page->ID, $meta, $value);
                update_post_meta($page->ID, '_' . $meta, $key);
            }
        }
    }
    update_option('ps_seo_approach_v1', 1, false);
    return true;
}

add_action('admin_init', function () {
    if (current_user_can('manage_options')) {
        ps_install_seo_approach();
    }
}, 88);
