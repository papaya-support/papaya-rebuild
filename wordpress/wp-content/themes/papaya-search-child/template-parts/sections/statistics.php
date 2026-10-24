<?php
/** Shared statistics sections: read ACF groups here and keep their markup together. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
$is_imported_service = (bool) get_post_meta(get_the_ID(), '_ps_live_service_source', true);
?>
<?php if ($variant === 'services-closing-cta'):
    $section_closing_cta = function_exists('get_field') ? (get_field('section_closing_cta', get_the_ID(), false) ?: []) : [];
    $text = ps_section_value($section_closing_cta, 'text', 'services_bed98f20a891');
    $text_2 = ps_section_value($section_closing_cta, 'text_2', 'services_8eb4c91b801a');
    $text_3 = ps_section_value($section_closing_cta, 'text_3', 'services_bd0b0253035b');
    $heading = ps_section_value($section_closing_cta, 'heading', 'services_94024e830fa2');
    $button_label = ps_section_value($section_closing_cta, 'button_label', 'services_4b4ceb001243');
    $button_url = ps_section_value($section_closing_cta, 'button_url', 'services_4b4ceb001243_url');
    ?>
    <!-- Services Closing Cta -->
    <?php if (!$is_imported_service || ($heading !== '' || $button_label !== '' || $text !== '' || $text_2 !== '' || $text_3 !== '')): ?>
        <section class="section-services-closing-cta section section-cta green">
            <div class="container center">
                <?php if ($heading !== ''): ?>
                    <h2 class=" is-uppercase" data-field="services_94024e830fa2"><?php echo ps_section_text($heading, 'services_94024e830fa2', 'h2', ' is-uppercase'); ?></h2>
                <?php endif; ?>
                <?php if (!$is_imported_service || ($text !== '' || $text_2 !== '' || $text_3 !== '')): ?>
                    <div class="grid grid-three stats">
                        <?php if (!$is_imported_service || ($text !== '')): ?>
                            <div class="stat">
                                <?php if ($text !== ''): ?>
                                    <h3 class="" data-field="services_bed98f20a891"><?php echo ps_section_text($text, 'services_bed98f20a891', 'h3', ''); ?></h3>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!$is_imported_service || ($text_2 !== '')): ?>
                            <div class="stat">
                                <?php if ($text_2 !== ''): ?>
                                    <h3 class="" data-field="services_8eb4c91b801a"><?php echo ps_section_text($text_2, 'services_8eb4c91b801a', 'h3', ''); ?></h3>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!$is_imported_service || ($text_3 !== '')): ?>
                            <div class="stat">
                                <?php if ($text_3 !== ''): ?>
                                    <h3 class="" data-field="services_bd0b0253035b"><?php echo ps_section_text($text_3, 'services_bd0b0253035b', 'h3', ''); ?></h3>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($button_label !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label, ''); ?> class="button button-peach" data-field="services_4b4ceb001243" href="<?php echo esc_url(ps_local_service_url($button_url ?: ps_booking_url())); ?>"><?php echo ps_inline_content('services_4b4ceb001243', $button_label); ?></a>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'home-results'):
    $introduction = ps_field_value('home_results_introduction');
    $section_results = function_exists('get_field') ? (get_field('section_results', get_the_ID(), false) ?: []) : [];
    $years_statistic = ps_section_value($section_results, 'years_statistic', 'home_840918b075ea');
    $description = ps_section_value($section_results, 'description', 'home_d17566645c30');
    $years_statistic_2 = ps_section_value($section_results, 'years_statistic_2', 'home_4973267b1258');
    $description_2 = ps_section_value($section_results, 'description_2', 'home_ea2e588e1e39');
    $statistic = ps_section_value($section_results, 'statistic', 'home_95726762e92f');
    $description_3 = ps_section_value($section_results, 'description_3', 'home_6b3533e29b8d');
    $heading = ps_section_value($section_results, 'heading', 'home_6666581aab96');
    $button_label = ps_section_value($section_results, 'button_label', 'home_d4db261ea551');
    $button_url = ps_section_value($section_results, 'button_url', 'home_d4db261ea551_url');
    ?>
    <!-- Home Results -->
    <?php if (!$is_imported_service || ($heading !== '' || $introduction !== '' || $button_label !== '' || $years_statistic !== '' || $description !== '' || $years_statistic_2 !== '' || $description_2 !== '' || $statistic !== '' || $description_3 !== '')): ?>
        <section class="section-home-results section">
            <div class="container center narrow">
                <?php if ($heading !== ''): ?>
                    <h2 class="accent" data-field="home_6666581aab96"><?php echo ps_section_text($heading, 'home_6666581aab96', 'h2', 'accent'); ?></h2>
                <?php endif; ?>
                <?php if ($introduction !== ''): ?>
                    <div class="prose home-results-introduction"><?php echo wp_kses_post(wpautop($introduction)); ?></div>
                <?php endif; ?>
                <?php if (!$is_imported_service || ($years_statistic !== '' || $description !== '' || $years_statistic_2 !== '' || $description_2 !== '' || $statistic !== '' || $description_3 !== '')): ?>
                    <div class="grid grid-three stats">
                        <?php if (!$is_imported_service || ($years_statistic !== '' || $description !== '')): ?>
                            <div class="stat">
                                <?php if ($years_statistic !== ''): ?>
                                    <h3 class=" is-uppercase" data-field="home_840918b075ea"><?php echo ps_section_text($years_statistic, 'home_840918b075ea', 'h3', ' is-uppercase'); ?></h3>
                                <?php endif; ?>
                                <?php if ($description !== ''): ?>
                                    <div class="prose " data-field="home_d17566645c30"><?php echo ps_section_text($description, 'home_d17566645c30', 'div', 'prose '); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!$is_imported_service || ($years_statistic_2 !== '' || $description_2 !== '')): ?>
                            <div class="stat">
                                <?php if ($years_statistic_2 !== ''): ?>
                                    <h3 class=" is-uppercase" data-field="home_4973267b1258"><?php echo ps_section_text($years_statistic_2, 'home_4973267b1258', 'h3', ' is-uppercase'); ?></h3>
                                <?php endif; ?>
                                <?php if ($description_2 !== ''): ?>
                                    <div class="prose " data-field="home_ea2e588e1e39"><?php echo ps_section_text($description_2, 'home_ea2e588e1e39', 'div', 'prose '); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!$is_imported_service || ($statistic !== '' || $description_3 !== '')): ?>
                            <div class="stat">
                                <?php if ($statistic !== ''): ?>
                                    <h3 class=" is-uppercase" data-field="home_95726762e92f"><?php echo ps_section_text($statistic, 'home_95726762e92f', 'h3', ' is-uppercase'); ?></h3>
                                <?php endif; ?>
                                <?php if ($description_3 !== ''): ?>
                                    <div class="prose " data-field="home_6b3533e29b8d"><?php echo ps_section_text($description_3, 'home_6b3533e29b8d', 'div', 'prose '); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($button_label !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label, ''); ?> class="button " data-field="home_d4db261ea551" href="<?php echo esc_url(ps_local_service_url($button_url ?: ps_route('case-studies'))); ?>"><?php echo ps_inline_content('home_d4db261ea551', $button_label); ?></a>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'search-engine-marketing-benefits'):
    $section_benefits = function_exists('get_field') ? (get_field('section_benefits', get_the_ID(), false) ?: []) : [];
    $statistic = ps_section_value($section_benefits, 'statistic', 'search_engine_marketing_d7c7e6181717');
    $description_2 = ps_section_value($section_benefits, 'description_2', 'search_engine_marketing_57c1652a3574');
    $heading_2 = ps_section_value($section_benefits, 'heading_2', 'search_engine_marketing_87a5e5276ed3');
    $description_3 = ps_section_value($section_benefits, 'description_3', 'search_engine_marketing_f063a20ed3bd');
    $heading_3 = ps_section_value($section_benefits, 'heading_3', 'search_engine_marketing_c552a4ad539c');
    $description_4 = ps_section_value($section_benefits, 'description_4', 'search_engine_marketing_b0fd5c696ade');
    $heading = ps_section_value($section_benefits, 'heading', 'search_engine_marketing_8f270f2a2afd');
    $description = ps_section_value($section_benefits, 'description', 'search_engine_marketing_8ac1ff91ac2a');
    $button_label = ps_section_value($section_benefits, 'button_label', 'search_engine_marketing_f56f157847bf');
    $button_url = ps_section_value($section_benefits, 'button_url', 'search_engine_marketing_f56f157847bf_url');
    ?>
    <!-- Search Engine Marketing Benefits -->
    <?php if (!$is_imported_service || ($heading !== '' || $description !== '' || $button_label !== '' || $statistic !== '' || $description_2 !== '' || $heading_2 !== '' || $description_3 !== '' || $heading_3 !== '' || $description_4 !== '')): ?>
        <section class="section-search-engine-marketing-benefits section cream">
            <div class="container center">
                <?php if ($heading !== ''): ?>
                    <h2 class="" data-field="search_engine_marketing_8f270f2a2afd"><?php echo ps_section_text($heading, 'search_engine_marketing_8f270f2a2afd', 'h2', ''); ?></h2>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="search_engine_marketing_8ac1ff91ac2a"><?php echo ps_section_text($description, 'search_engine_marketing_8ac1ff91ac2a', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if (!$is_imported_service || ($statistic !== '' || $description_2 !== '' || $heading_2 !== '' || $description_3 !== '' || $heading_3 !== '' || $description_4 !== '')): ?>
                    <div class="grid grid-three stats">
                        <?php if (!$is_imported_service || ($statistic !== '' || $description_2 !== '')): ?>
                            <div class="stat">
                                <?php if ($statistic !== ''): ?>
                                    <h3 class="accent" data-field="search_engine_marketing_d7c7e6181717"><?php echo ps_section_text($statistic, 'search_engine_marketing_d7c7e6181717', 'h3', 'accent'); ?></h3>
                                <?php endif; ?>
                                <?php if ($description_2 !== ''): ?>
                                    <div class="prose " data-field="search_engine_marketing_57c1652a3574"><?php echo ps_section_text($description_2, 'search_engine_marketing_57c1652a3574', 'div', 'prose '); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!$is_imported_service || ($heading_2 !== '' || $description_3 !== '')): ?>
                            <div class="stat">
                                <?php if ($heading_2 !== ''): ?>
                                    <h3 class="accent" data-field="search_engine_marketing_87a5e5276ed3"><?php echo ps_section_text($heading_2, 'search_engine_marketing_87a5e5276ed3', 'h3', 'accent'); ?></h3>
                                <?php endif; ?>
                                <?php if ($description_3 !== ''): ?>
                                    <div class="prose " data-field="search_engine_marketing_f063a20ed3bd"><?php echo ps_section_text($description_3, 'search_engine_marketing_f063a20ed3bd', 'div', 'prose '); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!$is_imported_service || ($heading_3 !== '' || $description_4 !== '')): ?>
                            <div class="stat">
                                <?php if ($heading_3 !== ''): ?>
                                    <h3 class="accent" data-field="search_engine_marketing_c552a4ad539c"><?php echo ps_section_text($heading_3, 'search_engine_marketing_c552a4ad539c', 'h3', 'accent'); ?></h3>
                                <?php endif; ?>
                                <?php if ($description_4 !== ''): ?>
                                    <div class="prose " data-field="search_engine_marketing_b0fd5c696ade"><?php echo ps_section_text($description_4, 'search_engine_marketing_b0fd5c696ade', 'div', 'prose '); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($button_label !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label, ''); ?> class="button button-peach" data-field="search_engine_marketing_f56f157847bf" href="<?php echo esc_url(ps_local_service_url($button_url ?: ps_booking_url())); ?>"><?php echo ps_inline_content('search_engine_marketing_f56f157847bf', $button_label); ?></a>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>
