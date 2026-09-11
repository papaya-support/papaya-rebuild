import { chromium } from 'playwright';
import fs from 'node:fs';
const browser=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});
const page=await browser.newPage({viewport:{width:1920,height:1080}});
const pages=JSON.parse(fs.readFileSync('wordpress/wp-content/themes/papaya-search-child/design/pages.json'));
const results=[];
for(const d of pages){await page.goto('http://127.0.0.1:9477/'+(d.slug==='home'?'':d.slug+'/'),{waitUntil:'networkidle'});await page.evaluate(()=>document.fonts.ready);
const result=await page.evaluate(()=>({inlineAttributes:document.querySelectorAll('[style]').length,inlineBlocks:[...document.querySelectorAll('style')].map(e=>e.id),stylesheets:[...document.querySelectorAll('link[rel=stylesheet]')].map(e=>e.href),fonts:document.fonts.check('20px Inter')}));
if(result.inlineAttributes||result.inlineBlocks.length||!result.fonts)throw Error(JSON.stringify({slug:d.slug,...result}));results.push({slug:d.slug,...result});}
await page.goto('http://127.0.0.1:9477/',{waitUntil:'networkidle'});await page.screenshot({path:'verification/css-after.png'});
// Compare the visible desktop result against the pre-refactor screenshot.
const before=fs.readFileSync('verification/css-before.png').toString('base64');const after=fs.readFileSync('verification/css-after.png').toString('base64');
const comparison=await page.evaluate(async({before,after})=>{const load=src=>new Promise(resolve=>{const i=new Image();i.onload=()=>resolve(i);i.src='data:image/png;base64,'+src;});const [a,b]=await Promise.all([load(before),load(after)]);const c=document.createElement('canvas');c.width=a.width;c.height=a.height;const ctx=c.getContext('2d');ctx.drawImage(a,0,0);const x=ctx.getImageData(0,0,c.width,c.height).data;ctx.clearRect(0,0,c.width,c.height);ctx.drawImage(b,0,0);const y=ctx.getImageData(0,0,c.width,c.height).data;let changed=0;for(let i=0;i<x.length;i+=4)if(x[i]!==y[i]||x[i+1]!==y[i+1]||x[i+2]!==y[i+2])changed++;return{changedPixels:changed,totalPixels:x.length/4};},{before,after});
fs.writeFileSync('verification/css-checks.json',JSON.stringify({pages:results,comparison},null,2));console.log(JSON.stringify({pages:results.length,comparison}));await browser.close();
