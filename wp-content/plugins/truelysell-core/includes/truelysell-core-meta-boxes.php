<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Meta_Boxes class
 */
class Truelysell_Core_Meta_Boxes {
	/**
	 * Constructor
	 */
	public function __construct() {

		// Add custom meta boxes
		add_action( 'cmb2_admin_init', array( $this, 'add_meta_boxes' ) );
		add_filter( 'cmb2_render_truelysellmenu', array( $this,'cmb2_render_truelysellmenu_field_callback'), 10, 5 );
		add_filter( 'cmb2_sanitize_truelysellmenu', array( $this,'cmb2_sanitize_truelysellmenu_field'), 10, 5 );
		add_filter( 'cmb2_sanitize_truelysellmenu', array( $this,'cmb2_split_truelysellmenu_values'), 12, 4 );
		add_filter( 'cmb2_types_esc_truelysellmenu', array( $this,'cmb2_types_esc_truelysellmenu_field'), 10, 4 );
		
		add_action( 'cmb2_render_datetime', array( $this,'cmb2_render_callback_for_datetime'), 10, 5 );
		
		add_action( 'cmb2_render_truelysell_package', array( $this,'cmb2_render_callback_for_truelysell_package'), 10, 5 );
	


		add_filter( 'cmb2_render_opening_hours_truelysell', array( $this,'cmb2_render_opening_hours_truelysell_field_callback'), 10, 5 );

		add_action( 'listing_category_add_form_fields', array( $this,'truelysell_listing_category_add_new_meta_field'), 10, 2 );
		add_action( 'listing_category_edit_form_fields', array( $this,'truelysell_listing_category_edit_meta_field'), 10, 2 );
		
		add_action( 'edited_listing_category', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );  
		add_action( 'created_listing_category', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );
		
		///add_action( 'region_add_form_fields', array( $this,'truelysell_listing_category_add_new_meta_field'), 10, 2 );
		///add_action( 'region_edit_form_fields', array( $this,'truelysell_listing_category_edit_meta_field'), 10, 2 );
		
		add_action( 'edited_region', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );  
		add_action( 'created_region', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );


		add_action( 'listing_feature_add_form_fields', array( $this,'truelysell_listing_category_add_new_meta_field'), 10, 2 );
		add_action( 'listing_feature_edit_form_fields', array( $this,'truelysell_listing_category_edit_meta_field'), 10, 2 );
		
		add_action( 'edited_listing_feature', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );  
		add_action( 'created_listing_feature', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );


		add_action( 'event_category_add_form_fields', array( $this,'truelysell_listing_category_add_new_meta_field'), 10, 2 );
		add_action( 'event_category_edit_form_fields', array( $this,'truelysell_listing_category_edit_meta_field'), 10, 2 );
		
		add_action( 'edited_event_category', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );  
		add_action( 'created_event_category', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );
	
		//add_action( 'service_category_add_form_fields', array( $this,'truelysell_listing_category_add_new_meta_field'), 10, 2 );
		//add_action( 'service_category_edit_form_fields', array( $this,'truelysell_listing_category_edit_meta_field'), 10, 2 );
		
		//add_action( 'edited_service_category', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );  
		//add_action( 'created_service_category', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );	

		add_action( 'rental_category_add_form_fields', array( $this,'truelysell_listing_category_add_new_meta_field'), 10, 2 );
		add_action( 'rental_category_edit_form_fields', array( $this,'truelysell_listing_category_edit_meta_field'), 10, 2 );
		
		add_action( 'edited_rental_category', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );  
		add_action( 'created_rental_category', array( $this,'truelysell_save_taxonomy_custom_meta'), 10, 2 );

		add_action( 'cmb2_admin_init', array( $this,'truelysell_register_taxonomy_metabox' ) );
		add_filter( 'cmb2_sanitize_checkbox', array( $this, 'sanitize_checkbox'), 10, 2 );
	}

