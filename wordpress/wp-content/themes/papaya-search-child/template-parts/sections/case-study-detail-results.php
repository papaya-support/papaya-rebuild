<?php
/** Section content and layout are edited together here. */
defined('ABSPATH') || exit();
$section_results = function_exists('get_field') ? (get_field('section_results', get_the_ID(), false) ?: []) : [];
$description = ps_section_value($section_results, 'description', 'case_study_detail_7ad346f8413b');
$description_2 = ps_section_value($section_results, 'description_2', 'case_study_detail_edf925d1bf1f');
$description_3 = ps_section_value($section_results, 'description_3', 'case_study_detail_269299b792ce');
$description_4 = ps_section_value($section_results, 'description_4', 'case_study_detail_9a239a17b643');
$description_5 = ps_section_value($section_results, 'description_5', 'case_study_detail_0e4af60dbfa0');
?>
<section class="section-case-study-detail-results section">
    <div class="container center">
        <?php if ($description !== ''): ?>
            <div class="prose " data-field="case_study_detail_7ad346f8413b"><?php echo ps_section_text($description, 'case_study_detail_7ad346f8413b', 'div', 'prose '); ?></div>
        <?php endif; ?>
        <div class="grid grid-three stats">
            <div class="stat">
                <img class="result-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/result-visibility.svg" alt="" aria-hidden="true"><?php if ($description_2 !== ''): ?>
                    <div class="prose " data-field="case_study_detail_edf925d1bf1f"><?php echo ps_section_text($description_2, 'case_study_detail_edf925d1bf1f', 'div', 'prose '); ?></div>
                <?php endif; ?>
            </div>
            <div class="stat">
                <img class="result-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/result-leads.svg" alt="" aria-hidden="true"><?php if ($description_3 !== ''): ?>
                    <div class="prose " data-field="case_study_detail_269299b792ce"><?php echo ps_section_text($description_3, 'case_study_detail_269299b792ce', 'div', 'prose '); ?></div>
                <?php endif; ?>
            </div>
            <div class="stat">
                <img class="result-icon" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/illustrations/result-engagement.svg" alt="" aria-hidden="true"><?php if ($description_4 !== ''): ?>
                    <div class="prose " data-field="case_study_detail_9a239a17b643"><?php echo ps_section_text($description_4, 'case_study_detail_9a239a17b643', 'div', 'prose '); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($description_5 !== ''): ?>
            <div class="prose " data-field="case_study_detail_0e4af60dbfa0"><?php echo ps_section_text($description_5, 'case_study_detail_0e4af60dbfa0', 'div', 'prose '); ?></div>
        <?php endif; ?>
    </div>
</section>
