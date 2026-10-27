<?php
/** Shared closing CTA image, editable using the section image field. */
defined('ABSPATH') || exit();
$image = get_post_meta(get_the_ID(), 'section_closing_cta_image', true);
$url = is_numeric($image) ? wp_get_attachment_url((int) $image) : '';
if (!$url && !metadata_exists('post', get_the_ID(), 'section_closing_cta_image')) {
    $url = get_stylesheet_directory_uri() . '/assets/images/papaya-team.png';
}
if (!$url) return;
?>
<div class="cta-team-media"><img src="<?php echo esc_url($url); ?>" alt="Papaya Search team" width="1559" height="1559" loading="lazy" decoding="async"></div>
