<?php defined('ABSPATH') || exit; ?>
<section class="section-services-service-grid section section-tight center">
<div class="container grid grid-two services-grid">
<article class="service-card" id="search-engine-optimization"><img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/service-seo.svg" alt="" aria-hidden="true"><?php /* ACF: SEO Service — Title */ ps_text('services_fb1240267041', 'h2', ''); ?>
<?php /* ACF: SEO Service — Subtitle */ ps_text('services_92fa01adc928', 'h3', 'accent'); ?>
<?php /* ACF: SEO Service — Description */ ps_text('services_61a499cbf5ad', 'div', 'prose '); ?>
<?php /* ACF: SEO Service — Button Label */ ps_button('services_403955cd73a3', ps_route('services').'#search-engine-optimization', 'button-small'); ?>
</article>
<article class="service-card" id="search-engine-marketing"><img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/service-ppc.svg" alt="" aria-hidden="true"><?php /* ACF: SEM Service — Title */ ps_text('services_b8a1a172da9c', 'h2', ''); ?>
<?php /* ACF: SEM Service — Subtitle */ ps_text('services_798933f3ef4e', 'h3', 'accent'); ?>
<?php /* ACF: SEM Service — Description */ ps_text('services_1b7af68589dc', 'div', 'prose '); ?>
<?php /* ACF: SEM Service — Button Label */ ps_button('services_350220fa15e8', ps_route('search-engine-marketing'), 'button-small'); ?>
</article>
<article class="service-card" id="website-analytics"><img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/service-analytics.svg" alt="" aria-hidden="true"><?php /* ACF: Analytics Service — Title */ ps_text('services_879ad37c29e0', 'h2', ''); ?>
<?php /* ACF: Analytics Service — Subtitle */ ps_text('services_822294744ef5', 'h3', 'accent'); ?>
<?php /* ACF: Analytics Service — Description */ ps_text('services_00de51947158', 'div', 'prose '); ?>
<?php /* ACF: Analytics Service — Button Label */ ps_button('services_cf9924922477', ps_route('services').'#website-analytics', 'button-small'); ?>
</article>
<article class="service-card" id="wordpress-maintenance"><img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/service-ai.svg" alt="" aria-hidden="true"><?php /* ACF: WordPress Service — Title */ ps_text('services_e0dbcb08a264', 'h2', ''); ?>
<?php /* ACF: WordPress Service — Subtitle */ ps_text('services_a706330f88c5', 'h3', 'accent'); ?>
<?php /* ACF: WordPress Service — Description */ ps_text('services_ff171c150d31', 'div', 'prose '); ?>
<?php /* ACF: WordPress Service — Button Label */ ps_button('services_8c6eed7c6379', ps_route('services').'#wordpress-maintenance', 'button-small'); ?>
</article>
</div>
</section>
