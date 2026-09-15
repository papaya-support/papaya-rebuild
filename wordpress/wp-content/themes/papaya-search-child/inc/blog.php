<?php
defined('ABSPATH') || exit;

/** Published posts, filtered by WordPress category and paginated on the Blog page. */
function ps_blog_listing() {
    $slug = isset($_GET['blog_category']) && is_string($_GET['blog_category']) ? sanitize_title(wp_unslash($_GET['blog_category'])) : '';
    $category = $slug ? get_term_by('slug', $slug, 'category') : false;
    $page = isset($_GET['blog_page']) && is_scalar($_GET['blog_page']) ? max(1, absint($_GET['blog_page'])) : 1;
    $query = ['post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>9, 'paged'=>$page, 'ignore_sticky_posts'=>true, 'orderby'=>['date'=>'DESC','ID'=>'DESC']];
    if ($category) {$query['tax_query'] = [['taxonomy'=>'category','field'=>'term_id','terms'=>[$category->term_id],'include_children'=>true]];}
    elseif ($slug) {$query['post__in'] = [0];}
    return ['query'=>new WP_Query($query), 'category'=>$category, 'slug'=>$slug, 'page'=>$page, 'url'=>get_permalink(get_queried_object_id())];
}

/** Retire the old static card/filter editors while preserving their saved metadata. */
function ps_blog_legacy_field($name) {
    return str_starts_with($name, 'blog_') && !str_starts_with($name, 'blog_detail_') && !in_array($name, ['blog_2f0c8cf2c985','blog_37a359563ced','blog_image_a770b85be1'], true);
}
add_action('admin_init', function () {
    if (!current_user_can('manage_options') || get_option('ps_dynamic_blog_fields_v1') || !function_exists('acf_delete_field')) {return;}
    $group = acf_get_field_group('group_ps_blog');
    if (!$group) {return;}
    $retired = [];
    foreach (acf_get_fields($group) ?: [] as $field) {
        if (ps_blog_legacy_field($field['name'])) {$retired[]=$field;}
    }
    add_option('ps_retired_blog_fields_v1', $retired, '', false);
    foreach ($retired as $field) {acf_delete_field($field['ID']);}
    flush_rewrite_rules(false);
    update_option('ps_dynamic_blog_fields_v1', 1, false);
}, 9);
