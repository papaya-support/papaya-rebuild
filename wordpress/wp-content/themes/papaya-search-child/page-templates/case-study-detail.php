<?php
/**
 * Template Name: Papaya — Case Study Detail
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) {
    the_post();
    ps_render_page('case-study-detail');
}
get_footer();
