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

Edit PHP or JavaScript directly in the child theme. For CSS, edit `tools/styles/base.css` and run:

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