	function sanitize_checkbox( $override_value, $value ) {
	    // Return 0 instead of false if null value given. This hack for
	    // checkbox or checkbox-like can be setting true as default value.
	
	    return is_null( $value ) ? '0' : $value;
	}
	public function add_meta_boxes( ) {
		
		$listing_admin_options = array(
				'id'           => 'truelysell_core_listing_admin_metaboxes',
				'title'        => __( 'Listing admin data', 'truelysell_core' ),
				'object_types' => array( 'listing' ),
				'show_names'   => true,

		);
		$cmb_listing_admin = new_cmb2_box( $listing_admin_options );

		$cmb_listing_admin->add_field( array(
			'name' => __( 'Expiration date', 'truelysell_core' ),
			'desc' => '',
			'id'   => '_listing_expires',
			'type' => 'text_date_timestamp',
			
		) );
		

		// Listing type meta
		$listing_type_options = array(
				'id'           => 'listing_type',
				'title'        => __( 'Listing type', 'truelysell_core' ),
				'object_types' => array( 'listing' ),
				'show_names'   => true,
		);
  		$cmb_listing_type = new_cmb2_box( $listing_type_options );
  		$cmb_listing_type->add_field( array(
			'name' => __( 'Listing Type', 'truelysell_core' ),
			'id'   => '_listing_type',
			'type' => 'select',
			'desc' => __(
				'Determines booking options',
				'truelysell_core'
			),
			'options'   => array(
				'service' => __( 'Service', 'truelysell_core' )
			),
		));  
		
		// EOF Listing type meta

		
		$tabs_box_options = array(
				'id'           => 'truelysell_tabbed_metaboxes',
				'title'        => __( 'Listing fields', 'truelysell_core' ),
				'object_types' => array( 'listing' ),
				'show_names'   => true,
			);

		// Setup meta box
		$cmb_tabs = new_cmb2_box( $tabs_box_options );

		// setting tabs
		$tabs_setting  = array(
			'config' => $tabs_box_options,
			'layout' => 'vertical', // Default : horizontal
			'tabs'   => array()
		);
		
		$tabs_setting['tabs'] = array(
			 
			 // $this->meta_boxes_main_details(),
			  $this->meta_boxes_location(),
			  $this->meta_boxes_gallery(),
			  $this->meta_boxes_contact(),
			 // $this->meta_boxes_event(),
			  //$this->meta_boxes_service(),
			  //$this->meta_boxes_rental(),
			  //$this->meta_boxes_prices(),
			  $this->meta_boxes_classifieds(),
			  $this->meta_boxes_video(),
			  //$this->meta_boxes_custom(),
			 // $this->meta_boxes_details(),
			 
		);

		// set tabs
		$cmb_tabs->add_field( array(
			'id'   => '_tabs',
			'type' => 'tabs',
			'tabs' => $tabs_setting
		) );
  


		// Pricing 
		$cmb_menu = new_cmb2_box( array(
            'id'            => '_menu_metabox',
            'title'         => __( 'Menu (Pricing)', 'truelysell_core' ),
            'object_types' => array( 'listing' ), // post type
            'context'       => 'normal',
            'priority'      => 'core',
            'show_names'    => true,
        ) );
		$cmb_menu->add_field( array(
			'name' => __( 'Pricing Status', 'truelysell_core' ),
			'id'   => '_menu_status',
			'type' => 'checkbox',
		));
		$cmb_menu->add_field( array(
			'name' => __( 'Hide pricing table on listing page but show bookable services in booking widget', 'truelysell_core' ),
			'id'   => '_hide_pricing_if_bookable',
			'type' => 'checkbox',
		));
        // Repeatable group
	        $menu_group = $cmb_menu->add_field( array(
	            'id'          => '_menu',
	            'type'        => 'group',
	            'options'     => array(
	                'group_title'   => __( 'Menu', 'truelysell_core' ) . ' {#}', // {#} gets replaced by row number
	                'add_button'    => __( 'Add another Menu', 'truelysell_core' ),
	                'remove_button' => __( 'Remove Menu', 'truelysell_core' ),
	                'sortable'      => true, // beta
	            ),
	        ) );


  
        // EOF Pricing
  		
		// EOF Gallery


  		//  Opening hours
		$opening_hours_options = array(
				'id'           => 'truelysell_core_opening_metaboxes',
				'title'        => __( 'Opening Hours (set here in 24:00 format)', 'truelysell_core' ),
				'object_types' => array( 'listing' ),
				'show_names'   => true,

		);


		$cmb_opening = new_cmb2_box( $opening_hours_options );

		$cmb_opening->add_field( array(
			'name' => __('Time zone','truelysell_core'),
			'id'   => '_listing_timezone',
			'type' => 'select_timezone',
		) );
		$cmb_opening->add_field( array(
			'name' => __( 'Opening Hours Status', 'truelysell_core' ),
			'id'   => '_opening_hours_status',
			'type' => 'checkbox',
			'desc' => __( 'Enable to show Opening Hours widget online', 'truelysell_core' ),
		));
		


		$cmb_opening->add_field( array(
			'name' => __( 'Opening Hours', 'truelysell_core' ),
			'id'   => '_opening_hours',
			'type' => 'opening_hours',
			'desc' => 'Set Opening Hours',
		));
		$days = truelysell_get_days();
		foreach ($days as $key => $value) {
			
				$cmb_opening->add_field( array(
					'name' => $value . __( ' Opening', 'truelysell_core' ),
					'desc' => '',
					'id'   => '_'.$key.'_opening_hour',
					'type' => 'opening_hours_truelysell',
					'attributes' => array(
						'data-timepicker' => json_encode( array(
							'timeFormat' => 'HH:mm',
						) ),
					),
					'time_format' => 'H:i',
					'after_field'  => '</div><button class="button button-secondary button-large add-time-picker">'.esc_html__('Add time','truelysell_core').'</button><div>',
					'before_row'      => '<div class="opening_hours_column">',
				
				) );
				$cmb_opening->add_field( array(
					'name' => $value . __( ' Closing', 'truelysell_core' ),
					'desc' => '',
					'id'   => '_'.$key.'_closing_hour',
					'type' => 'opening_hours_truelysell',
					'attributes' => array(
						'data-timepicker' => json_encode( array(
							'timeFormat' => 'HH:mm',
						) ),
					),
					'time_format' => 'H:i',
					
					'after_row'      => '</div>',
				) );
			
				
			
		}
		//  EOF Opening hours

		// Verified 
		
		// EOF Verified


		$featured_box_options = array(
				'id'           => 'truelysell_core_featured_metabox',
				'title'        => __( 'Featured Listing', 'truelysell_core' ),
				'context'	   => 'side',
				'priority'     => 'core', 
				'object_types' => array( 'listing' ),
				'show_names'   => false,

		);

		// Setup meta box
		$cmb_featured = new_cmb2_box( $featured_box_options );

		$cmb_featured->add_field( array(
			'name' => __( 'Featured', 'truelysell_core' ),
			'id'   => '_featured',
			'type' => 'checkbox',
			'desc' => __( 'Tick the checkbox to make it Featured', 'truelysell_core' ),
		));

		$advanced_box_options = array(
				'id'           => 'truelysell_core_advanced_metabox',
				'title'        => __( 'Advanced meta data Listing', 'truelysell_core' ),
				'priority'     => 'core', 
				'object_types' => array( 'listing' ),
				'show_names'   => true,

		);

		// Setup meta box
		$cmb_advanced = new_cmb2_box( $advanced_box_options );

		$cmb_advanced->add_field( array(
			'name' => __( 'WooCommerce Product ID', 'truelysell_core' ),
			'id'   => 'product_id',
			'type' => 'text',
			'desc' => __( 'WooCommerce Product ID. Don\'t change it unless you know what you are doing:)', 'truelysell_core' ),
		));



		$booking_box_options = array(
				'id'           => 'truelysell_core_booking_metabox',
				'title'        => __( 'Booking options', 'truelysell_core' ),
				'priority'     => 'core', 
				'object_types' => array( 'listing' ),
				'show_names'   => true,

		);

		// Setup meta box
		$cmb_booking = new_cmb2_box( $booking_box_options );


		
		$cmb_booking->add_field( array(
			'name' => __( 'Booking Status', 'truelysell_core' ),
			'id'   => '_booking_status',
			'type' => 'checkbox',
		));
	
		$cmb_booking->add_field( array(
			'name' => __( 'Enable Instant Booking', 'truelysell_core' ),
			'id'   => '_instant_booking',
			'type' => 'checkbox',
		));
		$cmb_booking->add_field(array(
			'name' => __('Payment options', 'truelysell_core'),
			'id'   => '_payment_option',
			'type' => 'select',
			'desc' => __('Select which payment type you require for a booking', 'truelysell_core'),
			'options'   => array(
				'pay_now' => __('Require online payment', 'truelysell_core'),
				'pay_maybe' => __('Allow online payment', 'truelysell_core'),
				'pay_cash' => __('Require only cash payment', 'truelysell_core'),
			),
		));
		
	}

