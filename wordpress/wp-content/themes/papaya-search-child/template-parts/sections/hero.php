<?php
/** Shared image hero, with an optional overlapping ACF emblem. */
defined('ABSPATH') || exit();

// Each variant owns its field mapping; the section markup remains shared.
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
?>
<section class="<?php echo esc_attr($args['class']); ?>">
    <?php ps_image($args['image'], 'hero-image', true); ?>
    <div class="<?php echo esc_attr($args['container_class']); ?>">
        <?php get_template_part('template-parts/components/content-items', null, [
            'items' => $args['items'],
        ]); ?>
    </div>
    <?php if (!empty($args['emblem'])) {
        ps_image($args['emblem'], 'hero-emblem', true);
    } ?>
</section>
