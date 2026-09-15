<?php
/** Reusable native WordPress post listing. Accepts a WP_Query and pagination context. */
defined('ABSPATH') || exit;
$listing=$args['listing'];$blog_posts_query=$listing['query']; ?>
<section class="section-blog-cards section section-tight">
<div class="container grid grid-three post-grid">
<?php while ($blog_posts_query->have_posts()) : $blog_posts_query->the_post(); ?>
<article class="post-card" data-post-id="<?php the_ID(); ?>">
<?php if (has_post_thumbnail()) : ?>
<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail('large', ['loading'=>'lazy']); ?></a>
<?php endif; ?>
<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<div class="post-categories"><?php the_category(', '); ?></div>
<div class="prose"><?php echo wp_kses_post(wpautop(get_the_excerpt())); ?></div>
</article>
<?php endwhile; wp_reset_postdata(); ?>
<?php if (!$blog_posts_query->post_count) : ?><p class="blog-empty"><?php esc_html_e('No posts found. Please choose another category or check back soon.', 'papaya-search-child'); ?></p><?php endif; ?>
</div>
</section>
<?php if ($blog_posts_query->max_num_pages > 1) : ?>
<nav class="container blog-pagination" aria-label="Blog pagination">
<?php
$base=$listing['url'];
if ($listing['slug']) {$base=add_query_arg('blog_category',$listing['slug'],$base);}
echo wp_kses_post(paginate_links(['base'=>add_query_arg('blog_page','%#%',$base),'format'=>'','current'=>$listing['page'],'total'=>$blog_posts_query->max_num_pages,'prev_text'=>'Previous','next_text'=>'Next','type'=>'list']));
?>
</nav>
<?php endif; ?>
