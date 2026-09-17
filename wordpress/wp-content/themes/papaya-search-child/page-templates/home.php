<?php
/**
 * Template Name: Papaya — Home
 * Template Post Type: page
 */
defined('ABSPATH') || exit();
get_header();

while (have_posts()):
    the_post();
    ?>
    <main tabindex="-1" id="main-content" class="page-home">
        <?php get_template_part('template-parts/sections/hero', null, ['variant' => 'home-hero']); ?>
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'home-introduction']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'home-search-strategy']); ?>
        <?php get_template_part('template-parts/sections/service-grid', null, ['variant' => 'home-services']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'home-search-trends']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'home-future-strategy']); ?>
        <?php get_template_part('template-parts/sections/testimonial', null, ['variant' => 'home-testimonial']); ?>
        <?php get_template_part('template-parts/sections/statistics', null, ['variant' => 'home-results']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'home-closing-cta']); ?>
    </main>
    <?php
endwhile;

get_footer();
