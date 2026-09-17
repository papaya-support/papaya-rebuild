<?php
/**
 * Template Name: Papaya — Services-Landing
 * Template Post Type: page
 */
defined('ABSPATH') || exit();
get_header();

while (have_posts()):
    the_post();
    ?>
    <main tabindex="-1" id="main-content" class="page-services">
        <?php get_template_part('template-parts/sections/banner', null, ['variant' => 'services-banner']); ?>
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'services-introduction']); ?>
        <?php get_template_part('template-parts/sections/service-grid', null, ['variant' => 'services-service-grid']); ?>
        <?php get_template_part('template-parts/sections/statistics', null, ['variant' => 'services-closing-cta']); ?>
    </main>
    <?php
endwhile;

get_footer();