	public static function meta_boxes_location() {
		
		$fields = array(
			'id'     => 'locations_tab',
			'title'  => __( 'Location', 'truelysell_core' ),
			'fields' => array(
				array(
					'name' => __( 'Address', 'truelysell_core' ),
					'id'   => '_friendly_address',
					'type' => 'text',
					'desc' =>  __(
					'Human readable address', 'truelysell_core'),
				),			
			 
			)
		);

		// Set meta box
		return apply_filters( 'truelysell_location_fields', $fields );
	}


	

	public static function meta_boxes_event() {
		
		$fields = array(
			'id'     => 'event_tab',
			'title'  => __( 'Event fields', 'truelysell_core' ),
			'fields' => array(
			 				
				
			)
		);

		// Set meta box
		return apply_filters( 'truelysell_event_fields', $fields );
	}
	public static function meta_boxes_prices() {
		
		$fields = array(
			'id'     => 'prices_tab',
			'title'  => __( 'Prices fields', 'truelysell_core' ),
			'fields' => array(
			 				
				
			)
		);

		// Set meta box
		return apply_filters( 'truelysell_prices_fields', $fields );
	}

	public static function meta_boxes_gallery() {
		
		$fields = array(
			'id'     => 'gallery_tab',
			'title'  => __( 'Gallery', 'truelysell_core' ),
			'fields' => array(
				array(
					'name' => __( 'Gallery display layout', 'truelysell_core' ),
					'desc' => '',
					'id'   => '_gallery_style',
					'type' => 'select',
					'default' => truelysell_fl_framework_getoptions('gallery_type'),
					'options'   => array(
						'top' => __( 'Gallery on top', 'truelysell_core' ),
 		    			
					)
				),
				array(
					'name' => __( 'Listing gallery', 'truelysell_core' ),
					'desc' => '',
					'id'   => '_gallery',
					'type' => 'file_list',
					// 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
				    'query_args' => array( 'type' => 'image' ), // Only images attachment
					// Optional, override default text strings
					'text' => array(
						'add_upload_files_text' => __('Add or Upload Images', 'truelysell_core' ),
					),
				)
			)
		);

		// Set meta box
		return apply_filters( 'truelysell_gallery_fields', $fields );
	}

