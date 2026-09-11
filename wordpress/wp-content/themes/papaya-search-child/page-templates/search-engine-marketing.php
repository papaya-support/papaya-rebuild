<?php
/**
 * Template Name: Papaya — Services-Individual
 * Template Post Type: page
 */
defined('ABSPATH') || exit;
get_header();
while (have_posts()) {
    the_post();
    ps_render_page('search-engine-marketing');
}
get_footer();
