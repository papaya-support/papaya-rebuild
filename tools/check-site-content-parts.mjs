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
ps_import_design_content(); ps_install_menus(); ps_install_section_groups();
$footer=ps_shared_id();
update_field('field_shared_78f8d1b3d0a8','Custom Navigation',$footer);
$before=get_post_meta($footer);
$menus=get_theme_mod('nav_menu_locations');
$render=function(){ob_start();get_template_part('template-parts/layout/site-header');get_template_part('template-parts/layout/site-footer');return ob_get_clean();};
$html=$render();
$result=ps_install_site_content_parts();
if(is_wp_error($result))throw new Exception($result->get_error_message());
$header=(int)get_option('ps_header_content_id');
if(!$header||$header===$footer||get_the_title($header)!=='Header'||get_the_title($footer)!=='Footer')throw new Exception('Incorrect entries');
if($before!==get_post_meta($footer))throw new Exception('Footer metadata changed');
if($menus!==get_theme_mod('nav_menu_locations'))throw new Exception('Menus changed');
// WordPress omits duplicate menu-item IDs when rendering twice in one request.
$normalize=function($markup){return preg_replace('/ id="menu-item-[0-9]+"/', '', $markup);};
if($normalize($html)!==$normalize($render()))throw new Exception('Design markup changed');
$header_groups=array_column(acf_get_field_groups(['post_id'=>$header]),'key');
$footer_groups=array_column(acf_get_field_groups(['post_id'=>$footer]),'key');
if(!in_array('group_ps_site_header',$header_groups)||in_array('group_ps_shared',$header_groups))throw new Exception('Header location mismatch');
if(!in_array('group_ps_shared',$footer_groups)||in_array('group_ps_site_header',$footer_groups))throw new Exception('Footer location mismatch');
$image=wp_insert_attachment(['post_title'=>'Test header logo','post_mime_type'=>'image/png','post_status'=>'inherit'],wp_upload_dir()['basedir'].'/header-test.png');
update_post_meta($image,'_wp_attachment_image_alt','Media Library logo alt');
update_field('field_ps_section_header',['logo'=>$image],$header);
if(ps_header_logo()['alt']!=='Media Library logo alt'||strpos(ps_header_logo()['url'],'header-test.png')===false)throw new Exception('Header logo field not connected');
wp_update_post(['ID'=>$header,'post_title'=>'Header Settings']);
ps_install_site_content_parts();
if(get_the_title($header)!=='Header Settings')throw new Exception('Repeated migration overwrote edit');
if(count(get_posts(['post_type'=>'ps_site_content','posts_per_page'=>-1]))!==2)throw new Exception('Duplicate entries');
echo 'PASS: separate Header/Footer entries and field locations, preserved footer data/menus/markup, editable Media Library logo, idempotent migration.';
`,
  });
  if (result.errors || result.exitCode)
    throw Error(result.errors || result.text);
  console.log(result.text);
} finally {
  await instance[Symbol.asyncDispose]();
}
