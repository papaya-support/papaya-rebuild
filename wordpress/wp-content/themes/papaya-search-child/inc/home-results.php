<?php
/** Update the homepage Results editor and preserve existing page content. */
defined('ABSPATH') || exit();

function ps_install_home_results_intro()
{
    if (get_option('ps_home_results_intro_v1')) {
        return true;
    }
    if (!function_exists('acf_get_field')) {
        return new WP_Error('acf_missing', 'ACF is required.');
    }
    $parent = acf_get_field('field_ps_section_home_section_results');
    if (empty($parent['ID'])) {
        return new WP_Error('results_missing', 'The Results Group is not installed yet.');
    }
    $key = 'field_home_results_introduction';
    if (!acf_get_field($key)) {
        $field = acf_update_field([
            'key' => $key,
            'label' => 'Introduction',
            'name' => 'introduction',
            'type' => 'wysiwyg',
            'parent' => $parent['ID'],
            'menu_order' => 1,
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 0,
        ]);
        if (empty($field['ID'])) {
            return new WP_Error('field_creation_failed', 'Could not create the Introduction field.');
        }
    }
    foreach (get_posts(['post_type' => 'page', 'post_status' => 'any', 'numberposts' => -1,
        'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/home.php']) as $page) {
        if (!metadata_exists('post', $page->ID, 'section_results_introduction')) {
            update_post_meta($page->ID, 'section_results_introduction', 'Rankings and clicks only tell part of the story. What matters is how search marketing contributes to your business goals. We focus on measurable performance, actionable insights, and strategies that turn search opportunities into meaningful business outcomes.');
            update_post_meta($page->ID, '_section_results_introduction', $key);
        }
    }
    // Retain a recoverable copy of the removed definitions; saved post metadata is untouched.
    $removed = [];
    foreach (['field_home_2de7aecb8d76', 'field_home_5697044bab17'] as $old_key) {
        $field = acf_get_field($old_key);
        if ($field) {
            $removed[$old_key] = $field;
        }
    }
    if ($removed && !get_option('ps_home_results_removed_fields_v1')) {
        update_option('ps_home_results_removed_fields_v1', $removed, false);
    }
    foreach ($removed as $field) {
        acf_delete_field($field['ID']);
    }
    update_option('ps_home_results_intro_v1', 1, false);
    return true;
}

add_action('admin_init', function () {
    if (current_user_can('manage_options')) {
        ps_install_home_results_intro();
    }
}, 80);
