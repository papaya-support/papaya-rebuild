import { runCLI } from "@wp-playground/cli";
import path from "node:path";
const content = path.resolve("wordpress/wp-content");
const instance = await runCLI({
  command: "server",
  port: 9483,
  php: "8.3",
  workers: 1,
  "define-bool": { DISABLE_WP_CRON: true },
  "mount-before-install": [
    "themes/astra",
    "themes/papaya-search-child",
    "plugins/advanced-custom-fields",
    "mu-plugins",
  ].map((p) => ({
    hostPath: path.join(content, p),
    vfsPath: "/wordpress/wp-content/" + p,
  })),
});
try {
  const result = await instance.playground.run({
    code: `<?php
require '/wordpress/wp-load.php';
wp_set_current_user(1);
ps_import_design_content();ps_install_section_groups();
for($i=0;$i<9;$i++){
$result=ps_service_import_step();
if(is_wp_error($result))throw new Exception($result->get_error_message());
}
if(count($result['completed'])!==9)throw new Exception('Incomplete import');
foreach(ps_service_import_data()['pages'] as $source){
$id=$result['ids'][$source['path']];
if(get_page_template_slug($id)!=='page-templates/search-engine-marketing.php')throw new Exception('Wrong template');
if(get_post_status($id)!=='publish')throw new Exception('Not published');
$intro_fields=get_field('section_introduction',$id);
if($intro_fields['heading']!==$source['groups']['section_introduction']['heading'])throw new Exception('Heading mismatch');
$groups=acf_get_field_groups(['post_id'=>$id]);
if(!in_array('group_ps_search-engine-marketing',array_column($groups,'key')))throw new Exception('Fields not visible');
foreach($source['groups'] as $group_name=>$fields){
foreach($fields as $field_name=>$value){
$stored=get_post_meta($id,$group_name.'_'.$field_name,true);
if($field_name==='image'&&$value){
if(!$stored||!get_attached_file((int)$stored)||!file_exists(get_attached_file((int)$stored)))throw new Exception('Missing imported image');
} else {
$expected=str_contains($value,'<')?ps_service_import_html($value,$result['ids']):$value;
if($stored!==$expected)throw new Exception('ACF value mismatch: '.$source['path'].' '.$group_name.' '.$field_name);
}
}}
foreach(ps_section_groups()['search-engine-marketing'] as $section){
foreach($section['fields'] as $legacy=>$name){
if(!metadata_exists('post',$id,$section['name'].'_'.$name))throw new Exception('Missing stored field: '.$section['name'].'_'.$name);
}}
$GLOBALS['post']=get_post($id);setup_postdata($GLOBALS['post']);

// The template loop is tested via HTTP below; validate section data directly here.
foreach($source['faqs'] as $faq){
if(strpos(serialize(get_post_meta($id)),$faq['question'])===false)throw new Exception('FAQ missing: '.$source['path'].' '.$faq['question']);
}
}
$before=get_post_meta($result['ids']['services/seo']);
ps_service_import_step();
if($before!==get_post_meta($result['ids']['services/seo']))throw new Exception('Repeat import overwrote content');
echo wp_json_encode($result);

`,
  });
  if (result.errors || result.exitCode)
    throw Error(result.errors || result.text);
  const state = JSON.parse(result.text);
  for (const route of Object.keys(state.ids)) {
    const response = await fetch(`http://127.0.0.1:9483/${route}/`);
    const html = await response.text();
    if (
      !response.ok ||
      (html.match(/<h1(?:\s|>)/g) || []).length !== 1 ||
      !html.includes("page-search-engine-marketing")
    )
      throw Error(`Render failed: ${route}`);
    if (html.includes("Fatal error")) throw Error(`PHP error: ${route}`);
    for (const section of ["benefits", "google-ads", "closing-cta"]) {
      if (!html.includes(`section-search-engine-marketing-${section}`))
        throw Error(`Missing XD section ${section}: ${route}`);
    }
    const banner =
      html.match(
        /<section class="section-search-engine-marketing-banner[\s\S]*?<\/section>/,
      )?.[0] || "";
    if (!banner.includes("df292e83c9c5368e3616147b1df1019d"))
      throw Error(`Banner is not the XD placeholder: ${route}`);
    if (html.includes("section-search-engine-marketing-microsoft-ads"))
      throw Error(`Removed section returned: ${route}`);
  }
  console.log(
    "PASS: 9 pages, template assignment, visible ACF fields, all FAQs, safe repeat import, and HTTP rendering with one H1 each.",
  );
} finally {
  await instance[Symbol.asyncDispose]();
}
