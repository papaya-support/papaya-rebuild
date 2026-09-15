<?php
/** Ordered ACF text, links and media within a section. Values stay in the page's ACF fields. */
defined('ABSPATH') || exit;
foreach ($args['items'] ?? [] as $item) {
    switch ($item['type']) {
        case 'text':
            ps_text($item['field'], $item['tag'] ?? 'div', $item['class'] ?? '');
            break;
        case 'button':
            ps_button($item['field'], $item['url'] ?? ps_booking_url(), $item['class'] ?? '');
            break;
        case 'image':
            ps_image($item['field'], $item['class'] ?? '', $item['eager'] ?? false);
            break;
        case 'illustration': ?>
            <img class="<?php echo esc_attr($item['class'] ?? ''); ?>" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/' . $item['asset']); ?>" alt="" aria-hidden="true">
            <?php break;
    }
}
