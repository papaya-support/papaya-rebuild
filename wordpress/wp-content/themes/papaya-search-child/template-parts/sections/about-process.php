<?php
/** Section content and layout are edited together here. */
defined('ABSPATH') || exit();
$section_process = function_exists('get_field') ? (get_field('section_process', get_the_ID(), false) ?: []) : [];
$heading = ps_section_value($section_process, 'heading', 'about_da4d2d3bcd37');
$description = ps_section_value($section_process, 'description', 'about_f46b98a005e8');
$number = ps_section_value($section_process, 'number', 'about_b871389519ab');
$heading_2 = ps_section_value($section_process, 'heading_2', 'about_b4e4254098b9');
$description_2 = ps_section_value($section_process, 'description_2', 'about_15ecab530a58');
$number_2 = ps_section_value($section_process, 'number_2', 'about_a48b87d433a1');
$heading_3 = ps_section_value($section_process, 'heading_3', 'about_bf25e9e3a036');
$description_3 = ps_section_value($section_process, 'description_3', 'about_ef99a48b3104');
$number_3 = ps_section_value($section_process, 'number_3', 'about_0bdb2df6cee4');
$heading_4 = ps_section_value($section_process, 'heading_4', 'about_b5f3d5d9b13b');
$description_4 = ps_section_value($section_process, 'description_4', 'about_38dffd506cc7');
$number_4 = ps_section_value($section_process, 'number_4', 'about_dc5e50a805d4');
$heading_5 = ps_section_value($section_process, 'heading_5', 'about_77299c25f780');
$description_5 = ps_section_value($section_process, 'description_5', 'about_a62f88959455');
$button_label = ps_section_value($section_process, 'button_label', 'about_9849908bc21e');
$button_url_2 = ps_section_value($section_process, 'button_url_2', 'about_9849908bc21e_url');
?>
<section class="section-about-process section teal">
    <div class="container center">
        <?php if ($heading !== ''): ?>
            <h2 class="section-title" data-field="about_da4d2d3bcd37"><?php echo ps_section_text($heading, 'about_da4d2d3bcd37', 'h2', 'section-title'); ?></h2>
        <?php endif; ?>
        <?php if ($description !== ''): ?>
            <div class="prose " data-field="about_f46b98a005e8"><?php echo ps_section_text($description, 'about_f46b98a005e8', 'div', 'prose '); ?></div>
        <?php endif; ?>
        <div class="grid grid-two">
            <div class="process-card">
                <?php if ($number !== ''): ?>
                    <span class="step-number is-uppercase" data-field="about_b871389519ab"><?php echo ps_section_text($number, 'about_b871389519ab', 'span', 'step-number is-uppercase'); ?></span>
                <?php endif; ?>
                <?php if ($heading_2 !== ''): ?>
                    <h3 class="accent" data-field="about_b4e4254098b9"><?php echo ps_section_text($heading_2, 'about_b4e4254098b9', 'h3', 'accent'); ?></h3>
                <?php endif; ?>
                <?php if ($description_2 !== ''): ?>
                    <div class="prose " data-field="about_15ecab530a58"><?php echo ps_section_text($description_2, 'about_15ecab530a58', 'div', 'prose '); ?></div>
                <?php endif; ?>
            </div>
            <div class="process-card">
                <?php if ($number_2 !== ''): ?>
                    <span class="step-number is-uppercase" data-field="about_a48b87d433a1"><?php echo ps_section_text($number_2, 'about_a48b87d433a1', 'span', 'step-number is-uppercase'); ?></span>
                <?php endif; ?>
                <?php if ($heading_3 !== ''): ?>
                    <h3 class="accent" data-field="about_bf25e9e3a036"><?php echo ps_section_text($heading_3, 'about_bf25e9e3a036', 'h3', 'accent'); ?></h3>
                <?php endif; ?>
                <?php if ($description_3 !== ''): ?>
                    <div class="prose " data-field="about_ef99a48b3104"><?php echo ps_section_text($description_3, 'about_ef99a48b3104', 'div', 'prose '); ?></div>
                <?php endif; ?>
            </div>
            <div class="process-card">
                <?php if ($number_3 !== ''): ?>
                    <span class="step-number is-uppercase" data-field="about_0bdb2df6cee4"><?php echo ps_section_text($number_3, 'about_0bdb2df6cee4', 'span', 'step-number is-uppercase'); ?></span>
                <?php endif; ?>
                <?php if ($heading_4 !== ''): ?>
                    <h3 class="accent" data-field="about_b5f3d5d9b13b"><?php echo ps_section_text($heading_4, 'about_b5f3d5d9b13b', 'h3', 'accent'); ?></h3>
                <?php endif; ?>
                <?php if ($description_4 !== ''): ?>
                    <div class="prose " data-field="about_38dffd506cc7"><?php echo ps_section_text($description_4, 'about_38dffd506cc7', 'div', 'prose '); ?></div>
                <?php endif; ?>
            </div>
            <div class="process-card">
                <?php if ($number_4 !== ''): ?>
                    <span class="step-number is-uppercase" data-field="about_dc5e50a805d4"><?php echo ps_section_text($number_4, 'about_dc5e50a805d4', 'span', 'step-number is-uppercase'); ?></span>
                <?php endif; ?>
                <?php if ($heading_5 !== ''): ?>
                    <h3 class="accent" data-field="about_77299c25f780"><?php echo ps_section_text($heading_5, 'about_77299c25f780', 'h3', 'accent'); ?></h3>
                <?php endif; ?>
                <?php if ($description_5 !== ''): ?>
                    <div class="prose " data-field="about_a62f88959455"><?php echo ps_section_text($description_5, 'about_a62f88959455', 'div', 'prose '); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($button_label !== ''): ?>
            <a class="button button-green" data-field="about_9849908bc21e" href="<?php echo esc_url(ps_local_service_url($button_url_2 ?: ps_booking_url())); ?>"><?php echo ps_inline_content('about_9849908bc21e', $button_label); ?></a>
        <?php endif; ?>
    </div>
</section>
