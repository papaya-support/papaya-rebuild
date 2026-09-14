<?php
/**
 * Template Name: Papaya — Services-Individual
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-search-engine-marketing">
<?php get_template_part('template-parts/sections/search-engine-marketing-banner'); ?>
<?php get_template_part('template-parts/sections/search-engine-marketing-introduction'); ?>
<?php get_template_part('template-parts/sections/search-engine-marketing-visibility'); ?>
<?php get_template_part('template-parts/sections/search-engine-marketing-ppc-services'); ?>
<?php get_template_part('template-parts/sections/search-engine-marketing-benefits'); ?>
<?php get_template_part('template-parts/sections/search-engine-marketing-google-ads'); ?>
<?php get_template_part('template-parts/sections/search-engine-marketing-microsoft-ads'); ?>
<?php get_template_part('template-parts/sections/search-engine-marketing-faqs'); ?>
<?php get_template_part('template-parts/sections/search-engine-marketing-closing-cta'); ?>
</main>
<?php endwhile;
get_footer();
