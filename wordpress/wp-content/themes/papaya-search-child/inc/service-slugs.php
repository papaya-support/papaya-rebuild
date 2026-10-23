<?php
/** Move the service hierarchy to /services/ while retaining old incoming links. */
defined('ABSPATH') || exit();

function ps_service_slug_links($value) {
    if (is_array($value)) return array_map('ps_service_slug_links', $value);
    if (!is_string($value)) return $value;
    $hosts = array_unique([wp_parse_url(home_url(), PHP_URL_HOST), 'papaya-codex-build.mystagingwebsite.com', 'papayasearch.com', 'www.papayasearch.com']);
    foreach ($hosts as $host) {
        $value = preg_replace('~(https?://' . preg_quote($host, '~') . '(?::[0-9]+)?)/results(?=/|[?#\s\x22\x27<]|$)~i', '$1/services', $value);
    }
    return preg_replace('~(^|[\x22\x27])/results(?=/|[?#\x22\x27]|$)~', '$1/services', $value);
}

function ps_install_service_slugs() {
    if (get_option('ps_service_slugs_v1')) return true;
    $root = get_page_by_path('results');
    if (!$root) return false;
    $existing = get_page_by_path('services');
    if ($existing && $existing->ID !== $root->ID) return new WP_Error('services_conflict', 'A different Services page already exists.');
    $routes = [];
    foreach (get_posts(['post_type'=>'page', 'post_status'=>'any', 'numberposts'=>-1]) as $page) {
        if ($page->ID === $root->ID || in_array($root->ID, get_post_ancestors($page), true)) {
            $routes[trim(get_page_uri($page), '/')] = $page->ID;
        }
    }
    update_option('ps_service_old_routes', $routes, false);
    $result = wp_update_post(['ID'=>$root->ID, 'post_name'=>'services'], true);
    if (is_wp_error($result)) return $result;
    if (get_post($root->ID)->post_name !== 'services') return new WP_Error('slug_conflict', 'Could not set the Services slug.');
    global $wpdb;
    foreach ($wpdb->get_results("SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/results%'") as $row) {
        $old = maybe_unserialize($row->meta_value);
        $new = ps_service_slug_links($old);
        if ($old !== $new) update_metadata_by_mid('post', $row->meta_id, $new);
    }
    foreach ($wpdb->get_results("SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type IN ('page','post','case_study') AND post_content LIKE '%/results%'") as $row) {
        $new = ps_service_slug_links($row->post_content);
        if ($new !== $row->post_content) wp_update_post(wp_slash(['ID'=>$row->ID, 'post_content'=>$new]));
    }
    flush_rewrite_rules(false);
    update_option('ps_service_slugs_v1', 1, false);
    return true;
}
add_action('admin_init', function () {
    if (current_user_can('manage_options')) ps_install_service_slugs();
}, 95);

add_action('template_redirect', function () {
    if (!get_option('ps_service_slugs_v1')) return;
    $path = trim(wp_parse_url(wp_unslash($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), '/');
    $base = trim(wp_parse_url(home_url(), PHP_URL_PATH) ?? '', '/');
    if ($base !== '' && str_starts_with($path, $base . '/')) $path = substr($path, strlen($base) + 1);
    $routes = get_option('ps_service_old_routes', []);
    if (!isset($routes[$path]) || get_post_status($routes[$path]) !== 'publish') return;
    $target = get_permalink($routes[$path]);
    $query = wp_parse_url(wp_unslash($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_QUERY);
    if ($query) $target .= '?' . $query;
    wp_safe_redirect($target, 301, 'Papaya Service URLs');
    exit;
}, 1);
