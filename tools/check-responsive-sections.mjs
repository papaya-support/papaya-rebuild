import { chromium } from 'playwright';
const browser = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
});
const page = await browser.newPage();
for (const slug of [
  '',
  'about',
  'services',
  'search-engine-marketing',
  'blog',
  'blog-detail',
  'case-studies',
  'case-study-detail',
]) {
  let desktop;
  for (const width of [1280, 1024, 768, 390]) {
    await page.setViewportSize({ width, height: 900 });
    await page.goto(`http://127.0.0.1:9477/${slug}`);
    await page.evaluate(() => document.fonts.ready);
    const data = await page.evaluate(() => ({
      colors: [
        ...document.querySelectorAll('main section, main h1, main h2, main .prose, .site-footer'),
      ].map((e) => [getComputedStyle(e).color, getComputedStyle(e).backgroundColor]),
      bad: [...document.querySelectorAll('.split')].filter((e) => {
        const image = e.querySelector(':scope > .split-media'),
          copy = e.querySelector(':scope > .split-copy');
        return (
          image && copy && image.getBoundingClientRect().top >= copy.getBoundingClientRect().top
        );
      }).length,
    }));
    if (width === 1280) desktop = JSON.stringify(data.colors);
    else {
      if (JSON.stringify(data.colors) !== desktop) throw Error(`Colors differ ${slug} ${width}`);
      if (data.bad) throw Error(`Image order ${slug} ${width}`);
    }
  }
}
await page.goto('http://127.0.0.1:9477/');
await page.locator('.hero-emblem').screenshot({ path: 'verification/hero-emblem-fixed.png' });
console.log('All eight pages: consistent colors and image-first sections at 1024, 768 and 390px.');
await browser.close();
