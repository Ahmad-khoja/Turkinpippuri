<?php
if (!class_exists('WP_Bootstrap_Navwalker')) {
    class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {
        public function start_lvl(&$output, $depth = 0, $args = array()) {
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
        }

        public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
            $indent = ($depth) ? str_repeat("\t", $depth) : '';

            $classes = empty($item->classes) ? array() : (array) $item->classes;
            $classes[] = 'nav-item';
            $classes[] = 'menu-item-' . $item->ID;

            if ($args->walker->has_children) {
                $classes[] = 'dropdown';
            }

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

            $output .= $indent . '<li' . $class_names . '>';

            $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
            $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
            $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
            $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
            $attributes .= ' class="nav-link"';

            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }
    }
}

function turkinpippuri_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for custom header image
    add_theme_support('custom-header', array(
        'default-image' => get_template_directory_uri() . '/images/hero-bg.jpg',
        'width'         => 1920,
        'height'        => 1080,
        'flex-width'    => true,
        'flex-height'   => true,
    ));

    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'turkinpippuri'),
        'footer'  => esc_html__('Footer Menu', 'turkinpippuri'),
    ));
}
add_action('after_setup_theme', 'turkinpippuri_setup');

// Enqueue scripts and styles
function turkinpippuri_scripts() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
    
    // Owl Carousel CSS
    wp_enqueue_style('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
    
    // Font Awesome
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
    
    // Custom CSS
    wp_enqueue_style('turkinpippuri-style', get_stylesheet_uri());
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/style.css');
    wp_enqueue_style('responsive-style', get_template_directory_uri() . '/css/responsive.css');

    // jQuery (already included with WordPress)
    wp_enqueue_script('jquery');
    
    // Bootstrap JS
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '', true);
    
    // Owl Carousel JS
    wp_enqueue_script('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), '', true);
    
    // Isotope JS
    wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js', array('jquery'), '', true);
    
    // Custom JS
    wp_enqueue_script('custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'turkinpippuri_scripts'); 

// Add ACF Options Page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => __('Restaurant Settings', 'turkinpippuri'),
        'menu_title' => __('Restaurant Settings', 'turkinpippuri'),
        'menu_slug'  => 'restaurant-settings',
        'capability' => 'edit_posts',
        'redirect'   => false
    ));
}

// Register Menu post type for ACF
function turkinpippuri_register_menu_post_type() {
    register_post_type('food_menu', array(
        'labels' => array(
            'name'          => __('Menu Items', 'turkinpippuri'),
            'singular_name' => __('Menu Item', 'turkinpippuri'),
        ),
        'public'        => true,
        'has_archive'   => false,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-food',
        'supports'      => array('title', 'thumbnail'),
        'show_in_rest'  => true,
    ));
}
add_action('init', 'turkinpippuri_register_menu_post_type');

