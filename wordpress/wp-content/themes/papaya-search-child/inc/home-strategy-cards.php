<?php
/** Editable text cards replacing the homepage strategy image split. */
defined('ABSPATH') || exit();
function ps_home_strategy_cards_defaults() {
    return [
        'heading_1' => 'Collaborative Strategy & Planning',
        'description_1' => 'Your business isn\'t one-size-fits-all, and your search strategy shouldn\'t be either. We take the time to understand your goals, evaluate your search presence, and build a collaborative plan focused on the opportunities that matter most to your business.',
        'heading_2' => 'Data-Driven Optimization',
        'description_2' => 'Good strategies don\'t stand still. We use performance data and ongoing analysis to identify what\'s working, uncover opportunities, and refine your SEO, PPC, and AI search efforts as technology and customer behavior evolve.',
        'heading_3' => 'Transparent Reporting & Continuous Improvement',
        'description_3' => 'You\'ll always know what we\'re working on and why it matters. We turn performance data into clear recommendations and meaningful progress updates, so you understand your results and can make confident decisions about what\'s next.',
    ];
}
function ps_install_home_strategy_cards() {
    if (get_option('ps_home_strategy_cards_v1')) return true;
    if (!function_exists('acf_get_field')) return false;
    $parent = acf_get_field('field_ps_section_home_section_future_strategy');
    if (empty($parent['ID'])) return false;
    $position = 2;
    foreach (ps_home_strategy_cards_defaults() as $name => $value) {
        acf_update_field(['key'=>'field_home_strategy_card_' . $name, 'name'=>'card_' . $name, 'label'=>ucwords(str_replace('_',' ', 'card_' . $name)), 'type'=>str_starts_with($name,'description') ? 'textarea' : 'text', 'parent'=>$parent['ID'], 'menu_order'=>$position++]);
    }
    $pages = get_posts(['post_type'=>'page','post_status'=>'any','numberposts'=>-1,'meta_key'=>'_wp_page_template','meta_value'=>'page-templates/home.php']);
    foreach ($pages as $page) {
        $backup = [];
        foreach (['heading','description','button_label'] as $name) $backup[$name] = get_post_meta($page->ID,'section_future_strategy_' . $name,true);
        add_post_meta($page->ID, '_ps_strategy_before_cards', $backup, true);
        $updates = [
            'heading' => ["A Search Marketing Strategy Built for What's Next", 'field_home_c03395a13124'],
            'description' => ['Since 2004, Papaya Search has helped businesses navigate an ever-changing search landscape. We combine years of hands-on experience with a collaborative, data-driven approach to build search marketing strategies that adapt to new technologies, evolving customer behavior, and your business goals.', 'field_home_1260983635dc'],
            'button_label' => ['Let’s Talk Strategy','field_home_58c8c372efdf'],
        ];
        foreach ($updates as $name => [$value,$key]) {
            update_post_meta($page->ID,'section_future_strategy_' . $name,$value);
            update_post_meta($page->ID,'_section_future_strategy_' . $name,$key);
        }
        foreach (ps_home_strategy_cards_defaults() as $name => $value) {
            $meta = 'section_future_strategy_card_' . $name;
            if (!metadata_exists('post',$page->ID,$meta)) {
                update_post_meta($page->ID,$meta,$value);
                update_post_meta($page->ID,'_' . $meta,'field_home_strategy_card_' . $name);
            }
        }
    }
    update_option('ps_home_strategy_cards_v1',1,false);
    return true;
}
add_action('admin_init',function(){ if(current_user_can('manage_options')) ps_install_home_strategy_cards(); },96);
add_filter('acf/prepare_field',function($field){
    if (in_array($field['key'],['field_home_image_9c76895b7c','field_home_20f80fb1ab2f'],true)) return false;
    return $field;
});
