<?php
/**
 * Template Name: Papaya — Home
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-home">
<?php get_template_part('template-parts/sections/home-hero'); ?>
<?php get_template_part('template-parts/sections/home-introduction'); ?>
<?php get_template_part('template-parts/sections/home-search-strategy'); ?>
<?php get_template_part('template-parts/sections/home-services'); ?>
<?php get_template_part('template-parts/sections/home-search-trends'); ?>
<?php get_template_part('template-parts/sections/home-future-strategy'); ?>
<?php get_template_part('template-parts/sections/home-testimonial'); ?>
<?php get_template_part('template-parts/sections/home-results'); ?>
<?php get_template_part('template-parts/sections/home-closing-cta'); ?>
</main>
<?php endwhile;
get_footer();
