<?php
/** Approved brand colors shared with the WordPress editor. */
defined('ABSPATH') || exit;
function ps_brand_palette() {
    return [
        ['name'=>'Primary green','slug'=>'papaya-teal','color'=>'#003e3f'],
        ['name'=>'Sage','slug'=>'papaya-sage','color'=>'#718a6b'],
        ['name'=>'Light green','slug'=>'papaya-green','color'=>'#bfd9ba'],
        ['name'=>'Off-white background','slug'=>'papaya-cream','color'=>'#fbf2e6'],
        ['name'=>'Orange accent','slug'=>'papaya-orange','color'=>'#e76025'],
        ['name'=>'Light orange','slug'=>'papaya-light-orange','color'=>'#ec882d'],
        ['name'=>'Peach','slug'=>'papaya-peach','color'=>'#de8d6d'],
    ];
}
// Map Astra's accent, heading, body and surface slots to the approved colors.
add_filter('astra_get_option_global-color-palette', function($value) {
    $value=is_array($value)?$value:[];
    $colors=array_column(ps_brand_palette(),'color','slug');
    $value['palette']=array_map(fn($slug)=>$colors['papaya-'.$slug], ['orange','light-orange','teal','teal','cream','cream','sage','green','peach']);
    // Keep the base surface white; cream belongs to designated design sections.
    $value['palette'][4]='#ffffff';
    $value['palette'][5]='#ffffff';
    return $value;
});
add_action('after_setup_theme', function() {add_theme_support('editor-color-palette', ps_brand_palette());}, 30);
add_filter('tiny_mce_before_init', function($settings) {
    $map=[];
    foreach(ps_brand_palette() as $color) {$map[]=ltrim($color['color'],'#');$map[]=$color['name'];}
    $settings['textcolor_map']=wp_json_encode($map);
    return $settings;
});
/** Refresh only the original imported emblem; preserve replacement images and alt text. */
add_action('admin_init', function() {
    if(!current_user_can('manage_options') || get_option('ps_brand_emblem_v1')) {return;}
    $id=(int)get_option('ps_asset_'.md5('hero-emblem.png'));
    $file=$id?get_attached_file($id):false;
    if(!$file || !is_file($file)) {return;}
    if(hash_file('sha256',$file)!=='f86f7af73c0fd32b172d6d0ee811a7f5730cfd30e2ae259fa3744a2d9ecf8210') {update_option('ps_brand_emblem_v1',1,false);return;}
    $upload=wp_upload_bits('papaya-brand-emblem.png',null,file_get_contents(get_stylesheet_directory().'/assets/images/hero-emblem.png'));
    if(!empty($upload['error'])) {return;}
    require_once ABSPATH.'wp-admin/includes/image.php';
    update_attached_file($id,$upload['file']);
    wp_update_attachment_metadata($id,wp_generate_attachment_metadata($id,$upload['file']));
    update_option('ps_brand_emblem_v1',1,false);
});
