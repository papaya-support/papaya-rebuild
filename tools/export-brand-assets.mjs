// One-time export of individual XD illustrations. These are images, never page layouts.
import {chromium} from 'playwright';
import fs from 'node:fs';
const theme='wordpress/wp-content/themes/papaya-search-child';
const regions={home:{'footer-logo':[95,5180,375,5440],'hero-emblem':[460,610,850,910],'service-seo':[160,1730,270,1815],'service-ppc':[440,1730,550,1815],'service-analytics':[715,1730,825,1815],'service-ai':[1000,1730,1110,1815],'testimonial-bird':[35,3510,210,3740]},about:{'papaya-tree':[825,4250,1200,4800]},'case-study-detail':{'result-visibility':[230,1100,350,1190],'result-leads':[570,1100,710,1190],'result-engagement':[930,1100,1050,1190]}};
fs.mkdirSync(`${theme}/assets/illustrations`,{recursive:true});
const b=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});const p=await b.newPage();
for(const [slug,areas] of Object.entries(regions)){
 await p.setContent(fs.readFileSync(`design-source/reference-artboards/${slug}.svg`,'utf8'));
 for(const [name,bounds] of Object.entries(areas)){
  const out=await p.evaluate(bounds=>{const [x,y,right,bottom]=bounds;const nodes=[...document.querySelector('svg').children].filter(e=>e.tagName==='g'&&!e.hasAttribute('data-image')).filter(e=>{const r=e.getBBox(),m=e.getCTM(),a=new DOMPoint(r.x,r.y).matrixTransform(m),b=new DOMPoint(r.x+r.width,r.y+r.height).matrixTransform(m);return a.x>=x&&a.y>=y&&b.x<=right&&b.y<=bottom&&r.width>0&&r.height>0;});if(!nodes.length)return null;let left=Infinity,top=Infinity,rgt=0,btm=0;for(const e of nodes){const r=e.getBBox(),m=e.getCTM(),a=new DOMPoint(r.x,r.y).matrixTransform(m),b=new DOMPoint(r.x+r.width,r.y+r.height).matrixTransform(m);left=Math.min(left,a.x);top=Math.min(top,a.y);rgt=Math.max(rgt,b.x);btm=Math.max(btm,b.y);}return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="${left} ${top} ${rgt-left} ${btm-top}">${nodes.map(e=>e.outerHTML).join('')}</svg>`;},bounds);
  if(!out)throw Error(`No artwork for ${name}`);fs.writeFileSync(`${theme}/assets/illustrations/${name}.svg`,out);console.log(name,out.length);
 }
}
await b.close();
