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

/** Create the requested test entries once; never overwrite or recreate edited/deleted samples. */
function ps_seed_case_studies()
{
    if (get_option('ps_case_study_samples_v1')) {
        return true;
    }
    $result = ps_install_case_studies();
    if (is_wp_error($result)) {
        return $result;
    }
    $result = ps_import_design_content();
    if (is_wp_error($result)) {
        return $result;
    }
    $topics = [
        'Local Services — Search Visibility',
        'Manufacturing — Lead Generation',
        'Retail — Product Discovery',
        'SaaS — Organic Acquisition',
        'Hospitality — Local Engagement',
        'Professional Services — Qualified Enquiries',
        'Ecommerce — Paid Search',
        'Education — Content Strategy',
        'Home Services — Regional Search',
    ];
    foreach ($topics as $topic) {
        $slug = 'sample-' . sanitize_title($topic);
        $existing = get_posts([
            'post_type' => 'case_study',
            'post_status' => ['publish', 'draft', 'pending', 'private', 'future', 'trash'],
            'meta_key' => '_ps_sample_case',
            'meta_value' => $slug,
            'posts_per_page' => 1,
            'fields' => 'ids',
        ]);
        if ($existing || get_page_by_path($slug, OBJECT, 'case_study')) {
            continue;
        }
        $id = wp_insert_post(
            [
                'post_type' => 'case_study',
                'post_status' => 'publish',
                'post_title' => 'Sample: ' . $topic,
                'post_name' => $slug,
                'post_excerpt' =>
                    'A sample ' .
                    strtolower($topic) .
                    ' case study demonstrating the template layout. Fictional test content.',
                'meta_input' => ['_ps_sample_case' => $slug],
            ],
            true,
        );
        if (is_wp_error($id)) {
            return $id;
        }
        $values = [
            'dac02921ffd6' => 'Home > Case Studies',
            'afcd2640f663' => 'Sample: ' . $topic,
            '7ad346f8413b' =>
                '<p><strong>Fictional test case study.</strong> This ' .
                esc_html(strtolower($topic)) .
                ' example demonstrates the detail template. Replace this sample content with approved client information.</p>',
            'edf925d1bf1f' =>
                '<p><span class="accent"><strong>Search Visibility</strong></span><br>Sample result: clearer service pages and more relevant search content.</p>',
            '269299b792ce' =>
                '<p><span class="accent"><strong>Lead Generation</strong></span><br>Sample result: focused landing pages and clearer enquiry paths.</p>',
            '9a239a17b643' =>
                '<p><span class="accent"><strong>Local Engagement</strong></span><br>Sample result: consistent location information and useful local pages.</p>',
            '0e4af60dbfa0' =>
                '<p>These illustrative outcomes demonstrate the results section; they are not verified client performance claims.</p>',
            '7e19b95f2be4' => '“',
            '3f5be18822e8' =>
                '<p>This sample quote shows how a client testimonial will appear in the finished case study.</p>',
            '74dd8a1b256d' => 'Sample testimonial — fictional client',
            'e27663e17aca' => 'The Challenge',
            '1dcedfb52114' =>
                '<p>This fictional project explores ' .
                esc_html(strtolower($topic)) .
                '.</p><ul><li>Make relevant information easier to discover.</li><li>Improve the path from search to enquiry.</li><li>Connect reporting to useful business questions.</li></ul>',
            '28224860229f' =>
                '<p>The sample approach combines a content review, improved page structure, and a measurement plan.</p><p>Use the image fields to replace the design placeholders with approved project photography and reporting graphics.</p>',
        ];
        foreach ($values as $suffix => $value) {
            update_field('field_case_study_detail_' . $suffix, $value, $id);
        }
        foreach (['cf7d13020e', '4d97ed1303', '26a15581be'] as $suffix) {
            $key = 'case_study_detail_image_' . $suffix;
            $asset = ps_default_content($key)['asset'];
            $image_id = (int) get_option('ps_asset_' . md5($asset));
            if ($image_id) {
                update_field('field_' . $key, $image_id, $id);
                if ($suffix === 'cf7d13020e') {
                    set_post_thumbnail($id, $image_id);
                }
            }
        }
    }
    update_option('ps_case_study_samples_v1', 1, false);
    return true;
}

add_action(
    'admin_init',
    function () {
        if (!current_user_can('manage_options')) {
            return;
        }
        $result = ps_seed_case_studies();
        if (is_wp_error($result)) {
            add_action('admin_notices', function () use ($result) {
                echo '<div class="notice notice-error"><p>' .
                    esc_html($result->get_error_message()) .
                    '</p></div>';
            });
        }
    },
    21,
);
