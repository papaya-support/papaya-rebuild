<?php
/**
 * Template Name: Papaya — Blog Detail
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) {
    the_post();
    ps_render_page('blog-detail');
}
get_footer();
