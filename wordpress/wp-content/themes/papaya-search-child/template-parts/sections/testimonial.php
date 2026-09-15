<?php
/** Shared testimonial with optional decorative illustration. */
defined('ABSPATH') || exit; ?>
<section class="<?php echo esc_attr($args['class'] ?? 'section'); ?>">
<div class="<?php echo esc_attr($args['container_class'] ?? 'container testimonial'); ?>">
<?php if (!empty($args['illustration'])) : ?>
<img class="quote-illustration" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/' . $args['illustration']); ?>" alt="" aria-hidden="true">
<?php endif; ?>
<blockquote><?php get_template_part('template-parts/components/content-items', null, ['items' => $args['items'] ?? []]); ?></blockquote>
</div>
</section>
