<?php
/** Shared service cards with optional section heading, subtitles and anchor IDs. */
defined('ABSPATH') || exit; ?>
<section class="<?php echo esc_attr($args['class']); ?>">
<?php if (!empty($args['heading'])) : ?><div class="container">
<?php get_template_part('template-parts/components/content-items', null, ['items' => $args['heading']]); ?>
<?php endif; ?>
<div class="<?php echo esc_attr($args['grid_class']); ?>">
<?php foreach ($args['cards'] as $card) : $tag = ($card['tag'] ?? '') === 'article' ? 'article' : 'div'; ?>
<<?php echo $tag; ?> class="service-card"<?php if (!empty($card['id'])) : ?> id="<?php echo esc_attr($card['id']); ?>"<?php endif; ?>>
<img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/' . $card['icon']); ?>" alt="" aria-hidden="true">
<?php get_template_part('template-parts/components/content-items', null, ['items' => $card['items']]); ?>
</<?php echo $tag; ?>>
<?php endforeach; ?>
</div>
<?php if (!empty($args['heading'])) : ?></div><?php endif; ?>
</section>
