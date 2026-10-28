<?php
/** Shared testimonial library and display settings. Existing page values stay archived. */
defined('ABSPATH') || exit();

add_action('init', function () {
    register_post_type('ps_testimonial', [
        'labels' => ['name' => 'Testimonials', 'singular_name' => 'Testimonial', 'add_new_item' => 'Add Testimonial'],
        'public' => false, 'show_ui' => false, 'show_in_menu' => false,
        'menu_icon' => 'dashicons-format-quote', 'supports' => ['title'],
        'capability_type' => 'page', 'map_meta_cap' => true,
    ]);
}, 10);

function ps_testimonial_site_id() {
    return (int) get_option('ps_testimonial_site_id');
}

function ps_testimonial_settings($variant) {
    $id = ps_testimonial_site_id();
    $prefix = $variant === 'seo-testimonial' ? 'seo' : ($variant === 'case-study-detail-testimonial' ? 'case' : 'home');
    $values = [];
    foreach (['heading', 'description', 'button_label', 'button_url'] as $name) {
        $meta = 'section_testimonials_' . $prefix . '_' . $name;
        $option = function_exists('ps_option_meta') ? ps_option_meta('testimonials', $meta) : null;
        $values[$name] = $option !== null ? (string) $option : ($id ? (string) get_post_meta($id, $meta, true) : '');
    }
    return $values;
}

function ps_testimonial_rows() {
    if (!get_option('ps_acf_options_v1')) return [];
    $count = (int) get_option('ps_testimonials_section_testimonials_items', 0);
    $rows = [];
    for ($index = 0; $index < $count; $index++) {
        $row = [];
        foreach (['quote', 'client_name', 'business_name', 'client_role', 'image', 'image_url', 'quote_mark'] as $name) {
            $row[$name] = get_option('ps_testimonials_section_testimonials_items_' . $index . '_' . $name, '');
        }
        if (trim((string) $row['quote']) !== '') $rows[] = $row;
    }
    return $rows;
}

