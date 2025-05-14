<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr(get_theme_mod('seo_description', 'Turkinpippuri - Herkullista pizzaa ja kebabia Rovaniemellä. Tilaa netistä tai tule paikan päälle!')); ?>">
    <meta name="keywords" content="<?php echo esc_attr(get_theme_mod('seo_keywords', 'pizza rovaniemi, kebab rovaniemi, ravintola rovaniemi, turkkilainen ravintola, kotiinkuljetus rovaniemi')); ?>">
    <meta name="geo.region" content="FI-LP">
    <meta name="geo.placename" content="Rovaniemi">
    <meta name="geo.position" content="66.5017333;25.7307001">
    <meta name="ICBM" content="66.5017333, 25.7307001">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo esc_attr(get_bloginfo('name')); ?> - Ravintola Rovaniemellä">
    <meta property="og:description" content="<?php echo esc_attr(get_theme_mod('seo_description', 'Herkullista pizzaa ja kebabia Rovaniemellä. Tilaa netistä tai tule paikan päälle!')); ?>">
    <meta property="og:image" content="<?php echo esc_url(get_theme_file_uri('images/hero-bg.jpg')); ?>">
    <meta property="og:type" content="restaurant.restaurant">
    <meta property="og:locale" content="fi_FI">
    <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
    <div class="hero_area">
        <div class="bg-box">
            <img src="<?php echo get_theme_file_uri('images/hero-bg.jpg'); ?>" alt="Restaurant background">
        </div>
        <header class="header_section">
            <div class="container">
                <nav class="navbar navbar-expand-lg custom_nav-container">
                    <div class="navbar-brand-container">
                        <?php if (has_custom_logo()): ?>
                            <?php the_custom_logo(); ?>
                        <?php else: ?>
                            <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                                <span><?php bloginfo('name'); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_class'     => 'navbar-nav mx-auto',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            'depth'          => 2,
                            'walker'         => new WP_Bootstrap_Navwalker()
                        ));
                        ?>
                        
                        <div class="user_option">
                            <?php if (get_field('turkinpippuri_wolt_link', 'option')): ?>
                                <a href="<?php echo esc_url(get_field('turkinpippuri_wolt_link', 'option')); ?>" class="order_online">
                                    <i class="fa fa-shopping-bag"></i>
                                    <?php esc_html_e('Tilaa', 'turkinpippuri'); ?>
                                </a>
                            <?php endif; ?>
                            <?php if (get_option('turkinpippuri_phone')): ?>
                                <a href="tel:<?php echo esc_attr(get_option('turkinpippuri_phone')); ?>" class="phone_link">
                                    <i class="fa fa-phone"></i>
                                    <?php echo esc_html(get_option('turkinpippuri_phone')); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Hero Content -->
        <div class="hero_content">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-6">
                        <div class="hero_detail">
                            <h1><?php echo get_theme_mod('hero_title', 'Tervetuloa'); ?></h1>
                            <div class="hero_info">
                                <div class="contact_info">
                                    <p><i class="fa fa-phone"></i> <?php esc_html_e('Soita ja tilaa:', 'turkinpippuri'); ?> <strong><?php echo esc_html(get_option('turkinpippuri_phone', '016 319 030')); ?></strong></p>
                                    <p><i class="fa fa-truck"></i> <strong><?php esc_html_e('Ilmainen kotiinkuljetus:', 'turkinpippuri'); ?></strong></p>
                                    <p class="delivery_info"><?php esc_html_e('12.00–21.00 yli 50 € tilauksille (4 km säteellä keskustasta)', 'turkinpippuri'); ?></p>
                                </div>
                                <div class="opening_hours">
                                    <h4><?php esc_html_e('Aukioloajat', 'turkinpippuri'); ?></h4>
                                    <ul>
                                        <li><span><?php esc_html_e('Ma–To:', 'turkinpippuri'); ?></span> 10.30–00.00 </li>
                                        <li><span><?php esc_html_e('Pe–La:', 'turkinpippuri'); ?></span> 10.30–04.30</li>
                                        <li><span><?php esc_html_e('Su:', 'turkinpippuri'); ?></span> 12.00–00.00 </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="hero_btns">
                                <?php if (get_option('turkinpippuri_wolt_link')): ?>
                                    <a href="<?php echo esc_url(get_option('turkinpippuri_wolt_link')); ?>" class="btn_primary">
                                        <?php esc_html_e('Tilaa Woltista', 'turkinpippuri'); ?>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="#menu" class="btn_secondary">
                                    <?php esc_html_e('Katso Menu', 'turkinpippuri'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>