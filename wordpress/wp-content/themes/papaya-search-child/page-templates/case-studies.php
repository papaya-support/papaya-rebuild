<?php
/**
 * Template Name: Papaya — Case Study
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main tabindex="-1" id="main-content" class="page-case-studies">
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-case-studies-introduction section',
    'container_class' => 'container intro center',
    'items' => [[
        'type' => 'text',
        'field' => 'case_studies_089cedf19e41',
        'tag' => 'h1',
        'class' => '',
    ], [
        'type' => 'text',
        'field' => 'case_studies_b5430be964d4',
        'tag' => 'div',
        'class' => 'prose ',
    ], [
        'type' => 'text',
        'field' => 'case_studies_8234b4aaee10',
        'tag' => 'div',
        'class' => 'prose ',
    ], [
        'type' => 'button',
        'field' => 'case_studies_5f8c3c80c990',
        'url' => ps_booking_url(),
        'class' => '',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/post-grid', null, [
    'class' => 'section-case-studies-cards section section-tight',
    'cards' => [[
        'image' => 'case_studies_image_8dd5e5fb58',
        'title' => 'case_studies_13234451b10b',
        'tag' => 'h2',
        'url' => ps_route('case-study-detail'),
        'excerpt' => 'case_studies_aea86c447f03',
    ], [
        'image' => 'case_studies_image_14cb78ca6e',
        'title' => 'case_studies_1c7050391843',
        'tag' => 'h2',
        'url' => ps_route('case-study-detail'),
        'excerpt' => 'case_studies_797e93346093',
    ], [
        'image' => 'case_studies_image_5ad8dd81b9',
        'title' => 'case_studies_0ecd54bcba1a',
        'tag' => 'h2',
        'url' => ps_route('case-study-detail'),
        'excerpt' => 'case_studies_c478f4605f8c',
    ], [
        'image' => 'case_studies_image_af8f78b46e',
        'title' => 'case_studies_0a7c3278aa61',
        'tag' => 'h2',
        'url' => ps_route('case-study-detail'),
        'excerpt' => 'case_studies_7ccd51309801',
    ], [
        'image' => 'case_studies_image_295549ea58',
        'title' => 'case_studies_716425e49fa2',
        'tag' => 'h2',
        'url' => ps_route('case-study-detail'),
        'excerpt' => 'case_studies_4c074436f397',
    ], [
        'image' => 'case_studies_image_b6a651e3c2',
        'title' => 'case_studies_74f48e8c27a3',
        'tag' => 'h2',
        'url' => ps_route('case-study-detail'),
        'excerpt' => 'case_studies_938030bd0b43',
    ], [
        'image' => 'case_studies_image_540f75952e',
        'title' => 'case_studies_304856bee0cf',
        'tag' => 'h2',
        'url' => ps_route('case-study-detail'),
        'excerpt' => 'case_studies_c6058d1d5199',
    ], [
        'image' => 'case_studies_image_27fdb373ca',
        'title' => 'case_studies_79f1326abebe',
        'tag' => 'h2',
        'url' => ps_route('case-study-detail'),
        'excerpt' => 'case_studies_02b4fd4b7c29',
    ], [
        'image' => 'case_studies_image_340ebfd1f6',
        'title' => 'case_studies_689120d3d2e7',
        'tag' => 'h2',
        'url' => ps_route('case-study-detail'),
        'excerpt' => 'case_studies_bf246d534c46',
    ]],
    'grid_class' => 'container grid grid-three post-grid',
]); ?>
</main>
<?php endwhile;
get_footer();
