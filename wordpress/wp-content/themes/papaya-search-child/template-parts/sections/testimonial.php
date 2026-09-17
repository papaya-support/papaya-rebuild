<?php
/** Shared testimonial with optional decorative illustration. */
defined('ABSPATH') || exit();

// Each variant owns its field mapping; the section markup remains shared.
switch ($args['variant'] ?? '') {
    case 'home-testimonial':
        $args = [
            'class' => 'section-home-testimonial section green',
            'container_class' => 'container testimonial',
            'items' => [
                [
                    'type' => 'text',
                    'field' => 'home_c71150b00266',
                    'tag' => 'span',
                    'class' => 'quote-mark',
                ],
                [
                    'type' => 'text',
                    'field' => 'home_71e8d5ebfb3e',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
                ['type' => 'text', 'field' => 'home_7393b65da373', 'tag' => 'cite', 'class' => ''],
            ],
            'illustration' => 'testimonial-bird.svg',
        ];
        break;
    case 'case-study-detail-testimonial':
        $args = [
            'class' => 'section-case-study-detail-testimonial section section-tight',
            'container_class' => 'container testimonial cream',
            'items' => [
                [
                    'type' => 'text',
                    'field' => 'case_study_detail_7e19b95f2be4',
                    'tag' => 'span',
                    'class' => 'quote-mark',
                ],
                [
                    'type' => 'text',
                    'field' => 'case_study_detail_3f5be18822e8',
                    'tag' => 'div',
                    'class' => 'prose ',
                ],
                [
                    'type' => 'text',
                    'field' => 'case_study_detail_74dd8a1b256d',
                    'tag' => 'cite',
                    'class' => '',
                ],
            ],
        ];
        break;
}
?>
<section class="<?php echo esc_attr($args['class'] ?? 'section'); ?>">
    <div class="<?php echo esc_attr($args['container_class'] ?? 'container testimonial'); ?>">
        <?php if (!empty($args['illustration'])): ?>
        <img class="quote-illustration" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/' . $args['illustration']); ?>" alt="" aria-hidden="true">
        <?php endif; ?>
        <blockquote><?php get_template_part('template-parts/components/content-items', null, [
    'items' => $args['items'] ?? [],
]); ?></blockquote>
    </div>
</section>
