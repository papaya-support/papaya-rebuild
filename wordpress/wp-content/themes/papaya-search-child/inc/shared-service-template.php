<?php
/** Common template and editor schema for every descendant of Services. */
defined('ABSPATH') || exit();
function ps_is_service_detail($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $root = get_page_by_path('services');
    return $root && in_array((int) $root->ID, array_map('intval', get_post_ancestors($post_id)), true);
}
add_filter('template_include', function ($template) {
    return is_page() && ps_is_service_detail() ? get_stylesheet_directory() . '/page-templates/search-engine-marketing.php' : $template;
});
function ps_assign_service_template($post_id) {
    if (wp_is_post_revision($post_id) || !ps_is_service_detail($post_id)) return;
    update_post_meta($post_id, '_wp_page_template', 'page-templates/search-engine-marketing.php');
}
add_action('save_post_page', 'ps_assign_service_template', 100);
add_action('admin_init', function () {
    if (!current_user_can('manage_options') || get_option('ps_shared_service_template_v1') || !get_page_by_path('services')) return;
    foreach (get_posts(['post_type'=>'page', 'post_status'=>'any', 'numberposts'=>-1, 'fields'=>'ids']) as $id) {
        if (!ps_is_service_detail($id)) continue;
        ps_assign_service_template($id);
        if (!metadata_exists('post', $id, 'ps_service_additional_content')) {
            update_post_meta($id, 'ps_service_additional_content', get_post_field('post_name', $id) !== 'seo' ? 1 : 0);
            update_post_meta($id, '_ps_service_additional_content', 'field_ps_service_additional_content');
        }
    }
    update_option('ps_shared_service_template_v1', 1, false);
}, 130);
add_action('acf/init', function () {
    acf_add_local_field([
        'key'=>'field_ps_service_additional_content', 'name'=>'ps_service_additional_content',
        'label'=>'Show Additional Content', 'type'=>'true_false', 'ui'=>1,
        'instructions'=>'Display the existing image/text and statistics sections after testimonials.',
        'parent'=>'group_ps_search-engine-marketing',
    ]);
});

/** Page-owned introduction; testimonial entries and buttons remain shared. */
add_action('acf/init', function () {
    acf_add_local_field([
        'key' => 'field_ps_service_testimonial_intro',
        'name' => 'section_service_testimonial_intro',
        'label' => 'Testimonial Introduction', 'type' => 'group', 'layout' => 'block',
        'instructions' => 'Heading and description above the testimonial band on this page. Leave either blank to hide it.',
        'parent' => 'group_ps_search-engine-marketing',
        'sub_fields' => [
            ['key' => 'field_ps_service_testimonial_intro_heading', 'name' => 'heading', 'label' => 'Heading', 'type' => 'text'],
            ['key' => 'field_ps_service_testimonial_intro_description', 'name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 4],
        ],
    ]);
});

// Copy the currently displayed intro once, without replacing page-owned values.
add_action('admin_init', function () {
    if (!current_user_can('manage_options') || get_option('ps_service_testimonial_intro_v1') ||
        !get_option('ps_acf_options_v1') || !get_page_by_path('services')) return;
    foreach (get_posts(['post_type' => 'page', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids']) as $id) {
        if (!ps_is_service_detail($id)) continue;
        $settings = ps_testimonial_settings(get_post_field('post_name', $id) === 'seo' ? 'seo-testimonial' : 'home-testimonial');
        foreach (['heading', 'description'] as $name) {
            $meta = 'section_service_testimonial_intro_' . $name;
            if (metadata_exists('post', $id, $meta)) continue;
            update_post_meta($id, $meta, wp_slash($settings[$name]));
            update_post_meta($id, '_' . $meta, 'field_ps_service_testimonial_intro_' . $name);
        }
        update_post_meta($id, '_section_service_testimonial_intro', 'field_ps_service_testimonial_intro');
    }
    update_option('ps_service_testimonial_intro_v1', 1, false);
}, 140);

// Retain the old option values for migration, but remove their obsolete editor controls.
foreach (['heading', 'description'] as $name) {
    add_filter('acf/prepare_field/key=field_ps_shared_testimonials_seo_' . $name, '__return_false');
}
