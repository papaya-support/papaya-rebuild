<?php
require '/wordpress/wp-load.php';
$checks=[];
foreach (glob(get_stylesheet_directory().'/inc/*.php') as $file) {token_get_all(file_get_contents($file), TOKEN_PARSE);}
foreach (glob(get_stylesheet_directory().'/page-templates/*.php') as $file) {token_get_all(file_get_contents($file), TOKEN_PARSE);}
$parts=new RecursiveIteratorIterator(new RecursiveDirectoryIterator(get_stylesheet_directory().'/template-parts'));
foreach($parts as $file) {if($file->isFile() && $file->getExtension()==='php') {token_get_all(file_get_contents($file->getPathname()),TOKEN_PARSE);}}
$ids=get_option('ps_page_ids');
foreach (ps_pages() as $page) {
 $id=$ids[$page['slug']];$post=get_post($id);
 $checks[$page['slug']]=['id'=>$id,'published'=>$post->post_status==='publish','template'=>get_page_template_slug($id),'fields'=>count(ps_initial_content($page['slug'])['texts'])];
}
$id=$ids['home'];$field=ps_initial_content('home')['texts'];$field=array_values(array_filter($field,fn($t)=>$t['scope']==='page'&&$t['tag']==='h1'))[0];
$old=get_field($field['key'],$id,false);update_field('field_'.$field['key'],'ACF round-trip verification',$id);
$GLOBALS['post']=get_post($id);setup_postdata($GLOBALS['post']);
ob_start();get_template_part('template-parts/sections/home-hero');$rendered=ob_get_clean();$roundtrip=str_contains($rendered,'ACF round-trip verification');
update_field('field_'.$field['key'],$old,$id);wp_reset_postdata();
$before=count(get_posts(['post_type'=>'page','posts_per_page'=>-1]));ps_import_design_content();$after=count(get_posts(['post_type'=>'page','posts_per_page'=>-1]));
if(!$roundtrip||$before!==$after){throw new Exception('ACF or importer validation failed');}
echo json_encode(['php'=>PHP_VERSION,'wordpress'=>get_bloginfo('version'),'acf'=>ACF_VERSION,'theme'=>get_stylesheet(),'parent'=>get_template(),'acf_edit_render_restore'=>$roundtrip,'importer_idempotent'=>$before===$after,'pages'=>$checks],JSON_PRETTY_PRINT);
