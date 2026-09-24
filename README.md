# Papaya Search — WordPress site

The local `wordpress/` folder is a complete WordPress installation with Astra, Advanced Custom Fields, and the Papaya Search child theme. Git tracks the custom theme and development tools; Pressable supplies WordPress, Astra, and ACF separately.

## PHP templates and HTML layout

Version 2.0.0 replaces the SVG page renderer with eight normal WordPress PHP templates. Each page uses `get_header()`, named `get_template_part()` sections, and `get_footer()`. Sections contain semantic HTML and explicit calls to ACF-backed content helpers. CSS Grid, Flexbox, normal document flow, and media queries provide the responsive layout. Text can grow without being limited to a fixed XD artboard.

The XD colors, local fonts, section order, images, illustrations, menus, and CTA treatments are retained. Logos and individual decorative illustrations are standalone image assets. No full-page SVGs, SVG viewBox slicing, fixed coordinate layout, duplicate mobile content, or inline styles render the pages.

Inside `wordpress/wp-content/themes/papaya-search-child/`:

- `page-templates/`: Home, About, Services, SEM, Blog, Blog Article, Case Studies, and Case Study Detail.
- `template-parts/sections/`: shared PHP sections: `hero.php`, `banner.php`, `content.php`, `image-text.php`, `testimonial.php`, `post-grid.php`, `service-grid.php`, and `statistics.php`. Unique layouts such as the team and process sections remain separate.
- `template-parts/layout/`: shared HTML header and footer.
- `inc/template-tags.php`: reads saved ACF values, escapes output, and renders text, links, and images.
- `inc/default-content.php`: initial content fallbacks; no layout coordinates.
- `inc/fields.php` and `acf-import/field-groups.json`: one-time installation of editable ACF database groups.
- `inc/setup.php`: preserves existing content when importing initial pages, media, and menus.
- `assets/site.css`: one compiled Tailwind stylesheet with local fonts and the existing nested XD rules.
- `assets/site.js`: mobile navigation, accessible FAQ interactions, and blog filtering.
- `inc/initial-content.php`: one-time PHP seed for pages, original text, images, and menu setup. No initial-content JSON is loaded.

Original artboards and extracted reference data are archived outside the theme in the untracked `design-source/` folder. They are not loaded by WordPress.

## Editing content

Use **Pages → Edit** for page text, images, link destinations, FAQ answers, and blog-card categories. All existing field names and keys are retained, so existing content survives the template rebuild.

The nine groups appear in **ACF → Field Groups** and are editable there. On a new installation, the theme creates missing groups once; later group edits are not overwritten. ACF Free is sufficient. Keep field names and keys unchanged when editing definitions. Database changes are not automatically committed to Git.

Use **Site Content** for footer headings, copyright, the footer CTA label, and default booking/contact destinations. The default booking link is the agreed phone number until replaced.

Use **Appearance → Menus** for Header Navigation, Footer Navigation, Footer Services, Footer Contact, and Footer Social Links. The header CTA item uses `menu-cta`; social items use `social-facebook`, `social-twitter`, or `social-linkedin` in their CSS Classes field. Desktop and mobile use the same menu and content markup.

The placeholder images, sample team copy, and example article cards are supplied by the XD. Blog filters work with the nine editable cards; View More reports when all available cards are displayed. FAQ answers can be supplied in ACF; unanswered questions link to the configured contact destination.

## Installation

1. For a standalone host, configure the database settings in `wordpress/wp-config.php`, upload the WordPress folder contents, and complete WordPress installation. Use your own administrator credentials. Pressable already supplies this installation.
2. Install Astra and ACF if needed; activate ACF and **Papaya Search Child**.
3. On a fresh site, use **Appearance → Papaya Setup → Import XD Pages & Content** to create the pages, field values, media, and native menus. Save permalinks if needed.
4. On an existing installation, deploy the new child theme without reimporting or resetting content. Existing template assignments and ACF data remain connected.

See `DEPLOYMENT.md` for the GitHub-to-Pressable paths. PHP 8.0+ is required; PHP 8.3 is tested.

## Development and verification

Edit section PHP files directly. Shared and page-specific rules in `tools/styles/base.css` use Tailwind `@apply` while retaining semantic template classes. Configure Tailwind in `tools/styles/tailwind.css`, then compile it with the local fonts:

```sh
npm ci --prefix tools
python3 tools/build-css.py
```

