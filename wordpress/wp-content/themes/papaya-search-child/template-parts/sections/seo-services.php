<?php
/** Editable SEO service cards from the ACF repeater. */
defined('ABSPATH') || exit();
if (!ps_is_service_detail()) {
    return;
}
$services = function_exists('get_field') ? (get_field('section_seo_services') ?: []) : [];
$services = array_merge(is_page('seo') ? ps_seo_services_defaults() : array_fill_keys(array_keys(ps_seo_services_defaults()), ''), $services);
if (!array_filter($services)) return;
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
            <?php foreach (ps_seo_services_cards($services) as $card): ?>
                <?php if (($card['heading'] ?? '') !== '' || ($card['description'] ?? '') !== ''): ?>
                    <article class="seo-service-card">
                        <?php $icon_url = !empty($card['icon']) ? wp_get_attachment_url((int) $card['icon']) : ($card['legacy_icon_url'] ?? ''); ?>
                        <?php if ($icon_url): ?>
                            <img class="seo-service-icon" src="<?php echo esc_url($icon_url); ?>" alt="" aria-hidden="true" width="64" height="64" loading="lazy" decoding="async">
                        <?php endif; ?>
                        <?php if (($card['heading'] ?? '') !== ''): ?>
                            <h3><?php echo esc_html(($card['heading'] ?? '')); ?></h3>
                        <?php endif; ?>
                        <div class="prose"><?php echo wp_kses_post(wpautop(($card['description'] ?? ''))); ?></div>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
