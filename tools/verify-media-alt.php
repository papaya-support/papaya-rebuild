<?php
require '/wordpress/wp-load.php';
$id=get_option('ps_page_ids')['home'];$key='home_image_d86e7be569';
$attachment=(int)get_field($key,$id,false);$original_alt=get_post_meta($attachment,'_wp_attachment_image_alt',true);
$group=acf_get_field_group('group_ps_home');
$legacy=acf_update_field(['key'=>'field_'.$key.'_alt','name'=>$key.'_alt','label'=>'Image Alternative Text','type'=>'text','parent'=>$group['ID']]);
if(empty($legacy['ID'])) {throw new Exception('Could not create legacy test field');}
update_post_meta($id,$key.'_alt','Obsolete per-page alt');
delete_option('ps_media_library_alt_v1');$result=ps_remove_acf_image_alt_fields();
if(is_wp_error($result) || acf_get_field('field_'.$key.'_alt')) {throw new Exception('Legacy field was not removed');}
if(get_post_meta($id,$key.'_alt',true)!=='Obsolete per-page alt') {throw new Exception('Existing metadata changed');}
try {
    $GLOBALS['post']=get_post($id);setup_postdata($GLOBALS['post']);
    update_post_meta($attachment,'_wp_attachment_image_alt','Library & description');
    ob_start();ps_image($key);$html=ob_get_clean();
    if(!str_contains($html,'alt="Library &amp; description"') || str_contains($html,'Obsolete')) {throw new Exception('Image did not use Media Library alt');}
    update_post_meta($attachment,'_wp_attachment_image_alt','Updated in the library');
    ob_start();ps_image($key);$html=ob_get_clean();
    if(!str_contains($html,'alt="Updated in the library"')) {throw new Exception('Media Library edit did not propagate');}
    update_post_meta($attachment,'_wp_attachment_image_alt','');
    ob_start();ps_image($key);$html=ob_get_clean();
    if(!str_contains($html,'alt=""')) {throw new Exception('Empty library alt was overridden');}
} finally {update_post_meta($attachment,'_wp_attachment_image_alt',$original_alt);delete_post_meta($id,$key.'_alt');wp_reset_postdata();}
echo json_encode(['legacyAltFieldRemoved'=>true,'mediaLibraryAltRendered'=>true,'libraryEditPropagates'=>true,'emptyAltRespected'=>true]);
