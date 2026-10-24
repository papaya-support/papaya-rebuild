<?php
/** Shared testimonial layout with optional introduction and button. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
$seo = $variant === 'seo-testimonial';
$case = $variant === 'case-study-detail-testimonial';
if (!in_array($variant, ['home-testimonial', 'case-study-detail-testimonial', 'seo-testimonial'], true) || ($seo && !is_page('seo'))) return;
$data = function_exists('get_field') ? (get_field($seo ? 'field_ps_seo_testimonial' : ($case ? 'field_ps_section_case_study_detail_section_testimonial' : 'field_ps_section_home_section_testimonial')) ?: []) : [];
$prefix = $seo ? 'ps_seo_testimonial_' : ($case ? 'case_study_detail_testimonial_' : 'home_testimonial_');
$keys = [
    'heading' => $prefix . 'heading',
    $seo ? 'description' : 'intro_description' => $prefix . ($seo ? 'description' : 'intro_description'),
    'quote_mark' => $seo ? $prefix . 'quote_mark' : ($case ? 'case_study_detail_7e19b95f2be4' : 'home_c71150b00266'),
    $seo ? 'quote' : 'description' => $seo ? $prefix . 'quote' : ($case ? 'case_study_detail_3f5be18822e8' : 'home_71e8d5ebfb3e'),
    $seo ? 'client_name' : 'attribution' => $seo ? $prefix . 'client_name' : ($case ? 'case_study_detail_74dd8a1b256d' : 'home_7393b65da373'),
    'business_name' => $prefix . 'business_name',
    'button' => ($seo ? 'seo_testimonial_' : $prefix) . 'button',
];
foreach ($keys as $name => $key) {
    if (array_key_exists('field_' . $key, $data)) $data[$name] = $data['field_' . $key];
}
$heading = trim($data['heading'] ?? '');
$intro = trim($data[$seo ? 'description' : 'intro_description'] ?? '');
$quote = trim($data[$seo ? 'quote' : 'description'] ?? '');
$client = trim($data[$seo ? 'client_name' : 'attribution'] ?? '');
$business = trim($data['business_name'] ?? '');
$mark = $data['quote_mark'] ?? '“';
$button = $data['button'] ?? [];
$old_target = is_array($button) ? ($button['target'] ?? '') : '';
$button = ps_editable_button($seo ? 'section_seo_testimonial' : 'section_testimonial', 'button', is_array($button) ? ($button['title'] ?? '') : '', is_array($button) ? ($button['url'] ?? '') : '');
$button['target'] = $old_target;
$show_button = is_array($button) && !empty($button['url']) && !empty($button['title']);
if ($heading === '' && $intro === '' && $quote === '' && !$show_button) return;
?>
<section class="section-<?php echo esc_attr($variant); ?>">
        <?php if ($heading !== '' || $intro !== ''): ?>
            <div class="container testimonial-intro center">
                <?php if ($heading !== ''): ?><h2><?php echo esc_html($heading); ?></h2><?php endif; ?>
                <?php if ($intro !== ''): ?><div class="prose"><?php echo wp_kses_post(wpautop($intro)); ?></div><?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ($quote !== '' || $show_button): ?>
        <div class="section <?php echo $case ? 'section-tight' : 'green'; ?>">
            <div class="container testimonial <?php echo $case ? 'cream testimonial-panel' : ''; ?>">
                <?php if (!$case && $quote !== ''): ?><img class="quote-illustration" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/testimonial-bird.svg'); ?>" alt="" aria-hidden="true"><?php endif; ?>
                <div class="testimonial-content">
                <?php if ($quote !== ''): ?>
                <blockquote>
                    <?php if ($mark !== ''): ?><span class="quote-mark" aria-hidden="true"><?php echo esc_html($mark); ?></span><?php endif; ?>
                    <div class="prose"><?php echo wp_kses_post(wpautop($quote)); ?></div>
                    <?php if ($client !== '' || $business !== ''): ?>
                        <cite class="is-uppercase"><?php echo esc_html(($seo ? '– ' : '') . implode(', ', array_filter([$client, $business], static fn($value) => $value !== ''))); ?></cite>
                    <?php endif; ?>
                </blockquote>
                <?php endif; ?>
        <?php if ($show_button): ?>
            <div class="testimonial-action center"><a class="button" href="<?php echo esc_url($button['url']); ?>"<?php if (($button['target'] ?? '') === '_blank'): ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>><?php echo esc_html($button['title']); ?></a></div>
        <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
</section>
