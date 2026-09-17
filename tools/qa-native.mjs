import pageFixtures from './page-fixtures.mjs';
import { chromium } from 'playwright';
import fs from 'node:fs';
const theme = 'wordpress/wp-content/themes/papaya-search-child';
const pages = pageFixtures;
const browser = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
});
const page = await browser.newPage({ viewport: { width: 1280, height: 900 } });
const errors = [];
page.on('pageerror', (e) => errors.push(e.message));
const results = [];
page.on('response', (response) => {
  if (response.request().isNavigationRequest() && response.status() >= 400)
    errors.push(`HTTP ${response.status()}: ${response.url()}`);
});
for (const width of [1280, 390]) {
  await page.setViewportSize({ width, height: 900 });
  for (const d of pages) {
    await page.goto('http://127.0.0.1:9477/' + (d.slug === 'home' ? '' : d.slug + '/'), {
      waitUntil: 'networkidle',
    });
    await page.evaluate(() => document.fonts.ready);
    const expected = d.expected;
    const state = await page.evaluate(
      (expected) => ({
        h1: document.querySelectorAll('main h1').length,
        footer: !!document.querySelector('footer'),
        fatal: /Fatal error|There has been a critical error/.test(document.body.textContent),
        svg: document.querySelectorAll('main svg,footer svg').length,
        inline: document.querySelectorAll('[style]').length,
        overflow: document.documentElement.scrollWidth > innerWidth,
        missingFields: expected.filter(
          (k) => !document.querySelector(`[data-field="${k}"],[data-image="${k}"]`),
        ),
        missingImages: [...document.images]
          .filter((i) => i.complete && !i.naturalWidth)
          .map((i) => i.src),
        height: document.body.scrollHeight,
      }),
      expected,
    );
    if (
      !state.footer ||
      state.fatal ||
      state.h1 !== 1 ||
      state.svg ||
      state.inline ||
      state.overflow ||
      state.missingFields.length ||
      state.missingImages.length
    )
      throw Error(JSON.stringify({ slug: d.slug, width, ...state }));
    await page.evaluate(async () => {
      for (const img of document.images) {
        img.loading = 'eager';
      }
      await Promise.all([...document.images].map((img) => img.decode().catch(() => {})));
    });
    const failed = await page
      .locator('img')
      .evaluateAll((images) => images.filter((i) => !i.naturalWidth).map((i) => i.src));
    if (failed.length) throw Error(JSON.stringify(failed));
    await page.screenshot({ path: `verification/native-${d.slug}-${width}.png`, fullPage: true });
    results.push({ slug: d.slug, width, ...state });
  }
}
await page.goto('http://127.0.0.1:9477/search-engine-marketing/', { waitUntil: 'networkidle' });
await page.locator('.faq-item summary').first().click();
if (
  !(await page
    .locator('.faq-item')
    .first()
    .evaluate((e) => e.open))
)
  throw Error('FAQ failed');
await page.keyboard.press('Escape');
await page.locator('.menu-toggle').click();
if (!(await page.locator('#primary-navigation').isVisible()))
  throw Error('Mobile navigation failed');
await page.keyboard.press('Escape');
if (errors.length) throw Error(errors.join('\n'));
fs.writeFileSync(
  'verification/native-checks.json',
  JSON.stringify({ pages: results, errors, faq: true, mobileNavigation: true }, null, 2),
);
console.log(
  'Verified eight semantic HTML pages at desktop and mobile sizes; all ACF fields and images present, no SVG layouts, inline styles, overflow, or JS errors.',
);
await browser.close();
