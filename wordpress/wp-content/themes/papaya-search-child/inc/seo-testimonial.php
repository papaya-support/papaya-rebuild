<?php
/** Editable content for the SEO testimonial section. */
defined('ABSPATH') || exit();

function ps_seo_testimonial_defaults()
{
    return [
        'heading' => 'Real SEO Results. Trusted Client Relationships.',
        'description' => 'Strong SEO partnerships are built on more than rankings. We focus on meaningful improvements to search visibility, website performance, and the customer experience, while building the trust and collaboration that help businesses move forward.',
        'quote' => 'Papaya Search’s expertise, knowledge, and attention to detail were exactly what we needed to take our search marketing to the next level. He was attentive, dedicated and successful in meeting our goals in a timely manner.',
        'client_name' => 'Shmuel Pearl',
        'business_name' => 'Pearl Solutions',
    ];
}

function ps_install_seo_testimonial()
{
    if (get_option('ps_seo_testimonial_v1')) {
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
    $group = acf_update_field_group(['key' => 'group_ps_seo_testimonial', 'title' => 'SEO — Testimonial', 'location' => $locations, 'active' => true]);
    $parent = acf_update_field(['key' => 'field_ps_seo_testimonial', 'name' => 'section_seo_testimonial', 'label' => 'Testimonial', 'type' => 'group', 'layout' => 'block', 'parent' => $group['ID']]);
    $position = 0;
    foreach (ps_seo_testimonial_defaults() as $name => $value) {
        $key = 'field_ps_seo_testimonial_' . $name;
        $field = acf_update_field(['key' => $key, 'name' => $name, 'label' => ucwords(str_replace('_', ' ', $name)), 'type' => in_array($name, ['description', 'quote'], true) ? 'textarea' : 'text', 'rows' => 3, 'parent' => $parent['ID'], 'menu_order' => $position++]);
        if (empty($field['ID'])) {
            return new WP_Error('field_missing', 'Could not create SEO testimonial fields.');
        }
        foreach ($pages as $page) {
            $meta = 'section_seo_testimonial_' . $name;
            if (!metadata_exists('post', $page->ID, $meta)) {
                update_post_meta($page->ID, $meta, $value);
                update_post_meta($page->ID, '_' . $meta, $key);
            }
        }
    }
    update_option('ps_seo_testimonial_v1', 1, false);
    return true;
}

add_action('admin_init', function () {
    if (current_user_can('manage_options')) {
        ps_install_seo_testimonial();
    }
}, 87);
