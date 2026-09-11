<?php
defined('ABSPATH') || exit;
function ps_pages() {
    static $pages;
    return $pages ?? ($pages = json_decode(file_get_contents(__DIR__ . '/../design/pages.json'), true));
}
function ps_design($slug) {
    static $cache = [];
    $allowed = array_column(ps_pages(), 'slug');
    if (!in_array($slug, $allowed, true)) { return null; }
    return $cache[$slug] ?? ($cache[$slug] = json_decode(file_get_contents(__DIR__ . '/../design/' . $slug . '.json'), true));
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
function ps_image_url($image, $post_id = null) {
    $value = ps_value($image['key'], '', $image['scope'], $post_id);
    if (is_numeric($value) && $value) { return wp_get_attachment_image_url((int)$value, 'full') ?: ps_asset($image['asset']); }
    return is_string($value) && preg_match('#^https?://#', $value) ? $value : ps_asset($image['asset']);
}
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
function ps_link_default($t, $slug) {
    $label = trim($t['text']);
    if (preg_match('/^(Get Started|Schedule|Take the first)/i', $label)) { return ps_value('ps_booking_url', 'tel:+14044259775', 'shared'); }
    if ($t['scope'] === 'header' || in_array($label, ['Case Studies','Careers'], true)) { return ps_destinations()[$label] ?? ''; }
    if ($label === 'Read More' || $label === 'Learn More') {
        if ($slug === 'about') { return 'https://papayasearch.com/team/'; }
        if ($slug === 'home') {
            $i = max(0,min(3,(int)floor(($t['x']-100)/280)));
            return [ps_route('services').'#search-engine-optimization',ps_route('search-engine-marketing'),ps_route('services').'#website-analytics',ps_destinations()['AI SEO']][$i];
        }
        return $t['x'] > 600 && $t['y'] < 1500 ? ps_route('search-engine-marketing') : ps_route('services').'#'.($t['y'] > 1500 ? ($t['x'] > 600 ? 'wordpress-maintenance' : 'website-analytics') : 'search-engine-optimization');
    }
    if (str_starts_with($label, 'Lorem ipsum') && $t['font']['weight'] >= 700) { return ps_route(str_contains($slug, 'case') ? 'case-study-detail' : 'blog-detail'); }
    return '';
}
function ps_is_faq($t, $slug) { return $slug === 'search-engine-marketing' && $t['y'] > 4100 && $t['y'] < 4750 && str_contains($t['text'], '?'); }
function ps_blog_cards() {
    $d=ps_design('blog');$titles=array_values(array_filter($d['texts'],fn($t)=>$t['scope']==='page'&&str_starts_with($t['text'],'Lorem ipsum')&&$t['font']['weight']>=700));
    $cards=[];
    foreach($titles as $i=>$title) {
        $fields=[$title['key']];$image=null;
        foreach($d['texts'] as $t) {if(str_starts_with($t['text'],'Excepteur')&&abs($t['x']-$title['x'])<20&&$t['y']>$title['y']&&$t['y']<$title['y']+180){$fields[]=$t['key'];}}
        foreach($d['images'] as $im) {if(abs($im['x']-$title['x'])<20&&$im['y']<$title['y']&&$im['y']>$title['y']-400){$image=$im;}}
        $cards[]=['fields'=>$fields,'image'=>$image?$image['key']:'','x'=>$title['x'],'y'=>$title['y'],'category'=>ps_value($title['key'].'_category','SEO')];
    }
    return $cards;
}
