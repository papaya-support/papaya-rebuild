<?php
/**
 * Template Name: Papaya — Services-Individual
 * Template Post Type: page
 */
defined('ABSPATH') || exit();
get_header();

while (have_posts()):
    the_post();
    ?>
    <main tabindex="-1" id="main-content" class="page-search-engine-marketing">
        <?php get_template_part('template-parts/sections/banner', null, ['variant' => 'search-engine-marketing-banner']); ?>
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'search-engine-marketing-introduction']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'search-engine-marketing-visibility']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'search-engine-marketing-ppc-services']); ?>
        <?php get_template_part('template-parts/sections/statistics', null, ['variant' => 'search-engine-marketing-benefits']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'search-engine-marketing-google-ads']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'search-engine-marketing-microsoft-ads']); ?>
        <?php get_template_part('template-parts/sections/search-engine-marketing-faqs'); ?>
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'search-engine-marketing-closing-cta']); ?>
    </main>
    <?php
endwhile;

get_footer();
