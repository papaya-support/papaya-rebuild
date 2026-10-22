<?php
/** Shared hero sections: read ACF groups here and keep their markup together. */
defined('ABSPATH') || exit();
$variant = $args['variant'] ?? '';
?>
<?php if ($variant === 'home-hero'):
    $section_hero = function_exists('get_field') ? (get_field('section_hero', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_hero, 'image', 'home_image_4b5fa21323');
    $heading = ps_section_value($section_hero, 'heading', 'home_1d791eb121c5');
    $image_2 = ps_section_value($section_hero, 'image_2', 'home_hero_emblem');
    ?>
    <!-- Home Hero -->
    <section class="section-home-hero home-hero">
        <?php ps_image('home_image_4b5fa21323', 'hero-image', true, $image); ?>
        <div class="container hero-copy">
            <h1 class=" is-uppercase" data-field="home_1d791eb121c5"><?php echo ps_section_text($heading, 'home_1d791eb121c5', 'h1', ' is-uppercase'); ?></h1>
        </div>
        <?php ps_image('home_hero_emblem', 'hero-emblem', true, $image_2); ?>
    </section>
<?php endif; ?>

<?php if ($variant === 'blog-hero'):
    $section_hero = function_exists('get_field') ? (get_field('section_hero', get_the_ID(), false) ?: []) : [];
    $image = ps_section_value($section_hero, 'image', 'blog_image_a770b85be1');
    $heading = ps_section_value($section_hero, 'heading', 'blog_2f0c8cf2c985');
    $description = ps_section_value($section_hero, 'description', 'blog_37a359563ced');
    ?>
    <!-- Blog Hero -->
    <section class="section-blog-hero listing-hero">
        <?php ps_image('blog_image_a770b85be1', 'hero-image', true, $image); ?>
        <div class="container intro center">
            <h1 class="" data-field="blog_2f0c8cf2c985"><?php echo ps_section_text($heading, 'blog_2f0c8cf2c985', 'h1', ''); ?></h1>
            <?php if ($description !== ''): ?>
                <div class="prose " data-field="blog_37a359563ced"><?php echo ps_section_text($description, 'blog_37a359563ced', 'div', 'prose '); ?></div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
