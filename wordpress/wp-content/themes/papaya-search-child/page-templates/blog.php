<?php
/**
 * Template Name: Papaya — Blog
 * Template Post Type: page
 */
defined('ABSPATH') || exit();
get_header();
while (have_posts()):
    the_post(); ?>
<main tabindex="-1" id="main-content" class="page-blog">
<?php get_template_part('template-parts/sections/hero', null, [
    'class' => 'section-blog-hero listing-hero',
    'container_class' => 'container intro center',
    'items' => [
        [
            'type' => 'text',
            'field' => 'blog_2f0c8cf2c985',
            'tag' => 'h1',
            'class' => '',
        ],
        [
            'type' => 'text',
            'field' => 'blog_37a359563ced',
            'tag' => 'div',
            'class' => 'prose ',
        ],
    ],
    'image' => 'blog_image_a770b85be1',
]); ?>
<?php
$listing = ps_blog_listing();
get_template_part('template-parts/sections/blog-filters', null, ['listing' => $listing]);
get_template_part('template-parts/sections/posts', null, ['listing' => $listing]);
?>
</main>
<?php
endwhile;
get_footer();
