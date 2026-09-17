<?php
/** Blog filters and post grid share one query and pagination context. */
defined('ABSPATH') || exit();

$listing = ps_blog_listing();

get_template_part('template-parts/sections/blog-filters', null, ['listing' => $listing]);
get_template_part('template-parts/sections/posts', null, ['listing' => $listing]);
