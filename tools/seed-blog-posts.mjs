/** Explicit, repeatable local sample-data import. Does not run during theme activation/deployment. */
import {chromium} from 'playwright';import fs from 'node:fs';import samples from './sample-blog-posts.mjs';
const base=process.env.PAPAYA_WP_URL||'http://127.0.0.1:9477';
const user=process.env.PAPAYA_WP_USER,password=process.env.PAPAYA_WP_PASSWORD;
if(!user||!password)throw Error('Set PAPAYA_WP_USER and PAPAYA_WP_PASSWORD.');
const escape=s=>s.replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;');
const slug=s=>s.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
const entries=samples.map(([category,title,intro,sections],index)=>({category,title,slug:'sample-'+slug(title),excerpt:intro,content:`<p>${escape(intro)}</p>`+sections.map(([heading,body])=>`<h2>${escape(heading)}</h2><p>${escape(body)}</p>`).join('')+'<p><em>Sample article created for the Papaya Search website preview. Review and adapt before using as final editorial content.</em></p>'}));
const browser=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});const page=await browser.newPage();
try {
 await page.route('**/*',route=>new URL(route.request().url()).origin===new URL(base).origin?route.continue():route.abort());
 await page.goto(base+'/wp-login.php',{waitUntil:'domcontentloaded'});
 await page.locator('#user_login').fill(user);await page.locator('#user_pass').fill(password);
 await Promise.all([page.waitForURL('**/wp-admin/**',{waitUntil:'domcontentloaded'}),page.locator('#wp-submit').click()]);
 await page.goto(base+'/wp-admin/post-new.php',{waitUntil:'domcontentloaded'});
 const nonce=await page.evaluate(()=>window.wpApiSettings?.nonce||window.wp?.apiFetch?.nonceMiddleware?.nonce);
 if(!nonce)throw Error('WordPress REST nonce unavailable.');
 const api=async(path,method='GET',data)=>{
  const response=await page.request.fetch(base+'/wp-json/wp/v2/'+path,{method,headers:{'X-WP-Nonce':nonce},...(data?{data}:{})});
  if(!response.ok())throw Error(`${method} ${path}: ${response.status()} ${await response.text()}`);
  return response.json();
 };
 const categories={};
 for(const name of [...new Set(entries.map(e=>e.category))]){
  const found=await api('categories?slug='+slug(name));categories[name]=found[0]?.id||(await api('categories','POST',{name,slug:slug(name)})).id;
 }
 const media=await api('media?per_page=100');
 const placeholder=media.find(m=>m.source_url.includes('35cc479761601be986b3f8891766eec2'));
 const report=[];
 for(const entry of entries){
  const existing=await api('posts?context=edit&status=publish,draft,pending,private,future&slug='+entry.slug);
  if(existing.length){report.push({id:existing[0].id,category:entry.category,title:existing[0].title.raw,status:'existing'});continue;}
  const result=await api('posts','POST',{title:entry.title,slug:entry.slug,excerpt:entry.excerpt,content:entry.content,status:'publish',categories:[categories[entry.category]],...(placeholder?{featured_media:placeholder.id}:{})});
  report.push({id:result.id,category:entry.category,title:entry.title,status:'created'});
 }
 // Portable native WordPress import for a different environment; Git does not transfer the database.
 const cdata=s=>'<![CDATA['+s.replaceAll(']]>',']]]]><![CDATA[>')+']]>';
 const xml=`<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/"><channel><title>Papaya Search Sample Posts</title><link>${escape(base)}</link><description>20 editable sample posts</description><language>en-US</language><wp:wxr_version>1.2</wp:wxr_version><wp:base_site_url>${escape(base)}</wp:base_site_url><wp:base_blog_url>${escape(base)}</wp:base_blog_url>`+entries.map((e,i)=>`<item><title>${cdata(e.title)}</title><dc:creator>${cdata(user)}</dc:creator><content:encoded>${cdata(e.content)}</content:encoded><excerpt:encoded>${cdata(e.excerpt)}</excerpt:encoded><wp:post_id>${10000+i}</wp:post_id><wp:post_name>${cdata(e.slug)}</wp:post_name><wp:status>publish</wp:status><wp:post_type>post</wp:post_type><wp:comment_status>closed</wp:comment_status><wp:ping_status>closed</wp:ping_status><category domain="category" nicename="${slug(e.category)}">${cdata(e.category)}</category></item>`).join('')+'</channel></rss>';
 fs.writeFileSync('deliverables/papaya-sample-posts.xml',xml);
 fs.writeFileSync('verification/sample-posts.json',JSON.stringify(report,null,2));
 for(const [name,id] of Object.entries(categories)){
  const posts=await api('posts?per_page=100&categories='+id);
  if(report.filter(p=>p.category===name).some(p=>!posts.some(post=>post.id===p.id)))throw Error('Missing category assignment: '+name);
 }
 await page.goto(base+'/blog/',{waitUntil:'domcontentloaded'});
 if(await page.locator('.post-card').count()!==9)throw Error('Blog first page should show nine posts.');
 for(const name of Object.keys(categories)){
  await Promise.all([page.waitForURL('**/blog/?blog_category='+slug(name),{waitUntil:'domcontentloaded'}),page.locator('.filter-bar').getByRole('link',{name,exact:true}).click()]);
  if(await page.locator('.post-card').count()<4)throw Error('Missing filtered samples: '+name);
 }
 console.log(JSON.stringify({total:report.length,created:report.filter(p=>p.status==='created').length,categories:Object.keys(categories),perCategory:4,filtersVerified:true,export:'deliverables/papaya-sample-posts.xml'}));
} finally {await browser.close();}
