if(process.argv.includes('--compare-only')) { await import('./compare-visual.mjs'); process.exit(0); }
import { chromium } from 'playwright';
import fs from 'node:fs';
import path from 'node:path';
const root=path.resolve(import.meta.dirname,'..');
const pages=JSON.parse(fs.readFileSync(path.join(root,'wordpress/wp-content/themes/papaya-search-child/design/pages.json')));
const browser=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});
const page=await browser.newPage({viewport:{width:1280,height:900},deviceScaleFactor:1});
const errors=[],results=[];page.on('pageerror',e=>errors.push(e.message));
for(const design of pages){
 const url='http://127.0.0.1:9477/'+(design.slug==='home'?'':design.slug+'/');
 const response=await page.goto(url,{waitUntil:'networkidle'});await page.evaluate(()=>document.fonts.ready);
 const result=await page.evaluate(()=>({title:document.title,h1:document.querySelectorAll('.xd-stage h1').length,fields:document.querySelectorAll('.xd-stage [data-field]').length,overflow:document.documentElement.scrollWidth>innerWidth,missingImages:[...document.images].filter(i=>i.complete&&!i.naturalWidth).map(i=>i.src),unresolvedTokens:document.body.innerHTML.includes('{{'),text:document.querySelector('.xd-stage')?.innerText.slice(0,120)}));
 result.slug=design.slug;result.status=response.status();
 if(response.status()!==200||result.h1!==1||result.unresolvedTokens||result.missingImages.length)throw new Error(JSON.stringify(result));
 await page.screenshot({path:path.join(root,`verification/${design.slug}.png`),fullPage:true});results.push(result);
}
await page.goto('http://127.0.0.1:9477/search-engine-marketing/',{waitUntil:'networkidle'});
const faq=page.locator('.xd-stage .faq-question').first();await faq.locator('.xd-line').first().click();if(await faq.getAttribute('aria-expanded')!=='true')throw new Error('FAQ failed');await page.keyboard.press('Escape');
await page.goto('http://127.0.0.1:9477/blog/',{waitUntil:'networkidle'});
await page.locator('.xd-stage [data-filter="SEM"] .xd-line').click();if(!(await page.locator('.blog-status').innerText()).includes('No posts'))throw new Error('Filter empty state failed');
await page.locator('.xd-stage [data-filter="View All"] .xd-line').click();await page.locator('.xd-stage [data-view-more] .xd-line').click();if(!(await page.locator('.blog-status').innerText()).includes('all available'))throw new Error('View More failed');
await page.setViewportSize({width:390,height:844});
for(const slug of ['home','about','services','blog','search-engine-marketing']){
 await page.goto('http://127.0.0.1:9477/'+(slug==='home'?'':slug+'/'),{waitUntil:'networkidle'});await page.evaluate(()=>document.fonts.ready);
 const overflow=await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth);if(overflow)throw new Error('Mobile overflow '+slug);
 await page.screenshot({path:path.join(root,`verification/${slug}-mobile.png`),fullPage:true});
}
await page.locator('.menu-toggle').click();if(await page.locator('#mobile-navigation').isHidden())throw new Error('Mobile menu failed');
if(errors.length)throw new Error('Browser errors: '+errors.join('; '));
const report={pages:results,javascriptErrors:errors,faq:true,filters:true,viewMore:true,mobileMenu:true,mobileOverflow:false};
fs.writeFileSync(path.join(root,'verification/browser-checks.json'),JSON.stringify(report,null,2));console.log(JSON.stringify(report));await browser.close();
