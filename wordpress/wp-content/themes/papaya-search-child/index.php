<?php
/** Standard fallback for WordPress content without an XD template. */
get_header(); ?>
<main class="standard-content" id="main-content">
<a href="<?php echo esc_url(home_url('/')); ?>">Papaya Search</a>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article><h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1><?php the_content(); ?></article>
<?php endwhile; the_posts_navigation(); else : ?><h1>Page not found</h1><p><a href="<?php echo esc_url(home_url('/')); ?>">Return to the home page</a></p><?php endif; ?>
</main>
<?php get_footer(); ?>
