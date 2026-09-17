<?php
/** All published Case Studies, using the shared native post card. */
defined('ABSPATH') || exit();
$case_studies = new WP_Query([
    'post_type' => 'case_study',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => ['date' => 'DESC', 'ID' => 'DESC'],
    'no_found_rows' => true,
]);
?>
<section class="section-case-studies-cards section section-tight">
    <div class="container grid grid-three post-grid">
        <?php while ($case_studies->have_posts()): ?>
            <?php $case_studies->the_post(); ?>
            <?php get_template_part('template-parts/components/post-card'); ?>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        <?php if (!$case_studies->post_count): ?>
            <p>No case studies are available yet. Please check back soon.</p>
        <?php endif; ?>
    </div>
</section>
