# GitHub → Pressable

The repository contains the custom Astra child theme, its CSS build tools and project documentation. The complete local WordPress installation and original XD extraction remain on disk but are excluded from Git. Do not deploy this repository to the server root.

## Connection settings

Use Pressable's current GitHub integration, under the target site's **Advanced → GitHub Integration**:

| Setting | Value |
| --- | --- |
| GitHub repository | Select the repository created for this project |
| Branch | `main` (the current local branch; connect staging first) |
| Repository Subdirectory | `wordpress/wp-content/themes/papaya-search-child` |
| Destination Path | `wp-content/themes/papaya-search-child` |

The destination is relative to Pressable's `/htdocs`. These matching paths restrict synchronization and deletion to the custom child theme. Pressable's integration synchronizes files and can remove destination files that are absent from Git, so never broaden the destination to `wp-content`, `themes`, or the site root for this repository. Keep `.deployignore` committed before enabling deployment.

Save the deployment paths, verify the intended site and branch, then select **Set and Deploy**. Subsequent pushes to that branch deploy automatically. Check Git History for completion. Older Pressable accounts may need support to enable the current integration with configurable paths.

Official instructions: https://pressable.com/knowledgebase/deploy-from-github/

## First deployment

1. Start with a fresh Pressable staging site and PHP 8.3.
2. Install the Astra parent theme and activate Advanced Custom Fields on Pressable.
3. Connect GitHub with the exact paths above and deploy the child theme.
4. Activate Papaya Search Child in WordPress.
5. Under Appearance → Papaya Setup, run Import XD Pages & Content to create the original pages, ACF content, media and five navigation menus.
6. Review the pages, menu assignments and CTA URLs on staging before configuring production.

The importer seeds the bundled defaults. Current local dashboard edits need a separate content migration; they are not in Git. Use Pressable's WordPress administrator account, not the local Playground test credentials.

## Ongoing updates

Edit PHP or JavaScript directly in the child theme. For CSS, edit `tools/styles/base.css` (Tailwind `@apply` and custom XD rules) or `tools/styles/tailwind.css` (framework configuration). Install build dependencies with `npm ci --prefix tools`, then run:

```sh
python3 tools/build-css.py
```

Commit the source edits and generated `assets/site.css` together. Pressable deploys the committed files without running a build. Its database retains page content, ACF values and native menu edits. New media uploaded through WordPress also remains outside Git.

Changes made to theme files through the production Theme File Editor can be overwritten by the next deployment; make code changes through Git instead. To roll back a code change, revert the relevant commit and push the deployment branch. Code rollback does not roll back database changes.

## ACF field groups after deployment

Visit the dashboard as an administrator after deploying version 2.0.0 to install the nine editable ACF database groups. Manage their definitions under ACF → Field Groups afterward. The shipped import does not override subsequent edits. Existing values and field keys are preserved. Export later group changes through ACF → Tools when migrating them between environments.

## HTML template rebuild (2.0.0)

All eight existing template filenames and ACF keys are retained. Deploy the child-theme update; do not reimport content or reset menus. Page layouts now use PHP section templates and responsive HTML/CSS. The obsolete SVG renderer and full-page SVG files have been removed. The new theme does not load them even if an older copy remains on the server. Clear the site cache after deployment and review desktop and mobile pages. No database migration is needed for existing content.

For version 2.1.0, visit WordPress admin once after deployment. The child theme reorders the existing ACF fields and converts textareas to WYSIWYG editors without changing saved page content. No manual field-group import is needed.

For version 2.1.1, open WordPress admin once to remove the obsolete image-alt ACF fields. Edit alt text on the attachment in Media Library.

For version 2.2.0, visit WordPress admin once after deployment to add the Hero — Emblem Image field and convert short rich-text fields to text inputs. The corrected emblem is imported into Media Library only when its page value is missing.

Version 2.4.0: Blog cards and category filters use published Posts and Categories. Visit admin once to remove obsolete Blog card/filter fields. Publish articles under Posts; the ACF hero remains editable on the Blog page.

## Original posts import (September 30 export)

This commit includes the original WXR inside a PHP-guarded data file and all 428 referenced media files (90 attachments, saved image sizes and full-resolution originals). The source WXR SHA-256 is `ba3c23d2c4c2dae16866fec71aeced320ad6abfbe7e2256a7146c23c0cb24b9f`. Draft text and private metadata are not exposed as a downloadable XML file. Do not convert this payload to a public XML/JSON asset.

After the theme deploys through Git, sign in to staging and open **Tools → Import Original Papaya Posts → Import / Resume**. Keep that page open until it reports completion. The importer copies the bundled media into WordPress uploads and creates the 27 posts, 90 media records, authors, categories and comments in the staging database. It preserves 24 published posts and three drafts. Git deployment alone does not update that database; the explicit administrator import is required. It does not remove existing sample posts or change pages, menus or ACF content.

