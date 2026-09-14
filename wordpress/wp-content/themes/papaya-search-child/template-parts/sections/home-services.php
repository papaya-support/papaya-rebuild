<?php defined('ABSPATH') || exit; ?>
<section class="section-home-services section cream center">
<div class="container">
<?php /* ACF: Services — Section Heading */ ps_text('home_473db858b064', 'h2', 'section-title'); ?>
<div class="grid grid-four">
<div class="service-card">
<img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/service-seo.svg" alt="" aria-hidden="true"><?php /* ACF: SEO Service — Title */ ps_text('home_536fe4a39b43', 'h3', ''); ?>
<?php /* ACF: SEO Service — Description */ ps_text('home_a2521eac95bf', 'div', 'prose '); ?>
<?php /* ACF: PPC Service — Button Label */ ps_button('home_c04f66011766', ps_route('services').'#search-engine-optimization', 'button-small'); ?>
</div>
<div class="service-card">
<img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/service-ppc.svg" alt="" aria-hidden="true"><?php /* ACF: PPC Service — Title */ ps_text('home_3416c7bfdc6a', 'h3', ''); ?>
<?php /* ACF: PPC Service — Description */ ps_text('home_87d8101d772c', 'div', 'prose '); ?>
<?php /* ACF: Analytics Service — Button Label */ ps_button('home_fadcbf34d7f6', ps_route('search-engine-marketing'), 'button-small'); ?>
</div>
<div class="service-card">
<img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/service-analytics.svg" alt="" aria-hidden="true"><?php /* ACF: Analytics Service — Title */ ps_text('home_ef6e013268e4', 'h3', ''); ?>
<?php /* ACF: Analytics Service — Description */ ps_text('home_87c29f9a637c', 'div', 'prose '); ?>
<?php /* ACF: AI SEO Service — Button Label */ ps_button('home_01079a482a16', ps_route('services').'#website-analytics', 'button-small'); ?>
</div>
<div class="service-card">
<img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/service-ai.svg" alt="" aria-hidden="true"><?php /* ACF: AI SEO Service — Title */ ps_text('home_9e841ae19a6e', 'h3', ''); ?>
<?php /* ACF: AI SEO Service — Description */ ps_text('home_c6db28f931d0', 'div', 'prose '); ?>
<?php /* ACF: SEO Service — Button Label */ ps_button('home_4dbc82586a2a', ps_destinations()['AI SEO'], 'button-small'); ?>
</div>
</div>
</div>
</section>
