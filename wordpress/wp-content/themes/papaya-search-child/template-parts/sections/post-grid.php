<?php
/** Shared post-grid sections: read ACF groups here and keep their markup together. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
?>
<?php if ($variant === 'blog-detail-related-posts'):
    $section_related_posts = function_exists('get_field') ? (get_field('section_related_posts', get_the_ID(), false) ?: []) : [];
    $description = ps_section_value($section_related_posts, 'description', 'blog_detail_4d181476ee16');
    $button_url = ps_section_value($section_related_posts, 'button_url', 'blog_detail_4d181476ee16_url');
    $image = ps_section_value($section_related_posts, 'image', 'blog_detail_image_e66cb91b30');
    $description_2 = ps_section_value($section_related_posts, 'description_2', 'blog_detail_8c5ed5ef437b');
    $button_url_2 = ps_section_value($section_related_posts, 'button_url_2', 'blog_detail_8c5ed5ef437b_url');
    $image_2 = ps_section_value($section_related_posts, 'image_2', 'blog_detail_image_e850bb0d10');
    $description_3 = ps_section_value($section_related_posts, 'description_3', 'blog_detail_1fab6576ba8d');
    $button_url_3 = ps_section_value($section_related_posts, 'button_url_3', 'blog_detail_1fab6576ba8d_url');
    $image_3 = ps_section_value($section_related_posts, 'image_3', 'blog_detail_image_e6018f7e64');
    $heading = ps_section_value($section_related_posts, 'heading', 'blog_detail_1624e4d9861e');
    ?>
    <!-- Blog Detail Related Posts -->
    <section class="section-blog-detail-related-posts section related-posts">
        <div class="container">
            <?php if ($heading !== ''): ?>
                <h2 class="section-title" data-field="blog_detail_1624e4d9861e"><?php echo ps_section_text($heading, 'blog_detail_1624e4d9861e', 'h2', 'section-title'); ?></h2>
            <?php endif; ?>
            <div class="grid grid-three">
                <article class="post-card">
                    <?php ps_image('blog_detail_image_e66cb91b30', '', false, $image); ?>
                    <h3><a data-field="blog_detail_4d181476ee16" href="<?php echo esc_url($button_url ?: ps_route('blog-detail')); ?>"><?php echo ps_inline_content('blog_detail_4d181476ee16', $description); ?></a></h3>

                </article>
                <article class="post-card">
                    <?php ps_image('blog_detail_image_e850bb0d10', '', false, $image_2); ?>
                    <h3><a data-field="blog_detail_8c5ed5ef437b" href="<?php echo esc_url($button_url_2 ?: ps_route('blog-detail')); ?>"><?php echo ps_inline_content('blog_detail_8c5ed5ef437b', $description_2); ?></a></h3>

                </article>
                <article class="post-card">
                    <?php ps_image('blog_detail_image_e6018f7e64', '', false, $image_3); ?>
                    <h3><a data-field="blog_detail_1fab6576ba8d" href="<?php echo esc_url($button_url_3 ?: ps_route('blog-detail')); ?>"><?php echo ps_inline_content('blog_detail_1fab6576ba8d', $description_3); ?></a></h3>

                </article>
            </div>
        </div>
    </section>
<?php endif; ?>
