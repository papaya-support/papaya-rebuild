<?php
/** Shared content sections: read ACF groups here and keep their markup together. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
$is_imported_service = (bool) get_post_meta(get_the_ID(), '_ps_live_service_source', true);
?>
<?php if ($variant === 'blog-detail-heading'):
    $section_heading = function_exists('get_field') ? (get_field('section_heading', get_the_ID(), false) ?: []) : [];
    $breadcrumb = ps_section_value($section_heading, 'breadcrumb', 'blog_detail_9c13381244a8');
    $heading = ps_section_value($section_heading, 'heading', 'blog_detail_8c780093d5a1');
    ?>
    <!-- Blog Detail Heading -->
    <?php if (!$is_imported_service || ($breadcrumb !== '' || $heading !== '')): ?>
        <section class="section-blog-detail-heading section">
            <div class="container article-heading">
                <?php if ($breadcrumb !== ''): ?>
                    <p class="breadcrumb" data-field="blog_detail_9c13381244a8"><?php echo ps_section_text($breadcrumb, 'blog_detail_9c13381244a8', 'p', 'breadcrumb'); ?></p>
                <?php endif; ?>
                <h1 class="" data-field="blog_detail_8c780093d5a1"><?php echo ps_section_text($heading, 'blog_detail_8c780093d5a1', 'h1', ''); ?></h1>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'blog-detail-featured-image'):
    $section_featured_image = function_exists('get_field') ? (get_field('section_featured_image', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_featured_image, 'image', 'blog_detail_image_33017e3b40');
    ?>
    <!-- Blog Detail Featured Image -->
    <section class="section-blog-detail-featured-image section section-tight">
        <div class="container">
            <?php ps_image('blog_detail_image_33017e3b40', 'article-featured', true, $image); ?>
        </div>
    </section>
<?php endif; ?>

<?php if ($variant === 'services-introduction'):
    $section_introduction = function_exists('get_field') ? (get_field('section_introduction', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_introduction, 'heading', 'services_1d102d9577a4');
    $heading_2 = ps_section_value($section_introduction, 'heading_2', 'services_c540653f6d16');
    $description = ps_section_value($section_introduction, 'description', 'services_c0d7b44ec821');
    ?>
    <!-- Services Introduction -->
    <?php if (!$is_imported_service || ($heading !== '' || $heading_2 !== '' || $description !== '')): ?>
        <section class="section-services-introduction section">
            <div class="container intro center">
                <h1 class="" data-field="services_1d102d9577a4"><?php echo ps_section_text($heading, 'services_1d102d9577a4', 'h1', ''); ?></h1>
                <?php if ($heading_2 !== ''): ?>
                    <h2 class="accent is-uppercase" data-field="services_c540653f6d16"><?php echo ps_section_text($heading_2, 'services_c540653f6d16', 'h2', 'accent is-uppercase'); ?></h2>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="services_c0d7b44ec821"><?php echo ps_section_text($description, 'services_c0d7b44ec821', 'div', 'prose '); ?></div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'home-introduction'):
    $section_introduction = function_exists('get_field') ? (get_field('section_introduction', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_introduction, 'heading', 'home_8c4225c0ac7d');
    $description = metadata_exists('post', get_the_ID(), 'section_introduction_description')
        ? ps_section_value($section_introduction, 'description', 'home_introduction_description')
        : ps_field_value('home_d92c033cd119');
    ?>
    <!-- Home Introduction -->
    <?php if (!$is_imported_service || ($heading !== '')): ?>
        <section class="section-home-introduction section">
            <div class="container center narrow">
                <?php if ($heading !== ''): ?>
                    <h2 class=" is-uppercase" data-field="home_8c4225c0ac7d"><?php echo ps_section_text($heading, 'home_8c4225c0ac7d', 'h2', ' is-uppercase'); ?></h2>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose home-introduction-copy" data-field="home_introduction_description"><?php echo wp_kses_post(ps_content_html('home_d92c033cd119', $description)); ?></div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'search-engine-marketing-introduction'):
    $section_introduction = function_exists('get_field') ? (get_field('section_introduction', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_introduction, 'heading', 'search_engine_marketing_f6246eab32c5');
    $subtitle = ps_section_value($section_introduction, 'subtitle', 'search_engine_marketing_4daf58fc05bf');
    $description = ps_section_value($section_introduction, 'description', 'search_engine_marketing_8f8d05ce486c');
    ?>
    <!-- Search Engine Marketing Introduction -->
    <?php if (!$is_imported_service || ($heading !== '' || $subtitle !== '' || $description !== '')): ?>
        <section class="section-search-engine-marketing-introduction section">
            <div class="container intro center">
                <h1 class="" data-field="search_engine_marketing_f6246eab32c5"><?php echo ps_section_text($heading, 'search_engine_marketing_f6246eab32c5', 'h1', ''); ?></h1>
                <?php if ($subtitle !== ''): ?>
                    <h2 class="accent is-uppercase" data-field="search_engine_marketing_4daf58fc05bf"><?php echo ps_section_text($subtitle, 'search_engine_marketing_4daf58fc05bf', 'h2', 'accent is-uppercase'); ?></h2>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="search_engine_marketing_8f8d05ce486c"><?php echo ps_section_text($description, 'search_engine_marketing_8f8d05ce486c', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php $intro_button = ps_editable_button('section_introduction', 'button', is_page('seo') ? "Let's Talk SEO" : '', ps_booking_url()); ?>
                <?php if ($intro_button['title'] !== '' && $intro_button['url'] !== ''): ?>
                    <a class="button seo-introduction-button" href="<?php echo esc_url(ps_local_service_url($intro_button['url'])); ?>"><?php echo esc_html($intro_button['title']); ?></a>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'search-engine-marketing-closing-cta'):
    $section_closing_cta = function_exists('get_field') ? (get_field('section_closing_cta', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_closing_cta, 'heading', 'search_engine_marketing_b1060e1729d0');
    $description = ps_section_value($section_closing_cta, 'description', 'search_engine_marketing_ca67c83aae59');
    $button_label = ps_section_value($section_closing_cta, 'button_label', 'search_engine_marketing_2263d859bf84');
    $button_url = ps_section_value($section_closing_cta, 'button_url', 'search_engine_marketing_2263d859bf84_url');
    ?>
    <!-- Search Engine Marketing Closing Cta -->
    <?php if (!$is_imported_service || ($heading !== '' || $description !== '' || $button_label !== '')): ?>
        <section class="section-search-engine-marketing-closing-cta section section-cta green">
            <div class="container cta-with-team"><div class="cta-copy">
                <?php if ($heading !== ''): ?>
                    <h2 class=" is-uppercase" data-field="search_engine_marketing_b1060e1729d0"><?php echo ps_section_text($heading, 'search_engine_marketing_b1060e1729d0', 'h2', ' is-uppercase'); ?></h2>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="search_engine_marketing_ca67c83aae59"><?php echo ps_section_text($description, 'search_engine_marketing_ca67c83aae59', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label, wp_strip_all_tags(ps_inline_content('search_engine_marketing_b1060e1729d0', $heading))); ?> class="button button-peach" data-field="search_engine_marketing_2263d859bf84" href="<?php echo esc_url(ps_local_service_url($button_url ?: ps_booking_url())); ?>"><?php echo ps_inline_content('search_engine_marketing_2263d859bf84', $button_label); ?></a>
                <?php endif; ?>
            </div><?php get_template_part('template-parts/sections/cta-image'); ?></div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'case-studies-introduction'):
    $section_introduction = function_exists('get_field') ? (get_field('section_introduction', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_introduction, 'heading', 'case_studies_089cedf19e41');
    $description = ps_section_value($section_introduction, 'description', 'case_studies_b5430be964d4');
    $description_2 = ps_section_value($section_introduction, 'description_2', 'case_studies_8234b4aaee10');
    $button_label = ps_section_value($section_introduction, 'button_label', 'case_studies_5f8c3c80c990');
    $button_url = ps_section_value($section_introduction, 'button_url', 'case_studies_5f8c3c80c990_url');
    ?>
    <!-- Case Studies Introduction -->
    <?php if (!$is_imported_service || ($heading !== '' || $description !== '' || $description_2 !== '' || $button_label !== '')): ?>
        <section class="section-case-studies-introduction section">
            <div class="container intro center">
                <h1 class="" data-field="case_studies_089cedf19e41"><?php echo ps_section_text($heading, 'case_studies_089cedf19e41', 'h1', ''); ?></h1>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="case_studies_b5430be964d4"><?php echo ps_section_text($description, 'case_studies_b5430be964d4', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($description_2 !== ''): ?>
                    <div class="prose " data-field="case_studies_8234b4aaee10"><?php echo ps_section_text($description_2, 'case_studies_8234b4aaee10', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label, wp_strip_all_tags(ps_inline_content('case_studies_089cedf19e41', $heading))); ?> class="button button-green" data-field="case_studies_5f8c3c80c990" href="<?php echo esc_url(ps_local_service_url($button_url ?: ps_booking_url())); ?>"><?php echo ps_inline_content('case_studies_5f8c3c80c990', $button_label); ?></a>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'about-introduction'):
    $section_introduction = function_exists('get_field') ? (get_field('section_introduction', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_introduction, 'heading', 'about_f1bcbe1f32e9');
    $description = ps_section_value($section_introduction, 'description', 'about_5364bb9bbe7f');
    ?>
    <!-- About Introduction -->
    <?php if (!$is_imported_service || ($heading !== '' || $description !== '')): ?>
        <section class="section-about-introduction section">
            <div class="container intro center">
                <h1 class="" data-field="about_f1bcbe1f32e9"><?php echo ps_section_text($heading, 'about_f1bcbe1f32e9', 'h1', ''); ?></h1>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="about_5364bb9bbe7f"><?php echo ps_section_text($description, 'about_5364bb9bbe7f', 'div', 'prose '); ?></div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'about-partnership-heading'):
    $section_partnership_heading = function_exists('get_field') ? (get_field('section_partnership_heading', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_partnership_heading, 'heading', 'about_3fed862d01f7');
    ?>
    <!-- About Partnership Heading -->
    <?php if (!$is_imported_service || ($heading !== '')): ?>
        <section class="section-about-partnership-heading section cream compact-section">
            <div class="container narrow center">
                <?php if ($heading !== ''): ?>
                    <h2 class="" data-field="about_3fed862d01f7"><?php echo ps_section_text($heading, 'about_3fed862d01f7', 'h2', ''); ?></h2>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'about-closing-cta'):
    $section_closing_cta = function_exists('get_field') ? (get_field('section_closing_cta', get_the_ID(), false) ?: []) : [];
    $heading = ps_section_value($section_closing_cta, 'heading', 'about_8ccc8c3f77e1');
    $description = ps_section_value($section_closing_cta, 'description', 'about_d48d9c392e5c');
    $button_label = ps_section_value($section_closing_cta, 'button_label', 'about_718abc37e0eb');
    $button_url_2 = ps_section_value($section_closing_cta, 'button_url_2', 'about_718abc37e0eb_url');
    ?>
    <!-- About Closing Cta -->
    <?php if (!$is_imported_service || ($heading !== '' || $description !== '' || $button_label !== '')): ?>
        <section class="section-about-closing-cta section section-cta green">
            <div class="container cta-with-team"><div class="cta-copy">
                <?php if ($heading !== ''): ?>
                    <h2 class=" is-uppercase" data-field="about_8ccc8c3f77e1"><?php echo ps_section_text($heading, 'about_8ccc8c3f77e1', 'h2', ' is-uppercase'); ?></h2>
                <?php endif; ?>
                <?php if ($description !== ''): ?>
                    <div class="prose " data-field="about_d48d9c392e5c"><?php echo ps_section_text($description, 'about_d48d9c392e5c', 'div', 'prose '); ?></div>
                <?php endif; ?>
                <?php if ($button_label !== ''): ?>
                    <a<?php echo ps_section_button_label($button_label, wp_strip_all_tags(ps_inline_content('about_8ccc8c3f77e1', $heading))); ?> class="button button-peach" data-field="about_718abc37e0eb" href="<?php echo esc_url(ps_local_service_url($button_url_2 ?: ps_booking_url())); ?>"><?php echo ps_inline_content('about_718abc37e0eb', $button_label); ?></a>
                <?php endif; ?>
            </div><?php get_template_part('template-parts/sections/cta-image'); ?></div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'case-study-detail-heading'):
    $section_heading = function_exists('get_field') ? (get_field('section_heading', get_the_ID(), false) ?: []) : [];
    $breadcrumb = ps_section_value($section_heading, 'breadcrumb', 'case_study_detail_dac02921ffd6');
    $heading = ps_section_value($section_heading, 'heading', 'case_study_detail_afcd2640f663');
    ?>
    <!-- Case Study Detail Heading -->
    <?php if (!$is_imported_service || ($breadcrumb !== '' || $heading !== '')): ?>
        <section class="section-case-study-detail-heading section">
            <div class="container article-heading">
                <?php if ($breadcrumb !== ''): ?>
                    <p class="breadcrumb" data-field="case_study_detail_dac02921ffd6"><?php echo ps_section_text($breadcrumb, 'case_study_detail_dac02921ffd6', 'p', 'breadcrumb'); ?></p>
                <?php endif; ?>
                <h1 class="" data-field="case_study_detail_afcd2640f663"><?php echo ps_section_text($heading, 'case_study_detail_afcd2640f663', 'h1', ''); ?></h1>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php if ($variant === 'case-study-detail-featured-image'):
    $section_featured_image = function_exists('get_field') ? (get_field('section_featured_image', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_featured_image, 'image', 'case_study_detail_image_cf7d13020e');
    ?>
    <!-- Case Study Detail Featured Image -->
    <section class="section-case-study-detail-featured-image section section-tight">
        <div class="container">
            <?php ps_image('case_study_detail_image_cf7d13020e', 'article-featured', true, $image); ?>
        </div>
    </section>
<?php endif; ?>

<?php if ($variant === 'case-study-detail-follow-up'):
    $section_follow_up = function_exists('get_field') ? (get_field('section_follow_up', get_the_ID(), false) ?: []) : [];
    $description = ps_section_value($section_follow_up, 'description', 'case_study_detail_28224860229f');
    $image = ps_section_value($section_follow_up, 'image', 'case_study_detail_image_26a15581be');
    ?>
    <!-- Case Study Detail Follow Up -->
    <section class="section-case-study-detail-follow-up section section-tight">
        <div class="container center">
            <?php if ($description !== ''): ?>
                <div class="prose " data-field="case_study_detail_28224860229f"><?php echo ps_section_text($description, 'case_study_detail_28224860229f', 'div', 'prose '); ?></div>
            <?php endif; ?>
            <?php ps_image('case_study_detail_image_26a15581be', 'results-chart', false, $image); ?>
        </div>
    </section>
<?php endif; ?>
