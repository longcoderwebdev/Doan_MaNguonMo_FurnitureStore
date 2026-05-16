<?php
/**
 * Template Name: Fuzzy Homepage
 * Custom full-width homepage for Furniture's Store
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Cart info
$cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_total = function_exists('WC') && WC()->cart ? strip_tags(WC()->cart->get_cart_total()) : '0 ₫';

// Categories
$categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => 0, 'number' => 6, 'exclude' => array(get_option('default_product_cat'))));
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class('fuzzy-homepage'); ?>>
<?php wp_body_open(); ?>

<!-- ===== CUSTOM HEADER ===== -->
<div class="fuzzy-header">
    <div class="fuzzy-header-top">
        <a href="<?php echo home_url(); ?>" class="fuzzy-logo">
            <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
            <div class="fuzzy-logo-text">FURNITURE'S<span>STORE</span></div>
            <?php endif; ?>
        </a>
        <div class="fuzzy-search">
            <input type="text" id="fuzzy-search-input" placeholder="Tìm kiếm sản phẩm tại đây..." autocomplete="off" />
            <button type="button" id="fuzzy-search-btn"><i class="fas fa-search"></i></button>
            <div class="fuzzy-search-results" id="fuzzy-search-results"></div>
        </div>
        <div class="fuzzy-header-actions">
            <?php $acc_url = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('myaccount')) : '#'; ?>
            <a href="<?php echo $acc_url; ?>" class="fuzzy-btn-login">
                <i class="fas fa-user"></i> <?php echo is_user_logged_in() ? 'TÀI KHOẢN' : 'ĐĂNG NHẬP'; ?>
            </a>
            <?php $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '#'; ?>
            <a href="<?php echo $cart_url; ?>" class="fuzzy-btn-cart">
                <i class="fas fa-shopping-cart"></i> <?php echo $cart_total; ?>
                <?php if ($cart_count > 0) : ?><span class="cart-count"><?php echo $cart_count; ?></span><?php endif; ?>
            </a>
        </div>
    </div>
    <nav class="fuzzy-nav">
        <div class="fuzzy-nav-inner">
            <?php if ( has_nav_menu('primary-menu') ) :
                wp_nav_menu(array('theme_location' => 'primary-menu', 'container' => false));
            else : ?>
            <ul>
                <li class="active"><a href="<?php echo home_url(); ?>">TRANG CHỦ</a></li>
                <?php if (!empty($categories)) : foreach (array_slice($categories, 0, 4) as $cat) : ?>
                <li><a href="<?php echo get_term_link($cat); ?>"><?php echo strtoupper(esc_html($cat->name)); ?> <i class="fas fa-chevron-down" style="font-size:10px"></i></a></li>
                <?php endforeach; endif; ?>
                <li><a href="<?php echo home_url('/blog'); ?>">BLOGS</a></li>
                <li><a href="<?php echo home_url('/lien-he'); ?>">LIÊN HỆ CHÚNG TÔI</a></li>
            </ul>
            <?php endif; ?>
        </div>
    </nav>
</div>

<!-- ===== HERO SLIDER ===== -->
<div class="fuzzy-hero">
    <div class="fuzzy-hero-slides" id="heroSlides">
        <div class="fuzzy-hero-slide" style="background-image: url('http://localhost/LongWEB/wp-content/uploads/2026/05/Carousel_1.jpg'); background-position: center; min-width:100%; height:450px; display:flex; align-items:center; justify-content:center;">
            <div class="fuzzy-hero-overlay">
                <h2>FURNITURE'S STORE</h2>
                <p>SOFA | BÀN GHẾ | TỦ KỆ | ĐÈN TRANG TRÍ</p>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="hero-btn">XEM TẠI ĐÂY</a>
            </div>
        </div>
        <div class="fuzzy-hero-slide" style="background-image: url('http://localhost/LongWEB/wp-content/uploads/2026/05/ANH3.jpg'); background-position: center; min-width:100%; height:450px; display:flex; align-items:center; justify-content:center;">
            <div class="fuzzy-hero-overlay">
                <h2>BỘ SƯU TẬP MỚI</h2>
                <p>PHONG CÁCH HIỆN ĐẠI - SANG TRỌNG</p>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="hero-btn">KHÁM PHÁ NGAY</a>
            </div>
        </div>
        <div class="fuzzy-hero-slide" style="background-image: url('http://localhost/LongWEB/wp-content/uploads/2026/05/ANH2.jpg'); background-position: center; min-width:100%; height:450px; display:flex; align-items:center; justify-content:center;">
            <div class="fuzzy-hero-overlay">
                <h2>GIẢM GIÁ LÊN ĐẾN 50%</h2>
                <p>ƯU ĐÃI CÓ HẠN - MUA NGAY HÔM NAY</p>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="hero-btn">MUA NGAY</a>
            </div>
        </div>
    </div>
    <button class="fuzzy-hero-arrow prev" onclick="moveSlide(-1)"><i class="fas fa-chevron-left"></i></button>
    <button class="fuzzy-hero-arrow next" onclick="moveSlide(1)"><i class="fas fa-chevron-right"></i></button>
    <div class="fuzzy-hero-dots" id="heroDots"></div>
</div>

<!-- ===== FEATURES ===== -->
<div class="fuzzy-section">
    <div class="fuzzy-features">
        <div class="fuzzy-feature-item"><i class="fas fa-truck"></i><div class="feat-text"><h4>Miễn phí vận chuyển</h4><p>Đơn hàng từ 2.000.000₫</p></div></div>
        <div class="fuzzy-feature-item"><i class="fas fa-sync-alt"></i><div class="feat-text"><h4>Đổi trả miễn phí</h4><p>Trong vòng 30 ngày</p></div></div>
        <div class="fuzzy-feature-item"><i class="fas fa-shield-alt"></i><div class="feat-text"><h4>Bảo hành chính hãng</h4><p>Bảo hành 12 tháng</p></div></div>
        <div class="fuzzy-feature-item"><i class="fas fa-headset"></i><div class="feat-text"><h4>Hỗ trợ 24/7</h4><p>Hotline: 1900-xxxx</p></div></div>
    </div>
</div>

<!-- ===== SALE PRODUCTS ===== -->
<div class="fuzzy-section">
    <div class="fuzzy-section-title">
        <h2><i class="fas fa-star"></i> SIÊU KHUYẾN MÃI</h2>
        <p>Những sản phẩm đang được giảm giá hấp dẫn nhất</p>
    </div>
    <div class="woocommerce">
    <?php
    $sale_ids = wc_get_product_ids_on_sale();
    if (!empty($sale_ids)) {
        echo do_shortcode('[products ids="'.implode(',', array_slice($sale_ids, 0, 8)).'" columns="4"]');
    } else {
        echo do_shortcode('[products limit="8" columns="4" orderby="date"]');
    }
    ?>
    </div>
</div>

<!-- ===== CATEGORIES ===== -->
<?php if (!empty($categories)) : ?>
<div class="fuzzy-section">
    <div class="fuzzy-section-title">
        <h2><i class="fas fa-th-large"></i> DANH MỤC SẢN PHẨM</h2>
        <p>Khám phá theo từng danh mục</p>
    </div>
    <div class="fuzzy-category-grid">
        <?php
        $cat_colors = array(
            'linear-gradient(135deg, #e8a838 0%, #c4842a 100%)',
            'linear-gradient(135deg, #3498db 0%, #2980b9 100%)',
            'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)',
        );
        $i = 0;
        foreach (array_slice($categories, 0, 3) as $cat) :
            $cat_link = get_term_link($cat);
            $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
            $cat_img = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
        ?>
        <a href="<?php echo esc_url($cat_link); ?>" class="fuzzy-category-card" style="<?php if(!$cat_img) echo 'background:'.$cat_colors[$i % 3].';'; ?>">
            <?php if($cat_img) : ?><img src="<?php echo $cat_img; ?>" alt="<?php echo esc_attr($cat->name); ?>" /><?php endif; ?>
            <div class="cat-overlay">
                <div>
                    <h3><?php echo esc_html($cat->name); ?></h3>
                    <span><?php echo $cat->count; ?> sản phẩm</span>
                </div>
            </div>
        </a>
        <?php $i++; endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- ===== FEATURED PRODUCTS ===== -->
<div class="fuzzy-section">
    <div class="fuzzy-section-title">
        <h2><i class="fas fa-fire"></i> SẢN PHẨM NỔI BẬT</h2>
        <p>Được yêu thích và bán chạy nhất</p>
    </div>
    <div class="woocommerce">
        <?php echo do_shortcode('[products limit="8" columns="4" orderby="popularity"]'); ?>
    </div>
</div>

<!-- ===== NEW PRODUCTS ===== -->
<div class="fuzzy-section">
    <div class="fuzzy-section-title">
        <h2><i class="fas fa-leaf"></i> SẢN PHẨM MỚI NHẤT</h2>
        <p>Vừa cập nhật trong tuần này</p>
    </div>
    <div class="woocommerce">
        <?php echo do_shortcode('[recent_products limit="8" columns="4"]'); ?>
    </div>
</div>

<!-- ===== FOOTER ===== -->
<footer class="fuzzy-footer">
    <div class="fuzzy-footer-inner">
        <div class="fuzzy-footer-col">
            <h3>FURNITURE'S STORE</h3>
            <p>Cung cấp nội thất cao cấp, phong cách hiện đại. Cam kết chất lượng và dịch vụ tốt nhất cho khách hàng.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>
        <div class="fuzzy-footer-col">
            <h3>Về chúng tôi</h3>
            <ul>
                <li><a href="#">Giới thiệu</a></li>
                <li><a href="#">Hệ thống cửa hàng</a></li>
                <li><a href="#">Chính sách bảo mật</a></li>
                <li><a href="#">Điều khoản sử dụng</a></li>
            </ul>
        </div>
        <div class="fuzzy-footer-col">
            <h3>Hỗ trợ khách hàng</h3>
            <ul>
                <li><a href="#">Hướng dẫn mua hàng</a></li>
                <li><a href="#">Chính sách đổi trả</a></li>
                <li><a href="#">Phương thức thanh toán</a></li>
                <li><a href="#">Vận chuyển & giao hàng</a></li>
            </ul>
        </div>
        <div class="fuzzy-footer-col">
            <h3>Liên hệ</h3>
            <ul>
                <li><a href="#"><i class="fas fa-map-marker-alt"></i> 123 Nguyễn Văn Linh, TP.HCM</a></li>
                <li><a href="tel:19001234"><i class="fas fa-phone"></i> 1900-1234</a></li>
                <li><a href="mailto:info@furniturestore.vn"><i class="fas fa-envelope"></i> info@furniturestore.vn</a></li>
            </ul>
        </div>
    </div>
    <div class="fuzzy-footer-bottom">
        &copy; <?php echo date('Y'); ?> Furniture's Store. All Rights Reserved. | Designed by Nguyễn Hải Long &amp; Team
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
