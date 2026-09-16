<?php
/** Shared semantic output. Stored editor content is never rewritten. */
defined('ABSPATH') || exit;
function ps_accessible_title() {
    return trim(wp_strip_all_tags(get_the_title())) ?: __('Untitled', 'papaya-search');
}
/** Normalize editorial headings under the page H1, preserving their visual sizes. */
function ps_article_headings($html) {
    $stack=[];
    $levels=[];
    // Leave examples, comments and executable/raw-text elements untouched.
    return preg_replace_callback('~<!--[\s\S]*?-->|<(pre|code|script|style)\b[^>]*>[\s\S]*?</\1\s*>|<(\/?)(h[1-6])\b([^>]*)>~i', function($match) use (&$stack, &$levels) {
        if(empty($match[3])) {return $match[0];}
        $original=(int)substr($match[3],1);
        if($match[2]==='/') {return '</h'.($levels[$original]??max(2,$original)).'>';}
        while($stack && end($stack)['original'] >= $original) {array_pop($stack);}
        $level=min(6, $stack ? end($stack)['level']+1 : 2);
        $stack[]=['original'=>$original,'level'=>$level];$levels[$original]=$level;
        $tag='<h'.$level.$match[4].'>';
        if($level!==$original) {
            $processor=new WP_HTML_Tag_Processor($tag);
            $processor->next_tag();$processor->add_class('editor-heading-'.$original);
            $tag=$processor->get_updated_html();
        }
        return $tag;
    }, $html);
}
add_filter('the_content', function($html) {
    return !is_admin() && is_singular() && in_the_loop() && is_main_query() ? ps_article_headings($html) : $html;
}, 20);
