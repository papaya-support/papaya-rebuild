<?php
defined('ABSPATH') || exit();
$group = function_exists('get_field') ? (get_field('field_ps_section_home_section_future_strategy',get_the_ID(),false) ?: []) : [];
$heading = ps_section_value($group,'heading','home_c03395a13124');
$intro = ps_section_value($group,'description','home_1260983635dc');
$url = ps_section_value($group,'button_url','home_58c8c372efdf_url');
$label = ps_section_value($group,'button_label','home_58c8c372efdf');
$content = ps_home_strategy_content($group, $intro);
$intro = $content['intro'];
if (!get_option('ps_home_strategy_cards_v1') && $label === '') {
    $label = 'Let’s Talk Strategy';
}
?>
<section class="section-home-future-strategy section section-tight">
    <div class="container">
        <div class="home-strategy-intro center">
            <?php if ($heading !== ''): ?><h2 class="accent"><?php echo esc_html(wp_strip_all_tags($heading)); ?></h2><?php endif; ?>
            <?php if ($intro !== ''): ?><div class="prose"><?php echo wp_kses_post(wpautop($intro)); ?></div><?php endif; ?>
        </div>
        <div class="home-strategy-cards">
            <?php for($i=1;$i<=3;$i++):
                $title = $content['cards']['heading_' . $i];
                $text = $content['cards']['description_' . $i];
                if ($title === '' && $text === '') continue;
            ?>
                <article class="home-strategy-card">
                    <span class="home-strategy-number" aria-hidden="true"><?php echo esc_html(sprintf('%02d',$i)); ?></span>
                    <?php if ($title !== ''): ?><h3><?php echo esc_html($title); ?></h3><?php endif; ?>
                    <?php if ($text !== ''): ?><div class="prose"><?php echo wp_kses_post(wpautop($text)); ?></div><?php endif; ?>
                </article>
            <?php endfor; ?>
        </div>
        <?php if ($label !== ''): ?><div class="center home-strategy-action"><a class="button" href="<?php echo esc_url($url ?: ps_booking_url()); ?>"><?php echo esc_html(wp_strip_all_tags($label)); ?></a></div><?php endif; ?>
    </div>
</section>
