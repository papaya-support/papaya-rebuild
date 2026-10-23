<?php
/** Three-stage SEO process. */
defined('ABSPATH') || exit();
if (!is_page('seo')) {
    return;
}
$approach = function_exists('get_field') ? (get_field('section_seo_approach') ?: []) : [];
$approach = array_merge(ps_seo_approach_defaults(), $approach);
?>
<section class="section-seo-approach section">
    <div class="container">
        <div class="seo-approach-intro center">
            <?php if ($approach['heading'] !== ''): ?>
                <h2><?php echo esc_html($approach['heading']); ?></h2>
            <?php endif; ?>
            <?php if ($approach['description'] !== ''): ?>
                <div class="prose"><?php echo wp_kses_post(wpautop($approach['description'])); ?></div>
            <?php endif; ?>
        </div>
        <ol class="seo-approach-steps">
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <?php if ($approach['heading_' . $i] !== '' || $approach['description_' . $i] !== ''): ?>
                    <li class="seo-approach-step">
                        <span class="seo-approach-number" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i)); ?></span>
                        <?php if ($approach['heading_' . $i] !== ''): ?>
                            <h3><?php echo esc_html($approach['heading_' . $i]); ?></h3>
                        <?php endif; ?>
                        <div class="prose"><?php echo wp_kses_post(wpautop($approach['description_' . $i])); ?></div>
                    </li>
                <?php endif; ?>
            <?php endfor; ?>
        </ol>
    </div>
</section>
