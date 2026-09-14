<?php
/**
 * Template Name: Papaya — About
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-about">
<?php get_template_part('template-parts/sections/about-banner'); ?>
<?php get_template_part('template-parts/sections/about-introduction'); ?>
<?php get_template_part('template-parts/sections/about-audience-strategy'); ?>
<?php get_template_part('template-parts/sections/about-partnership-heading'); ?>
<?php get_template_part('template-parts/sections/about-partnership'); ?>
<?php get_template_part('template-parts/sections/about-process'); ?>
<?php get_template_part('template-parts/sections/about-team'); ?>
<?php get_template_part('template-parts/sections/about-brand-story'); ?>
<?php get_template_part('template-parts/sections/about-closing-cta'); ?>
</main>
<?php endwhile;
get_footer();
