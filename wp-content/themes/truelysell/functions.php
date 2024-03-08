<?php
/**
 * truelysell functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package truelysell
 */

update_option( 'Tselling_new_lic_Key', 'activated' );
if ( ! function_exists( 'truelysell_setup' ) ) :
	
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */

global $wpdb;

function truelysell_setup() {
	load_theme_textdomain( 'truelysell', get_template_directory() . '/languages' );

  

	if(in_array('redux-framework/redux-framework.php', apply_filters('active_plugins', get_option('active_plugins'))))
	 {
		 require_once( dirname(__FILE__).'/inc/options-init.php' );
	 }


	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(900, 500, true); //size of thumbs
	add_image_size( 'truelysell-avatar', 590, 590 );
	add_image_size( 'truelysell-blog-post', 1200, 670 );
	add_image_size( 'truelysell-blog-related-post', 577, 866 );
	add_image_size( 'truelysell-post-thumb', 150, 150, true );
	
	

	add_editor_style();
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'truelysell_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

 	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'truelysell_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function truelysell_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'truelysell_content_width', 760 );
}
add_action( 'after_setup_theme', 'truelysell_content_width', 0 );

/**
 * Register widget area.
 */
function truelysell_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'truelysell' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
		'before_widget' => '<div id="%1$s" class="card widget %2$s"><div class="card-body">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h4 class="side-title">',
		'after_title'   => '</h4>',
	) );	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Area1', 'truelysell' ),
			'id'            => 'footerarea-1',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s footer-widget footer-content">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Area2', 'truelysell' ),
			'id'            => 'footerarea-2',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s footer-menu">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Area3', 'truelysell' ),
			'id'            => 'footerarea-3',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s footer-contact">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Area4', 'truelysell' ),
			'id'            => 'footerarea-4',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s ">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Style2 Area1', 'truelysell' ),
			'id'            => 'footerareastwo-1',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s ">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Style2 Area2', 'truelysell' ),
			'id'            => 'footerareastwo-2',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s ">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Style2 Area3', 'truelysell' ),
			'id'            => 'footerareastwo-3',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s ">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Style2 Area4', 'truelysell' ),
			'id'            => 'footerareastwo-4',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s ">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Style2 Area5', 'truelysell' ),
			'id'            => 'footerareastwo-5',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s ">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);

 

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Copyright Menu', 'truelysell' ),
			'id'            => 'footerarea-copyright-menu',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s copyright-menu policy-menu">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Bookmark Widget', 'truelysell' ),
			'id'            => 'bookmark_widget',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Bookservice Widget', 'truelysell' ),
			'id'            => 'bookservice_widget',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell' ),
			'before_widget' => '<section id="%1$s" class="listing-widget  %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'truelysell_widgets_init' );


add_action('after_switch_theme', 'truelysell_setup_options');

function truelysell_setup_options () {
  	update_option('truelysell_activation_date',time());
}
/**
 * Enqueue scripts and styles.
 */
