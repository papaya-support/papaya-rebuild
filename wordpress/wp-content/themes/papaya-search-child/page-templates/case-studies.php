<?php
/**
 * Template Name: Papaya — Case Study
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-case-studies">
<?php get_template_part('template-parts/sections/case-studies-introduction'); ?>
<?php get_template_part('template-parts/sections/case-studies-cards'); ?>
</main>
<?php endwhile;
get_footer();
