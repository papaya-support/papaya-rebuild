import {runCLI} from '@wp-playground/cli';import {chromium} from 'playwright';import path from 'node:path';
const content=path.resolve('wordpress/wp-content');
const mount=['themes/astra','themes/papaya-search-child','plugins/advanced-custom-fields','mu-plugins'].map(p=>({hostPath:path.join(content,p),vfsPath:'/wordpress/wp-content/'+p}));
const instance=await runCLI({command:'server',port:9479,php:'8.3',workers:1,'mount-before-install':mount});let browser;
try {
const seeded=await instance.playground.run({code:`<?php require '/wordpress/wp-load.php';ps_import_design_content();
wp_set_current_user(1);$group=acf_get_field_group('group_ps_blog');acf_update_field(['key'=>'field_blog_legacy_test','name'=>'blog_legacy_test','label'=>'Old card','type'=>'text','parent'=>$group['ID']]);do_action('admin_init');if(acf_get_field('field_blog_legacy_test')||!acf_get_field('field_blog_2f0c8cf2c985'))throw new Exception('Blog field migration');
foreach(get_posts(['post_type'=>'post','posts_per_page'=>-1,'post_status'=>'any']) as $p){wp_delete_post($p->ID,true);}
$a=wp_insert_term('SEO & Strategy','category',['slug'=>'qa-seo'])['term_id'];$b=wp_insert_term('Paid Search','category',['slug'=>'qa-paid'])['term_id'];$child=wp_insert_term('Local Search','category',['slug'=>'qa-local','parent'=>$a])['term_id'];
$ids=[];for($i=1;$i<=12;$i++){$id=wp_insert_post(['post_type'=>'post','post_status'=>'publish','post_title'=>'Dynamic article '.$i,'post_content'=>'Native article content '.$i,'post_excerpt'=>'Custom excerpt '.$i,'post_date'=>sprintf('2025-01-%02d 12:00:00',$i)]);wp_set_post_categories($id,$i===12?[$a,$b]:($i===11?[$child]:[$a]));$ids[]=$id;}
wp_insert_post(['post_type'=>'post','post_status'=>'draft','post_title'=>'Hidden draft']);
set_post_thumbnail($ids[11],get_field('home_hero_emblem',get_option('ps_page_ids')['home'],false));
echo json_encode(['ids'=>$ids,'latest'=>get_permalink($ids[11])]);`});const fixture=JSON.parse(seeded.text);
browser=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});const page=await browser.newPage({javaScriptEnabled:false});
const url='http://127.0.0.1:9479/blog/';await page.goto(url);
if(await page.locator('.post-card').count()!==9)throw Error('Initial page size');
if(!await page.locator('.post-card').first().textContent().then(t=>t.includes('Dynamic article 12')&&t.includes('Custom excerpt 12')&&t.includes('SEO & Strategy')&&t.includes('Paid Search')))throw Error('Native post fields');
if(await page.locator('.post-card').first().locator('img').count()!==1)throw Error('Featured image missing');
if(await page.getByText('Hidden draft').count())throw Error('Draft leaked');
await page.locator('.blog-pagination a').filter({hasText:'Next'}).click();if(await page.locator('.post-card').count()!==3)throw Error('Second page');
await page.getByRole('link',{name:'Paid Search',exact:true}).first().click();if(await page.locator('.post-card').count()!==1||page.url().includes('blog_page'))throw Error('Filter/reset');
await page.getByRole('link',{name:'SEO & Strategy',exact:true}).first().click();await page.locator('.blog-pagination a').filter({hasText:'Next'}).click();if(!page.url().includes('blog_category=qa-seo')||await page.locator('.post-card').count()!==3)throw Error('Filtered pagination/descendants');
await page.goto(url+'?blog_category=missing');if(await page.locator('.post-card').count()||!await page.locator('.blog-empty').isVisible())throw Error('Empty state');
await page.goto(fixture.latest);if(!await page.locator('main').textContent().then(t=>t.includes('Native article content 12')))throw Error('Article link: '+fixture.latest+' '+await page.locator('main').textContent());
await page.setViewportSize({width:390,height:900});await page.goto(url);if(await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth))throw Error('Mobile overflow');
console.log('Dynamic Blog passed: native post fields, featured image, multiple categories, parent categories, pagination, drafts, empty state, permalinks, mobile and JavaScript-disabled filtering.');
} finally {await browser?.close();await instance[Symbol.asyncDispose]();}
