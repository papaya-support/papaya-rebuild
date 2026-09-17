<?php
/** Full-width banner, with the image selected in the page's ACF fields. */
defined('ABSPATH') || exit();

// Each variant owns its field mapping; the section markup remains shared.
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
?>
<section class="<?php echo esc_attr($args['class'] ?? 'page-banner'); ?>">
    <?php get_template_part('template-parts/components/content-items', null, [
        'items' => $args['items'] ?? [],
    ]); ?>
</section>
