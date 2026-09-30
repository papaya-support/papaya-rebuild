<?php
$state = PS_Original_Post_Import::state();
$errors = [];
$counts = ['post' => 0, 'attachment' => 0, 'comments' => 0, 'meta' => 0, 'files' => 0];
foreach (PS_Original_Post_Import::items() as $item) {
    $wp = PS_Original_Post_Import::wp($item);
    $old = (string) $wp->post_id;
    $id = $state['posts'][$old] ?? 0;
    $post = get_post($id);
    if (!$post) { $errors[] = "Missing record $old"; continue; }
    $counts[$post->post_type]++;
    $expected = [
        'post_title' => (string) $item->title,
        'post_content' => (string) $item->children('http://purl.org/rss/1.0/modules/content/')->encoded,
        'post_excerpt' => (string) $item->children('http://wordpress.org/export/1.2/excerpt/')->encoded,
        'post_status' => (string) $wp->status,
        'guid' => (string) $item->guid,
    ];
    foreach (['post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt', 'post_name', 'post_password', 'comment_status', 'ping_status'] as $key) $expected[$key] = (string) $wp->$key;
    foreach ($expected as $key => $value) if ($post->$key !== $value) $errors[] = "$old: $key differs";
    foreach ($wp->postmeta as $meta) {
        $counts['meta']++;
        $key = (string) $meta->meta_key;
        $value = maybe_unserialize((string) $meta->meta_value);
        if (in_array($key, ['_thumbnail_id', '_seopress_social_fb_img_attachment_id'], true) && isset($state['posts'][(string) $value])) $value = (string) $state['posts'][(string) $value];
        if (in_array($key, ['_yoast_wpseo_primary_category', '_seopress_robots_primary_cat'], true)) {
            $categories = wp_get_post_categories($id);
            if (count($categories) === 1) $value = (string) $categories[0];
        }
        if ($key === '_wp_attached_file') $value = PS_Original_Post_Import::PREFIX . $value;
        if ($key === '_wp_attachment_metadata' && is_array($value) && isset($value['file'])) $value['file'] = PS_Original_Post_Import::PREFIX . $value['file'];
        if (!in_array(serialize($value), array_map('serialize', get_post_meta($id, $key, false)), true)) $errors[] = "$old: meta $key differs";
    }
    foreach ($item->category as $category) {
        if (!has_term((string) $category['nicename'], (string) $category['domain'], $id)) $errors[] = "$old: category missing";
    }
    foreach ($wp->comment as $comment) {
        $counts['comments']++;
        $saved = get_comment($state['comments'][(string) $comment->comment_id] ?? 0);
        foreach (['comment_author', 'comment_author_email', 'comment_author_url', 'comment_author_IP', 'comment_date', 'comment_date_gmt', 'comment_content', 'comment_approved', 'comment_type'] as $key) {
            if (!$saved || $saved->$key !== (string) $comment->$key) $errors[] = "$old: comment $key differs";
        }
    }
    if ($post->post_type === 'attachment') {
        $local = get_attached_file($id);
        $source = PS_ORIGINAL_MEDIA_PATH . '/' . substr(get_post_meta($id, '_wp_attached_file', true), strlen(PS_Original_Post_Import::PREFIX));
        if (!is_file($local) || hash_file('sha256', $local) !== hash_file('sha256', $source)) $errors[] = "$old: original media differs";
        else $counts['files']++;
    }
}
$source_root = PS_ORIGINAL_MEDIA_PATH;
$upload_root = wp_upload_dir()['basedir'] . '/' . PS_Original_Post_Import::PREFIX;
$media_count = 0;
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source_root, FilesystemIterator::SKIP_DOTS)) as $file) {
    if (!$file->isFile()) continue;
    $relative = substr($file->getPathname(), strlen($source_root) + 1);
    $target = $upload_root . $relative;
    if (!is_file($target) || hash_file('sha256', $target) !== hash_file('sha256', $file->getPathname())) $errors[] = 'Media bytes differ: ' . $relative;
    $media_count++;
}
$counts['all_media_files'] = $media_count;
foreach (PS_Original_Post_Import::wp(PS_Original_Post_Import::source())->author as $author) {
    $user = get_userdata($state['authors'][(string) $author->author_login]);
    foreach (['user_login' => 'author_login', 'user_email' => 'author_email', 'display_name' => 'author_display_name', 'first_name' => 'author_first_name', 'last_name' => 'author_last_name'] as $saved => $original) {
        if ($user->$saved !== (string) $author->$original) $errors[] = 'Author differs: ' . $saved;
    }
}
$before = count($state['posts']);
PS_Original_Post_Import::step();
if (count(PS_Original_Post_Import::state()['posts']) !== $before) $errors[] = 'Repeated import created records';
echo json_encode(['counts' => $counts, 'authors' => count($state['authors']), 'source_sha256' => PS_Original_Post_Import::SOURCE_HASH, 'errors' => $errors], JSON_PRETTY_PRINT);
