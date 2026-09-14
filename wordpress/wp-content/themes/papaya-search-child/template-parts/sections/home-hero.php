<?php defined('ABSPATH') || exit; ?>
<section class="section-home-hero home-hero">
<?php /* ACF: Hero — Background Image */ ps_image('home_image_4b5fa21323', 'hero-image', true); ?>
<div class="container hero-copy">
<?php /* ACF: Hero — Heading */ ps_text('home_1d791eb121c5', 'h1', ''); ?>
</div><img class="hero-emblem" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/hero-emblem.svg" alt="" aria-hidden="true">
</section>
