<?php
/** Admin-only labels and visual grouping for ACF content editors. */
defined('ABSPATH') || exit();

function ps_is_content_editor_screen() {
    if (!is_admin() || !function_exists('get_current_screen')) return false;
    $screen = get_current_screen();
    if (!$screen) return false;

    if ($screen->base === 'post') return $screen->post_type !== 'acf-field-group';

    return isset($_GET['page']) && sanitize_key(wp_unslash($_GET['page'])) === 'papaya-header-options';
}

add_action('admin_enqueue_scripts', function () {
    if (!ps_is_content_editor_screen()) return;
    $path = get_stylesheet_directory() . '/assets/acf-editor.css';
    wp_enqueue_style(
        'papaya-acf-editor',
        get_stylesheet_directory_uri() . '/assets/acf-editor.css',
        [],
        filemtime($path)
    );
});

add_filter('acf/prepare_field', function ($field) {
    if (!ps_is_content_editor_screen()) return $field;

    // Some legacy ACF keys retain labels from unrelated fields. Only their
    // display labels change; their keys, names, values, and types remain intact.
    $labels = [
        'location' => 'Analytics Service — Title',
        'website-analytics' => 'Future Strategy — Button Label',
        'field_home_introduction_description' => 'Introduction — Description',
        'field_home_results_introduction' => 'Results — Introduction',
        'field_home_image_0aad8a4a0f' => 'Closing CTA — Image',
    ];
    for ($number = 1; $number <= 3; $number++) {
        $labels['field_home_strategy_card_heading_' . $number] = 'Strategy Card ' . $number . ' — Heading';
        $labels['field_home_strategy_card_description_' . $number] = 'Strategy Card ' . $number . ' — Description';
    }
    if (isset($labels[$field['key'] ?? ''])) $field['label'] = $labels[$field['key']];

    // The original instruction exposes a template filename to editors. Give
    // all section groups the same content-focused description instead.
    if (($field['type'] ?? '') === 'group'
        && str_starts_with($field['key'] ?? '', 'field_ps_section_')
        && str_starts_with($field['instructions'] ?? '', 'Content for the ')
        && str_contains($field['instructions'], 'Layout is controlled by the shared ')) {
        $field['instructions'] = 'Edit the content for this page section below.';
    }

    return $field;
}, 20);
