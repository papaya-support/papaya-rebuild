import {runCLI} from '@wp-playground/cli';import path from 'node:path';import fs from 'node:fs';
const root=path.resolve(import.meta.dirname,'..');const content=path.join(root,'wordpress/wp-content');
const mount=['themes/astra','themes/papaya-search-child','plugins/advanced-custom-fields','mu-plugins'].map(p=>({hostPath:path.join(content,p),vfsPath:'/wordpress/wp-content/'+p}));
const instance=await runCLI({command:'server',port:9479,php:'8.3',workers:1,'mount-before-install':mount});
const legacy=fs.readFileSync('tools/fixtures/hero-emblem-legacy.png').toString('base64');
try {const result=await instance.playground.run({code:`<?php require '/wordpress/wp-load.php';ps_import_design_content();wp_set_current_user(1);
$css=file_get_contents(get_stylesheet_directory().'/assets/site.css');
foreach(ps_brand_palette() as $color) {if(!str_contains($css,'--'.$color['slug'].': '.$color['color']))throw new Exception('CSS/editor mismatch');}
$editor=get_theme_support('editor-color-palette')[0];if($editor!==ps_brand_palette())throw new Exception('Editor palette mismatch');
$astra=astra_get_option('global-color-palette')['palette'];if(count($astra)!==9||$astra[0]!=='#e76025')throw new Exception('Astra palette');
$id=(int)get_option('ps_asset_'.md5('hero-emblem.png'));
$legacy=wp_upload_bits('legacy-test.png',null,base64_decode('${legacy}'));update_attached_file($id,$legacy['file']);update_post_meta($id,'_wp_attachment_image_alt','Preserved custom alt');delete_option('ps_brand_emblem_v1');do_action('admin_init');
if(hash_file('sha256',get_attached_file($id))!==hash_file('sha256',get_stylesheet_directory().'/assets/images/hero-emblem.png'))throw new Exception('Emblem not refreshed');
if(get_post_meta($id,'_wp_attachment_image_alt',true)!=='Preserved custom alt')throw new Exception('Alt overwritten');
$updated=get_attached_file($id);delete_option('ps_brand_emblem_v1');do_action('admin_init');if(get_attached_file($id)!==$updated)throw new Exception('Updated/custom media was replaced');
echo 'Seven CSS/editor colors, Astra palette, legacy emblem refresh, alt preservation and repeat safety passed.';`});console.log(result.text);if(result.errors||result.exitCode)throw Error(result.errors||result.text);} finally {await instance[Symbol.asyncDispose]();}