	public static function meta_boxes_contact() {
	
		$fields = array(
			'id'     => 'contact_tab',
			'title'  => __( 'Contact details', 'truelysell_core' ),
			'fields' => array(
				 			
				 
			)
		);

		// Set meta box
		return apply_filters( 'truelysell_contact_fields', $fields );
	}


	public static function meta_boxes_service() {

		$fields = array(
			'id'     => 'service_tab',
			'title'  => __( 'Service fields', 'truelysell_core' ),
			'fields' => array(
				
			)
		);

		// Set meta box
		return apply_filters( 'truelysell_service_fields', $fields );
	}

	public static function meta_boxes_rental() {
		$fields = array(
			'id'     => 'rental_tab',
			'title'  => __( 'Rental fields', 'truelysell_core' ),
			'fields' => array(
				 					
				 			
			)
		);


		// Set meta box
		return apply_filters( 'truelysell_rental_fields', $fields );
	}	

	public static function meta_boxes_classifieds() {
		$fields = array(
			'id'     => 'classifieds_tab',
			'title'  => __( 'Classifieds fields', 'truelysell_core' ),
			'fields' => array(
				 					
			
			)
		);


		// Set meta box
		return apply_filters( 'truelysell_classifieds_fields', $fields );
	}


	public static function meta_boxes_video() {
		
		$fields = array(
			'id'     => 'video_tab',
			'title'  => __( 'Video', 'truelysell_core' ),
			'fields' => array(
				'video' => array(
					'name' => __( 'Video', 'truelysell_core' ),
					'id'   => '_video',
					'type' => 'textarea',
					'desc'      => __( 'URL to oEmbed supported service','truelysell_core' ),
				),
			
			)
		);
		$fields = apply_filters( 'truelysell_video_fields', $fields );
		
		// Set meta box
		return $fields;
	}

