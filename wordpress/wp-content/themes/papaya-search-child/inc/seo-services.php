<?php
/** Editable content for the SEO services section. */
defined('ABSPATH') || exit();

function ps_seo_services_defaults()
{
    return [
        'heading' => 'Our Search Engine Optimization Services',
        'description' => 'Effective SEO starts with understanding your business, your audience, and the opportunities within your website. Our services address the technical, content, and performance factors that help strengthen your organic search presence.',
        'heading_1' => 'SEO Audits & Technical SEO',
        'description_1' => 'We examine your website\'s technical health, crawlability, indexing, and overall SEO performance to identify issues and opportunities. From resolving technical barriers to prioritizing improvements, our audits provide a clear foundation for a stronger organic search strategy.',
        'heading_2' => 'On-Page SEO Optimization',
        'description_2' => 'We optimize page titles, headings, content, and internal linking to help search engines understand your website and visitors find relevant information. Every improvement is guided by search intent, usability, and your business goals.',
        'heading_3' => 'SEO Keyword Research',
        'description_3' => 'We research how your customers search, what questions they\'re asking, and which keywords represent meaningful opportunities. These insights help us prioritize relevant search terms and build strategies that connect your website with the right audience.',
        'heading_4' => 'SEO Content Strategy',
        'description_4' => 'We develop content strategies that address your audience\'s needs throughout their search journey. By identifying content gaps, strengthening existing pages, and planning valuable new resources, we help your website become a more useful destination for prospective customers.',
        'heading_5' => 'Link Building & Website Authority',
        'description_5' => 'We evaluate your website\'s linking structure and identify opportunities to strengthen its authority through relevant, high-quality links. Our approach emphasizes useful connections, sound internal linking, and sustainable practices rather than shortcuts that could put your search presence at risk.',
        'heading_6' => 'SEO Reporting & Analytics',
        'description_6' => 'We track organic search performance, identify meaningful trends, and translate data into clear, actionable recommendations. Through transparent reporting and ongoing analysis, you\'ll understand what\'s working, where opportunities remain, and how your SEO strategy is progressing.',
    ];
}

function ps_install_seo_services()
{
    if (get_option('ps_seo_services_v1')) {
        return true;
    }
    if (!function_exists('acf_update_field_group')) {
        return new WP_Error('acf_missing', 'ACF is required.');
    }
    $pages = get_posts(['post_type' => 'page', 'post_status' => 'any', 'name' => 'seo', 'numberposts' => -1]);
    if (!$pages) {
        return new WP_Error('seo_missing', 'The SEO page is not available yet.');
    }
    $locations = [];
    foreach ($pages as $page) {
        $locations[] = [['param' => 'page', 'operator' => '==', 'value' => (string) $page->ID]];
    }
    $group = acf_update_field_group(['key' => 'group_ps_seo_services', 'title' => 'SEO — Services', 'location' => $locations, 'active' => true]);
    $parent = acf_update_field(['key' => 'field_ps_seo_services', 'name' => 'section_seo_services', 'label' => 'SEO Services', 'type' => 'group', 'layout' => 'block', 'parent' => $group['ID']]);
    $position = 0;
    foreach (ps_seo_services_defaults() as $name => $value) {
        $key = 'field_ps_seo_services_' . $name;
        $field = acf_update_field(['key' => $key, 'name' => $name, 'label' => ucwords(str_replace('_', ' ', $name)), 'type' => str_starts_with($name, 'description') ? 'textarea' : 'text', 'rows' => 3, 'parent' => $parent['ID'], 'menu_order' => $position++]);
        if (empty($field['ID'])) {
            return new WP_Error('field_missing', 'Could not create SEO services fields.');
        }
        foreach ($pages as $page) {
            $meta = 'section_seo_services_' . $name;
            if (!metadata_exists('post', $page->ID, $meta)) {
                update_post_meta($page->ID, $meta, $value);
                update_post_meta($page->ID, '_' . $meta, $key);
            }
        }
    }
    update_option('ps_seo_services_v1', 1, false);
    return true;
}

add_action('admin_init', function () {
    if (current_user_can('manage_options')) {
        ps_install_seo_services();
    }
}, 86);
