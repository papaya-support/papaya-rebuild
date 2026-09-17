<?php
/**
 * Template Name: Papaya — About
 * Template Post Type: page
 */
defined('ABSPATH') || exit();
get_header();
while (have_posts()):
    the_post(); ?>
<main tabindex="-1" id="main-content" class="page-about">
<?php get_template_part('template-parts/sections/banner', null, [
    'class' => 'section-about-banner page-banner',
    'items' => [
        [
            'type' => 'image',
            'field' => 'about_image_8a6938ebd6',
            'class' => 'banner-image',
            'eager' => true,
        ],
    ],
]); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-about-introduction section',
    'container_class' => 'container intro center',
    'items' => [
        ['type' => 'text', 'field' => 'about_f1bcbe1f32e9', 'tag' => 'h1', 'class' => ''],
        ['type' => 'text', 'field' => 'about_5364bb9bbe7f', 'tag' => 'div', 'class' => 'prose '],
    ],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-about-audience-strategy section section-tight',
    'columns' => [
        [
            'class' => 'split-copy',
            'wrapper' => '',
            'items' => [
                [
                    'type' => 'text',
                    'field' => 'about_feb01315cca8',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
                [
                    'type' => 'text',
                    'field' => 'about_595ae388ed18',
                    'tag' => 'h2',
                    'class' => 'accent',
                ],
                [
                    'type' => 'text',
                    'field' => 'about_6fd9927a1ee9',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
            ],
        ],
        [
            'class' => 'split-media',
            'wrapper' => '',
            'items' => [
                [
                    'type' => 'image',
                    'field' => 'about_image_fabff0a2d1',
                    'class' => '',
                    'eager' => false,
                ],
            ],
        ],
    ],
]); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-about-partnership-heading section cream compact-section',
    'container_class' => 'container narrow center',
    'items' => [['type' => 'text', 'field' => 'about_3fed862d01f7', 'tag' => 'h2', 'class' => '']],
]); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-about-partnership section',
    'columns' => [
        [
            'class' => 'split-media',
            'wrapper' => '',
            'items' => [
                [
                    'type' => 'image',
                    'field' => 'about_image_3f841022dd',
                    'class' => '',
                    'eager' => false,
                ],
            ],
        ],
        [
            'class' => 'split-copy',
            'wrapper' => '',
            'items' => [
                [
                    'type' => 'text',
                    'field' => 'about_95d816dc41a8',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
                [
                    'type' => 'text',
                    'field' => 'about_073b5d613eba',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
            ],
        ],
    ],
]); ?>
<?php get_template_part('template-parts/sections/about-process'); ?>
<?php get_template_part('template-parts/sections/about-team'); ?>
<?php get_template_part('template-parts/sections/image-text', null, [
    'class' => 'section-about-brand-story section',
    'columns' => [
        [
            'class' => 'split-copy',
            'wrapper' => '',
            'items' => [
                [
                    'type' => 'text',
                    'field' => 'about_cc11b96d229a',
                    'tag' => 'h2',
                    'class' => 'accent',
                ],
                [
                    'type' => 'text',
                    'field' => 'about_e6c42fc4a3fa',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
            ],
        ],
        [
            'class' => 'split-media',
            'wrapper' => '',
            'items' => [
                ['type' => 'illustration', 'asset' => 'papaya-tree.svg', 'class' => 'brand-tree'],
            ],
        ],
    ],
]); ?>
<?php get_template_part('template-parts/sections/content', null, [
    'class' => 'section-about-closing-cta section green',
    'container_class' => 'container',
    'items' => [
        [
            'type' => 'text',
            'field' => 'about_8ccc8c3f77e1',
            'tag' => 'h2',
            'class' => '',
        ],
        ['type' => 'text', 'field' => 'about_d48d9c392e5c', 'tag' => 'div', 'class' => 'prose '],
        [
            'type' => 'button',
            'field' => 'about_718abc37e0eb',
            'url' => ps_booking_url(),
            'class' => 'button-peach',
        ],
    ],
]); ?>
</main>
<?php
endwhile;
get_footer();
