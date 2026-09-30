/** Regression: existing author profile differences must not block or change accounts. */
import { runCLI } from "@wp-playground/cli";
import path from "node:path";
const content = path.resolve("wordpress/wp-content");
const instance = await runCLI({
  command: "server",
  port: 9482,
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
$source = PS_Original_Post_Import::wp(PS_Original_Post_Import::source())->author;
$first = $source[0];
$login = (string) $first->author_login;
$id = wp_insert_user(['user_login'=>$login,'user_email'=>'existing-local@example.test','user_pass'=>wp_generate_password(),'display_name'=>'Existing staging profile','first_name'=>'Different','role'=>'editor']);
if(is_wp_error($id))throw new Exception($id->get_error_message());
$before = get_userdata($id);
$state = PS_Original_Post_Import::state();
PS_Original_Post_Import::authors($state);
$after = get_userdata($id);
foreach(['user_email','display_name','first_name','user_pass'] as $key)if($before->$key!==$after->$key)throw new Exception('Account changed: '.$key);
if($before->roles!==$after->roles)throw new Exception('Roles changed');
if($state['authors'][$login]!==$id)throw new Exception('Existing login not reused');
if($state['source_authors'][$login]['display_name']!==(string)$first->author_display_name)throw new Exception('Original author details lost');
$count=count_users()['total_users'];
PS_Original_Post_Import::authors($state);
if(count_users()['total_users']!==$count)throw new Exception('Duplicate authors created');
// Exercise email fallback with a different login in a separate mapping pass.
$second=$source[1];$old=$state['authors'][(string)$second->author_login];
global $wpdb;
$wpdb->update($wpdb->users,['user_login'=>'existing-email-mapping'],['ID'=>$old]);
clean_user_cache($old);
$retry=PS_Original_Post_Import::state();$retry['authors']=[];
PS_Original_Post_Import::authors($retry);
if($retry['authors'][(string)$second->author_login]!==$old)throw new Exception('Email mapping failed');
if(count_users()['total_users']!==$count)throw new Exception('Email mapping duplicated user');
echo 'PASS: login conflict, email fallback, source profile preservation, unchanged credentials/roles and repeat mapping.';
`,
  });
  if (result.exitCode || result.errors)
    throw Error(result.errors || result.text);
  console.log(result.text);
} finally {
  await instance[Symbol.asyncDispose]();
}
