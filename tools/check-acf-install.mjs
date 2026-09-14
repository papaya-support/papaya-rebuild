import {runCLI} from '@wp-playground/cli';import path from 'node:path';import fs from 'node:fs';
const root=path.resolve(import.meta.dirname,'..');const content=path.join(root,'wordpress/wp-content');
const mount=['themes/astra','themes/papaya-search-child','plugins/advanced-custom-fields','mu-plugins'].map(p=>({hostPath:path.join(content,p),vfsPath:'/wordpress/wp-content/'+p}));
const instance=await runCLI({command:'server',port:9478,php:'8.3',workers:1,'mount-before-install':mount});
try{const result=await instance.playground.run({code:`<?php
require '/wordpress/wp-load.php';
ps_import_design_content();
$groups=json_decode(file_get_contents(get_stylesheet_directory().'/acf-import/field-groups.json'),true);
$checked=0;
foreach($groups as $group){$stored=acf_get_field_group($group['key']);if(empty($stored['ID']))throw new Exception('Missing database group');foreach($group['fields'] as $field){$actual=acf_get_field($field['key']);if($actual['type']!==$field['type']||$actual['label']!==$field['label'])throw new Exception('Field mismatch: '.$field['key']);$checked++;}}
$id=get_option('ps_page_ids')['home'];$key='home_1d791eb121c5';$old=get_field($key,$id,false);update_field('field_'.$key,'Preserved content verification',$id);
delete_option('ps_acf_database_groups_v1');ps_install_acf_groups();if(get_field($key,$id,false)!=='Preserved content verification')throw new Exception('Content overwritten');update_field('field_'.$key,$old,$id);
echo json_encode(['groups'=>count($groups),'verifiedFieldDefinitions'=>$checked,'existingContentPreserved'=>true,'databaseGroups'=>count(get_posts(['post_type'=>'acf-field-group','posts_per_page'=>-1]))]);`});const runtime=await instance.playground.run({code:fs.readFileSync('tools/verify-runtime.php','utf8')});
console.log(runtime.text);if(runtime.errors||runtime.exitCode)throw Error(runtime.errors||runtime.text);fs.writeFileSync('verification/runtime-checks.json',runtime.text);
const editors=await instance.playground.run({code:fs.readFileSync('tools/verify-acf-editors.php','utf8')});console.log(editors.text);if(editors.errors||editors.exitCode)throw Error(editors.errors||editors.text);fs.writeFileSync('verification/acf-editor-checks.json',editors.text);
const media=await instance.playground.run({code:fs.readFileSync('tools/verify-media-alt.php','utf8')});console.log(media.text);if(media.errors||media.exitCode)throw Error(media.errors||media.text);fs.writeFileSync('verification/media-alt-checks.json',media.text);
console.log(result.text);if(result.errors||result.exitCode)throw Error(result.errors||result.text);fs.writeFileSync('verification/acf-install-checks.json',result.text);}finally{await instance[Symbol.asyncDispose]();}
