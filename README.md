# Papaya Search — WordPress XD rebuild

The `wordpress/` directory is the complete uploadable WordPress installation, including `wp-admin/`, `wp-includes/`, `wp-content/`, `wp-config.php`, and all standard root files. The Astra parent theme, genuine Advanced Custom Fields plugin, and Papaya Search child theme are included.

## Install on your WordPress host

1. Create an empty MySQL/MariaDB database and user on your host.
2. Edit `wordpress/wp-config.php` with that database's name, user, password, and host. Alternatively, supply the `WORDPRESS_DB_*` environment variables documented in that file.
3. Upload **the contents of `wordpress/`** to your site's document root and visit your domain to complete WordPress's normal installation. Choose your own administrator credentials.
4. On a fresh installation, the bundled setup plugin automatically activates **Advanced Custom Fields** and **Papaya Search Child**, with Astra as its parent. For an existing WordPress database, activate ACF and the child theme manually in Plugins and Appearance → Themes.
5. The child theme imports the eight pages on the next administrator screen. If needed, use **Appearance → Papaya Setup → Import XD Pages & Content**. The importer sets the front page, assigns the PHP templates, populates ACF fields, and imports the supplied images into the Media Library. Repeating it preserves existing edits.
6. Visit **Settings → Permalinks** and save once if your host requires its rewrite rules to be refreshed.

PHP 8.0+ is required by the child theme; PHP 8.3 was tested. Use standard WordPress hosting with MySQL/MariaDB and image-processing support. Production database credentials are intentionally not supplied. The package does not include an administrator account or a production database dump; the bundled importer creates its content in your own database.

## Included pages

| XD artboard | URL | PHP template |
|---|---|---|
| Home | `/` | `page-templates/home.php` |
| Blog | `/blog/` | `page-templates/blog.php` |
| Blog Detail | `/blog-detail/` | `page-templates/blog-detail.php` |
| Case Study | `/case-studies/` | `page-templates/case-studies.php` |
| Case Study Detail | `/case-study-detail/` | `page-templates/case-study-detail.php` |
| About | `/about/` | `page-templates/about.php` |
| Services-Landing | `/services/` | `page-templates/services.php` |
| Services-Individual | `/search-engine-marketing/` | `page-templates/search-engine-marketing.php` |

The template paths above are inside `wordpress/wp-content/themes/papaya-search-child/`.

## Editing in ACF

- **Pages → Edit**: edit that page's text, images, image alternative text, and link overrides in its **XD Content** field group.
- **Site Content → Header, Footer & Links**: edit shared navigation/footer content and destinations.
- **Get Started / Schedule a Call destination**: currently `tel:+14044259775`, using the phone number in the XD, as agreed. Replace it with your booking URL whenever ready.
- **Services-Individual / Search Engine Marketing**: each FAQ has a question and an answer field. The XD supplies the collapsed questions only. Until you enter answers, an expanded question offers the configured contact link.
- **Blog**: the nine supplied cards have editable category fields, initially set to SEO. Category buttons filter these cards. “View More” reports when all nine available cards are already displayed. This is the supplied fixed card layout, not an unbounded WordPress-post archive.
- The sample article titles, placeholder images, sample team biographies, and typos are retained from the XD. They can be replaced in ACF.
- Links to pages not represented by an XD artboard (such as Free Tools, Careers, and legal pages) use the existing Papaya Search website. Their destinations can be overridden in shared ACF fields. The SEO/analytics/maintenance service links target the corresponding section of the supplied Services layout; SEM opens its supplied detail layout.

ACF Free is sufficient; no ACF Pro license or page-builder plugin is needed. Field groups are registered in PHP in `inc/fields.php`, so no manual field-group import is required.

## Design implementation

All eight source artboards are 1280px wide. Original XD geometry, colors, text baselines, letter spacing, explicit line breaks, raster images, and vector artwork are compiled into the child theme. Text is selectable HTML rendered by PHP from ACF; the artwork is SVG, not a full-page screenshot. The original fonts (Rubik, Inter, and Noto Sans) are bundled locally with their licenses.

At the supplied desktop width, the templates use the measured XD coordinates. Desktop and tablet layouts scale that composition to the full browser width, including screens wider than 1280px. At the user’s request, desktop text and button artwork are reduced by 10% within that composition. Below 768px the same content is presented in a stacked layout with a mobile menu, since no mobile artboards were included. Mobile layout therefore is an adaptation, not an additional supplied design.

The desktop layout deliberately preserves fixed artboard measurements. Large changes to text length may need a layout adjustment; review a page after substantial copy edits. This tradeoff keeps the supplied desktop composition intact.

Astra remains the active parent (`Template: astra`). Its parent PHP runs normally; the child overrides the page header/footer and front-end styles to avoid adding Astra's default layout to the XD design. WordPress core and Astra source files are unmodified.

## Files for developers

- `wordpress/wp-content/themes/papaya-search-child/page-templates/`: eight PHP page templates.
- `inc/render.php`: shared HTML/SVG rendering and responsive content layout.
- `inc/fields.php`: page-specific and shared ACF registration.
- `inc/setup.php`: idempotent page, field-value, and media importer.
- `inc/content.php`: content access and destination helpers.
- `assets/site.css`, `assets/site.js`: styling and interactions.
- `design/*.json`, `design/*.svg`: measurements, original field values, and artwork compiled from XD.
- `design-source/`: extracted original XD archive, kept outside the public WordPress directory.
- `tools/extract-xd.py`: reproducible XD compiler; source document metadata is treated as data only.
- `verification/`: browser screenshots and validation reports.

