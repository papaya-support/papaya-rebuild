<?php
/** SEO benefits, using the shared service-card layout and brand colors. */
defined('ABSPATH') || exit();
if (!is_page('seo')) {
    return;
}
$benefits = function_exists('get_field') ? (get_field('section_seo_benefits') ?: []) : [];
$benefits = array_merge(ps_seo_benefits_defaults(), $benefits);
?>
<section class="section-seo-benefits section cream">
    <div class="container">
        <div class="seo-benefits-intro center">
            <?php if ($benefits['heading'] !== ''): ?>
                <h2><?php echo esc_html($benefits['heading']); ?></h2>
            <?php endif; ?>
            <?php if ($benefits['description'] !== ''): ?>
                <div class="prose"><?php echo wp_kses_post(wpautop($benefits['description'])); ?></div>
            <?php endif; ?>
        </div>
        <div class="grid grid-two seo-benefits-grid">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <?php if ($benefits['heading_' . $i] !== '' || $benefits['description_' . $i] !== ''): ?>
                    <article class="seo-benefit">
                        <span class="seo-benefit-number" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i)); ?></span>
                        <div>
                            <?php if ($benefits['heading_' . $i] !== ''): ?>
                                <h3><?php echo esc_html($benefits['heading_' . $i]); ?></h3>
                            <?php endif; ?>
                            <div class="prose"><?php echo wp_kses_post(wpautop($benefits['description_' . $i])); ?></div>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>
