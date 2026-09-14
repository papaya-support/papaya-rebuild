<?php defined('ABSPATH') || exit; ?>
<section class="section-home-testimonial section green">
<div class="container testimonial">
<img class="quote-illustration" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/testimonial-bird.svg" alt="" aria-hidden="true"><blockquote><?php /* ACF: Testimonial — Quote Mark */ ps_text('home_c71150b00266', 'span', 'quote-mark'); ?>
<?php /* ACF: Testimonial — Quote */ ps_text('home_71e8d5ebfb3e', 'div', 'prose '); ?>
<?php /* ACF: Testimonial — Attribution */ ps_text('home_7393b65da373', 'cite', ''); ?>
</blockquote>
</div>
</section>
