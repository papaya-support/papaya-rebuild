<?php
/** Astra child theme. No WordPress or parent-theme files are modified. */
defined('ABSPATH') || exit;
require_once __DIR__ . '/inc/content.php';
require_once __DIR__ . '/inc/fields.php';
require_once __DIR__ . '/inc/template-tags.php';
require_once __DIR__ . '/inc/menus.php';
require_once __DIR__ . '/inc/setup.php';
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus(ps_menu_locations());
}, 20);
add_action('wp_enqueue_scripts', function () {
    // Semantic PHP templates use a single responsive stylesheet. Astra remains the parent.
    wp_dequeue_style('astra-theme-css');
    if (is_page_template() && str_starts_with(get_page_template_slug(), 'page-templates/')) {
        remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
        if (class_exists('Astra_Scroll_To_Top_Loader')) {
            remove_action('wp_footer', [Astra_Scroll_To_Top_Loader::get_instance(), 'html_markup_loader']);
        }
        // These ACF templates contain no block markup; their styles are bundled below.
        foreach (['wp-block-library', 'global-styles', 'wp-emoji-styles', 'wp-img-auto-sizes-contain'] as $handle) {
            wp_dequeue_style($handle);
        }
    }
    wp_enqueue_style('papaya-design', get_stylesheet_directory_uri() . '/assets/site.css', [], filemtime(__DIR__ . '/assets/site.css'));
    wp_enqueue_script('papaya-design', get_stylesheet_directory_uri() . '/assets/site.js', [], filemtime(__DIR__ . '/assets/site.js'), true);
}, 100);
add_filter('body_class', function ($classes) { $classes[] = 'papaya-site'; return $classes; });
// Keep Astra's script state consistent with the custom templates' omitted control.
add_filter('astra_get_option_scroll-to-top-enable', function ($enabled) {
    return !is_admin() && is_page_template() && str_starts_with(get_page_template_slug(), 'page-templates/') ? false : $enabled;
});
