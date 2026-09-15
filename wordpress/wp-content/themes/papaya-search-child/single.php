<?php
/** Native WordPress articles linked from the dynamic Blog listing. */
defined('ABSPATH') || exit;
get_header(); ?>
<main id="main-content" class="standard-content">
<?php while (have_posts()) : the_post(); ?>
<article class="article-body">
<h1><?php the_title(); ?></h1>
<div class="post-categories"><?php the_category(', '); ?></div>
<?php ps_post_image('article-featured'); ?>
<div class="prose"><?php the_content(); ?></div>
</article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
