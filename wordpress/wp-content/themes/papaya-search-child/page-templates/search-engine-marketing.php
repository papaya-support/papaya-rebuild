<?php
/**
 * Template Name: Papaya — Services-Individual
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main tabindex="-1" id="main-content" class="page-search-engine-marketing">
<?php get_template_part('template-parts/sections/banner', null, [
    'class' => 'section-search-engine-marketing-banner page-banner',
    'items' => [[
        'type' => 'image',
        'field' => 'search_engine_marketing_image_96ff85c5d5',
        'class' => 'banner-image',
        'eager' => true,
    ]],
]); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-search-engine-marketing-introduction section',
    'container_class' => 'container intro center',
    'items' => [[
        'type' => 'text',
        'field' => 'search_engine_marketing_f6246eab32c5',
        'tag' => 'h1',
        'class' => '',
    ], [
        'type' => 'text',
        'field' => 'search_engine_marketing_4daf58fc05bf',
        'tag' => 'h2',
        'class' => 'accent',
    ], [
        'type' => 'text',
        'field' => 'search_engine_marketing_8f8d05ce486c',
        'tag' => 'div',
        'class' => 'prose ',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-search-engine-marketing-visibility section section-tight',
    'columns' => [[
        'class' => 'split-media',
        'wrapper' => '',
        'items' => [[
            'type' => 'image',
            'field' => 'search_engine_marketing_image_2e8b60cfe5',
            'class' => '',
            'eager' => false,
        ]],
    ], [
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'search_engine_marketing_2a5d5198d572',
            'tag' => 'h2',
            'class' => '',
        ], [
            'type' => 'text',
            'field' => 'search_engine_marketing_dbbcc6928a34',
            'tag' => 'div',
            'class' => 'prose ',
        ]],
    ]],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-search-engine-marketing-ppc-services section',
    'columns' => [[
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'search_engine_marketing_9a1901bd9481',
            'tag' => 'div',
            'class' => 'prose ',
        ]],
    ], [
        'class' => 'split-media',
        'wrapper' => '',
        'items' => [[
            'type' => 'image',
            'field' => 'search_engine_marketing_image_de24625b12',
            'class' => '',
            'eager' => false,
        ]],
    ]],
]); ?>
<?php get_template_part('template-parts/sections/statistics', null, [
    'class' => 'section-search-engine-marketing-benefits section cream',
    'container_class' => 'container center',
    'before' => [[
        'type' => 'text',
        'field' => 'search_engine_marketing_8f270f2a2afd',
        'tag' => 'h2',
        'class' => '',
    ], [
        'type' => 'text',
        'field' => 'search_engine_marketing_8ac1ff91ac2a',
        'tag' => 'div',
        'class' => 'prose ',
    ]],
    'stats' => [[[
        'type' => 'text',
        'field' => 'search_engine_marketing_d7c7e6181717',
        'tag' => 'h3',
        'class' => 'accent',
    ], [
        'type' => 'text',
        'field' => 'search_engine_marketing_57c1652a3574',
        'tag' => 'div',
        'class' => 'prose ',
    ]], [[
        'type' => 'text',
        'field' => 'search_engine_marketing_87a5e5276ed3',
        'tag' => 'h3',
        'class' => 'accent',
    ], [
        'type' => 'text',
        'field' => 'search_engine_marketing_f063a20ed3bd',
        'tag' => 'div',
        'class' => 'prose ',
    ]], [[
        'type' => 'text',
        'field' => 'search_engine_marketing_c552a4ad539c',
        'tag' => 'h3',
        'class' => 'accent',
    ], [
        'type' => 'text',
        'field' => 'search_engine_marketing_b0fd5c696ade',
        'tag' => 'div',
        'class' => 'prose ',
    ]]],
    'after' => [[
        'type' => 'button',
        'field' => 'search_engine_marketing_f56f157847bf',
        'url' => ps_booking_url(),
        'class' => 'button-peach',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-search-engine-marketing-google-ads section',
    'columns' => [[
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'search_engine_marketing_5da4f91cb7c5',
            'tag' => 'h2',
            'class' => '',
        ], [
            'type' => 'text',
            'field' => 'search_engine_marketing_c4ab9d94a0db',
            'tag' => 'div',
            'class' => 'prose ',
        ]],
    ], [
        'class' => 'split-media',
        'wrapper' => 'certification-card',
        'items' => [[
            'type' => 'image',
            'field' => 'search_engine_marketing_image_f953878ff0',
            'class' => '',
            'eager' => false,
        ], [
            'type' => 'text',
            'field' => 'search_engine_marketing_c14dc679be7b',
            'tag' => 'div',
            'class' => 'prose ',
        ]],
    ]],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-search-engine-marketing-microsoft-ads section section-tight',
    'columns' => [[
        'class' => 'split-media',
        'wrapper' => 'certification-card',
        'items' => [[
            'type' => 'image',
            'field' => 'search_engine_marketing_image_843cfc6b8d',
            'class' => '',
            'eager' => false,
        ], [
            'type' => 'text',
            'field' => 'search_engine_marketing_9b5edf4fcb86',
            'tag' => 'div',
            'class' => 'prose ',
        ]],
    ], [
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'search_engine_marketing_9a4e6a600a10',
            'tag' => 'h2',
            'class' => '',
        ], [
            'type' => 'text',
            'field' => 'search_engine_marketing_e7514b17f1f3',
            'tag' => 'div',
            'class' => 'prose ',
        ]],
    ]],
]); ?>
<?php get_template_part('template-parts/sections/search-engine-marketing-faqs'); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-search-engine-marketing-closing-cta section green',
    'container_class' => 'container',
    'items' => [[
        'type' => 'text',
        'field' => 'search_engine_marketing_b1060e1729d0',
        'tag' => 'h2',
        'class' => '',
    ], [
        'type' => 'text',
        'field' => 'search_engine_marketing_ca67c83aae59',
        'tag' => 'div',
        'class' => 'prose ',
    ], [
        'type' => 'button',
        'field' => 'search_engine_marketing_2263d859bf84',
        'url' => ps_booking_url(),
        'class' => 'button-peach',
    ]],
]); ?>
</main>
<?php endwhile;
get_footer();
