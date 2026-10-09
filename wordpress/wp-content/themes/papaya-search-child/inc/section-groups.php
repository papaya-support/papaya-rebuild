<?php
/** Native ACF Group migration and compatibility with existing template bindings. */
defined('ABSPATH') || exit();

function ps_section_groups()
{
    static $sections;
    $sections ??= require __DIR__ . '/section-groups-map.php';
    return $sections;
}

function ps_grouped_field_binding($legacy_name)
{
    static $bindings;
    if ($bindings === null) {
        $bindings = [];
        foreach (ps_section_groups() as $sections) {
            foreach ($sections as $section) {
                foreach ($section['fields'] as $legacy => $name) {
                    $bindings[$legacy] = [$section['name'], $name];
                }
            }
        }
    }
    return $bindings[$legacy_name] ?? null;
}

/** Read the Group as an ACF array; an intentionally empty subfield stays empty. */
function ps_grouped_field_value($legacy_name, $post_id, &$found)
{
    $found = false;
    $binding = ps_grouped_field_binding($legacy_name);
    if (!$binding || !$post_id) {
        return null;
    }
    [$group_name, $name] = $binding;
    $meta_name = $group_name . '_' . $name;
    if (!metadata_exists('post', $post_id, $meta_name)) {
        return null;
    }
    $found = true;
    if (function_exists('get_field')) {
        $group = get_field($group_name, $post_id, false);
        if (is_array($group) && array_key_exists('field_' . $legacy_name, $group)) {
            return $group['field_' . $legacy_name];
        }
    }
    return get_post_meta($post_id, $meta_name, true);
}

