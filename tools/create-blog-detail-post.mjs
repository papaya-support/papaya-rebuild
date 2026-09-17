/** Copy the current Blog Detail article into a native, editable sticky Post. */
import { chromium } from 'playwright';
import fs from 'node:fs';
const base = process.env.PAPAYA_WP_URL || 'http://127.0.0.1:9477';
const user = process.env.PAPAYA_WP_USER,
  password = process.env.PAPAYA_WP_PASSWORD;
if (!user || !password) throw Error('Set PAPAYA_WP_USER and PAPAYA_WP_PASSWORD.');
const browser = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
});
const page = await browser.newPage();
try {
  await page.goto(base + '/blog-detail/', { waitUntil: 'domcontentloaded' });
  const article = await page.evaluate(() => {
    const title = document.querySelector('main h1').textContent.trim();
    const body = document
      .querySelector('.section-blog-detail-article-body .article-body')
      .cloneNode(true);
    const heading = document
      .querySelector('.section-blog-detail-local-rankings .section-title')
      .cloneNode(true);
    const split = document
      .querySelector('.section-blog-detail-local-rankings .split')
      .cloneNode(true);
    body.append(heading, split);
    body.querySelectorAll('[data-field],[data-image]').forEach((e) => {
      e.removeAttribute('data-field');
      e.removeAttribute('data-image');
    });
    body.querySelectorAll('img').forEach((e) => {
      const name = new URL(e.src).pathname.split('/').pop();
      e.setAttribute('src', '/wp-content/themes/papaya-search-child/assets/images/' + name);
      e.removeAttribute('srcset');
      e.removeAttribute('sizes');
    });
    return {
      title,
      content: body.innerHTML,
      excerpt: body.querySelector('p')?.textContent || '',
      image: document.querySelector('.article-featured')?.src,
    };
  });
  await page.route('**/*', (route) =>
    new URL(route.request().url()).origin === new URL(base).origin
      ? route.continue()
      : route.abort(),
  );
  await page.goto(base + '/wp-login.php', { waitUntil: 'domcontentloaded' });
  await page.locator('#user_login').fill(user);
  await page.locator('#user_pass').fill(password);
  await Promise.all([
    page.waitForURL('**/wp-admin/**', { waitUntil: 'domcontentloaded' }),
    page.locator('#wp-submit').click(),
  ]);
  await page.goto(base + '/wp-admin/post-new.php', { waitUntil: 'domcontentloaded' });
  const nonce = await page.evaluate(
    () => window.wpApiSettings?.nonce || window.wp?.apiFetch?.nonceMiddleware?.nonce,
  );
  if (!nonce) throw Error('No REST nonce');
  const api = async (path, method = 'GET', data) => {
    const r = await page.request.fetch(base + '/wp-json/wp/v2/' + path, {
      method,
      headers: { 'X-WP-Nonce': nonce },
      ...(data ? { data } : {}),
    });
    if (!r.ok()) throw Error(`${r.status()} ${await r.text()}`);
    return r.json();
  };
  const slug = article.title
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '');
  const categories = await api('categories?slug=seo');
  const category = categories[0] || (await api('categories', 'POST', { name: 'SEO', slug: 'seo' }));
  const existing = await api(
    'posts?context=edit&status=publish,draft,pending,private,future&slug=' + slug,
  );
  let post;
  if (existing.length) {
    post = await api('posts/' + existing[0].id, 'POST', { sticky: true });
  } else {
    const media = await api('media?per_page=100');
    const featured = media.find((m) => m.source_url === article.image);
    post = await api('posts', 'POST', {
      title: article.title,
      slug,
      content: article.content,
      excerpt: article.excerpt,
      status: 'publish',
      sticky: true,
      categories: [category.id],
      ...(featured ? { featured_media: featured.id } : {}),
    });
  }
  fs.writeFileSync(
    'verification/blog-detail-post.json',
    JSON.stringify(
      { id: post.id, title: article.title, url: post.link, sticky: post.sticky },
      null,
      2,
    ),
  );
  const c = (s) => '<![CDATA[' + s.replaceAll(']]>', ']]]]><![CDATA[>') + ']]>';
  fs.writeFileSync(
    'deliverables/papaya-blog-detail-post.xml',
    `<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/"><channel><title>Papaya Blog Detail Post</title><link>${base}</link><description>Native Blog Detail article</description><wp:wxr_version>1.2</wp:wxr_version><wp:base_site_url>${base}</wp:base_site_url><wp:base_blog_url>${base}</wp:base_blog_url><item><title>${c(article.title)}</title><dc:creator>${c(user)}</dc:creator><content:encoded>${c(article.content)}</content:encoded><excerpt:encoded>${c(article.excerpt)}</excerpt:encoded><wp:post_id>${post.id}</wp:post_id><wp:post_name>${c(slug)}</wp:post_name><wp:status>publish</wp:status><wp:post_type>post</wp:post_type><wp:is_sticky>1</wp:is_sticky><category domain="category" nicename="seo">SEO</category></item></channel></rss>`,
  );
  await page.goto(base + '/blog/', { waitUntil: 'domcontentloaded' });
  if ((await page.locator('.post-card').first().getAttribute('data-post-id')) !== String(post.id))
    throw Error('Article is not first');
  await page.goto(post.link, { waitUntil: 'networkidle' });
  if (!(await page.locator('main').textContent()).includes('Why Local SEO Matters'))
    throw Error('Article body missing');
  const broken = await page
    .locator('main img')
    .evaluateAll((images) => images.filter((i) => i.complete && !i.naturalWidth).map((i) => i.src));
  if (broken.length) throw Error('Broken images: ' + broken.join(','));
  console.log(
    JSON.stringify({
      id: post.id,
      title: article.title,
      url: post.link,
      sticky: post.sticky,
      firstOnBlog: true,
    }),
  );
} finally {
  await browser.close();
}
