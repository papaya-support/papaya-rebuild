<?php
/** Consistent optional controls for all testimonial groups; preserve existing field names and content. */
defined('ABSPATH') || exit();
function ps_install_testimonial_fields() {
    if (get_option('ps_testimonial_fields_v1')) return true;
    if (!function_exists('acf_get_field')) return new WP_Error('acf_missing', 'ACF is required.');
    $groups = [
        ['field_ps_section_home_section_testimonial', 'home', 'home_c71150b00266', 'home_71e8d5ebfb3e', 'home_7393b65da373'],
        ['field_ps_section_case_study_detail_section_testimonial', 'case_study_detail', 'case_study_detail_7e19b95f2be4', 'case_study_detail_3f5be18822e8', 'case_study_detail_74dd8a1b256d'],
        ['field_ps_seo_testimonial', 'seo', 'ps_seo_testimonial_quote_mark', 'ps_seo_testimonial_quote', 'ps_seo_testimonial_client_name'],
    ];
    foreach ($groups as [$parent_key, $slug, $mark, $quote, $client]) {
        $parent = acf_get_field($parent_key);
        if (empty($parent['ID'])) return new WP_Error('missing_group', 'Testimonial group not installed.');
        $seo = $slug === 'seo';
        $fields = [
            ['heading', 'Heading', 'text', $seo ? 'ps_seo_testimonial_heading' : $slug . '_testimonial_heading'],
            [$seo ? 'description' : 'intro_description', 'Description', 'textarea', $seo ? 'ps_seo_testimonial_description' : $slug . '_testimonial_intro_description'],
            ['quote_mark', 'Quote Mark', 'text', $mark],
            [$seo ? 'quote' : 'description', 'Quote', 'textarea', $quote],
            [$seo ? 'client_name' : 'attribution', 'Client Name', 'text', $client],
            ['business_name', 'Business Name', 'text', $seo ? 'ps_seo_testimonial_business_name' : $slug . '_testimonial_business_name'],
            ['button', 'Button', 'link', $slug . '_testimonial_button'],
        ];
        foreach ($fields as $order => [$name, $label, $type, $key]) {
            $field = acf_get_field('field_' . $key) ?: [];
            $field = acf_update_field(array_merge($field, ['key'=>'field_' . $key, 'name'=>$name, 'label'=>$label, 'type'=>$type, 'parent'=>$parent['ID'], 'menu_order'=>$order, 'required'=>0, 'return_format'=>'array']));
            if (empty($field['ID'])) return new WP_Error('field_failed', 'Could not update testimonial fields.');
        }
    }
    $cases = get_page_by_path('case-studies');
    foreach (get_posts(['post_type'=>'page','post_status'=>'any','name'=>'seo','numberposts'=>-1]) as $page) {
        if (!metadata_exists('post', $page->ID, 'section_seo_testimonial_button')) {
            update_post_meta($page->ID, 'section_seo_testimonial_button', ['title'=>'Explore Our SEO Case Studies', 'url'=>$cases ? get_permalink($cases) : home_url('/case-studies/'), 'target'=>'']);
            update_post_meta($page->ID, '_section_seo_testimonial_button', 'field_seo_testimonial_button');
        }
        if (!metadata_exists('post', $page->ID, 'section_seo_testimonial_quote_mark')) {
            update_post_meta($page->ID, 'section_seo_testimonial_quote_mark', '“');
            update_post_meta($page->ID, '_section_seo_testimonial_quote_mark', 'field_ps_seo_testimonial_quote_mark');
        }
    }
    update_option('ps_testimonial_fields_v1', 1, false);
    return true;
}
add_action('admin_init', function () { if (current_user_can('manage_options')) ps_install_testimonial_fields(); }, 90);
