<?php
/** Native post image in the Blog Detail layout. */
defined('ABSPATH') || exit();
?>
<section class="section-blog-detail-featured-image section section-tight">
    <div class="container"><?php ps_post_image('article-featured', true); ?></div>
</section>
