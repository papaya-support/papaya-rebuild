<?php
/** Reusable introduction, heading, call-to-action or featured-media section. */
defined('ABSPATH') || exit; ?>
<section class="<?php echo esc_attr($args['class'] ?? 'section'); ?>">
<div class="<?php echo esc_attr($args['container_class'] ?? 'container'); ?>">
<?php get_template_part('template-parts/components/content-items', null, ['items' => $args['items'] ?? []]); ?>
</div>
</section>
