<?php
/**
 * Template Name: Papaya — Services-Landing
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-services">
<?php get_template_part('template-parts/sections/services-banner'); ?>
<?php get_template_part('template-parts/sections/services-introduction'); ?>
<?php get_template_part('template-parts/sections/services-service-grid'); ?>
<?php get_template_part('template-parts/sections/services-closing-cta'); ?>
</main>
<?php endwhile;
get_footer();
