<?php
/** Shared image-text sections: read ACF groups here and keep their markup together. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
$is_imported_service = (bool) get_post_meta(get_the_ID(), '_ps_live_service_source', true);
?>
<?php if ($variant === 'blog-detail-local-rankings'):
    $section_local_rankings = function_exists('get_field') ? (get_field('section_local_rankings', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_local_rankings, 'image', 'blog_detail_image_82ae55901c');
    $description = ps_section_value($section_local_rankings, 'description', 'blog_detail_bcb4f48e1767');
    $heading = ps_section_value($section_local_rankings, 'heading', 'blog_detail_2c021570ce1c');
    ?>
    <!-- Blog Detail Local Rankings -->
    <?php if (!$is_imported_service || (($description !== '') || ($heading !== ''))): ?>
        <section class="section-blog-detail-local-rankings section">
            <div class="container">
                <?php if ($heading !== ''): ?>
                    <h2 class="section-title" data-field="blog_detail_2c021570ce1c"><?php echo ps_section_text($heading, 'blog_detail_2c021570ce1c', 'h2', 'section-title'); ?></h2>
                <?php endif; ?>
            </div>
            <div class="container<?php echo !$is_imported_service || ((int) (true) + (int) ($description !== '')) > 1 ? ' split' : ''; ?>">
                <div class="split-media">
                    <?php ps_image('blog_detail_image_82ae55901c', '', false, $image); ?>
                </div>
                <?php if (!$is_imported_service || ($description !== '')): ?>
                    <div class="split-copy">
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="blog_detail_bcb4f48e1767"><?php echo ps_section_text($description, 'blog_detail_bcb4f48e1767', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'home-search-strategy'):
    $section_search_strategy = function_exists('get_field') ? (get_field('section_search_strategy', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_search_strategy, 'image', 'home_image_d86e7be569');
    $heading = ps_section_value($section_search_strategy, 'heading', 'home_2263b6f88209');
    $description = ps_section_value($section_search_strategy, 'description', 'home_d92c033cd119');
    $button_label = ps_section_value($section_search_strategy, 'button_label', 'home_6901adf0ab1d');
    $button_url = ps_section_value($section_search_strategy, 'button_url', 'home_6901adf0ab1d_url');
    ?>
    <!-- Home Search Strategy -->
    <?php if (!$is_imported_service || (($heading !== '' || $description !== '' || $button_label !== ''))): ?>
        <section class="section-home-search-strategy section section-tight">
            <div class="container<?php echo !$is_imported_service || ((int) (true) + (int) ($heading !== '' || $description !== '' || $button_label !== '')) > 1 ? ' split' : ''; ?>">
                <div class="split-media">
                    <?php ps_image('home_image_d86e7be569', '', false, $image); ?>
                </div>
                <?php if (!$is_imported_service || ($heading !== '' || $description !== '' || $button_label !== '')): ?>
                    <div class="split-copy">
                        <?php if ($heading !== ''): ?>
                            <h2 class="accent" data-field="home_2263b6f88209"><?php echo ps_section_text($heading, 'home_2263b6f88209', 'h2', 'accent'); ?></h2>
                        <?php endif; ?>
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="home_d92c033cd119"><?php echo ps_section_text($description, 'home_d92c033cd119', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                        <?php if ($button_label !== ''): ?>
                            <a<?php echo ps_section_button_label($button_label, wp_strip_all_tags(ps_inline_content('home_2263b6f88209', $heading))); ?> class="button " data-field="home_6901adf0ab1d" href="<?php echo esc_url(ps_local_service_url($button_url ?: ps_booking_url())); ?>"><?php echo ps_inline_content('home_6901adf0ab1d', $button_label); ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'home-search-trends'):
    $section_search_trends = function_exists('get_field') ? (get_field('section_search_trends', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_search_trends, 'heading', 'home_685fc698bd13');
    $description = ps_section_value($section_search_trends, 'description', 'home_ad1e50936d54');
    $image = ps_section_value($section_search_trends, 'image', 'home_image_d3f8f036db');
    ?>
    <!-- Home Search Trends -->
    <?php if (!$is_imported_service || (($heading !== '' || $description !== ''))): ?>
        <section class="section-home-search-trends section">
            <div class="container<?php echo !$is_imported_service || ((int) ($heading !== '' || $description !== '') + (int) (true)) > 1 ? ' split' : ''; ?>">
                <?php if (!$is_imported_service || ($heading !== '' || $description !== '')): ?>
                    <div class="split-copy">
                        <?php if ($heading !== ''): ?>
                            <h2 class="accent" data-field="home_685fc698bd13"><?php echo ps_section_text($heading, 'home_685fc698bd13', 'h2', 'accent'); ?></h2>
                        <?php endif; ?>
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="home_ad1e50936d54"><?php echo ps_section_text($description, 'home_ad1e50936d54', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="split-media">
                    <?php ps_image('home_image_d3f8f036db', '', false, $image); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'home-future-strategy'):
    $section_future_strategy = function_exists('get_field') ? (get_field('section_future_strategy', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_future_strategy, 'image', 'home_image_9c76895b7c');
    $heading = ps_section_value($section_future_strategy, 'heading', 'home_c03395a13124');
    $description = ps_section_value($section_future_strategy, 'description', 'home_1260983635dc');
    $heading_2 = ps_section_value($section_future_strategy, 'heading_2', 'home_20f80fb1ab2f');
    $button_label = ps_section_value($section_future_strategy, 'button_label', 'home_58c8c372efdf');
    $button_url = ps_section_value($section_future_strategy, 'button_url', 'home_58c8c372efdf_url');
    ?>
    <!-- Home Future Strategy -->
    <?php if (!$is_imported_service || (($heading !== '' || $description !== '' || $heading_2 !== '' || $button_label !== ''))): ?>
        <section class="section-home-future-strategy section section-tight">
            <div class="container<?php echo !$is_imported_service || ((int) (true) + (int) ($heading !== '' || $description !== '' || $heading_2 !== '' || $button_label !== '')) > 1 ? ' split' : ''; ?>">
                <div class="split-media">
                    <?php ps_image('home_image_9c76895b7c', '', false, $image); ?>
                </div>
                <?php if (!$is_imported_service || ($heading !== '' || $description !== '' || $heading_2 !== '' || $button_label !== '')): ?>
                    <div class="split-copy">
                        <?php if ($heading !== ''): ?>
                            <h2 class="accent" data-field="home_c03395a13124"><?php echo ps_section_text($heading, 'home_c03395a13124', 'h2', 'accent'); ?></h2>
                        <?php endif; ?>
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="home_1260983635dc"><?php echo ps_section_text($description, 'home_1260983635dc', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                        <?php if ($heading_2 !== ''): ?>
                            <h3 class="" data-field="home_20f80fb1ab2f"><?php echo ps_section_text($heading_2, 'home_20f80fb1ab2f', 'h3', ''); ?></h3>
                        <?php endif; ?>
                        <?php if ($button_label !== ''): ?>
                            <a<?php echo ps_section_button_label($button_label, wp_strip_all_tags(ps_inline_content('home_20f80fb1ab2f', $heading_2))); ?> class="button " data-field="home_58c8c372efdf" href="<?php echo esc_url(ps_local_service_url($button_url ?: ps_booking_url())); ?>"><?php echo ps_inline_content('home_58c8c372efdf', $button_label); ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'home-closing-cta'):
    $section_closing_cta = function_exists('get_field') ? (get_field('section_closing_cta', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_closing_cta, 'heading', 'home_09fe6c6170dc');
    $description = ps_section_value($section_closing_cta, 'description', 'home_26e26ba9efb2');
    $button_label = ps_section_value($section_closing_cta, 'button_label', 'home_b48f00ea5554');
    $button_url = ps_section_value($section_closing_cta, 'button_url', 'home_b48f00ea5554_url');
    ?>
    <!-- Home Closing Cta -->
    <?php if (!$is_imported_service || (($heading !== '' || $description !== '' || $button_label !== ''))): ?>
        <section class="section-home-closing-cta section section-cta cream">
            <div class="container split cta-with-team">
                <?php if (!$is_imported_service || ($heading !== '' || $description !== '' || $button_label !== '')): ?>
                    <div class="split-copy">
                        <?php if ($heading !== ''): ?>
                            <h2 class="accent" data-field="home_09fe6c6170dc"><?php echo ps_section_text($heading, 'home_09fe6c6170dc', 'h2', 'accent'); ?></h2>
                        <?php endif; ?>
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="home_26e26ba9efb2"><?php echo ps_section_text($description, 'home_26e26ba9efb2', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                        <?php if ($button_label !== ''): ?>
                            <a<?php echo ps_section_button_label($button_label, wp_strip_all_tags(ps_inline_content('home_09fe6c6170dc', $heading))); ?> class="button button-text-cream" data-field="home_b48f00ea5554" href="<?php echo esc_url(ps_local_service_url($button_url ?: ps_booking_url())); ?>"><?php echo ps_inline_content('home_b48f00ea5554', $button_label); ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php get_template_part('template-parts/sections/cta-image'); ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'search-engine-marketing-visibility'):
    $section_visibility = function_exists('get_field') ? (get_field('section_visibility', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_visibility, 'image', 'search_engine_marketing_image_2e8b60cfe5');
    $heading = ps_section_value($section_visibility, 'heading', 'search_engine_marketing_2a5d5198d572');
    $description = ps_section_value($section_visibility, 'description', 'search_engine_marketing_dbbcc6928a34');
    ?>
    <!-- Search Engine Marketing Visibility -->
    <?php if (!$is_imported_service || (($heading !== '' || $description !== ''))): ?>
        <section class="section-search-engine-marketing-visibility section section-tight">
            <div class="container<?php echo !$is_imported_service || ((int) (true) + (int) ($heading !== '' || $description !== '')) > 1 ? ' split' : ''; ?>">
                <div class="split-media">
                    <?php ps_image('search_engine_marketing_image_2e8b60cfe5', '', false, $image); ?>
                </div>
                <?php if (!$is_imported_service || ($heading !== '' || $description !== '')): ?>
                    <div class="split-copy">
                        <?php if ($heading !== ''): ?>
                            <h2 class="" data-field="search_engine_marketing_2a5d5198d572"><?php echo ps_section_text($heading, 'search_engine_marketing_2a5d5198d572', 'h2', ''); ?></h2>
                        <?php endif; ?>
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="search_engine_marketing_dbbcc6928a34"><?php echo ps_section_text($description, 'search_engine_marketing_dbbcc6928a34', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'search-engine-marketing-ppc-services'):
    $section_ppc_services = function_exists('get_field') ? (get_field('section_ppc_services', get_the_ID(), false) ?: []) : [];
    $description = ps_section_value($section_ppc_services, 'description', 'search_engine_marketing_9a1901bd9481');
    $image = ps_section_value($section_ppc_services, 'image', 'search_engine_marketing_image_de24625b12');
    ?>
    <!-- Search Engine Marketing Ppc Services -->
    <?php if (!$is_imported_service || (($description !== ''))): ?>
        <section class="section-search-engine-marketing-ppc-services section">
            <div class="container<?php echo !$is_imported_service || ((int) ($description !== '') + (int) (true)) > 1 ? ' split' : ''; ?>">
                <?php if (!$is_imported_service || ($description !== '')): ?>
                    <div class="split-copy">
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="search_engine_marketing_9a1901bd9481"><?php echo ps_section_text($description, 'search_engine_marketing_9a1901bd9481', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="split-media">
                    <?php ps_image('search_engine_marketing_image_de24625b12', '', false, $image); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'search-engine-marketing-google-ads'):
    $section_google_ads = function_exists('get_field') ? (get_field('section_google_ads', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_google_ads, 'heading', 'search_engine_marketing_5da4f91cb7c5');
    $description = ps_section_value($section_google_ads, 'description', 'search_engine_marketing_c4ab9d94a0db');
    $description_2 = ps_section_value($section_google_ads, 'description_2', 'search_engine_marketing_c14dc679be7b');
    $image = ps_section_value($section_google_ads, 'image', 'search_engine_marketing_image_f953878ff0');
    ?>
    <!-- Search Engine Marketing Google Ads -->
    <?php if (!$is_imported_service || (($heading !== '' || $description !== '' || $description_2 !== ''))): ?>
        <section class="section-search-engine-marketing-google-ads section">
            <div class="container<?php echo !$is_imported_service || ((int) ($heading !== '' || $description !== '') + (int) (true)) > 1 ? ' split' : ''; ?>">
                <?php if (!$is_imported_service || ($heading !== '' || $description !== '')): ?>
                    <div class="split-copy">
                        <?php if ($heading !== ''): ?>
                            <h2 class="" data-field="search_engine_marketing_5da4f91cb7c5"><?php echo ps_section_text($heading, 'search_engine_marketing_5da4f91cb7c5', 'h2', ''); ?></h2>
                        <?php endif; ?>
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="search_engine_marketing_c4ab9d94a0db"><?php echo ps_section_text($description, 'search_engine_marketing_c4ab9d94a0db', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="split-media">
                    <div class="certification-card">
                        <?php ps_image('search_engine_marketing_image_f953878ff0', '', false, $image); ?>
                        <?php if ($description_2 !== ''): ?>
                            <div class="prose " data-field="search_engine_marketing_c14dc679be7b"><?php echo ps_section_text($description_2, 'search_engine_marketing_c14dc679be7b', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'search-engine-marketing-microsoft-ads'):
    $section_microsoft_ads = function_exists('get_field') ? (get_field('section_microsoft_ads', get_the_ID(), false) ?: []) : [];
    $description = ps_section_value($section_microsoft_ads, 'description', 'search_engine_marketing_9b5edf4fcb86');
    $image = ps_section_value($section_microsoft_ads, 'image', 'search_engine_marketing_image_843cfc6b8d');
    $heading = ps_section_value($section_microsoft_ads, 'heading', 'search_engine_marketing_9a4e6a600a10');
    $description_2 = ps_section_value($section_microsoft_ads, 'description_2', 'search_engine_marketing_e7514b17f1f3');
    ?>
    <!-- Search Engine Marketing Microsoft Ads -->
    <?php if (!$is_imported_service || (($description !== '' || $heading !== '' || $description_2 !== ''))): ?>
        <section class="section-search-engine-marketing-microsoft-ads section section-tight">
            <div class="container<?php echo !$is_imported_service || ((int) (true) + (int) ($heading !== '' || $description_2 !== '')) > 1 ? ' split' : ''; ?>">
                <div class="split-media">
                    <div class="certification-card">
                        <?php ps_image('search_engine_marketing_image_843cfc6b8d', '', false, $image); ?>
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="search_engine_marketing_9b5edf4fcb86"><?php echo ps_section_text($description, 'search_engine_marketing_9b5edf4fcb86', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!$is_imported_service || ($heading !== '' || $description_2 !== '')): ?>
                    <div class="split-copy">
                        <?php if ($heading !== ''): ?>
                            <h2 class="" data-field="search_engine_marketing_9a4e6a600a10"><?php echo ps_section_text($heading, 'search_engine_marketing_9a4e6a600a10', 'h2', ''); ?></h2>
                        <?php endif; ?>
                        <?php if ($description_2 !== ''): ?>
                            <div class="prose " data-field="search_engine_marketing_e7514b17f1f3"><?php echo ps_section_text($description_2, 'search_engine_marketing_e7514b17f1f3', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'about-audience-strategy'):
    $section_audience_strategy = function_exists('get_field') ? (get_field('section_audience_strategy', get_the_ID(), false) ?: []) : [];
    $description = ps_section_value($section_audience_strategy, 'description', 'about_feb01315cca8');
    $heading = ps_section_value($section_audience_strategy, 'heading', 'about_595ae388ed18');
    $description_2 = ps_section_value($section_audience_strategy, 'description_2', 'about_6fd9927a1ee9');
    $image = ps_section_value($section_audience_strategy, 'image', 'about_image_fabff0a2d1');
    ?>
    <!-- About Audience Strategy -->
    <?php if (!$is_imported_service || (($description !== '' || $heading !== '' || $description_2 !== ''))): ?>
        <section class="section-about-audience-strategy section section-tight">
            <div class="container<?php echo !$is_imported_service || ((int) ($description !== '' || $heading !== '' || $description_2 !== '') + (int) (true)) > 1 ? ' split' : ''; ?>">
                <?php if (!$is_imported_service || ($description !== '' || $heading !== '' || $description_2 !== '')): ?>
                    <div class="split-copy">
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="about_feb01315cca8"><?php echo ps_section_text($description, 'about_feb01315cca8', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                        <?php if ($heading !== ''): ?>
                            <h2 class="accent" data-field="about_595ae388ed18"><?php echo ps_section_text($heading, 'about_595ae388ed18', 'h2', 'accent'); ?></h2>
                        <?php endif; ?>
                        <?php if ($description_2 !== ''): ?>
                            <div class="prose " data-field="about_6fd9927a1ee9"><?php echo ps_section_text($description_2, 'about_6fd9927a1ee9', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="split-media">
                    <?php ps_image('about_image_fabff0a2d1', '', false, $image); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'about-partnership'):
    $section_partnership = function_exists('get_field') ? (get_field('section_partnership', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_partnership, 'image', 'about_image_3f841022dd');
    $description = ps_section_value($section_partnership, 'description', 'about_95d816dc41a8');
    $description_2 = ps_section_value($section_partnership, 'description_2', 'about_073b5d613eba');
    ?>
    <!-- About Partnership -->
    <?php if (!$is_imported_service || (($description !== '' || $description_2 !== ''))): ?>
        <section class="section-about-partnership section">
            <div class="container<?php echo !$is_imported_service || ((int) (true) + (int) ($description !== '' || $description_2 !== '')) > 1 ? ' split' : ''; ?>">
                <div class="split-media">
                    <?php ps_image('about_image_3f841022dd', '', false, $image); ?>
                </div>
                <?php if (!$is_imported_service || ($description !== '' || $description_2 !== '')): ?>
                    <div class="split-copy">
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="about_95d816dc41a8"><?php echo ps_section_text($description, 'about_95d816dc41a8', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                        <?php if ($description_2 !== ''): ?>
                            <div class="prose " data-field="about_073b5d613eba"><?php echo ps_section_text($description_2, 'about_073b5d613eba', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'about-brand-story'):
    $section_brand_story = function_exists('get_field') ? (get_field('section_brand_story', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_brand_story, 'heading', 'about_cc11b96d229a');
    $description = ps_section_value($section_brand_story, 'description', 'about_e6c42fc4a3fa');
    ?>
    <!-- About Brand Story -->
    <?php if (!$is_imported_service || (($heading !== '' || $description !== '' || false))): ?>
        <section class="section-about-brand-story section">
            <div class="container<?php echo !$is_imported_service || ((int) ($heading !== '' || $description !== '') + (int) (false)) > 1 ? ' split' : ''; ?>">
                <?php if (!$is_imported_service || ($heading !== '' || $description !== '')): ?>
                    <div class="split-copy">
                        <?php if ($heading !== ''): ?>
                            <h2 class="accent" data-field="about_cc11b96d229a"><?php echo ps_section_text($heading, 'about_cc11b96d229a', 'h2', 'accent'); ?></h2>
                        <?php endif; ?>
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="about_e6c42fc4a3fa"><?php echo ps_section_text($description, 'about_e6c42fc4a3fa', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (!$is_imported_service || (false)): ?>
                    <div class="split-media">
                        <img class="brand-tree" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/illustrations/papaya-tree.svg'); ?>" alt="" aria-hidden="true">
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'case-study-detail-challenge'):
    $section_challenge = function_exists('get_field') ? (get_field('section_challenge', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_challenge, 'image', 'case_study_detail_image_4d97ed1303');
    $description = ps_section_value($section_challenge, 'description', 'case_study_detail_1dcedfb52114');
    $heading = ps_section_value($section_challenge, 'heading', 'case_study_detail_e27663e17aca');
    ?>
    <!-- Case Study Detail Challenge -->
    <?php if (!$is_imported_service || (($description !== '') || ($heading !== ''))): ?>
        <section class="section-case-study-detail-challenge section">
            <div class="container">
                <?php if ($heading !== ''): ?>
                    <h2 class="section-title" data-field="case_study_detail_e27663e17aca"><?php echo ps_section_text($heading, 'case_study_detail_e27663e17aca', 'h2', 'section-title'); ?></h2>
                <?php endif; ?>
            </div>
            <div class="container<?php echo !$is_imported_service || ((int) (true) + (int) ($description !== '')) > 1 ? ' split' : ''; ?>">
                <div class="split-media">
                    <?php ps_image('case_study_detail_image_4d97ed1303', '', false, $image); ?>
                </div>
                <?php if (!$is_imported_service || ($description !== '')): ?>
                    <div class="split-copy">
                        <?php if ($description !== ''): ?>
                            <div class="prose " data-field="case_study_detail_1dcedfb52114"><?php echo ps_section_text($description, 'case_study_detail_1dcedfb52114', 'div', 'prose '); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>
