/** Compare all theme templates against a saved pre-migration CSS bundle. */
import { runCLI } from '@wp-playground/cli';
import { chromium } from 'playwright';
import fs from 'node:fs';
import path from 'node:path';
import assert from 'node:assert/strict';
const before = fs.readFileSync(process.argv[2] || '/tmp/papaya-before-tailwind.css', 'utf8');
const after = fs.readFileSync(
  'wordpress/wp-content/themes/papaya-search-child/assets/site.css',
  'utf8',
);
const content = path.resolve('wordpress/wp-content');
const instance = await runCLI({
  command: 'server',
  port: 9479,
  php: '8.3',
  workers: 1,
  'mount-before-install': [
    'themes/astra',
    'themes/papaya-search-child',
    'plugins/advanced-custom-fields',
    'mu-plugins',
  ].map((p) => ({ hostPath: path.join(content, p), vfsPath: '/wordpress/wp-content/' + p })),
});
let browser;
try {
  const seed = await instance.playground.run({
    code: `<?php require '/wordpress/wp-load.php'; ps_import_design_content(); ps_seed_case_studies();
$id=wp_insert_post(['post_type'=>'post','post_status'=>'publish','post_title'=>'Tailwind verification article','post_content'=>'<h2>Article heading</h2><p>Article text with <a href="#test">a link</a>.</p>']);
$plain=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Plain page','post_content'=>'<h2>Page heading</h2><p>Page text.</p>']);
$cases=get_posts(['post_type'=>'case_study','posts_per_page'=>1]);
echo json_encode([get_permalink($id),get_permalink($plain),get_permalink($cases[0]),get_category_link(1)]);`,
  });
  if (seed.errors || seed.exitCode) throw Error(seed.errors || seed.text);
  const urls = [
    '/',
    '/about/',
    '/services/',
    '/search-engine-marketing/',
    '/blog/',
    '/blog-detail/',
    '/case-studies/',
    '/case-study-detail/',
    ...JSON.parse(seed.text),
    '/?s=Tailwind',
    '/missing-tailwind-page/',
  ];
  browser = await chromium.launch({
    executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
    headless: true,
  });
  const page = await browser.newPage({ reducedMotion: 'reduce' });
  const errors = [];
  page.on('pageerror', (e) => errors.push(e.message));
  let stylesheet = before;
  await page.route('**/assets/site.css*', (route) =>
    route.fulfill({ contentType: 'text/css', body: stylesheet }),
  );
  const capture = async (url, open = false) => {
    await page.goto(url.startsWith('http') ? url : 'http://127.0.0.1:9479' + url);
    await page.evaluate(async () => {
      await document.fonts.ready;
      await Promise.all(
        [...document.images].map(async (i) => {
          i.loading = 'eager';
          // Switching from lazy loading may select a new responsive image source.
          for (let attempt = 0; attempt < 5; attempt++) {
            try {
              await i.decode();
              break;
            } catch {
              await new Promise((resolve) => requestAnimationFrame(resolve));
            }
          }
        }),
      );
    });
    await page.evaluate(
      () => new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve))),
    );
    if (open) {
      await page.locator('.menu-toggle').click();
      // Opening the menu can trigger its previously hidden bold font face.
      await page.evaluate(async () => {
        await document.fonts.ready;
        await new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve)));
      });
    }
    const metrics = await page.evaluate(() =>
      [...document.querySelectorAll('header,header *,main,main *,footer,footer *')].map((el) => {
        const s = getComputedStyle(el),
          r = el.getBoundingClientRect();
        const props = [
          'display',
          'position',
          'color',
          'backgroundColor',
          'fontFamily',
          'fontSize',
          'fontWeight',
          'lineHeight',
          'textAlign',
          'padding',
          'margin',
          'border',
          'gap',
          'gridTemplateColumns',
          'alignItems',
          'justifyContent',
          'width',
          'height',
          'objectFit',
        ];
        return [
          el.tagName,
          el.className,
          [r.x, r.y, r.width, r.height],
          props.map((p) => s[p]),
          el.tagName === 'IMG' ? [el.currentSrc, el.naturalWidth, el.naturalHeight] : null,
        ];
      }),
    );
    // Chromium can rasterize resized placeholder images differently between loads.
    // Compare image sources/dimensions above and mask only their pixels here.
    const screenshot = await page.screenshot({
      fullPage: true,
      animations: 'disabled',
      mask: [page.locator('img')],
    });
    return { metrics, screenshot };
  };
  const comparePixels = async (first, second) => {
    if (first.equals(second)) return true;
    return page.evaluate(
      async (sources) => {
        const frames = [];
        for (const source of sources) {
          const image = new Image();
          image.src = source;
          await image.decode();
          const canvas = document.createElement('canvas');
          canvas.width = image.width;
          canvas.height = image.height;
          const context = canvas.getContext('2d');
          context.drawImage(image, 0, 0);
          frames.push(context.getImageData(0, 0, image.width, image.height));
        }
        if (frames[0].width !== frames[1].width || frames[0].height !== frames[1].height)
          return false;
        let changed = 0;
        for (let i = 0; i < frames[0].data.length; i += 4) {
          let different = false;
          for (let channel = 0; channel < 4; channel++) {
            const delta = Math.abs(frames[0].data[i + channel] - frames[1].data[i + channel]);
            if (delta > 4) return false;
            different ||= delta > 0;
          }
          if (different) changed++;
        }
        // Permit only sparse, very small antialiasing variation at rounded edges.
        return changed / (frames[0].width * frames[0].height) < 0.0001;
      },
      [first, second].map((buffer) => 'data:image/png;base64,' + buffer.toString('base64')),
    );
  };
  let checked = 0;
  for (const url of urls)
    for (const width of [390, 768, 1280]) {
      await page.setViewportSize({ width, height: 900 });
      stylesheet = before;
      const old = await capture(url);
      stylesheet = after;
      const current = await capture(url);
      assert.deepEqual(
        current.metrics,
        old.metrics,
        `Computed styles/layout changed: ${url} ${width}`,
      );
      if (!current.screenshot.equals(old.screenshot)) {
        fs.writeFileSync('/tmp/papaya-tailwind-before.png', old.screenshot);
        fs.writeFileSync('/tmp/papaya-tailwind-after.png', current.screenshot);
      }
      assert.ok(
        await comparePixels(old.screenshot, current.screenshot),
        `Pixels changed: ${url} ${width}`,
      );
      checked++;
    }
  console.log(`${checked} page comparisons passed; checking expanded mobile menus.`);
  for (const width of [390, 768]) {
    await page.setViewportSize({ width, height: 900 });
    stylesheet = before;
    const old = await capture('/', true);
    stylesheet = after;
    const current = await capture('/', true);
    assert.deepEqual(current.metrics, old.metrics);
    assert.ok(await comparePixels(old.screenshot, current.screenshot));
    checked++;
  }
  assert.deepEqual(errors, []);
  console.log(
    `${checked} comparisons passed: identical computed styles, layout and image sources; screenshots match within strict antialiasing tolerance, including mobile menus.`,
  );
} finally {
  await browser?.close();
  await instance[Symbol.asyncDispose]();
}
