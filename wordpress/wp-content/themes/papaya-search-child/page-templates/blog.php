<?php
/**
 * Template Name: Papaya — Blog
 * Template Post Type: page
 */
defined('ABSPATH') || exit();
get_header();

while (have_posts()):
    the_post();
    ?>
    <main tabindex="-1" id="main-content" class="page-blog">
        <?php get_template_part('template-parts/sections/hero', null, ['variant' => 'blog-hero']); ?>
        <?php get_template_part('template-parts/sections/blog-listing'); ?>
    </main>
    <?php
endwhile;

get_footer();
