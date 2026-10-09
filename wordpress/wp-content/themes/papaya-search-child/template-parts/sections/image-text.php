<?php
/** Two-column image and text section. Column order is desktop order; CSS puts media first on mobile. */
defined('ABSPATH') || exit();

$args = ps_section_layout('image-text', $args ?? []);
if (!empty($args['skip'])) {
    return;
}

?>
<section class="<?php echo esc_attr($args['class'] ?? 'section'); ?>">
    <?php if (!empty($args['heading'])): ?>
    <div class="container">
        <?php get_template_part('template-parts/components/content-items', null, [
            'items' => $args['heading'],
        ]); ?>
    </div>
    <?php endif; ?>
    <div class="container<?php echo count($args['columns']) > 1 ? ' split' : ''; ?>">
        <?php foreach ($args['columns'] ?? [] as $column): ?>
        <div class="<?php echo esc_attr($column['class']); ?>">
            <?php if (!empty($column['wrapper'])): ?><div class="<?php echo esc_attr($column['wrapper']); ?>"><?php endif; ?>
                <?php get_template_part('template-parts/components/content-items', null, [
                    'items' => $column['items'],
                ]); ?>
                <?php if (!empty($column['wrapper'])): ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
