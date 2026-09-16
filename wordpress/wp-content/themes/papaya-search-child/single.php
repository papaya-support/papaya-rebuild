<?php
/** All native posts share the Blog Detail design, with each post's own content. */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main tabindex="-1" id="main-content" class="page-blog-detail">
<?php get_template_part('template-parts/sections/article-heading'); ?>
<section class="section-blog-detail-featured-image section section-tight">
<div class="container"><?php ps_post_image('article-featured', true); ?></div>
</section>
<section class="section-blog-detail-article-body section">
<div class="container article-body prose"><?php the_content(); ?></div>
</section>
<?php get_template_part('template-parts/sections/related-posts'); ?>
</main>
<?php endwhile;
get_footer();
