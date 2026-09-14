<?php
/** Content helpers for the HTML page templates. No coordinates or page artwork. */
defined('ABSPATH') || exit;
function ps_default_content($key) {
    static $defaults;
    $defaults ??= require __DIR__.'/default-content.php';
    return $defaults[$key] ?? [];
}
function ps_field_value($key) {
    $default=ps_default_content($key);
    $post_id=($default['scope']??'page')==='page' ? get_the_ID() : ps_shared_id();
    // Keep the existing field names so all values edited in ACF survive the rebuild.
    if ($post_id && metadata_exists('post',$post_id,$key)) {
        $value=function_exists('get_field') ? get_field($key,$post_id,false) : get_post_meta($post_id,$key,true);
        return is_scalar($value) ? (string)$value : '';
    }
    return $default['text']??'';
}
function ps_content_html($key) {
    $value=str_replace(["\r\n","\r"],"\n",ps_field_value($key));
    $default=ps_default_content($key);
    $html=$value===str_replace(["\r\n","\r"],"\n",$default['text']??'') ? ($default['rich']??esc_html($value)) : esc_html($value);
    $html=str_replace(["\r\n","\r"],"\n",$html);
    // Lists and paragraphs participate in normal document flow and grow with ACF copy.
    if(preg_match('/^\s*•/u',strip_tags($html))) {
        $items=preg_split('/\s*•\s*/u',trim(esc_html($value)),-1,PREG_SPLIT_NO_EMPTY);
        return '<ul><li>'.implode('</li><li>',array_map('trim',$items)).'</li></ul>';
    }
    return wpautop($html);
}
function ps_text($key,$tag='p',$class='') {
    if(!in_array($tag,['h1','h2','h3','p','div','span','cite'],true)) {$tag='p';}
    $value=ps_field_value($key);
    if($value==='') {return;}
    if(!empty(ps_default_content($key)['uppercase'])) {$class.=' is-uppercase';}
    echo '<'.$tag.' class="'.esc_attr($class).'" data-field="'.esc_attr($key).'">';
    $text=esc_html(preg_replace('/\s+/u',' ',trim($value)));
    if(str_contains($class,'breadcrumb')) {$text=preg_replace('/^Home/', '<a href="'.esc_url(ps_route('home')).'">Home</a>', $text);}
    echo $tag==='div' ? wp_kses_post(ps_content_html($key)) : $text;
    echo '</'.$tag.'>';
}
function ps_booking_url() {return ps_value('ps_booking_url','tel:+14044259775','shared');}
function ps_button($key,$default_url,$class='') {
    $default=ps_default_content($key);
    $url=ps_value($key.'_url','',$default['scope']??'page') ?: $default_url;
    if(ps_field_value($key)==='') {return;}
    echo '<a class="button '.esc_attr($class).'" data-field="'.esc_attr($key).'" href="'.esc_url($url).'">'.esc_html(ps_field_value($key)).'</a>';
}
function ps_image($key,$class='',$eager=false) {
    $default=ps_default_content($key);
    $value=ps_value($key,'',$default['scope']??'page');
    if(is_array($value)) {$value=$value['ID']??($value['url']??'');}
    $alt=ps_value($key.'_alt','',$default['scope']??'page');
    $attributes=['class'=>$class,'data-image'=>$key,'alt'=>$alt,'loading'=>$eager?'eager':'lazy','decoding'=>'async'];
    if($eager) {$attributes['fetchpriority']='high';}
    $source=is_numeric($value) && $value ? wp_get_attachment_image_src((int)$value,'full') : false;
    if($source) {
        $url=$source[0];$attributes['width']=$source[1];$attributes['height']=$source[2];
        if(!$alt) {$attributes['alt']=get_post_meta((int)$value,'_wp_attachment_image_alt',true);}
    } else {
        $url=is_string($value)&&preg_match('#^https?://#',$value) ? $value : ps_asset($default['asset']??'');
    }
    echo '<img src="'.esc_url($url).'"';
    foreach($attributes as $name=>$attribute) {echo ' '.esc_attr($name).'="'.esc_attr($attribute).'"';}
    echo '>';
}
function ps_faq_answer($key) {
    $answer=ps_value($key.'_answer','');
    if($answer) {echo wp_kses_post(wpautop(esc_html($answer)));}
    else {echo '<p><a href="'.esc_url(ps_booking_url()).'">Contact us to discuss this question.</a></p>';}
}
function ps_link_for_label($label) {
    return ps_value('ps_link_'.sanitize_title($label),'','shared') ?: (ps_destinations()[trim($label)]??'');
}
function ps_footer_legal() {
    $key='shared_a8e4f9f45c81';$text=esc_html(ps_field_value($key));
    foreach(['Privacy Policy','Terms & Conditions','Careers'] as $label) {
        $text=str_replace(esc_html($label),'<a href="'.esc_url(ps_link_for_label($label)).'">'.esc_html($label).'</a>',$text);
    }
    echo '<p data-field="'.esc_attr($key).'">'.$text.'</p>';
}
