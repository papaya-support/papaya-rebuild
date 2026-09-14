<?php
/**
 * Template Name: Papaya — Blog
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-blog">
<?php get_template_part('template-parts/sections/blog-hero'); ?>
<?php get_template_part('template-parts/sections/blog-filters'); ?>
<?php get_template_part('template-parts/sections/blog-cards'); ?>
<?php get_template_part('template-parts/sections/blog-more'); ?>
</main>
<?php endwhile;
get_footer();
