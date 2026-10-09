<?php
/** Local service destinations and a one-time native menu update. */
defined('ABSPATH') || exit();

function ps_local_service_url($url)
{
    if (!is_string($url) || $url === '') {
        return $url;
    }
    $parts = wp_parse_url($url);
    $host = strtolower($parts['host'] ?? '');
    if ($host && !in_array($host, ['papayasearch.com', 'www.papayasearch.com', wp_parse_url(home_url(), PHP_URL_HOST)], true)) {
        return $url;
    }
    $path = trim($parts['path'] ?? '', '/');
    $fragment = $parts['fragment'] ?? '';
    // The original Gravity Forms download remains on the source site.
    if (str_starts_with($fragment, 'gform')) {
        return $url;
    }
    $anchors = [
        'search-engine-optimization' => 'seo',
        'website-analytics' => 'website-analytics',
        'wordpress-maintenance' => 'website-maintenance',
    ];
    if ($path === 'services' && isset($anchors[$fragment])) {
        $path = 'services/' . $anchors[$fragment];
        $fragment = '';
    }
    if ($path === 'search-engine-marketing') {
        $path = 'services/search-engine-marketing';
    }
    $paths = array_column(ps_service_import_data()['pages'], 'path');
    if (!in_array($path, $paths, true)) {
        return $url;
    }
    $page = get_page_by_path($path);
    if (!$page || $page->post_status !== 'publish') {
        return $url;
    }
    return get_permalink($page) . (!empty($parts['query']) ? '?' . $parts['query'] : '') . ($fragment !== '' ? '#' . $fragment : '');
}

function ps_local_service_links($html)
{
    $processor = new WP_HTML_Tag_Processor($html);
    while ($processor->next_tag('a')) {
        $url = $processor->get_attribute('href');
        $local = ps_local_service_url($url);
        if ($local !== $url) {
            $processor->set_attribute('href', $local);
        }
    }
    return $processor->get_updated_html();
}
add_filter('the_content', 'ps_local_service_links', 30);
add_filter('nav_menu_link_attributes', function ($attributes) {
    if (isset($attributes['href'])) {
        $attributes['href'] = ps_local_service_url($attributes['href']);
    }
    return $attributes;
});

function ps_install_service_navigation()
{
    if (get_option('ps_service_navigation_v1')) {
        return true;
    }
    $pages = [];
    foreach (ps_service_import_data()['pages'] as $source) {
        $page = get_page_by_path($source['path']);
        if (!$page || $page->post_status !== 'publish') {
            return false; // Wait until every service page has been imported.
        }
        $pages[$source['path']] = $page;
    }
    $locations = get_nav_menu_locations();
    $menu_id = $locations['primary'] ?? 0;
    if (!$menu_id) {
        return false;
    }
    $items = wp_get_nav_menu_items($menu_id) ?: [];
    add_option('ps_service_navigation_backup_v1', $items, '', false);
    $services = get_page_by_path('services');
    if (!$services) {
        return false;
    }
    $labels = [
        'services' => 'Services',
        'services/ai-seo-aeo-services' => 'AI SEO',
        'services/seo' => 'SEO',
        'services/seo/audit' => 'SEO Audit',
        'services/local-seo' => 'Local SEO',
        'services/local-seo/gbp-optmization' => 'Google Business Profile Optimization',
        'services/local-seo/gbp-reinstatement' => 'Google Business Profile Reinstatement',
        'services/search-engine-marketing' => 'Search Engine Marketing',
        'services/website-analytics' => 'Website Analytics',
        'services/website-maintenance' => 'Website Maintenance',
    ];
    $menu_ids = [];
    foreach (['services' => $services] + $pages as $path => $page) {
        $existing = 0;
        foreach ($items as $item) {
            if ((int) $item->object_id === $page->ID || untrailingslashit(ps_local_service_url($item->url)) === untrailingslashit(get_permalink($page))) {
                $existing = $item->ID;
                break;
            }
        }
        if ($path === 'services' && $existing) {
            $menu_ids[$path] = $existing;
            continue;
        }
        $result = wp_update_nav_menu_item($menu_id, $existing, [
            'menu-item-title' => $labels[$path],
            'menu-item-object-id' => $page->ID,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => $menu_ids[dirname($path)] ?? 0,
        ]);
        if (is_wp_error($result)) {
            return $result;
        }
        $menu_ids[$path] = $result;
    }
    // Existing footer links become native page items without changing labels/order.
    foreach (array_unique(array_values($locations)) as $id) {
        foreach (wp_get_nav_menu_items($id) ?: [] as $item) {
            $local = ps_local_service_url($item->url);
            if ($local === $item->url) {
                continue;
            }
            $page_id = url_to_postid($local);
            if ($page_id && !wp_parse_url($local, PHP_URL_QUERY) && !wp_parse_url($local, PHP_URL_FRAGMENT)) {
                update_post_meta($item->ID, '_menu_item_type', 'post_type');
                update_post_meta($item->ID, '_menu_item_object', 'page');
                update_post_meta($item->ID, '_menu_item_object_id', $page_id);
                update_post_meta($item->ID, '_menu_item_url', $local);
                clean_post_cache($item->ID);
            }
        }
    }
    update_option('ps_service_navigation_v1', 1, false);
    return true;
}
add_action('admin_init', function () {
    if (current_user_can('edit_theme_options')) {
        ps_install_service_navigation();
    }
}, 50);
