<?php
/** Fit imported service copy to the XD template's existing sections. */
defined('ABSPATH') || exit();

function ps_service_template_content($page)
{
    $page['groups'] = array_intersect_key($page['groups'], array_flip([
        'section_introduction', 'section_visibility', 'section_ppc_services', 'section_faqs',
    ]));
    $page['groups']['section_banner'] = ['image' => ''];
    $fixed = ['section_benefits', 'section_google_ads', 'section_closing_cta'];
    // The SEM source has a statistics section and testimonials here; use the XD copy.
    if ($page['path'] === 'services/search-engine-marketing') {
        $fixed = array_merge($fixed, ['section_visibility', 'section_ppc_services']);
    }
    // Pricing tables do not belong in the template's second image/text section.
    if ($page['path'] === 'services/website-maintenance') {
        unset($page['groups']['section_ppc_services']);
    }
    foreach (ps_section_groups()['search-engine-marketing'] as $section) {
        if (!in_array($section['name'], $fixed, true)) {
            continue;
        }
        $values = [];
        foreach ($section['fields'] as $key => $name) {
            $default = ps_default_content($key);
            // Empty images use the original XD asset through ps_image().
            $values[$name] = $name === 'image' ? '' : ($default['text'] ?? '');
            if ($name === 'button_url') {
                $values[$name] = ps_booking_url();
            }
        }
        $page['groups'][$section['name']] = $values;
    }
    foreach (['section_introduction', 'section_visibility', 'section_ppc_services'] as $name) {
        if (empty($page['groups'][$name]['description'])) {
            continue;
        }
        // Keep illustrations in the image field, not additional nested layout content.
        $description = $page['groups'][$name]['description'];
        // Source testimonial panels have no counterpart in these XD sections.
        $description = preg_replace('/<img\b[^>]*headshot[^>]*>.*$/is', '', $description);
        $description = preg_replace('/<blockquote\b[^>]*>.*?<\/blockquote>/is', '', $description);
        $description = preg_replace('/<img\b[^>]*>/i', '', $description);
        // These XD text sections do not have standalone CTA button slots.
        $description = preg_replace('/<p>\s*<a class="button[^"]*"[^>]*>.*?<\/a>\s*<\/p>/s', '', $description);
        if ($name === 'section_introduction' && preg_match('/^<h[2-6]>(.*?)<\/h[2-6]>\s*/s', $description, $match)) {
            $page['groups'][$name]['subtitle'] = wp_strip_all_tags($match[1]);
            $description = substr($description, strlen($match[0]));
        }
        $page['groups'][$name]['description'] = trim($description);
    }
    return $page;
}

/** One-time correction of existing imported pages, with the prior fields retained. */
function ps_update_service_template_content()
{
    if (!function_exists('update_field')) {
        return new WP_Error('acf_missing', 'Activate ACF before updating service content.');
    }
    $state = get_option('ps_service_import_v1', ['ids' => []]);
    foreach (ps_service_import_data()['pages'] as $page) {
        $id = $state['ids'][$page['path']] ?? 0;
        if (!$id || !get_post_meta($id, '_ps_live_service_source', true) || get_post_meta($id, '_ps_service_xd_template_v2', true)) {
            continue;
        }
        add_post_meta($id, '_ps_before_service_xd_template_v2', get_post_meta($id), true);
        $result = ps_save_service_fields($page, $id, $state['ids']);
        if (is_wp_error($result)) {
            return $result;
        }
        update_post_meta($id, '_ps_service_xd_template_v2', 1);
    }
    return true;
}
add_action('admin_init', function () {
    if (current_user_can('manage_options')) {
        ps_update_service_template_content();
    }
}, 60);
