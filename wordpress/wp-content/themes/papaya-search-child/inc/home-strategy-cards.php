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

/** Read both the original combined description and the editable card fields. */
function ps_home_strategy_content($group, $intro) {
    $cards = ps_home_strategy_cards_defaults();
    // Older pages stored the three cards in the introduction's rich text.
    $plain = trim(preg_replace('/\s+/u', ' ', html_entity_decode(wp_strip_all_tags(preg_replace('/<[^>]+>/', ' ', $intro)), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    $positions = [];
    for ($i = 1; $i <= 3; $i++) {
        $positions[$i] = strpos($plain, $cards['heading_' . $i]);
    }
    if ($positions[1] !== false && $positions[2] > $positions[1] && $positions[3] > $positions[2]) {
        $intro = trim(substr($plain, 0, $positions[1]));
        for ($i = 1; $i <= 3; $i++) {
            $start = $positions[$i] + strlen($cards['heading_' . $i]);
            $end = $i < 3 ? $positions[$i + 1] : strlen($plain);
            $cards['description_' . $i] = trim(substr($plain, $start, $end - $start));
        }
    }
    foreach ($cards as $name => $fallback) {
        foreach (['field_home_strategy_card_' . $name, 'card_' . $name] as $key) {
            if (array_key_exists($key, $group)) {
                $cards[$name] = (string) $group[$key];
                break;
            }
        }
    }
    return ['intro' => $intro, 'cards' => $cards];
}
