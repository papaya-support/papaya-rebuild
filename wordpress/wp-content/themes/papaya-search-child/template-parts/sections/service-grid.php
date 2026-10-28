<?php
/** Shared service-grid sections: read ACF groups here and keep their markup together. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
?>
<?php if ($variant === 'services-service-grid'):
    $section_service_grid = function_exists('get_field') ? (get_field('section_service_grid', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_service_grid, 'heading', 'services_fb1240267041');
    $subtitle = ps_section_value($section_service_grid, 'subtitle', 'services_92fa01adc928');
    $description = ps_section_value($section_service_grid, 'description', 'services_61a499cbf5ad');
    $button_label = ps_section_value($section_service_grid, 'button_label', 'services_403955cd73a3');
    $button_url = ps_section_value($section_service_grid, 'button_url', 'services_403955cd73a3_url');
    $heading_2 = ps_section_value($section_service_grid, 'heading_2', 'services_b8a1a172da9c');
    $subtitle_2 = ps_section_value($section_service_grid, 'subtitle_2', 'services_798933f3ef4e');
    $description_2 = ps_section_value($section_service_grid, 'description_2', 'services_1b7af68589dc');
    $button_label_2 = ps_section_value($section_service_grid, 'button_label_2', 'services_350220fa15e8');
    $button_url_2 = ps_section_value($section_service_grid, 'button_url_2', 'services_350220fa15e8_url');
    $heading_3 = ps_section_value($section_service_grid, 'heading_3', 'services_879ad37c29e0');
    $subtitle_3 = ps_section_value($section_service_grid, 'subtitle_3', 'services_822294744ef5');
    $description_3 = ps_section_value($section_service_grid, 'description_3', 'services_00de51947158');
    $button_label_3 = ps_section_value($section_service_grid, 'button_label_3', 'services_cf9924922477');
    $button_url_3 = ps_section_value($section_service_grid, 'button_url_3', 'services_cf9924922477_url');
    $heading_4 = ps_section_value($section_service_grid, 'heading_4', 'services_e0dbcb08a264');
    $subtitle_4 = ps_section_value($section_service_grid, 'subtitle_4', 'services_a706330f88c5');
    $description_4 = ps_section_value($section_service_grid, 'description_4', 'services_ff171c150d31');
    $button_label_4 = ps_section_value($section_service_grid, 'button_label_4', 'services_8c6eed7c6379');
    $button_url_4 = ps_section_value($section_service_grid, 'button_url_4', 'services_8c6eed7c6379_url');
    ?>
    <!-- Services Service Grid -->
    <section class="section-services-service-grid section section-tight center">
        <div class="container grid grid-two services-grid">
            <article class="service-card" id="search-engine-optimization">
                <img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/service-seo.svg'); ?>" alt="" aria-hidden="true">
                <?php if ($heading !== ''): ?>
                    <h2 class="" data-field="services_fb1240267041"><?php echo ps_section_text($heading, 'services_fb1240267041', 'h2', ''); ?></h2>
                <?php endif; ?>
                <?php if ($subtitle !== ''): ?>
                    <h3 class="accent" data-field="services_92fa01adc928"><?php echo ps_section_text($subtitle, 'services_92fa01adc928', 'h3', 'accent'); ?></h3>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="services_61a499cbf5ad"><?php echo ps_section_text($description, 'services_61a499cbf5ad', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label, wp_strip_all_tags(ps_inline_content('services_92fa01adc928', $subtitle))); ?> class="button button-small" data-field="services_403955cd73a3" href="<?php echo esc_url(ps_local_service_url($button_url ?: ps_route('services') . '#search-engine-optimization')); ?>"><?php echo ps_inline_content('services_403955cd73a3', $button_label); ?></a>
                <?php endif; ?>
            </article>
            <article class="service-card" id="search-engine-marketing">
                <img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/service-ppc.svg'); ?>" alt="" aria-hidden="true">
                <?php if ($heading_2 !== ''): ?>
                    <h2 class="" data-field="services_b8a1a172da9c"><?php echo ps_section_text($heading_2, 'services_b8a1a172da9c', 'h2', ''); ?></h2>
                <?php endif; ?>
                <?php if ($subtitle_2 !== ''): ?>
                    <h3 class="accent" data-field="services_798933f3ef4e"><?php echo ps_section_text($subtitle_2, 'services_798933f3ef4e', 'h3', 'accent'); ?></h3>
                <?php endif; ?>
                <?php if ($description_2 !== ''): ?>
                    <div class="prose " data-field="services_1b7af68589dc"><?php echo ps_section_text($description_2, 'services_1b7af68589dc', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label_2 !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label_2, wp_strip_all_tags(ps_inline_content('services_798933f3ef4e', $subtitle_2))); ?> class="button button-small" data-field="services_350220fa15e8" href="<?php echo esc_url(ps_local_service_url($button_url_2 ?: ps_route('search-engine-marketing'))); ?>"><?php echo ps_inline_content('services_350220fa15e8', $button_label_2); ?></a>
                <?php endif; ?>
            </article>
            <article class="service-card" id="website-analytics">
                <img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/service-analytics.svg'); ?>" alt="" aria-hidden="true">
                <?php if ($heading_3 !== ''): ?>
                    <h2 class="" data-field="services_879ad37c29e0"><?php echo ps_section_text($heading_3, 'services_879ad37c29e0', 'h2', ''); ?></h2>
                <?php endif; ?>
                <?php if ($subtitle_3 !== ''): ?>
                    <h3 class="accent" data-field="services_822294744ef5"><?php echo ps_section_text($subtitle_3, 'services_822294744ef5', 'h3', 'accent'); ?></h3>
                <?php endif; ?>
                <?php if ($description_3 !== ''): ?>
                    <div class="prose " data-field="services_00de51947158"><?php echo ps_section_text($description_3, 'services_00de51947158', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label_3 !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label_3, wp_strip_all_tags(ps_inline_content('services_822294744ef5', $subtitle_3))); ?> class="button button-small" data-field="services_cf9924922477" href="<?php echo esc_url(ps_local_service_url($button_url_3 ?: ps_route('services') . '#website-analytics')); ?>"><?php echo ps_inline_content('services_cf9924922477', $button_label_3); ?></a>
                <?php endif; ?>
            </article>
            <article class="service-card" id="wordpress-maintenance">
                <img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/service-ai.svg'); ?>" alt="" aria-hidden="true">
                <?php if ($heading_4 !== ''): ?>
                    <h2 class="" data-field="services_e0dbcb08a264"><?php echo ps_section_text($heading_4, 'services_e0dbcb08a264', 'h2', ''); ?></h2>
                <?php endif; ?>
                <?php if ($subtitle_4 !== ''): ?>
                    <h3 class="accent" data-field="services_a706330f88c5"><?php echo ps_section_text($subtitle_4, 'services_a706330f88c5', 'h3', 'accent'); ?></h3>
                <?php endif; ?>
                <?php if ($description_4 !== ''): ?>
                    <div class="prose " data-field="services_ff171c150d31"><?php echo ps_section_text($description_4, 'services_ff171c150d31', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label_4 !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label_4, wp_strip_all_tags(ps_inline_content('services_a706330f88c5', $subtitle_4))); ?> class="button button-small" data-field="services_8c6eed7c6379" href="<?php echo esc_url(ps_local_service_url($button_url_4 ?: ps_route('services') . '#wordpress-maintenance')); ?>"><?php echo ps_inline_content('services_8c6eed7c6379', $button_label_4); ?></a>
                <?php endif; ?>
            </article>
        </div>
    </section>
<?php endif; ?>

<?php if ($variant === 'home-services'):
    $section_services = function_exists('get_field') ? (get_field('section_services', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_services, 'heading', 'home_536fe4a39b43');
    $description = ps_section_value($section_services, 'description', 'home_a2521eac95bf');
    $heading_2 = ps_section_value($section_services, 'heading_2', 'home_3416c7bfdc6a');
    $description_2 = ps_section_value($section_services, 'description_2', 'home_87d8101d772c');
    $heading_3 = ps_section_value($section_services, 'heading_3', 'home_ef6e013268e4');
    $description_3 = ps_section_value($section_services, 'description_3', 'home_87c29f9a637c');
    $heading_4 = ps_section_value($section_services, 'heading_4', 'home_9e841ae19a6e');
    $description_4 = ps_section_value($section_services, 'description_4', 'home_c6db28f931d0');
    $heading_5 = ps_section_value($section_services, 'heading_5', 'home_473db858b064');
    ?>
    <!-- Home Services -->
    <section class="section-home-services section cream center">
        <div class="container">
            <?php if ($heading_5 !== ''): ?>
                <h2 class="section-title home-services-eyebrow" data-field="home_473db858b064"><?php echo ps_section_text($heading_5, 'home_473db858b064', 'h2', 'section-title home-services-eyebrow'); ?></h2>
            <?php endif; ?>
            <div class="grid grid-four">
                <div class="service-card home-service-card">
                    <img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/service-seo.svg'); ?>" alt="" aria-hidden="true">
                    <?php if ($heading !== ''): ?>
                        <h3 class="" data-field="home_536fe4a39b43"><a class="home-service-link" href="<?php $service_page = get_page_by_path('services/seo'); echo esc_url($service_page ? get_permalink($service_page) : home_url('/services/seo/')); ?>"><?php echo ps_section_text($heading, 'home_536fe4a39b43', 'h3', ''); ?></a></h3>
                    <?php endif; ?>
                    <?php if ($description !== ''): ?>
                        <div class="prose " data-field="home_a2521eac95bf"><?php echo ps_section_text($description, 'home_a2521eac95bf', 'div', 'prose '); ?></div>
                    <?php endif; ?>
                </div>
                <div class="service-card home-service-card">
                    <img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/service-ppc.svg'); ?>" alt="" aria-hidden="true">
                    <?php if ($heading_2 !== ''): ?>
                        <h3 class="" data-field="home_3416c7bfdc6a"><a class="home-service-link" href="<?php $service_page = get_page_by_path('services/search-engine-marketing'); echo esc_url($service_page ? get_permalink($service_page) : home_url('/services/search-engine-marketing/')); ?>"><?php echo ps_section_text($heading_2, 'home_3416c7bfdc6a', 'h3', ''); ?></a></h3>
                    <?php endif; ?>
                    <?php if ($description_2 !== ''): ?>
                        <div class="prose " data-field="home_87d8101d772c"><?php echo ps_section_text($description_2, 'home_87d8101d772c', 'div', 'prose '); ?></div>
                    <?php endif; ?>
                </div>
                <div class="service-card home-service-card">
                    <img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/service-analytics.svg'); ?>" alt="" aria-hidden="true">
                    <?php if ($heading_3 !== ''): ?>
                        <h3 class="" data-field="home_ef6e013268e4"><a class="home-service-link" href="<?php $service_page = get_page_by_path('services/website-analytics'); echo esc_url($service_page ? get_permalink($service_page) : home_url('/services/website-analytics/')); ?>"><?php echo ps_section_text($heading_3, 'home_ef6e013268e4', 'h3', ''); ?></a></h3>
                    <?php endif; ?>
                    <?php if ($description_3 !== ''): ?>
                        <div class="prose " data-field="home_87c29f9a637c"><?php echo ps_section_text($description_3, 'home_87c29f9a637c', 'div', 'prose '); ?></div>
                    <?php endif; ?>
                </div>
                <div class="service-card home-service-card">
                    <img class="service-icon" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/service-ai.svg'); ?>" alt="" aria-hidden="true">
                    <?php if ($heading_4 !== ''): ?>
                        <h3 class="" data-field="home_9e841ae19a6e"><a class="home-service-link" href="<?php $service_page = get_page_by_path('services/ai-seo-aeo-services'); echo esc_url($service_page ? get_permalink($service_page) : home_url('/services/ai-seo-aeo-services/')); ?>"><?php echo ps_section_text($heading_4, 'home_9e841ae19a6e', 'h3', ''); ?></a></h3>
                    <?php endif; ?>
                    <?php if ($description_4 !== ''): ?>
                        <div class="prose " data-field="home_c6db28f931d0"><?php echo ps_section_text($description_4, 'home_c6db28f931d0', 'div', 'prose '); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
