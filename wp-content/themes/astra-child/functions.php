<?php
// Kế thừa CSS từ theme Astra gốc
add_action( 'wp_enqueue_scripts', 'fuzzy_enqueue_styles', 20 );
function fuzzy_enqueue_styles() {
    wp_enqueue_style( 'astra-parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'fuzzy-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'), filemtime( get_stylesheet_directory() . '/style.css' ) );
    
    // Google Fonts
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap', array(), null );
    
    // Font Awesome
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );
    
    // Custom JS
    wp_enqueue_script( 'fuzzy-custom-js', get_stylesheet_directory_uri() . '/custom.js', array('jquery'), filemtime( get_stylesheet_directory() . '/custom.js' ), true );
}

// Đăng ký Menu
register_nav_menus( array(
    'primary-menu' => __( 'Menu Chính', 'astra-child' ),
    'footer-menu'  => __( 'Menu Footer', 'astra-child' ),
) );

// Đăng ký Widget Areas
function fuzzy_widgets_init() {
    register_sidebar( array(
        'name'          => 'Footer Col 1',
        'id'            => 'footer-1',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
    ));
    register_sidebar( array(
        'name'          => 'Footer Col 2',
        'id'            => 'footer-2',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
    ));
    register_sidebar( array(
        'name'          => 'Footer Col 3',
        'id'            => 'footer-3',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
    ));
}
add_action( 'widgets_init', 'fuzzy_widgets_init' );

// WooCommerce Support
function fuzzy_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'fuzzy_woocommerce_support' );

// AJAX Search
add_action( 'wp_ajax_fuzzy_live_search', 'fuzzy_live_search' );
add_action( 'wp_ajax_nopriv_fuzzy_live_search', 'fuzzy_live_search' );
function fuzzy_live_search() {
    $search = sanitize_text_field( $_POST['query'] );
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 5,
        's'              => $search,
    );
    $query = new WP_Query( $args );
    $results = array();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $product = wc_get_product( get_the_ID() );
            $results[] = array(
                'title' => get_the_title(),
                'url'   => get_permalink(),
                'price' => $product->get_price_html(),
                'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
            );
        }
    }
    wp_reset_postdata();
    wp_send_json( $results );
}

// Localize script for AJAX
add_action( 'wp_enqueue_scripts', 'fuzzy_localize_scripts', 30 );
function fuzzy_localize_scripts() {
    wp_localize_script( 'fuzzy-custom-js', 'fuzzy_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'fuzzy_nonce' ),
    ));
}

// Shortcode: Sale Products
function fuzzy_sale_products_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'limit' => 8 ), $atts );
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => intval($atts['limit']),
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => '_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ),
        ),
    );
    $query = new WP_Query( $args );
    ob_start();
    if ( $query->have_posts() ) {
        echo '<div class="fuzzy-product-grid">';
        while ( $query->have_posts() ) {
            $query->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        echo '</div>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'fuzzy_sale_products', 'fuzzy_sale_products_shortcode' );

// Shortcode: Featured Products
function fuzzy_featured_products_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'limit' => 8 ), $atts );
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => intval($atts['limit']),
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ),
        ),
    );
    $query = new WP_Query( $args );
    ob_start();
    if ( $query->have_posts() ) {
        echo '<div class="fuzzy-product-grid">';
        while ( $query->have_posts() ) {
            $query->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        echo '</div>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'fuzzy_featured_products', 'fuzzy_featured_products_shortcode' );

// Shortcode: New Products
function fuzzy_new_products_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'limit' => 8 ), $atts );
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => intval($atts['limit']),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $query = new WP_Query( $args );
    ob_start();
    if ( $query->have_posts() ) {
        echo '<div class="fuzzy-product-grid">';
        while ( $query->have_posts() ) {
            $query->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        echo '</div>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'fuzzy_new_products', 'fuzzy_new_products_shortcode' );

// Shortcode: Products by Category
function fuzzy_category_products_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'category' => '', 'limit' => 4 ), $atts );
    if ( empty($atts['category']) ) return '';
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => intval($atts['limit']),
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $atts['category'],
            ),
        ),
    );
    $query = new WP_Query( $args );
    ob_start();
    if ( $query->have_posts() ) {
        echo '<div class="fuzzy-product-grid">';
        while ( $query->have_posts() ) {
            $query->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        echo '</div>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'fuzzy_category_products', 'fuzzy_category_products_shortcode' );

// Remove default Astra header/footer on front page
add_action('template_redirect', function() {
    if ( is_front_page() || is_page_template('front-page.php') ) {
        remove_action( 'astra_header', 'astra_header_markup' );
        remove_action( 'astra_footer', 'astra_footer_markup' );
    }
});
?>