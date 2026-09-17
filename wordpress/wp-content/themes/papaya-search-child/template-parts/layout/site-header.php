<?php defined('ABSPATH') || exit(); ?>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Papaya Search home">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/brand.svg'); ?>" alt="Papaya Search — Be seen. Stay ahead. Grow smarter." width="340"
                height="66">
        </a>
        <button class="menu-toggle" hidden type="button" aria-controls="primary-navigation"
            aria-expanded="false" aria-label="Open navigation"><span
                aria-hidden="true">☰</span></button>
        <div id="primary-navigation"><?php ps_render_menu('primary'); ?></div>
    </div>
</header>
