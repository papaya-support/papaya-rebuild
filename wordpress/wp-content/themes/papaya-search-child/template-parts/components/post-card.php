<?php
/** Native post card shared by the Blog listing and related articles. */
defined('ABSPATH') || exit;
$heading_tag = ($args['heading'] ?? 'h2') === 'h3' ? 'h3' : 'h2'; ?>
<article class="post-card" data-post-id="<?php the_ID(); ?>">
<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php ps_post_image(); ?></a>
<<?php echo $heading_tag; ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo $heading_tag; ?>>
<?php if (empty($args['compact'])) : ?>
<div class="post-categories"><?php the_category(', '); ?></div>
<div class="prose"><?php echo wp_kses_post(wpautop(get_the_excerpt())); ?></div>
<?php endif; ?>
</article>
