import {chromium} from 'playwright';import fs from 'node:fs';
const browser=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});const page=await browser.newPage();const base='http://127.0.0.1:9477';const errors=[];
page.on('pageerror',e=>errors.push(e.message));page.on('response',r=>{if(r.status()>=500)errors.push(`${r.status()} ${r.url()}`);});
try {
await page.goto(base+'/blog/',{waitUntil:'networkidle'});
if(await page.locator('.page-numbers').count())throw Error('Numbered pagination remains');
const first=await page.locator('.post-card').first().getAttribute('data-post-id');
await page.route('**/*blog_page=2*',route=>route.fulfill({status:503,body:'Temporary test failure'}),{times:1});
await page.locator('[data-view-more]').click();await page.getByText('Unable to load more posts. Please try again.').waitFor();
if(await page.locator('.post-card').count()!==9)throw Error('Failure changed cards');
errors.length=0;
await page.locator('[data-view-more]').click();await page.waitForFunction(()=>document.querySelectorAll('[data-blog-grid] .post-card').length===18);
await page.locator('[data-view-more]').click();await page.waitForFunction(()=>document.querySelector('[data-view-more]').hidden);
const ids=await page.locator('[data-blog-grid] .post-card').evaluateAll(cards=>cards.map(c=>c.dataset.postId));
if(ids.length!==new Set(ids).size||ids[0]!==first)throw Error('Duplicate cards or sticky ordering');
await page.locator('.filter-bar').getByRole('link',{name:'SEO',exact:true}).click();await page.waitForLoadState('domcontentloaded');
await page.locator('footer').waitFor();if(await page.locator('[data-view-more]').count())throw Error('Unneeded View More for short category');
const articles=await page.request.get(base+'/wp-json/wp/v2/posts?per_page=100&_fields=id,link');const posts=await articles.json();
for(const width of [1280,390]) {
 await page.setViewportSize({width,height:900});await page.goto(base+'/blog-detail/',{waitUntil:'networkidle'});
 const reference=await page.locator('.article-heading').evaluate(e=>{let r=e.getBoundingClientRect();return {width:r.width,left:r.left,font:getComputedStyle(e.querySelector('h1')).fontSize};});
 for(const post of posts){
  const response=await page.goto(post.link,{waitUntil:'domcontentloaded'});if(response.status()!==200)throw Error('Article HTTP error');await page.evaluate(()=>document.fonts.ready);
  for(const selector of ['main.page-blog-detail','.article-heading .breadcrumb','.article-heading h1','.article-featured','.section-blog-detail-article-body','.section-blog-detail-related-posts','footer'])if(!await page.locator(selector).count())throw Error(`${post.id} missing ${selector}`);
  const state=await page.locator('.article-heading').evaluate(e=>{let r=e.getBoundingClientRect();return {width:r.width,left:r.left,font:getComputedStyle(e.querySelector('h1')).fontSize};});
  if(JSON.stringify(reference)!==JSON.stringify(state))throw Error('Heading layout differs from Blog Detail');
  if(await page.locator(`.related-posts [data-post-id="${post.id}"]`).count())throw Error('Self-related article');
  if(await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth))throw Error('Article overflow');
 }
 await page.screenshot({path:`verification/single-post-design-${width}.png`,fullPage:true});
}
if(errors.length)throw Error(errors.join('\n'));
console.log(`Verified View More append/retry/end state, sticky order, category reset, and Blog Detail layout on ${posts.length} native articles at desktop and mobile widths.`);
} finally {await browser.close();}
