<?php
/**
 * Template Name: Papaya — Services-Landing
 * Template Post Type: page
 */
defined('ABSPATH') || exit();
get_header();
while (have_posts()):
    the_post(); ?>
<main tabindex="-1" id="main-content" class="page-services">
<?php get_template_part('template-parts/sections/banner', null, [
    'class' => 'section-services-banner page-banner',
    'items' => [
        [
            'type' => 'image',
            'field' => 'services_image_95cc8a2f1e',
            'class' => 'banner-image',
            'eager' => true,
        ],
    ],
]); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-services-introduction section',
    'container_class' => 'container intro center',
    'items' => [
        ['type' => 'text', 'field' => 'services_1d102d9577a4', 'tag' => 'h1', 'class' => ''],
        ['type' => 'text', 'field' => 'services_c540653f6d16', 'tag' => 'h2', 'class' => 'accent'],
        ['type' => 'text', 'field' => 'services_c0d7b44ec821', 'tag' => 'div', 'class' => 'prose '],
    ],
]); ?>
<?php get_template_part('template-parts/sections/service-grid', null, [
    'class' => 'section-services-service-grid section section-tight center',
    'cards' => [
        [
            'icon' => 'service-seo.svg',
            'tag' => 'article',
            'items' => [
                [
                    'type' => 'text',
                    'field' => 'services_fb1240267041',
                    'tag' => 'h2',
                    'class' => '',
                ],
                [
                    'type' => 'text',
                    'field' => 'services_92fa01adc928',
                    'tag' => 'h3',
                    'class' => 'accent',
                ],
                [
                    'type' => 'text',
                    'field' => 'services_61a499cbf5ad',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
                [
                    'type' => 'button',
                    'field' => 'services_403955cd73a3',
                    'url' => ps_route('services') . '#search-engine-optimization',
                    'class' => 'button-small',
                ],
            ],
            'id' => 'search-engine-optimization',
        ],
        [
            'icon' => 'service-ppc.svg',
            'tag' => 'article',
            'items' => [
                [
                    'type' => 'text',
                    'field' => 'services_b8a1a172da9c',
                    'tag' => 'h2',
                    'class' => '',
                ],
                [
                    'type' => 'text',
                    'field' => 'services_798933f3ef4e',
                    'tag' => 'h3',
                    'class' => 'accent',
                ],
                [
                    'type' => 'text',
                    'field' => 'services_1b7af68589dc',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
                [
                    'type' => 'button',
                    'field' => 'services_350220fa15e8',
                    'url' => ps_route('search-engine-marketing'),
                    'class' => 'button-small',
                ],
            ],
            'id' => 'search-engine-marketing',
        ],
        [
            'icon' => 'service-analytics.svg',
            'tag' => 'article',
            'items' => [
                [
                    'type' => 'text',
                    'field' => 'services_879ad37c29e0',
                    'tag' => 'h2',
                    'class' => '',
                ],
                [
                    'type' => 'text',
                    'field' => 'services_822294744ef5',
                    'tag' => 'h3',
                    'class' => 'accent',
                ],
                [
                    'type' => 'text',
                    'field' => 'services_00de51947158',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
                [
                    'type' => 'button',
                    'field' => 'services_cf9924922477',
                    'url' => ps_route('services') . '#website-analytics',
                    'class' => 'button-small',
                ],
            ],
            'id' => 'website-analytics',
        ],
        [
            'icon' => 'service-ai.svg',
            'tag' => 'article',
            'items' => [
                [
                    'type' => 'text',
                    'field' => 'services_e0dbcb08a264',
                    'tag' => 'h2',
                    'class' => '',
                ],
                [
                    'type' => 'text',
                    'field' => 'services_a706330f88c5',
                    'tag' => 'h3',
                    'class' => 'accent',
                ],
                [
                    'type' => 'text',
                    'field' => 'services_ff171c150d31',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
                [
                    'type' => 'button',
                    'field' => 'services_8c6eed7c6379',
                    'url' => ps_route('services') . '#wordpress-maintenance',
                    'class' => 'button-small',
                ],
            ],
            'id' => 'wordpress-maintenance',
        ],
    ],
    'grid_class' => 'container grid grid-two services-grid',
]); ?>
<?php get_template_part('template-parts/sections/statistics', null, [
    'class' => 'section-services-closing-cta section green',
    'container_class' => 'container center',
    'before' => [
        [
            'type' => 'text',
            'field' => 'services_94024e830fa2',
            'tag' => 'h2',
            'class' => '',
        ],
    ],
    'stats' => [
        [
            [
                'type' => 'text',
                'field' => 'services_bed98f20a891',
                'tag' => 'h3',
                'class' => '',
            ],
        ],
        [
            [
                'type' => 'text',
                'field' => 'services_8eb4c91b801a',
                'tag' => 'h3',
                'class' => '',
            ],
        ],
        [
            [
                'type' => 'text',
                'field' => 'services_bd0b0253035b',
                'tag' => 'h3',
                'class' => '',
            ],
        ],
    ],
    'after' => [
        [
            'type' => 'button',
            'field' => 'services_4b4ceb001243',
            'url' => ps_booking_url(),
            'class' => 'button-peach',
        ],
    ],
]); ?>
</main>
<?php
endwhile;
get_footer();