/** Move the existing definitions and copy metadata once, retaining all legacy values. */
function ps_install_section_groups()
{
    if (get_option('ps_section_groups_v1')) {
        return true;
    }
    if (!function_exists('acf_update_field')) {
        return new WP_Error('acf_missing', 'Activate Advanced Custom Fields first.');
    }
    $backup = get_option('ps_section_groups_backup_v1', []);
    foreach (ps_section_groups() as $slug => $sections) {
        $field_group = acf_get_field_group('group_ps_' . $slug);
        if (empty($field_group['ID'])) {
            return new WP_Error('section_group_missing', 'Missing ACF fields for ' . $slug);
        }
        if (!isset($backup[$slug])) {
            $backup[$slug] = ['group' => $field_group, 'fields' => acf_get_fields($field_group)];
            update_option('ps_section_groups_backup_v1', $backup, false);
        }
        foreach ($sections as $position => $section) {
            $group_key = 'field_ps_section_' . str_replace('-', '_', $slug) . '_' . $section['name'];
            $parent = acf_get_field($group_key);
            if (!$parent) {
                $parent = acf_update_field([
                    'key' => $group_key,
                    'name' => $section['name'],
                    'label' => $section['label'],
                    'type' => 'group',
                    'layout' => 'block',
                    'instructions' => 'Content for the ' . $section['label'] . ' section. Layout is controlled by the shared ' . $section['template'] . ' template.',
                    'parent' => $field_group['ID'],
                    'menu_order' => $position,
                ]);
            }
            if (empty($parent['ID'])) {
                return new WP_Error('section_create_failed', 'Could not create ' . $section['label']);
            }
            $sub_position = 0;
            foreach ($section['fields'] as $legacy_name => $name) {
                $field = acf_get_field('field_' . $legacy_name);
                // Additional live-service FAQs were introduced after the original schema.
                if (!$field && preg_match('/^search_engine_marketing_faq_(question|answer)_([7-9])$/', $legacy_name, $match)) {
                    $field = acf_update_field([
                        'key' => 'field_' . $legacy_name,
                        'name' => $name,
                        'label' => ucfirst($match[1]) . ' ' . $match[2],
                        'type' => $match[1] === 'question' ? 'text' : 'wysiwyg',
                        'parent' => $parent['ID'],
                    ]);
                }
                if (!$field) {
                    return new WP_Error('section_field_missing', 'Missing field ' . $legacy_name);
                }
                // The original key, field type, validation and editor options are preserved.
                $meta_name = $section['name'] . '_' . $name;
                $post_ids = get_posts([
                    'post_type' => ['page', 'case_study', 'ps_site_content'],
                    'post_status' => ['publish', 'draft', 'pending', 'private', 'future', 'trash'],
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                    'meta_key' => $legacy_name,
                ]);
                foreach ($post_ids as $post_id) {
                    if (!metadata_exists('post', $post_id, $meta_name)) {
                        update_post_meta($post_id, $meta_name, wp_slash(get_post_meta($post_id, $legacy_name, true)));
                        update_post_meta($post_id, '_' . $meta_name, $field['key']);
                    }
                    update_post_meta($post_id, '_' . $section['name'], $group_key);
                    if (!metadata_exists('post', $post_id, $section['name'])) {
                        update_post_meta($post_id, $section['name'], '');
                    }
                }
                $field['parent'] = $parent['ID'];
                $field['name'] = $name;
                // Keep descriptive labels for repeated cards, steps and questions.
                $field['menu_order'] = $sub_position++;
                if (!acf_update_field(wp_slash($field))) {
                    return new WP_Error('section_move_failed', 'Could not group ' . $legacy_name);
                }
            }
        }
    }
    // The listing now uses real Case Study posts; retain old static cards off-screen.
    $archive = acf_get_field_group('group_ps_archived_case_cards');
    if (!$archive) {
        $archive = acf_update_field_group([
            'key' => 'group_ps_archived_case_cards',
            'title' => 'Case Studies — Archived Static Cards',
            'active' => false,
            'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'page']]],
        ]);
    }
    if (empty($archive['ID'])) {
        return new WP_Error('archive_failed', 'Could not preserve the legacy case study fields.');
    }
    foreach ([
        'field_case_studies_image_8dd5e5fb58',
        'field_case_studies_13234451b10b',
        'field_case_studies_13234451b10b_url',
        'field_case_studies_aea86c447f03',
        'field_case_studies_image_14cb78ca6e',
        'field_case_studies_1c7050391843',
        'field_case_studies_1c7050391843_url',
        'field_case_studies_797e93346093',
        'field_case_studies_image_5ad8dd81b9',
        'field_case_studies_0ecd54bcba1a',
        'field_case_studies_0ecd54bcba1a_url',
        'field_case_studies_c478f4605f8c',
        'field_case_studies_image_af8f78b46e',
        'field_case_studies_0a7c3278aa61',
        'field_case_studies_0a7c3278aa61_url',
        'field_case_studies_7ccd51309801',
        'field_case_studies_image_295549ea58',
        'field_case_studies_716425e49fa2',
        'field_case_studies_716425e49fa2_url',
        'field_case_studies_4c074436f397',
        'field_case_studies_image_b6a651e3c2',
        'field_case_studies_74f48e8c27a3',
        'field_case_studies_74f48e8c27a3_url',
        'field_case_studies_938030bd0b43',
        'field_case_studies_image_540f75952e',
        'field_case_studies_304856bee0cf',
        'field_case_studies_304856bee0cf_url',
        'field_case_studies_c6058d1d5199',
        'field_case_studies_image_27fdb373ca',
        'field_case_studies_79f1326abebe',
        'field_case_studies_79f1326abebe_url',
        'field_case_studies_02b4fd4b7c29',
        'field_case_studies_image_340ebfd1f6',
        'field_case_studies_689120d3d2e7',
        'field_case_studies_689120d3d2e7_url',
        'field_case_studies_bf246d534c46',
    ] as $key) {
        $field = acf_get_field($key);
        if ($field) {
            $field['parent'] = $archive['ID'];
            if (!acf_update_field(wp_slash($field))) {
                return new WP_Error('archive_field_failed', 'Could not archive ' . $key);
            }
        }
    }
    update_option('ps_section_groups_v1', 1, false);
    return true;
}

add_action('admin_init', function () {
    if (!current_user_can('manage_options')) {
        return;
    }
    $result = ps_install_section_groups();
    if (is_wp_error($result)) {
        add_action('admin_notices', function () use ($result) {
            echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
        });
    }
}, 30);