	public static function meta_boxes_custom() {
		
		$fields = array(
			'id'     => 'custom_tab',
			'title'  => __( 'Custom fields', 'truelysell_core' ),
			'fields' => array(
			 
			
			)
		);
		$fields = apply_filters( 'truelysell_custom_fields', $fields );
		
		// Set meta box
		return $fields;
	}

		
	function cmb2_render_opening_hours_truelysell_field_callback( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		//var_dump($escaped_value);
		if(is_array($escaped_value)){
			foreach ($escaped_value as $key => $time) {
				echo $field_type_object->input( 
					array( 
						'type' => 'text_time', 
						
						'value' => $time,
						'name'  => $field_type_object->_name( '[]' ),
						
						'placeholder' => __('use only 24:00 hour format','truelysell_core'),
						'time_format' => 'H:i',
					) );
					echo "<br>";	
			}
		} else {
			echo $field_type_object->input( 
				array( 
					'type' => 'text', 
					
					'class' => 'input', 
					'placeholder' => __('use only 24:00 hour format', 'truelysell_core'),
					'name'  => $field_type_object->_name( '[]' ),

				) );	
		}
		
	}
			

	
	/**
	 * Render TruelysellMenu Field
	 */
	function cmb2_render_truelysellmenu_field_callback( $field, $value, $object_id, $object_type, $field_type ) {

		// make sure we specify each part of the value we need.
		$value = wp_parse_args( $value, array(
			'name' => '',
			'cover' => '',
			'description' => '',
			'price'      => '',
			'bookable'      => '',
			'bookable_options'      => '',
			'bookable_quantity'      => '',
		) );

		?>
		<div class="alignleft"><p><label for="<?php echo $field_type->_id( '_name' ); ?>'"><?php echo esc_html( $field_type->_text( 'truelysellmenu_name_text', 'Name' ) ); ?></label></p>
			<?php echo $field_type->input( array(
				'class' => '',
				'name'  => $field_type->_name( '[name]' ),
				'id'    => $field_type->_id( '_name' ),
				'value' => $value['name'],
				'desc'  => '',
			) ); ?>
		</div>
		<div class="alignleft"><p><label for="<?php echo $field_type->_id( '_cover' ); ?>'"><?php echo esc_html( $field_type->_text( 'truelysellmenu_cover_text', 'Cover (Media ID)' ) ); ?></label></p>
			<?php echo $field_type->input( array(
				'class' => '',
				'name'  => $field_type->_name( '[cover]' ),
				'id'    => $field_type->_id( '_cover' ),
				'value' => $value['cover'],
				'desc'  => '',
			) ); ?>
		</div>

		<div class="alignleft"><p><label for="<?php echo $field_type->_id( '_covericon' ); ?>'"><?php echo esc_html( $field_type->_text( 'truelysellmenu_cover_text', 'Cover (Media ID)' ) ); ?></label></p>
			<?php echo $field_type->input( array(
				'class' => '',
				'name'  => $field_type->_name( '[covericon]' ),
				'id'    => $field_type->_id( '_covericon' ),
				'value' => $value['covericon'],
				'desc'  => '',
			) ); ?>
		</div>

		
		<div class="alignleft"><p><label for="<?php echo $field_type->_id( '_price' ); ?>'"><?php echo esc_html( $field_type->_text( 'truelysellmenu_price_text', __('Price','truelysell_core') ) ); ?></label></p>
			<?php echo $field_type->input( array(
				'class' => '',
				'name'  => $field_type->_name( '[price]' ),
				'id'    => $field_type->_id( '_price' ),
				'value' => $value['price'],
				'type'  => 'text',
				'desc'  => '',
			) ); ?>
		</div>
		<div class="alignleft"><p><label for="<?php echo $field_type->_id( '_bookable' ); ?>'"><?php echo esc_html( $field_type->_text( 'truelysellmenu_bookable_text', __('Bookable','truelysell_core') ) ); ?></label></p>
			<?php echo $field_type->input( array(
				'class' => '',
				'name'  => $field_type->_name( '[bookable]' ),
				'id'    => $field_type->_id( '_bookable' ),
				'value' => 'on',
				'type'  => 'checkbox',
				'checked'  => ($value['bookable'] == 'on') ? 'checked' : false,

				'desc'  => '',
			) ); ?>
		</div>
			<div class="alignleft"><p><label for="<?php echo $field_type->_id( '_bookable_options' ); ?>'"><?php echo esc_html( $field_type->_text( 'truelysellmenu_bookable_options_text',__('Bookable Options', 'truelysell_core')) ); ?></label></p>
			<?php echo $field_type->select( array(
				'name'  => $field_type->_name( '[bookable_options]' ),
				'id'    => $field_type->_id( '_bookable_options' ),
				'value' => $value['bookable_options'],
				'desc'  => '',
				'options'          => '<option '.selected('onetime',$value['bookable_options'],false).' value="onetime">'.esc_html__('One time fee','truelysell_core').'</option>
							<option '.selected('byguest',$value['bookable_options'],false).' value="byguest">'.esc_html__('Multiply by guests','truelysell_core').'</option>
							<option '.selected('bydays',$value['bookable_options'],false).' value="bydays">'.esc_html__('Multiply by days','truelysell_core').'</option>
							<option '.selected('byguestanddays',$value['bookable_options'],false).' value="byguestanddays">'.esc_html__('Multiply by guests & days ','truelysell_core').'</option>'
				
			) ); ?>
		</div>
		<div class="alignleft"><p><label for="<?php echo $field_type->_id( '_bookable_quantity' ); ?>'"><?php echo esc_html( $field_type->_text( 'truelysellmenu_bookable_quantity_text', __('Bookable Quantity', 'truelysell_core') ) ); ?></label></p>
			<?php echo $field_type->input( array(
				'class' => '',
				'name'  => $field_type->_name( '[bookable_quantity]' ),
				'id'    => $field_type->_id( '_bookable_quantity' ),
				'value' => 'on',
				'type'  => 'checkbox',
				'checked'  => ($value['bookable_quantity'] == 'on') ? 'checked' : false,

				'desc'  => '',
			) ); ?>
		</div>
		<br class="clear">
		<div><p><label for="<?php echo $field_type->_id( '_description' ); ?>'"><?php echo esc_html( $field_type->_text( 'truelysellmenu_description_text', __('Description','truelysell_core') ) ); ?></label></p>
			<?php echo $field_type->textarea( array(
				'name'  => $field_type->_name( '[description]' ),
				'id'    => $field_type->_id( '_description' ),
				'value' => $value['description'],
				'desc'  => '',
			) ); ?>
		</div>
		<!-- bookable_options
			bookable_quantity -->

			
	
		<?php
		echo $field_type->_desc( true );

	}


