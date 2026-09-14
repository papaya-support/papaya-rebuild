<?php
defined('ABSPATH') || exit;
add_action('init', function () {
    register_post_type('ps_site_content', ['label'=>'Site Content','public'=>false,'show_ui'=>true,'show_in_menu'=>true,'menu_icon'=>'dashicons-admin-site-alt3','supports'=>['title'],'capability_type'=>'page','map_meta_cap'=>true]);
});
/**
 * One-time installation of real ACF field-group/field posts.
 * Field definitions are subsequently managed in ACF's editor, not registered in PHP.
 * The import lives outside acf-json, so it cannot override dashboard edits.
 */
function ps_install_acf_groups() {
    if (get_option('ps_acf_database_groups_v1')) {return true;}
    if (!function_exists('acf_import_field_group')) {return new WP_Error('acf_missing','Activate Advanced Custom Fields first.');}
    if (!did_action('acf/init')) {acf_init();}
    $groups=json_decode(file_get_contents(__DIR__.'/../acf-import/field-groups.json'),true);
    if (!is_array($groups)) {return new WP_Error('acf_import_invalid','The ACF field group import could not be read.');}
    foreach ($groups as $group) {
        // Preserve existing groups, including disabled or trashed groups, by key.
        $existing=get_posts(['post_type'=>'acf-field-group','post_status'=>['publish','draft','private','trash'],'name'=>$group['key'],'posts_per_page'=>1,'fields'=>'ids']);
        if ($existing) {continue;}
        $result=acf_import_field_group($group);
        if (is_wp_error($result) || empty($result['ID'])) {return new WP_Error('acf_import_failed','Could not import '.$group['title'].'.');}
    }
    update_option('ps_acf_database_groups_v1',1);
    return true;
}
add_action('admin_init',function(){
    if (!current_user_can('manage_options')) {return;}
    $result=ps_install_acf_groups();
    if (is_wp_error($result)) {
        add_action('admin_notices',function() use ($result) {echo '<div class="notice notice-error"><p>'.esc_html($result->get_error_message()).'</p></div>';});
    }
},5);

// Database groups are authoritative; disable theme-local JSON shadowing for them.
add_filter('acf/json/save_paths',function($paths,$post){
    return str_starts_with($post['key']??'', 'group_ps_') ? [] : $paths;
},10,2);
add_filter('acf/json/load_paths',function($paths){
    return array_values(array_filter($paths,fn($path)=>untrailingslashit($path)!==get_stylesheet_directory().'/acf-json'));
});

/** Update existing database fields once; never reset later editor customizations. */
function ps_upgrade_acf_editors() {
    if(get_option('ps_acf_editor_order_v2')) {return true;}
    if(!function_exists('acf_update_field')) {return new WP_Error('acf_missing','Activate Advanced Custom Fields first.');}
    if(!did_action('acf/init')) {acf_init();}
    $orders=require __DIR__.'/acf-editor-order.php';
    $labels=require __DIR__.'/acf-editor-labels.php';
    $backup=[];$updates=[];
    foreach($orders as $group_key=>$names) {
        $group=acf_get_field_group($group_key);
        if(!$group || empty($group['ID'])) {return new WP_Error('acf_group_missing','Missing ACF group: '.$group_key);}
        $fields=acf_get_fields($group) ?: [];
        $by_name=[];
        foreach($fields as $field) {$by_name[$field['name']]=$field;}
        // Keep custom fields that were added through the dashboard, after known page fields.
        $names=array_values(array_unique(array_merge($names,array_keys($by_name))));
        foreach($names as $position=>$name) {
            if(!isset($by_name[$name])) {continue;}
            $field=$by_name[$name];$backup[$field['key']]=$field;
            $field['menu_order']=$position;
            if(isset($labels[$name]) && $field['label']===$labels[$name]['old']) {$field['label']=$labels[$name]['new'];}
            if($field['type']==='textarea') {
                $field['type']='wysiwyg';$field['tabs']='all';$field['toolbar']='full';$field['media_upload']=0;$field['delay']=1;
                unset($field['rows'],$field['new_lines'],$field['maxlength']);
                $field['instructions']='Edit this content with the visual editor. Headings and button labels support inline formatting; descriptions support paragraphs, lists, and links.';
            }
            $updates[]=$field;
        }
    }
    add_option('ps_acf_editor_upgrade_backup_v2',$backup,'',false);
    foreach($updates as $field) {if(!acf_update_field(wp_slash($field))) {return new WP_Error('acf_field_update_failed','Could not update '.$field['name']);}}
    update_option('ps_acf_editor_order_v2',1,false);
    return true;
}
add_action('admin_init',function(){
    if(!current_user_can('manage_options')) {return;}
    $result=ps_upgrade_acf_editors();
    if(is_wp_error($result)) {add_action('admin_notices',function() use($result){echo '<div class="notice notice-error"><p>'.esc_html($result->get_error_message()).'</p></div>';});}
},6);

/** Remove the former per-page alt editors; Media Library metadata is authoritative. */
function ps_remove_acf_image_alt_fields() {
    if(get_option('ps_media_library_alt_v1')) {return true;}
    if(!function_exists('acf_delete_field')) {return new WP_Error('acf_missing','Activate Advanced Custom Fields first.');}
    $fields=[];
    foreach(ps_initial_content() as $page) {
        foreach($page['images'] as $image) {
            $field=acf_get_field('field_'.$image['key'].'_alt');
            if($field && !empty($field['ID'])) {$fields[$field['key']]=$field;}
        }
    }
    add_option('ps_removed_image_alt_definitions_v1',$fields,'',false);
    foreach($fields as $field) {
        if(!acf_delete_field($field['ID'])) {return new WP_Error('acf_alt_removal_failed','Could not remove '.$field['label']);}
    }
    // Old page metadata is left untouched, but is no longer read or exposed as a field.
    update_option('ps_media_library_alt_v1',1,false);
    return true;
}
add_action('admin_init',function(){
    if(!current_user_can('manage_options')) {return;}
    $result=ps_remove_acf_image_alt_fields();
    if(is_wp_error($result)) {add_action('admin_notices',function() use($result){echo '<div class="notice notice-error"><p>'.esc_html($result->get_error_message()).'</p></div>';});}
},7);