function truelysell_scripts() {
	
	$my_theme = wp_get_theme();
	$ver_num = 1.9;

   wp_enqueue_style( 'google-font-roboto', 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap',array(),  $ver_num );
   wp_enqueue_style( 'google-font-poppins', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap',array(),  $ver_num );
   wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '5.2.3');
   wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/assets/plugins/fontawesome/css/fontawesome.min.css',array(),  $ver_num );
   wp_enqueue_style( 'allmin', get_template_directory_uri().'/assets/plugins/fontawesome/css/all.min.css',array(),  $ver_num );
   wp_enqueue_style( 'feather', get_template_directory_uri().'/assets/css/feather.css',array(),  $ver_num );
   wp_enqueue_style( 'select2', get_template_directory_uri().'/assets/plugins/select2/css/select2.min.css',array(),  $ver_num );
   wp_enqueue_style( 'owl-carousel', trailingslashit(get_template_directory_uri()).'/assets/css/owl.carousel.min.css',array(),  $ver_num  );
   wp_enqueue_style( 'aos', get_template_directory_uri().'/assets/plugins/aos/aos.css',array(),  $ver_num );
   wp_enqueue_style( 'bootstrap-datetimepicker', get_template_directory_uri().'/assets/css/bootstrap-datetimepicker.min.css',array(),  $ver_num );
   wp_enqueue_style( 'truelysell-fancybox', trailingslashit(get_template_directory_uri()).'/assets/plugins/fancybox/jquery.fancybox.min.css',array(),  $ver_num  );
   wp_enqueue_style( 'datatables', get_template_directory_uri().'/assets/plugins/datatables/datatables.min.css',array(),  $ver_num );
   wp_enqueue_style( 'truelysell-default', get_template_directory_uri().'/assets/css/default-css.css',array(),  $ver_num );
   wp_enqueue_style( 'truelysell-woocommerce', get_template_directory_uri().'/assets/css/theme-woocommerce.css',array(),  $ver_num );
   wp_enqueue_style( 'truelysell-main', get_template_directory_uri().'/assets/css/style.css',array(),  $ver_num );
   wp_enqueue_style( 'truelysell-responsive', get_template_directory_uri().'/assets/css/responsive.css',array(),  $ver_num );
  
   //3rd party js
   wp_enqueue_script('bootstrap-bundle', trailingslashit(get_template_directory_uri()) . '/assets/js/bootstrap.bundle.min.js', false, false, true);
   wp_enqueue_script('owl-carousel', trailingslashit(get_template_directory_uri()) . '/assets/js/owl.carousel.min.js', false, false, true);
   wp_enqueue_script('aos', trailingslashit(get_template_directory_uri()) . '/assets/plugins/aos/aos.js', false, false, true);
   wp_enqueue_script('backToTopjs', trailingslashit(get_template_directory_uri()) . '/assets/js/backToTop.js', false, false, true);
   wp_enqueue_script('select2', trailingslashit(get_template_directory_uri()) . '/assets/plugins/select2/js/select2.min.js', false, false, true);
   wp_enqueue_script('bootstrap-datetimepicker', trailingslashit(get_template_directory_uri()) . '/assets/js/bootstrap-datetimepicker.min.js', false, false, true);
   wp_enqueue_script('resizesensor', trailingslashit(get_template_directory_uri()) . '/assets/plugins/theia-sticky-sidebar/ResizeSensor.js', false, false, true);
   wp_enqueue_script('theia-sticky-sidebar', trailingslashit(get_template_directory_uri()) . '/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js', false, false, true);
   wp_enqueue_script('jquery-fancybox', trailingslashit(get_template_directory_uri()) . '/assets/plugins/fancybox/jquery.fancybox.min.js', false, false, true);
   wp_enqueue_script('jquery-dataTables', trailingslashit(get_template_directory_uri()) . '/assets/plugins/datatables/jquery.dataTables.min.js', false, false, true);
   wp_enqueue_script('datatables', trailingslashit(get_template_directory_uri()) . '/assets/plugins/datatables/datatables.min.js', false, false, true);
   wp_enqueue_script('truelysell-script', trailingslashit(get_template_directory_uri()) . '/assets/js/script.js', false, false, true);

	if(get_option('truelysell_iconsmind')!='hide'){
		wp_enqueue_style( 'truelysell-iconsmind');
	}
	 
	wp_register_script( 'chosen', get_template_directory_uri() . '/assets/js/chosen.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'select2', get_template_directory_uri() . '/assets/plugins/select2/js/select2.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'counterup', get_template_directory_uri() . '/assets/js/counterup.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'counterupj', get_template_directory_uri() . '/assets/js/jquery.counterup.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'jquery-scrollto', get_template_directory_uri() . '/assets/js/jquery.scrollto.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'datedropper', get_template_directory_uri() . '/assets/js/datedropper.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'dropzone', get_template_directory_uri() . '/assets/js/dropzone.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'isotope', get_template_directory_uri() . '/assets/js/isotope.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'jquery-countdown', get_template_directory_uri() . '/assets/js/jquery.countdown.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'quantitybuttons', get_template_directory_uri() . '/assets/js/quantityButtons.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'rangeslider', get_template_directory_uri() . '/assets/js/rangeslider.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'timedropper', get_template_directory_uri() . '/assets/js/timedropper.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'waypoints', get_template_directory_uri() . '/assets/js/tooltips.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'waypoints', get_template_directory_uri() . '/assets/js/waypoints.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'slick', get_template_directory_uri() . '/assets/js/slick.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'mmenu', get_template_directory_uri() . '/assets/js/mmenu.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'moment', get_template_directory_uri() . '/assets/js/moment.min.js', array( 'jquery' ), $ver_num );
	wp_register_script( 'daterangepicker', get_template_directory_uri() . '/assets/js/daterangepicker.js', array( 'jquery','moment' ), $ver_num );
 	wp_register_script( 'flatpickr', get_template_directory_uri() . '/assets/js/flatpickr.js', array( 'jquery' ), $ver_num );
 	wp_register_script( 'bootstrap-slider', get_template_directory_uri() . '/assets/js/bootstrap-slider.min.js', array( 'jquery' ), $ver_num );
	
 	wp_enqueue_script( 'select2' );
	wp_enqueue_script( 'counterup' );
	wp_enqueue_script( 'counterupj' );
	wp_enqueue_script( 'datedropper' );
	wp_enqueue_script( 'dropzone' );
	if ( is_page_template( 'template-comming-soon.php' ) ) {
		wp_enqueue_script( 'jquery-countdown' );
	}
	wp_enqueue_script( 'mmenu' );
	wp_enqueue_script( 'slick' );
	wp_enqueue_script( 'quantitybuttons' );
	wp_enqueue_script( 'rangeslider' );
	wp_enqueue_script( 'timedropper' );
	wp_enqueue_script( 'jquery-scrollto' );
	wp_enqueue_script( 'waypoints' );
	wp_enqueue_script( 'waypoints' );
	wp_enqueue_script( 'moment' );
	wp_enqueue_script( 'daterangepicker' );
	wp_enqueue_script( 'bootstrap-slider' );
	wp_enqueue_script( 'flatpickr' );
 	wp_enqueue_script( 'truelysell-themescript');
	wp_enqueue_script( 'truelysell-custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), '20170821', true );
	
	$convertedData = truelysell_get_datetime_wp_format();
	// add converented format date to javascript
	wp_localize_script( 'truelysell-custom', 'wordpress_date_format', $convertedData );
	$ajax_url = admin_url( 'admin-ajax.php', 'relative' );
	wp_localize_script( 'truelysell-custom', 'truelysell',
    array(
        'ajaxurl' 				=> $ajax_url,
        'theme_url'				=> get_template_directory_uri(),
        )
    );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'truelysell_scripts' );
add_action(  'admin_enqueue_scripts', 'truelysell_admin_scripts' );
function truelysell_admin_scripts($hook){

	if($hook=='edit-tags.php' || $hook == 'term.php'|| $hook == 'post.php' || $hook == 'toplevel_page_truelysell_settings' || $hook = 'truelysell-core_page_truelysell_license'){
		wp_enqueue_style( 'truelysell-icons', get_template_directory_uri(). '/assets/css/all.css' );
		wp_enqueue_style( 'truelysell-icons-fav4', get_template_directory_uri(). '/assets/css/fav4-shims.min.css' );
	}
}
function truelysell_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'truelysell_add_editor_styles' );

 
require get_template_directory() . '/inc/shop-func.php';
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/theme-addon.php';

/**
 * Custom meta-boxes
 */
require get_template_directory() . '/inc/add-demo-metaboxes.php';

/**
 * Customizer additions.
 */
////require get_template_directory() . '/inc/theme-options.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

function truelysell_add_apple_google_pay()
{
	return array(
		'simple',
		'variable',
		'variation',
		'subscription',
		'variable-subscription',
		'subscription_variation',
		'listing_booking',
		'listing_package_subscription',
		'booking',
		'bundle',
		'composite'
	);
}
add_filter('wc_stripe_payment_request_supported_types', 'truelysell_add_apple_google_pay'); 
/**
 * Load TGMPA file.
 */
require get_template_directory() . '/inc/tgmpa.php';

/**
 * Load woocommerce 
 */
require get_template_directory() . '/inc/woocommerce.php';
/**
 * Setup Wizard
 */
// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');

function truelysell_new_customer_data($new_customer_data){
 $new_customer_data['role'] = 'owner';
 return $new_customer_data;
}
add_filter( 'woocommerce_new_customer_data', 'truelysell_new_customer_data');

function truelysell_noindex_for_products()
{
    if ( is_singular( 'product' ) ) {
    	global $post;
    	if( function_exists('wc_get_product') ){
    		$product = wc_get_product( $post->ID );
    		//listing_booking, listing_package_subscription, listing_package
            if( $product->is_type( 'listing_booking' ) || $product->is_type( 'listing_package_subscription' ) || $product->is_type( 'listing_package' )  ){
            	echo '<meta name="robots" content="noindex, follow">';
            }
    	}
        
    }
}
add_action('wp_head', 'truelysell_noindex_for_products');

function truelysell_header_menu() {
	register_nav_menu('header_menu',esc_html( 'Header Menu' ));
  }
  add_action( 'init', 'truelysell_header_menu' );
 function truelysell_category_pagination() {
  
    if( is_singular() )
        return;
  
    global $wp_query;
  
    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;
  
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );
  
    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;
  
    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
  
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
  
    echo '<div class="pagination"><ul>' . "\n";
  
    /** Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li class="arrow pagination_arrow">%s</li>' . "\n", get_previous_posts_link( __( '<i class="fas fa-angle-left"></i>', 'truelysell' ) ) );
  
    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';
  
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
  
        if ( ! in_array( 2, $links ) )
            echo '<li>…</li>';
    }
  
    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }
  
    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li>…</li>' . "\n";
  
        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }
  
    /** Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li class="arrow pagination_arrow">%s</li>' . "\n", get_next_posts_link( __( '<i class="fas fa-angle-right"></i>', 'truelysell' ) ) );
  
    echo '</ul></div>' . "\n";
  
}

function truelysell_blog_pagination_search() {
  
    if( is_singular() )
        return;
  
		if (isset($_GET['s']) && $_GET['s'] != "")
{
	$list_s = $_GET['s'];
}
$args_search = array('post_type' => 'post','s'=>$list_s);
$wp_query_search = new WP_Query($args_search);

   
    /** Stop execution if there's only 1 page */
    if( $wp_query_search->max_num_pages <= 1 )
        return;
  
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query_search->max_num_pages );
  
    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;
  
    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
  
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
  
    echo '<div class="blog-pagination"><nav><ul class="pagination justify-content-center">' . "\n";
  
    /** Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li class="page-item pagination_arrow">%s</li>' . "\n", get_previous_posts_link( __( '<i class="fas fa-angle-left"></i>', 'truelysell' ) ) );
  
    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="page-item active"' : '';
		$class_active = 1 == $paged ? ' active' : '';
  
        printf( '<li%s class="page-item"><a href="%s" class="page-link '.$class_active.'" >%s</a></li>' . "\n", $class_active, esc_url( get_pagenum_link( 1 ) ), '1' );
  
        if ( ! in_array( 2, $links ) )
            echo '<li class="page-item dots-page-item"><span href="#" class="page-link dots" >…</span></li>';
    }
  
    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="page-item active "' : '';
		$class_active = $paged == $link ? '  active' : '';
        printf( '<li%s class="page-item"><a href="%s" class="page-link '.$class_active.'">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }
  
    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li class="page-item dots-page-item"><span href="#" class="page-link dots" >…</span></li>' . "\n";
  
        $class = $paged == $max ? ' class="page-item active"' : '';
        printf( '<li%s class="page-item"><a class="page-link" href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }
	
    /** Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li class="page-item pagination_arrow">%s</li>' . "\n", get_next_posts_link( __( '<i class="fas fa-angle-right"></i>', 'truelysell' ) ) );
  
    echo '</ul></nav></div>' . "\n";
  
}

function truelysell_blog_pagination() {
  
    if( is_singular() )
        return;

    global $wp_query;
    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;
  
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );
  
    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;
  
    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
  
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
  
    echo '<div class="blog-pagination"><nav><ul class="pagination justify-content-center">' . "\n";
  
    /** Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li class="page-item pagination_arrow">%s</li>' . "\n", get_previous_posts_link( __( '<i class="fas fa-angle-left"></i>', 'truelysell' ) ) );
  
    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="page-item active"' : '';
		$class_active = 1 == $paged ? ' active' : '';
  
        printf( '<li%s class="page-item"><a href="%s" class="page-link '.$class_active.'" >%s</a></li>' . "\n", $class_active, esc_url( get_pagenum_link( 1 ) ), '1' );
  
        if ( ! in_array( 2, $links ) )
            echo '<li class="page-item dots-page-item"><span href="#" class="page-link dots" >…</span></li>';
    }
  
    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="page-item active "' : '';
		$class_active = $paged == $link ? '  active' : '';
        printf( '<li%s class="page-item"><a href="%s" class="page-link '.$class_active.'">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }
  
    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li class="page-item dots-page-item"><span href="#" class="page-link dots" >…</span></li>' . "\n";
  
        $class = $paged == $max ? ' class="page-item active"' : '';
        printf( '<li%s class="page-item"><a class="page-link" href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }
	
    /** Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li class="page-item pagination_arrow">%s</li>' . "\n", get_next_posts_link( __( '<i class="fas fa-angle-right"></i>', 'truelysell' ) ) );
  
    echo '</ul></nav></div>' . "\n";
  
}

add_filter( 'woocommerce_should_load_paypal_standard', '__return_true' );
add_theme_support( 'woocommerce' );

function truelysell_cat_count_span( $links ) {
	$links = str_replace( '</a> (', '</a><span class="post-count">(', $links );
	$links = str_replace( ')', ')</span>', $links );
	return $links;
}
add_filter( 'wp_list_categories', 'truelysell_cat_count_span' );

/**
 * Filter the archives widget to add a span around post count
 */
