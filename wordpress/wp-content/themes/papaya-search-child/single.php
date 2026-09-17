<?php
/** All native posts share the Blog Detail design, with each post's own content. */
defined('ABSPATH') || exit();
get_header();

while (have_posts()):
    the_post();
    ?>
    <main tabindex="-1" id="main-content" class="page-blog-detail">
        <?php get_template_part('template-parts/sections/article-heading'); ?>
        <?php get_template_part('template-parts/sections/article-image'); ?>
        <?php get_template_part('template-parts/sections/article-body'); ?>
        <?php get_template_part('template-parts/sections/related-posts'); ?>
    </main>
    <?php
endwhile;

get_footer();
