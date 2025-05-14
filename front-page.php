<?php include 'includes/header.php'; ?>



<!-- offer section -->
<section class="offer_section layout_padding-bottom">
    <div class="offer_container">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>Tilaa Verkosta</h2>
                <p>Valitse sinulle sopivin toimitustapa</p>
            </div>
            <div class="row justify-content-center">
                <!-- Wolt Box -->
                <div class="col-md-6 col-lg-5">
                    <div class="delivery-box wolt">
                        <div class="img-box">
                            <img src="<?php echo get_theme_file_uri('images/Wolt.jpg'); ?>" alt="Wolt delivery">
                        </div>
                        <div class="content">
                            <h3>Tilaa Woltista</h3>
                            <p>Nopea toimitus suoraan kotiovellesi</p>
                            <?php if (get_field('turkinpippuri_wolt_link', 'option')): ?>
                                <a href="<?php echo esc_url(get_field('turkinpippuri_wolt_link', 'option')); ?>" class="order-btn" target="_blank">
                                    Tilaa Nyt
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Foodora Box -->
                <div class="col-md-6 col-lg-5">
                    <div class="delivery-box foodora">
                        <div class="img-box">
                            <img src="<?php echo get_theme_file_uri('images/foodora.png'); ?>" alt="Foodora delivery">
                        </div>
                        <div class="content">
                            <h3>Tilaa Foodorasta</h3>
                            <p>Laaja toimitusalue ja luotettava palvelu</p>
                            <?php if (get_field('turkinpippuri_foodora_link', 'option')): ?>
                                <a href="<?php echo esc_url(get_field('turkinpippuri_foodora_link', 'option')); ?>" class="order-btn" target="_blank">
                                    Tilaa Nyt
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- food section -->
<section class="food_section layout_padding" id="menu">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>Meidän Menu</h2>
            <p>Tutustu meidän herkullisiin annoksiin</p>
        </div>

        <ul class="filters_menu">
            <li data-filter=".pizza" class="active">Pizza</li>
            <li data-filter=".kebab">Kebab & Döner</li>
            <li data-filter=".burger">Burger</li>
            <li data-filter=".wings">Hot Wings</li>
            <li data-filter=".drinks">Juomat</li>
        </ul>

        <div class="filters-content">
            <div class="row grid">
                <?php
                // Define default images for each category
                $default_images = array(
                    'pizza' => get_theme_file_uri('images/defaults/pizza-default.jpg'),
                    'kebab' => get_theme_file_uri('images/defaults/kebab-default.jpg'),
                    'burger' => get_theme_file_uri('images/defaults/burger-default.jpg'),
                    'wings' => get_theme_file_uri('images/defaults/wings-default.jpg'),
                    'drinks' => get_theme_file_uri('images/defaults/drinks-default.jpg')
                );

                $menu_items = new WP_Query(array(
                    'post_type' => 'food_menu',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));

                if ($menu_items->have_posts()) :
                    while ($menu_items->have_posts()) : $menu_items->the_post();
                        $category = get_field('category');
                        $description = get_field('description');
                        $prices = get_field('prices');
                        $spicy_level = get_field('spicy_level');
                        
                        // Get the image URL (either featured image or default)
                        $image_url = has_post_thumbnail() ? 
                            get_the_post_thumbnail_url(get_the_ID(), 'medium') : 
                            $default_images[$category];
                        ?>
                        
                        <div class="col-sm-6 col-lg-4 all <?php echo esc_attr($category); ?>">
                            <div class="box">
                                <div class="img-box">
                                    <img src="<?php echo esc_url($image_url); ?>" 
                                         alt="<?php echo esc_attr(get_the_title()); ?>"
                                         class="<?php echo has_post_thumbnail() ? '' : 'default-img'; ?>">
                                    
                                    <?php if ($spicy_level) : ?>
                                        <div class="spicy-badge <?php echo esc_attr($spicy_level); ?>">
                                            <i class="fa fa-pepper-hot"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="detail-box">
                                    <h5><?php the_title(); ?></h5>
                                    <?php if ($description) : ?>
                                        <p class="description"><?php echo esc_html($description); ?></p>
                                    <?php endif; ?>

                                    <div class="options">
                                        <div class="price-box">
                                            <?php if ($prices['normal']) : ?>
                                                <div class="price-item">
                                                    <span class="label">Normaali</span>
                                                    <span class="price"><?php echo number_format($prices['normal'], 2); ?>€</span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($prices['family']) : ?>
                                                <div class="price-item">
                                                    <span class="label">Perhe</span>
                                                    <span class="price"><?php echo number_format($prices['family'], 2); ?>€</span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($prices['giant']) : ?>
                                                <div class="price-item">
                                                    <span class="label">Jätti</span>
                                                    <span class="price"><?php echo number_format($prices['giant'], 2); ?>€</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if (get_field('wolt_link')) : ?>
                                            <a href="<?php echo esc_url(get_field('wolt_link')); ?>" class="order-link" target="_blank">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </div>
</section>

<!-- Map section -->
<section class="map_section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="location_info">
                    <h2>Meistä</h2>
                    <div class="info_item">
                        <i class="fa fa-utensils"></i>
                        <div class="content">
                            <h3>Ravintola Turkinpippuri</h3>
                            <p>Tervetuloa Turkinpippuriin! Olemme palvelleet Rovaniemen asukkaita ja vierailijoita jo vuodesta 2010. Tarjoamme aitoja turkkilaisia makuja ja herkullisia pizzoja tuoreista raaka-aineista valmistettuna.</p>
                        </div>
                    </div>
                    
                    <div class="info_item">
                        <i class="fa fa-truck"></i>
                        <div class="content">
                            <h3>Kotiinkuljetus</h3>
                            <p>Ilmainen kotiinkuljetus 4 km säteellä keskustasta klo 12.00-21.00 yli 50€ tilauksille.</p>
                            <p>Tilaa suoraan meiltä soittamalla tai käytä Wolt/Foodora-palveluja.</p>
                        </div>
                    </div>

                    <div class="info_item">
                        <i class="fa fa-map-marker-alt"></i>
                        <div class="content">
                            <h3>Sijainti & Yhteystiedot</h3>
                            <p><strong>Osoite:</strong> Koskikatu 22, 96200 Rovaniemi</p>
                            <p><strong>Puhelin:</strong> <a href="tel:+35816319030">016 319 030</a></p>
                            <p class="location-note">Keskeinen sijainti kävelymatkan päässä Lordi-aukiolta</p>
                        </div>
                    </div>

                    <div class="info_item">
                        <i class="fa fa-clock"></i>
                        <div class="content">
                            <h3>Aukioloajat</h3>
                            <div class="opening-hours-grid">
                                <div class="day">Ma–To:</div><div class="hours">10.30–00.00</div>
                                <div class="day">Pe–La:</div><div class="hours">10.30–04.30</div>
                                <div class="day">Su:</div><div class="hours">12.00–00.00</div>
                            </div>
                            <p class="kitchen-note">Keittiö sulkeutuu 30min ennen sulkemisaikaa</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="map_container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1221.730931262208!2d25.7307001!3d66.5017333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x442b4bf31c30b935%3A0x9bb03e19e355e452!2sTurkinpippuri!5e0!3m2!1sen!2sfi!4v1696605630834!5m2!1sen!2sfi" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?> 