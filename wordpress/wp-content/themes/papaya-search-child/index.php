<?php
/** Accessible fallback for pages, search, archives and missing URLs. */
defined('ABSPATH') || exit;
get_header(); ?>
<main class="container section standard-content" id="main-content" tabindex="-1">
<?php if (is_singular() && have_posts()) : while (have_posts()) : the_post(); ?>
<article><h1><?php echo esc_html(ps_accessible_title()); ?></h1><div class="prose"><?php the_content(); wp_link_pages(); ?></div></article>
<?php endwhile; else : ?>
<h1><?php
if (is_404()) {esc_html_e('Page not found', 'papaya-search');}
elseif (is_search()) {echo esc_html(sprintf(__('Search results for: %s', 'papaya-search'), get_search_query()));}
elseif (is_archive()) {echo esc_html(wp_strip_all_tags(get_the_archive_title()));}
else {esc_html_e('Blog', 'papaya-search');}
?></h1>
<?php if (have_posts()) : ?>
<div class="grid grid-three post-grid">
<?php while (have_posts()) : the_post(); get_template_part('template-parts/components/post-card'); endwhile; ?>
</div>
<?php the_posts_navigation(); else : ?>
<p><?php esc_html_e('No matching content was found.', 'papaya-search'); ?> <a href="<?php echo esc_url(home_url('/')); ?>">Return to the home page</a></p>
<?php endif; endif; ?>
</main>
<?php get_footer(); ?>
