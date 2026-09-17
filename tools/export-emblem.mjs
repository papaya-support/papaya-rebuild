import { chromium } from 'playwright';
import fs from 'node:fs';
const theme = 'wordpress/wp-content/themes/papaya-search-child';
const browser = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
});
const page = await browser.newPage({ viewport: { width: 900, height: 900 }, deviceScaleFactor: 1 });
await page.setContent(
  '<style>body{margin:0}svg{width:900px;height:900px;display:block}</style>' +
    fs.readFileSync(`${theme}/assets/illustrations/hero-emblem.svg`, 'utf8'),
);
await page
  .locator('svg')
  .screenshot({ path: `${theme}/assets/images/hero-emblem.png`, omitBackground: true });
await browser.close();
