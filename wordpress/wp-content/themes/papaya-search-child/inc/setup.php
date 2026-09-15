<?php
defined('ABSPATH') || exit;
/** Idempotent installer: creates missing pages, never overwrites existing content. */
function ps_import_design_content() {
    if (!function_exists('update_field')) { return new WP_Error('acf_missing', 'Activate Advanced Custom Fields before importing the design.'); }
    $fields_result=ps_install_acf_groups();
    if (is_wp_error($fields_result)) {return $fields_result;}
    $shared_id=ps_shared_id();
    if(!$shared_id || !get_post($shared_id)) {
        $shared_id=wp_insert_post(['post_type'=>'ps_site_content','post_status'=>'publish','post_title'=>'Header, Footer & Links'],true);
        if(is_wp_error($shared_id)){return $shared_id;}update_option('ps_shared_content_id',$shared_id);
    }
    $ids=get_option('ps_page_ids',[]);
    require_once ABSPATH.'wp-admin/includes/image.php';
    foreach(ps_pages() as $page) {
        $slug=$page['slug'];$id=$ids[$slug]??0;
        if(!$id || !get_post($id)) {
            $existing=get_page_by_path($slug);
            if($existing) {$id=$existing->ID;} else {
                $id=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>$page['name'],'post_name'=>$slug],true);
                if(is_wp_error($id)){return $id;}
            }
            $ids[$slug]=$id;
            update_post_meta($id,'_wp_page_template','page-templates/'.$slug.'.php');
        }
        $design=ps_initial_content($slug);
        foreach($design['texts'] as $t) {
            if (ps_menu_field($t) || ps_blog_legacy_field($t['key'])) {continue;}
            $target=$t['scope']==='page'?$id:$shared_id;
            if(!metadata_exists('post',$target,$t['key'])) {update_field('field_'.$t['key'],$t['text'],$target);}
        }
        foreach($design['images'] as $im) {
            if (ps_blog_legacy_field($im['key'])) {continue;}
            if(metadata_exists('post',$id,$im['key'])) {continue;}
            $asset_key='ps_asset_'.md5($im['asset']);$attachment=(int)get_option($asset_key);
            if(!$attachment || !get_post($attachment)) {
                $source=get_stylesheet_directory().'/assets/images/'.$im['asset'];
                $upload=wp_upload_bits($im['asset'],null,file_get_contents($source));
                if(!empty($upload['error'])) {continue;}
                $attachment=wp_insert_attachment(['post_mime_type'=>wp_check_filetype($upload['file'])['type'],'post_title'=>'XD — '.$im['label'],'post_status'=>'inherit'],$upload['file']);
                if(!is_wp_error($attachment)) {wp_update_attachment_metadata($attachment,wp_generate_attachment_metadata($attachment,$upload['file']));update_option($asset_key,$attachment);}
            }
            if($attachment&&!is_wp_error($attachment)) {update_field('field_'.$im['key'],$attachment,$id);}
        }
    }
    update_option('ps_page_ids',$ids);
    if(!get_option('ps_design_imported')) {
        update_option('show_on_front','page');update_option('page_on_front',$ids['home']);
        update_option('blogname','Papaya Search');update_option('blogdescription','Be seen. Stay ahead. Grow smarter.');
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure('/%postname%/');flush_rewrite_rules();
    }
    update_option('ps_design_imported','1.0.0');ps_install_menus();return $ids;
}
add_action('after_switch_theme',function(){update_option('ps_import_pending',1);});
add_action('admin_init',function(){
    if(get_option('ps_import_pending')&&current_user_can('manage_options')&&function_exists('update_field')) {
        $result=ps_import_design_content();if(!is_wp_error($result)){delete_option('ps_import_pending');}
    }
});
add_action('admin_menu',function(){add_theme_page('Papaya Setup','Papaya Setup','manage_options','papaya-setup',function(){
    if(!current_user_can('manage_options')){return;}
    echo '<div class="wrap"><h1>Papaya Search — XD Site Setup</h1>';
    if(isset($_POST['ps_import'])) {check_admin_referer('ps_import_design');$result=ps_import_design_content();echo is_wp_error($result)?'<div class="notice notice-error"><p>'.esc_html($result->get_error_message()).'</p></div>':'<div class="notice notice-success"><p>All eight pages and ACF content are ready. Existing edits were preserved.</p></div>';}
    echo '<p>Import the eight XD artboards, assign their PHP templates, and populate ACF text and image fields. This operation is safe to repeat.</p><form method="post">';wp_nonce_field('ps_import_design');submit_button('Import XD Pages & Content','primary','ps_import');echo '</form><p>Edit page content under Pages. Edit navigation under Appearance → Menus. Edit remaining footer content and page button destinations under Site Content.</p></div>';
});});
