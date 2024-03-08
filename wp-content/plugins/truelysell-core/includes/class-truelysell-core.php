<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Truelysell_Core {

	/**
	 * The single instance of Truelysell_Core.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.5.27' ) {
		$this->_version = $version;
		
		$this->_token = 'truelysell_core';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		register_activation_hook( $this->file, array( $this, 'install' ) );


		define( 'TRUELYSELL_CORE_ASSETS_DIR', trailingslashit( $this->dir ) . 'assets' );
		define( 'TRUELYSELL_CORE_ASSETS_URL', esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) ) );
		

		
		include( 'truelysell-core-post-types.php' );
		include( 'truelysell-core-meta-boxes.php' );
		include( 'truelysell-core-listing.php' );
		include( 'truelysell-core-reviews.php' );
		include( 'truelysell-core-submit.php' );
		include( 'truelysell-core-shortcodes.php' );
		include( 'truelysell-core-search.php' );
		
		include( 'truelysell-core-users.php' );
		include( 'truelysell-core-bookmarks.php' );
		include( 'truelysell-core-coupons.php' );
		include( 'truelysell-core-activities-log.php' );
		include( 'truelysell-core-notification-log.php' );
		include( 'truelysell-core-calendar.php' );
		include( 'truelysell-core-emails.php' );
		include( 'truelysell-core-messages.php' );
		include( 'truelysell-core-bookings-calendar.php' );
		 
		include( 'truelysell-core-commissions.php' );
		include( 'truelysell-core-payouts.php' );
		include( 'truelysell-core-bookings-admin.php' );
		include( 'truelysell-calendar/truelysell-core-calendar.php' );
		include( 'truelysell-core-tcal.php' );
		
		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		add_action( 'wp_ajax_handle_dropped_media', array( $this, 'truelysell_core_handle_dropped_media' ));
		add_action( 'wp_ajax_nopriv_handle_dropped_media', array( $this, 'truelysell_core_handle_dropped_media' ));
		add_action( 'wp_ajax_nopriv_handle_delete_media',  array( $this, 'truelysell_core_handle_delete_media' ));
		add_action( 'wp_ajax_handle_delete_media',  array( $this, 'truelysell_core_handle_delete_media' ));

		add_filter('cron_schedules',array( $this, 'truelysell_cron_schedules'));
		
		$this->post_types 	= Truelysell_Core_Post_Types::instance();
		$this->meta_boxes 	= new Truelysell_Core_Meta_Boxes();
		$this->listing 		= new Truelysell_Core_Listing();
		$this->reviews 		= new Truelysell_Core_Reviews();
		$this->submit 		= Truelysell_Core_Submit::instance();
		$this->search 		= new Truelysell_Core_Search();
		$this->users 		= new Truelysell_Core_Users();
		$this->bookmarks 	= new Truelysell_Core_Bookmarks();
		$this->activites_log = new Truelysell_Core_Activities_Log();
		$this->notification_log = new Truelysell_Core_Notification_Log();
		$this->messages 	= new Truelysell_Core_Messages();
		$this->calendar 	= Truelysell_Core_Calendar::instance();
		$this->emails 		= Truelysell_Core_Emails::instance();
		$this->commissions 	= Truelysell_Core_Commissions::instance();
		$this->payouts 		= Truelysell_Core_Payouts::instance();
		$this->ical 		= Truelysell_Core_iCal::instance();
		$this->coupons 		= new Truelysell_Core_Coupons();
		
		
		
		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
		add_action( 'init', array( $this, 'image_size' ) );
		add_action( 'init', array( $this, 'register_sidebar' ) );
		
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		
		add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );

		add_filter( 'template_include', array( $this, 'listing_templates' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ), 13 );
		add_action( 'plugins_loaded', array( $this, 'truelysell_core_update_db_1_3_2' ), 13 );
		add_action( 'plugins_loaded', array( $this, 'truelysell_core_update_db_1_5_18' ), 13 );

		add_action( 'admin_notices', array( $this, 'google_api_notice' ));

		add_action('wp_head',  array( $this, 'truelysell_og_image' ));

		// Schedule cron jobs
		self::maybe_schedule_cron_jobs();
		

	} // End __construct ()
	  
	/**
	 * Widgets init
	 */
	public function widgets_init() {
		include( 'truelysell-core-widgets.php' );
	}


	public function include_template_functions() {
		include( REALTEO_PLUGIN_DIR.'/truelysell-core-template-functions.php' );
		include( REALTEO_PLUGIN_DIR.'/includes/paid-services/truelysell-core-paid-services-functions.php' );
	}

	/* handles single listing and archive listing view */
	public static function listing_templates( $template ) {
		$post_type = get_post_type();  
		$custom_post_types = array( 'listing' );
		
		$template_loader = new Truelysell_Core_Template_Loader;
		if ( in_array( $post_type, $custom_post_types ) ) {
			
			if ( is_archive() && !is_author() ) {

				$template = $template_loader->locate_template('archive-' . $post_type . '.php');

				return $template;
			}

			if ( is_single() ) {
				$template = $template_loader->locate_template('single-' . $post_type . '.php');
				return $template;
			}
		}

		if( is_tax( 'listing_category' ) ){
			$template = $template_loader->locate_template('archive-listing.php');
		}

		if( is_post_type_archive( 'listing' ) ){

			$template = $template_loader->locate_template('archive-listing.php');

		}

		return $template;
	}

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
		

	} // End enqueue_styles ()



	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
		
		wp_register_script(	'uploads', esc_url( $this->assets_url ) . 'js/uploads.min.js', array( 'jquery' ), $this->_version, true );
		wp_register_script(	'ajaxsearch', esc_url( $this->assets_url ) . 'js/ajax.search.min.js', array( 'jquery' ), $this->_version, true );

		wp_register_script( $this->_token . '-recaptchav3', esc_url( $this->assets_url ) . 'js/recaptchav3.js', array( 'jquery' ), $this->_version );
		
		wp_register_script( $this->_token . '-google-autocomplete', esc_url( $this->assets_url ) . 'js/truelysell.google.autocomplete.js', array( 'jquery' ), $this->_version );

		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend.js', array( 'jquery' ), $this->_version );
		wp_register_script( $this->_token . '-bookings', esc_url( $this->assets_url ) . 'js/bookings.js', array( 'jquery' ), $this->_version );
		
		wp_register_script( $this->_token . '-pwstrength-bootstrap-min', esc_url( $this->assets_url ) . 'js/pwstrength-bootstrap.min.js', array( 'jquery' ), $this->_version );

		wp_register_script(	'markerclusterer', esc_url( $this->assets_url )  . '/js/markerclusterer.js', array( 'jquery' ), $this->_version );
		wp_register_script( 'infobox-min', esc_url( $this->assets_url )  . '/js/infobox.min.js', array( 'jquery' ), $this->_version  );
		wp_register_script( 'jquery-geocomplete-min',esc_url( $this->assets_url )  . '/js/jquery.geocomplete.min.js', array( 'jquery','maps' ), $this->_version  );
		wp_register_script( 'maps', esc_url( $this->assets_url )  . '/js/maps.js', array( 'jquery','truelysell-custom','markerclusterer' ), $this->_version  );



		$map_provider = get_option( 'truelysell_map_provider');
		$maps_api_key = get_option( 'truelysell_maps_api' );


		if($map_provider != "none"):
			wp_enqueue_script( 'leaflet.js', esc_url( $this->assets_url ) . 'js/leaflet.js');

			if( $map_provider == 'bing'){
				wp_enqueue_script('polyfill','https://cdn.polyfill.io/v2/polyfill.min.js?features=Promise');
				wp_enqueue_script($this->_token . '-leaflet-bing-layer');
				
			}
			
			if( $map_provider == 'here' ){
				wp_enqueue_script($this->_token . '-leaflet-tilelayer-here');
			}
			
		if( $map_provider == 'google' ){

				if (is_page_template('template-listings-map.php')) { 
					wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js' );
					wp_enqueue_script( 'map-js', get_template_directory_uri().'/assets/js/map.js');
				}
			}

			wp_enqueue_script( $this->_token . '-leaflet-google-maps');
			wp_enqueue_script( $this->_token . '-leaflet-geocoder' );
			wp_enqueue_script( $this->_token . '-leaflet-markercluster' );
			wp_enqueue_script( $this->_token . '-leaflet-gesture-handling' );
			wp_enqueue_script( $this->_token . '-leaflet' );

			if( get_option('truelysell_map_address_provider') == 'google') {
				wp_enqueue_script( 'google-maps', 'https://maps.google.com/maps/api/js?key='.$maps_api_key.'&libraries=places' );
				wp_enqueue_script( $this->_token . '-google-autocomplete' );	
			};

		else:
			wp_localize_script(  $this->_token . '-frontend' , 'truelysellmap',
				    array(
				    	'address_provider'	=> 'off',
				        )
				    );
		endif;




		$recaptcha_status = get_option('truelysell_recaptcha');
		$recaptcha_version = get_option('truelysell_recaptcha_version');
		$recaptcha_sitekey3 = get_option('truelysell_recaptcha_sitekey3');
		if(is_user_logged_in()){
			$recaptcha_status = false;

		}
		if(!empty($recaptcha_status) && $recaptcha_version == 'v3' && !empty($recaptcha_sitekey3)){
			wp_enqueue_script( 'google-recaptcha-truelysell', 'https://www.google.com/recaptcha/api.js?render='.trim($recaptcha_sitekey3));	
			wp_enqueue_script( $this->_token . '-recaptchav3' );
		}
		if(!empty($recaptcha_status) && $recaptcha_version == 'v2'){
			wp_enqueue_script( 'google-recaptcha-truelysell', 'https://www.google.com/recaptcha/api.js' );
		}
		if(!is_user_logged_in()){
		 	wp_enqueue_script(  $this->_token . '-pwstrength-bootstrap-min' );
		}

		$_price_min =  $this->get_min_all_listing_price('');
		$_price_max =  $this->get_max_all_listing_price('');


		$ajax_url = admin_url( 'admin-ajax.php', 'relative' );
		$currency = truelysell_fl_framework_getoptions('currency' );
		$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency); 
		
		
		$localize_array = array(
				'ajax_url'                	=> $ajax_url,
				'payout_not_valid_email_msg'  => esc_html__('The email address is not valid. Please add a valid email address.', 'truelysell_core'),
				'is_rtl'                  	=> is_rtl() ? 1 : 0,
				'lang'                    	=> defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : '', // WPML workaround until this is standardized
				'_price_min'		    	=> $_price_min,
				'_price_max'		    	=> $_price_max,
				'currency'		      		=> truelysell_fl_framework_getoptions('currency' ),
				'currency_position'		    => truelysell_fl_framework_getoptions('currency_postion' ),
				'currency_symbol'		    => esc_attr($currency_symbol),
				'submitCenterPoint'		    => get_option( 'truelysell_submit_center_point','52.2296756,21.012228700000037' ),
				'centerPoint'		      	=> get_option( 'truelysell_map_center_point','52.2296756,21.012228700000037' ),
				'country'		      		=> get_option( 'truelysell_maps_limit_country' ),
				'upload'					=> admin_url( 'admin-ajax.php?action=handle_dropped_media' ),
  				'delete'					=> admin_url( 'admin-ajax.php?action=handle_delete_media' ),
  				'color'						=> get_option('pp_main_color','#274abb' ), 
  				'dictDefaultMessage'		=> esc_html__("Drop files here to upload","truelysell_core"),
				'dictFallbackMessage' 		=> esc_html__("Your browser does not support drag'n'drop file uploads.","truelysell_core"),
				'dictFallbackText' 			=> esc_html__("Please use the fallback form below to upload your files like in the olden days.","truelysell_core"),
				'dictFileTooBig' 			=> esc_html__("File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.","truelysell_core"),
				'dictInvalidFileType' 		=> esc_html__("You can't upload files of this type.","truelysell_core"),
				'dictResponseError'		 	=> esc_html__("Server responded with {{statusCode}} code.","truelysell_core"),
				'dictCancelUpload' 			=> esc_html__("Cancel upload","truelysell_core"),
				'dictCancelUploadConfirmation' => esc_html__("Are you sure you want to cancel this upload?","truelysell_core"),
				'dictRemoveFile' 			=> esc_html__("Remove file","truelysell_core"),
				'dictMaxFilesExceeded' 		=> esc_html__("You can not upload any more files.","truelysell_core"),
				'areyousure' 				=> esc_html__("Do you want to remove this service?","truelysell_core"),
				'maxFiles' 					=> truelysell_fl_framework_getoptions('max_files'),
				'maxFilesize' 				=> truelysell_fl_framework_getoptions('max_filesize'),
				'clockformat' 				=> (truelysell_fl_framework_getoptions('clock_format') == '24') ? true : false,
				'prompt_price'				=> esc_html__('Set price for this date','truelysell_core'),
				'menu_price'				=> esc_html__('Price (optional)','truelysell_core'),
				'menu_desc'					=> esc_html__('Description','truelysell_core'),
				'menu_title'				=> esc_html__('Title','truelysell_core'),
				"applyLabel"				=> esc_html__( "Apply",'truelysell_core'),
		        "cancelLabel" 				=> esc_html__( "Cancel",'truelysell_core'),
		        "clearLabel" 				=> esc_html__( "Clear",'truelysell_core'),
		        "fromLabel"					=> esc_html__( "From",'truelysell_core'),
		        "toLabel" 					=> esc_html__( "To",'truelysell_core'),
		        "customRangeLabel" 			=> esc_html__( "Custom",'truelysell_core'),
		        "mmenuTitle" 				=> esc_html__( "Menu",'truelysell_core'),
		        "pricingTooltip" 			=> esc_html__( "Click to make this item bookable in booking widget",'truelysell_core'),
		        "today" 					=> esc_html__( "Today",'truelysell_core'),
		        "yesterday" 				=> esc_html__( "Yesterday",'truelysell_core'),
		        "last_7_days" 				=> esc_html__( "Last 7 Days",'truelysell_core'),
		        "last_30_days" 				=> esc_html__( "Last 30 Days",'truelysell_core'),
		        "this_month" 				=> esc_html__( "This Month",'truelysell_core'),
		        "last_month" 				=> esc_html__( "Last Month",'truelysell_core'),
		        "map_provider" 				=> get_option('truelysell_map_provider','osm'),
		        "address_provider" 			=> get_option('truelysell_map_address_provider','osm'),
		        "mapbox_access_token" 		=> get_option('truelysell_mapbox_access_token'),
		        "mapbox_retina" 			=> get_option('truelysell_mapbox_retina'),
		        "mapbox_style_url" 			=> get_option('truelysell_mapbox_style_url') ? get_option('truelysell_mapbox_style_url') : 'https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}@2x?access_token=',
		        "bing_maps_key" 			=> get_option('truelysell_bing_maps_key'),
		        "thunderforest_api_key" 	=> get_option('truelysell_thunderforest_api_key'),
		        "here_app_id" 				=> get_option('truelysell_here_app_id'),
		        "here_app_code" 			=> get_option('truelysell_here_app_code'),
		        "maps_reviews_text" 		=> esc_html__('reviews','truelysell_core'),
		        "maps_noreviews_text" 		=> esc_html__('Not rated yet','truelysell_core'),
		        "category_title" 			=> esc_html__('Category Title','truelysell_core'),
  				"day_short_su" => esc_html_x("Su", 'Short for Sunday', 'truelysell_core'),
	            "day_short_mo" => esc_html_x("Mo", 'Short for Monday','truelysell_core'),
	            "day_short_tu" => esc_html_x("Tu", 'Short for Tuesday','truelysell_core'),
	            "day_short_we" => esc_html_x("We", 'Short for Wednesday','truelysell_core'),
	            "day_short_th" => esc_html_x("Th", 'Short for Thursday','truelysell_core'),
	            "day_short_fr" => esc_html_x("Fr", 'Short for Friday','truelysell_core'),
	            "day_short_sa" => esc_html_x("Sa", 'Short for Saturday','truelysell_core'),
	            "radius_state" => get_option('truelysell_radius_state'),
	            "maps_autofit" => get_option('truelysell_map_autofit','on'),
	            "maps_autolocate" 	=> get_option('truelysell_map_autolocate'),
	            "maps_zoom" 		=> (!empty(get_option('truelysell_map_zoom_global'))) ? get_option('truelysell_map_zoom_global') : 9,
	            "maps_single_zoom" 	=> (!empty(get_option('truelysell_map_zoom_single'))) ? get_option('truelysell_map_zoom_single') : 9,
	            "autologin" 	=> truelysell_fl_framework_getoptions('autologin'),
	            "no_results_text" 	=> esc_html__('No results match','truelysell_core'),
	            "no_results_found_text" 	=> esc_html__('No results found','truelysell_core'),
	            "placeholder_text_single" 	=> esc_html__('Select an Option','truelysell_core'),
	            "placeholder_text_multiple" => esc_html__('Select Some Options ','truelysell_core'),
	            "january" => esc_html__("January",'truelysell_core'),
		        "february" => esc_html__("February",'truelysell_core'),
		        "march" => esc_html__("March",'truelysell_core'),
				"april" => esc_html__("April",'truelysell_core'),
		        "may" => esc_html__("May",'truelysell_core'),
		        "june" => esc_html__("June",'truelysell_core'),
		        "july" => esc_html__("July",'truelysell_core'),
		        "august" => esc_html__("August",'truelysell_core'),
		        "september" => esc_html__("September",'truelysell_core'),
		        "october" => esc_html__("October",'truelysell_core'),
		        "november" => esc_html__("November",'truelysell_core'),
		        "december" => esc_html__("December",'truelysell_core'),
		        "opening_time" => esc_html__("Opening Time",'truelysell_core'),
		        "closing_time" => esc_html__("Closing Time",'truelysell_core'),
		        "remove" => esc_html__("Remove",'truelysell_core'),
		        "onetimefee" => esc_html__("One time fee",'truelysell_core'),
		        "multiguest" => esc_html__("Multiply by guests",'truelysell_core'),
		        "multidays" => esc_html__("Multiply by days",'truelysell_core'),
		        "multiguestdays" => esc_html__("Multiply by guest & days",'truelysell_core'),
		        "quantitybuttons" => esc_html__("Quantity Buttons",'truelysell_core'),
		        "booked_dates" => esc_html__("Those dates are already booked",'truelysell_core'),
		        "replied" => esc_html__("Replied",'truelysell_core'),
		        "recaptcha_status" 			=> $recaptcha_status,
	            "recaptcha_version" 		=> $recaptcha_version,
	            "recaptcha_sitekey3" 		=> trim($recaptcha_sitekey3)
			);
		$criteria_fields = truelysell_get_reviews_criteria();
		
		$loc_critera = array();
		foreach ($criteria_fields as $key => $value) {
			$loc_critera[] = $key;
		};
		if(!empty($loc_critera)){
			$localize_array['review_criteria'] = implode(',',$loc_critera);	
		}
		
		wp_localize_script(  $this->_token . '-frontend', 'truelysell_core', $localize_array);

		wp_enqueue_script( 'jquery-ui-core' );
		
		wp_enqueue_script( 'jquery-ui-autocomplete' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'uploads' );
		if(truelysell_fl_framework_getoptions('ajax_browsing') == 'on'){
			wp_enqueue_script( 'ajaxsearch' );	
		}
		
		
		wp_enqueue_script( $this->_token . '-frontend' );
		wp_enqueue_script( $this->_token . '-bookings' );
	
		
	} // End enqueue_scripts ()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts ( $hook = '' ) {
		
		wp_register_script( $this->_token . '-settings', esc_url( $this->assets_url ) . 'js/settings' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-settings' );
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
		

		$map_provider = get_option( 'truelysell_map_provider');
		$maps_api_key = get_option( 'truelysell_maps_api' );
		if( get_option('truelysell_map_address_provider') == 'google') {
			if($maps_api_key) {
				wp_enqueue_script( 'google-maps', 'https://maps.google.com/maps/api/js?key='.$maps_api_key.'&libraries=places' );	
				wp_register_script( $this->_token . '-admin-maps', esc_url( $this->assets_url ) . 'js/admin.maps' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
				wp_enqueue_script( $this->_token . '-admin-maps' );
			
			}
		} else {
			wp_enqueue_script( 'leaflet.js', esc_url( $this->assets_url ) . 'js/leaflet.js');
			wp_enqueue_script( 'leaflet-geocoder',esc_url( $this->assets_url ) . 'js/control.geocoder.js');
			wp_register_script( $this->_token . '-admin-leaflet', esc_url( $this->assets_url ) . 'js/admin.leaflet' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
			wp_enqueue_script( $this->_token . '-admin-leaflet' );
			
		}
		wp_enqueue_script('jquery-ui-datepicker');
		if(function_exists('truelysell_date_time_wp_format')) {
			$convertedData = truelysell_date_time_wp_format();
	        // add converented format date to javascript
	        wp_localize_script(  $this->_token . '-admin', 'wordpress_date_format', $convertedData );
        }

         wp_localize_script(  $this->_token . '-admin', 'truelysell_admin', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'pp_cancel_payout_confirmation_msg' => esc_html__('Are you sure to cancel the automatic commission that was sent previously by using PayPal Payout?', 'truelysell')
        ] );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'truelysell_core', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );

	} // End load_localisation ()

	//subscription
	public function init_plugin() {



		if ( class_exists( 'WC_Product_Subscription' ) ) {
		include( 'paid-services/truelysell-core-paid-subscriptions.php' );			
			include_once( 'paid-services/truelysell-core-paid-subscriptions-product.php' );
			include_once( 'paid-services/class-wc-product-listing-package-subscription.php' );
			

		}

	}

	/**
	 * Adds image sizes
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function image_size () {
		add_image_size('truelysell-gallery', 1200, 0, true);
		add_image_size('truelysell-listing-grid', 415, 280, true);
		add_image_size('truelysell-listing-grid-small', 300, 200, true);
		add_image_size('truelysell_core-avatar', 590, 590, true);
		add_image_size('truelysell_core-preview', 200, 200, true);

	} // End load_localisation ()

	public function register_sidebar () {

		register_sidebar( array(
			'name'          => esc_html__( 'Single listing sidebar', 'truelysell_core' ),
			'id'            => 'sidebar-listing',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell_core' ),
			'before_widget' => '<div id="%1$s" class="listing-widget card widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Listings sidebar', 'truelysell_core' ),
			'id'            => 'sidebar-listings',
			'description'   => esc_html__( 'Add widgets here.', 'truelysell_core' ),
			'before_widget' => '<div id="%1$s" class="listing-widget  %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="">',
			'after_title'   => '</h2>',
		) );		



	} // End load_localisation ()


	function get_min_listing_price($type) {
		global $wpdb;
		$result = $wpdb->get_var(
	    $wpdb->prepare("
	            SELECT min(m2.meta_value + 0)
	            FROM $wpdb->posts AS p
	            INNER JOIN $wpdb->postmeta AS m1 ON ( p.ID = m1.post_id )
				INNER JOIN $wpdb->postmeta AS m2  ON ( p.ID = m2.post_id )
				WHERE
				p.post_type = 'listing'
				AND p.post_status = 'publish'
				AND ( m1.meta_key = '_offer_type' AND m1.meta_value = %s )
				AND ( m2.meta_key = '_price'  ) AND m2.meta_value != ''
	        ", $type )
	    ) ;

	    return $result;
	}	

	function get_max_listing_price($type) {
		global $wpdb;
		$result = $wpdb->get_var(
	    $wpdb->prepare("
	            SELECT max(m2.meta_value + 0)
	            FROM $wpdb->posts AS p
	            INNER JOIN $wpdb->postmeta AS m1 ON ( p.ID = m1.post_id )
				INNER JOIN $wpdb->postmeta AS m2  ON ( p.ID = m2.post_id )
				WHERE
				p.post_type = 'listing'
				AND p.post_status = 'publish'
				AND ( m1.meta_key = '_offer_type' AND m1.meta_value = %s )
				AND ( m2.meta_key = '_price'  ) AND m2.meta_value != ''
	        ", $type )
	    ) ;
	   

	    return $result;
	}	

	function get_min_all_listing_price() {
		global $wpdb;
		$result = $wpdb->get_var(
	    "	SELECT min(m2.meta_value + 0)
	            FROM $wpdb->posts AS p
	            INNER JOIN $wpdb->postmeta AS m1 ON ( p.ID = m1.post_id )
				INNER JOIN $wpdb->postmeta AS m2  ON ( p.ID = m2.post_id )
				WHERE
				p.post_type = 'listing'
				AND p.post_status = 'publish'
				AND ( m2.meta_key = '_price'  ) AND m2.meta_value != ''
	        "
	    ) ;

	    return $result;
	}	

	function get_max_all_listing_price() {
		global $wpdb;
		$result = $wpdb->get_var(
	   "
	            SELECT max(m2.meta_value + 0)
	            FROM $wpdb->posts AS p
	            INNER JOIN $wpdb->postmeta AS m1 ON ( p.ID = m1.post_id )
				INNER JOIN $wpdb->postmeta AS m2  ON ( p.ID = m2.post_id )
				WHERE
				p.post_type = 'listing'
				AND p.post_status = 'publish'
				AND ( m2.meta_key = '_price'  ) AND m2.meta_value != ''
	        "
	    ) ;
	   

	    return $result;
	}




	function truelysell_core_handle_delete_media(){

	    if( isset($_REQUEST['media_id']) ){
	        $post_id = absint( $_REQUEST['media_id'] );
	        $status = wp_delete_attachment($post_id, true);
	        if( $status )
	            echo json_encode(array('status' => 'OK'));
	        else
	            echo json_encode(array('status' => 'FAILED'));
	    }
	    wp_die();
	}


	function truelysell_core_handle_dropped_media() {
	    status_header(200);

	    $upload_dir = wp_upload_dir();
	    $upload_path = $upload_dir['path'] . DIRECTORY_SEPARATOR;

	    $newupload = 0;

	    if ( !empty($_FILES) ) {
	        $files = $_FILES;
	        foreach($files as $file) {
	            $newfile = array (
	                    'name' => $file['name'],
	                    'type' => $file['type'],
	                    'tmp_name' => $file['tmp_name'],
	                    'error' => $file['error'],
	                    'size' => $file['size']
	            );

	            $_FILES = array('upload'=>$newfile);
	            foreach($_FILES as $file => $array) {
	                $newupload = media_handle_upload( $file, 0 );
	            }
	        }
	    }

	    echo $newupload;    
	    wp_die();
	}

		
		function google_api_notice() {
		
		$map_provider = get_option( 'truelysell_map_provider');
		$maps_api_key = get_option( 'truelysell_maps_api' );
		if($map_provider == 'google') {

			if(empty($maps_api_key)) {
			    ?>
			   
			    <?php
			}
		}
	}

	function truelysell_og_image(){
	    if( is_singular('listing') ) {
	    	
	    	global $post;
	    	
	    	$gallery = (array) get_post_meta( $post->ID, '_gallery', true );
			
			if(!empty($gallery)){
				$ids = array_keys($gallery);
				if(!empty($ids[0])){ 
					$image =  wp_get_attachment_image_url($ids[0],'truelysell-listing-grid'); 
				}	
			} else { 
				$image = get_truelysell_core_placeholder_image(); 
			}
			if(empty($image)){
				$image = get_the_post_thumbnail_url(get_the_ID(),'full') ;
			}
	       
	        echo '<meta property="og:image" content="'. $image .'" />';
	    }
	}
	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'truelysell_core';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Truelysell_Core Instance
	 *
	 * Ensures only one instance of Truelysell_Core is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Truelysell_Core()
	 * @return Main Truelysell_Core instance
	 */
	public static function instance ( $file = '', $version = '1.2.1' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?','truelysell_core' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?','truelysell_core' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
		$this->init_user_roles();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

	/**
	* Schedule cron jobs for Truelysell_Core events.
	*/
	public static function maybe_schedule_cron_jobs() {
		
		if ( ! wp_next_scheduled( 'truelysell_core_check_for_expired_listings' ) ) {
			wp_schedule_event( time(), 'hourly', 'truelysell_core_check_for_expired_listings' );
		}

		if ( ! wp_next_scheduled( 'truelysell_core_check_for_expired_bookings' ) ) {
			wp_schedule_event( time(), '5min', 'truelysell_core_check_for_expired_bookings' );
		}


		if ( ! wp_next_scheduled( 'truelysell_core_check_for_new_messages' ) ) {
			wp_schedule_event( time(), '30min', 'truelysell_core_check_for_new_messages' );
		}
	}

	function truelysell_cron_schedules($schedules){
	    if(!isset($schedules["5min"])){
	        $schedules["5min"] = array(
	            'interval' => 5*60,
	            'display' => __('Once every 5 minutes'));
	    }
	    if(!isset($schedules["30min"])){
	        $schedules["30min"] = array(
	            'interval' => 30*60,
	            'display' => __('Once every 30 minutes'));
	    }
	    if(!isset($schedules["every_week"])){
		    $schedules['every_week'] = array(
	            'interval'  => 604800, //604800 seconds in 1 week
	            'display'   => esc_html__( 'Every Week', 'truelysell_core' )
	    	);
	 	}
	    return $schedules;
	}

	function init_user_roles(){
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) && ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
 
		if ( is_object( $wp_roles ) ) {
				remove_role( 'owner' );
				add_role( 'owner', __( 'Owner', 'truelysell_core' ), array(
					'read'                 => true,
					'upload_files'         => true,
					'edit_listing'         => true,
					'read_listing'         => true,
					'delete_listing'       => true,
					'edit_listings'        => true,
					'delete_listings'      => true,
					'edit_listings'        => true,
					'assign_listing_terms' => true,
					'dokandar'                  => true,
				'edit_shop_orders'          => true,
				'edit_product'              => true,
				'read_product'              => true,
				'delete_product'            => true,
				'edit_products'             => true,
				'publish_products'          => true,
				'read_private_products'     => true,
				'delete_products'           => true,
				'delete_products'           => true,
				'delete_private_products'   => true,
				'delete_published_products' => true,
				'delete_published_products' => true,
				'edit_private_products'     => true,
				'edit_published_products'   => true,
				'manage_product_terms'      => true,
				'delete_product_terms'      => true,
				'assign_product_terms'      => true,
			) );

			if (class_exists('WeDevs_Dokan')) :

				$capabilities = [];
				$all_cap      = dokan_get_all_caps();

				foreach ($all_cap as $key => $cap) {
					$capabilities = array_merge($capabilities, array_keys($cap));
				}

				foreach ($capabilities as $key => $capability) {
					$wp_roles->add_cap('owner', $capability);
				}
				
			endif;
			$capabilities = array(
				'core' => array(
					'manage_listings'
				),
				'listing' => array(
					"edit_listing",
					"read_listing",
					"delete_listing",
					"edit_listings",
					"edit_others_listings",
					"publish_listings",
					"read_private_listings",
					"delete_listings",
					"delete_private_listings",
					"delete_published_listings",
					"delete_others_listings",
					"edit_private_listings",
					"edit_published_listings",
					"manage_listing_terms",
					"edit_listing_terms",
					"delete_listing_terms",
					"assign_listing_terms"
				));

				add_role( 'guest', __( 'Guest', 'truelysell_core' ), array(
						'read'  => true,
				) );

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->add_cap( 'administrator', $cap );
				}
			}
		}

	}
	
	//Add support1.3.1
	function truelysell_core_update_db_1_3_2() {
		$db_option = get_option( 'truelysell_core_db_version', '1.3.1' );
		if ( $db_option && version_compare( $db_option, '1.3.2', '<' ) ) {
			global $wpdb;

			$sql = "ALTER TABLE `{$wpdb->prefix}truelysell_core_conversations` ADD `notification` VARCHAR(10) DEFAULT 'sent' AFTER `last_update`";
			$wpdb->query( $sql );

			update_option( 'truelysell_core_db_version', '1.3.2' );
		}
	}

	function truelysell_core_update_db_1_5_18() {
		$db_option = get_option( 'truelysell_core_db_version', '1.3.2' );
		if ( $db_option && version_compare( $db_option, '1.5.18', '<' ) ) {
			global $wpdb;

			$sql = "ALTER TABLE `{$wpdb->prefix}truelysell_core_user_packages` 
			ADD   package_option_booking int(1) NULL,
			ADD	  package_option_reviews int(1) NULL,
			ADD	  package_option_gallery int(1) NULL,
			ADD	  package_option_gallery_limit bigint(20) NULL,
			ADD	  package_option_social_links int(1) NULL,
			ADD	  package_option_opening_hours int(1) NULL,
			ADD	  package_option_video int(1) NULL,
			ADD	  package_option_coupons int(1) NULL";
			$wpdb->query( $sql );

			update_option( 'truelysell_core_db_version', '1.5.18' );
		}
	}

}