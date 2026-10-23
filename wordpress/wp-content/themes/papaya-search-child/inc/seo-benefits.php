<?php
/** Editable content for the SEO benefits section. */
defined('ABSPATH') || exit();

function ps_seo_benefits_defaults()
{
    return [
        'heading' => 'Why Invest in SEO for Your Business?',
        'description' => 'SEO helps your business connect with people actively searching for your products or services. A thoughtful strategy can improve your visibility, strengthen your website, and create opportunities for sustainable growth.',
        'heading_1' => 'Increase Organic Visibility',
        'description_1' => 'Help potential customers discover your business in relevant search results.',
        'heading_2' => 'Attract Qualified Traffic',
        'description_2' => 'Reach people actively searching for the products, services, and solutions you offer.',
        'heading_3' => 'Build Trust & Credibility',
        'description_3' => 'Strengthen your online presence with useful content and a better website experience.',
        'heading_4' => 'Support Long-Term Growth',
        'description_4' => 'Build a stronger organic search foundation that creates opportunities over time.',
    ];
}

function ps_install_seo_benefits()
{
    if (get_option('ps_seo_benefits_v1')) {
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
    $group = acf_update_field_group(['key' => 'group_ps_seo_benefits', 'title' => 'SEO — Benefits', 'location' => $locations, 'active' => true]);
    $parent = acf_update_field(['key' => 'field_ps_seo_benefits', 'name' => 'section_seo_benefits', 'label' => 'Why Invest in SEO', 'type' => 'group', 'layout' => 'block', 'parent' => $group['ID']]);
    $position = 0;
    foreach (ps_seo_benefits_defaults() as $name => $value) {
        $key = 'field_ps_seo_benefits_' . $name;
        $field = acf_update_field(['key' => $key, 'name' => $name, 'label' => ucwords(str_replace('_', ' ', $name)), 'type' => str_starts_with($name, 'description') ? 'textarea' : 'text', 'rows' => 3, 'parent' => $parent['ID'], 'menu_order' => $position++]);
        if (empty($field['ID'])) {
            return new WP_Error('field_missing', 'Could not create SEO benefits fields.');
        }
        foreach ($pages as $page) {
            $meta = 'section_seo_benefits_' . $name;
            if (!metadata_exists('post', $page->ID, $meta)) {
                update_post_meta($page->ID, $meta, $value);
                update_post_meta($page->ID, '_' . $meta, $key);
            }
        }
    }
    update_option('ps_seo_benefits_v1', 1, false);
    return true;
}

add_action('admin_init', function () {
    if (current_user_can('manage_options')) {
        ps_install_seo_benefits();
    }
}, 85);
