import pageFixtures from './page-fixtures.mjs';
import { chromium } from 'playwright';
import fs from 'node:fs';
const browser = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
});
const page = await browser.newPage({ viewport: { width: 1920, height: 1080 } });
const designs = pageFixtures;
const results = [];
async function check(slug, width) {
  await page.setViewportSize({ width, height: 1080 });
  await page.goto(`http://127.0.0.1:9477/${slug === 'home' ? '' : slug + '/'}`, {
    waitUntil: 'networkidle',
  });
  const result = await page.evaluate(() => {
    const v = document.querySelector('main'),
      s = document.querySelector('.site-footer');
    return {
      width: innerWidth,
      viewportWidth: v.getBoundingClientRect().width,
      stageLeft: s.getBoundingClientRect().left,
      stageRight: s.getBoundingClientRect().right,
      overflow: document.documentElement.scrollWidth > innerWidth,
      mobile: matchMedia('(max-width:767px)').matches,
    };
  });
  if (
    result.overflow ||
    (!result.mobile && (Math.abs(result.stageLeft) > 1 || Math.abs(result.stageRight - width) > 1))
  )
    throw new Error(JSON.stringify({ slug, ...result }));
  results.push({ slug, ...result });
}
for (const d of designs) await check(d.slug, 1920);
for (const width of [2560, 1280, 1024, 390]) await check('home', width);
await page.setViewportSize({ width: 1920, height: 1080 });
await page.goto('http://127.0.0.1:9477/', { waitUntil: 'networkidle' });
await page.screenshot({ path: 'verification/home-full-width.png' });
fs.writeFileSync('verification/full-width-checks.json', JSON.stringify(results, null, 2));
console.log(
  'Passed: all eight pages fill 1920px; home also checked at 2560, 1280, 1024, and 390px.',
);
await browser.close();
