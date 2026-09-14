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

Edit PHP or JavaScript directly in the child theme. For CSS, edit `tools/styles/base.css` or the design data and run:

```sh
python3 tools/build-css.py
```

Commit the source edits and generated `assets/site.css` together. Pressable deploys the committed files without running a build. Its database retains page content, ACF values and native menu edits. New media uploaded through WordPress also remains outside Git.

Changes made to theme files through the production Theme File Editor can be overwritten by the next deployment; make code changes through Git instead. To roll back a code change, revert the relevant commit and push the deployment branch. Code rollback does not roll back database changes.

## ACF field groups after deployment

Visit the dashboard as an administrator after deploying version 1.0.10 to install the nine editable ACF database groups. Manage their definitions under ACF → Field Groups afterward. The shipped import does not override subsequent edits. Existing values and field keys are preserved. Export later group changes through ACF → Tools when migrating them between environments.