function truelysell_archive_count_span( $links ) {
	$links = str_replace( '</a>&nbsp;(', '</a><span class="post-count">(', $links );
	$links = str_replace( ')', ')</span>', $links );
	return $links;
}
add_filter( 'get_archives_link', 'truelysell_archive_count_span' );

function truelysell_searchfilter($query) {
    if ($query->is_search && is_page_template('search.php')) {
        $query->set('post_type', 'post');
    }
    return $query;
}
add_filter('pre_get_posts','truelysell_searchfilter');
 
add_filter('wpcf7_autop_or_not', '__return_false');
 





function truelysell_hex2RGB($hex) 
{
        preg_match("/^#{0,1}([0-9a-f]{1,6})$/i",$hex,$match);
        if(!isset($match[1]))
        {
            return false;
        }

        if(strlen($match[1]) == 6)
        {
            list($r, $g, $b) = array($hex[0].$hex[1],$hex[2].$hex[3],$hex[4].$hex[5]);
        }
        elseif(strlen($match[1]) == 3)
        {
            list($r, $g, $b) = array($hex[0].$hex[0],$hex[1].$hex[1],$hex[2].$hex[2]);
        }
        else if(strlen($match[1]) == 2)
        {
            list($r, $g, $b) = array($hex[0].$hex[1],$hex[0].$hex[1],$hex[0].$hex[1]);
        }
        else if(strlen($match[1]) == 1)
        {
            list($r, $g, $b) = array($hex.$hex,$hex.$hex,$hex.$hex);
        }
        else
        {
            return false;
        }

        $color = array();
        $color['r'] = hexdec($r);
        $color['g'] = hexdec($g);
        $color['b'] = hexdec($b);

        return $color;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function truelysell_theme_options_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'truelysell_theme_options_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function truelysell_js_customize() {
    wp_enqueue_script( 'truelysell_customizer', get_template_directory_uri() . 'assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'truelysell_js_customize' );



 
function theme_customize_register( $wp_customize ) {
 
 	
	$wp_customize->add_section( 'mytheme_new_section_name' , array(
		'title'      => __( 'Visible Section Name', 'truelysell' ),
		'priority'   => 30,
	   
	   ) );

      // Text color
    $wp_customize->add_setting( 'primary_color', array(
      'default'   => '#4c40ed',
      'sanitize_callback' => 'sanitize_hex_color',
       'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Primary color', 'truelysell' ),
      'show_opacity' => true,
    
    ) ) );

      // Text color
      $wp_customize->add_setting( 'primary_hover_color', array(
        'default'   => '#2229C1',
        'sanitize_callback' => 'sanitize_hex_color',
         'transport' => 'refresh',
      ) );
  
      $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_hover_color', array(
        'section' => 'colors',
        'label'   => esc_html__( 'Primary Hover color', 'truelysell' ),
        'show_opacity' => true,
      
      ) ) );

    // Text color
    $wp_customize->add_setting( 'secondary_color', array(
      'default'   => '#F7F7FF',
	    'sanitize_callback' => 'sanitize_hex_color',
      'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Secondary color', 'truelysell' ),
    ) ) );

    // Text color
    $wp_customize->add_setting( 'breadcrumb_color', array(
      'default'   => '#F7F7FF',
	    'sanitize_callback' => 'sanitize_hex_color',
      'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'breadcrumb_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Breadcrumb color', 'truelysell' ),
    ) ) );
  
    // Text color
    $wp_customize->add_setting( 'text_color', array(
      'default'   => '#74788D',
	     'sanitize_callback' => 'sanitize_hex_color',
      'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Text color', 'truelysell' ),
    ) ) );

    // Link color
    $wp_customize->add_setting( 'link_color', array(
      'default'   => '#4c40ed',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Link color', 'truelysell' ),
    ) ) );

    

    // Border color
    $wp_customize->add_setting( 'heading_color', array(
      'default'   => '#28283C',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'heading_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Heading color', 'truelysell' ),
    ) ) );

    // Sidebar background
    $wp_customize->add_setting( 'sidebar_background', array(
      'default'   => '#F7F7FF',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidebar_background', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Sidebar Background', 'truelysell' ),
    ) ) );
	
  }
  add_action( 'customize_register', 'theme_customize_register' );
 
  function theme_get_customizer_css() {
    ob_start();

    
    $primary_color = get_theme_mod( 'primary_color', '#4C40ED' );
    if ( ! empty( $primary_color ) ) {
      ?>
      :root {
        --ts_primary_color: <?php echo  $primary_color; ?>;
        --ts_primary_trans_color: <?php echo  $primary_color; ?>99;
      }

      <?php
    }
    $primary_hover_color = get_theme_mod( 'primary_hover_color', '#2229C1' );
    if ( ! empty( $primary_hover_color ) ) {
      ?>
      :root {
        --ts_primary_hover_color: <?php echo  $primary_hover_color; ?>;
       }
      <?php
    }

    

    $secondary_color = get_theme_mod( 'secondary_color', '#F7F7FF' );
    if ( ! empty( $secondary_color ) ) {
      ?>
      :root {
        --ts_secondary_color: <?php echo $secondary_color; ?>;
      }

      <?php
    }

    $breadcrumb_color = get_theme_mod( 'breadcrumb_color', '#F7F7FF' );
    if ( ! empty( $breadcrumb_color ) ) {
      ?>
      :root {
        --ts_breadcrumb_color: <?php echo $breadcrumb_color; ?>;
      }

      <?php
    }



    $text_color = get_theme_mod( 'text_color', '#74788D' );
    if ( ! empty( $text_color ) ) {
      ?>
      :root {
        --ts_text_color: <?php echo $text_color; ?>;
      }

      <?php
    }


    $link_color = get_theme_mod( 'link_color', '#4C40ED' );
    if ( ! empty( $link_color ) ) {
      ?>
      :root {
        --ts_link_color: <?php echo $link_color; ?>;
      }
      <?php
    }

    
    $heading_color = get_theme_mod( 'heading_color', '#28283c' );
    if ( ! empty( $heading_color ) ) {
      ?>
      :root {
        --ts_heading_color: <?php echo $heading_color; ?>;
      }
      <?php
    }

 
     
    $sidebar_background = get_theme_mod( 'sidebar_background', '#F7F7FF' );
    if ( ! empty( $sidebar_background ) ) {
      ?>
      :root {
        --ts_sidebar_bgcolor: <?php echo $sidebar_background; ?>;
      }
      <?php
    }

    $css = ob_get_clean();
    return $css;
  }

