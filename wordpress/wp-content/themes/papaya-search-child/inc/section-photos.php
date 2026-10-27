<?php
/** Install editable section photos once; later editor changes are preserved. */
defined('ABSPATH') || exit();
function ps_install_section_photos() {
    if (get_option('ps_section_photos_v1')) return true;
    if (!function_exists('acf_update_field')) return new WP_Error('acf_missing', 'ACF is required.');
    $file = get_stylesheet_directory() . '/assets/images/papaya-team.png';
    $id = (int) get_option('ps_team_photo_attachment');
    if (!$id || !get_post($id)) {
        $existing = get_posts(['post_type'=>'attachment', 'post_status'=>'inherit', 'name'=>'papaya-team', 'numberposts'=>1]);
        $id = $existing ? $existing[0]->ID : 0;
        if (!$id) {
            $upload = wp_upload_bits('papaya-team.png', null, file_get_contents($file));
            if ($upload['error']) return new WP_Error('upload_failed', $upload['error']);
            $id = wp_insert_attachment(['post_title'=>'Papaya Search team', 'post_mime_type'=>'image/png', 'post_status'=>'inherit'], $upload['file'], 0, true);
            if (is_wp_error($id)) return $id;
            require_once ABSPATH . 'wp-admin/includes/image.php';
            wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $upload['file']));
            update_post_meta($id, '_wp_attachment_image_alt', 'Papaya Search team');
        }
        update_option('ps_team_photo_attachment', $id, false);
    }
    foreach (['home','services','search_engine_marketing','about'] as $slug) {
        $parent = acf_get_field('field_ps_section_' . $slug . '_section_closing_cta');
        if (empty($parent['ID'])) return new WP_Error('missing_group', 'Closing CTA fields are required.');
        $key = $slug === 'home' ? 'field_home_image_0aad8a4a0f' : 'field_ps_' . $slug . '_closing_cta_image';
        $field = acf_get_field($key) ?: [];
        acf_update_field(array_merge($field, ['key'=>$key, 'name'=>'image', 'label'=>'Image', 'type'=>'image', 'return_format'=>'id', 'preview_size'=>'medium', 'parent'=>$parent['ID'], 'menu_order'=>99, 'required'=>0]));
        $template = 'page-templates/' . str_replace('_','-',$slug) . '.php';
        foreach (get_posts(['post_type'=>'page', 'post_status'=>'any', 'numberposts'=>-1, 'meta_key'=>'_wp_page_template', 'meta_value'=>$template]) as $page) {
            // Requested initial assignment, including CTAs previously left empty.
            update_post_meta($page->ID, 'section_closing_cta_image', $id);
            update_post_meta($page->ID, '_section_closing_cta_image', $key);
        }
    }
    update_option('ps_section_photos_v1', 1, false);
    return true;
}
add_action('admin_init', function () { if (current_user_can('manage_options')) ps_install_section_photos(); }, 110);

function ps_install_testimonial_photos() {
    if (get_option('ps_testimonial_photos_v1')) return true;
    if (!function_exists('acf_update_field')) return new WP_Error('acf_missing', 'ACF is required.');
    $id = (int) get_option('ps_pearl_photo_attachment');
    if (!$id || !get_post($id)) {
        $source = get_stylesheet_directory() . '/assets/images/shmuel-pearl-ceo-of-pearl-solutions.png';
        $upload = wp_upload_bits(basename($source), null, file_get_contents($source));
        if ($upload['error']) return new WP_Error('upload_failed', $upload['error']);
        $id = wp_insert_attachment(['post_title'=>'Pearl Solutions', 'post_mime_type'=>'image/png', 'post_status'=>'inherit'], $upload['file'], 0, true);
        if (is_wp_error($id)) return $id;
        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $upload['file']));
        update_post_meta($id, '_wp_attachment_image_alt', 'Pearl Solutions logo');
        update_option('ps_pearl_photo_attachment', $id, false);
    }
    foreach (['home','case_study_detail','seo'] as $slug) {
        $seo = $slug === 'seo';
        $parent = acf_get_field($seo ? 'field_ps_seo_testimonial' : 'field_ps_section_' . $slug . '_section_testimonial');
        if (empty($parent['ID'])) return new WP_Error('missing_group', 'Testimonial fields are required.');
        $key = 'field_ps_' . $slug . '_testimonial_image';
        acf_update_field(['key'=>$key, 'name'=>'image', 'label'=>'Client Image', 'type'=>'image', 'return_format'=>'id', 'preview_size'=>'thumbnail', 'parent'=>$parent['ID'], 'menu_order'=>99, 'required'=>0]);
        $meta = $seo ? 'section_seo_testimonial' : 'section_testimonial';
        foreach (get_posts(['post_type'=>['page','case_study'], 'post_status'=>'any', 'numberposts'=>-1]) as $page) {
            $template = get_page_template_slug($page->ID);
            if ($slug === 'home' && $template !== 'page-templates/home.php') continue;
            if ($slug === 'case_study_detail' && $page->post_type !== 'case_study' && $template !== 'page-templates/case-study-detail.php') continue;
            if ($seo && $page->post_name !== 'seo') continue;
            $client = get_post_meta($page->ID, $meta . ($seo ? '_client_name' : '_attribution'), true);
            if (stripos($client, 'Shmuel') !== false && !metadata_exists('post', $page->ID, $meta . '_image')) {
                update_post_meta($page->ID, $meta . '_image', $id);
                update_post_meta($page->ID, '_' . $meta . '_image', $key);
            }
        }
    }
    update_option('ps_testimonial_photos_v1', 1, false);
    return true;
}
add_action('admin_init', function () { if (current_user_can('manage_options')) ps_install_testimonial_photos(); }, 111);