## Local preview

The preview uses WordPress Playground with PHP 8.3 and SQLite **for local verification only**. The uploadable installation remains configured for MySQL/MariaDB.

```sh
npm ci --prefix tools
node tools/preview.mjs
```

Open `http://127.0.0.1:9477/`. The preview mounts the bundled themes and ACF plugin, imports the eight pages, and runs the PHP/ACF checks. The local preview database is separate from the deliverable and is recreated when the preview restarts. Development edits saved through its admin are not a production database export.

`node tools/qa.mjs` uses the installed macOS Google Chrome executable to check all eight routes plus representative mobile layouts, FAQ controls, category filters, and the mobile menu.

## Official dependency sources

- WordPress: https://wordpress.org/latest.zip
- Astra: https://downloads.wordpress.org/theme/astra.latest-stable.zip
- Advanced Custom Fields: https://www.advancedcustomfields.com/latest/
- Fonts: Google Fonts, with OFL licenses included in `assets/fonts/`.

The existing Papaya Search site was consulted only to verify link destinations for pages outside the supplied XD artboards. Its current design and copy were not substituted for the user's XD design.

## Consolidated frontend CSS

The eight custom page templates load one stylesheet: `wordpress/wp-content/themes/papaya-search-child/assets/site.css`. It includes fonts, shared responsive styling, and all per-page typography, positions, backgrounds, image ratios, and filter positions. PHP outputs classes and data attributes; JavaScript changes interaction state without writing inline styles. SVG artwork retains its native vector geometry and presentation attributes.

To make maintainable changes, edit `tools/styles/base.css` for shared styling or the XD design data for page-specific geometry, then run `python3 tools/build-css.py`. Run this CSS build after regenerating the XD files as well. The installed child theme requires no build tools at runtime. CSS container units and typed arithmetic handle full-width scaling in current browsers.

Unused WordPress block/global styles and Astra's unused scroll-to-top markup are omitted only on these custom templates. WordPress dashboard and plugin interfaces keep their own styles. `node tools/check-css.mjs` verifies zero inline style attributes/blocks on all eight public pages and compares the homepage with the captured pre-refactor screenshot.

### Native CSS nesting

`assets/site.css` now uses native CSS nesting with `&` selectors. Shared components are nested in `tools/styles/base.css`; the CSS generator groups each page's field typography, text lines, and edited-content rules beneath page and field selectors. The output remains one CSS file, with no Sass or browser-side compilation. Rebuild with `python3 tools/build-css.py` after source changes.

For a visual regression comparison, save the previous stylesheet first and run `node tools/check-nesting.mjs /absolute/path/to/previous.css`. The check compares all eight pages at desktop and mobile widths using the same browser.

Footer text normalizes saved ACF line endings before matching the design. Edited and mobile text rely on CSS whitespace handling without extra HTML line breaks, preventing doubled spacing. Footer links remain linked when their text is edited.

## WordPress navigation menus

Use **Appearance → Menus** to edit the five assigned menu locations: Header Navigation (including Get Started), Footer Navigation, Footer Services, Footer Contact, and Footer Social Links. The same menus power desktop and mobile. Edit labels, URLs, order, and submenu hierarchy with the standard WordPress editor. The header button uses the `menu-cta` CSS class; social items use `social-facebook`, `social-twitter`, and `social-linkedin` (enable CSS Classes under Screen Options to edit these).

Installation seeds these menus from existing shared content and destinations. On an existing installation, visit the dashboard once after updating the child theme to run the one-time migration. Existing assigned menus and legacy ACF data are preserved; migrated navigation fields are no longer exposed in ACF or used to render the menus. Future admin visits do not overwrite menu edits or reassign deliberately unassigned locations. Other page content, footer headings, copyright, and the Schedule a Call button remain in ACF.

The contact menu contains the phone, address/map link, and Contact Us. Social destinations were checked against public metadata on https://papayasearch.com/. Icons use WordPress's bundled GPL Dashicons font. Menu styling remains in the single nested stylesheet. The XD desktop artboards retain their designed dimensions; extensive new content may require corresponding layout adjustments.

## Editable ACF field groups

From version 1.0.10, the eight page groups and one shared footer group are stored as ACF field-group and field posts in the WordPress database. They appear under **ACF → Field Groups** and can be edited there. The child theme no longer calls `acf_add_local_field_group` or loads overriding local JSON definitions.

After deploying this update, open the WordPress dashboard as an administrator once. A one-time importer creates missing groups from `acf-import/field-groups.json`. Existing groups, saved content, and field keys/names are preserved. Later ACF edits are not reset by a refresh or deployment. The Papaya Setup importer also installs missing groups on a fresh site before importing content.

Alternatively, use **ACF → Tools → Import Field Groups** and select the bundled `acf-import/field-groups.json`. Do not reimport over customized groups unless you intend to replace their definitions. Keep field names and keys unchanged to retain template connections. Page content remains editable under Pages; native menus remain under Appearance → Menus.

Use ACF's export tools to back up subsequent field-definition changes or migrate them to another site. Those dashboard changes live in the database and are not automatically committed to Git. `tools/build-acf-import.py` only builds the initial distributable import file; it is not used to define frontend fields at runtime.

Spacing: `inc/spacing.json` in the child theme defines empty XD bands to remove. The artwork and generated text coordinates share this map, preserving image and font proportions. Update it with `python3 tools/build-css.py` and verify with `node tools/audit-spacing.mjs`. Shared footer and mobile spacing is in `tools/styles/base.css`.