// Add custom meta box for menu item prices
function turkinpippuri_add_price_meta_box() {
    add_meta_box(
        'menu_item_prices',
        __('Menu Item Prices', 'turkinpippuri'),
        'turkinpippuri_price_meta_box_callback',
        'menu_item',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'turkinpippuri_add_price_meta_box');

// Meta box callback function
function turkinpippuri_price_meta_box_callback($post) {
    wp_nonce_field('turkinpippuri_save_meta_box_data', 'turkinpippuri_meta_box_nonce');

    $normal_price = get_post_meta($post->ID, '_normal_price', true);
    $family_price = get_post_meta($post->ID, '_family_price', true);
    $giant_price = get_post_meta($post->ID, '_giant_price', true);

    echo '<p>';
    echo '<label for="normal_price">' . __('Normal Price', 'turkinpippuri') . '</label> ';
    echo '<input type="text" id="normal_price" name="normal_price" value="' . esc_attr($normal_price) . '" />';
    echo '</p>';
    
    echo '<p>';
    echo '<label for="family_price">' . __('Family Price', 'turkinpippuri') . '</label> ';
    echo '<input type="text" id="family_price" name="family_price" value="' . esc_attr($family_price) . '" />';
    echo '</p>';
    
    echo '<p>';
    echo '<label for="giant_price">' . __('Giant Price', 'turkinpippuri') . '</label> ';
    echo '<input type="text" id="giant_price" name="giant_price" value="' . esc_attr($giant_price) . '" />';
    echo '</p>';
}

// Save meta box data
function turkinpippuri_save_meta_box_data($post_id) {
    if (!isset($_POST['turkinpippuri_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['turkinpippuri_meta_box_nonce'], 'turkinpippuri_save_meta_box_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $fields = array('normal_price', 'family_price', 'giant_price');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'turkinpippuri_save_meta_box_data');

// Add theme options page
function turkinpippuri_add_theme_page() {
    add_theme_page(
        __('Theme Settings', 'turkinpippuri'),
        __('Theme Settings', 'turkinpippuri'),
        'manage_options',
        'turkinpippuri-settings',
        'turkinpippuri_theme_page_html'
    );
}
add_action('admin_menu', 'turkinpippuri_add_theme_page');

// Theme options page callback
function turkinpippuri_theme_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('turkinpippuri_options');
            do_settings_sections('turkinpippuri-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register theme settings
function turkinpippuri_register_settings() {
    // Register Wolt Link setting
    register_setting('general', 'turkinpippuri_wolt_link', array(
        'type' => 'string',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    // Register Foodora Link setting
    register_setting('general', 'turkinpippuri_foodora_link', array(
        'type' => 'string',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    // Add settings fields to Restaurant Settings page
    add_settings_section(
        'turkinpippuri_delivery_settings',
        __('Delivery Service Links', 'turkinpippuri'),
        null,
        'restaurant-settings'
    );
    
    add_settings_field(
        'turkinpippuri_wolt_link',
        __('Wolt Link', 'turkinpippuri'),
        'turkinpippuri_url_field_callback',
        'restaurant-settings',
        'turkinpippuri_delivery_settings',
        array('label_for' => 'turkinpippuri_wolt_link')
    );
    
    add_settings_field(
        'turkinpippuri_foodora_link',
        __('Foodora Link', 'turkinpippuri'),
        'turkinpippuri_url_field_callback',
        'restaurant-settings',
        'turkinpippuri_delivery_settings',
        array('label_for' => 'turkinpippuri_foodora_link')
    );
}
add_action('admin_init', 'turkinpippuri_register_settings');

// Callback function for URL fields
function turkinpippuri_url_field_callback($args) {
    $option = get_option($args['label_for']);
    ?>
    <input type="url" 
           id="<?php echo esc_attr($args['label_for']); ?>"
           name="<?php echo esc_attr($args['label_for']); ?>"
           value="<?php echo esc_url($option); ?>"
           class="regular-text"
           placeholder="https://"
    >
    <?php
}

function turkinpippuri_enqueue_scripts() {
    // Add Font Awesome first
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );
    
    // Then add your main stylesheet
    wp_enqueue_style(
        'turkinpippuri-style',
        get_stylesheet_uri(),
        array('font-awesome'),
        wp_get_theme()->get('Version')
    );
    
    // Then add your custom styles
    wp_enqueue_style(
        'turkinpippuri-custom',
        get_template_directory_uri() . '/css/style.css',
        array('turkinpippuri-style'),
        wp_get_theme()->get('Version')
    );

    // Scripts remain the same...
}
add_action('wp_enqueue_scripts', 'turkinpippuri_enqueue_scripts');

// Add after your existing ACF related code
if (function_exists('acf_add_local_field_group')) {
    $settings_json = file_get_contents(get_template_directory() . '/settings-export.json');
    $settings_array = json_decode($settings_json, true);
    
    if ($settings_array) {
        acf_add_local_field_group($settings_array);
    }
}

function turkinpippuri_add_schema_markup() {
    $schema = array(
        "@context" => "https://schema.org",
        "@type" => "Restaurant",
        "name" => "Turkinpippuri",
        "image" => get_theme_file_uri('images/hero-bg.jpg'),
        "description" => get_theme_mod('seo_description', 'Herkullista pizzaa ja kebabia Rovaniemellä'),
        "@id" => home_url('/'),
        "url" => home_url('/'),
        "telephone" => "+35816319030",
        "address" => array(
            "@type" => "PostalAddress",
            "streetAddress" => "Koskikatu 22",
            "addressLocality" => "Rovaniemi",
            "postalCode" => "96200",
            "addressCountry" => "FI"
        ),
        "geo" => array(
            "@type" => "GeoCoordinates",
            "latitude" => 66.5017333,
            "longitude" => 25.7307001
        ),
        "openingHoursSpecification" => [
            [
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday"],
                "opens" => "10:30",
                "closes" => "00:00"
            ],
            [
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => ["Friday", "Saturday"],
                "opens" => "10:30",
                "closes" => "04:30"
            ],
            [
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => "Sunday",
                "opens" => "12:00",
                "closes" => "00:00"
            ]
        ],
        "servesCuisine" => ["Pizza", "Kebab", "Turkish"],
        "priceRange" => "€€",
        "paymentAccepted" => "Cash, Credit Card",
        "deliveryArea" => "Rovaniemi city center (4 km radius)",
        "menu" => home_url('/#menu')
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action('wp_head', 'turkinpippuri_add_schema_markup');

// Add customizer settings for SEO
function turkinpippuri_customize_register($wp_customize) {
    // Add SEO section
    $wp_customize->add_section('seo_settings', array(
        'title' => __('SEO Settings', 'turkinpippuri'),
        'priority' => 120,
    ));

    // Add Meta Description setting
    $wp_customize->add_setting('seo_description', array(
        'default' => 'Turkinpippuri - Herkullista pizzaa ja kebabia Rovaniemellä. Tilaa netistä tai tule paikan päälle!',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('seo_description', array(
        'label' => __('Meta Description', 'turkinpippuri'),
        'section' => 'seo_settings',
        'type' => 'textarea',
    ));

    // Add Meta Keywords setting
    $wp_customize->add_setting('seo_keywords', array(
        'default' => 'pizza rovaniemi, kebab rovaniemi, ravintola rovaniemi, turkkilainen ravintola, kotiinkuljetus rovaniemi',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('seo_keywords', array(
        'label' => __('Meta Keywords', 'turkinpippuri'),
        'section' => 'seo_settings',
        'type' => 'text',
    ));
}
add_action('customize_register', 'turkinpippuri_customize_register');
