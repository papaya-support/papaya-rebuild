<?php
/**
 * Template Name: Papaya — Services-Landing
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) {
    the_post();
    ps_render_page('services');
}
get_footer();
