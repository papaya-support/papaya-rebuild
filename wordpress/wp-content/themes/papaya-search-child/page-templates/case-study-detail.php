<?php
/**
 * Template Name: Papaya — Case Study Detail
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main tabindex="-1" id="main-content" class="page-case-study-detail">
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-case-study-detail-heading section',
    'container_class' => 'container article-heading',
    'items' => [[
        'type' => 'text',
        'field' => 'case_study_detail_dac02921ffd6',
        'tag' => 'p',
        'class' => 'breadcrumb',
    ], [
        'type' => 'text',
        'field' => 'case_study_detail_afcd2640f663',
        'tag' => 'h1',
        'class' => '',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-case-study-detail-featured-image section section-tight',
    'container_class' => 'container',
    'items' => [[
        'type' => 'image',
        'field' => 'case_study_detail_image_cf7d13020e',
        'class' => 'article-featured',
        'eager' => true,
    ]],
]); ?>
<?php get_template_part('template-parts/sections/case-study-detail-results'); ?>
<?php get_template_part('template-parts/sections/testimonial', null, [
    'class' => 'section-case-study-detail-testimonial section section-tight',
    'container_class' => 'container testimonial cream',
    'items' => [[
        'type' => 'text',
        'field' => 'case_study_detail_7e19b95f2be4',
        'tag' => 'span',
        'class' => 'quote-mark',
    ], [
        'type' => 'text',
        'field' => 'case_study_detail_3f5be18822e8',
        'tag' => 'div',
        'class' => 'prose ',
    ], [
        'type' => 'text',
        'field' => 'case_study_detail_74dd8a1b256d',
        'tag' => 'cite',
        'class' => '',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-case-study-detail-challenge section',
    'columns' => [[
        'class' => 'split-media',
        'wrapper' => '',
        'items' => [[
            'type' => 'image',
            'field' => 'case_study_detail_image_4d97ed1303',
            'class' => '',
            'eager' => false,
        ]],
    ], [
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'case_study_detail_1dcedfb52114',
            'tag' => 'div',
            'class' => 'prose ',
        ]],
    ]],
    'heading' => [[
        'type' => 'text',
        'field' => 'case_study_detail_e27663e17aca',
        'tag' => 'h2',
        'class' => 'section-title',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-case-study-detail-follow-up section section-tight',
    'container_class' => 'container center',
    'items' => [[
        'type' => 'text',
        'field' => 'case_study_detail_28224860229f',
        'tag' => 'div',
        'class' => 'prose ',
    ], [
        'type' => 'image',
        'field' => 'case_study_detail_image_26a15581be',
        'class' => 'results-chart',
        'eager' => false,
    ]],
]); ?>
</main>
<?php endwhile;
get_footer();
