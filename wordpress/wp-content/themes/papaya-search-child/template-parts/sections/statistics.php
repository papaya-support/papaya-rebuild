<?php
/** Shared three-column statistics or benefits section with optional surrounding copy. */
defined('ABSPATH') || exit();

// Each variant owns its field mapping; the section markup remains shared.
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
?>
<section class="<?php echo esc_attr($args['class']); ?>">
    <div class="<?php echo esc_attr($args['container_class']); ?>">
        <?php get_template_part('template-parts/components/content-items', null, [
            'items' => $args['before'],
        ]); ?>
        <div class="grid grid-three stats">
            <?php foreach ($args['stats'] as $stat): ?>
            <div class="stat">
                <?php get_template_part('template-parts/components/content-items', null, ['items' => $stat]); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php get_template_part('template-parts/components/content-items', null, [
            'items' => $args['after'],
        ]); ?>
    </div>
</section>
