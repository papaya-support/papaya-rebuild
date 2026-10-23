<?php
/** Six equally weighted SEO service cards. */
defined('ABSPATH') || exit();
if (!is_page('seo')) {
    return;
}
$services = function_exists('get_field') ? (get_field('section_seo_services') ?: []) : [];
$services = array_merge(ps_seo_services_defaults(), $services);
?>
<section class="section-seo-services section">
    <div class="container">
        <div class="seo-services-intro center">
            <?php if ($services['heading'] !== ''): ?>
                <h2><?php echo esc_html($services['heading']); ?></h2>
            <?php endif; ?>
            <?php if ($services['description'] !== ''): ?>
                <div class="prose"><?php echo wp_kses_post(wpautop($services['description'])); ?></div>
            <?php endif; ?>
        </div>
        <div class="grid seo-services-grid">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <?php if ($services['heading_' . $i] !== '' || $services['description_' . $i] !== ''): ?>
                    <article class="seo-service-card">
                        <?php if ($services['heading_' . $i] !== ''): ?>
                            <h3><?php echo esc_html($services['heading_' . $i]); ?></h3>
                        <?php endif; ?>
                        <div class="prose"><?php echo wp_kses_post(wpautop($services['description_' . $i])); ?></div>
                    </article>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>
