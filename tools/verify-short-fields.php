<?php
require '/wordpress/wp-load.php';
$id = get_option('ps_page_ids')['home'];
$key = 'home_1d791eb121c5';
$field = acf_get_field('field_' . $key);
$old = get_post_meta($id, $key, true);
$field['type'] = 'wysiwyg';
acf_update_field(wp_slash($field));
update_post_meta($id, $key, '<p>Readable heading</p>');
acf_delete_field('field_home_hero_emblem');
delete_post_meta($id, 'home_hero_emblem');
delete_option('ps_short_fields_emblem_v1');
$result = ps_upgrade_short_fields_and_emblem();
if (is_wp_error($result)) {
    throw new Exception($result->get_error_message());
}
if (
    acf_get_field('field_' . $key)['type'] !== 'text' ||
    get_post_meta($id, $key, true) !== 'Readable heading'
) {
    throw new Exception('Plain text migration failed');
}
$image = get_field('home_hero_emblem', $id, false);
if (!$image || !wp_get_attachment_url($image)) {
    throw new Exception('Emblem not in Media Library');
}
if (!acf_get_field('field_home_hero_emblem')['ID']) {
    throw new Exception('Emblem field missing');
}
update_post_meta($id, $key, $old);
echo json_encode([
    'shortFieldMigration' => true,
    'emblemDatabaseField' => true,
    'emblemMediaAttachment' => true,
]);