	/**
	 * Optionally save the Address values into separate fields
	 */
	function cmb2_split_truelysellmenu_values( $override_value, $value, $object_id, $field_args ) {
		if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] ) {
			// Don't do the override
			return $override_value;
		}

		$_keys = array(  'name', 'description', 'price','bookable' );

		foreach ( $_keys as $key ) {
			if ( ! empty( $value[ $key ] ) ) {
				update_post_meta( $object_id, $field_args['id'] . 'listing_menu_items_'. $key, $value[ $key ] );
			}
		}

		// Tell CMB2 we already did the update
		return true;
	}
	

	/**
	 * The following snippets are required for allowing the address field
	 * to work as a repeatable field, or in a repeatable group
	 */

	function cmb2_sanitize_truelysellmenu_field( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {

		// if not repeatable, bail out.
		if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
			return $check;
		}

		foreach ( $meta_value as $key => $val ) {

			if ( '' == $val['name'] ) {
	            unset( $meta_value[$key] );
	        } else {
				// if($key == 'bookable'){
				// 	$meta_value['bookable'] = 'on';
				// } else {
					if(isset($val['booking'])){
						$val['booking'] = 'on';
					}
					//$meta_value[ $key ] = array_map( 'sanitize_text_field', $val );
					$meta_value[ $key ] = $val;
				//}
			}
			
		}

		return $meta_value;
	}

	function cmb2_types_esc_truelysellmenu_field( $check, $meta_value, $field_args, $field_object ) {
		// if not repeatable, bail out.
		if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
			return $check;
		}

		foreach ( $meta_value as $key => $val ) {
			$meta_value[ $key ] = array_map( 'esc_attr', $val );
		}

		return $meta_value;
	}


	function truelysell_register_taxonomy_metabox() {
		$prefix = 'truelysell_';
	/**
	 * Metabox to add fields to categories and tags
	 */
	$cmb_term = new_cmb2_box( array(
		'id'               => $prefix . 'edit',
		'title'            => esc_html__( 'Listing Taxonomy Meta', 'truelysell_core' ), // Doesn't output for term boxes
		'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
		'taxonomies'       => array( 'listing_category' ), // Tells CMB2 which taxonomies should have these fields
		// 'new_term_section' => true, // Will display in the "Add New Category" section
	) );

	}
	/*
	 * Custom Icon field for Job Categories taxonomy 
	 **/

	// Add term page
	function truelysell_listing_category_add_new_meta_field() {
		
		?>
 		<?php wp_enqueue_media(); ?>
		
		<div class="form-field">
			<label for="_cover"><?php esc_html_e( 'Category Cover', 'truelysell_core' ); ?></label>
			<input style="width:100px" type="text" name="_cover" id="_cover" value="">
				<input type='button' class="truelysell-custom-image-upload button-primary" value="<?php _e( 'Upload Image', 'truelysell_core' ); ?>" id="uploadimage"/><br />
			<p class="description"><?php esc_html_e( 'Similar to the single jobs you can add image to the category header. It should be 1920px wide','truelysell_core' ); ?></p>
		</div>

		<div class="form-field">
			<label for="_covericon"><?php esc_html_e( 'Category Cover Icon', 'truelysell_core' ); ?></label>
			<input style="width:100px" type="text" name="_covericon" id="_covericon" value="">
				<input type='button' class="truelysell-custom-image-upload button-primary" value="<?php _e( 'Upload Image', 'truelysell_core' ); ?>" id="uploadimage"/><br />
			<p class="description"><?php esc_html_e( 'Similar to the single jobs you can add image to the category header. It should be 1920px wide','truelysell_core' ); ?></p>
		</div>

		
			
	<?php
	}
	

	// Edit term page
	function truelysell_listing_category_edit_meta_field($term) {
		// put the term ID into a variable
		$t_id = $term->term_id;
		// retrieve the existing value(s) for this meta field. This returns an array
		?>		
		<!---<tr class="form-field">
			<th scope="row" valign="top">
				<label for="icon"><?php esc_html_e( 'Category Icon', 'truelysell_core' ); ?></label>
		</tr>--->
		<?php wp_enqueue_media(); ?>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label for="_cover"><?php esc_html_e( 'Category Cover', 'truelysell_core' ); ?></label></th>
			<td>
				<?php 
				$cover = get_term_meta( $t_id, '_cover', true );
				
				if($cover) :
					$cover_image = wp_get_attachment_image_src($cover,'medium');
					
					if ($cover_image)  {
						echo '<img src="'.$cover_image[0].'" style="width:300px;height: auto;"/><br>';
					} 
				endif;
				?>
				<input style="width:100px" type="text" name="_cover" id="_cover" value="<?php echo $cover; ?>">
				<input type='button' class="truelysell-custom-image-upload button-primary" value="<?php _e( 'Upload Image', 'truelysell_core' ); ?>" id="uploadimage"/><br />
			</td>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top"><label for="_covericon"><?php esc_html_e( 'Category Cover Icon', 'truelysell_core' ); ?></label></th>
			<td>
				<?php 
				$covericon = get_term_meta( $t_id, '_covericon', true );
				
				if($covericon) :
					$covericon_image = wp_get_attachment_image_src($covericon,'medium');
					
					if ($covericon_image)  {
						echo '<img src="'.$covericon_image[0].'" style="width:300px;height: auto;"/><br>';
					} 
				endif;
				?>
				<input style="width:100px" type="text" name="_covericon" id="_covericon" value="<?php echo $covericon; ?>">
				<input type='button' class="truelysell-custom-image-upload button-primary" value="<?php _e( 'Upload Image', 'truelysell_core' ); ?>" id="uploadimage"/><br />
			</td>
		</tr>

	<?php
	}


	// Save extra taxonomy fields callback function.
	function truelysell_save_taxonomy_custom_meta( $term_id, $tt_id ) {


		if( isset( $_POST['icon'] ) && '' !== $_POST['icon'] ){
	        $icon = $_POST['icon'];

	        update_term_meta( $term_id, 'icon', $icon );
	    }

	    if( isset( $_POST['_cover'] ) && '' !== $_POST['_cover'] ){
	        $cover = sanitize_title( $_POST['_cover'] );
	        update_term_meta( $term_id, '_cover', $cover );
	    } 

		if( isset( $_POST['_covericon'] ) && '' !== $_POST['_covericon'] ){
	        $cover = sanitize_title( $_POST['_covericon'] );
	        update_term_meta( $term_id, '_covericon', $cover );
	    } 

	    if( isset( $_POST['_icon_svg'] ) ){
	        $_icon_svg = sanitize_title( $_POST['_icon_svg'] );
	        update_term_meta( $term_id, '_icon_svg', $_icon_svg );
	    }
		
	}  

	function cmb2_render_callback_for_datetime( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		echo $field_type_object->input( array( 'type' => 'text', 'class' => 'input-datetime' ) );
	}

	function cmb2_render_callback_for_truelysell_package($field, $escaped_value, $object_id, $object_type, $field_type_object ){
		    
		    $post_id = get_the_ID();
		    $post_author_id = get_post_field( 'post_author', $post_id );
		    
			if ( version_compare( CMB2_VERSION, '2.2.2', '>=' ) ) {
				$field_type_object->type = new CMB2_Type_Select( $field_type_object );
			}
			echo $field_type_object->select( 
				array(
				'class'     => 'pw_select2 pw_select',
			    'options'   => truelysell_core_available_packages($post_author_id,$escaped_value),
			) );

		 
	}


	function changed_user_package($meta_id, $post_id, $meta_key, $meta_value ) {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) { 
			// this goes when it's the first pass by the new block editor, does NOT occur when Classic Editor is activated
  			// $_POST is not available here
		} else {
			if($meta_key== '_user_package_id'){

				$post_author_id = get_post_field( 'post_author', $post_id );

				
			}
		}
		
 
    	return; 
	}

	function check_user_package( $null, $post_id, $meta_key, $meta_value, $prev_value ){
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) { 
		} 

		else {
			if(is_admin() && $meta_key== '_user_package_id'){
				
					$decrease = get_post_meta($post_id,'_user_package_decrease',true);
					$post_author_id = get_post_field( 'post_author', $post_id );
					$prev_package = get_post_meta($post_id,'_user_package_id',true);
					$new_package = $meta_value;

				
					truelysell_core_increase_package_count($post_author_id, $new_package);
					if($decrease == 'on') {
						truelysell_core_decrease_package_count($post_author_id, $prev_package);
					}
					}
			}
	}


	public static function meta_boxes_user_owner(){

		$fields = array(
				'phone' => array(
					'id'                => 'phone',
					'class'                => 'form-control',
					'name'              => __( 'Phone', 'truelysell_core' ),
					'label'             => __( 'Phone Number', 'truelysell_core' ),
					'type'              => 'text',
					'maxlength'        => 10
					
				),
				
			);
		$fields = apply_filters( 'truelysell_user_owner_fields', $fields );
		
		// Set meta box
		return $fields;
	}

	public static function meta_boxes_user_guest(){

		$fields = array(
				'phone' => array(
					'id'                => 'phone',
					'name'              => __( 'Phone', 'truelysell_core' ),
					'label'             => __( 'Phone Number', 'truelysell_core' ),
					'type'              => 'text',
					'class'              => 'form-control'
					
				),
				
			);
		$fields = apply_filters( 'truelysell_user_guest_fields', $fields );
		
		// Set meta box
		return $fields;
	}

	



}