<?php
/** Shared service cards with optional section heading, subtitles and anchor IDs. */
defined('ABSPATH') || exit();

// Each variant owns its field mapping; the section markup remains shared.
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
?>
<section class="<?php echo esc_attr($args['class']); ?>">
    <?php if (!empty($args['heading'])): ?><div class="container">
        <?php get_template_part('template-parts/components/content-items', null, [
            'items' => $args['heading'],
        ]); ?>
        <?php endif; ?>
        <div class="<?php echo esc_attr($args['grid_class']); ?>">
            <?php foreach ($args['cards'] as $card):
                $tag = ($card['tag'] ?? '') === 'article' ? 'article' : 'div'; ?>
            <<?php echo $tag; ?> class="service-card" <?php if (!empty($card['id'])): ?> id="<?php echo esc_attr($card['id']); ?>" <?php endif; ?>>
                <img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/' . $card['icon']); ?>" alt="" aria-hidden="true">
                <?php get_template_part('template-parts/components/content-items', null, [
                    'items' => $card['items'],
                ]); ?>
            </<?php echo $tag; ?>>
            <?php
            endforeach; ?>
        </div>
        <?php if (!empty($args['heading'])): ?>
    </div>
    <?php endif; ?>
</section>