function ps_shared_testimonial_ids() {
    $id = ps_testimonial_site_id();
    if (!$id) return [];
    $selected = get_post_meta($id, 'section_testimonials_selected', true);
    $selected = is_array($selected) ? array_map('absint', $selected) : [];
    if (!$selected) $selected = get_posts(['post_type' => 'ps_testimonial', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC', 'fields' => 'ids']);
    return array_values(array_filter($selected, static fn($post_id) => get_post_status($post_id) === 'publish' && get_post_type($post_id) === 'ps_testimonial'));
}

function ps_install_shared_testimonials() {
    if (get_option('ps_shared_testimonials_v1')) return true;
    if (!function_exists('acf_update_field_group')) return new WP_Error('acf_missing', 'Advanced Custom Fields is required.');

    $entry = ps_testimonial_site_id();
    if (!$entry || !get_post($entry)) {
        $existing = get_posts(['post_type' => 'ps_site_content', 'post_status' => 'any', 'name' => 'testimonials', 'numberposts' => 1]);
        $entry = $existing ? $existing[0]->ID : wp_insert_post(['post_type' => 'ps_site_content', 'post_status' => 'publish', 'post_title' => 'Testimonials', 'post_name' => 'testimonials'], true);
        if (is_wp_error($entry)) return $entry;
        update_option('ps_testimonial_site_id', $entry, false);
    }

    $library = acf_update_field_group(['key' => 'group_ps_testimonial_library', 'title' => 'Testimonial', 'active' => true, 'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'ps_testimonial']]]]);
    if (empty($library['ID'])) return new WP_Error('testimonial_fields', 'Unable to create testimonial fields.');
    foreach ([
        ['quote', 'Quote', 'textarea'], ['client_name', 'Client Name', 'text'],
        ['business_name', 'Business Name', 'text'], ['client_role', 'Client Role', 'text'], ['image', 'Client Image', 'image'],
        ['quote_mark', 'Quote Mark', 'text'],
    ] as $position => [$name, $label, $type]) {
        $field = acf_update_field(['key' => 'field_ps_testimonial_' . $name, 'name' => $name, 'label' => $label, 'type' => $type, 'parent' => $library['ID'], 'menu_order' => $position, 'required' => $name === 'quote' ? 1 : 0, 'return_format' => 'id', 'preview_size' => 'thumbnail']);
        if (empty($field['ID'])) return new WP_Error('testimonial_field', 'Unable to create ' . $label . '.');
    }

    $settings_group = acf_update_field_group(['key' => 'group_ps_shared_testimonials', 'title' => 'Testimonials — Site Content', 'active' => true, 'location' => [[['param' => 'post', 'operator' => '==', 'value' => (string) $entry]]]]);
    $parent = acf_update_field(['key' => 'field_ps_shared_testimonials', 'name' => 'section_testimonials', 'label' => 'Testimonial Sections', 'type' => 'group', 'layout' => 'block', 'parent' => $settings_group['ID']]);
    if (empty($parent['ID'])) return new WP_Error('testimonial_settings', 'Unable to create shared testimonial settings.');
    $position = 0;
    foreach (['home' => 'Home', 'seo' => 'SEO', 'case' => 'Case Study'] as $prefix => $label) {
        foreach (['heading' => 'Heading', 'description' => 'Description', 'button_label' => 'Button Label', 'button_url' => 'Button URL'] as $name => $field_label) {
            $field = acf_update_field(['key' => 'field_ps_shared_testimonials_' . $prefix . '_' . $name, 'name' => $prefix . '_' . $name, 'label' => $label . ' — ' . $field_label, 'type' => $name === 'description' ? 'textarea' : 'text', 'parent' => $parent['ID'], 'menu_order' => $position++, 'required' => 0]);
            if (empty($field['ID'])) return new WP_Error('testimonial_settings_field', 'Unable to create ' . $field_label . '.');
        }
    }
    $selected_field = acf_update_field(['key' => 'field_ps_shared_testimonials_selected', 'name' => 'selected', 'label' => 'Testimonials to Display', 'instructions' => 'Select multiple testimonials and arrange them in the order they should appear. Leave empty to show every published testimonial.', 'type' => 'post_object', 'post_type' => ['ps_testimonial'], 'multiple' => 1, 'return_format' => 'id', 'parent' => $parent['ID'], 'menu_order' => $position]);
    if (empty($selected_field['ID'])) return new WP_Error('testimonial_selection', 'Unable to create testimonial selection field.');

    $source = [
        'home' => [get_page_by_path('home') ?: get_post((int) get_option('page_on_front')), 'section_testimonial_', 'attribution', 'description', 'intro_description'],
        'seo' => [get_page_by_path('services/seo') ?: get_page_by_path('seo'), 'section_seo_testimonial_', 'client_name', 'quote', 'description'],
        'case' => [get_page_by_path('case-study-detail'), 'section_testimonial_', 'attribution', 'description', 'intro_description'],
    ];
    $selected = [];
    $seen = [];
    $posts = get_posts(['post_type' => ['page', 'case_study'], 'post_status' => 'any', 'numberposts' => -1, 'orderby' => 'ID', 'order' => 'ASC']);
    foreach ($posts as $post) {
        $seo = $post->post_name === 'seo';
        $base = $seo ? 'section_seo_testimonial_' : 'section_testimonial_';
        $quote = trim((string) get_post_meta($post->ID, $base . ($seo ? 'quote' : 'description'), true));
        if ($quote === '') continue;
        $client = trim((string) get_post_meta($post->ID, $base . ($seo ? 'client_name' : 'attribution'), true));
        $business = trim((string) get_post_meta($post->ID, $base . 'business_name', true));
        $fingerprint = md5(trim(wp_strip_all_tags($quote)));
        if (isset($seen[$fingerprint])) continue;
        $sample = str_starts_with($post->post_title, 'Sample:') || stripos($client, 'fictional') !== false;
        $title = trim(preg_replace('/^[\s–—-]+/u', '', strtok($client, "\n") ?: '')) ?: 'Client testimonial';
        $role = trim(strstr($client, "\n") ?: '');
        $slug = 'testimonial-' . substr($fingerprint, 0, 12);
        $existing = get_page_by_path($slug, OBJECT, 'ps_testimonial');
        $id = $existing ? $existing->ID : wp_insert_post(['post_type' => 'ps_testimonial', 'post_status' => $sample ? 'draft' : 'publish', 'post_name' => $slug, 'post_title' => $title], true);
        if (is_wp_error($id)) return $id;
        $seen[$fingerprint] = $id;
        foreach (['quote' => $quote, 'client_name' => $title, 'business_name' => $business, 'client_role' => $role, 'image' => get_post_meta($post->ID, $base . 'image', true), 'quote_mark' => get_post_meta($post->ID, $base . 'quote_mark', true)] as $name => $value) {
            if ($value === '' || metadata_exists('post', $id, $name)) continue;
            update_post_meta($id, $name, wp_slash($value));
            update_post_meta($id, '_' . $name, 'field_ps_testimonial_' . $name);
        }
        if (!$sample) $selected[] = $id;
    }
    foreach ($source as $variant => [$post, $base, $client_key, $quote_key, $description_key]) {
        if (!$post) continue;
        $button = get_post_meta($post->ID, $base . 'button', true);
        $values = [
            'heading' => get_post_meta($post->ID, $base . 'heading', true),
            'description' => get_post_meta($post->ID, $base . $description_key, true),
            'button_label' => metadata_exists('post', $post->ID, $base . 'button_label') ? get_post_meta($post->ID, $base . 'button_label', true) : (is_array($button) ? ($button['title'] ?? '') : ''),
            'button_url' => metadata_exists('post', $post->ID, $base . 'button_url') ? get_post_meta($post->ID, $base . 'button_url', true) : (is_array($button) ? ($button['url'] ?? '') : ''),
        ];
        foreach ($values as $name => $value) {
            $meta = 'section_testimonials_' . $variant . '_' . $name;
            if (!metadata_exists('post', $entry, $meta)) {
                update_post_meta($entry, $meta, wp_slash($value));
                update_post_meta($entry, '_' . $meta, 'field_ps_shared_testimonials_' . $variant . '_' . $name);
            }
        }
    }
    if (!metadata_exists('post', $entry, 'section_testimonials_selected')) {
        update_post_meta($entry, 'section_testimonials_selected', array_values(array_unique($selected)));
        update_post_meta($entry, '_section_testimonials_selected', 'field_ps_shared_testimonials_selected');
    }

    $archive = acf_get_field_group('group_ps_archived_testimonials');
    if (!$archive) $archive = acf_update_field_group(['key' => 'group_ps_archived_testimonials', 'title' => 'Archived — Page Testimonial Fields', 'active' => false, 'location' => []]);
    foreach (['field_ps_section_home_section_testimonial', 'field_ps_section_case_study_detail_section_testimonial'] as $key) {
        $field = acf_get_field($key);
        if ($field && !empty($archive['ID'])) { $field['parent'] = $archive['ID']; acf_update_field(wp_slash($field)); }
    }
    $seo_group = acf_get_field_group('group_ps_seo_testimonial');
    if ($seo_group) { $seo_group['active'] = false; acf_update_field_group($seo_group); }
    update_option('ps_shared_testimonials_v1', 1, false);
    return true;
}

add_action('init', function () {
    if (!get_option('ps_shared_testimonials_v1') && function_exists('acf_update_field_group') && get_option('ps_section_groups_v1')) ps_install_shared_testimonials();
}, 120);

/** Restore the SEO testimonial CTA after the page fields moved to Site Content. */
add_action('init', function () {
    if (get_option('ps_seo_testimonial_button_restored_v1')) return;
    $entry = ps_testimonial_site_id();
    if (!$entry || !get_post($entry)) return;

    $seo_page = get_page_by_path('services/seo') ?: get_page_by_path('seo');
    $legacy_button = $seo_page ? get_post_meta($seo_page->ID, 'section_seo_testimonial_button', true) : [];
    $defaults = [
        'button_label' => $seo_page ? (string) get_post_meta($seo_page->ID, 'section_seo_testimonial_button_label', true) : '',
        'button_url' => $seo_page ? (string) get_post_meta($seo_page->ID, 'section_seo_testimonial_button_url', true) : '',
    ];
    if ($defaults['button_label'] === '') $defaults['button_label'] = is_array($legacy_button) ? (string) ($legacy_button['title'] ?? '') : '';
    if ($defaults['button_url'] === '') $defaults['button_url'] = is_array($legacy_button) ? (string) ($legacy_button['url'] ?? '') : '';
    if ($defaults['button_label'] === '') $defaults['button_label'] = 'Explore Our SEO Case Studies';
    if ($defaults['button_url'] === '') $defaults['button_url'] = home_url('/case-studies/');

    foreach ($defaults as $name => $value) {
        $meta = 'section_testimonials_seo_' . $name;
        if (trim((string) get_post_meta($entry, $meta, true)) !== '') continue;
        update_post_meta($entry, $meta, wp_slash($value));
        update_post_meta($entry, '_' . $meta, 'field_ps_shared_testimonials_seo_' . $name);
    }
    update_option('ps_seo_testimonial_button_restored_v1', 1, false);
}, 130);
