/** Persistent local import and source-data verification. */
import { runCLI } from "@wp-playground/cli";
import fs from "node:fs";
import path from "node:path";
const root = path.resolve(import.meta.dirname, "..");
const content = path.join(root, "wordpress/wp-content");
const local = path.join(root, ".local/original-posts");
for (const dir of ["database", "uploads"])
  fs.mkdirSync(path.join(local, dir), { recursive: true });
const mounts = [
  "themes/astra",
  "themes/papaya-search-child",
  "plugins/advanced-custom-fields",
  "mu-plugins",
].map((p) => ({
  hostPath: path.join(content, p),
  vfsPath: "/wordpress/wp-content/" + p,
}));
for (const dir of ["database", "uploads"])
  mounts.push({
    hostPath: path.join(local, dir),
    vfsPath: "/wordpress/wp-content/" + dir,
  });
mounts.push({
  hostPath: path.join(content, "themes/papaya-search-child/import-data/media"),
  vfsPath: "/original-media",
});
const instance = await runCLI({
  command: "server",
  port: 9481,
  php: "8.3",
  workers: 1,
  "define-bool": { DISABLE_WP_CRON: true },
  "mount-before-install": mounts,
});
const run = async (code) => {
  const r = await instance.playground.run({
    code: `<?php require '/wordpress/wp-load.php'; wp_set_current_user(1); define('PS_ORIGINAL_MEDIA_PATH', '/original-media');\n${code}`,
  });
  if (r.errors || r.exitCode) throw Error(r.errors || r.text);
  return r.text;
};
try {
  console.log(
    await run('ps_import_design_content(); echo "Local site ready.";'),
  );
  let state;
  do {
    state = JSON.parse(
      await run("echo json_encode(PS_Original_Post_Import::step());"),
    );
    if (state.processed % 10 === 0 || state.complete) console.log(state);
  } while (!state.complete);
  const report = await run(
    fs
      .readFileSync(path.join(root, "tools/verify-original-posts.php"), "utf8")
      .replace(/^<\?php\s*/, ""),
  );
  fs.writeFileSync(path.join(local, "verification.json"), report);
  console.log(report);
  const verification = JSON.parse(report);
  if (verification.errors.length) throw Error("Source verification failed.");
  await run("flush_rewrite_rules(false);");
  const urls = JSON.parse(
    await run(
      "echo json_encode(array_map('get_permalink',get_posts(['post_type'=>'post','post_status'=>'publish','posts_per_page'=>-1,'meta_key'=>'_ps_original_wxr_id'])));",
    ),
  );
  for (const url of urls) {
    const response = await fetch(url);
    const html = await response.text();
    if (
      !response.ok ||
      !html.includes("page-blog-detail") ||
      html.includes("critical error")
    )
      throw Error("Post render failed: " + url);
  }
  console.log(
    `Verified all ${urls.length} published posts render through the XD Blog Detail template.`,
  );
  console.log("LOCAL_IMPORT_READY http://127.0.0.1:9481");
  if (!process.argv.includes("--serve")) await instance[Symbol.asyncDispose]();
} catch (error) {
  await instance[Symbol.asyncDispose]();
  throw error;
}
process.on("SIGINT", async () => {
  await instance[Symbol.asyncDispose]();
  process.exit();
});
