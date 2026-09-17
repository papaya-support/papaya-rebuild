import { chromium } from 'playwright';
import fs from 'node:fs';
const user = process.env.PAPAYA_WP_USER,
  password = process.env.PAPAYA_WP_PASSWORD;
if (!user || !password) throw Error('Set PAPAYA_WP_USER and PAPAYA_WP_PASSWORD.');
const b = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
});
const p = await b.newPage({ viewport: { width: 1440, height: 1000 } });
try {
  await p.goto('http://127.0.0.1:9477/wp-login.php');
  await p.locator('#user_login').fill(user);
  await p.locator('#user_pass').fill(password);
  await Promise.all([p.waitForURL('**/wp-admin/**'), p.locator('#wp-submit').click()]);
  if (process.argv.includes('--import-fixture')) {
    await p.goto(
      'http://127.0.0.1:9477/wp-admin/edit.php?post_type=acf-field-group&page=acf-tools',
      { waitUntil: 'networkidle' },
    );
    await p
      .locator('input[type=file][name=acf_import_file]')
      .setInputFiles(
        'wordpress/wp-content/themes/papaya-search-child/acf-import/field-groups.json',
      );
    await Promise.all([
      p.waitForNavigation(),
      p.getByRole('button', { name: 'Import JSON', exact: true }).click(),
    ]);
  }
  await p.goto('http://127.0.0.1:9477/wp-admin/edit.php?post_type=acf-field-group', {
    waitUntil: 'networkidle',
  });
  const rows = await p.locator('.wp-list-table .row-title').allTextContents();
  console.log(rows);
  if (rows.length !== 9) throw Error('Expected nine database field groups');
  await p.screenshot({ path: 'verification/acf-field-groups.png' });
  await p.getByRole('link', { name: 'Home — Page Content', exact: true }).click();
  await p.waitForLoadState('networkidle');
  console.log((await p.locator('#post').innerText()).slice(0, 1200));
  if (!(await p.locator('body').innerText()).includes('Hero — Heading'))
    throw Error('Descriptive labels missing');
  await p.screenshot({ path: 'verification/acf-home-fields.png' });
  const id = new URL(p.url()).searchParams.get('post');
  // A real editable field-group post can be renamed in ACF, and is not reset next request.
  const title = p.locator('#title');
  const original = await title.inputValue();
  try {
    await title.fill(original + ' verification');
    await Promise.all([
      p.waitForNavigation(),
      p.getByRole('button', { name: 'Save Changes', exact: true }).click(),
    ]);
    await p.reload({ waitUntil: 'networkidle' });
    if ((await title.inputValue()) !== original + ' verification')
      throw Error('Group edit overwritten');
  } finally {
    await title.fill(original);
    await Promise.all([
      p.waitForNavigation(),
      p.getByRole('button', { name: 'Save Changes', exact: true }).click(),
    ]);
  }
  await p.goto('http://127.0.0.1:9477/wp-admin/edit.php?post_type=acf-field-group', {
    waitUntil: 'networkidle',
  });
  if ((await p.locator('.wp-list-table .row-title').count()) !== 9) throw Error('Duplicate groups');
  fs.writeFileSync(
    'verification/acf-database-groups.json',
    JSON.stringify(
      { groups: rows, editablePostId: id, editPersists: true, restored: true, noDuplicates: true },
      null,
      2,
    ),
  );
} finally {
  await b.close();
}
