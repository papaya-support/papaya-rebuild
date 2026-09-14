<?php
defined('ABSPATH') || exit;
function ps_initial_content($slug=null) {
    static $pages;
    $pages ??= require __DIR__.'/initial-content.php';
    return $slug===null ? $pages : ($pages[$slug]??null);
}
function ps_pages() {
    return array_values(array_map(fn($page)=>['name'=>$page['name'],'slug'=>$page['slug']],ps_initial_content()));
}
function ps_shared_id() { return (int) get_option('ps_shared_content_id'); }
function ps_value($key, $default = '', $scope = 'page', $post_id = null) {
    $id = $scope === 'page' ? ($post_id ?: get_the_ID()) : ps_shared_id();
    // Check existence to distinguish intentionally cleared content from an unset field.
    if ($id && metadata_exists('post', $id, $key)) {
        return function_exists('get_field') ? get_field($key, $id, false) : get_post_meta($id, $key, true);
    }
    return $default;
}
function ps_asset($asset) { return get_stylesheet_directory_uri() . '/assets/images/' . $asset; }
function ps_route($slug) {
    if ($slug === 'home') { return home_url('/'); }
    $pages = get_option('ps_page_ids', []);
    return !empty($pages[$slug]) ? get_permalink($pages[$slug]) : home_url('/' . $slug . '/');
}
function ps_destinations() {
    return [
        'Home' => ps_route('home'), 'Services' => ps_route('services'), 'Case Studies' => ps_route('case-studies'),
        'About' => ps_route('about'), 'Blog' => ps_route('blog'),
        'Free Tools' => 'https://papayasearch.com/tools/', 'Careers' => 'https://papayasearch.com/careers/',
        'AI SEO' => 'https://papayasearch.com/services/ai-seo-aeo-services/', 'SEO' => 'https://papayasearch.com/services/seo/',
        'SEO for SaaS' => 'https://papayasearch.com/industry/saas-seo-agency/',
        'Atlanta SEO' => 'https://papayasearch.com/atlanta-seo-agency/', 'Atlanta SEM' => 'https://papayasearch.com/atlanta-sem-agency/',
        'PPC' => ps_route('search-engine-marketing'), 'Contact Us' => ps_value('ps_contact_url', 'tel:+14044259775', 'shared'),
        'Privacy Policy' => 'https://papayasearch.com/privacy-policy/', 'Terms & Conditions' => 'https://papayasearch.com/terms-and-conditions/',
    ];
}
