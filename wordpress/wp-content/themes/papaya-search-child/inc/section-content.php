<?php
/** Value formatting and backward-compatible ACF reads; section markup lives in templates. */
defined('ABSPATH') || exit();

function ps_section_value($section, $name, $legacy_key)
{
    // Unformatted ACF Groups use field keys. Accept named arrays as well.
    foreach (['field_' . $legacy_key, $name] as $key) {
        if (is_array($section) && array_key_exists($key, $section)) {
            $value = $section[$key];
            if (get_post_type() === 'case_study' && $legacy_key === 'case_study_detail_afcd2640f663' && !$value) {
                return get_the_title();
            }
            return is_array($value) ? $value : (is_scalar($value) ? (string) $value : '');
        }
    }
    // Existing installations without the Group migration retain their saved content.
    if (
        str_contains($legacy_key, '_image_') ||
        str_ends_with($legacy_key, '_url') ||
        $legacy_key === 'home_hero_emblem'
    ) {
        return ps_value($legacy_key, '');
    }
    return ps_field_value($legacy_key);
}

function ps_section_text($value, $legacy_key, $tag, $class = '')
{
    if ($tag === 'div') {
        return wp_kses_post(ps_content_html($legacy_key, (string) $value));
    }
    $text = ps_inline_content($legacy_key, (string) $value);
    if ($tag === 'h1' && trim(wp_strip_all_tags($text)) === '') {
        $text = esc_html(ps_accessible_title());
    }
    if (str_contains($class, 'breadcrumb')) {
        if ($legacy_key === 'case_study_detail_dac02921ffd6') {
            $text = str_replace(
                'Case Studies',
                '<a href="' . esc_url(ps_route('case-studies')) . '">Case Studies</a>',
                $text,
            );
        }
        $text = preg_replace('/^Home/', '<a href="' . esc_url(ps_route('home')) . '">Home</a>', $text);
    }
    return $text;
}

function ps_section_button_label($value, $context)
{
    $label = html_entity_decode(
        wp_strip_all_tags(ps_inline_content('', (string) $value)),
        ENT_QUOTES,
        get_bloginfo('charset'),
    );
    if (!$context || !preg_match('/^(read|learn) more$/i', trim($label))) {
        return '';
    }
    $context = html_entity_decode($context, ENT_QUOTES, get_bloginfo('charset'));
    return ' aria-label="' . esc_attr(trim($label) . ' about ' . $context) . '"';
}