The process is resumable, uses one database transaction per record, and refuses to overwrite records with matching source GUIDs. Completed imports are not repeated. Media is isolated under `uploads/papaya-originals-20260930/` to avoid overwriting existing uploads. Known attachment/category IDs and parent/comment relationships are mapped to the destination records; the original WXR retains all source values. Article HTML, excerpts, dates, comments, and plugin metadata remain preserved. Imported articles use the existing XD Blog Detail template; uploaded-image URLs are resolved at render time without rewriting saved HTML. Plugin-specific metadata is retained even if the corresponding plugin is not installed.

For local use, run `node tools/import-original-posts.mjs --serve`. The persistent database and uploads are kept in ignored `.local/original-posts/`; the site is served at `http://127.0.0.1:9481`. The script verifies imported records and media against the export, checks repeat-import behavior, and requests every published article. `tools/fetch-original-media.py /path/to/export.xml` can recreate the local download cache. Do not commit the local database or its backups.

Existing authors are matched by login first, then email. The importer reuses the account without modifying its profile, password or permissions. Original author details remain in the source export and the import state’s `source_authors` records. Profile differences do not block import; newly needed author accounts retain the exported details with Subscriber permissions.

## Separate Header and Footer Site Content

After deploying this update, open WordPress admin once as an administrator. Site Content will contain separate **Header** and **Footer** entries, each with its own native ACF field group. Footer reuses the original record and saved values. Header provides an optional Media Library logo; leaving it empty preserves the existing design logo. Header links and its Get Started button remain managed through Appearance → Menus → Header Navigation.

The migration runs once, retains menu assignments, and does not overwrite later editor changes. No manual ACF import is required.

The Header logo update imports the existing bundled logo into Media Library and assigns it to Header → Header Content → Logo on the next administrator visit. It keeps the original image bytes and alt text, runs once, and preserves any logo already selected by an editor.

## Gray Group inline-style cleanup

After deployment, open WordPress admin once as an administrator. The one-time cleanup scans Posts and removes the saved inline style attribute and Gutenberg `style` settings only from Group blocks with a `#f5f5f5` background. Nested Groups are included. Other blocks, child styles, pages, text, links, dates, and post statuses are preserved. Each changed post's original content is retained in `_ps_before_group_style_cleanup_v1` post metadata. The bundled source export is unchanged.

The Group class update runs once on an administrator visit after deployment. Groups identified from the prior cleanup backups receive `green-post-block`; remaining Groups in Posts receive `orange-post-block`. Classes are stored in Gutenberg attributes and wrapper HTML. Existing styles and content are preserved, with pre-label content backed up in `_ps_before_group_classes_v1`. No CSS rules are changed by this update.

The orange Group update removes saved inline styles and Gutenberg style settings from `orange-post-block` Groups on the next administrator visit. Their rounded right corners, orange left border, pale orange background, margins, and padding now come from the scoped theme CSS. Original post content is backed up in `_ps_before_orange_group_cleanup_v1`; green Groups and child block styles are preserved.

## Live Services pages (October 9, 2026)

Push this update, then open **Tools → Import Service Pages → Import / Resume** on staging. This copies nine pages from the public Services submenu into the existing **Papaya — Services-Individual** template and its native ACF Groups, preserving the `/services/` hierarchy and nested SEO Audit / Google Business Profile paths. The import runs one page per request, resumes after errors, and leaves completed pages' later edits intact. Existing matching pages are backed up before import; unrelated pages are not changed.

The captured copy and media are bundled in `import-data/service-pages.php` and `import-data/services-media/`. Content uses readable PHP data, not JSON or rendered page artwork. Copy is mapped in source order into the template's introduction, image/text, benefits, additional information and FAQ fields. Extra source sections share the final rich-text area where necessary. The FAQ Group now accommodates up to nine questions, preserving all 41 source FAQs. Empty sections and image columns are omitted on imported pages to prevent SEM placeholder content from appearing.

The Google Business Profile Optimization ebook form depends on Gravity Forms on the original site. Its download CTA points to the original working form; this import does not recreate or submit that form locally. Source Local SEO content includes placeholder case-study wording; that wording is retained rather than rewritten. Page links between imported services are localized, while other source links retain their destinations. Header/footer menu assignments are unchanged.

Local import and verification: `node tools/import-service-pages.mjs --serve`. This uses the persistent `.local/original-posts/` database/uploads and serves port 9481. Verification checks all mapped ACF values, imported images, FAQ text, template assignment, repeat-import safety and successful HTTP responses with a single H1. `node tools/check-service-pages.mjs` runs the same import in isolated WordPress. `tools/prepare-service-pages.py` creates the bundled PHP content from the ignored public REST scan, using BeautifulSoup; the scan is a source capture, not a runtime dependency.
