<?php
/** Section presentation variants. Content is resolved from the matching ACF Group. */
defined('ABSPATH') || exit();
function ps_section_layout($type, $args)
{
    switch ($type) {
        case 'hero':
            switch ($args['variant'] ?? '') {
                case 'home-hero':
                    $args = [
                        'class' => 'section-home-hero home-hero',
                        'container_class' => 'container hero-copy',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'home_1d791eb121c5',
                                'tag' => 'h1',
                                'class' => '',
                            ],
                        ],
                        'image' => 'home_image_4b5fa21323',
                        'emblem' => 'home_hero_emblem',
                    ];
                    break;
                case 'blog-hero':
                    $args = [
                        'class' => 'section-blog-hero listing-hero',
                        'container_class' => 'container intro center',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'blog_2f0c8cf2c985',
                                'tag' => 'h1',
                                'class' => '',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'blog_37a359563ced',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                        ],
                        'image' => 'blog_image_a770b85be1',
                    ];
                    break;
            }
            break;
        case 'banner':
            switch ($args['variant'] ?? '') {
                case 'services-banner':
                    $args = [
                        'class' => 'section-services-banner page-banner',
                        'items' => [
                            [
                                'type' => 'image',
                                'field' => 'services_image_95cc8a2f1e',
                                'class' => 'banner-image',
                                'eager' => true,
                            ],
                        ],
                    ];
                    break;
                case 'search-engine-marketing-banner':
                    $args = [
                        'class' => 'section-search-engine-marketing-banner page-banner',
                        'items' => [
                            [
                                'type' => 'image',
                                'field' => 'search_engine_marketing_image_96ff85c5d5',
                                'class' => 'banner-image',
                                'eager' => true,
                            ],
                        ],
                    ];
                    break;
                case 'about-banner':
                    $args = [
                        'class' => 'section-about-banner page-banner',
                        'items' => [
                            [
                                'type' => 'image',
                                'field' => 'about_image_8a6938ebd6',
                                'class' => 'banner-image',
                                'eager' => true,
                            ],
                        ],
                    ];
                    break;
            }
            break;
        case 'testimonial':
            switch ($args['variant'] ?? '') {
                case 'home-testimonial':
                    $args = [
                        'class' => 'section-home-testimonial section green',
                        'container_class' => 'container testimonial',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'home_c71150b00266',
                                'tag' => 'span',
                                'class' => 'quote-mark',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'home_71e8d5ebfb3e',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                            ['type' => 'text', 'field' => 'home_7393b65da373', 'tag' => 'cite', 'class' => ''],
                        ],
                        'illustration' => 'testimonial-bird.svg',
                    ];
                    break;
                case 'case-study-detail-testimonial':
                    $args = [
                        'class' => 'section-case-study-detail-testimonial section section-tight',
                        'container_class' => 'container testimonial cream',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'case_study_detail_7e19b95f2be4',
                                'tag' => 'span',
                                'class' => 'quote-mark',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'case_study_detail_3f5be18822e8',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'case_study_detail_74dd8a1b256d',
                                'tag' => 'cite',
                                'class' => '',
                            ],
                        ],
                    ];
                    break;
            }
            break;
        case 'service-grid':
            switch ($args['variant'] ?? '') {
                case 'services-service-grid':
                    $args = [
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
                    ];
                    break;
                case 'home-services':
                    $args = [
                        'class' => 'section-home-services section cream center',
                        'cards' => [
                            [
                                'icon' => 'service-seo.svg',
                                'tag' => 'div',
                                'items' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'home_536fe4a39b43',
                                        'tag' => 'h3',
                                        'class' => '',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'home_a2521eac95bf',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                    [
                                        'type' => 'button',
                                        'field' => 'home_c04f66011766',
                                        'url' => ps_route('services') . '#search-engine-optimization',
                                        'class' => 'button-small',
                                    ],
                                ],
                            ],
                            [
                                'icon' => 'service-ppc.svg',
                                'tag' => 'div',
                                'items' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'home_3416c7bfdc6a',
                                        'tag' => 'h3',
                                        'class' => '',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'home_87d8101d772c',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                    [
                                        'type' => 'button',
                                        'field' => 'home_fadcbf34d7f6',
                                        'url' => ps_route('search-engine-marketing'),
                                        'class' => 'button-small',
                                    ],
                                ],
                            ],
                            [
                                'icon' => 'service-analytics.svg',
                                'tag' => 'div',
                                'items' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'home_ef6e013268e4',
                                        'tag' => 'h3',
                                        'class' => '',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'home_87c29f9a637c',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                    [
                                        'type' => 'button',
                                        'field' => 'home_01079a482a16',
                                        'url' => ps_route('services') . '#website-analytics',
                                        'class' => 'button-small',
                                    ],
                                ],
                            ],
                            [
                                'icon' => 'service-ai.svg',
                                'tag' => 'div',
                                'items' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'home_9e841ae19a6e',
                                        'tag' => 'h3',
                                        'class' => '',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'home_c6db28f931d0',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                    [
                                        'type' => 'button',
                                        'field' => 'home_4dbc82586a2a',
                                        'url' => ps_destinations()['AI SEO'],
                                        'class' => 'button-small',
                                    ],
                                ],
                            ],
                        ],
                        'heading' => [
                            [
                                'type' => 'text',
                                'field' => 'home_473db858b064',
                                'tag' => 'h2',
                                'class' => 'section-title',
                            ],
                        ],
                        'grid_class' => 'grid grid-four',
                    ];
                    break;
            }
            break;
        case 'content':
            switch ($args['variant'] ?? '') {
                case 'blog-detail-heading':
                    $args = [
                        'class' => 'section-blog-detail-heading section',
                        'container_class' => 'container article-heading',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'blog_detail_9c13381244a8',
                                'tag' => 'p',
                                'class' => 'breadcrumb',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'blog_detail_8c780093d5a1',
                                'tag' => 'h1',
                                'class' => '',
                            ],
                        ],
                    ];
                    break;
                case 'blog-detail-featured-image':
                    $args = [
                        'class' => 'section-blog-detail-featured-image section section-tight',
                        'container_class' => 'container',
                        'items' => [
                            [
                                'type' => 'image',
                                'field' => 'blog_detail_image_33017e3b40',
                                'class' => 'article-featured',
                                'eager' => true,
                            ],
                        ],
                    ];
                    break;
                case 'services-introduction':
                    $args = [
                        'class' => 'section-services-introduction section',
                        'container_class' => 'container intro center',
                        'items' => [
                            ['type' => 'text', 'field' => 'services_1d102d9577a4', 'tag' => 'h1', 'class' => ''],
                            ['type' => 'text', 'field' => 'services_c540653f6d16', 'tag' => 'h2', 'class' => 'accent'],
                            ['type' => 'text', 'field' => 'services_c0d7b44ec821', 'tag' => 'div', 'class' => 'prose '],
                        ],
                    ];
                    break;
                case 'home-introduction':
                    $args = [
                        'class' => 'section-home-introduction section',
                        'container_class' => 'container center narrow',
                        'items' => [['type' => 'text', 'field' => 'home_8c4225c0ac7d', 'tag' => 'h2', 'class' => '']],
                    ];
                    break;
                case 'search-engine-marketing-introduction':
                    $args = [
                        'class' => 'section-search-engine-marketing-introduction section',
                        'container_class' => 'container intro center',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'search_engine_marketing_f6246eab32c5',
                                'tag' => 'h1',
                                'class' => '',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'search_engine_marketing_4daf58fc05bf',
                                'tag' => 'h2',
                                'class' => 'accent',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'search_engine_marketing_8f8d05ce486c',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                        ],
                    ];
                    break;
                case 'search-engine-marketing-closing-cta':
                    $args = [
                        'class' => 'section-search-engine-marketing-closing-cta section green',
                        'container_class' => 'container',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'search_engine_marketing_b1060e1729d0',
                                'tag' => 'h2',
                                'class' => '',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'search_engine_marketing_ca67c83aae59',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                            [
                                'type' => 'button',
                                'field' => 'search_engine_marketing_2263d859bf84',
                                'url' => ps_booking_url(),
                                'class' => 'button-peach',
                            ],
                        ],
                    ];
                    break;
                case 'case-studies-introduction':
                    $args = [
                        'class' => 'section-case-studies-introduction section',
                        'container_class' => 'container intro center',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'case_studies_089cedf19e41',
                                'tag' => 'h1',
                                'class' => '',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'case_studies_b5430be964d4',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'case_studies_8234b4aaee10',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                            [
                                'type' => 'button',
                                'field' => 'case_studies_5f8c3c80c990',
                                'url' => ps_booking_url(),
                                'class' => 'button-green',
                            ],
                        ],
                    ];
                    break;
                case 'about-introduction':
                    $args = [
                        'class' => 'section-about-introduction section',
                        'container_class' => 'container intro center',
                        'items' => [
                            ['type' => 'text', 'field' => 'about_f1bcbe1f32e9', 'tag' => 'h1', 'class' => ''],
                            ['type' => 'text', 'field' => 'about_5364bb9bbe7f', 'tag' => 'div', 'class' => 'prose '],
                        ],
                    ];
                    break;
                case 'about-partnership-heading':
                    $args = [
                        'class' => 'section-about-partnership-heading section cream compact-section',
                        'container_class' => 'container narrow center',
                        'items' => [['type' => 'text', 'field' => 'about_3fed862d01f7', 'tag' => 'h2', 'class' => '']],
                    ];
                    break;
                case 'about-closing-cta':
                    $args = [
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
                    ];
                    break;
                case 'case-study-detail-heading':
                    $args = [
                        'class' => 'section-case-study-detail-heading section',
                        'container_class' => 'container article-heading',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'case_study_detail_dac02921ffd6',
                                'tag' => 'p',
                                'class' => 'breadcrumb',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'case_study_detail_afcd2640f663',
                                'tag' => 'h1',
                                'class' => '',
                            ],
                        ],
                    ];
                    break;
                case 'case-study-detail-featured-image':
                    $args = [
                        'class' => 'section-case-study-detail-featured-image section section-tight',
                        'container_class' => 'container',
                        'items' => [
                            [
                                'type' => 'image',
                                'field' => 'case_study_detail_image_cf7d13020e',
                                'class' => 'article-featured',
                                'eager' => true,
                            ],
                        ],
                    ];
                    break;
                case 'case-study-detail-follow-up':
                    $args = [
                        'class' => 'section-case-study-detail-follow-up section section-tight',
                        'container_class' => 'container center',
                        'items' => [
                            [
                                'type' => 'text',
                                'field' => 'case_study_detail_28224860229f',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                            [
                                'type' => 'image',
                                'field' => 'case_study_detail_image_26a15581be',
                                'class' => 'results-chart',
                                'eager' => false,
                            ],
                        ],
                    ];
                    break;
            }
            break;
        case 'post-grid':
            switch ($args['variant'] ?? '') {
                case 'blog-detail-related-posts':
                    $args = [
                        'class' => 'section-blog-detail-related-posts section related-posts',
                        'cards' => [
                            [
                                'image' => 'blog_detail_image_e66cb91b30',
                                'title' => 'blog_detail_4d181476ee16',
                                'tag' => 'h3',
                                'url' => ps_route('blog-detail'),
                            ],
                            [
                                'image' => 'blog_detail_image_e850bb0d10',
                                'title' => 'blog_detail_8c5ed5ef437b',
                                'tag' => 'h3',
                                'url' => ps_route('blog-detail'),
                            ],
                            [
                                'image' => 'blog_detail_image_e6018f7e64',
                                'title' => 'blog_detail_1fab6576ba8d',
                                'tag' => 'h3',
                                'url' => ps_route('blog-detail'),
                            ],
                        ],
                        'heading' => [
                            [
                                'type' => 'text',
                                'field' => 'blog_detail_1624e4d9861e',
                                'tag' => 'h2',
                                'class' => 'section-title',
                            ],
                        ],
                        'grid_class' => 'grid grid-three',
                    ];
                    break;
            
            }
            break;
        case 'statistics':
            switch ($args['variant'] ?? '') {
                case 'services-closing-cta':
                    $args = [
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
                    ];
                    break;
                case 'home-results':
                    $args = [
                        'class' => 'section-home-results section',
                        'container_class' => 'container center narrow',
                        'before' => [
                            ['type' => 'text', 'field' => 'home_6666581aab96', 'tag' => 'h2', 'class' => 'accent'],
                        ],
                        'stats' => [
                            [
                                ['type' => 'text', 'field' => 'home_840918b075ea', 'tag' => 'h3', 'class' => ''],
                                [
                                    'type' => 'text',
                                    'field' => 'home_d17566645c30',
                                    'tag' => 'div',
                                    'class' => 'prose ',
                                ],
                            ],
                            [
                                ['type' => 'text', 'field' => 'home_4973267b1258', 'tag' => 'h3', 'class' => ''],
                                [
                                    'type' => 'text',
                                    'field' => 'home_ea2e588e1e39',
                                    'tag' => 'div',
                                    'class' => 'prose ',
                                ],
                            ],
                            [
                                ['type' => 'text', 'field' => 'home_95726762e92f', 'tag' => 'h3', 'class' => ''],
                                ['type' => 'text', 'field' => 'home_6b3533e29b8d', 'tag' => 'div', 'class' => 'prose '],
                            ],
                        ],
                        'after' => [
                            [
                                'type' => 'text',
                                'field' => 'home_2de7aecb8d76',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                            ['type' => 'text', 'field' => 'home_5697044bab17', 'tag' => 'p', 'class' => ''],
                            [
                                'type' => 'button',
                                'field' => 'home_d4db261ea551',
                                'url' => ps_route('case-studies'),
                                'class' => '',
                            ],
                        ],
                    ];
                    break;
                case 'search-engine-marketing-benefits':
                    $args = [
                        'class' => 'section-search-engine-marketing-benefits section cream',
                        'container_class' => 'container center',
                        'before' => [
                            [
                                'type' => 'text',
                                'field' => 'search_engine_marketing_8f270f2a2afd',
                                'tag' => 'h2',
                                'class' => '',
                            ],
                            [
                                'type' => 'text',
                                'field' => 'search_engine_marketing_8ac1ff91ac2a',
                                'tag' => 'div',
                                'class' => 'prose ',
                            ],
                        ],
                        'stats' => [
                            [
                                [
                                    'type' => 'text',
                                    'field' => 'search_engine_marketing_d7c7e6181717',
                                    'tag' => 'h3',
                                    'class' => 'accent',
                                ],
                                [
                                    'type' => 'text',
                                    'field' => 'search_engine_marketing_57c1652a3574',
                                    'tag' => 'div',
                                    'class' => 'prose ',
                                ],
                            ],
                            [
                                [
                                    'type' => 'text',
                                    'field' => 'search_engine_marketing_87a5e5276ed3',
                                    'tag' => 'h3',
                                    'class' => 'accent',
                                ],
                                [
                                    'type' => 'text',
                                    'field' => 'search_engine_marketing_f063a20ed3bd',
                                    'tag' => 'div',
                                    'class' => 'prose ',
                                ],
                            ],
                            [
                                [
                                    'type' => 'text',
                                    'field' => 'search_engine_marketing_c552a4ad539c',
                                    'tag' => 'h3',
                                    'class' => 'accent',
                                ],
                                [
                                    'type' => 'text',
                                    'field' => 'search_engine_marketing_b0fd5c696ade',
                                    'tag' => 'div',
                                    'class' => 'prose ',
                                ],
                            ],
                        ],
                        'after' => [
                            [
                                'type' => 'button',
                                'field' => 'search_engine_marketing_f56f157847bf',
                                'url' => ps_booking_url(),
                                'class' => 'button-peach',
                            ],
                        ],
                    ];
                    break;
            }
            break;
        case 'image-text':
            switch ($args['variant'] ?? '') {
                case 'blog-detail-local-rankings':
                    $args = [
                        'class' => 'section-blog-detail-local-rankings section',
                        'columns' => [
                            [
                                'class' => 'split-media',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'image',
                                        'field' => 'blog_detail_image_82ae55901c',
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
                                        'field' => 'blog_detail_bcb4f48e1767',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                ],
                            ],
                        ],
                        'heading' => [
                            [
                                'type' => 'text',
                                'field' => 'blog_detail_2c021570ce1c',
                                'tag' => 'h2',
                                'class' => 'section-title',
                            ],
                        ],
                    ];
                    break;
                case 'home-search-strategy':
                    $args = [
                        'class' => 'section-home-search-strategy section section-tight',
                        'columns' => [
                            [
                                'class' => 'split-media',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'image',
                                        'field' => 'home_image_d86e7be569',
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
                                        'field' => 'home_2263b6f88209',
                                        'tag' => 'h2',
                                        'class' => 'accent',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'home_d92c033cd119',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                    [
                                        'type' => 'button',
                                        'field' => 'home_6901adf0ab1d',
                                        'url' => ps_booking_url(),
                                        'class' => '',
                                    ],
                                ],
                            ],
                        ],
                    ];
                    break;
                case 'home-search-trends':
                    $args = [
                        'class' => 'section-home-search-trends section',
                        'columns' => [
                            [
                                'class' => 'split-copy',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'home_685fc698bd13',
                                        'tag' => 'h2',
                                        'class' => 'accent',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'home_ad1e50936d54',
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
                                        'field' => 'home_image_d3f8f036db',
                                        'class' => '',
                                        'eager' => false,
                                    ],
                                ],
                            ],
                        ],
                    ];
                    break;
                case 'home-future-strategy':
                    $args = [
                        'class' => 'section-home-future-strategy section section-tight',
                        'columns' => [
                            [
                                'class' => 'split-media',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'image',
                                        'field' => 'home_image_9c76895b7c',
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
                                        'field' => 'home_c03395a13124',
                                        'tag' => 'h2',
                                        'class' => 'accent',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'home_1260983635dc',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                    ['type' => 'text', 'field' => 'home_20f80fb1ab2f', 'tag' => 'h3', 'class' => ''],
                                    [
                                        'type' => 'button',
                                        'field' => 'home_58c8c372efdf',
                                        'url' => ps_booking_url(),
                                        'class' => '',
                                    ],
                                ],
                            ],
                        ],
                    ];
                    break;
                case 'home-closing-cta':
                    $args = [
                        'class' => 'section-home-closing-cta section cream',
                        'columns' => [
                            [
                                'class' => 'split-copy',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'home_09fe6c6170dc',
                                        'tag' => 'h2',
                                        'class' => 'accent',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'home_26e26ba9efb2',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                    [
                                        'type' => 'button',
                                        'field' => 'home_b48f00ea5554',
                                        'url' => ps_booking_url(),
                                        'class' => 'button-text-cream',
                                    ],
                                ],
                            ],
                            [
                                'class' => 'split-media',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'image',
                                        'field' => 'home_image_0aad8a4a0f',
                                        'class' => '',
                                        'eager' => false,
                                    ],
                                ],
                            ],
                        ],
                    ];
                    break;
                case 'search-engine-marketing-visibility':
                    $args = [
                        'class' => 'section-search-engine-marketing-visibility section section-tight',
                        'columns' => [
                            [
                                'class' => 'split-media',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'image',
                                        'field' => 'search_engine_marketing_image_2e8b60cfe5',
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
                                        'field' => 'search_engine_marketing_2a5d5198d572',
                                        'tag' => 'h2',
                                        'class' => '',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'search_engine_marketing_dbbcc6928a34',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                ],
                            ],
                        ],
                    ];
                    break;
                case 'search-engine-marketing-ppc-services':
                    $args = [
                        'class' => 'section-search-engine-marketing-ppc-services section',
                        'columns' => [
                            [
                                'class' => 'split-copy',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'search_engine_marketing_9a1901bd9481',
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
                                        'field' => 'search_engine_marketing_image_de24625b12',
                                        'class' => '',
                                        'eager' => false,
                                    ],
                                ],
                            ],
                        ],
                    ];
                    break;
                case 'search-engine-marketing-google-ads':
                    $args = [
                        'class' => 'section-search-engine-marketing-google-ads section',
                        'columns' => [
                            [
                                'class' => 'split-copy',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'search_engine_marketing_5da4f91cb7c5',
                                        'tag' => 'h2',
                                        'class' => '',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'search_engine_marketing_c4ab9d94a0db',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                ],
                            ],
                            [
                                'class' => 'split-media',
                                'wrapper' => 'certification-card',
                                'items' => [
                                    [
                                        'type' => 'image',
                                        'field' => 'search_engine_marketing_image_f953878ff0',
                                        'class' => '',
                                        'eager' => false,
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'search_engine_marketing_c14dc679be7b',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                ],
                            ],
                        ],
                    ];
                    break;
                case 'search-engine-marketing-microsoft-ads':
                    $args = [
                        'class' => 'section-search-engine-marketing-microsoft-ads section section-tight',
                        'columns' => [
                            [
                                'class' => 'split-media',
                                'wrapper' => 'certification-card',
                                'items' => [
                                    [
                                        'type' => 'image',
                                        'field' => 'search_engine_marketing_image_843cfc6b8d',
                                        'class' => '',
                                        'eager' => false,
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'search_engine_marketing_9b5edf4fcb86',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                ],
                            ],
                            [
                                'class' => 'split-copy',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'text',
                                        'field' => 'search_engine_marketing_9a4e6a600a10',
                                        'tag' => 'h2',
                                        'class' => '',
                                    ],
                                    [
                                        'type' => 'text',
                                        'field' => 'search_engine_marketing_e7514b17f1f3',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                ],
                            ],
                        ],
                    ];
                    break;
                case 'about-audience-strategy':
                    $args = [
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
                    ];
                    break;
                case 'about-partnership':
                    $args = [
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
                    ];
                    break;
                case 'about-brand-story':
                    $args = [
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
                    ];
                    break;
                case 'case-study-detail-challenge':
                    $args = [
                        'class' => 'section-case-study-detail-challenge section',
                        'columns' => [
                            [
                                'class' => 'split-media',
                                'wrapper' => '',
                                'items' => [
                                    [
                                        'type' => 'image',
                                        'field' => 'case_study_detail_image_4d97ed1303',
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
                                        'field' => 'case_study_detail_1dcedfb52114',
                                        'tag' => 'div',
                                        'class' => 'prose ',
                                    ],
                                ],
                            ],
                        ],
                        'heading' => [
                            [
                                'type' => 'text',
                                'field' => 'case_study_detail_e27663e17aca',
                                'tag' => 'h2',
                                'class' => 'section-title',
                            ],
                        ],
                    ];
                    break;
            }
            break;
    }
    return ps_live_service_section_args($args, $type);
}