No frontend build step runs on Pressable. Commit the PHP/JS changes and generated stylesheet together.

Local preview and checks:

```sh
npm ci --prefix tools
node tools/preview.mjs
node tools/qa.mjs
node tools/check-acf-install.mjs
python3 tools/package.py
```

The preview opens at `http://127.0.0.1:9477/` and uses WordPress Playground/SQLite for local testing. The deployable installation uses MySQL/MariaDB. Its local database is recreated on preview restart, so migrate dashboard edits separately when needed.

The browser check covers all eight pages at desktop and mobile sizes, field/image presence, native HTML rendering, overflow, JavaScript errors, menus, FAQ controls, and filters. The isolated installation check verifies all 267 ACF field definitions, PHP syntax, content preservation, and an ACF edit/render/restore round trip. Downloads are rebuilt in `deliverables/`.

The ACF field-group JSON is a schema installer, not a page-content source. It is only needed to create missing groups on a fresh site. Existing installations use the database groups and skip the installer; do not reimport over customized groups. A database migration that already includes the ACF groups also removes the need for an import on the destination.

## ACF editor order and rich text (2.1.0)

Open the dashboard once after deployment to apply the one-time field update. Fields follow each page’s PHP sections from top to bottom; images/alt text, buttons/URLs, FAQ questions/answers and card metadata stay together. All 222 original textarea fields become WYSIWYG editors. Field names, keys, IDs and saved values stay intact. Later dashboard changes are not reset.

Descriptions support paragraphs, lists, links and emphasis. Headings and button labels support inline emphasis without invalid paragraph nesting. Editor text alignment is rendered with CSS classes. The migration updates the existing ACF database posts and does not register code-only groups or require a manual reimport. Its original field definitions are retained in a non-autoloaded WordPress option for recovery.

## Media Library alt text (2.1.1)

Image alt text is managed under **Media → Library → Alt Text**. The 42 separate page alt-text fields have been removed. The theme reads the selected attachment’s alt text directly, so Media Library edits apply wherever that image appears. An empty library alt stays empty. After deployment, open WordPress admin once to remove the old field definitions; existing page metadata is preserved but no longer used.

### Version 2.2.0
The Home hero emblem is an ACF Media Library image. Split image/text sections stack image-first at 1024px and below, using the same colors at every breakpoint. Short headings, button labels and step numbers use text inputs; body copy retains WYSIWYG editors. Open WordPress admin once after deployment to apply the existing-site field migration; no manual field import is required. Existing values are preserved, with original rich markup backed up when converted to plain text.

### Reusing sections (2.3.0)
Each page calls shared sections through WordPress `get_template_part($path, null, $args)`. The arguments map that page’s existing ACF field names to the shared markup; they do not store content or inline styling. For example:

```php
get_template_part('template-parts/sections/content', null, [
    'class' => 'section cream',
    'container_class' => 'container intro center',
    'items' => [
        ['type' => 'text', 'field' => 'services_1d102d9577a4', 'tag' => 'h1'],
        ['type' => 'text', 'field' => 'services_c0d7b44ec821', 'tag' => 'div', 'class' => 'prose'],
    ],
]);
```

Use `image-text.php` for ordered media/copy columns, `post-grid.php` for linked cards, and `service-grid.php` for service cards. The common `components/content-items.php` handles ACF text, image and button output through the existing escaping and formatting helpers. Styling stays in CSS. ACF field names, database groups and values are unchanged, so this refactor needs no database migration. The explicit `inc/acf-editor-order.php` map preserves field order independently of template argument order.

### Dynamic Blog (2.4.0)
The Blog page keeps its ACF hero and introduction. Cards now come from published WordPress Posts, newest first, with featured images, titles, excerpts, permalinks and assigned Categories. Manage them under Posts → All Posts and Posts → Categories. Categories with published content appear automatically in the filter bar. Category links and nine-post pagination work without JavaScript; changing category resets pagination. Parent category filters include descendants. Posts without featured images omit the image, and empty filters show an empty-state message.

Open WordPress admin once after deployment to retire the obsolete static Blog card/filter ACF editors. Their old metadata is retained; no sample articles are published or converted automatically. The native `single.php` template displays the linked post content. Run `node tools/check-dynamic-blog.mjs` to test with an isolated set of published/draft posts and multiple categories.

