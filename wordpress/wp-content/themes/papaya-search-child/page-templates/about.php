<?php
/**
 * Template Name: Papaya — About
 * Template Post Type: page
 */
defined('ABSPATH') || exit();
get_header();

while (have_posts()):
    the_post();
    ?>
    <main tabindex="-1" id="main-content" class="page-about">
        <?php get_template_part('template-parts/sections/banner', null, ['variant' => 'about-banner']); ?>
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'about-introduction']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'about-audience-strategy']); ?>
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'about-partnership-heading']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'about-partnership']); ?>
        <?php get_template_part('template-parts/sections/about-process'); ?>
        <?php get_template_part('template-parts/sections/about-team'); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'about-brand-story']); ?>
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'about-closing-cta']); ?>
    </main>
    <?php
endwhile;

get_footer();