// Modify our styles registration like so:

function theme_enqueue_styles() {
  wp_enqueue_style( 'theme-styles', get_stylesheet_uri() ); // This is where you enqueue your theme's main stylesheet
  $custom_css = theme_get_customizer_css();
  wp_add_inline_style( 'theme-styles', $custom_css );
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );























add_action( 'category_add_form_fields', 'category_add_form_fields_callback' );


function category_add_form_fields_callback() {
  $image_id = null;
  ?>

  <div id="category_custom_image"></div>
  <input 
        type="hidden" 
        id="category_custom_image_url"     
        name="category_custom_image_url">
  <div style="margin-bottom: 20px;">
      <span>Category Image </span>
      <a href="#" 
          class="button custom-button-upload" 
          id="custom-button-upload">Upload image</a>
      <a href="#" 
          class="button custom-button-remove" 
          id="custom-button-remove" 
          style="display: none">Remove image</a>
  </div>

<?php 

}

/*** user can register auto  ***/
add_action('init', 'truelysell_update_can_register');
function truelysell_update_can_register() {
  update_option('users_can_register', true);
}
/*** user can register auto  ***/

/*** OCDI  ***/

require_once get_template_directory(). '/inc/truelysell-demo-content.php';


function remove_ocdi_about_notice() {
    echo '<style type="text/css">
    .ocdi__theme-about {display: none}
          </style>';
 }
 add_action('admin_head', 'remove_ocdi_about_notice');

 /*** OCDI  ***/

 if( !function_exists('truelysell_fl_framework_getoptions') )
{
    function truelysell_fl_framework_getoptions($get_text)
    {
        global $truelysell_theme_options;
        if(isset($truelysell_theme_options[$get_text]) &&  $truelysell_theme_options[$get_text] !=""):
            return $truelysell_theme_options[$get_text];
        else:
            return false;
        endif;
    }
}