### Blog error fix (2.4.1)
The post-list section uses its own query variable instead of WordPress’s global `$posts`. This prevents the main page loop from failing after cards render. Browser checks now verify HTTP status and footer completion as well as visible content. No database migration is required for this fix.

### Sample Blog content
`tools/sample-blog-posts.mjs` contains 20 editorial samples, four each for Digital Marketing, SEO, SEM, Wordpress and Papaya HQ. They are created as native published Posts by the explicit `tools/seed-blog-posts.mjs` utility, not loaded by the theme. Set `PAPAYA_WP_USER` and `PAPAYA_WP_PASSWORD` in the process environment before running it. The default target is the local preview; `PAPAYA_WP_URL` selects another explicitly authorized target. Matching sample slugs are skipped, preserving existing edits.

The import also writes `deliverables/papaya-sample-posts.xml`, which can be imported on Pressable using Tools → Import → WordPress. Each article is marked as sample editorial content for review. Git pushes do not copy the local WordPress database. The XML transfers article text and category assignments; it does not transfer featured-image attachments.

### Default post image and featured article (2.5.0)
Posts without a featured image display the original XD placeholder on cards and single-post pages. Existing featured images retain their Media Library metadata. The Blog query orders native sticky posts first while preserving category restrictions and nine-post pagination, without repeating the pinned article on later pages. Editors can change pinning through the WordPress post's Sticky setting.

`tools/create-blog-detail-post.mjs` copies the current Blog Detail article into a native SEO Post, preserves its text and in-body diagram, and makes it sticky. It requires the same environment credentials as the sample importer and skips overwriting an existing article with the same slug. The new post is editable through the standard Posts editor; the original design-reference page remains available. `deliverables/papaya-blog-detail-post.xml` transfers its text, category and sticky status to another WordPress site. In-body artwork uses the installed child theme's assets. Featured-image attachments are not included in the XML; the default image is used until one is selected.

### Shared single-post design and View More (2.6.0)
Every native Post uses the Blog Detail design: breadcrumb/title section, full-width featured image, constrained article body, and three related cards. Content remains editable in the Posts editor. Related posts prefer shared categories, fill remaining slots with recent posts, and exclude the current article. Missing featured images use the XD placeholder.

Blog pagination displays the design's View More button instead of page numbers. With JavaScript, it appends the next nine matching posts, keeps the current category, prevents duplicate requests/cards, announces loading or errors, and hides when no pages remain. Without JavaScript, the same link opens the next page. A failed request leaves the existing cards intact and allows retrying. `tools/check-article-layout-and-more.mjs` verifies these behaviors and compares every local native article's heading layout with the reference Blog Detail page at desktop/mobile widths.

Version 2.6.1 changes the shared `.container` width to 80% above 1024px. Tablet/mobile gutters and explicitly narrow text sections retain their existing sizing.

### Semantic markup and accessibility (2.7.0)

Page templates and native posts render one H1. Empty page titles have a fallback. Editorial H1s and skipped levels are normalized on output; the saved ACF/post content and original heading typography are preserved. Default pages, search, archives, and 404s use the child theme's semantic layout. Keep new section headings descriptive and add subsections under their parent heading.

The mobile menu supports Escape focus restoration and remains available without JavaScript. The skip link focuses the main landmark. Shared Read More/Learn More links include their heading context in accessible labels. Media Library images have responsive sources when WordPress supplies image sizes; local fallback images have dimensions, and native article featured images load eagerly. Alt text continues to come from the Media Library.

Run `node tools/check-accessibility.mjs` after `npm ci --prefix tools`. It creates disposable WordPress fixtures and checks 13 routes at desktop/mobile widths: all eight design templates, a native post, a default page, category archive, search, and 404. The report is saved to `verification/accessibility-audit.json`. It checks one nonempty H1, heading order, titles, language, overflow, axe accessibility rules, keyboard skip/menu controls, and no-JavaScript navigation. PHP/ACF regressions: `node tools/check-acf-install.mjs`; Blog regressions: `node tools/check-dynamic-blog.mjs`.

Validation: 26 viewport checks passed structural and non-contrast accessibility checks. WordPress title tags and singular canonical URLs are present. Exact XD colors were explicitly retained: axe reports contrast failures for some orange headings on cream and small orange text/breadcrumbs on white. The test reports these exceptions; they prevent claiming full WCAG AA compliance. Automated checks do not certify accessibility, rankings, or Core Web Vitals. Production speed depends on hosting, caching, plugins, and media; informative image descriptions still require appropriate Media Library alt text. Existing SEO-plugin metadata and indexing settings are not overwritten.

