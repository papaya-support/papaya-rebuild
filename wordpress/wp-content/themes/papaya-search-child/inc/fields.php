<?php
defined('ABSPATH') || exit;
add_action('init', function () {
    register_post_type('ps_site_content', ['label'=>'Site Content','public'=>false,'show_ui'=>true,'show_in_menu'=>true,'menu_icon'=>'dashicons-admin-site-alt3','supports'=>['title'],'capability_type'=>'page','map_meta_cap'=>true]);
});
function ps_text_field($t) {
    $label = preg_replace('/\s+/', ' ', $t['text']);
    $label = mb_strlen($label) > 76 ? mb_substr($label,0,73).'…' : $label;
    return ['key'=>'field_'.$t['key'],'name'=>$t['key'],'label'=>$label,'type'=>'textarea','rows'=>max(2,min(8,count($t['lines']))),'default_value'=>$t['text'],'new_lines'=>'','instructions'=>'Original XD content. Existing line breaks preserve the supplied layout.'];
}
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) { return; }
    $shared = [];
    foreach (ps_pages() as $page) {
        $design = ps_design($page['slug']); $fields = [];
        foreach ($design['texts'] as $t) {
            if (ps_menu_field($t)) {continue;}
            $field = ps_text_field($t);
            if ($t['scope'] !== 'page') { $shared[$t['key']] = $field; continue; }
            $fields[] = $field;
            if ($page['slug']==='blog' && str_starts_with($t['text'],'Lorem ipsum') && $t['font']['weight']>=700) {
                $fields[]=['key'=>'field_'.$t['key'].'_category','name'=>$t['key'].'_category','label'=>'Card category','type'=>'select','choices'=>array_combine(['Digital Marketing','SEO','SEM','Wordpress','Papaya HQ'],['Digital Marketing','SEO','SEM','Wordpress','Papaya HQ']),'default_value'=>'SEO'];
            }
            if (ps_link_default($t, $page['slug'])) {
                $fields[] = ['key'=>'field_'.$t['key'].'_url','name'=>$t['key'].'_url','label'=>$field['label'].' — link','type'=>'text','instructions'=>'Absolute URL, tel: link, or a relative site URL. Leave empty to use the default destination.'];
            }
            if (ps_is_faq($t, $page['slug'])) {
                $fields[] = ['key'=>'field_'.$t['key'].'_answer','name'=>$t['key'].'_answer','label'=>$field['label'].' — answer','type'=>'textarea','rows'=>4,'instructions'=>'The XD supplies only the collapsed question. Add the approved answer here.'];
            }
        }
        foreach ($design['images'] as $im) {
            $fields[] = ['key'=>'field_'.$im['key'],'name'=>$im['key'],'label'=>'Image — '.($im['y'] < 700 ? 'Hero' : $im['label']).' ('.round($im['y']).'px)','type'=>'image','return_format'=>'id','preview_size'=>'medium','library'=>'all','instructions'=>'Original XD asset is used until you choose a replacement.'];
            $fields[] = ['key'=>'field_'.$im['key'].'_alt','name'=>$im['key'].'_alt','label'=>'Image alternative text','type'=>'text'];
        }
        acf_add_local_field_group(['key'=>'group_ps_'.$page['slug'],'title'=>$page['name'].' — XD Content','fields'=>$fields,'location'=>[[['param'=>'page_template','operator'=>'==','value'=>'page-templates/'.$page['slug'].'.php']]],'style'=>'default','position'=>'normal']);
    }
    $shared['booking']=['key'=>'field_ps_booking_url','name'=>'ps_booking_url','label'=>'Page buttons / Schedule a Call destination','type'=>'text','default_value'=>'tel:+14044259775'];
    acf_add_local_field_group(['key'=>'group_ps_shared','title'=>'Shared Header, Footer & Links','fields'=>array_values($shared),'location'=>[[['param'=>'post_type','operator'=>'==','value'=>'ps_site_content']]]]);
});
