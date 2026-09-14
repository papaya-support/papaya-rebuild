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
