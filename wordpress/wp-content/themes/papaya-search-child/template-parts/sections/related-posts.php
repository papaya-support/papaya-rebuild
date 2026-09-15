<?php
/** Three related native Posts, preferring the current article's categories. */
defined('ABSPATH') || exit;
$current_article_id = get_the_ID();
$related_args = ['post_type'=>'post','post_status'=>'publish','posts_per_page'=>3,'post__not_in'=>[$current_article_id],'fields'=>'ids','orderby'=>['date'=>'DESC','ID'=>'DESC']];
$category_ids = wp_get_post_categories($current_article_id);
if ($category_ids) {$related_args['category__in']=$category_ids;}
$related_ids = get_posts($related_args);
if (count($related_ids)<3) {
    unset($related_args['category__in']);
    $related_args['post__not_in']=array_merge([$current_article_id], $related_ids);
    $related_args['posts_per_page']=3-count($related_ids);
    $related_ids=array_merge($related_ids,get_posts($related_args));
}
if (!$related_ids) {return;}
$related_query = new WP_Query(['post_type'=>'post','post_status'=>'publish','post__in'=>$related_ids,'orderby'=>'post__in','posts_per_page'=>3,'ignore_sticky_posts'=>true,'no_found_rows'=>true]); ?>
<section class="section-blog-detail-related-posts section related-posts">
<div class="container">
<h2 class="section-title">Related Posts</h2>
<div class="grid grid-three">
<?php while ($related_query->have_posts()) : $related_query->the_post();
get_template_part('template-parts/components/post-card', null, ['heading'=>'h3','compact'=>true]);
endwhile; wp_reset_postdata(); ?>
</div>
</div>
</section>
