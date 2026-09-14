<?php
defined('ABSPATH') || exit;
function ps_link_for_label($label) {
    $destinations = ps_destinations(); $label = trim($label);
    $override = ps_value('ps_link_'.sanitize_title($label), '', 'shared');
    return $override ?: ($destinations[$label] ?? '');
}
function ps_rich_slice($t, $from, $to) {
    $out = ''; $cursor = $from;
    foreach ($t['runs'] as $run_index => $run) {
        $start = max($cursor, $run['from']); $end = min($to, $run['to']);
        if ($end <= $start) { continue; }
        if ($start > $cursor) { $out .= esc_html(mb_substr($t['text'], $cursor, $start-$cursor)); }
        $out .= '<span class="xd-run-'.(int)$run_index.'">'.esc_html(rtrim(mb_substr($t['text'], $start, $end-$start), "\r\n")).'</span>';
        $cursor = $end;
    }
    if ($cursor < $to) { $out .= esc_html(rtrim(mb_substr($t['text'], $cursor, $to-$cursor), "\r\n")); }
    return $out;
}
function ps_text_node($t, $slug, $mobile = false) {
    $value = str_replace(["\r\n", "\r"], "\n", (string) ps_value($t['key'], $t['text'], $t['scope'])); $font=$t['font'];
    $url = ps_value($t['key'].'_url', '', $t['scope']) ?: ps_link_default($t,$slug);
    if ($t['scope']==='header' && !preg_match('/Get Started/i',$t['text'])) { $url=ps_link_for_label($t['text']); }
    $faq=ps_is_faq($t,$slug); $tab=$slug==='blog' && $t['y']>600 && $t['y']<750;
    $more=$slug==='blog' && $t['text']==='View More';
    $tag=$t['tag']; if ($url) {$tag='a';} elseif ($faq || $tab || $more) {$tag='button';}
    $classes='xd-text'.($url ? ' xd-link':'').($mobile ? ' mobile-text':'').($faq?' faq-question':'');
    $attrs='';
    if ($url) {$attrs.=' href="'.esc_url($url).'"';}
    if ($tag==='button') {$attrs.=' type="button"';}
    if ($faq) {$attrs.=' aria-expanded="false" aria-controls="'.($mobile?'mobile-':'').'answer-'.esc_attr($t['key']).'"';}
    if ($tab) {$attrs.=' data-filter="'.esc_attr(trim($value)).'" aria-pressed="'.($value==='View All'?'true':'false').'"';}
    if ($more) {$attrs.=' data-view-more="true"';}
    echo '<'.$tag.' class="'.esc_attr($classes).'" data-field="'.esc_attr($t['key']).'"'.$attrs.'>';
    if ($mobile) { echo esc_html($value); }
    elseif ($value !== $t['text']) {
        // White-space: pre-wrap renders newlines directly; adding <br> doubles them.
        $content = esc_html($value);
        if ($t['scope'] === 'footer') {
            $lines = [];
            foreach (explode("\n", $value) as $label) {
                $link = trim($label) === '404-425-9775' ? 'tel:+14044259775' : ps_link_for_label(trim($label));
                $lines[] = $link ? '<a href="'.esc_url($link).'">'.esc_html($label).'</a>' : esc_html($label);
            }
            $content = implode("\n", $lines);
        }
        echo '<span class="xd-edited">'.$content.'</span>';
    } else {
        foreach ($t['lines'] as $line_index => $line) {
            $slice=trim(mb_substr($value,$line['from'],$line['to']-$line['from']));
            if ($slice==='') {continue;}
            $x=$line['x']??0; $y=$line['y']-$font['size']*$t['baseline'];
            $content=ps_rich_slice($t,$line['from'],$line['to']);
            if ($t['scope']==='footer' && !$url && ($lineUrl=ps_link_for_label($slice))) {$content='<a href="'.esc_url($lineUrl).'">'.$content.'</a>';}
            if ($t['scope']==='footer' && str_starts_with($slice,'Copyright')) {foreach(['Privacy Policy','Terms & Conditions','Careers'] as $label){$content=str_replace(esc_html($label),'<a href="'.esc_url(ps_link_for_label($label)).'">'.esc_html($label).'</a>',$content);}}
            if ($t['scope']==='footer' && $slice==='404-425-9775') {$content='<a href="tel:+14044259775">'.$content.'</a>';}
            if ($t['scope']==='page' && str_starts_with($value,'Home >')) {
                $content=str_replace('Home','<a href="'.esc_url(ps_route('home')).'">Home</a>',$content);
            }
            echo '<span class="xd-line xd-line-'.(int)$line_index.'">'.$content.'</span>';
        }
    }
    echo '</'.$tag.'>';
    if ($faq) {
        $answer=ps_value($t['key'].'_answer','','page');
        echo '<div class="faq-answer'.($mobile?' mobile-answer':'').'" id="'.($mobile?'mobile-':'').'answer-'.esc_attr($t['key']).'" hidden>'.($answer?nl2br(esc_html($answer)):'<a href="'.esc_url(ps_value('ps_booking_url','tel:+14044259775','shared')).'">Contact us to discuss this question.</a>').'</div>';
    }
}
function ps_footer_heading_keys() {
    return ['footer_navigation'=>'shared_78f8d1b3d0a8','footer_services'=>'shared_63ce494a885e','footer_contact'=>'shared_4937e4e54c2c'];
}
function ps_render_footer_columns($design, $mobile=false) {
    echo '<div class="ps-footer-columns">';
    foreach(ps_footer_heading_keys() as $location=>$key) {
        $fallback=['footer_navigation'=>'Navigation','footer_services'=>'Services','footer_contact'=>'Contact'][$location];
        foreach($design['texts'] as $text) {if($text['key']===$key) {$fallback=$text['text'];break;}}
        echo '<section class="ps-footer-column"><h2 class="ps-footer-heading">'.esc_html(ps_value($key,$fallback,'footer')).'</h2>';
        ps_render_menu($location,$mobile);
        if($location==='footer_contact') {ps_render_menu('footer_social',$mobile);}
        echo '</section>';
    }
    echo '</div>';
}
function ps_render_page($slug) {
    $design=ps_design($slug); if (!$design) {return;}
    $svg=file_get_contents(__DIR__.'/../design/'.$slug.'.svg');
    // The header button is now real menu markup with a CSS background.
    $svg=preg_replace('/<g data-ui-button="true"><g transform="matrix\(1 0 0 1 988 46\)"[^>]*><rect[^>]*\/><\/g><\/g>/', '', $svg);
    // Footer column dividers are rendered in CSS; remove the original XD strokes.
    $svg=preg_replace_callback('/<g transform="matrix\(1 0 0 1 ([\d.]+) ([\d.]+)\)"[^>]*><line [^>]*\/><\/g>/', function($match) use ($design) {
        return abs((float)$match[2]-($design['footerY']+184.5))<0.01 ? '' : $match[0];
    }, $svg);
    foreach ($design['images'] as $im) {$svg=str_replace('{{'.$im['key'].'}}',esc_url(ps_image_url($im)),$svg);}
    echo '<div class="xd-viewport" data-page="'.esc_attr($slug).'"><div class="xd-stage" data-height="'.$design['height'].'">';
    // SVG contains only the original decorative geometry. Text remains selectable HTML, read from ACF.
    echo '<div class="xd-artwork">'.$svg.'</div>';
    echo '<header class="xd-region" aria-label="Site header"><a class="brand-hit" href="'.esc_url(home_url('/')).'" aria-label="Papaya Search home"></a>';
    ps_render_menu('primary');
    echo '</header><main id="main-content" class="xd-region" tabindex="-1">';
    foreach($design['texts'] as $t) {if($t['scope']==='page') {ps_text_node($t,$slug);}}
    foreach($design['images'] as $im) {
        $alt=ps_value($im['key'].'_alt','','page');
        if($alt) {echo '<span role="img" class="screen-reader-text" aria-label="'.esc_attr($alt).'"></span>';}
    }
    if ($slug==='services') {
        foreach(['search-engine-optimization'=>995,'website-analytics'=>1490,'wordpress-maintenance'=>1490] as $anchor=>$y) {echo '<span id="'.esc_attr($anchor).'" class="xd-anchor"></span>';}
    }
    echo '</main><footer class="xd-region" aria-label="Site footer">';
    foreach($design['texts'] as $t) {if($t['scope']==='footer' && !ps_menu_field($t) && !in_array($t['key'],ps_footer_heading_keys(),true)) {ps_text_node($t,$slug);}}
    ps_render_footer_columns($design);
    echo '</footer></div></div>';
    ps_render_mobile($design);
    if($slug==='blog') {echo '<script type="application/json" id="papaya-blog-cards">'.wp_json_encode(ps_blog_cards(),JSON_HEX_TAG|JSON_HEX_AMP).'</script><div class="blog-status" role="status" aria-live="polite"></div>';}

}
function ps_mobile_bands($slug, $footer) {
    $bands=[
        'home'=>[[0,850,1],[850,1100,1],[1100,1560,2],[1560,1740,1],[1740,2300,4],[2300,2800,2],[2800,3460,2],[3460,3960,1],[3960,4220,3],[4220,4500,1],[4500,5200,2]],
        'about'=>[[0,830,1],[830,1350,2],[1350,1550,1],[1550,2150,2],[2150,2380,1],[2380,2790,2],[2790,3220,2],[3220,3440,1],[3440,3630,1],[3630,4150,3],[4150,4820,2],[4820,5300,1]],
        'services'=>[[0,990,1],[990,1450,2],[1450,2010,2],[2010,2220,1],[2220,2420,3],[2420,2600,1]],
        'search-engine-marketing'=>[[0,980,1],[980,1380,2],[1380,2000,2],[2000,2210,1],[2210,2420,3],[2420,2630,1],[2630,3280,2],[3280,3950,2],[3950,4060,1],[4060,4810,2],[4810,5350,1]],
        'blog'=>[[0,750,1],[750,1390,3],[1390,1960,3],[1960,2530,3],[2530,2800,1]],
        'case-studies'=>[[0,580,1],[580,1150,3],[1150,1650,3],[1650,2220,3]],
        'blog-detail'=>[[0,2770,1],[2770,2860,1],[2860,3354,3]],
        'case-study-detail'=>[[0,1150,1],[1150,1330,3],[1330,1850,1],[1850,2350,2],[2350,3354,1]],
    ];return $bands[$slug]??[[0,$footer,1]];
}
function ps_render_mobile($design) {
    $slug=$design['slug'];
    echo '<div class="mobile-site" data-page="'.esc_attr($slug).'"><header class="mobile-header"><a href="'.esc_url(home_url('/')).'"><img src="'.esc_url(get_stylesheet_directory_uri().'/assets/brand.svg').'" alt="Papaya Search"></a><button class="menu-toggle" aria-expanded="false" aria-controls="mobile-navigation" aria-label="Open navigation">☰</button><div id="mobile-navigation" hidden>';
    ps_render_menu('primary',true);
    echo '</div></header><main id="mobile-main">';
    foreach(ps_mobile_bands($slug,$design['footerY']) as [$from,$to,$columns]) {
        $background=$design['background'];$midpoint=($from+min($to,$design['footerY']))/2;
        foreach(($design['backgrounds']??[]) as $band) {if($midpoint>=$band['from'] && $midpoint<$band['to']) {$background=$band['color'];}}
        echo '<section class="mobile-band" data-band="'.$from.'">';
        for($column=0;$column<$columns;$column++) {
            $items=[];
            foreach($design['texts'] as $t) {
                if($t['scope']!=='page'||$t['y']<$from||$t['y']>=$to) {continue;}
                $center=$t['matrix'][4];
                if($t['frame']['type']!=='positioned') {$center+=($t['frame']['width']??0)/2;}
                if(min($columns-1,max(0,(int)floor($center/(1280/$columns))))===$column) {$items[]=['y'=>$t['y'],'text'=>$t];}
            }
            foreach($design['images'] as $im) {
                if(max(0,$im['y'])<$from||max(0,$im['y'])>=$to) {continue;}
                if(min($columns-1,max(0,(int)floor(($im['x']+$im['width']/2)/(1280/$columns))))===$column) {$items[]=['y'=>$im['y'],'image'=>$im];}
            }
            usort($items,fn($a,$b)=>$a['y']<=>$b['y']);if(!$items) {continue;}echo '<div class="mobile-column">';
            foreach($items as $item) {
                if(isset($item['text'])) {ps_text_node($item['text'],$slug,true);}
                else {$im=$item['image']; echo '<img class="mobile-image" src="'.esc_url(ps_image_url($im)).'" alt="'.esc_attr(ps_value($im['key'].'_alt','','page')).'" loading="lazy" data-image="'.esc_attr($im['key']).'">';}
            }
            echo '</div>';
        }
        echo '</section>';
    }
    echo '</main><footer class="mobile-footer"><a href="'.esc_url(home_url('/')).'"><img src="'.esc_url(get_stylesheet_directory_uri().'/assets/brand.svg').'" alt="Papaya Search"></a>';
    ps_render_footer_columns($design,true);
    foreach($design['texts'] as $t) {
        if($t['scope']!=='footer') {continue;}
        if(ps_menu_field($t) || in_array($t['key'],ps_footer_heading_keys(),true)) {continue;}
        $value=(string)ps_value($t['key'],$t['text'],$t['scope']);
        if(str_contains($value,"\n")) {
            echo '<div class="mobile-footer-links">';foreach(explode("\n",$value) as $label) {$label=trim($label);if(!$label){continue;}$url=ps_link_for_label($label);echo $url?'<a href="'.esc_url($url).'">'.esc_html($label).'</a>':'<span>'.esc_html($label).'</span>';}echo '</div>';
        } else {ps_text_node($t,$slug,true);}
    }
    echo '</footer></div>';
}
