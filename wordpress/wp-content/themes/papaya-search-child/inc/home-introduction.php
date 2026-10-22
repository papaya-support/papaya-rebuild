<?php
/** Add an editable introduction text block without replacing existing homepage edits. */
defined('ABSPATH') || exit();

function ps_install_home_introduction()
{
    if (get_option('ps_home_introduction_text_v1')) {
        return true;
    }
    if (!function_exists('acf_get_field')) {
        return new WP_Error('acf_missing', 'Activate ACF to edit the homepage introduction.');
    }
    $parent = acf_get_field('field_ps_section_home_section_introduction');
    if (empty($parent['ID'])) {
        return new WP_Error('introduction_missing', 'The homepage Introduction Group is not installed yet.');
    }
    $key = 'field_home_introduction_description';
    if (!acf_get_field($key)) {
        $field = acf_update_field([
            'key' => $key,
            'label' => 'Description',
            'name' => 'description',
            'type' => 'wysiwyg',
            'parent' => $parent['ID'],
            'menu_order' => 1,
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 0,
            'instructions' => 'Text displayed beneath the homepage introduction heading.',
        ]);
        if (empty($field['ID'])) {
            return new WP_Error('field_creation_failed', 'Could not create the introduction Description field.');
        }
    }
    $pages = get_posts([
        'post_type' => 'page',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-templates/home.php',
    ]);
    foreach ($pages as $page) {
        if (metadata_exists('post', $page->ID, 'section_introduction_description')) {
            continue;
        }
        $value = get_post_meta($page->ID, 'section_search_strategy_description', true);
        if (!metadata_exists('post', $page->ID, 'section_search_strategy_description')) {
            $value = get_post_meta($page->ID, 'home_d92c033cd119', true);
            if (!metadata_exists('post', $page->ID, 'home_d92c033cd119')) {
                $value = ps_default_content('home_d92c033cd119')['text'] ?? '';
            }
        }
        // Preserve the old section's fields as recoverable data, and seed the new text only once.
        update_post_meta($page->ID, 'section_introduction_description', $value);
        update_post_meta($page->ID, '_section_introduction_description', $key);
    }
    update_option('ps_home_introduction_text_v1', 1, false);
    return true;
}

add_action('admin_init', function () {
    if (current_user_can('manage_options')) {
        ps_install_home_introduction();
    }
}, 70);

// The removed section is no longer editable on the page; its saved content remains intact.
add_filter('acf/prepare_field/key=field_ps_section_home_section_search_strategy', function ($field) {
    return get_option('ps_home_introduction_text_v1') ? false : $field;
});
