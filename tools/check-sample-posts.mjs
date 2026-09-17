import { chromium } from 'playwright';
import samples from './sample-blog-posts.mjs';
const browser = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
});
const page = await browser.newPage();
try {
  const base = 'http://127.0.0.1:9477/blog/';
  await page.goto(base, { waitUntil: 'domcontentloaded' });
  for (const name of [...new Set(samples.map((s) => s[0]))]) {
    const slug = name.toLowerCase().replaceAll(' ', '-');
    await Promise.all([
      page.waitForURL('**/blog/?blog_category=' + slug, { waitUntil: 'domcontentloaded' }),
      page.locator('.filter-bar').getByRole('link', { name, exact: true }).click(),
    ]);
    await page.locator('footer').waitFor();
    const titles = await page.locator('.post-card h2').allTextContents();
    for (const sample of samples.filter((s) => s[0] === name)) {
      if (!titles.some((t) => t.trim() === sample[1])) throw Error(`${name}: missing ${sample[1]}`);
    }
  }
  await page.goto(base, { waitUntil: 'networkidle' });
  if ((await page.locator('.post-card').count()) !== 9) throw Error('First page');
  await page.screenshot({ path: 'verification/blog-sample-posts.png', fullPage: true });
  await page.locator('[data-view-more]').click();
  await page.waitForFunction(
    () => document.querySelectorAll('[data-blog-grid] .post-card').length === 18,
  );
  await page.setViewportSize({ width: 390, height: 900 });
  await page.goto(base, { waitUntil: 'domcontentloaded' });
  if (await page.evaluate(() => document.documentElement.scrollWidth > innerWidth))
    throw Error('Mobile overflow');
  console.log(
    'Verified 20 sample posts: four per design category, working category links, pagination, and mobile layout.',
  );
} finally {
  await browser.close();
}
