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
- `assets/site.css`: one bundled stylesheet with native CSS nesting.
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

Edit section PHP files directly. Edit nested CSS in `tools/styles/base.css`, then bundle it with the local fonts:

```sh
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
