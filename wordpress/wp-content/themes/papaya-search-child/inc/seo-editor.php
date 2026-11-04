<?php
/** Present the same ordered section fields for all Services subpages. */
defined('ABSPATH') || exit();

function ps_is_seo_editor() {
    if (!is_admin() || !empty($GLOBALS['ps_editor_cleanup_running'])) return false;
    $post_id = function_exists('acf_get_form_data') ? acf_get_form_data('post_id') : 0;
    if (!$post_id) $post_id = absint($_GET['post'] ?? $_POST['post_ID'] ?? $_POST['_acf_post_id'] ?? 0);
    $post = get_post($post_id);
    return $post && $post->post_type === 'page' && ps_is_service_detail($post->ID);
}

add_filter('acf/load_field_group', function ($group) {
    if (!ps_is_seo_editor()) return $group;
    if ($group['key'] === 'group_ps_search-engine-marketing') {
        $group['title'] = 'Services — Page Content';
        $group['menu_order'] = 0;
    }
    if (in_array($group['key'], ['group_ps_seo_benefits', 'group_ps_seo_services', 'group_ps_seo_approach', 'group_ps_seo_testimonial'], true)) {
        // Their original fields are displayed inside the ordered Page Content panel.
        $group['active'] = false;
    }
    return $group;
});

add_filter('acf/load_fields', function ($fields, $parent) {
    if (!ps_is_seo_editor() || ($parent['key'] ?? '') !== 'group_ps_search-engine-marketing') return $fields;
    $keys = [
        'field_ps_section_search_engine_marketing_section_banner',
        'field_ps_section_search_engine_marketing_section_introduction',
        'field_ps_seo_benefits',
        'field_ps_seo_services',
        'field_ps_seo_approach',
        'field_ps_service_testimonial_intro',
        'field_ps_service_additional_content',
        'field_ps_section_search_engine_marketing_section_visibility',
        'field_ps_section_search_engine_marketing_section_ppc_services',
        'field_ps_section_search_engine_marketing_section_benefits',
        'field_ps_section_search_engine_marketing_section_google_ads',
        'field_ps_section_search_engine_marketing_section_faqs',
        'field_ps_section_search_engine_marketing_section_closing_cta',
    ];
    $ordered = [];
    foreach ($keys as $position => $key) {
        $field = acf_get_field($key);
        if (!$field) continue;
        $field['menu_order'] = $position;
        $labels = ['field_ps_seo_benefits'=>'Benefits', 'field_ps_seo_services'=>'Service Cards', 'field_ps_seo_approach'=>'Our Approach'];
        if (isset($labels[$key])) $field['label'] = $labels[$key];
        if (in_array($key, ['field_ps_section_search_engine_marketing_section_visibility', 'field_ps_section_search_engine_marketing_section_ppc_services', 'field_ps_section_search_engine_marketing_section_benefits', 'field_ps_section_search_engine_marketing_section_google_ads'], true)) {
            $field['conditional_logic'] = [[['field'=>'field_ps_service_additional_content', 'operator'=>'==', 'value'=>'1']]];
        }
        $ordered[] = $field;
    }
    return $ordered;
}, 20, 2);
