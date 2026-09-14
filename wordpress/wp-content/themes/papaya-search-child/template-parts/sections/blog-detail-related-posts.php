<?php defined('ABSPATH') || exit; ?>
<section class="section-blog-detail-related-posts section related-posts">
<div class="container">
<?php /* ACF: Related Posts — Heading */ ps_text('blog_detail_1624e4d9861e', 'h2', 'section-title'); ?>
<div class="grid grid-three">
<article class="post-card"><?php /* ACF: Related Post 1 — Image */ ps_image('blog_detail_image_e66cb91b30', '', false); ?>
<h3><a data-field="blog_detail_4d181476ee16" href="<?php echo esc_url(ps_value('blog_detail_4d181476ee16_url','') ?: ps_route('blog-detail')); ?>"><?php echo ps_inline_content('blog_detail_4d181476ee16'); ?>
</a></h3></article>
<article class="post-card"><?php /* ACF: Related Post 2 — Image */ ps_image('blog_detail_image_e850bb0d10', '', false); ?>
<h3><a data-field="blog_detail_8c5ed5ef437b" href="<?php echo esc_url(ps_value('blog_detail_8c5ed5ef437b_url','') ?: ps_route('blog-detail')); ?>"><?php echo ps_inline_content('blog_detail_8c5ed5ef437b'); ?>
</a></h3></article>
<article class="post-card"><?php /* ACF: Related Post 3 — Image */ ps_image('blog_detail_image_e6018f7e64', '', false); ?>
<h3><a data-field="blog_detail_1fab6576ba8d" href="<?php echo esc_url(ps_value('blog_detail_1fab6576ba8d_url','') ?: ps_route('blog-detail')); ?>"><?php echo ps_inline_content('blog_detail_1fab6576ba8d'); ?>
</a></h3></article>
</div>
</div>
</section>