### Approved brand palette (2.8.0)

The shared CSS, Astra global palette, block-editor swatches, ACF visual-editor swatches, and custom vector artwork use Primary green `#003E3F`, Sage `#718A6B`, Light green `#BFD9BA`, Off-white background `#FBF2E6`, Orange accent `#E76025`, Light orange `#EC882D`, and Peach `#DE8D6D`. The orange table value was confirmed by the user. Default page backgrounds remain white; the off-white token applies only to designated cream sections. Neutral body copy, shadows, photos, and gray placeholder imagery retain their purpose. This approved palette supersedes the older XD color approximations.

After deploying, visit WordPress admin once to refresh the original imported hero emblem. The migration only updates the known original file, preserves the attachment ID and Media Library alt text, and leaves replacement artwork alone. Test palette consistency and that migration with `node tools/check-brand.mjs`. The legacy PNG in `tools/fixtures` is a test fixture, not deployed theme artwork. Exact approved colors remain authoritative; no full contrast-compliance claim is made.

### Page and section responsibilities

The eight page templates and `single.php` contain only the WordPress loop, main landmark, and ordered `get_template_part()` calls. A short `variant` identifies the section's page-specific ACF mapping; the reusable section file owns that mapping and its HTML layout. For example, Home calls the shared `image-text` section for its search strategy, search trends, and closing CTA. The field names, saved content, shared rendering helpers, CSS classes, and section order remain unchanged.

`template-parts/sections/blog-listing.php` owns the Blog query and passes the same listing context to its filters and post grid. Native post featured-image and body markup live in `article-image.php` and `article-body.php`. Shared sections still accept explicit arguments for existing callers.

### Case Studies in ACF

After deploying, open the WordPress dashboard as an administrator. The theme installs a native, editable **ACF → Post Types → Case Studies** definition once. Manage entries under **Case Studies → Add New**. Their URLs use `/case-studies/example/`; the existing `/case-studies/` landing page stays in place.

**ACF → Field Groups → Case Study Detail — Page Content** applies to both the existing detail page and the new Case Studies post type. Its 16 fields follow the design order: breadcrumb, title, featured image, introduction, three results, summary, testimonial, challenge, follow-up and performance chart. Short labels use text fields, paragraphs use WYSIWYG editors, and image alt text comes from the Media Library. The post title and featured image provide fallbacks when their corresponding ACF fields are empty. Unfilled case study fields do not display sample client claims.

The post type and field group remain editable in ACF after installation; the installer does not overwrite later changes. Singular entries reuse the existing detail section templates. The landing-page grid automatically lists every published Case Study, newest first, using its detail title, featured image and excerpt. Drafts are excluded. Nine clearly labeled fictional samples are created once when an administrator opens the dashboard after deployment; edit or delete them in Case Studies. Subsequent setup runs preserve sample edits and do not recreate deleted entries.

### Tailwind CSS build

Tailwind CSS and its CLI are pinned to 4.3.3 in `tools/package-lock.json`. Run `npm ci --prefix tools` once, then `python3 tools/build-css.py` (or `npm run build:css --prefix tools`) after CSS changes. Commit the generated `assets/site.css` along with the source changes. Pressable serves the compiled CSS and requires no Node.js or CDN runtime.

All front-end page templates, single posts, Case Studies, archives, search and fallback pages share the Tailwind-powered stylesheet. Existing semantic classes stay in the PHP templates; utility declarations are composed centrally with `@apply`. This preserves page structure, native nesting, exact XD dimensions, breakpoint values and responsive behavior. Tailwind Preflight is deliberately omitted. Utilities use the `tw:` prefix so Tailwind's container/grid names cannot collide with existing theme classes. The CSS entry scans only custom-theme PHP and JavaScript; write complete literal class names for future utilities. Brand color utilities, such as `tw:text-papaya-teal`, map to the approved CSS variables.

For a visual regression check, save the previous generated stylesheet and run `node tools/check-tailwind.mjs /absolute/path/to/previous-site.css`. The check compares rendered geometry, computed design styles, image sources and screenshots (image pixels masked to avoid browser resampling noise) on all page types at desktop, tablet and mobile sizes, plus expanded mobile menus.
