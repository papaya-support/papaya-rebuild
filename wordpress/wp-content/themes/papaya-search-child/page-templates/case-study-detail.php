<?php
/**
 * Template Name: Papaya — Case Study Detail
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-case-study-detail">
<?php get_template_part('template-parts/sections/case-study-detail-heading'); ?>
<?php get_template_part('template-parts/sections/case-study-detail-featured-image'); ?>
<?php get_template_part('template-parts/sections/case-study-detail-results'); ?>
<?php get_template_part('template-parts/sections/case-study-detail-testimonial'); ?>
<?php get_template_part('template-parts/sections/case-study-detail-challenge'); ?>
<?php get_template_part('template-parts/sections/case-study-detail-follow-up'); ?>
</main>
<?php endwhile;
get_footer();
