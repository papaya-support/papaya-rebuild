<?php defined('ABSPATH') || exit(); ?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/footer-logo.svg'); ?>" alt="Papaya Search — Be seen. Stay ahead. Grow smarter." width="280" height="240"
                    loading="lazy"></a>
            <?php /* ACF: Footer — Call Button Label */
                        ps_button('shared_7b3ca0dbb8c9', ps_booking_url(), 'button-green'); ?>
        </div>
        <section class="footer-column">
            <?php /* ACF: Footer — Navigation Heading */
                        ps_text('shared_78f8d1b3d0a8', 'h2', 'footer-heading'); ?>
            <?php ps_render_menu('footer_navigation'); ?>
        </section>
        <section class="footer-column">
            <?php /* ACF: Footer — Services Heading */
                        ps_text('shared_63ce494a885e', 'h2', 'footer-heading'); ?>
            <?php ps_render_menu('footer_services'); ?>
        </section>
        <section class="footer-column">
            <?php /* ACF: Footer — Contact Heading */
                        ps_text('shared_4937e4e54c2c', 'h2', 'footer-heading'); ?>
            <?php ps_render_menu('footer_contact'); ?>
            <?php ps_render_menu('footer_social'); ?>
        </section>
    </div>
    <div class="container footer-legal"><?php ps_footer_legal(); ?></div>
</footer>
