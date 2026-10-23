<?php
/** Shared testimonial sections: read ACF groups here and keep their markup together. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
?>
<?php if ($variant === 'home-testimonial'):
    $section_testimonial = function_exists('get_field') ? (get_field('section_testimonial', get_the_ID(), false) ?: []) : [];
    $business_name = trim(ps_section_value($section_testimonial, 'business_name', 'home_testimonial_business_name'));
    $quote_mark = ps_section_value($section_testimonial, 'quote_mark', 'home_c71150b00266');
    $description = ps_section_value($section_testimonial, 'description', 'home_71e8d5ebfb3e');
    $attribution = ps_section_value($section_testimonial, 'attribution', 'home_7393b65da373');
    ?>
    <!-- Home Testimonial -->
    <section class="section-home-testimonial section green">
        <div class="container testimonial">
            <img class="quote-illustration" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/testimonial-bird.svg'); ?>" alt="" aria-hidden="true">
            <blockquote>
                <?php if ($quote_mark !== ''): ?>
                    <span class="quote-mark" data-field="home_c71150b00266"><?php echo ps_section_text($quote_mark, 'home_c71150b00266', 'span', 'quote-mark'); ?></span>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="home_71e8d5ebfb3e"><?php echo ps_section_text($description, 'home_71e8d5ebfb3e', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($attribution !== ''): ?>
                    <cite class=" is-uppercase" data-field="home_7393b65da373"><?php echo ps_section_text($attribution, 'home_7393b65da373', 'cite', ' is-uppercase'); ?><?php if ($business_name !== ''): ?>, <span class="testimonial-business"><?php echo esc_html($business_name); ?></span><?php endif; ?></cite>
                <?php endif; ?>
            </blockquote>
        </div>
    </section>
<?php endif; ?>

<?php if ($variant === 'case-study-detail-testimonial'):
    $section_testimonial = function_exists('get_field') ? (get_field('section_testimonial', get_the_ID(), false) ?: []) : [];
    $business_name = trim(ps_section_value($section_testimonial, 'business_name', 'case_study_detail_testimonial_business_name'));
    $quote_mark = ps_section_value($section_testimonial, 'quote_mark', 'case_study_detail_7e19b95f2be4');
    $description = ps_section_value($section_testimonial, 'description', 'case_study_detail_3f5be18822e8');
    $attribution = ps_section_value($section_testimonial, 'attribution', 'case_study_detail_74dd8a1b256d');
    ?>
    <!-- Case Study Detail Testimonial -->
    <section class="section-case-study-detail-testimonial section section-tight">
        <div class="container testimonial cream">

            <blockquote>
                <?php if ($quote_mark !== ''): ?>
                    <span class="quote-mark" data-field="case_study_detail_7e19b95f2be4"><?php echo ps_section_text($quote_mark, 'case_study_detail_7e19b95f2be4', 'span', 'quote-mark'); ?></span>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="case_study_detail_3f5be18822e8"><?php echo ps_section_text($description, 'case_study_detail_3f5be18822e8', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($attribution !== ''): ?>
                    <cite class=" is-uppercase" data-field="case_study_detail_74dd8a1b256d"><?php echo ps_section_text($attribution, 'case_study_detail_74dd8a1b256d', 'cite', ' is-uppercase'); ?><?php if ($business_name !== ''): ?>, <span class="testimonial-business"><?php echo esc_html($business_name); ?></span><?php endif; ?></cite>
                <?php endif; ?>
            </blockquote>
        </div>
    </section>
<?php endif; ?>

<?php if ($variant === 'seo-testimonial' && is_page('seo')):
    $testimonial = function_exists('get_field') ? (get_field('section_seo_testimonial') ?: []) : [];
    $testimonial = array_merge(ps_seo_testimonial_defaults(), $testimonial);
    ?>
    <section class="section-seo-testimonial">
        <div class="container center seo-testimonial-intro">
            <?php if ($testimonial['heading'] !== ''): ?>
                <h2><?php echo esc_html($testimonial['heading']); ?></h2>
            <?php endif; ?>
            <?php if ($testimonial['description'] !== ''): ?>
                <div class="prose"><?php echo wp_kses_post(wpautop($testimonial['description'])); ?></div>
            <?php endif; ?>
        </div>
        <?php if ($testimonial['quote'] !== ''): ?>
            <div class="section green">
                <div class="container testimonial">
                    <img class="quote-illustration" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/testimonial-bird.svg'); ?>" alt="" aria-hidden="true">
                    <blockquote>
                        <span class="quote-mark" aria-hidden="true">“</span>
                        <div class="prose"><?php echo wp_kses_post(wpautop($testimonial['quote'])); ?></div>
                        <?php if ($testimonial['client_name'] !== '' || $testimonial['business_name'] !== ''): ?>
                            <cite class="is-uppercase">– <?php echo esc_html(implode(', ', array_filter([$testimonial['client_name'], $testimonial['business_name']], static fn($name) => $name !== ''))); ?></cite>
                        <?php endif; ?>
                    </blockquote>
                </div>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
