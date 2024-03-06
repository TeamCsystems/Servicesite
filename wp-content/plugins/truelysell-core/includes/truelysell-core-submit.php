<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Truelysell_Core_Submit  {

	/**
	 * Form name.
	 *
	 * @var string
	 */
	public $form_name = 'submit-listing';

	/**
	 * Listing ID.
	 *
	 * @access protected
	 * @var int
	 */
	protected $listing_id;


	/**
	 * Listing Type
	 *
	 * @var string
	 */
	protected $listing_type;


	/**
	 * Form fields.
	 *
	 * @access protected
	 * @var array
	 */
	protected $fields = array();


	/**
	 * Form errors.
	 *
	 * @access protected
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Form steps.
	 *
	 * @access protected
	 * @var array
	 */
	protected $steps = array();

	/**
	 * Current form step.
	 *
	 * @access protected
	 * @var int
	 */
	protected $step = 0;


	/**
	 * Form action.
	 *
	 * @access protected
	 * @var string
	 */
	protected $action = '';

	/**
	 * Form form_action.
	 *
	 * @access protected
	 * @var string
	 */
	protected $form_action = '';

	private static $package_id      = 0;
	private static $is_user_package = false;

	/**
	 * Stores static instance of class.
	 *
	 * @access protected
	 * @var Truelysell_Core_Submit The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Returns static instance of class.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * Constructor
	 */
	public function __construct() {

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

		add_shortcode( 'truelysell_submit_listing', array( $this, 'get_form' ) );
	
		add_filter( 'submit_listing_steps', array( $this, 'enable_paid_listings' ), 30 );

		add_action( 'wp', array( $this, 'process' ) );

		$this->steps  = (array) apply_filters( 'submit_listing_steps', array(

			'type' => array(
				'name'     => __( 'Choose Type ', 'truelysell_core' ),
				'view'     => array( $this, 'type' ),
				'handler'  => array( $this, 'type_handler' ),
				'priority' => 9
				),
			'submit' => array(
				'name'     => __( 'Submit Details', 'truelysell_core' ),
				'view'     => array( $this, 'submit' ),
				'handler'  => array( $this, 'submit_handler' ),
				'priority' => 10
				),
			'preview' => array(
				'name'     => __( 'Preview', 'truelysell_core' ),
				'view'     => array( $this, 'preview' ),
				'handler'  => array( $this, 'preview_handler' ),
				'priority' => 20
			),
			'done' => array(
				'name'     => __( 'Done', 'truelysell_core' ),
				'view'     => array( $this, 'done' ),
				'priority' => 30
			)
		) );

		$listing_types = truelysell_fl_framework_getoptions('listing_types',array( 'service', 'rental', 'event','classifieds' ));
		if(empty($listing_types)){
			unset($this->steps['type']);
		}
		if(is_array($listing_types) && sizeof($listing_types) == 1 ){
			unset($this->steps['type']);	
		}	
		uasort( $this->steps, array( $this, 'sort_by_priority' ) );


		if ( ! empty( $_POST['package'] ) ) {
			if ( is_numeric( $_POST['package'] ) ) {
	
				self::$package_id      = absint( $_POST['package'] );
				self::$is_user_package = false;
			} else {
			
				self::$package_id      = absint( substr( $_POST['package'], 5 ) );
				self::$is_user_package = true;
			}
		} elseif ( ! empty( $_COOKIE['chosen_package_id'] ) ) {
			self::$package_id      = absint( $_COOKIE['chosen_package_id'] );
			self::$is_user_package = absint( $_COOKIE['chosen_package_is_user_package'] ) === 1;
		}

		// Get step/listing
		if ( isset( $_POST['step'] ) ) {
			$this->step = is_numeric( $_POST['step'] ) ? max( absint( $_POST['step'] ), 0 ) : array_search( $_POST['step'], array_keys( $this->steps ) );
		} elseif ( ! empty( $_GET['step'] ) ) {
			$this->step = is_numeric( $_GET['step'] ) ? max( absint( $_GET['step'] ), 0 ) : array_search( $_GET['step'], array_keys( $this->steps ) );
		}

		$this->listing_id = ! empty( $_REQUEST[ 'listing_id' ] ) ? absint( $_REQUEST[ 'listing_id' ] ) : 0;
		$this->listing_type = ! empty( $_REQUEST[ '_listing_type' ] ) ?  $_REQUEST[ '_listing_type' ]  : false;
		
		if(is_array($listing_types) && sizeof($listing_types) == 1 ){
			$this->listing_type = $listing_types[0];
		}	

		if(!is_admin() && isset($_GET["action"]) && isset($_GET['listing_id'])  && $_GET["action"] == 'edit' ) {
		 	$this->form_action = "editing";
		 	unset($this->steps['type']);
		 	$this->listing_id = ! empty( $_GET[ 'listing_id' ] ) ? absint( $_GET[ 'listing_id' ] ) : 0;
		 	
		 	if(self::$package_id==0){
		 		self::$package_id = get_post_meta($_GET[ 'listing_id' ],'_package_id',true);
		 		if(get_post_meta($_GET[ 'listing_id' ],'_user_package_id',true)){
		 			self::$is_user_package = true;
		 		}

		 	}
		} 

		if(isset($_GET["action"]) && $_GET["action"] == 'renew' ) {
		 	$this->form_action = "renew";
		 	unset($this->steps['type']);
		 	$this->listing_id = ! empty( $_GET[ 'listing_id' ] ) ? absint( $_GET[ 'listing_id' ] ) : 0;
		 	if(self::$package_id==0){
		 		self::$package_id = get_post_meta($_GET[ 'listing_id' ],'_package_id',true);
		 		if(get_post_meta($_GET[ 'listing_id' ],'_user_package_id',true)){
		 			self::$is_user_package = true;
		 		}

		 	}
		}

		$this->listing_edit = false;
		if ( ! isset( $_GET[ 'new' ] ) && ( ! $this->listing_id ) && ! empty( $_COOKIE['truelysell-submitting-listing-id'] ) && ! empty( $_COOKIE['truelysell-submitting-listing-key'] ) ) {
			$listing_id     = absint( $_COOKIE['truelysell-submitting-listing-id'] );
			$listing_status = get_post_status( $listing_id );

			if ( ( 'preview' === $listing_status || 'pending_payment' === $listing_status ) && get_post_meta( $listing_id, '_submitting_key', true ) === $_COOKIE['truelysell-submitting-listing-key'] ) {
				$this->listing_id = $listing_id;
				$this->listing_edit = get_post_meta( $listing_id, '_submitting_key', true );
				
			}
		}

		// We should make sure new jobs are pending payment and not published or pending.
		add_filter( 'submit_listing_post_status', array( $this, 'submit_listing_post_status' ), 10, 2 );

	}


	/**
	 * Processes the form result and can also change view if step is complete.
	 */
	public function process() {

		// reset cookie
		if (
			isset( $_GET[ 'new' ] ) &&
			isset( $_COOKIE[ 'truelysell-submitting-listing-id' ] ) &&
			isset( $_COOKIE[ 'truelysell-submitting-listing-key' ] ) &&
			get_post_meta( $_COOKIE[ 'truelysell-submitting-listing-id' ], '_submitting_key', true ) == $_COOKIE['truelysell-submitting-listing-key']
		) {
			delete_post_meta( $_COOKIE[ 'truelysell-submitting-listing-id' ], '_submitting_key' );
			setcookie( 'truelysell-submitting-listing-id', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN, false );
			setcookie( 'truelysell-submitting-listing-key', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN, false );

			wp_redirect( remove_query_arg( array( 'new', 'key' ), $_SERVER[ 'REQUEST_URI' ] ) );

		}

		$step_key = $this->get_step_key( $this->step );

		if(isset( $_POST[ 'truelysell_core_form' ] )) {
		

			if ( $step_key && isset( $this->steps[ $step_key ]['handler']) && is_callable( $this->steps[ $step_key ]['handler'] ) ) {
				call_user_func( $this->steps[ $step_key ]['handler'] );
			}
		}
		$next_step_key = $this->get_step_key( $this->step );
		
		// if the step changed, but the next step has no 'view', call the next handler in sequence.
		if ( $next_step_key && $step_key !== $next_step_key && ! is_callable( $this->steps[ $next_step_key ]['view'] ) ) {
			$this->process();
		}

	}

	/**
	 * Initializes the fields used in the form.
	 */
	public function init_fields() {
		
		if ( $this->fields ) {
			return;
		}

		$scale = get_option( 'scale', 'sq ft' );
		
		$currency_abbr = truelysell_fl_framework_getoptions('currency' );
  		
 		$currency = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
		
		$this->fields =  array(
			'basic_info' => array(
				'title' 	=> __('Basic Information','truelysell_core'),
				'class' 	=> '',
				'icon' 		=> '',
				'fields' 	=> array(
						'listing_title' => array(
							'label'       => __( 'Service Title', 'truelysell_core' ),
							'type'        => 'text',
							'name'       => 'listing_title',
							'tooltip'	  => '',
							'required'    => true,
							'placeholder' => '',
							'class'		  => 'form-control',
							'priority'    => 1,
							
						),
						'listing_category' => array(
							'label'       => __( 'Category', 'truelysell_core' ),
							'type'        => 'term-select',
							'placeholder' => '',
							'name'        => 'listing_category',
							'taxonomy'	  => 'listing_category',
							'tooltip'	  => '',
							'priority'    => 10,
							'default'	  => '',
							'render_row_col' => '12',
							'multi'    	  => false,
							'required'    => true,
						),
 				),
			),
			'location' =>  array(
				'title' 	=> __('Location','truelysell_core'),
				'icon' 		=> '',
				'fields' 	=> array(
					
					'region' => array(
						'label'       => __( 'Region', 'truelysell_core' ),
						'type'        => 'term-select',
						'required'    => false,
						'name'        => 'region',
						'taxonomy'        => 'region',
						'placeholder' => '',
						'class'		  => '',
						
						'priority'    => 8,
						'render_row_col' => '6',
						
					),		
					'_friendly_address' => array(
						'label'       => __( 'Address', 'truelysell_core' ),
						'type'        => 'text',
						'required'    => false,
						'name'        => '_friendly_address',
						'placeholder' => '',
						'tooltip'	  =>  '',
						'class'		  => '',
						'required'    => true,
						'priority'    => 8,
						'render_row_col' => '6',
						'class' =>'form-control'
					),	
					 
				),
			),
			'gallery' => array(
				'title' 	=> __('Gallery','truelysell_core'),
				'icon' 		=> '',
				'fields' 	=> array(
						'_gallery' => array(
							'label'       => __( 'Gallery', 'truelysell_core' ),
							'name'       => '_gallery',
							'type'        => 'files',
							'description' => __( 'By selecting (clicking on a photo) one of the uploaded photos you will set it as Featured Image for this service (marked by icon with star). Drag and drop thumbnails to re-order images in gallery.', 'truelysell_core' ),
							'placeholder' => 'Upload images',
							'class'		  => '',
							'priority'    => 1,
							'required'    => false,
						),				
					
				),
			),
			'details' => array(
				'title' 	=> __('Details','truelysell_core'),
				'icon' 		=> '',
				'fields' 	=> array(
						'listing_description' => array(
							'label'       => __( 'Description', 'truelysell_core' ),
							'name'       => 'listing_description',
							'type'        => 'wp-editor',
							'description' => '',
							'placeholder' => 'Description',
							'class'		  => '',
							'priority'    => 1,
							'required'    => true,
						),				
						'_video' => array(
							'label'       => __( 'Video', 'truelysell_core' ),
							'type'        => 'text',
							'name'        => '_video',
							'required'    => false,
							'placeholder' => '',
							'class'		  => 'form-control',
							'priority'    => 5,
							'render_row_col' => '12'
						),

						'_phone' => array(
							'label'       => __( 'Phone', 'truelysell_core' ),
							'type'        => 'text',
							'required'    => false,
							'placeholder' => '',
							'name'        => '_phone',
							'class'		  => 'form-control',
							'priority'    => 9,
							'render_row_col' => '6'
						),		
						 
						'_email' => array(
							'label'       => __( 'E-mail', 'truelysell_core' ),
							'type'        => 'text',
							'required'    => false,
							'placeholder' => '',
							'name'        => '_email',
							
							'class'		  => 'form-control',
							'priority'    => 10,
							'render_row_col' => '6'
						),
						 			
						
						 

				),
			),
			
			'opening_hours' => array(
				'title' 	=> __('Opening Hours','truelysell_core'),
				'onoff'		=> true,
				'icon' 		=> '',
				'fields' 	=> array(
						'_opening_hours_status' => array(
								'label'       => __( 'Opening Hours status', 'truelysell_core' ),
								'type'        => 'skipped',
								'required'    => false,
								'name'        => '_opening_hours_status',
						),
						
						
						'_opening_hours' => array(
							'label'       => __( 'Opening Hours', 'truelysell_core' ),
							'name'       => '_opening_hours',
							'type'        => 'hours',
							'placeholder' => '',
							'class'		  => '',
							'priority'    => 1,
							'required'    => false,
						),	
						'_monday_opening_hour' => array(
							'label'       => __( 'Monday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_monday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),
						'_monday_closing_hour' => array(
							'label'       => __( 'Monday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_monday_closing_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),	
						'_tuesday_opening_hour' => array(
							'label'       => __( 'Tuesday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_tuesday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),
						'_tuesday_closing_hour' => array(
							'label'       => __( 'Monday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_tuesday_closing_hour',
							'before_row' 	 => '',
							'priority'    => 9,
							'render_row_col' => '4'
						),
	
						'_wednesday_opening_hour' => array(
							'label'       => __( 'Wednesday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_wednesday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),
						'_wednesday_closing_hour' => array(
							'label'       => __( 'Wednesday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_wednesday_closing_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),		
						'_thursday_opening_hour' => array(
							'label'       => __( 'Thursday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_thursday_opening_hour',
							'before_row' 	 => '',
							'priority'    => 9,
							'render_row_col' => '4'
						),
						'_thursday_closing_hour' => array(
							'label'       => __( 'Thursday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_thursday_closing_hour',
							'before_row' 	 => '',
							'priority'    => 9,
							'render_row_col' => '4'
						),						
						'_friday_opening_hour' => array(
							'label'       => __( 'Friday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_friday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),
						'_friday_closing_hour' => array(
							'label'       => __( 'Friday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_friday_closing_hour',
							'before_row' 	 => '',
							'priority'    => 9,
							'render_row_col' => '4'
						),												
						'_saturday_opening_hour' => array(
							'label'       => __( 'saturday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_saturday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),
						'_saturday_closing_hour' => array(
							'label'       => __( 'saturday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_saturday_closing_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),														
						'_sunday_opening_hour' => array(
							'label'       => __( 'sunday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_sunday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),
						'_sunday_closing_hour' => array(
							'label'       => __( 'sunday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_sunday_closing_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						),				
						'_listing_timezone' => array(
								'label'       => __( 'Listing Timezone', 'truelysell_core' ),
								'type'        => 'timezone',
								'required'    => false,
								'name'        => '_listing_timezone',
						),
						
				),
			),
			'event' => array(
				'title'		=> __( 'Event Date', 'truelysell_core' ),
				'icon'		=> '',
				'fields'	=> array(
					'_event_date' => array(
						'label'       => __( 'Event Date', 'truelysell_core' ),
						'tooltip'	  => __('Select date when even will start', 'truelysell_core'),
						'type'        => 'text',						
						'required'    => false,
						'name'        => '_event_date',
						'class'		  => 'input-datetime',
						'placeholder' => '',
						'priority'    => 9,
						'render_row_col' => '6'
					),
					'_event_date_end' => array(
						'label'       => __( 'Event Date End', 'truelysell_core' ),
						'tooltip'	  => __('Select date when even will end', 'truelysell_core'),
						'type'        => 'text',
						'required'    => false,
						'name'        => '_event_date_end',
						'class'		  => 'input-datetime',
						'placeholder' => '',
						'priority'    => 9,
						'render_row_col' => '6'
					),
					
				)
			),
			 
			 
			'booking' => array(
				'title' 	=> __('Booking','truelysell_core'),
				'class' 	=> 'booking-enable',
				'onoff'		=> true,
				'icon' 		=> '',
				'fields' 	=> array(
					'_booking_status' => array(
							'label'       => __( 'Booking status', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_booking_status',
							
					),
				)
			),
			'slots' => array(
				'title' 	=> __('Availability','truelysell_core'),
				'onoff'		=> true,
				'icon' 		=> '',
				'fields' 	=> array(
						'_slots_status' => array(
								'label'       => __( 'Booking status', 'truelysell_core' ),
								'type'        => 'skipped',
								'required'    => false,
								'name'        => '_slots_status',
						),
						'_slots' => array(
							'label'       => __( 'Availability Slots', 'truelysell_core' ),
							'name'       => '_slots',
							'type'        => 'slots',
							'placeholder' => '',
							'class'		  => '',
							'priority'    => 1,
							'required'    => false,
						),				
						
				),
			),
			

			'basic_prices' => array(
				'title'		=> __('Booking prices and settings','truelysell_core'),
				'icon'		=> '',
				'fields'	=> array(
					
					'_event_tickets' => array(
						'label'       => __( 'Available Tickets', 'truelysell_core' ),
						'tooltip'	  => '',
						'type'        => 'number',
						'required'    => false,
						'name'        => '_event_tickets',
						'class'		  => '',
						'placeholder' => '',
						'priority'    => 9,
						'render_row_col' => '6'
					),

					'_normal_price' => array(
						'label'       => __( 'Regular Price', 'truelysell_core' ),
						'type'        => 'number',
						'tooltip'	  => '',
						'required'    => false,
						'default'           => '0',
						'placeholder' => '',
						'unit'		  => '',
						'name'        => '_normal_price',
						'class'		  => '',
						'priority'    => 10,
						'priority'    => 9,
						'render_row_col' => '4'
						
					),	

					   
					'_instant_booking' => array(
						'label'       => __( 'Enable Instant Booking', 'truelysell_core' ),
						'type'        => 'checkbox',
						'tooltip'	  => '',
						'required'    => false,
						'placeholder' => '',
						'name'        => '_instant_booking',
						'class'		  => '',
						'priority'    => 10,
						'priority'    => 9,
						'render_row_col' => '4'
					),
					  
					'_end_hour' => array(
						'label'       => __( 'Enable End Hour time-picker', 'truelysell_core' ),
						'type'        => 'checkbox',
						'tooltip'	  =>  '',
						'required'    => false,
						
						'placeholder' => '',
						'name'        => '_end_hour',
						'class'		  => '',
						'priority'    => 10,
						'priority'    => 9,
						'render_row_col' => '4',
						'for_type'	  => 'service'
					),	 
					 
					
						
				),
			),

			 

		);

		$this->fields = apply_filters('submit_listing_form_fields', $this->fields);
		// get listing type

		if ( ! $this->listing_type)
		{
			$listing_type_array = get_post_meta( $this->listing_id, '_listing_type' );
		}
		
		// disable opening hours everywhere outside services
		if ( $this->listing_type != 'service' && apply_filters('disable_opening_hours', false) ) 
			unset( $this->fields['opening_hours'] );

		// disable slots everywhere outside services
		if ( $this->listing_type != 'service' && apply_filters('disable_slots', true) ) 
			unset( $this->fields['slots'] );

		// disable availability calendar outside rent
		if ( $this->listing_type == 'event' && apply_filters('disable_availability_calendar', true) ) 
			unset( $this->fields['availability_calendar'] );
		

		if ( $this->listing_type != 'classifieds' ) 
			unset( $this->fields['classifieds'] );

		// disable event date calendar outside events
		if ( $this->listing_type != 'event' ) 
		{
			unset( $this->fields['event']);
			unset( $this->fields['basic_prices']['fields']['_event_tickets'] );
		} else {
			// disable fields for events
			unset( $this->fields['basic_prices']['fields']['_weekday_price'] );
			unset( $this->fields['basic_prices']['fields']['_count_per_guest'] );
			unset( $this->fields['basic_prices']['fields']['_max_guests'] );

			$this->fields['basic_prices']['fields']['_event_tickets']['render_row_col'] = 3;
			$this->fields['basic_prices']['fields']['_normal_price']['render_row_col'] = 3;
			$this->fields['basic_prices']['fields']['_normal_price']['label'] = esc_html__('Ticket Price','truelysell_core');
			$this->fields['basic_prices']['fields']['_reservation_price']['render_row_col'] = 3;
			$this->fields['basic_prices']['fields']['_expired_after']['render_row_col'] = 3;
		}

		 
		//add coupon fields

		if(!truelysell_fl_framework_getoptions('remove_coupons')) {

				//get user coupons

					$current_user = wp_get_current_user();
					

					$args = array(
						'author'        	=>  $current_user->ID,
					    'posts_per_page'   => -1,
					    'orderby'          => 'title',
					    'order'            => 'asc',
					    'post_type'        => 'shop_coupon',
					    'post_status'      => 'publish',
					);
			    	$coupon_options = array();
					$coupons = get_posts( $args );
					if($coupons){
						$coupon_options[0] = esc_html__('Select coupon','truelysell_core');
					}
					foreach ($coupons as $coupon) {
						$coupon_options[$coupon->ID] = $coupon->post_title;
					}


			 

		}

		$current_user = wp_get_current_user();

if (class_exists('WeDevs_Dokan') ) :
		$args = array(
			'author'        	=>  $current_user->ID,
			'posts_per_page'   => -1,
			'orderby'          => 'title',
			'order'            => 'asc',
			'post_type'        => 'product',
			'post_status'      => 'publish',
		);
		$args['tax_query'] = array(
			'taxonomy' => 'product_type',
			'field'    => 'slug',
			'terms' => array('listing_booking','listing_package'), // 
			'operator' => 'NOT IN'
		);

		$product_options = array();
		
		$args['exclude_listing_booking'] = 'true';
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field' => 'slug',
			'terms' => array('truelysell-booking'), // Don't display products in the clothing category on the shop page.
			'operator' => 'NOT IN'
		);
		$args['tax_query'][] = array(
			'taxonomy' => 'product_type',
			'field' => 'slug',
			'terms' => array('listing_package'), // Don't display products in the clothing category on the shop page.
			'operator' => 'NOT IN'
		);
		$products = wc_get_products($args); 
		if ($products) {
			$product_options[0] = esc_html__('Select product', 'truelysell_core');
		}
		foreach ($products as $product) {
			$product_options[$product->get_id()] = $product->get_title();
		}
			// dokan section
		if ($products) {
			$this->fields['store_section'] = array(
				'title' 	=> __('Store Settings', 'truelysell_core'),
				'onoff'		=> true,
				'icon' 		=> 'fa fa-store-alt',
				'fields' 	=> array(
					'_store_section_status' => array(
						'label'       => __('Store Section status', 'truelysell_core'),
						'type'        => 'skipped',
						'required'    => false,
						'name'        => '_store_section_status',
					),
					'_store_products' => array(
						'label'       => __('Select some of your products to display in this listing view', 'truelysell_core'),
						'name'       => '_store_products',
						'type'        => 'select',
						'placeholder' => '',
						'class'		  => '',
						'priority'    => 1,
						'multi'    => 'on',
						'options'	 => $product_options,
						'required'    => false,
					),
					'_store_widget_status' => array(
						'type'        => 'checkboxes',
						'required'    => false,
						'placeholder' => '',
						'name'        => '_store_widget_status',
						'label'       => '',
						'placeholder' => '',
						'class'		  => '',
						'options'	=> array(
							'show' => __('Show store card widget on listing sidebar', 'truelysell_core' )
						),
					),

				),
			);
		}
	endif; //dokan
		if(isset( $this->fields['opening_hours']['fields']['_opening_hours'])){
			 $this->fields['opening_hours']['fields']['_monday_opening_hour'] = array(
							'label'       => __( 'Monday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_monday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			 $this->fields['opening_hours']['fields']['_monday_closing_hour'] = array(
							'label'       => __( 'Monday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_monday_closing_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);	
			$this->fields['opening_hours']['fields']['_tuesday_opening_hour'] = array(
							'label'       => __( 'Tuesday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_tuesday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_tuesday_closing_hour'] = array(
							'label'       => __( 'Monday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_tuesday_closing_hour',
							'before_row' 	 => '',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_wednesday_opening_hour'] = array(
							'label'       => __( 'Wednesday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_wednesday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_wednesday_closing_hour'] = array(			
							'label'       => __( 'Wednesday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_wednesday_closing_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_thursday_opening_hour'] = array(				
							'label'       => __( 'Thursday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_thursday_opening_hour',
							'before_row' 	 => '',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_thursday_closing_hour'] = array(			
							'label'       => __( 'Thursday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_thursday_closing_hour',
							'before_row' 	 => '',
							'priority'    => 9,
							'render_row_col' => '4'
						);	
			$this->fields['opening_hours']['fields']['_friday_opening_hour'] = array(							
							'label'       => __( 'Friday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_friday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_friday_closing_hour'] = array(			
							'label'       => __( 'Friday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_friday_closing_hour',
							'before_row' 	 => '',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_saturday_opening_hour'] = array(												
							'label'       => __( 'saturday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_saturday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_saturday_closing_hour'] = array(			
							'label'       => __( 'saturday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_saturday_closing_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_sunday_opening_hour'] = array(													
							'label'       => __( 'sunday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_sunday_opening_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);
			$this->fields['opening_hours']['fields']['_sunday_closing_hour'] = array(			
							'label'       => __( 'sunday Opening Hour', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_sunday_closing_hour',
							'priority'    => 9,
							'render_row_col' => '4'
						);
		}



		$this->fields['basic_info']['fields']['product_id'] = array(
							'name'        => 'product_id',
							'type'        => 'hidden',							
							'required'    => false,
						);

		$this->fields['gallery']['fields']['_thumbnail_id'] = array(
							'label'       => __( 'Thumbnail ID', 'truelysell_core' ),
							'type'        => 'hidden',
							'name'        => '_thumbnail_id',
							'class'		  => '',
							'priority'    => 1,
							'required'    => false,
						);
		// remove parts for booking

		$packages_disabled_modules = truelysell_fl_framework_getoptions('listing_packages_options',array());
		if(empty($packages_disabled_modules)) {
			$packages_disabled_modules = array();
		}

			$package = false;

			if(!empty(self::$package_id)){
							
				if(self::$is_user_package){
					if(get_post_meta($this->listing_id,'_user_package_id',true)){
						$package = truelysell_core_get_user_package( get_post_meta($this->listing_id,'_user_package_id',true) );
					} else {
						$package = truelysell_core_get_user_package( self::$package_id );
					}
					

				} else {
					$package = wc_get_product(self::$package_id );

				}
				
			} 

			foreach ( $this->fields as $group_key => $group_fields ) {
				foreach ( $group_fields['fields']  as $key => $field ) {
					

					if(in_array('option_booking',$packages_disabled_modules)){
						if($key == '_booking_status') {
							if($package && $package->has_listing_booking() == 1){

							} else {
							  unset( $this->fields[$group_key] );	
							}
						}
					}
					if(in_array('option_social_links',$packages_disabled_modules)){
						if( in_array ( $key, array('_facebook','_twitter','_youtube','_instagram','_whatsapp','_skype','_website') ) ) {
								if($package && $package->has_listing_social_links() == 1){

								} else {
									unset( $this->fields[$group_key]['fields'][$key] );		
								}
							
						}
					}
					if(in_array('option_opening_hours',$packages_disabled_modules)){

						if($key == '_opening_hours') {
							if($package && $package->has_listing_opening_hours() == 1){

							} else {
								unset( $this->fields[$group_key] );
							}
						}
					}

					if($key == '_gallery') {
						$gallery_limit = truelysell_fl_framework_getoptions('max_files');
						if(!empty(self::$package_id)){

								if($package && $package->get_option_gallery_limit()){
									$gallery_limit = $package->get_option_gallery_limit();
								} else {
									$gallery_limit = truelysell_fl_framework_getoptions('max_files');
								}
							
						} else {
							$gallery_limit = truelysell_fl_framework_getoptions('max_files');
						}
						$this->fields[$group_key]['fields'][$key]['max_files'] = $gallery_limit;
					}
				
					if(in_array('option_gallery',$packages_disabled_modules)){
						if($key == '_gallery') {
							if($package && $package->has_listing_gallery() == 1){

							} else {
								unset( $this->fields[$group_key] );
							}
						}
					}

					if(in_array('option_video',$packages_disabled_modules)){

						if($key == '_video') {
							if($package && $package->has_listing_video() == 1){

							} else {
								unset( $this->fields[$group_key]['fields'][$key] );
							}
						}
					}
					if(in_array('option_coupons',$packages_disabled_modules)){
						if($key == '_coupon_section_status') {
							if($package && $package->has_listing_coupons() == 1){

							} else {
							unset( $this->fields['coupon_section'] );
							}
						}
					}
				}
			}

		switch ( $this->listing_type) {
			case 'event':
				foreach ( $this->fields as $group_key => $group_fields ) {
					foreach ( $group_fields['fields']  as $key => $field ) {
						if ( !empty($field['for_type']) && in_array($field['for_type'],array('rental','service','classifieds') ) ) {
							unset( $this->fields[$group_key]['fields'][$key] );
						}
					}
				}

			break;
			case 'service':
				foreach ( $this->fields as $group_key => $group_fields ) {
					foreach ( $group_fields['fields']  as $key => $field ) {
						if ( !empty($field['for_type']) && in_array($field['for_type'],array('rental','event','classifieds') ) ) {
							unset($this->fields[$group_key]['fields'][$key]);
						}
					}
				}
			break;
			case 'rental':
				foreach ( $this->fields as $group_key => $group_fields ) {
					foreach ( $group_fields['fields']  as $key => $field ) {
						if ( !empty($field['for_type']) && in_array($field['for_type'],array('event','service','classifieds') ) ) {
							unset($this->fields[$group_key]['fields'][$key]);
						}
					}
				}
			break;
			case 'classifieds':
				foreach ( $this->fields as $group_key => $group_fields ) {
					foreach ( $group_fields['fields']  as $key => $field ) {
						if ( !empty($field['for_type']) && in_array($field['for_type'],array('event','service','rental') ) ) {
							unset($this->fields[$group_key]['fields'][$key]);
						}
					}
				}
				unset( $this->fields['booking'] );
				unset( $this->fields['slots'] );
				unset( $this->fields['basic_prices'] );
				unset( $this->fields['availability_calendar'] );
				unset( $this->fields['coupon_section'] );
				unset( $this->fields['menu'] );

		 	break;
			
		 	default:

		 		break;
		 }
		if(truelysell_fl_framework_getoptions('bookings_disabled')){
			unset( $this->fields['booking'] );
			unset( $this->fields['slots'] );
			unset( $this->fields['basic_prices'] );
			unset( $this->fields['availability_calendar'] );
		}
		
	}

	/**
	 * Validates the posted fields.
	 *
	 * @param array $values
	 * @throws Exception Uploaded file is not a valid mime-type or other validation error
	 * @return bool|WP_Error True on success, WP_Error on failure
	 */
	protected function validate_fields( $values ) {

		foreach ( $this->fields as $group_key => $group_fields ) {
			
			foreach ( $group_fields['fields']  as $key => $field ) {

				if ( $field['type'] != 'header' && isset($field['required']) && $field['required'] && empty( $values[ $group_key ][ $key ] ) ) {
					return new WP_Error( 'validation-error', sprintf( __( '%s is a required field', 'truelysell_core' ), $field['label'] ) );
				}
				if ( ! empty( $field['taxonomy'] ) && in_array( $field['type'], array( 'term-checkboxes', 'term-select', 'term-multiselect' ) ) ) {
					if ( is_array( $values[ $group_key ][ $key ] ) ) {
						$check_value = $values[ $group_key ][ $key ];
					} else {
						$check_value = empty( $values[ $group_key ][ $key ] ) ? array() : array( $values[ $group_key ][ $key ] );
					}

					foreach ( $check_value as $term ) {
						if ( (int) $term != -1 ){

							if ( ! term_exists( (int) $term, $field['taxonomy'] ) ) {

								return new WP_Error( 'validation-error', sprintf( __( '%s is invalid', 'truelysell_core' ), $field['label'] ) );
							}
						}
					
						if( isset($field['required']) && $field['required'] != 0 &&  (int) $term == -1 ){ 
							return new WP_Error( 'validation-error', sprintf( __( '%s is a required field', 'truelysell_core' ), $field['label'] ) );
						}
					}
				}
			}
		}
	
		return apply_filters( 'submit_listing_form_validate_fields', true, $this->fields, $values );
	}



	/**
	 * Displays the form.
	 */
	public function submit() {

		$this->init_fields();
		$template_loader = new Truelysell_Core_Template_Loader;
		if ( ! is_user_logged_in() ) {
			$template_loader->get_template_part( 'listing-sign-in' );
			$template_loader->get_template_part( 'account/login' ); 
		} else {


		if ( is_user_logged_in() && $this->listing_id ) {
			$listing = get_post( $this->listing_id );
			
			if($listing){

				foreach ( $this->fields as $group_key => $group_fields ) {
					foreach ( $group_fields['fields'] as $key => $field ) {
					
						switch ( $key ) {
							case 'listing_title' :
								$this->fields[ $group_key ]['fields'][ $key ]['value'] = $listing->post_title;
							break;
							case 'listing_description' :
								$this->fields[ $group_key ]['fields'][ $key ]['value'] = $listing->post_content;
							break;
							case 'listing_feature' :
								$this->fields[ $group_key ]['fields'][ $key ]['value'] =  wp_get_object_terms( $listing->ID, 'listing_feature', array( 'fields' => 'ids' ) ) ;
							break;
							case 'listing_category' :
								$this->fields[ $group_key ]['fields'][ $key ]['value'] =  wp_get_object_terms( $listing->ID, 'listing_category', array( 'fields' => 'ids' ) ) ;
							break;
							case 'service_category' :
								$this->fields[ $group_key ]['fields'][ $key ]['value'] =  wp_get_object_terms( $listing->ID, 'service_category', array( 'fields' => 'ids' ) ) ;
							break;
							case 'rental_category' :
								$this->fields[ $group_key ]['fields'][ $key ]['value'] =  wp_get_object_terms( $listing->ID, 'rental_category', array( 'fields' => 'ids' ) ) ;
							break;
							case 'event_category' :
								$this->fields[ $group_key ]['fields'][ $key ]['value'] =  wp_get_object_terms( $listing->ID, 'event_category', array( 'fields' => 'ids' ) ) ;
							break;
							
							case (substr($key,0,4) == 'tax-') :
								$tax = substr($key, 4);
								$this->fields[ $group_key ]['fields'][ $key ]['value'] =  wp_get_object_terms( $listing->ID, $tax, array( 'fields' => 'ids' ) ) ;
								
							break;
							
							case '_opening_hours' :

								$days = truelysell_get_days();
								$opening_hours = array();
								foreach ($days as $d_key => $value) {
									$value_day = get_post_meta( $listing->ID, '_'.$d_key.'_opening_hour', true );
									if($value_day){
										$opening_hours[$d_key.'_opening'] = $value_day;
									}
									$value_day = get_post_meta( $listing->ID, '_'.$d_key.'_closing_hour', true );
									if($value_day){
										$opening_hours[$d_key.'_closing'] = $value_day;
									}
								
									
								}
								
								$this->fields[ $group_key ]['fields'][ $key ]['value'] = $opening_hours;
							break;
							case 'region' :
								$this->fields[ $group_key ]['fields'][ $key ]['value'] = wp_get_object_terms( $listing->ID, 'region', array( 'fields' => 'ids' ) );
							break;
					
							default:
							
								if(isset($this->fields[ $group_key ]['fields'][ $key ]['multi']) && $this->fields[ $group_key ]['fields'][ $key ]['multi']) {
									$this->fields[ $group_key ]['fields'][ $key ]['value'] = get_post_meta( $listing->ID, $key, false );
								} else {
									$this->fields[ $group_key ]['fields'][ $key ]['value'] = get_post_meta( $listing->ID, $key, true );
								}
								if($this->fields[ $group_key ]['fields'][ $key ]['type'] == 'checkboxes'){
								    $this->fields[ $group_key ]['fields'][ $key ]['value'] = get_post_meta( $listing->ID, $key, false );
								}
							break;
						}
					
					}
				}
			}
			
		}  elseif ( is_user_logged_in() && empty( $_POST['submit_listing'] ) ) {
			$this->fields = apply_filters( 'submit_listing_form_fields_get_user_data', $this->fields, get_current_user_id() );
		}
		
		
		$template_loader->set_template_data( 
			array( 
				'action' 		=> $this->get_action(),
				'fields' 		=> $this->fields,
				'form'      	=> $this->form_name,
				'listing_edit' => $this->listing_edit,
				'listing_id'   => $this->get_listing_id(),
				'step'      	=> $this->get_step(),
				'submit_button_text' => apply_filters( 'submit_listing_form_submit_button_text', __( 'Preview', 'truelysell_core' ) )
				) 
			)->get_template_part( 'listing-submit' );
		}
	} 
	

	/**
	 * Handles the submission of form data.
	 */
	public function submit_handler() {
		// Posted Data

		try {
			// Init fields
			$this->init_fields();

			// Get posted values
			$values = $this->get_posted_fields();
	
			if ( empty( $_POST['submit_listing'] ) ) {
				return;
			}

			// Validate required
			if ( is_wp_error( ( $return = $this->validate_fields( $values ) ) ) ) {
				throw new Exception( $return->get_error_message() );
			}


			if ( ! is_user_logged_in() ) {
				throw new Exception( __( 'You must be signed in to post a new listing.', 'truelysell_core' ) );
			}

		
			$post_title = $values['basic_info']['listing_title'];
			$post_content = $values['details']['listing_description'];
			$product_id = (isset($values['basic_info']['product_id'])) ? $values['basic_info']['product_id'] : '' ;
			
			
			$content = '';

			//locate listing_description
			foreach ($values as $section => $s_fields) {
				foreach ($s_fields as $key => $value) {
					if($key == 'listing_description') {
						$content = $value;
					}
				}
				
			}
			

			//Update the listing
			$this->save_listing( $values['basic_info']['listing_title'], $content, $this->listing_id ? '' : 'preview', $values );
			

			$this->update_listing_data( $values );

			// Successful, show next step
			$this->step++;


		} catch ( Exception $e ) {

			$this->add_error( $e->getMessage() );
			return;
		}
	}

	/**
	 * Handles the preview step form response.
	 */
	public function preview_handler() {
			
		
		if ( ! $_POST ) {
			return;
		}

		
		if ( ! is_user_logged_in() ) {
			throw new Exception( __( 'You must be signed in to post a new listing.', 'truelysell_core' ) );
		}

		// Edit = show submit form again
		if ( ! empty( $_POST['edit_listing'] ) ) {
			$this->step--;
		}

		// Continue = change listing status then show next screen
		if ( ! empty( $_POST['continue'] ) ) {

			$listing = get_post( $this->listing_id );

			if ( in_array( $listing->post_status, array( 'preview', 'expired' ) ) ) {
				// Reset expiry
				delete_post_meta( $listing->ID, '_listing_expires' );

				// Update listing listing
				$update_listing                  = array();
				$update_listing['ID']            = $listing->ID;
				if( $this->form_action == "editing" ) {
			
					$update_listing['post_status']   = apply_filters( 'edit_listing_post_status', truelysell_fl_framework_getoptions('edit_listing_requires_approval' ) ? 'pending' : $listing->post_status, $listing );

				} else {
					$update_listing['post_status']   = apply_filters( 'submit_listing_post_status', truelysell_fl_framework_getoptions('new_listing_requires_approval' ) ? 'pending' : 'publish', $listing );
				}
			
				$update_listing['post_date']     = current_time( 'mysql' );
				$update_listing['post_date_gmt'] = current_time( 'mysql', 1 );
				$update_listing['post_author']   = get_current_user_id();
				
				wp_update_post( $update_listing );
			}

			$this->step++;
		}
	}

	/**
	 * Displays the final screen after a listing listing has been submitted.
	 */
	public function done() {

		
		do_action( 'truelysell_core_listing_submitted', $this->listing_id );
		if( $this->form_action == "editing" ) {
			if(truelysell_fl_framework_getoptions('edit_listing_requires_approval' )){
				 wp_update_post(array(
			        'ID'    => $this->listing_id,
			        'post_status'   =>  'pending'
		        ));
			}
			
		}
		do_action( 'truelysell_core_listing_edited', $this->listing_id );
		$template_loader = new Truelysell_Core_Template_Loader;
		$template_loader->set_template_data( 
			array( 
				'listing' 	=>  get_post( $this->listing_id ),
				'id' 		=> 	$this->listing_id,
				) 
			)->get_template_part( 'listing-submitted' );

	}


	public function type( $atts = array() ) {

	$template_loader = new Truelysell_Core_Template_Loader;
		if ( ! is_user_logged_in() ) {
			$template_loader->get_template_part( 'listing-sign-in' );
			$template_loader->get_template_part( 'account/login' ); 
		} else {
			
			$template_loader->set_template_data( 
				array( 
					
					'form'      	=> $this->form_name,
					'action' 		=> $this->get_action(),
					'listing_id'   => $this->get_listing_id(),
					'step'      	=> $this->get_step(),
					'submit_button_text' => __( 'Submit Service', 'truelysell_core' ),
					) 
				)->get_template_part( 'listing-submit-type' );
		}
	}


	public function type_handler() {

		// Process the package unless we're doing this before a job is submitted
		
			$this->next_step();
	
	}


	public function choose_package( $atts = array() ) {
	$template_loader = new Truelysell_Core_Template_Loader;
		if ( ! is_user_logged_in() ) {
			$template_loader->get_template_part( 'listing-sign-in' );
			$template_loader->get_template_part( 'account/login' ); 
		} else {
			$packages      = self::get_packages(  );
			
			$user_packages = truelysell_core_user_packages( get_current_user_id() );
			
			$template_loader->set_template_data( 
				array( 
					'packages' 		=> $packages,
					'user_packages' => $user_packages,
					'form'      	=> $this->form_name,
					'action' 		=> $this->get_action(),
					'listing_id'   => $this->get_listing_id(),
					'step'      	=> $this->get_step(),
					'submit_button_text' => __( 'Submit Service', 'truelysell_core' ),
					) 
				)->get_template_part( 'listing-submit-package' );
		}
	}

	public function choose_package_handler() {

		// Validate Selected Package
		$validation = self::validate_package( self::$package_id, self::$is_user_package );

		// Error? Go back to choose package step.
		if ( is_wp_error( $validation ) ) {
			$this->add_error( $validation->get_error_message() );
			$this->set_step( array_search( 'package', array_keys( $this->get_steps() ) ) );
			return false;
		}

		// Store selection in cookie
		wc_setcookie( 'chosen_package_id', self::$package_id );
		wc_setcookie( 'chosen_package_is_user_package', self::$is_user_package ? 1 : 0 );

		// Process the package unless we're doing this before a job is submitted
		if ( 'process-package' === $this->get_step_key() ) {
			// Product the package
			if ( self::process_package( self::$package_id, self::$is_user_package, $this->get_listing_id() ) ) {
				$this->next_step();
			}
		} else {
			$this->next_step();
		}
	}

	/**
	 * Validate package
	 *
	 * @param  int  $package_id
	 * @param  bool $is_user_package
	 * @return bool|WP_Error
	 */
	private static function validate_package( $package_id, $is_user_package ) {
		if ( empty( $package_id ) ) {
			return new WP_Error( 'error', __( 'Invalid Package', 'truelysell_core' ) );
		} elseif ( $is_user_package ) {
			if ( ! truelysell_core_package_is_valid( get_current_user_id(), $package_id ) ) {
				return new WP_Error( 'error', __( 'Invalid Package', 'truelysell_core' ) );
			}
		} else {
			$package = wc_get_product( $package_id );

			if ( ! $package->is_type( 'listing_package' ) && ! $package->is_type( 'listing_package_subscription' ) ) {
				return new WP_Error( 'error', __( 'Invalid Package', 'truelysell_core' ) );
			}

		}
		return true;
	}


	/**
	 * Purchase a job package
	 *
	 * @param  int|string $package_id
	 * @param  bool       $is_user_package
	 * @param  int        $listing_id
	 * @return bool Did it work or not?
	 */
	private static function process_package( $package_id, $is_user_package, $listing_id ) {
		// Make sure the job has the correct status
		do_action( 'truelysell_core_listing_submitted', $listing_id );
		if ( 'preview' === get_post_status( $listing_id ) ) {
			// Update job listing
			$update_job                  = array();
			$update_job['ID']            = $listing_id;
			$update_job['post_status']   = 'pending_payment';
			$update_job['post_date']     = current_time( 'mysql' );
			$update_job['post_date_gmt'] = current_time( 'mysql', 1 );
			$update_job['post_author']   = get_current_user_id();
		
			wp_update_post( $update_job );
		}

		if ( $is_user_package ) {
			$user_package = truelysell_core_get_user_package( $package_id );
			$package      = wc_get_product( $user_package->get_product_id() );

			// Give listing the package attributes
			update_post_meta( $listing_id, '_duration', $user_package->get_duration() );
			update_post_meta( $listing_id, '_featured', $user_package->is_featured() ?  'on' : 0  );
			update_post_meta( $listing_id, '_package_id', $user_package->get_product_id() );
			update_post_meta( $listing_id, '_user_package_id', $package_id );
			

			// Approve the listing
			if ( in_array( get_post_status( $listing_id ), array( 'pending_payment', 'expired' ) ) ) {
				truelysell_core_approve_listing_with_package( $listing_id, get_current_user_id(), $package_id );
			}
			if(isset($_GET["action"]) && $_GET["action"] == 'renew' ){
				$post_types_expiry = new Truelysell_Core_Post_Types;
				$post_types_expiry->set_expiry(get_post($listing_id));
			}

			return true;
		} elseif ( $package_id ) {
			$package = wc_get_product( $package_id );

			
			$is_featured = $package->is_listing_featured();
			

			// Give job the package attributes
			update_post_meta( $listing_id, '_duration', $package->get_duration() );
			update_post_meta( $listing_id, '_featured', $is_featured ? 'on' : 0 );
			update_post_meta( $listing_id, '_package_id', $package_id );
			delete_post_meta( $listing_id, '_user_package_id' );
			if(isset($_GET["action"]) && $_GET["action"] == 'renew' ){
				update_post_meta( $listing_id, '_package_change', $package_id );
			}
			// Clear cookie
			wc_setcookie( 'chosen_package_id', '', time() - HOUR_IN_SECONDS );
			wc_setcookie( 'chosen_package_is_user_package', '', time() - HOUR_IN_SECONDS );


			// Add package to the cart
			WC()->cart->add_to_cart( $package_id, 1, '', '', array(
				'listing_id' => $listing_id,
			) );

			wc_add_to_cart_message( $package_id );


			// Redirect to checkout page
			wp_redirect( get_permalink( wc_get_page_id( 'checkout' ) ) );
			exit;
		}// End if().
	}


	/**
	 * Adds an error.
	 *
	 * @param string $error The error message.
	 */
	public function add_error( $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Gets post data for fields.
	 *
	 * @return array of data
	 */
	protected function get_posted_fields() {
		$this->init_fields();

		$values = array();

		foreach ( $this->fields as $group_key => $group_fields ) {
		
			foreach ( $group_fields['fields'] as $key => $field ) {
				// Get the value
				$field_type = str_replace( '-', '_', $field['type'] );
				
				if ( $handler = apply_filters( "truelysell_core_get_posted_{$field_type}_field", false ) ) {
					
					$values[ $group_key ][ $key ] = call_user_func( $handler, $key, $field );
				} elseif ( method_exists( $this, "get_posted_{$field_type}_field" ) ) {
					
					$values[ $group_key ][ $key ] = call_user_func( array( $this, "get_posted_{$field_type}_field" ), $key, $field );
				} else {
					
					$values[ $group_key ][ $key ] = $this->get_posted_field( $key, $field );
				}
				
				// Set fields value

				$this->fields[ $group_key ]['fields'][ $key ]['value'] = $values[ $group_key ][ $key ];
			}
		}


		return $values;
	}


	/**
	 * Gets the value of a posted field.
	 *
	 * @param  string $key
	 * @param  array  $field
	 * @return string|array
	 */
	protected function get_posted_field( $key, $field ) {
		
		return isset( $_POST[ $key ] ) ? $this->sanitize_posted_field( $_POST[ $key ] ) : '';
	}

	/**
	 * Navigates through an array and sanitizes the field.
	 *
	 * @param array|string $value The array or string to be sanitized.
	 * @return array|string $value The sanitized array (or string from the callback).
	 */
	protected function sanitize_posted_field( $value ) {
		// Santize value
		$value = is_array( $value ) ? array_map( array( $this, 'sanitize_posted_field' ), $value ) : sanitize_text_field( stripslashes( trim( $value ) ) );

		return $value;
	}

	/**
	 * Gets the value of a posted textarea field.
	 *
	 * @param  string $key
	 * @param  array  $field
	 * @return string
	 */
	protected function get_posted_textarea_field( $key, $field ) {
		return isset( $_POST[ $key ] ) ? wp_kses_post( trim( stripslashes( $_POST[ $key ] ) ) ) : '';
	}

	/**
	 * Gets the value of a posted textarea field.
	 *
	 * @param  string $key
	 * @param  array  $field
	 * @return string
	 */
	protected function get_posted_wp_editor_field( $key, $field ) {
		return $this->get_posted_textarea_field( $key, $field );
	}


	protected function get_posted_pricing_field ($key, $field ) {
		

		require_once(ABSPATH . 'wp-admin/includes/image.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/media.php');
		$file_urls       = array();
		
		$my_files_array = $_FILES[$key];
	
		foreach ($my_files_array['name'] as $x => $xvalue) {
			
			foreach ($xvalue as $y => $yvalue) {
				foreach ($yvalue as $z => $file) {
					if(!empty($file['cover'])){
			
						if(!isset($my_files_array['name'][$x][$y][$z]['cover'])) {
							continue;
						}
						$file_data = $my_files_array;
						$type              = wp_check_filetype($file_data['name'][$x][$y][$z]['cover']); // Map mime type to one WordPress recognises
						$file_to_upload = array(
							'name'     => $file_data['name'][$x][$y][$z]['cover'],
							'type'     => $type['type'],
							'tmp_name' => $file_data['tmp_name'][$x][$y][$z]['cover'],
							'error'    => $file_data['error'][$x][$y][$z]['cover'],
							'size'     => $file_data['size'][$x][$y][$z]['cover']
						);
						truelysell_write_log($file_to_upload);
						$_FILES = array('upload' => $file_to_upload);
						foreach ($_FILES as $file => $array) {
							
							$attachment_id = media_handle_upload($file, 0);
						}
						
						// These files need to be included as dependencies when on the front end.
							if (is_wp_error($attachment_id)) {
							} else {
								$file_urls[] = $attachment_id;
								$_POST[$key][$x][$y][$z]['cover'] =$attachment_id;
							}


						
					
					}


					

				}
			}
			
		}
		truelysell_write_log($_POST[$key]);
	
		return isset($_POST[$key]) ? $this->sanitize_posted_field($_POST[$key]) : '';
		//return $this->get_posted_textarea_field( $key, $field );
	}


	protected function get_posted_file_field( $key, $field ) {
		
		$file = $this->upload_file( $key, $field );
		
		

		if ( ! $file ) {
			$file = $this->get_posted_field( 'current_' . $key, $field );
		} elseif ( is_array( $file ) ) {
			$file = array_filter( array_merge( $file, (array) $this->get_posted_field( 'current_' . $key, $field ) ) );
		}

		return $file;
	}

	/**
	 * Updates or creates a listing listing from posted data.
	 *
	 * @param  string $post_title
	 * @param  string $post_content
	 * @param  string $status
	 * @param  array  $values
	 * @param  bool   $update_slug
	 */
	protected function save_listing( $post_title, $post_content, $status = 'preview', $values = array(), $update_slug = true ) {
		$listing_data = array(
			'post_title'     => $post_title,
			'post_content'   => $post_content,
			'post_type'      => 'listing',
			'comment_status' => 'open'
		);

		if ( $update_slug ) {
			$listing_slug   = array();

			$listing_slug[]            = $post_title;
			$listing_data['post_name'] = sanitize_title( implode( '-', $listing_slug ) );
		}

		if ( $status && $this->form_action != "editing") {
			$listing_data['post_status'] = $status;
		}

		$listing_data = apply_filters( 'submit_listing_form_save_listing_data', $listing_data, $post_title, $post_content, $status, $values );

		if ( $this->listing_id ) {
			$listing_data['ID'] = $this->listing_id;
			wp_update_post( $listing_data );
		} else {
			$this->listing_id = wp_insert_post( $listing_data );

			if ( ! headers_sent() ) {
				$submitting_key = uniqid();

				setcookie( 'truelysell_core-submitting-listing-id', $this->listing_id, false, COOKIEPATH, COOKIE_DOMAIN, false );
				setcookie( 'truelysell_core-submitting-listing-key', $submitting_key, false, COOKIEPATH, COOKIE_DOMAIN, false );

				update_post_meta( $this->listing_id, '_submitting_key', $submitting_key );
			}
		}
	}

	/**
	 * Sets listing meta and terms based on posted values.
	 *
	 * @param  array $values
	 */
	protected function update_listing_data( $values ) {
		// Set defaults

		$maybe_attach = array();
		// Check if not availability dates are sended and then set them as booking reservations
		if (! empty( $values['availability_calendar']['_availability'] ) ) {

			$bookings = new Truelysell_Core_Bookings_Calendar;
			
			// set array only with dates when listing is not avalible
			$dates = array_filter( explode( "|", $values['availability_calendar']['_availability']['dates'] ) );

			if ( ! empty( $dates ) ) {
			
				$bookings :: update_reservations( $this->listing_id, $dates );
			}

			// set array only with dates when we have special prices for booking
			$special_prices = json_decode( $values['availability_calendar']['_availability']['price'], true );
			
			if ( ! empty( $special_prices ) ) $bookings :: update_special_prices( $this->listing_id, $special_prices );

		}
		// Loop fields and save meta and term data
		foreach ( $this->fields as $group_key => $group_fields ) {
			foreach ( $group_fields['fields'] as $key => $field ) {

				// Save opening hours to array in post meta
				if ( $key == '_opening_hours') {
					$open_hours = $this->posted_hours_to_array( $key, $field);

					if ( $open_hours ) update_post_meta( $this->listing_id,  '_opening_hours', json_encode( $open_hours ) );
					else update_post_meta( $this->listing_id,  '_opening_hours', json_encode( false ) );
					continue;
				}

				// Save taxonomies
				if ( ! empty( $field['taxonomy'] ) ) {
					if ( is_array( $values[ $group_key ][ $key ] ) ) {
						$new_tax_array = array_map('intval', $values[ $group_key ][ $key ] );
						/*TODO - fix the damn region string*/
						wp_set_object_terms( $this->listing_id, $new_tax_array, $field['taxonomy'], false );
					} else {
						wp_set_object_terms( $this->listing_id, array( intval($values[ $group_key ][ $key ]) ), $field['taxonomy'], false );
					}

				//  logo is a featured image
				} elseif ( 'thumbnail' === $key ) {
					$attachment_id = is_numeric( $values[ $group_key ][ $key ] );
					if ( empty( $attachment_id ) ) {
						delete_post_thumbnail( $this->listing_id );
					} else {
						set_post_thumbnail( $this->listing_id, $attachment_id );
					}
					
				} else {
					
					if( isset($field['multi']) && $field['multi'] == true || $field['type'] == 'checkboxes') {
						
						delete_post_meta($this->listing_id, $key); 
						
						if ( is_array( $values[ $group_key ][ $key ] ) ) {
							foreach( $values[ $group_key ][ $key ] as $value ) {
								add_post_meta( $this->listing_id, $key, $value );
							}
						} else {
							if(!empty($values[ $group_key ][ $key ])){
								add_post_meta( $this->listing_id, $key, $values[ $group_key ][ $key ] );	
							}
							
						}
					} else {
						if( '_event_date' === $key ||  '_event_date_end' === $key){

							$meta_value_date = explode(' ', $values[ $group_key ][ $key ],2); 
							
							if($meta_value_date){
								$meta_value_stamp_obj = DateTime::createFromFormat(truelysell_date_time_wp_format_php(), $meta_value_date[0]);
								if($meta_value_stamp_obj){
									$meta_value_stamp = $meta_value_stamp_obj->getTimestamp();
									update_post_meta( $this->listing_id, $key.'_timestamp', $meta_value_stamp );		
								}
								
							}
							
						}

						
						update_post_meta( $this->listing_id, $key, $values[ $group_key ][ $key ] );
					}

					//update_post_meta( $this->listing_id, $key, $values[ $group_key ][ $key ] );	
					
					
					// Handle attachments
					
						
					if ( 'file' === $field['type'] ) {
						if ( is_array( $values[ $group_key ][ $key ] ) ) {
							foreach ( $values[ $group_key ][ $key ] as $file_url ) {
								$maybe_attach[] = $file_url;
							}
						} else {
							$maybe_attach[] = $values[ $group_key ][ $key ];
						}
					}

					$maybe_attach = array_filter( $maybe_attach );

					// Handle attachments.
					if ( count( $maybe_attach ) ) {
						// Get attachments.
						$attachments     = get_posts( 'post_parent=' . $this->listing_id . '&post_type=attachment&fields=ids&numberposts=-1' );
						$attachment_urls = [];

						// Loop attachments already attached to the job.
						foreach ( $attachments as $attachment_id ) {
							$attachment_urls[] = wp_get_attachment_url( $attachment_id );
						}

						foreach ( $maybe_attach as $attachment_url ) {
							if ( ! in_array( $attachment_url, $attachment_urls, true ) ) {
								$this->create_attachment( $attachment_url );
							}
						}
					}
	
				}
			}
		}


		// save listing type
		update_post_meta( $this->listing_id, '_listing_type', $this->listing_type );

		// And user meta to save time in future
		

		do_action( 'truelysell_core_update_listing_data', $this->listing_id, $values );
	}


	/**
	 * Displays preview of listing Listing.
	 */
	public function preview() {
		global $post, $listing_preview;
		
		if ( $this->listing_id ) {
			$listing_preview       = true;
			$post              = get_post( $this->listing_id );
			$post->post_status = 'preview';

			setup_postdata( $post );
			
			$template_loader = new Truelysell_Core_Template_Loader;
			$template_loader->set_template_data( 
			array( 
				'action' 		=> $this->get_action(),
				'fields' 		=> $this->fields,
				'form'      	=> $this->form_name,
				'post'      	=> $post,
				'listing_id'   => $this->get_listing_id(),
				'step'      	=> $this->get_step(),
				'submit_button_text' => apply_filters( 'submit_listing_form_preview_button_text', __( 'Submit', 'truelysell_core' ) )
				) 
			)->get_template_part( 'listing-preview' );

			wp_reset_postdata();
		}
	}


	protected function get_posted_hours_field( $key, $field ) {
		
		$values = array();
		if($key == '_opening_hours'){
			$days = truelysell_get_days();
			foreach ($days as $d_key => $value) {
				if ( isset( $_POST[ 'opening_hours_'.$d_key ] ) ) {
					$values['_opening_hours_'.$d_key] =  $_POST[ 'opening_hours_'.$d_key ];
				}
			}
		}
		
		return $values;
	}

	
	protected function posted_hours_to_array( $key, $field ) {
		
		$values = array();
		if($key == '_opening_hours'){

			$days = truelysell_get_days();
			$int = 0;
			$is_empty = true;

			foreach ($days as $d_key => $value) {
				if(isset($_POST[ '_' . $d_key . '_opening_hour' ])){
					$values[$int]['opening'] =  $_POST[ '_' . $d_key . '_opening_hour' ];
					$values[$int]['closing'] =  $_POST[ '_' . $d_key . '_closing_hour' ];
					$int++;

					// check if there are opened days
					if ( $_POST[ '_' . $d_key . '_opening_hour' ] != 'Closed' &&
					$_POST[ '_' . $d_key . '_closing_hour' ] != 'Closed' ) $is_empty = false;
				}
				
			}
		}
		
		// return false if all days is closed
		if ($is_empty) return false;

		return $values;

	}

	protected function get_posted_term_checkboxes_field( $key, $field ) {

		if ( isset( $_POST[ 'tax_input' ] ) && isset( $_POST[ 'tax_input' ][ $field['taxonomy'] ] ) ) {
			return array_map( 'absint', $_POST[ 'tax_input' ][ $field['taxonomy'] ] );
		} else {
			return array();
		}
	}


	function enable_paid_listings($steps){
 
		if(truelysell_fl_framework_getoptions('new_listing_requires_purchase' ) && !isset($_GET["action"]) || isset($_GET["action"]) && $_GET["action"] == 'renew' ){

		/*
		if(truelysell_fl_framework_getoptions('core_listing_submit_option', 'truelysell_core_new_listing_requires_purchase' ) && !isset($_GET["action"])){*/
			$steps['package'] = array(
					'name'     => __( 'Choose a package', 'truelysell_core' ),
					'view'     => array( $this, 'choose_package' ),
					'handler'  => array(  $this, 'choose_package_handler' ),
					'priority' => 5,
				);
			$steps['process-package'] = array(
					'name'     => '',
					'view'     => false,
					'handler'  => array( $this, 'choose_package_handler' ),
					'priority' => 25,
			);
		}
		return $steps;
	}

	/**
	 * Gets step key from outside of the class.
	 *
	 * @since 1.24.0
	 * @param string|int $step
	 * @return string
	 */
	public function get_step_key( $step = '' ) {
		if ( ! $step ) {
			$step = $this->step;
		}
		$keys = array_keys( $this->steps );
		return isset( $keys[ $step ] ) ? $keys[ $step ] : '';
	}


	/**
	 * Gets steps from outside of the class.
	 *
	 * @since 1.24.0
	 */
	public function get_steps() {
		return $this->steps;
	}

	/**
	 * Gets step from outside of the class.
	 */
	public function get_step() {
		return $this->step;
	}


	/**
	 * Decreases step from outside of the class.
	 */
	public function previous_step() {
		$this->step --;
	}

	/**
	 * Sets step from outside of the class.
	 *
	 * @since 1.24.0
	 * @param int $step
	 */
	public function set_step( $step ) {
		$this->step = absint( $step );
	}

	/**
	 * Increases step from outside of the class.
	 */
	public function next_step() {
		$this->step ++;
	}

	/**
	 * Displays errors.
	 */
	public function show_errors() {
		foreach ( $this->errors as $error ) {
			echo '<div class="alert alert-danger">' . wpautop( $error, true ) . '</div>';
		}
	}


	/**
	 * Gets the action (URL for forms to post to).
	 * As of 1.22.2 this defaults to the current page permalink.
	 *
	 * @return string
	 */
	public function get_action() {
		return esc_url_raw( $this->action ? $this->action : wp_unslash( $_SERVER['REQUEST_URI'] ) );
	}

	/**
	 * Gets the submitted listing ID.
	 *
	 * @return int
	 */
	public function get_listing_id() {
		return absint( $this->listing_id );
	}

	/**
	 * Sorts array by priority value.
	 *
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	protected function sort_by_priority( $a, $b ) {
	    if ( $a['priority'] == $b['priority'] ) {
	        return 0;
	    }
	    return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
	}

	/**
	 * Calls the view handler if set, otherwise call the next handler.
	 *
	 * @param array $atts Attributes to use in the view handler.
	 */
	public function output( $atts = array() ) {
		$step_key = $this->get_step_key( $this->step );
		$this->show_errors();

		if ( $step_key && is_callable( $this->steps[ $step_key ]['view'] ) ) {
			call_user_func( $this->steps[ $step_key ]['view'], $atts );
		}
	}

	/**
	 * Returns the form content.
	 *
	 * @param string $form_name
	 * @param array  $atts Optional passed attributes
	 * @return string|null
	 */
	public function get_form( $atts = array() ) {
		
			ob_start();
			$this->output( $atts );
			return ob_get_clean();
		
	}
	
	/**
	 * This filter insures users only see their own media
	 */
	function filter_media( $query ) {
		// admins get to see everything
		if ( ! current_user_can( 'manage_options' ) )
			$query['author'] = get_current_user_id();
		return $query;
	}

	function change_page_title( $title, $id = null ) {

	    if ( is_page( get_option( 'submit_listing_page' ) ) && in_the_loop()) {
	       if($this->form_action == "editing") {
	       	$title = esc_html__('Edit Listing', 'truelysell_core');
	       };
	    }

	    return $title;
	}


	/**
	 * Creates a file attachment.
	 *
	 * @param  string $attachment_url
	 * @return int attachment id
	 */
	protected function create_attachment( $attachment_url ) {
		include_once( ABSPATH . 'wp-admin/includes/image.php' );
		include_once( ABSPATH . 'wp-admin/includes/media.php' );

		$upload_dir     = wp_upload_dir();
		$attachment_url = str_replace( array( $upload_dir['baseurl'], WP_CONTENT_URL, site_url( '/' ) ), array( $upload_dir['basedir'], WP_CONTENT_DIR, ABSPATH ), $attachment_url );

		if ( empty( $attachment_url ) || ! is_string( $attachment_url ) ) {
			return 0;
		}

		$attachment     = array(
			'post_title'   => get_the_title( $this->listing_id ),
			'post_content' => '',
			'post_status'  => 'inherit',
			'post_parent'  => $this->listing_id,
			'guid'         => $attachment_url
		);

		if ( $info = wp_check_filetype( $attachment_url ) ) {
			$attachment['post_mime_type'] = $info['type'];
		}

		$attachment_id = wp_insert_attachment( $attachment, $attachment_url, $this->listing_id );

		if ( ! is_wp_error( $attachment_id ) ) {
			wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $attachment_url ) );
			return $attachment_id;
		}

		return 0;
	}

	/**
	 * Return packages
	 *
	 * @param array $post__in
	 * @return array
	 */
	public static function get_packages( $post__in = array() ) {
		return get_posts( array(
			'post_type'        => 'product',
			'posts_per_page'   => -1,
			'post__in'         => $post__in,
			'order'            => 'asc',
			'orderby'          => 'date',
			'suppress_filters' => false,
			'tax_query'        => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'listing_package','listing_package_subscription'),
					'operator' => 'IN',
				),
			),
		)  );
	}

	/**
	 * Change initial job status
	 *
	 * @param string  $status
	 * @param WP_Post $job
	 * @return string
	 */
	public static function submit_listing_post_status( $status, $listing ) {
		if(truelysell_fl_framework_getoptions('new_listing_requires_purchase' )){
			switch ( $listing->post_status ) {
				case 'preview' :
					return 'pending_payment';
				break;
				case 'expired' :
					return 'expired';
				break;
				default :
					return $status;
				break;
			}
		} else {
			return $status;
		}

	}

	/**
	 * Save or update current listing as WooCommerce product
    *
	* @return int $product_id number with product id associated with listing
	*
	 */
	private function save_as_product($post_title,$post_content,$product_id){

	

		// basic listing informations will be added to listing
		$product = array (
			'post_author' => get_current_user_id(),
			'post_content' => $post_content,
			'post_status' => 'publish',
			'post_title' => $post_title,
			'post_parent' => '',
			'post_type' => 'product',
		);

		// add product if not exist
		if ( ! $product_id ||  get_post_type( $product_id ) != 'product') {
			
			// insert listing as WooCommerce product
			$product_id = wp_insert_post( $product );
			wp_set_object_terms( $product_id, 'listing_booking', 'product_type' );
			
			wp_set_object_terms($product_id, 'exclude-from-catalog', 'product_visibility');
			wp_set_object_terms($product_id, 'exclude-from-search', 'product_visibility');


		} else {

			// update existing product
			$product['ID'] = $product_id;
			wp_update_post ( $product );
			wp_set_object_terms($product_id, 'exclude-from-catalog', 'product_visibility');
			wp_set_object_terms($product_id, 'exclude-from-search', 'product_visibility');

		}

		
		// set product category
		$term = get_term_by( 'name', apply_filters( 'truelysell_default_product_category', 'Truelysell booking'), 'product_cat', ARRAY_A );

		if ( ! $term ) $term = wp_insert_term(
			apply_filters( 'truelysell_default_product_category', 'Truelysell booking'),
			'product_cat',
			array(
			  'description'=> __( 'Listings category', 'truelysell-core' ),
			  'slug' => str_replace( ' ', '-', apply_filters( 'truelysell_default_product_category', 'Truelysell booking') )
			)
		  );
		  
		wp_set_object_terms( $product_id, $term['term_id'], 'product_cat');

		return $product_id;
	}	

	
	/**
	 * Handles the uploading of files.
	 *
	 * @param string $field_key
	 * @param array  $field
	 * @throws Exception When file upload failed
	 * @return  string|array
	 */
	protected function upload_file( $field_key, $field ) {
		if ( isset( $_FILES[ $field_key ] ) && ! empty( $_FILES[ $field_key ] ) && ! empty( $_FILES[ $field_key ]['name'] ) ) {
			if ( ! empty( $field['allowed_mime_types'] ) ) {
				$allowed_mime_types = $field['allowed_mime_types'];
			} else {
				$allowed_mime_types = truelysell_get_allowed_mime_types();
			}

			$file_urls       = array();
			$files_to_upload = truelysell_prepare_uploaded_files( $_FILES[ $field_key ] );

			foreach ( $files_to_upload as $file_to_upload ) {
				$uploaded_file = truelysell_upload_file( $file_to_upload, array(
					'file_key'           => $field_key,
					'allowed_mime_types' => $allowed_mime_types,
					) );

				if ( is_wp_error( $uploaded_file ) ) {
					throw new Exception( $uploaded_file->get_error_message() );
				} else {
					$file_urls[] = $uploaded_file->url;
				}
			}

			if ( ! empty( $field['multiple'] ) ) {
				return $file_urls;
			} else {
				return current( $file_urls );
			}
		}
	}

}