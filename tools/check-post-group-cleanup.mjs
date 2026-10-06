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
$gray='<!-- wp:group {"style":{"color":{"background":"#f5f5f5"},"spacing":{"padding":{"top":"30px"}}},"layout":{"type":"flex"}} --><div class="wp-block-group has-background" style="background-color:#f5f5f5;padding-top:30px"><!-- wp:paragraph --><p style="color:red">Keep this text and its child style.</p><!-- /wp:paragraph --></div><!-- /wp:group -->';
$other=str_replace('#f5f5f5','#feedda',$gray);
$post=wp_insert_post(['post_type'=>'post','post_status'=>'publish','post_title'=>'Cleanup test','post_content'=>wp_slash($gray.$other)]);
$page=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Unchanged page','post_content'=>wp_slash($gray)]);
$before=get_post($post,ARRAY_A);
$report=ps_cleanup_post_group_styles();
if(is_wp_error($report))throw new Exception($report->get_error_message());
$after=get_post($post,ARRAY_A);
$parsed=parse_blocks($after['post_content']);
if(isset($parsed[0]['attrs']['style'])||strpos($parsed[0]['innerHTML'],'background-color')!==false)throw new Exception('Group styles remain');
if(strpos($after['post_content'],'<p style="color:red">')===false)throw new Exception('Child style changed');
if(serialize_block($parsed[1])!==$other)throw new Exception('Other color group changed');
if(get_post($page)->post_content!==$gray)throw new Exception('Page changed');
if(get_post_meta($post,'_ps_before_group_style_cleanup_v1',true)!==$before['post_content'])throw new Exception('Backup mismatch');
unset($before['post_content'],$after['post_content']);
if($before!==$after)throw new Exception('Other post fields changed');
$saved=get_post($post)->post_content;
ps_cleanup_post_group_styles();
if(get_post($post)->post_content!==$saved)throw new Exception('Repeat cleanup changed content');
$nested=parse_blocks('<!-- wp:group --><div class="wp-block-group">'.$gray.'</div><!-- /wp:group -->');
$count=0;$nested=ps_clean_gray_post_groups($nested,$count);
if($count!==1||isset($nested[0]['innerBlocks'][0]['attrs']['style']))throw new Exception('Nested Group cleanup failed');
echo 'PASS: matching Group styles removed; child styles, other groups, pages, post fields preserved; backup exact; nested and repeat cleanup verified.';

`,
  });
  if (result.errors || result.exitCode)
    throw Error(result.errors || result.text);
  console.log(result.text);
} finally {
  await instance[Symbol.asyncDispose]();
}
