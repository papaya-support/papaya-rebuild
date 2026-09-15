<?php
/**
 * Template Name: Papaya — Blog Detail
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-blog-detail">
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-blog-detail-heading section',
    'container_class' => 'container article-heading',
    'items' => [[
        'type' => 'text',
        'field' => 'blog_detail_9c13381244a8',
        'tag' => 'p',
        'class' => 'breadcrumb',
    ], [
        'type' => 'text',
        'field' => 'blog_detail_8c780093d5a1',
        'tag' => 'h1',
        'class' => '',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-blog-detail-featured-image section section-tight',
    'container_class' => 'container',
    'items' => [[
        'type' => 'image',
        'field' => 'blog_detail_image_33017e3b40',
        'class' => 'article-featured',
        'eager' => true,
    ]],
]); ?>
<?php get_template_part('template-parts/sections/blog-detail-article-body'); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-blog-detail-local-rankings section',
    'columns' => [[
        'class' => 'split-media',
        'wrapper' => '',
        'items' => [[
            'type' => 'image',
            'field' => 'blog_detail_image_82ae55901c',
            'class' => '',
            'eager' => false,
        ]],
    ], [
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'blog_detail_bcb4f48e1767',
            'tag' => 'div',
            'class' => 'prose ',
        ]],
    ]],
    'heading' => [[
        'type' => 'text',
        'field' => 'blog_detail_2c021570ce1c',
        'tag' => 'h2',
        'class' => 'section-title',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/post-grid', null, [
    'class' => 'section-blog-detail-related-posts section related-posts',
    'cards' => [[
        'image' => 'blog_detail_image_e66cb91b30',
        'title' => 'blog_detail_4d181476ee16',
        'tag' => 'h3',
        'url' => ps_route('blog-detail'),
    ], [
        'image' => 'blog_detail_image_e850bb0d10',
        'title' => 'blog_detail_8c5ed5ef437b',
        'tag' => 'h3',
        'url' => ps_route('blog-detail'),
    ], [
        'image' => 'blog_detail_image_e6018f7e64',
        'title' => 'blog_detail_1fab6576ba8d',
        'tag' => 'h3',
        'url' => ps_route('blog-detail'),
    ]],
    'heading' => [[
        'type' => 'text',
        'field' => 'blog_detail_1624e4d9861e',
        'tag' => 'h2',
        'class' => 'section-title',
    ]],
    'grid_class' => 'grid grid-three',
]); ?>
</main>
<?php endwhile;
get_footer();
