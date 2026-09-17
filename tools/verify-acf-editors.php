<?php
require '/wordpress/wp-load.php';
$groups = json_decode(
    file_get_contents(get_stylesheet_directory() . '/acf-import/field-groups.json'),
    true,
);
$ids = get_option('ps_page_ids');
$before = [];
foreach (array_merge(array_values($ids), [ps_shared_id()]) as $id) {
    $before[$id] = get_post_meta($id);
}
// Simulate the existing database groups, with their original textareas and old ordering.
foreach ($groups as $group) {
    $fields = acf_get_fields(acf_get_field_group($group['key']));
    foreach ($fields as $i => $field) {
        $field['menu_order'] = count($fields) - $i;
        if ($field['type'] === 'wysiwyg') {
            $field['type'] = 'textarea';
        }
        acf_update_field(wp_slash($field));
    }
}
delete_option('ps_acf_editor_order_v2');
$result = ps_upgrade_acf_editors();
if (is_wp_error($result)) {
    throw new Exception($result->get_error_message());
}
$wysiwyg = 0;
$checked = 0;
foreach ($groups as $group) {
    $actual = acf_get_fields(acf_get_field_group($group['key']));
    foreach ($group['fields'] as $i => $expected) {
        $field = $actual[$i];
        if (
            $field['name'] !== $expected['name'] ||
            $field['type'] !== $expected['type'] ||
            $field['label'] !== $expected['label']
        ) {
            throw new Exception('Field order/type mismatch: ' . $expected['name']);
        }
        if ($field['type'] === 'wysiwyg') {
            $wysiwyg++;
        }
        $checked++;
    }
}
foreach ($before as $id => $meta) {
    if (get_post_meta($id) !== $meta) {
        throw new Exception('Saved page values changed');
    }
}
$id = $ids['home'];
$heading = 'home_1d791eb121c5';
$body = 'home_ad1e50936d54';
$button = 'home_6901adf0ab1d';
$old = [];
foreach ([$heading, $body, $button] as $key) {
    $old[$key] = get_field($key, $id, false);
}
try {
    update_field('field_' . $heading, '<p>Heading <em>emphasis</em></p>', $id);
    update_field(
        'field_' . $body,
        '<p style="text-align: center;"><strong>Bold copy</strong> and <a href="https://example.com/">a link</a>.</p><ul><li>List item</li></ul>',
        $id,
    );
    update_field('field_' . $button, '<p><strong>Call now</strong></p>', $id);
    $GLOBALS['post'] = get_post($id);
    setup_postdata($GLOBALS['post']);
    ob_start();
    ps_text($heading, 'h1');
    ps_text($body, 'div');
    ps_button($button, 'tel:+14044259775');
    $html = ob_get_clean();
    foreach (
        [
            '<em>emphasis</em>',
            '<strong>Bold copy</strong>',
            '<ul>',
            '<li>List item</li>',
            'rich-align-center',
            '<strong>Call now</strong>',
        ]
        as $expected
    ) {
        if (!str_contains($html, $expected)) {
            throw new Exception('Rich formatting missing: ' . $expected);
        }
    }
    if (
        str_contains($html, 'style=') ||
        str_contains($html, '&lt;p&gt;') ||
        preg_match('/<h1[^>]*>\s*<p/', $html)
    ) {
        throw new Exception('Invalid rich-text markup');
    }
} finally {
    foreach ($old as $key => $value) {
        update_field('field_' . $key, $value, $id);
    }
    wp_reset_postdata();
}
$field = acf_get_field('field_' . $heading);
$original = $field;
try {
    $field['menu_order'] = 999;
    $field['toolbar'] = 'basic';
    acf_update_field(wp_slash($field));
    ps_upgrade_acf_editors();
    $saved = acf_get_field($field['key']);
    if ($saved['menu_order'] !== 999 || $saved['toolbar'] !== 'basic') {
        throw new Exception('Subsequent dashboard edit overwritten');
    }
} finally {
    acf_update_field(wp_slash($original));
}
echo json_encode([
    'orderedFields' => $checked,
    'wysiwygFields' => $wysiwyg,
    'groups' => count($groups),
    'existingContentPreserved' => true,
    'richTextRendering' => true,
    'laterEditorChangesPreserved' => true,
]);
