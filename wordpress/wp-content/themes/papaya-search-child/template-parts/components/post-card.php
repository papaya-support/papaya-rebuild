<?php
/** Native post card shared by Blog, Case Studies and related articles. */
defined('ABSPATH') || exit();
$is_case_study = get_post_type() === 'case_study';
$card_title = $is_case_study
    ? ps_plain_field('case_study_detail_afcd2640f663')
    : ps_accessible_title();
$heading_tag = ($args['heading'] ?? 'h2') === 'h3' ? 'h3' : 'h2';
?>
<article class="post-card" data-post-id="<?php the_ID(); ?>">
    <a href="<?php the_permalink(); ?>" tabindex="-1"
        aria-hidden="true">
        <?php if ($is_case_study) {
            ps_image('case_study_detail_image_cf7d13020e');
        } else {
            ps_post_image();
        } ?>
    </a>
    <<?php echo $heading_tag; ?>><a href="<?php the_permalink(); ?>"><?php echo esc_html($card_title); ?></a></<?php echo $heading_tag; ?>>
    <?php if (empty($args['compact'])): ?>
        <?php if (!$is_case_study): ?>
            <div class="post-categories"><?php the_category(', '); ?></div>
        <?php endif; ?>
        <div class="prose"><?php echo wp_kses_post(wpautop(get_the_excerpt())); ?></div>
    <?php endif; ?>
</article>
