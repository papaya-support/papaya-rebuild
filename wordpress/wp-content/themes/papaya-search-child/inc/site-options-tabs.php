<?php
/** Combine the existing Header, Footer, and Testimonials editors on one ACF options page. */
defined('ABSPATH') || exit();

function ps_install_site_options_tabs() {
    if (get_option('ps_site_options_tabs_v1')) return true;
    if (!get_option('ps_acf_options_v1') || !function_exists('acf_update_field_group') ||
        !function_exists('acf_get_setting') || !acf_get_setting('pro')) return false;

    $sections = [
        ['header', 'group_ps_site_header', 'field_ps_section_header', 'Header', 0],
        ['footer', 'group_ps_shared', 'field_ps_section_shared_section_footer', 'Footer', 2],
        ['testimonials', 'group_ps_shared_testimonials', 'field_ps_shared_testimonials', 'Testimonials', 4],
    ];
    foreach ($sections as [, $group_key, $field_key]) {
        if (empty(acf_get_field_group($group_key)['ID']) || empty(acf_get_field($field_key)['ID'])) return false;
    }

    // Keep a recoverable copy of the original field-group definitions and option values.
    if (get_option('ps_site_options_tabs_backup_v1', null) === null) {
        $backup = [];
        foreach ($sections as [, $group_key]) $backup[$group_key] = acf_get_field_group($group_key);
        add_option('ps_site_options_tabs_backup_v1', $backup, '', false);
    }

    global $wpdb;
    foreach (['header', 'footer', 'testimonials'] as $scope) {
        foreach (['ps_' . $scope . '_', '_ps_' . $scope . '_'] as $prefix) {
            $names = $wpdb->get_col($wpdb->prepare(
                "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
                $wpdb->esc_like($prefix) . '%'
            ));
            foreach ($names as $source) {
                $name = substr($source, strlen($prefix));
                if (!str_starts_with($name, 'section_') && !($scope === 'footer' && str_starts_with($name, 'shared_'))) continue;
                $target = ($source[0] === '_' ? '_ps_site_options_' : 'ps_site_options_') . $name;
                update_option($target, get_option($source), false);
            }
        }
    }

    $combined = acf_get_field_group('group_ps_site_options');
    if (!$combined) $combined = acf_update_field_group([
        'key' => 'group_ps_site_options',
        'title' => 'Site Options',
        'active' => true,
        'style' => 'seamless',
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'papaya-header-options']]],
    ]);
    if (empty($combined['ID'])) return false;

    foreach ($sections as [$scope, $group_key, $field_key, $label, $order]) {
        $tab = acf_get_field('field_ps_site_options_tab_' . $scope);
        if (!$tab) $tab = acf_update_field([
            'key' => 'field_ps_site_options_tab_' . $scope,
            'name' => '', 'label' => $label, 'type' => 'tab',
            'placement' => 'top', 'endpoint' => 0,
            'parent' => $combined['ID'], 'menu_order' => $order,
        ]);
        if (empty($tab['ID'])) return false;

        $field = acf_get_field($field_key);
        $field['parent'] = $combined['ID'];
        $field['menu_order'] = $order + 1;
        if (!acf_update_field(wp_slash($field))) return false;

        $old_group = acf_get_field_group($group_key);
        $old_group['active'] = false;
        if (!acf_update_field_group(wp_slash($old_group))) return false;
    }

    $keys = array_column(acf_get_fields($combined) ?: [], 'key');
    foreach ($sections as [$scope, , $field_key]) {
        if (!in_array('field_ps_site_options_tab_' . $scope, $keys, true) ||
            !in_array($field_key, $keys, true)) return false;
    }
    update_option('ps_site_options_tabs_v1', 1, false);
    return true;
}

add_action('init', 'ps_install_site_options_tabs', 250);

// Earlier releases kept the testimonial group field attached to its former page.
// Repair sites where that old one-time hierarchy action ran after consolidation.
add_action('init', function () {
    if (!get_option('ps_site_options_tabs_v1')) return;
    $combined = acf_get_field_group('group_ps_site_options');
    if (empty($combined['ID'])) return;
    foreach (['field_ps_section_header' => 1,
              'field_ps_section_shared_section_footer' => 3,
              'field_ps_shared_testimonials' => 5] as $key => $order) {
        $field = acf_get_field($key);
        if (empty($field['ID'])) continue;
        $post = get_post((int) $field['ID']);
        if ($post && ((int) $post->post_parent !== (int) $combined['ID'] || (int) $post->menu_order !== $order)) {
            wp_update_post(['ID' => (int) $field['ID'], 'post_parent' => (int) $combined['ID'], 'menu_order' => $order]);
            if (function_exists('acf_flush_field_cache')) acf_flush_field_cache($field);
        }
    }
}, 251);
