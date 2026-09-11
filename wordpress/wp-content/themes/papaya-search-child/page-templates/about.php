<?php
/**
 * Template Name: Papaya — About
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) {
    the_post();
    ps_render_page('about');
}
get_footer();
