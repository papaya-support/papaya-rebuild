<?php
/**
 * Template Name: Papaya — Blog Detail
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-blog-detail">
<?php get_template_part('template-parts/sections/blog-detail-heading'); ?>
<?php get_template_part('template-parts/sections/blog-detail-featured-image'); ?>
<?php get_template_part('template-parts/sections/blog-detail-article-body'); ?>
<?php get_template_part('template-parts/sections/blog-detail-local-rankings'); ?>
<?php get_template_part('template-parts/sections/blog-detail-related-posts'); ?>
</main>
<?php endwhile;
get_footer();
