<?php
/** Reusable native WordPress post listing. Accepts a WP_Query and pagination context. */
defined('ABSPATH') || exit();
$listing = $args['listing'];
$blog_posts_query = $listing['query'];
?>
<section class="section-blog-cards section section-tight">
    <div class="container grid grid-three post-grid" data-blog-grid>
        <?php while ($blog_posts_query->have_posts()): ?>
            <?php $blog_posts_query->the_post(); ?>
            <?php get_template_part('template-parts/components/post-card'); ?>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>

        <?php if (!$blog_posts_query->post_count): ?>
            <p class="blog-empty">
                <?php esc_html_e(
                    'No posts found. Please choose another category or check back soon.',
                    'papaya-search-child',
                ); ?>
            </p>
        <?php endif; ?>
    </div>
</section>
<section class="section-blog-more section compact-section">
    <div class="container center">
        <?php if ($listing['page'] < $blog_posts_query->max_num_pages): ?>
            <?php
            $next_url = add_query_arg('blog_page', $listing['page'] + 1, $listing['url']);
            if ($listing['slug']) {
                $next_url = add_query_arg('blog_category', $listing['slug'], $next_url);
            }
            ?>
            <a class="button" data-view-more rel="next" href="<?php echo esc_url($next_url); ?>">View More</a>
        <?php endif; ?>
        <p class="blog-status" role="status" aria-live="polite"></p>
    </div>
</section>
