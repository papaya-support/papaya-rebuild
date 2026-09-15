<?php
/**
 * Template Name: Papaya — Home
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="page-home">
<?php get_template_part('template-parts/sections/hero', null, [
    'class' => 'section-home-hero home-hero',
    'container_class' => 'container hero-copy',
    'items' => [[
        'type' => 'text',
        'field' => 'home_1d791eb121c5',
        'tag' => 'h1',
        'class' => '',
    ]],
    'image' => 'home_image_4b5fa21323',
    'emblem' => 'home_hero_emblem',
]); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-home-introduction section',
    'container_class' => 'container center narrow',
    'items' => [[
        'type' => 'text',
        'field' => 'home_8c4225c0ac7d',
        'tag' => 'h2',
        'class' => '',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-home-search-strategy section section-tight',
    'columns' => [[
        'class' => 'split-media',
        'wrapper' => '',
        'items' => [[
            'type' => 'image',
            'field' => 'home_image_d86e7be569',
            'class' => '',
            'eager' => false,
        ]],
    ], [
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'home_2263b6f88209',
            'tag' => 'h2',
            'class' => 'accent',
        ], [
            'type' => 'text',
            'field' => 'home_d92c033cd119',
            'tag' => 'div',
            'class' => 'prose ',
        ], [
            'type' => 'button',
            'field' => 'home_6901adf0ab1d',
            'url' => ps_booking_url(),
            'class' => '',
        ]],
    ]],
]); ?>
<?php get_template_part('template-parts/sections/service-grid', null, [
    'class' => 'section-home-services section cream center',
    'cards' => [[
        'icon' => 'service-seo.svg',
        'tag' => 'div',
        'items' => [[
            'type' => 'text',
            'field' => 'home_536fe4a39b43',
            'tag' => 'h3',
            'class' => '',
        ], [
            'type' => 'text',
            'field' => 'home_a2521eac95bf',
            'tag' => 'div',
            'class' => 'prose ',
        ], [
            'type' => 'button',
            'field' => 'home_c04f66011766',
            'url' => ps_route('services').'#search-engine-optimization',
            'class' => 'button-small',
        ]],
    ], [
        'icon' => 'service-ppc.svg',
        'tag' => 'div',
        'items' => [[
            'type' => 'text',
            'field' => 'home_3416c7bfdc6a',
            'tag' => 'h3',
            'class' => '',
        ], [
            'type' => 'text',
            'field' => 'home_87d8101d772c',
            'tag' => 'div',
            'class' => 'prose ',
        ], [
            'type' => 'button',
            'field' => 'home_fadcbf34d7f6',
            'url' => ps_route('search-engine-marketing'),
            'class' => 'button-small',
        ]],
    ], [
        'icon' => 'service-analytics.svg',
        'tag' => 'div',
        'items' => [[
            'type' => 'text',
            'field' => 'home_ef6e013268e4',
            'tag' => 'h3',
            'class' => '',
        ], [
            'type' => 'text',
            'field' => 'home_87c29f9a637c',
            'tag' => 'div',
            'class' => 'prose ',
        ], [
            'type' => 'button',
            'field' => 'home_01079a482a16',
            'url' => ps_route('services').'#website-analytics',
            'class' => 'button-small',
        ]],
    ], [
        'icon' => 'service-ai.svg',
        'tag' => 'div',
        'items' => [[
            'type' => 'text',
            'field' => 'home_9e841ae19a6e',
            'tag' => 'h3',
            'class' => '',
        ], [
            'type' => 'text',
            'field' => 'home_c6db28f931d0',
            'tag' => 'div',
            'class' => 'prose ',
        ], [
            'type' => 'button',
            'field' => 'home_4dbc82586a2a',
            'url' => ps_destinations()['AI SEO'],
            'class' => 'button-small',
        ]],
    ]],
    'heading' => [[
        'type' => 'text',
        'field' => 'home_473db858b064',
        'tag' => 'h2',
        'class' => 'section-title',
    ]],
    'grid_class' => 'grid grid-four',
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-home-search-trends section',
    'columns' => [[
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'home_685fc698bd13',
            'tag' => 'h2',
            'class' => 'accent',
        ], [
            'type' => 'text',
            'field' => 'home_ad1e50936d54',
            'tag' => 'div',
            'class' => 'prose ',
        ]],
    ], [
        'class' => 'split-media',
        'wrapper' => '',
        'items' => [[
            'type' => 'image',
            'field' => 'home_image_d3f8f036db',
            'class' => '',
            'eager' => false,
        ]],
    ]],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-home-future-strategy section section-tight',
    'columns' => [[
        'class' => 'split-media',
        'wrapper' => '',
        'items' => [[
            'type' => 'image',
            'field' => 'home_image_9c76895b7c',
            'class' => '',
            'eager' => false,
        ]],
    ], [
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'home_c03395a13124',
            'tag' => 'h2',
            'class' => 'accent',
        ], [
            'type' => 'text',
            'field' => 'home_1260983635dc',
            'tag' => 'div',
            'class' => 'prose ',
        ], [
            'type' => 'text',
            'field' => 'home_20f80fb1ab2f',
            'tag' => 'h3',
            'class' => '',
        ], [
            'type' => 'button',
            'field' => 'home_58c8c372efdf',
            'url' => ps_booking_url(),
            'class' => '',
        ]],
    ]],
]); ?>
<?php get_template_part('template-parts/sections/testimonial', null, [
    'class' => 'section-home-testimonial section green',
    'container_class' => 'container testimonial',
    'items' => [[
        'type' => 'text',
        'field' => 'home_c71150b00266',
        'tag' => 'span',
        'class' => 'quote-mark',
    ], [
        'type' => 'text',
        'field' => 'home_71e8d5ebfb3e',
        'tag' => 'div',
        'class' => 'prose ',
    ], [
        'type' => 'text',
        'field' => 'home_7393b65da373',
        'tag' => 'cite',
        'class' => '',
    ]],
    'illustration' => 'testimonial-bird.svg',
]); ?>
<?php get_template_part('template-parts/sections/statistics', null, [
    'class' => 'section-home-results section',
    'container_class' => 'container center narrow',
    'before' => [[
        'type' => 'text',
        'field' => 'home_6666581aab96',
        'tag' => 'h2',
        'class' => 'accent',
    ]],
    'stats' => [[[
        'type' => 'text',
        'field' => 'home_840918b075ea',
        'tag' => 'h3',
        'class' => '',
    ], [
        'type' => 'text',
        'field' => 'home_d17566645c30',
        'tag' => 'div',
        'class' => 'prose ',
    ]], [[
        'type' => 'text',
        'field' => 'home_4973267b1258',
        'tag' => 'h3',
        'class' => '',
    ], [
        'type' => 'text',
        'field' => 'home_ea2e588e1e39',
        'tag' => 'div',
        'class' => 'prose ',
    ]], [[
        'type' => 'text',
        'field' => 'home_95726762e92f',
        'tag' => 'h3',
        'class' => '',
    ], [
        'type' => 'text',
        'field' => 'home_6b3533e29b8d',
        'tag' => 'div',
        'class' => 'prose ',
    ]]],
    'after' => [[
        'type' => 'text',
        'field' => 'home_2de7aecb8d76',
        'tag' => 'div',
        'class' => 'prose ',
    ], [
        'type' => 'text',
        'field' => 'home_5697044bab17',
        'tag' => 'p',
        'class' => '',
    ], [
        'type' => 'button',
        'field' => 'home_d4db261ea551',
        'url' => ps_route('case-studies'),
        'class' => '',
    ]],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-home-closing-cta section cream',
    'columns' => [[
        'class' => 'split-copy',
        'wrapper' => '',
        'items' => [[
            'type' => 'text',
            'field' => 'home_09fe6c6170dc',
            'tag' => 'h2',
            'class' => 'accent',
        ], [
            'type' => 'text',
            'field' => 'home_26e26ba9efb2',
            'tag' => 'div',
            'class' => 'prose ',
        ], [
            'type' => 'button',
            'field' => 'home_b48f00ea5554',
            'url' => ps_booking_url(),
            'class' => '',
        ]],
    ], [
        'class' => 'split-media',
        'wrapper' => '',
        'items' => [[
            'type' => 'image',
            'field' => 'home_image_0aad8a4a0f',
            'class' => '',
            'eager' => false,
        ]],
    ]],
]); ?>
</main>
<?php endwhile;
get_footer();
