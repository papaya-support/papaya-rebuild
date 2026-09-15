<?php
/**
 * Template Name: Papaya — Blog
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-blog">
<?php get_template_part('template-parts/sections/hero', null, [
    'class' => 'section-blog-hero listing-hero',
    'container_class' => 'container intro center',
    'items' => [[
        'type' => 'text',
        'field' => 'blog_2f0c8cf2c985',
        'tag' => 'h1',
        'class' => '',
    ], [
        'type' => 'text',
        'field' => 'blog_37a359563ced',
        'tag' => 'div',
        'class' => 'prose ',
    ]],
    'image' => 'blog_image_a770b85be1',
]); ?>
<?php get_template_part('template-parts/sections/blog-filters'); ?>
<?php get_template_part('template-parts/sections/post-grid', null, [
    'class' => 'section-blog-cards section section-tight',
    'cards' => [[
        'image' => 'blog_image_fa29e64549',
        'title' => 'blog_1249a2f105cc',
        'tag' => 'h2',
        'url' => ps_route('blog-detail'),
        'category' => 'blog_1249a2f105cc_category',
        'excerpt' => 'blog_2c5657a9caa5',
    ], [
        'image' => 'blog_image_a1f7944375',
        'title' => 'blog_f52e28904d6f',
        'tag' => 'h2',
        'url' => ps_route('blog-detail'),
        'category' => 'blog_f52e28904d6f_category',
        'excerpt' => 'blog_a8e77dee2a51',
    ], [
        'image' => 'blog_image_1e16218537',
        'title' => 'blog_99693f626393',
        'tag' => 'h2',
        'url' => ps_route('blog-detail'),
        'category' => 'blog_99693f626393_category',
        'excerpt' => 'blog_3daccb27a0a0',
    ], [
        'image' => 'blog_image_70787a206a',
        'title' => 'blog_4544074113f3',
        'tag' => 'h2',
        'url' => ps_route('blog-detail'),
        'category' => 'blog_4544074113f3_category',
        'excerpt' => 'blog_cf0b8391a4e7',
    ], [
        'image' => 'blog_image_c43d00948a',
        'title' => 'blog_077d3e00d0d6',
        'tag' => 'h2',
        'url' => ps_route('blog-detail'),
        'category' => 'blog_077d3e00d0d6_category',
        'excerpt' => 'blog_cba7a4019382',
    ], [
        'image' => 'blog_image_509af40fcd',
        'title' => 'blog_c5f77c3ce3f5',
        'tag' => 'h2',
        'url' => ps_route('blog-detail'),
        'category' => 'blog_c5f77c3ce3f5_category',
        'excerpt' => 'blog_df6295346605',
    ], [
        'image' => 'blog_image_cc7f21f187',
        'title' => 'blog_4b9efd521840',
        'tag' => 'h2',
        'url' => ps_route('blog-detail'),
        'category' => 'blog_4b9efd521840_category',
        'excerpt' => 'blog_ea9fcdeea6f4',
    ], [
        'image' => 'blog_image_8989ff6955',
        'title' => 'blog_9545cdf5b05f',
        'tag' => 'h2',
        'url' => ps_route('blog-detail'),
        'category' => 'blog_9545cdf5b05f_category',
        'excerpt' => 'blog_5c38a4f149da',
    ], [
        'image' => 'blog_image_4e3d7d5c57',
        'title' => 'blog_55b16823b966',
        'tag' => 'h2',
        'url' => ps_route('blog-detail'),
        'category' => 'blog_55b16823b966_category',
        'excerpt' => 'blog_b58b9119f729',
    ]],
    'grid_class' => 'container grid grid-three post-grid',
]); ?>
<?php get_template_part('template-parts/sections/blog-more'); ?>
</main>
<?php endwhile;
get_footer();
