<?php
/** Section content and layout are edited together here. */
defined('ABSPATH') || exit();
$section_team = function_exists('get_field') ? (get_field('section_team', get_the_ID(), false) ?: []) : [];
$heading = ps_section_value($section_team, 'heading', 'about_5182134bb65f');
$name = ps_section_value($section_team, 'name', 'about_3167a1bf8b73');
$heading_2 = ps_section_value($section_team, 'heading_2', 'about_64c8a292f1a0');
$description = ps_section_value($section_team, 'description', 'about_14d648101a42');
$name_2 = ps_section_value($section_team, 'name_2', 'about_e90a86ecb353');
$heading_3 = ps_section_value($section_team, 'heading_3', 'about_49f625079f2e');
$description_2 = ps_section_value($section_team, 'description_2', 'about_15160ce464b3');
$heading_4 = ps_section_value($section_team, 'heading_4', 'about_7db8407b1d3f');
$description_3 = ps_section_value($section_team, 'description_3', 'about_175746524cd8');
$button_label = ps_section_value($section_team, 'button_label', 'about_0207ec398514');
$button_url = ps_section_value($section_team, 'button_url', 'about_0207ec398514_url');
$button_label_2 = ps_section_value($section_team, 'button_label_2', 'about_c3554bd72d4e');
$button_url_2 = ps_section_value($section_team, 'button_url_2', 'about_c3554bd72d4e_url');
$button_label_3 = ps_section_value($section_team, 'button_label_3', 'about_5d0d58023776');
$button_url_3 = ps_section_value($section_team, 'button_url_3', 'about_5d0d58023776_url');
?>
<section class="section-about-team section center">
    <div class="container">
        <?php if ($heading !== ''): ?>
            <h2 class="section-title accent" data-field="about_5182134bb65f"><?php echo ps_section_text($heading, 'about_5182134bb65f', 'h2', 'section-title accent'); ?></h2>
        <?php endif; ?>
        <div class="grid grid-three">
            <div class="team-card">
                <div class="avatar-placeholder" aria-hidden="true"></div>
                <?php if ($name !== ''): ?>
                    <h3 class="" data-field="about_3167a1bf8b73"><?php echo ps_section_text($name, 'about_3167a1bf8b73', 'h3', ''); ?></h3>
                <?php endif; ?>
                <?php if ($heading_2 !== ''): ?>
                    <p class="accent is-uppercase" data-field="about_64c8a292f1a0"><?php echo ps_section_text($heading_2, 'about_64c8a292f1a0', 'p', 'accent is-uppercase'); ?></p>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="about_14d648101a42"><?php echo ps_section_text($description, 'about_14d648101a42', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label !== ''): ?>
                    <a class="button " data-field="about_0207ec398514" href="<?php echo esc_url(ps_local_service_url($button_url ?: 'https://papayasearch.com/team/')); ?>"><?php echo ps_inline_content('about_0207ec398514', $button_label); ?></a>
                <?php endif; ?>
            </div>
            <div class="team-card">
                <div class="avatar-placeholder" aria-hidden="true"></div>
                <?php if ($name_2 !== ''): ?>
                    <h3 class="" data-field="about_e90a86ecb353"><?php echo ps_section_text($name_2, 'about_e90a86ecb353', 'h3', ''); ?></h3>
                <?php endif; ?>
                <?php if ($heading_3 !== ''): ?>
                    <p class="accent is-uppercase" data-field="about_49f625079f2e"><?php echo ps_section_text($heading_3, 'about_49f625079f2e', 'p', 'accent is-uppercase'); ?></p>
                <?php endif; ?>
                <?php if ($description_2 !== ''): ?>
                    <div class="prose " data-field="about_15160ce464b3"><?php echo ps_section_text($description_2, 'about_15160ce464b3', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label_2 !== ''): ?>
                    <a class="button " data-field="about_c3554bd72d4e" href="<?php echo esc_url(ps_local_service_url($button_url_2 ?: 'https://papayasearch.com/team/')); ?>"><?php echo ps_inline_content('about_c3554bd72d4e', $button_label_2); ?></a>
                <?php endif; ?>
            </div>
            <div class="team-card careers-card">
                <?php if ($heading_4 !== ''): ?>
                    <h3 class="" data-field="about_7db8407b1d3f"><?php echo ps_section_text($heading_4, 'about_7db8407b1d3f', 'h3', ''); ?></h3>
                <?php endif; ?>
                <?php if ($description_3 !== ''): ?>
                    <div class="prose " data-field="about_175746524cd8"><?php echo ps_section_text($description_3, 'about_175746524cd8', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label_3 !== ''): ?>
                    <a class="button " data-field="about_5d0d58023776" href="<?php echo esc_url(ps_local_service_url($button_url_3 ?: ps_destinations()['Careers'])); ?>"><?php echo ps_inline_content('about_5d0d58023776', $button_label_3); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
