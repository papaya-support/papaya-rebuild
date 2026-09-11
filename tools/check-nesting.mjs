import {chromium} from 'playwright';
import fs from 'node:fs';
const browser=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});
const page=await browser.newPage();
if(!process.argv[2])throw new Error('Pass the path to the previous stylesheet for comparison.');
const flat=fs.readFileSync(process.argv[2],'utf8');
const designs=JSON.parse(fs.readFileSync('wordpress/wp-content/themes/papaya-search-child/design/pages.json'));
const results=[];
for(const width of [1280,390]){
 await page.setViewportSize({width,height:900});
 for(const d of designs){
  const url='http://127.0.0.1:9477/'+(d.slug==='home'?'':d.slug+'/');
  await page.route('**/assets/site.css?*',r=>r.fulfill({contentType:'text/css',body:flat}));
  await page.goto(url,{waitUntil:'networkidle'});await page.evaluate(async()=>{await document.fonts.ready;await Promise.all([...document.images].map(i=>{i.loading='eager';return i.decode().catch(()=>{});}));await document.fonts.ready;await new Promise(r=>requestAnimationFrame(()=>requestAnimationFrame(r)));});
  const before=await page.screenshot({fullPage:true});
  await page.unroute('**/assets/site.css?*');
  await page.reload({waitUntil:'networkidle'});await page.evaluate(async()=>{await document.fonts.ready;await Promise.all([...document.images].map(i=>{i.loading='eager';return i.decode().catch(()=>{});}));await document.fonts.ready;await new Promise(r=>requestAnimationFrame(()=>requestAnimationFrame(r)));});
  const after=await page.screenshot({fullPage:true});
  let identical=before.equals(after);
  if(!identical){
   // Chromium may rasterize a few antialiased edge pixels differently.
   const difference=await page.evaluate(async({a,b})=>{const load=s=>new Promise(resolve=>{const img=new Image();img.onload=()=>resolve(img);img.src='data:image/png;base64,'+s;});const [x,y]=await Promise.all([load(a),load(b)]);if(x.width!==y.width||x.height!==y.height)return 1;const c=document.createElement('canvas');c.width=x.width;c.height=x.height;const ctx=c.getContext('2d');ctx.drawImage(x,0,0);const first=ctx.getImageData(0,0,c.width,c.height).data;ctx.drawImage(y,0,0);const second=ctx.getImageData(0,0,c.width,c.height).data;let changed=0;for(let i=0;i<first.length;i+=4)if(Math.max(Math.abs(first[i]-second[i]),Math.abs(first[i+1]-second[i+1]),Math.abs(first[i+2]-second[i+2]))>2)changed++;return changed/(first.length/4);},{a:before.toString('base64'),b:after.toString('base64')});
   identical=difference===0;
  }
  if(!identical){fs.writeFileSync(`verification/nesting-${d.slug}-${width}-before.png`,before);fs.writeFileSync(`verification/nesting-${d.slug}-${width}-after.png`,after);}
  results.push({slug:d.slug,width,visuallyMatched:identical});
 }
}
fs.writeFileSync('verification/nesting-checks.json',JSON.stringify(results,null,2));
console.log(JSON.stringify(results));await browser.close();
if(results.some(r=>!r.visuallyMatched))process.exit(1);
