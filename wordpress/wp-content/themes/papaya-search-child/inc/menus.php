<?php
defined('ABSPATH') || exit;
function ps_menu_locations() {
    return ['primary'=>'Header Navigation', 'footer_navigation'=>'Footer Navigation', 'footer_services'=>'Footer Services', 'footer_contact'=>'Footer Contact', 'footer_social'=>'Footer Social Links'];
}
function ps_menu_field($t) {
    return $t['scope']==='header' || in_array($t['key'], ['shared_819c8f0b4b41','shared_5f04f2b25bf0','shared_ed5745efb63b'], true);
}
/** One-time migration. Preserve existing menu assignments and all legacy ACF data. */
function ps_install_menus() {
    if (get_option('ps_menus_migrated') || !get_option('ps_design_imported')) { return; }
    $design=ps_initial_content('home'); $sets=array_fill_keys(array_keys(ps_menu_locations()), []);
    foreach ($design['texts'] as $t) {
        $value=str_replace(["\r\n","\r"],"\n",(string)ps_value($t['key'],$t['text'],$t['scope']));
        if ($t['scope']==='header') {
            $url=ps_value($t['key'].'_url','',$t['scope']) ?: (preg_match('/Get Started/i',$t['text']) ? ps_booking_url() : ps_link_for_label($t['text']));
            $sets['primary'][]=['title'=>$value,'url'=>$url,'classes'=>preg_match('/Get Started/i',$t['text'])?'menu-cta':''];
        }
        $location=['shared_819c8f0b4b41'=>'footer_navigation','shared_5f04f2b25bf0'=>'footer_services'][$t['key']]??'';
        if ($location) {
            foreach (explode("\n",$value) as $label) {if(trim($label)!=='') {$sets[$location][]=['title'=>trim($label),'url'=>ps_link_for_label(trim($label)) ?: '#'];}}
        }
        if ($t['key']==='shared_ed5745efb63b') {
            foreach (preg_split('/\n\s*\n/',trim($value)) as $label) {
                $label=trim($label);$url=ps_link_for_label($label);
                if (!$url && preg_match('/^[+\d() .-]+$/',$label)) {$url='tel:'.preg_replace('/[^+\d]/','',$label);}
                if (!$url) {$url='https://www.google.com/maps/search/?api=1&query='.rawurlencode(str_replace("\n",', ',$label));}
                $sets['footer_contact'][]=['title'=>$label,'url'=>$url];
            }
        }
    }
    // Preserve the design's social symbols. Destinations are editable custom links.
    foreach (['Facebook'=>'https://www.facebook.com/papayasearch','Twitter'=>'https://twitter.com/PapayaSearch','LinkedIn'=>'https://www.linkedin.com/company/papayasearch/'] as $label=>$url) {$sets['footer_social'][]=['title'=>$label,'url'=>$url,'classes'=>'social-'.strtolower($label)];}
    $locations=get_theme_mod('nav_menu_locations',[]);
    foreach (ps_menu_locations() as $location=>$name) {
        if (!empty($locations[$location]) && wp_get_nav_menu_object($locations[$location])) {continue;}
        $menu=wp_get_nav_menu_object('Papaya — '.$name);
        $id=$menu ? $menu->term_id : wp_create_nav_menu('Papaya — '.$name);
        if (is_wp_error($id)) {return $id;}
        if (!wp_get_nav_menu_items($id)) {
            foreach ($sets[$location] as $position=>$item) {
                $args=['menu-item-title'=>$item['title'],'menu-item-url'=>$item['url'],'menu-item-status'=>'publish','menu-item-type'=>'custom','menu-item-position'=>$position+1,'menu-item-classes'=>$item['classes']??''];
                // Use page menu items for local pages so WordPress tracks URL changes.
                $page_id=url_to_postid($item['url']);
                if ($page_id) {$args['menu-item-type']='post_type';$args['menu-item-object']='page';$args['menu-item-object-id']=$page_id;}
                $result=wp_update_nav_menu_item($id,0,$args);
                if (is_wp_error($result)) {return $result;}
            }
        }
        $locations[$location]=(int)$id;
    }
    set_theme_mod('nav_menu_locations',$locations);
    update_option('ps_menus_migrated',1);
}
add_action('admin_init',function(){if(current_user_can('edit_theme_options')) {ps_install_menus();}});
function ps_render_menu($location, $mobile=false) {
    $labels=ps_menu_locations();
    echo '<nav class="ps-menu ps-menu-'.esc_attr($location).'" aria-label="'.esc_attr($labels[$location]).'">';
    wp_nav_menu(['theme_location'=>$location,'container'=>false,'menu_class'=>'ps-menu-list','menu_id'=>($mobile?'mobile-':'desktop-').$location,'fallback_cb'=>false,'depth'=>0]);
    echo '</nav>';
}
// Social labels remain available to assistive technology; CSS supplies the compact symbols.
add_filter('nav_menu_link_attributes',function($atts,$item,$args){
    if (($args->theme_location??'')==='footer_social') {$atts['aria-label']=$item->title;}
    return $atts;
},10,3);
