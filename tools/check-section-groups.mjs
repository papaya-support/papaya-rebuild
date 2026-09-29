import { runCLI } from "@wp-playground/cli";
import { chromium } from "playwright";
import path from "node:path";
import assert from "node:assert/strict";
const content = path.resolve("wordpress/wp-content");
const instance = await runCLI({
  command: "server",
  port: 9479,
  php: "8.3",
  workers: 1,
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
let browser;
const run = async (code) => {
  const result = await instance.playground.run({
    code: "<?php require '/wordpress/wp-load.php';\n" + code,
  });
  if (result.errors || result.exitCode)
    throw Error(result.errors || result.text);
  return result.text;
};
try {
  const seeded = JSON.parse(
    await run(`ps_import_design_content(); ps_seed_case_studies();
$case=get_posts(['post_type'=>'case_study','posts_per_page'=>1])[0];
echo json_encode(['case'=>get_permalink($case),'case_id'=>$case->ID,'home'=>get_option('ps_page_ids')['home']]);`),
  );
  browser = await chromium.launch({
    executablePath:
      "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome",
    headless: true,
  });
  const page = await browser.newPage();
  const urls = [
    "/",
    "/about/",
    "/services/",
    "/search-engine-marketing/",
    "/blog/",
    "/blog-detail/",
    "/case-studies/",
    "/case-study-detail/",
    seeded.case,
  ];
  const capture = async () => {
    const results = {};
    for (const url of urls)
      for (const width of [390, 768, 1280]) {
        await page.setViewportSize({ width, height: 900 });
        await page.goto(
          url.startsWith("http") ? url : "http://127.0.0.1:9479" + url,
        );
        await page.evaluate(async () => {
          await document.fonts.ready;
          await Promise.all(
            [...document.images].map((i) => {
              i.loading = "eager";
              return i.decode().catch(() => {});
            }),
          );
        });
        results[url + "-" + width] = await page.evaluate(() =>
          [
            ...document.querySelectorAll(
              "header,header *,main,main *,footer,footer *",
            ),
          ].map((e) => {
            const r = e.getBoundingClientRect(),
              s = getComputedStyle(e);
            return [
              e.tagName,
              [...e.attributes].map((a) => [a.name, a.value]),
              e.children.length ? null : e.textContent,
              [r.x, r.y, r.width, r.height],
              s.color,
              s.backgroundColor,
              s.fontSize,
              s.margin,
              s.padding,
            ];
          }),
        );
      }
    return results;
  };
  const before = await capture();
  console.log("Captured 27 page/viewport baselines.");
  console.log(
    await run(`$result=ps_install_section_groups();if(is_wp_error($result))throw new Exception($result->get_error_message());
$count=0;$groups=0;
foreach(ps_section_groups() as $slug=>$sections){foreach($sections as $section){$groups++;foreach($section['fields'] as $old=>$name){$field=acf_get_field('field_'.$old);$parent=acf_get_field($field['parent']);if($field['name']!==$name||$parent['type']!=='group')throw new Exception('Incorrect parent '.$old);$ids=get_posts(['post_type'=>['page','case_study','ps_site_content'],'post_status'=>'any','posts_per_page'=>-1,'fields'=>'ids','meta_key'=>$old]);foreach($ids as $id){$value=ps_grouped_field_value($old,$id,$found);if(!$found||$value!==get_post_meta($id,$old,true))throw new Exception('Content changed '.$old.' '.$id);} $count++;}}}
echo "Verified $count grouped field definitions across $groups sections.";`),
  );
  const after = await capture();
  for (const key of Object.keys(before))
    assert.deepEqual(after[key], before[key], key + " changed after migration");
  console.log(
    await run(
      `$id=${seeded.home};$old='home_2263b6f88209';[$group,$name]=ps_grouped_field_binding($old);$value=get_field($group,$id,false);$value['field_'.$old]='Edited grouped heading';update_field($group,$value,$id);$GLOBALS['post']=get_post($id);setup_postdata($GLOBALS['post']);if(ps_field_value($old)!=='Edited grouped heading')throw new Exception('Grouped edit not rendered');$value['field_'.$old]='';update_field($group,$value,$id);if(ps_field_value($old)!=='')throw new Exception('Empty field fell back');ps_install_section_groups();if(ps_field_value($old)!=='')throw new Exception('Migration overwrote edit');echo 'Group edits, cleared values and repeat migration verified.';`,
    ),
  );
  console.log(
    "All 27 rendered page comparisons unchanged, including native Case Studies.",
  );
} finally {
  await browser?.close();
  await instance[Symbol.asyncDispose]();
}
