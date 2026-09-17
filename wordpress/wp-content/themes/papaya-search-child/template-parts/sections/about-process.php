<?php defined('ABSPATH') || exit; ?>
<section class="section-about-process section teal">
    <div class="container center">
        <?php
        /* ACF: Process — Heading */
        ps_text('about_da4d2d3bcd37', 'h2', 'section-title');
        ?>
        <?php
        /* ACF: Process — Introduction */
        ps_text('about_f46b98a005e8', 'div', 'prose ');
        ?>
        <div class="grid grid-two">
            <div class="process-card">
                <?php
                /* ACF: Process Step 1 — Number */
                ps_text('about_b871389519ab', 'span', 'step-number');
                ?>
                <?php
                /* ACF: Process Step 1 — Title */
                ps_text('about_b4e4254098b9', 'h3', 'accent');
                ?>
                <?php
                /* ACF: Process Step 1 — Description */
                ps_text('about_15ecab530a58', 'div', 'prose ');
                ?>
            </div>
            <div class="process-card">
                <?php
                /* ACF: Process Step 2 — Number */
                ps_text('about_a48b87d433a1', 'span', 'step-number');
                ?>
                <?php
                /* ACF: Process Step 2 — Title */
                ps_text('about_bf25e9e3a036', 'h3', 'accent');
                ?>
                <?php
                /* ACF: Process Step 2 — Description */
                ps_text('about_ef99a48b3104', 'div', 'prose ');
                ?>
            </div>
            <div class="process-card">
                <?php
                /* ACF: Process Step 3 — Number */
                ps_text('about_0bdb2df6cee4', 'span', 'step-number');
                ?>
                <?php
                /* ACF: Process Step 3 — Title */
                ps_text('about_b5f3d5d9b13b', 'h3', 'accent');
                ?>
                <?php
                /* ACF: Process Step 3 — Description */
                ps_text('about_38dffd506cc7', 'div', 'prose ');
                ?>
            </div>
            <div class="process-card">
                <?php
                /* ACF: Process Step 4 — Number */
                ps_text('about_dc5e50a805d4', 'span', 'step-number');
                ?>
                <?php
                /* ACF: Process Step 4 — Title */
                ps_text('about_77299c25f780', 'h3', 'accent');
                ?>
                <?php
                /* ACF: Process Step 4 — Description */
                ps_text('about_a62f88959455', 'div', 'prose ');
                ?>
            </div>
        </div>
        <?php
        /* ACF: Process — Button Label */
        ps_button('about_9849908bc21e', ps_booking_url(), 'button-green');
        ?>
    </div>
</section>
