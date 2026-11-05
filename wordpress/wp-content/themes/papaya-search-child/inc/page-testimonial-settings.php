<?php
/** Keep section copy and buttons with the page that displays them. */
defined('ABSPATH') || exit();

function ps_testimonial_page_group($variant) {
    return match ($variant) {
        'home-testimonial' => 'section_home_testimonial_settings',
        'case-study-detail-testimonial' => 'section_case_testimonial_settings',
        'seo-testimonial' => 'section_service_testimonial_intro',
        default => '',
    };
}

function ps_page_testimonial_settings($variant) {
    $legacy_variant = $variant === 'seo-testimonial' && !is_page('seo') ? 'home-testimonial' : $variant;
    $settings = get_option('ps_page_testimonial_settings_v1')
        ? array_fill_keys(['heading', 'description', 'button_label', 'button_url'], '')
        : ps_testimonial_settings($legacy_variant);
    $group_name = ps_testimonial_page_group($variant);
    if ($group_name === '') return $settings;

    $values = function_exists('get_field') ? (get_field($group_name) ?: []) : [];
    foreach ($settings as $name => $_) {
        $meta = $group_name . '_' . $name;
        if (metadata_exists('post', get_the_ID(), $meta)) {
            $settings[$name] = (string) ($values[$name] ?? get_post_meta(get_the_ID(), $meta, true));
        }
    }
    return $settings;
}

add_action('acf/init', function () {
    foreach ([
        ['home', 'group_ps_home', 5],
        ['case', 'group_ps_case-study-detail', 3],
    ] as [$type, $parent, $order]) {
        $key = 'field_ps_' . $type . '_testimonial_settings';
        acf_add_local_field([
            'key' => $key,
            'name' => ps_testimonial_page_group($type === 'home' ? 'home-testimonial' : 'case-study-detail-testimonial'),
            'label' => 'Testimonial Section',
            'type' => 'group', 'layout' => 'block', 'menu_order' => $order,
            'instructions' => 'Edit the introduction and button for this page’s testimonial section. Shared testimonials are managed in Site Options.',
            'parent' => $parent,
            'sub_fields' => [
                ['key' => $key . '_heading', 'name' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => $key . '_description', 'name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 4],
                ['key' => $key . '_button_label', 'name' => 'button_label', 'label' => 'Button Label', 'type' => 'text'],
                ['key' => $key . '_button_url', 'name' => 'button_url', 'label' => 'Button URL', 'type' => 'text'],
            ],
        ]);
    }
});

// Preserve each page's currently displayed values before retiring the global controls.
add_action('admin_init', function () {
    if (!current_user_can('manage_options') || !get_option('ps_acf_options_v1')) return;

    if (!get_option('ps_page_testimonial_settings_v1')) {
        $front_id = (int) get_option('page_on_front');
        foreach (get_posts(['post_type' => ['page', 'case_study'], 'post_status' => 'any', 'numberposts' => -1]) as $post) {
            $template = get_post_meta($post->ID, '_wp_page_template', true);
            if ($post->post_type === 'page' && ps_is_service_detail($post->ID)) {
                $variant = 'seo-testimonial';
                $source = $post->post_name === 'seo' ? $variant : 'home-testimonial';
            } elseif ($post->post_type === 'page' && ($post->ID === $front_id || $template === 'page-templates/home.php')) {
                $variant = $source = 'home-testimonial';
            } elseif ($post->post_type === 'case_study' || $template === 'page-templates/case-study-detail.php') {
                $variant = $source = 'case-study-detail-testimonial';
            } else {
                continue;
            }

            $group_name = ps_testimonial_page_group($variant);
            $group_key = match ($variant) {
                'home-testimonial' => 'field_ps_home_testimonial_settings',
                'case-study-detail-testimonial' => 'field_ps_case_testimonial_settings',
                default => 'field_ps_service_testimonial_intro',
            };
            $source_values = ps_testimonial_settings($source);
            foreach ($source_values as $name => $value) {
                $meta = $group_name . '_' . $name;
                if (metadata_exists('post', $post->ID, $meta)) continue;
                update_post_meta($post->ID, $meta, wp_slash($value));
                update_post_meta($post->ID, '_' . $meta, $group_key . '_' . $name);
            }
            update_post_meta($post->ID, '_' . $group_name, $group_key);
        }
        update_option('ps_page_testimonial_settings_v1', 1, false);
    }

    // ACF can retain duplicate database fields from earlier options migrations.
    // Move every copy so no section setting remains on the shared options editor.
    if (get_option('ps_page_testimonial_settings_fields_archived_v1')) return;
    $archive = acf_get_field_group('group_ps_archived_testimonial_section_settings');
    if (!$archive) $archive = acf_update_field_group([
        'key' => 'group_ps_archived_testimonial_section_settings',
        'title' => 'Archived — Testimonial Section Settings',
        'active' => false, 'location' => [],
    ]);
    if (empty($archive['ID'])) return;
    global $wpdb;
    foreach (['home', 'seo', 'case'] as $type) {
        foreach (['heading', 'description', 'button_label', 'button_url'] as $name) {
            $key = 'field_ps_shared_testimonials_' . $type . '_' . $name;
            $ids = $wpdb->get_col($wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'acf-field' AND post_status = 'publish' AND post_name = %s",
                $key
            ));
            foreach ($ids as $id) {
                if ((int) get_post($id)->post_parent === (int) $archive['ID']) continue;
                if (is_wp_error(wp_update_post(['ID' => (int) $id, 'post_parent' => (int) $archive['ID']], true))) return;
            }
        }
    }
    update_option('ps_page_testimonial_settings_fields_archived_v1', 1, false);
}, 145);

// Hide archived settings on installations awaiting their first admin visit.
foreach (['home', 'seo', 'case'] as $type) {
    foreach (['heading', 'description', 'button_label', 'button_url'] as $name) {
        add_filter('acf/prepare_field/key=field_ps_shared_testimonials_' . $type . '_' . $name, '__return_false');
    }
}
