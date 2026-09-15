<?php
defined('ABSPATH') || exit;
$listing = $args['listing'];
$categories = get_categories(['hide_empty'=>true, 'orderby'=>'name', 'order'=>'ASC']); ?>
<section class="section-blog-filters section compact-section">
<nav class="container filter-bar" aria-label="Filter posts by category">
<a href="<?php echo esc_url($listing['url']); ?>"<?php if (!$listing['slug']) : ?> aria-current="true"<?php endif; ?>><?php esc_html_e('All', 'papaya-search-child'); ?></a>
<?php foreach ($categories as $category) : ?>
<a href="<?php echo esc_url(add_query_arg('blog_category', $category->slug, $listing['url'])); ?>"<?php if ($listing['slug']===$category->slug) : ?> aria-current="true"<?php endif; ?>><?php echo esc_html($category->name); ?></a>
<?php endforeach; ?>
</nav>
</section>
