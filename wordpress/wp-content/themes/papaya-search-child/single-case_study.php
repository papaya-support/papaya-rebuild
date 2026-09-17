<?php
/**
 * Single Case Study — uses the same sections as the detail page template.
 */
defined('ABSPATH') || exit();
get_header();

while (have_posts()):
    the_post();
    ?>
    <main tabindex="-1" id="main-content" class="page-case-study-detail">
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'case-study-detail-heading']); ?>
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'case-study-detail-featured-image']); ?>
        <?php get_template_part('template-parts/sections/case-study-detail-results'); ?>
        <?php get_template_part('template-parts/sections/testimonial', null, ['variant' => 'case-study-detail-testimonial']); ?>
        <?php get_template_part('template-parts/sections/image-text', null, ['variant' => 'case-study-detail-challenge']); ?>
        <?php get_template_part('template-parts/sections/content', null, ['variant' => 'case-study-detail-follow-up']); ?>
    </main>
    <?php
endwhile;

get_footer();
