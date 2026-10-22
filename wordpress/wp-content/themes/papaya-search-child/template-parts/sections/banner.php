<?php
/** Shared banner sections: read ACF groups here and keep their markup together. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
?>
<?php if ($variant === 'services-banner'):
    $section_banner = function_exists('get_field') ? (get_field('section_banner', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_banner, 'image', 'services_image_95cc8a2f1e');
    ?>
    <!-- Services Banner -->
    <section class="section-services-banner page-banner">
        <?php ps_image('services_image_95cc8a2f1e', 'banner-image', true, $image); ?>
    </section>
<?php endif; ?>

<?php if ($variant === 'search-engine-marketing-banner'):
    $section_banner = function_exists('get_field') ? (get_field('section_banner', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_banner, 'image', 'search_engine_marketing_image_96ff85c5d5');
    ?>
    <!-- Search Engine Marketing Banner -->
    <section class="section-search-engine-marketing-banner page-banner">
        <?php ps_image('search_engine_marketing_image_96ff85c5d5', 'banner-image', true, $image); ?>
    </section>
<?php endif; ?>

<?php if ($variant === 'about-banner'):
    $section_banner = function_exists('get_field') ? (get_field('section_banner', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_banner, 'image', 'about_image_8a6938ebd6');
    ?>
    <!-- About Banner -->
    <section class="section-about-banner page-banner">
        <?php ps_image('about_image_8a6938ebd6', 'banner-image', true, $image); ?>
    </section>
<?php endif; ?>
