<?php
/** Match the content editor to the rendered sections. Retired definitions remain recoverable. */
defined('ABSPATH') || exit();

function ps_editor_field_order() {
    $order = [];
    foreach (ps_section_groups() as $slug => $sections) {
        $root = 'group_ps_' . $slug;
        foreach ($sections as $section) {
            $key = 'field_ps_section_' . str_replace('-', '_', $slug) . '_' . $section['name'];
            $order[$root][] = $key;
            $order[$key] = array_values($section['fields']);
        }
    }
    $home = 'field_ps_section_home_';
    $service = 'field_ps_section_search_engine_marketing_';
    $order['group_ps_home'] = array_values(array_diff($order['group_ps_home'], [$home . 'section_search_strategy']));
    $order['group_ps_search-engine-marketing'] = array_values(array_diff($order['group_ps_search-engine-marketing'], [$service . 'section_microsoft_ads']));
    $order[$home . 'section_services'] = ['heading_5','heading','description','heading_2','description_2','heading_3','description_3','heading_4','description_4'];
    $order[$home . 'section_future_strategy'] = ['heading','description','card_heading_1','card_description_1','card_heading_2','card_description_2','card_heading_3','card_description_3','button_label','button_url'];
    $order[$service . 'section_faqs'] = array_values(array_diff($order[$service . 'section_faqs'], ['image']));
    $order[$service . 'section_introduction'] = ['heading','subtitle','description','button_label','button_url'];
    foreach (['home','case_study_detail','seo'] as $slug) {
        $key = $slug === 'seo' ? 'field_ps_seo_testimonial' : 'field_ps_section_' . $slug . '_section_testimonial';
        $order[$key] = ['heading', $slug === 'seo' ? 'description' : 'intro_description', 'image','quote_mark', $slug === 'seo' ? 'quote' : 'description', $slug === 'seo' ? 'client_name' : 'attribution','business_name','button_label','button_url'];
    }
    foreach (['home','services','search_engine_marketing','about'] as $slug) {
        $key = 'field_ps_section_' . $slug . '_section_closing_cta';
        $order[$key] = array_values(array_diff($order[$key], ['image']));
        if ($slug === 'about') $order[$key] = ['heading','description','button_label','button_url_2'];
        $order[$key][] = 'image';
    }
    $key = 'field_ps_section_about_section_process';
    $order[$key] = array_values(array_diff($order[$key], ['button_url']));
    $order['field_ps_section_shared_section_footer'] = ['call_button_label','call_button_url','navigation_heading','services_heading','contact_heading','copyright_and_legal_text','default_page_button_and_footer_call_url'];
    foreach (['benefits'=>4, 'services'=>6, 'approach'=>3] as $section => $count) {
        $key = 'field_ps_seo_' . $section;
        $order[$key] = ['heading','description'];
        for ($i=1; $i<=$count; $i++) {
            if ($section === 'services') $order[$key][] = 'icon_' . $i;
            $order[$key][] = 'heading_' . $i;
            $order[$key][] = 'description_' . $i;
        }
    }
    return $order;
}

function ps_editor_retired_field_keys() {
    $keys = [
        'field_ps_section_home_section_search_strategy',
        'field_home_image_9c76895b7c', 'field_home_20f80fb1ab2f',
        'field_ps_section_search_engine_marketing_section_microsoft_ads',
        'field_search_engine_marketing_image_ba13013ff2',
        'field_about_b4e4254098b9_url', 'field_about_d48d9c392e5c_url',
    ];
    foreach (['home_c04f66011766','home_fadcbf34d7f6','home_01079a482a16','home_4dbc82586a2a'] as $key) {
        $keys[] = 'field_' . $key;
        $keys[] = 'field_' . $key . '_url';
    }
    return $keys;
}

function ps_clean_editor_fields() {
    if (get_option('ps_editor_cleanup_v1')) return true;
    if (!function_exists('acf_update_field') || !get_option('ps_section_groups_v1') || !get_option('ps_button_fields_v1')) return false;
    $archive = acf_get_field_group('group_ps_retired_fields');
    if (!$archive) $archive = acf_update_field_group(['key'=>'group_ps_retired_fields','title'=>'Archived — Retired layout fields','active'=>false,'location'=>[]]);
    if (empty($archive['ID'])) return false;
    $backup = get_option('ps_editor_cleanup_backup_v1', []);
    foreach (ps_editor_retired_field_keys() as $key) {
        $field = acf_get_field($key);
        if (!$field) continue;
        if (!isset($backup[$key])) {
            $backup[$key] = $field;
            // Save before moving the definition; page values are never changed.
            update_option('ps_editor_cleanup_backup_v1', $backup, false);
        }
        $field['parent'] = $archive['ID'];
        if (!acf_update_field(wp_slash($field))) return false;
    }
    $GLOBALS['ps_editor_cleanup_running'] = true;
    try {
        foreach (ps_editor_field_order() as $key => $names) {
            $parent = str_starts_with($key,'group_') ? acf_get_field_group($key) : acf_get_field($key);
            if (!$parent) continue;
            $fields = acf_get_fields($parent) ?: [];
            $positions = array_flip($names);
            // Unknown custom fields remain available after the known frontend fields.
            usort($fields, static fn($a,$b) => ($positions[$a['key']] ?? $positions[$a['name']] ?? PHP_INT_MAX) <=> ($positions[$b['key']] ?? $positions[$b['name']] ?? PHP_INT_MAX));
            foreach ($fields as $position => $field) {
                $field['menu_order'] = $position;
                if (!acf_update_field(wp_slash($field))) return false;
            }
        }
    } finally {
        unset($GLOBALS['ps_editor_cleanup_running']);
    }
    update_option('ps_editor_cleanup_v1', 1, false);
    return true;
}
add_action('admin_init', function () {
    if (current_user_can('manage_options')) ps_clean_editor_fields();
}, 130);
