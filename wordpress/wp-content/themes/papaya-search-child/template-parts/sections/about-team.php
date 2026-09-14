<?php defined('ABSPATH') || exit; ?>
<section class="section-about-team section center">
<div class="container">
<?php /* ACF: Team — Heading */ ps_text('about_5182134bb65f', 'h2', 'section-title accent'); ?>
<div class="grid grid-three">
<div class="team-card">
<div class="avatar-placeholder" aria-hidden="true"></div><?php /* ACF: Team Member 1 — Name */ ps_text('about_3167a1bf8b73', 'h3', ''); ?>
<?php /* ACF: Team Member 1 — Job Title */ ps_text('about_64c8a292f1a0', 'p', 'accent'); ?>
<?php /* ACF: Team Member 1 — Biography */ ps_text('about_14d648101a42', 'div', 'prose '); ?>
<?php /* ACF: Team Member 1 — Button Label */ ps_button('about_0207ec398514', 'https://papayasearch.com/team/', ''); ?>
</div>
<div class="team-card">
<div class="avatar-placeholder" aria-hidden="true"></div><?php /* ACF: Team Member 2 — Name */ ps_text('about_e90a86ecb353', 'h3', ''); ?>
<?php /* ACF: Team Member 2 — Job Title */ ps_text('about_49f625079f2e', 'p', 'accent'); ?>
<?php /* ACF: Team Member 2 — Biography */ ps_text('about_15160ce464b3', 'div', 'prose '); ?>
<?php /* ACF: Team Member 2 — Button Label */ ps_button('about_c3554bd72d4e', 'https://papayasearch.com/team/', ''); ?>
</div>
<div class="team-card careers-card">
<?php /* ACF: Careers — Heading */ ps_text('about_7db8407b1d3f', 'h3', ''); ?>
<?php /* ACF: Careers — Description */ ps_text('about_175746524cd8', 'div', 'prose '); ?>
<?php /* ACF: Careers — Button Label */ ps_button('about_5d0d58023776', ps_destinations()['Careers'], ''); ?>
</div>
</div>
</div>
</section>
