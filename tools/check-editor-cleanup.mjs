import {runCLI} from '@wp-playground/cli';
import path from 'node:path'; import fs from 'node:fs';
import {execFileSync} from 'node:child_process';
// Test a consistent SQLite snapshot; never run cleanup against the source database.
fs.mkdirSync('.local/acf-audit/database', {recursive:true});
execFileSync('python3', ['-c', "import sqlite3; s=sqlite3.connect('.local/original-posts/database/.ht.sqlite'); d=sqlite3.connect('.local/acf-audit/database/.ht.sqlite'); s.backup(d); d.close(); s.close()"]);
const instance=await runCLI({command:'server',port:9493,php:'8.3',workers:1,'define-bool':{DISABLE_WP_CRON:true},'mount-before-install':['themes/astra','themes/papaya-search-child','plugins/advanced-custom-fields','mu-plugins'].map(p=>({hostPath:path.resolve('wordpress/wp-content',p),vfsPath:'/wordpress/wp-content/'+p})).concat([{hostPath:path.resolve('.local/acf-audit/database'),vfsPath:'/wordpress/wp-content/database'}])});
const run = async (code) => {
 const r=await instance.playground.run({code:"<?php require '/wordpress/wp-load.php'; " + code});
 if(r.errors || r.exitCode) throw Error(r.errors || r.text);
 return r.text;
};
try {
 const routes=['/','/about/','/services/','/services/seo/','/services/search-engine-marketing/','/blog/','/case-studies/'];
 const capture=async()=>Promise.all(routes.map(async route=>{
  const response=await fetch('http://127.0.0.1:9493'+route);
  if(!response.ok)throw Error(route+': '+response.status);
  const html=await response.text();
  return html.match(/<main[\s\S]*?<\/main>/)?.[0] || html;
 }));
 const before=await capture();
 console.log(await run(`
 global $wpdb;
 delete_option('ps_editor_cleanup_v1');
 require_once ABSPATH . 'wp-admin/includes/admin.php';
 set_current_screen('page');
 $seo=get_page_by_path('services/seo');
 $_GET['post']=$seo->ID;

 $hash=hash('sha256',serialize($wpdb->get_results("SELECT * FROM {$wpdb->postmeta} ORDER BY meta_id",ARRAY_A)));
 if(!ps_clean_editor_fields()) throw new Exception('Cleanup failed');
 unset($_GET['post']); $GLOBALS['current_screen']=null;
 if($hash!==hash('sha256',serialize($wpdb->get_results("SELECT * FROM {$wpdb->postmeta} ORDER BY meta_id",ARRAY_A)))) throw new Exception('Post metadata changed');
 $archive=acf_get_field_group('group_ps_retired_fields');
 foreach(ps_editor_retired_field_keys() as $key){$f=acf_get_field($key);if($f && (int)$f['parent']!==(int)$archive['ID'])throw new Exception('Not archived: '.$key);}
 foreach(ps_editor_field_order() as $key=>$names){
  $p=str_starts_with($key,'group_')?acf_get_field_group($key):acf_get_field($key);if(!$p)continue;
  $last=-1;$rank=array_flip($names);
  foreach(acf_get_fields($p)?:[] as $f){$pos=$rank[$f['key']]??$rank[$f['name']]??PHP_INT_MAX;if($pos<$last)throw new Exception('Incorrect order: '.$key);$last=$pos;}
 }
 $first=serialize($wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_type IN ('acf-field','acf-field-group') ORDER BY ID",ARRAY_A));
 ps_clean_editor_fields();
 if($first!==serialize($wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_type IN ('acf-field','acf-field-group') ORDER BY ID",ARRAY_A)))throw new Exception('Not idempotent');
 echo 'Field order, retirement, content preservation and repeat-run checks passed.';
 `));
 const after=await capture();
 before.forEach((html,i)=>{if(html!==after[i])throw Error('Frontend changed: '+routes[i]);});
 console.log('Frontend unchanged on '+routes.length+' routes.');
} finally { await instance[Symbol.asyncDispose](); }
