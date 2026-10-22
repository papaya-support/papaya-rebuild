<?php
/** Section content and layout are edited together here. */
defined('ABSPATH') || exit();
$section_article_body = function_exists('get_field') ? (get_field('section_article_body', get_the_ID(), false) ?: []) : [];
$description = ps_section_value($section_article_body, 'description', 'blog_detail_3ec80043fb9f');
$heading = ps_section_value($section_article_body, 'heading', 'blog_detail_15daca854069');
$description_2 = ps_section_value($section_article_body, 'description_2', 'blog_detail_4fac5cef0a97');
$heading_2 = ps_section_value($section_article_body, 'heading_2', 'blog_detail_8c8e30bb3e63');
$description_3 = ps_section_value($section_article_body, 'description_3', 'blog_detail_16e7a84007ce');
$description_4 = ps_section_value($section_article_body, 'description_4', 'blog_detail_44d1e412731f');
?>
<section class="section-blog-detail-article-body section">
    <div class="container article-body">
        <?php if ($description !== ''): ?>
            <div class="prose " data-field="blog_detail_3ec80043fb9f"><?php echo ps_section_text($description, 'blog_detail_3ec80043fb9f', 'div', 'prose '); ?></div>
        <?php endif; ?>
        <?php if ($heading !== ''): ?>
            <h2 class="" data-field="blog_detail_15daca854069"><?php echo ps_section_text($heading, 'blog_detail_15daca854069', 'h2', ''); ?></h2>
        <?php endif; ?>
        <?php if ($description_2 !== ''): ?>
            <div class="prose " data-field="blog_detail_4fac5cef0a97"><?php echo ps_section_text($description_2, 'blog_detail_4fac5cef0a97', 'div', 'prose '); ?></div>
        <?php endif; ?>
        <?php if ($heading_2 !== ''): ?>
            <h2 class="" data-field="blog_detail_8c8e30bb3e63"><?php echo ps_section_text($heading_2, 'blog_detail_8c8e30bb3e63', 'h2', ''); ?></h2>
        <?php endif; ?>
        <?php if ($description_3 !== ''): ?>
            <div class="prose " data-field="blog_detail_16e7a84007ce"><?php echo ps_section_text($description_3, 'blog_detail_16e7a84007ce', 'div', 'prose '); ?></div>
        <?php endif; ?>
        <div class="tip">
            <?php if ($description_4 !== ''): ?>
                <div class="prose " data-field="blog_detail_44d1e412731f"><?php echo ps_section_text($description_4, 'blog_detail_44d1e412731f', 'div', 'prose '); ?></div>
            <?php endif; ?>
        </div>
    </div>
</section>
