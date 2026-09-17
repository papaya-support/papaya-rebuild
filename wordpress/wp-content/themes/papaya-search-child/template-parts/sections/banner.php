<?php
/** Full-width banner, with the image selected in the page's ACF fields. */
defined('ABSPATH') || exit(); ?>
<section class="<?php echo esc_attr($args['class'] ?? 'page-banner'); ?>">
    <?php get_template_part('template-parts/components/content-items', null, [
        'items' => $args['items'] ?? [],
    ]); ?>
</section>
