<?php
/** Reusable introduction, heading, call-to-action or featured-media section. */
defined('ABSPATH') || exit();

// Each variant owns its field mapping; the section markup remains shared.
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
?>
<section class="<?php echo esc_attr($args['class'] ?? 'section'); ?>">
    <div class="<?php echo esc_attr($args['container_class'] ?? 'container'); ?>">
        <?php get_template_part('template-parts/components/content-items', null, [
            'items' => $args['items'] ?? [],
        ]); ?>
    </div>
</section>
