<?php
defined('ABSPATH') || exit();

/** Published posts, filtered by WordPress category and paginated on the Blog page. */
function ps_blog_listing()
{
    $slug =
        isset($_GET['blog_category']) && is_string($_GET['blog_category'])
            ? sanitize_title(wp_unslash($_GET['blog_category']))
            : '';
    $category = $slug ? get_term_by('slug', $slug, 'category') : false;
    $page =
        isset($_GET['blog_page']) && is_scalar($_GET['blog_page'])
            ? max(1, absint($_GET['blog_page']))
            : 1;
    $query = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 9,
        'paged' => $page,
        'ignore_sticky_posts' => true,
        'ps_blog_listing' => true,
        'orderby' => ['date' => 'DESC', 'ID' => 'DESC'],
    ];
    if ($category) {
        $query['tax_query'] = [
            [
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => [$category->term_id],
                'include_children' => true,
            ],
        ];
    } elseif ($slug) {
        $query['post__in'] = [0];
    }
    return [
        'query' => new WP_Query($query),
        'category' => $category,
        'slug' => $slug,
        'page' => $page,
        'url' => get_permalink(get_queried_object_id()),
    ];
}

/** Retire the old static card/filter editors while preserving their saved metadata. */
function ps_blog_legacy_field($name)
{
    return str_starts_with($name, 'blog_') &&
        !str_starts_with($name, 'blog_detail_') &&
        !in_array($name, ['blog_2f0c8cf2c985', 'blog_37a359563ced', 'blog_image_a770b85be1'], true);
}
add_action(
    'admin_init',
    function () {
        if (
            !current_user_can('manage_options') ||
            get_option('ps_dynamic_blog_fields_v1') ||
            !function_exists('acf_delete_field')
        ) {
            return;
        }
        $group = acf_get_field_group('group_ps_blog');
        if (!$group) {
            return;
        }
        $retired = [];
        foreach (acf_get_fields($group) ?: [] as $field) {
            if (ps_blog_legacy_field($field['name'])) {
                $retired[] = $field;
            }
        }
        add_option('ps_retired_blog_fields_v1', $retired, '', false);
        foreach ($retired as $field) {
            acf_delete_field($field['ID']);
        }
        flush_rewrite_rules(false);
        update_option('ps_dynamic_blog_fields_v1', 1, false);
    },
    9,
);

/** Pin native sticky posts in this listing without changing page size or duplicating them. */
add_filter(
    'posts_orderby',
    function ($orderby, $query) {
        if (!$query->get('ps_blog_listing')) {
            return $orderby;
        }
        $sticky = array_filter(array_map('absint', (array) get_option('sticky_posts', [])));
        if (!$sticky) {
            return $orderby;
        }
        global $wpdb;
        return $wpdb->posts . '.ID IN (' . implode(',', $sticky) . ') DESC, ' . $orderby;
    },
    10,
    2,
);

/** Use the XD placeholder only when a post has no assigned featured image. */
function ps_post_image($class = '', $eager = false)
{
    if (has_post_thumbnail()) {
        the_post_thumbnail('large', [
            'class' => $class,
            'loading' => $eager ? 'eager' : 'lazy',
            'fetchpriority' => $eager ? 'high' : 'auto',
        ]);
        return;
    }
    echo '<img src="' .
        esc_url(ps_asset('35cc479761601be986b3f8891766eec2.png')) .
        '" class="' .
        esc_attr($class) .
        '" alt="" width="1920" height="1440" loading="' .
        ($eager ? 'eager' : 'lazy') .
        '" decoding="async">';
}
