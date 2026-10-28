<?php
/** Replace fixed FAQ pairs with an ordered ACF Pro repeater on service pages. */
defined('ABSPATH') || exit();

function ps_install_faq_repeater() {
    if (get_option('ps_faq_repeater_v1')) return true;
    if (!function_exists('acf_get_setting') || !acf_get_setting('pro') || !get_option('ps_section_groups_v1')) return false;
    $parent = acf_get_field('field_ps_section_search_engine_marketing_section_faqs');
    if (empty($parent['ID'])) return false;
    $repeater = acf_get_field('field_ps_faq_items');
    if (!$repeater) $repeater = acf_update_field([
        'key' => 'field_ps_faq_items', 'name' => 'items', 'label' => 'Questions and Answers',
        'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Add Question',
        'parent' => $parent['ID'], 'menu_order' => 1,
    ]);
    if (empty($repeater['ID'])) $repeater = acf_get_field('field_ps_faq_items');
    if (empty($repeater['ID'])) return false;
    foreach (['question' => 'text', 'answer' => 'wysiwyg'] as $name => $type) {
        $field = acf_get_field('field_ps_faq_item_' . $name);
        if (!$field) $field = acf_update_field([
            'key' => 'field_ps_faq_item_' . $name, 'name' => $name,
            'label' => ucfirst($name), 'type' => $type,
            'parent' => $repeater['ID'], 'menu_order' => $name === 'question' ? 0 : 1,
            'required' => $name === 'question' ? 1 : 0,
        ]);
        if (empty($field['ID']) && !acf_get_field('field_ps_faq_item_' . $name)) return false;
    }
    $map = ps_section_groups()['search-engine-marketing'];
    $faq_section = null;
    foreach ($map as $section) if ($section['name'] === 'section_faqs') $faq_section = $section;
    if (!$faq_section) return false;
    $names = array_flip($faq_section['fields']);
    $pages = get_posts(['post_type' => 'page', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids']);
    foreach ($pages as $post_id) {
        if (metadata_exists('post', $post_id, 'section_faqs_items')) continue;
        $rows = [];
        for ($number = 1; $number <= 9; $number++) {
            $question_name = $number === 1 ? 'question' : 'question_' . $number;
            $answer_name = $number === 1 ? 'answer' : 'answer_' . $number;
            $question = get_post_meta($post_id, 'section_faqs_' . $question_name, true);
            $answer = get_post_meta($post_id, 'section_faqs_' . $answer_name, true);
            if ($question === '' && isset($names[$question_name])) $question = get_post_meta($post_id, $names[$question_name], true);
            if ($answer === '' && isset($names[$answer_name])) $answer = get_post_meta($post_id, $names[$answer_name], true);
            if (trim((string) $question) !== '') $rows[] = ['question' => $question, 'answer' => $answer];
        }
        if (!$rows) continue;
        update_post_meta($post_id, 'section_faqs_items', count($rows));
        update_post_meta($post_id, '_section_faqs_items', 'field_ps_faq_items');
        foreach ($rows as $index => $row) {
            foreach ($row as $name => $value) {
                $meta = 'section_faqs_items_' . $index . '_' . $name;
                update_post_meta($post_id, $meta, wp_slash($value));
                update_post_meta($post_id, '_' . $meta, 'field_ps_faq_item_' . $name);
            }
        }
    }
    $archive = acf_get_field_group('group_ps_archived_faq_pairs');
    if (!$archive) $archive = acf_update_field_group(['key' => 'group_ps_archived_faq_pairs', 'title' => 'Archived Fixed FAQ Fields', 'active' => false, 'location' => []]);
    foreach ($faq_section['fields'] as $legacy => $name) {
        if (!preg_match('/^(question|answer)(?:_\d+)?$/', $name)) continue;
        $field = acf_get_field('field_' . $legacy);
        if ($field) { $field['parent'] = $archive['ID']; acf_update_field($field); }
    }
    update_option('ps_faq_repeater_v1', 1, false);
    return true;
}

add_action('init', 'ps_install_faq_repeater', 170);
