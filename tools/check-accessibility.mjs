import {runCLI} from '@wp-playground/cli';
import {chromium} from 'playwright';
import path from 'node:path';
import fs from 'node:fs';
const content=path.resolve('wordpress/wp-content');
const mount=['themes/astra','themes/papaya-search-child','plugins/advanced-custom-fields','mu-plugins'].map(p=>({hostPath:path.join(content,p),vfsPath:'/wordpress/wp-content/'+p}));
const instance=await runCLI({command:'server',port:9479,php:'8.3',workers:1,'mount-before-install':mount});let browser;
const report=[];
try {
const seeded=await instance.playground.run({code:`<?php require '/wordpress/wp-load.php';ps_import_design_content();
$home=get_option('ps_page_ids')['home'];$GLOBALS['post']=get_post($home);setup_postdata($GLOBALS['post']);
$old=get_field('home_1d791eb121c5',$home,false);update_field('field_home_1d791eb121c5','',$home);
ob_start();ps_text('home_1d791eb121c5','h1');$empty=ob_get_clean();
if(!preg_match('/<h1[^>]*>[^<]+<\\/h1>/', $empty))throw new Exception('Empty ACF title fallback failed');
update_field('field_home_1d791eb121c5',$old,$home);wp_reset_postdata();
$normalized=ps_article_headings('<h1>Top</h1><h2>Child</h2><h5>Grandchild</h5><h2>Sibling</h2>');
if(!preg_match('/<h2.*<h3.*<h4.*<h3/s',$normalized))throw new Exception('Nested editorial headings failed');
$id=wp_insert_post(['post_type'=>'post','post_status'=>'publish','post_title'=>'Accessibility audit article','post_content'=>'<h1>Editor heading</h1><p>Article body.</p><h4>Subheading</h4><h2>Next section</h2>']);
$plain=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Plain page','post_content'=>'<h3>Body heading</h3><p>Page content.</p>']);
echo json_encode([get_permalink($id),get_permalink($plain),get_category_link(1)]);`});
const urls=['/','/about/','/services/','/search-engine-marketing/','/blog/','/blog-detail/','/case-studies/','/case-study-detail/',...JSON.parse(seeded.text),'/?s=Accessibility','/missing-audit-page/'];
browser=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});const page=await browser.newPage();
for(const width of [1280,390]) {
 await page.setViewportSize({width,height:900});
 for(const url of urls) {
  await page.goto(new URL(url,'http://127.0.0.1:9479').href);await page.addScriptTag({path:path.resolve('tools/node_modules/axe-core/axe.min.js')});
  const data=await page.evaluate(async()=>{
   const headings=[...document.querySelectorAll('h1,h2,h3,h4,h5,h6')].map(h=>({level:+h.tagName[1],text:h.textContent.trim()}));
   const axe=await window.axe.run(document,{runOnly:{type:'tag',values:['wcag2a','wcag2aa','wcag21aa','best-practice']}});
   return {headings,overflow:document.documentElement.scrollWidth>innerWidth,title:document.title,lang:document.documentElement.lang,canonical:document.querySelector('link[rel=canonical]')?.href,images:[...document.querySelectorAll('main img')].map(i=>({width:i.getAttribute('width'),height:i.getAttribute('height'),responsive:!!i.srcset,loading:i.loading,broken:i.complete&&!i.naturalWidth})),violations:axe.violations.map(v=>({id:v.id,impact:v.impact,nodes:v.nodes.map(n=>({html:n.html,summary:n.failureSummary}))}))};
  });report.push({url,width,...data});
 }
}
await page.goto('http://127.0.0.1:9479/');
await page.keyboard.press('Tab');await page.keyboard.press('Enter');
if(await page.locator('main').evaluate(e=>e!==document.activeElement))throw Error('Skip link did not focus main');
await page.locator('.menu-toggle').click();await page.locator('#primary-navigation a').first().focus();await page.keyboard.press('Escape');
if(await page.locator('.menu-toggle').getAttribute('aria-expanded')!=='false'||!await page.locator('.menu-toggle').evaluate(e=>e===document.activeElement))throw Error('Mobile menu focus restoration');
await page.screenshot({path:'/tmp/papaya-accessibility-mobile.png',fullPage:true});
await page.setViewportSize({width:1280,height:900});await page.goto('http://127.0.0.1:9479/blog-detail/');await page.screenshot({path:'/tmp/papaya-accessibility-desktop.png',fullPage:true});
const noJS=await browser.newPage({javaScriptEnabled:false,viewport:{width:390,height:900}});await noJS.goto('http://127.0.0.1:9479/');
if(!await noJS.locator('#primary-navigation').isVisible())throw Error('No-JavaScript mobile navigation inaccessible');
await noJS.close();
fs.mkdirSync('verification',{recursive:true});fs.writeFileSync('verification/accessibility-audit.json',JSON.stringify(report,null,2));
console.log(JSON.stringify(report.map(r=>({url:r.url,width:r.width,h1:r.headings.filter(h=>h.level===1).length,gaps:r.headings.filter((h,i)=>i&&h.level>r.headings[i-1].level+1),overflow:r.overflow,violations:r.violations.map(v=>({id:v.id,count:v.nodes.length,example:v.nodes[0]}))})),null,2));
// Exact XD colors retained at the user's request; report contrast failures explicitly.
const failures=report.filter(r=>r.overflow||!r.title||!r.lang||r.headings.filter(h=>h.level===1).length!==1||r.headings.some((h,i)=>!h.text||(i&&h.level>r.headings[i-1].level+1))||r.violations.some(v=>v.id!=='color-contrast'));
console.log(`${report.length} viewport checks; ${failures.length} structural/non-contrast failures. Contrast exceptions retained per user instruction.`);
if(failures.length)process.exitCode=1;
}finally{await browser?.close();await instance[Symbol.asyncDispose]();}
