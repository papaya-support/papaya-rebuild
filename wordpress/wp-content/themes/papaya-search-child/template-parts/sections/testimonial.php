<?php
/** Shared testimonial carousel, populated from the Testimonials library. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
if (!in_array($variant, ['home-testimonial', 'case-study-detail-testimonial', 'seo-testimonial'], true) || ($variant === 'seo-testimonial' && !is_page('seo'))) return;
$case = $variant === 'case-study-detail-testimonial';
$settings = ps_testimonial_settings($variant);
$testimonials = ps_testimonial_rows();
if (!get_option('ps_acf_options_v1')) {
    foreach (ps_shared_testimonial_ids() as $id) {
        $testimonials[] = [
            'quote' => get_post_meta($id, 'quote', true),
            'client_name' => get_post_meta($id, 'client_name', true),
            'business_name' => get_post_meta($id, 'business_name', true),
            'client_role' => get_post_meta($id, 'client_role', true),
            'image' => get_post_meta($id, 'image', true),
            'image_url' => '',
            'quote_mark' => get_post_meta($id, 'quote_mark', true),
        ];
    }
}
$has_button = $settings['button_label'] !== '' && $settings['button_url'] !== '';
if (!$testimonials && $settings['heading'] === '' && $settings['description'] === '' && !$has_button) return;
?>
<section class="section-<?php echo esc_attr($variant); ?>">
    <?php if ($settings['heading'] !== '' || $settings['description'] !== ''): ?>
        <div class="container testimonial-intro center">
            <?php if ($settings['heading'] !== ''): ?><h2><?php echo esc_html($settings['heading']); ?></h2><?php endif; ?>
            <?php if ($settings['description'] !== ''): ?><div class="prose"><?php echo wp_kses_post(wpautop($settings['description'])); ?></div><?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if ($testimonials || $has_button): ?>
        <div class="section <?php echo $case ? 'section-tight' : 'green'; ?>">
            <?php if ($testimonials): ?>
                <div class="testimonial-slider container" data-testimonial-slider aria-roledescription="carousel" aria-label="Client testimonials">
                    <?php foreach ($testimonials as $index => $testimonial):
                        $quote = trim((string) $testimonial['quote']);
                        $client = trim((string) $testimonial['client_name']);
                        $business = trim((string) $testimonial['business_name']);
                        $role = trim((string) $testimonial['client_role']);
                        $mark = (string) $testimonial['quote_mark'];
                        $image_id = (int) $testimonial['image'];
                        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : esc_url((string) $testimonial['image_url']);
                        $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                    ?>
                    <article class="testimonial-slide testimonial <?php echo $case ? 'cream testimonial-panel' : ''; ?> <?php echo $index === 0 ? 'is-active' : ''; ?>" aria-roledescription="slide" aria-label="Testimonial <?php echo esc_attr($index + 1); ?> of <?php echo esc_attr(count($testimonials)); ?>"<?php if ($index !== 0): ?> aria-hidden="true" inert<?php endif; ?>>
                        <?php if ($image_url): ?><img class="testimonial-portrait" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt ?: $client); ?>" width="145" height="145" loading="lazy"><?php endif; ?>
                        <div class="testimonial-content">
                            <blockquote>
                                <?php if ($mark !== ''): ?><span class="quote-mark" aria-hidden="true"><?php echo esc_html($mark); ?></span><?php endif; ?>
                                <div class="prose"><?php echo wp_kses_post(wpautop($quote)); ?></div>
                                <?php if ($client !== '' || $business !== '' || $role !== ''): ?><cite class="is-uppercase"><?php echo esc_html('– ' . implode(', ', array_filter([$client, $role, $business]))); ?></cite><?php endif; ?>
                            </blockquote>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php if (count($testimonials) > 1): ?>
                    <div class="testimonial-controls" data-testimonial-controls aria-label="Choose a testimonial">
                        <?php foreach ($testimonials as $index => $_): ?><button type="button" class="testimonial-dot <?php echo $index === 0 ? 'is-active' : ''; ?>" data-testimonial-dot="<?php echo esc_attr($index); ?>" aria-label="Show testimonial <?php echo esc_attr($index + 1); ?>" aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>"></button><?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($has_button): ?><div class="container testimonial-action center"><a class="button" href="<?php echo esc_url($settings['button_url']); ?>"><?php echo esc_html($settings['button_label']); ?></a></div><?php endif; ?>
        </div>
    <?php endif; ?>
</section>
