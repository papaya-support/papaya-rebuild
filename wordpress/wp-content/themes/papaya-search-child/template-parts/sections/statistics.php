<?php
/** Shared three-column statistics or benefits section with optional surrounding copy. */
defined('ABSPATH') || exit(); ?>
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
