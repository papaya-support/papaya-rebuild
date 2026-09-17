import { runCLI } from '@wp-playground/cli';
import { chromium } from 'playwright';
import path from 'node:path';
import assert from 'node:assert/strict';
const content = path.resolve('wordpress/wp-content');
const instance = await runCLI({
  command: 'server',
  port: 9479,
  php: '8.3',
  workers: 1,
  'mount-before-install': [
    'themes/astra',
    'themes/papaya-search-child',
    'plugins/advanced-custom-fields',
    'mu-plugins',
  ].map((p) => ({
    hostPath: path.join(content, p),
    vfsPath: '/wordpress/wp-content/' + p,
  })),
});
let browser;
try {
  const result = await instance.playground.run({
    code: `<?php
require '/wordpress/wp-load.php';
ps_import_design_content();
$result = ps_install_case_studies();
if (is_wp_error($result)) throw new Exception($result->get_error_message());
$seed = ps_seed_case_studies();
if (is_wp_error($seed)) throw new Exception($seed->get_error_message());
$samples = get_posts(['post_type'=>'case_study','posts_per_page'=>-1,'meta_key'=>'_ps_sample_case']);
if (count($samples) !== 9) throw new Exception('Expected nine samples');
foreach ($samples as $sample) {
    if (!get_post_thumbnail_id($sample->ID)) throw new Exception('Missing sample image');
}
update_field('field_case_study_detail_7ad346f8413b','Preserved sample edit',$samples[0]->ID);
ps_seed_case_studies();
if (get_field('case_study_detail_7ad346f8413b',$samples[0]->ID,false) !== 'Preserved sample edit') throw new Exception('Sample overwritten');
wp_insert_post(['post_type'=>'case_study','post_title'=>'Hidden draft','post_status'=>'draft']);
$type = acf_get_post_type('post_type_ps_case_study');
if (empty($type['ID']) || !post_type_exists('case_study')) throw new Exception('Missing native ACF post type');
$group = acf_get_field_group('group_ps_case-study-detail');
if (!in_array([['param'=>'post_type','operator'=>'==','value'=>'case_study']], $group['location'], true)) throw new Exception('Missing location');
$fields = acf_get_fields($group);
if (count($fields) !== 16) throw new Exception('Missing detail fields');
$id = wp_insert_post(['post_type'=>'case_study','post_title'=>'Case study test','post_name'=>'case-study-test','post_status'=>'publish']);
update_field('field_case_study_detail_7ad346f8413b', '<p>Unique client introduction.</p>', $id);
update_field('field_case_study_detail_afcd2640f663', 'Client success heading', $id);
$group['description'] = 'Editor customization preserved';
acf_update_field_group($group);
$type['description'] = 'Post type customization preserved';
acf_update_post_type($type);
ps_install_case_studies();
if (acf_get_field_group($group['ID'])['description'] !== 'Editor customization preserved') throw new Exception('Group overwritten');
if (acf_get_post_type($type['ID'])['description'] !== 'Post type customization preserved') throw new Exception('Type overwritten');
$GLOBALS['post'] = get_post($id);
setup_postdata($GLOBALS['post']);
update_field('field_case_study_detail_afcd2640f663', '', $id);
if (ps_field_value('case_study_detail_afcd2640f663') !== 'Case study test') throw new Exception('Missing title fallback');
if (ps_field_value('case_study_detail_3f5be18822e8') !== '') throw new Exception('Sample testimonial leaked');
update_field('field_case_study_detail_afcd2640f663', 'Client success heading', $id);
flush_rewrite_rules(false);
echo json_encode(['url'=>get_permalink($id), 'fields'=>count($fields), 'acfPostTypeId'=>$type['ID']]);`,
  });
  if (result.exitCode || result.errors) throw Error(result.errors || result.text);
  const data = JSON.parse(result.text);
  browser = await chromium.launch({
    executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
    headless: true,
  });
  const page = await browser.newPage();
  const response = await page.goto(data.url);
  assert.equal(response.status(), 200);
  assert.equal(await page.locator('main.page-case-study-detail').count(), 1);
  assert.equal(await page.locator('h1').count(), 1);
  assert.equal(await page.locator('h1').innerText(), 'Client success heading');
  assert.match(await page.locator('main').innerText(), /Unique client introduction/);
  for (const width of [390, 1280]) {
    await page.setViewportSize({ width, height: 900 });
    assert.equal(
      await page.evaluate(() => document.documentElement.scrollWidth > innerWidth),
      false,
    );
  }
  assert.equal((await page.goto('http://127.0.0.1:9479/case-studies/')).status(), 200);
  assert.equal(await page.locator('.post-card').count(), 10);
  assert.equal(await page.getByText('Hidden draft').count(), 0);
  const links = await page
    .locator('.post-card h2 a')
    .evaluateAll((nodes) => nodes.map((node) => node.href));
  assert.equal(new Set(links).size, 10);
  for (const url of links) {
    assert.equal((await page.goto(url)).status(), 200);
    assert.equal(await page.locator('main.page-case-study-detail').count(), 1);
    assert.equal(await page.locator('h1').count(), 1);
  }
  console.log(
    'Nine samples, no duplicates, preserved sample edits, dynamic cards, draft exclusion and ten unique single pages verified. Native ACF type, 16 fields, preserved edits, singular template, ACF values, one H1, responsive layout and existing landing page verified.',
  );
} finally {
  await browser?.close();
  await instance[Symbol.asyncDispose]();
}
