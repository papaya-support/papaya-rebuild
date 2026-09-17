<?php defined('ABSPATH') || exit();
$article_categories = get_the_category();
?>
<section class="section-blog-detail-heading section">
    <div class="container article-heading">
        <p class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a> &gt; Papaya Search &gt; <a href="<?php echo esc_url(ps_route('blog')); ?>">Blog</a><?php if ($article_categories): ?> &gt; <a href="<?php echo esc_url(add_query_arg('blog_category', $article_categories[0]->slug, ps_route('blog'))); ?>"><?php echo esc_html($article_categories[0]->name); ?></a><?php endif; ?></p>
        <h1><?php echo esc_html(ps_accessible_title()); ?></h1>
    </div>
</section>
