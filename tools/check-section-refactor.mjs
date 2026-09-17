import { chromium } from 'playwright';
import fs from 'node:fs';
const browser = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
});
const page = await browser.newPage();
const output = {};
for (const slug of [
  'home',
  'about',
  'services',
  'search-engine-marketing',
  'blog',
  'blog-detail',
  'case-studies',
  'case-study-detail',
])
  for (const width of [1280, 768, 390]) {
    await page.setViewportSize({ width, height: 900 });
    await page.goto(`http://127.0.0.1:9477/${slug === 'home' ? '' : slug}`);
    await page.evaluate(async () => {
      await document.fonts.ready;
      await Promise.all(
        [...document.images].map((i) => {
          i.loading = 'eager';
          return i.decode().catch(() => {});
        }),
      );
    });
    output[`${slug}-${width}`] = await page.evaluate(() => {
      const root = document.querySelector('main');
      const walk = (e) =>
        e.nodeType === 3
          ? e.textContent.replace(/\s+/g, ' ').trim()
          : [
              e.tagName,
              [...e.attributes].map((a) => [a.name, a.value]),
              [...e.childNodes]
                .filter((n) => n.nodeType === 1 || (n.nodeType === 3 && n.textContent.trim()))
                .map(walk),
            ];
      return walk(root);
    });
  }
const path = 'verification/section-refactor-baseline.json';
if (process.argv.includes('--baseline')) fs.writeFileSync(path, JSON.stringify(output));
else {
  const before = JSON.parse(fs.readFileSync(path));
  for (const key of Object.keys(output))
    if (JSON.stringify(before[key]) !== JSON.stringify(output[key]))
      throw Error(`DOM changed: ${key}`);
  console.log('Identical rendered HTML on eight pages at three viewport sizes.');
}
await browser.close();
