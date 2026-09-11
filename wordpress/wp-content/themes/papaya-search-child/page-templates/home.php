<?php
/**
 * Template Name: Papaya — Home
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) {
    the_post();
    ps_render_page('home');
}
get_footer();
