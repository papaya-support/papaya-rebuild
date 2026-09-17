<?php
/** Install an editable ACF post type and attach the existing detail fields once. */
defined('ABSPATH') || exit();

function ps_install_case_studies()
{
    if (get_option('ps_case_studies_acf_v1')) {
        return true;
    }
    if (!function_exists('acf_import_post_type')) {
        return new WP_Error('acf_missing', 'Activate Advanced Custom Fields 6.1 or newer first.');
    }
    $result = ps_install_acf_groups();
    if (is_wp_error($result)) {
        return $result;
    }
    $group = acf_get_field_group('group_ps_case-study-detail');
    if (empty($group['ID'])) {
        return new WP_Error('case_fields_missing', 'The Case Study Detail field group is missing.');
    }

    // Save a native ACF definition, never override subsequent dashboard edits.
    $existing = get_posts([
        'post_type' => 'acf-post-type',
        'post_status' => ['publish', 'draft', 'private', 'trash'],
        'name' => 'post_type_ps_case_study',
        'posts_per_page' => 1,
        'fields' => 'ids',
    ]);
    if (!$existing) {
        if (post_type_exists('case_study')) {
            return new WP_Error('case_type_exists', 'A case_study post type already exists.');
        }
        $result = acf_import_post_type([
            'key' => 'post_type_ps_case_study',
            'title' => 'Case Studies',
            'active' => true,
            'post_type' => 'case_study',
            'labels' => [
                'name' => 'Case Studies',
                'singular_name' => 'Case Study',
                'menu_name' => 'Case Studies',
                'all_items' => 'All Case Studies',
                'add_new_item' => 'Add New Case Study',
                'edit_item' => 'Edit Case Study',
                'view_item' => 'View Case Study',
                'search_items' => 'Search Case Studies',
            ],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => ['title', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'],
            // Keep the existing Case Studies landing page at /case-studies/.
            'has_archive' => false,
            'rewrite' => [
                'permalink_rewrite' => 'custom_permalink',
                'slug' => 'case-studies',
                'with_front' => false,
                'feeds' => false,
                'pages' => true,
            ],
        ]);
        if (is_wp_error($result) || empty($result['ID'])) {
            return new WP_Error(
                'case_type_failed',
                'Could not create the ACF Case Studies post type.',
            );
        }
    }

    $location = [['param' => 'post_type', 'operator' => '==', 'value' => 'case_study']];
    if (!in_array($location, $group['location'], true)) {
        $group['location'][] = $location;
        if (!acf_update_field_group($group)) {
            return new WP_Error(
                'case_fields_failed',
                'Could not attach the Case Study Detail fields.',
            );
        }
    }
    // ACF registers the saved definition; refresh permalinks only during installation.
    acf_get_internal_post_type_instance('acf-post-type')->register_post_types();
    flush_rewrite_rules(false);
    update_option('ps_case_studies_acf_v1', 1, false);
    return true;
}

add_action(
    'admin_init',
    function () {
        if (!current_user_can('manage_options')) {
            return;
        }
        $result = ps_install_case_studies();
        if (is_wp_error($result)) {
            add_action('admin_notices', function () use ($result) {
                echo '<div class="notice notice-error"><p>' .
                    esc_html($result->get_error_message()) .
                    '</p></div>';
            });
        }
    },
    20,
);
