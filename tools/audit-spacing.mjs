import {chromium} from 'playwright';
import fs from 'node:fs';
const theme='wordpress/wp-content/themes/papaya-search-child';
const spacing=JSON.parse(fs.readFileSync(`${theme}/inc/spacing.json`));
const b=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});const p=await b.newPage();
for(const [slug,cuts] of Object.entries(spacing)){
 const svg=fs.readFileSync(`${theme}/design/${slug}.svg`,'utf8');
 await p.setContent(svg);
 const hits=await p.evaluate(cuts=>cuts.map(([a,b])=>({cut:[a,b],hits:[...document.querySelectorAll('path,circle,ellipse,line,rect')].filter(e=>!e.closest('defs')).map(e=>{const box=e.getBBox(),m=e.getCTM();const p1=new DOMPoint(box.x,box.y).matrixTransform(m),p2=new DOMPoint(box.x+box.width,box.y+box.height).matrixTransform(m);return {tag:e.tagName,y:Math.min(p1.y,p2.y),bottom:Math.max(p1.y,p2.y),h:Math.abs(p2.y-p1.y),w:Math.abs(p2.x-p1.x)};}).filter(r=>r.y<b&&r.bottom>a&&r.h<200&&r.w<1100)})).filter(r=>r.hits.length),cuts);
 if(hits.length) throw new Error(`${slug}: spacing clips artwork ${JSON.stringify(hits)}`);
 const d=JSON.parse(fs.readFileSync(`${theme}/design/${slug}.json`));
 for(const [a,z] of cuts){
  for(const item of [...d.texts.filter(t=>t.scope==='page'),...d.images]){
   if(item.y<z && item.y+item.height>a)throw new Error(`${slug}: spacing clips ${item.key}`);
  }
 }
 console.log(`${slug}: content, images and decorative elements clear`);
}
await b.close();
