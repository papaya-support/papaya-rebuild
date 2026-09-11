import {chromium} from 'playwright';
import fs from 'node:fs';
const browser=await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});
const page=await browser.newPage();
const src='data:image/png;base64,'+fs.readFileSync('design-source/preview.png').toString('base64');
const actual='data:image/png;base64,'+fs.readFileSync('verification/search-engine-marketing.png').toString('base64');
const result=await page.evaluate(async([a,b])=>{
 const load=s=>new Promise((resolve,reject)=>{let i=new Image;i.onload=()=>resolve(i);i.onerror=reject;i.src=s});
 const [reference,current]=await Promise.all([load(a),load(b)]);
 const canv=document.createElement('canvas');canv.width=reference.width;canv.height=reference.height;const ctx=canv.getContext('2d',{willReadFrequently:true});
 ctx.drawImage(reference,0,0);const x=ctx.getImageData(0,0,canv.width,canv.height).data;ctx.clearRect(0,0,canv.width,canv.height);ctx.drawImage(current,0,0,canv.width,canv.height);const y=ctx.getImageData(0,0,canv.width,canv.height).data;
 let total=0,close=0;for(let i=0;i<x.length;i+=4){const delta=(Math.abs(x[i]-y[i])+Math.abs(x[i+1]-y[i+1])+Math.abs(x[i+2]-y[i+2]))/3;total+=delta;if(delta<20)close++;}
 return {referenceWidth:canv.width,referenceHeight:canv.height,meanAbsoluteChannelDifference:total/(x.length/4),fractionOfPixelsWithin20Levels:close/(x.length/4),note:'Comparison of embedded XD preview and browser screenshot after resampling. Font antialiasing and preview resampling differ; this is not proof of identical pixels.'};
},[src,actual]);
fs.writeFileSync('verification/visual-comparison.json',JSON.stringify(result,null,2));console.log(result);await browser.close();
