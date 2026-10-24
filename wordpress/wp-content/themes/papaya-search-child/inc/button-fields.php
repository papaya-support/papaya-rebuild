<?php
/** Native ACF button editors; preserve existing content and link destinations. */
defined('ABSPATH') || exit();
function ps_install_button_fields() {
    if (get_option('ps_button_fields_v1')) return true;
    if (!function_exists('acf_update_field')) return new WP_Error('acf_missing', 'Activate ACF first.');
    $specs = [
        ['field_ps_section_shared_section_footer', 'shared_7b3ca0dbb8c9', 'call_button', false],
        ['field_ps_section_search_engine_marketing_section_introduction', 'ps_service_intro_button', 'button', true],
        ['field_ps_section_home_section_testimonial', 'home_testimonial_button', 'button', true],
        ['field_ps_section_case_study_detail_section_testimonial', 'case_study_detail_testimonial_button', 'button', true],
        ['field_ps_seo_testimonial', 'seo_testimonial_button', 'button', true],
    ];
    foreach ($specs as [$parent_key, $key, $name, $add_label]) {
        $parent = acf_get_field($parent_key);
        if (!$parent) return new WP_Error('missing_group', 'Missing section: ' . $parent_key);
        $position = count(acf_get_fields($parent) ?: []);
        foreach (['label', 'url'] as $suffix) {
            if ($suffix === 'label' && !$add_label) continue;
            $field_key = 'field_' . $key . '_' . $suffix;
            if (!acf_get_field($field_key) && !acf_update_field([
                'key' => $field_key, 'name' => $name . '_' . $suffix,
                'label' => 'Button ' . ($suffix === 'url' ? 'URL' : 'Label'), 'type' => 'text',
                'parent' => $parent['ID'], 'menu_order' => $position++, 'required' => 0,
                'instructions' => $suffix === 'url' ? 'Enter a page URL, /relative-path/, #anchor, tel: number, or mailto: address.' : 'Enter the text displayed on the button. Leave empty to hide it.',
            ])) return new WP_Error('button_field_failed', 'Could not create ' . $field_key);
        }
        foreach (get_posts(['post_type'=>['page','case_study','ps_site_content'], 'post_status'=>'any', 'numberposts'=>-1]) as $post) {
            $storage = $parent['name'] . '_' . $name;
            $old = get_post_meta($post->ID, $storage, true);
            $values = is_array($old) ? ['label'=>$old['title'] ?? '', 'url'=>$old['url'] ?? ''] : [];
            if ($key === 'shared_7b3ca0dbb8c9' && $post->ID === ps_shared_id()) $values = ['url'=>ps_booking_url()];
            if ($key === 'ps_service_intro_button' && $post->post_name === 'seo') $values = ['label'=>"Let's Talk SEO", 'url'=>ps_booking_url()];
            foreach ($values as $suffix => $value) {
                if (!metadata_exists('post', $post->ID, $storage . '_' . $suffix)) {
                    update_post_meta($post->ID, $storage . '_' . $suffix, $value);
                    update_post_meta($post->ID, '_' . $storage . '_' . $suffix, 'field_' . $key . '_' . $suffix);
                }
            }
        }
    }
    update_option('ps_button_fields_v1', 1, false);
    return true;
}
add_action('admin_init', function () {
    if (!current_user_can('manage_options')) return;
    $result = ps_install_button_fields();
    if (is_wp_error($result)) add_action('admin_notices', function () use ($result) {
        echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
    });
}, 100);

/** Read separate fields, falling back only while they have never been saved. */
function ps_editable_button($group, $name, $label = '', $url = '', $post_id = null) {
    $id = $post_id ?: get_the_ID();
    $prefix = $group . '_' . $name . '_';
    return [
        'title' => metadata_exists('post', $id, $prefix . 'label') ? get_post_meta($id, $prefix . 'label', true) : $label,
        'url' => metadata_exists('post', $id, $prefix . 'url') ? get_post_meta($id, $prefix . 'url', true) : $url,
    ];
}
// Retain legacy link values for compatibility, but expose one unambiguous editor pair.
foreach (['home_testimonial_button', 'case_study_detail_testimonial_button', 'seo_testimonial_button'] as $key) {
    add_filter('acf/prepare_field/key=field_' . $key, function ($field) {
        return get_option('ps_button_fields_v1') ? false : $field;
    });
}
