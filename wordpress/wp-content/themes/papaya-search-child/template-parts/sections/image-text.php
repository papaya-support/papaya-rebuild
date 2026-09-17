<?php
/** Two-column image and text section. Column order is desktop order; CSS puts media first on mobile. */
defined('ABSPATH') || exit();

// Each variant owns its field mapping; the section markup remains shared.
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
?>
<section class="<?php echo esc_attr($args['class'] ?? 'section'); ?>">
    <?php if (!empty($args['heading'])): ?>
    <div class="container">
        <?php get_template_part('template-parts/components/content-items', null, [
            'items' => $args['heading'],
        ]); ?>
    </div>
    <?php endif; ?>
    <div class="container split ">
        <?php foreach ($args['columns'] ?? [] as $column): ?>
        <div class="<?php echo esc_attr($column['class']); ?>">
            <?php if (!empty($column['wrapper'])): ?><div class="<?php echo esc_attr($column['wrapper']); ?>"><?php endif; ?>
                <?php get_template_part('template-parts/components/content-items', null, [
                    'items' => $column['items'],
                ]); ?>
                <?php if (!empty($column['wrapper'])): ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
