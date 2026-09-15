<?php
/** Shared image hero, with an optional overlapping ACF emblem. */
defined('ABSPATH') || exit; ?>
<section class="<?php echo esc_attr($args['class']); ?>">
<?php ps_image($args['image'], 'hero-image', true); ?>
<div class="<?php echo esc_attr($args['container_class']); ?>">
<?php get_template_part('template-parts/components/content-items', null, ['items' => $args['items']]); ?>
</div>
<?php if (!empty($args['emblem'])) {ps_image($args['emblem'], 'hero-emblem', true);} ?>
</section>
