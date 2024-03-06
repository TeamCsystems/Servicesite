<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Truelysell_Submit_Editor {
/**
     * Stores static instance of class.
     *
     * @access protected
     * @var Truelysell_Submit The single instance of the class
     */
    protected static $_instance = null;

    protected $fields = array();
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

    public function __construct($version = '1.0.0') {
  
       	add_action( 'admin_menu', array( $this, 'add_options_page' ) ); //create tab pages
      	add_filter('submit_listing_form_fields', array( $this,'add_truelysell_submit_listing_form_fields_form_editor')); 
       
      	add_action('wp_ajax_editor_load_field', array($this, 'editor_load_field'));
      	add_action('wp_ajax_truelysell_editor_save_field', array($this, 'editor_save_field'));
      	add_action('wp_ajax_truelysell_editor_get_items', array($this, 'editor_get_items'));
      	//add_action('wp_ajax_truelysell_editor_delete_field', array($this, 'editor_delete_field'));


    }

    function add_truelysell_submit_listing_form_fields_form_editor($r){
        $fields =  get_option('truelysell_submit_form_fields');
        
        if(!empty($fields)) { $r = $fields; }
      
        return $r;
    }

    function init_fields(){
    	if ( $this->fields ) {
			return;
		}

		$scale = get_option( 'scale', 'sq ft' );
		$currency = get_option('truelysell_currency');
    	
		$this->fields = array(
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
							'class'		  => '',
							'priority'    => 1,
							'for_type'	  => ''
							
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
							'required'    => false,
							'multi'    => false,
							'for_type'	  => ''
						),
 
				),
			),
			
			'location' =>  array(
				'title' 	=> __('Location','truelysell_core'),
				//'class' 	=> 'margin-top-40',
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
						'tooltip'	  => '',
						'multi'    => false,
						'priority'    => 8,
						'render_row_col' => '6',
						'for_type'	  => ''
					),	
					 			
					'_friendly_address' => array(
						'label'       => __( 'Address', 'truelysell_core' ),
						'type'        => 'text',
						'required'    => false,
						'name'        => '_friendly_address',
						'placeholder' => '',
						'tooltip'	  => '',
						'class'		  => '',
 						'priority'    => 8,
						'render_row_col' => '6',
						'for_type'	  => ''
					),	
				 
				),
			),
			'gallery' => array(
				'title' 	=> __('Gallery','truelysell_core'),
				//'class' 	=> 'margin-top-40',
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
							
							'for_type'	  => ''
						),				
						
				),
			),
			'details' => array(
				'title' 	=> __('Details','truelysell_core'),
				//'class' 	=> 'margin-top-40',
				'icon' 		=> '',
				'fields' 	=> array(
						'listing_description' => array(
							'label'       => __( 'Description', 'truelysell_core' ),
							'name'       => 'listing_description',
							'type'        => 'wp-editor',
							'description' => '',
							'placeholder' => 'Upload images',
							'class'		  => '',
							'tooltip'	  => '',
							'priority'    => 1,
							'required'    => true,
							'render_row_col' => '12',
							'for_type'	  => ''
						),				
						'_video' => array(
							'label'       => __( 'Video', 'truelysell_core' ),
							'type'        => 'text',
							'name'        => '_video',
							'required'    => false,
							'placeholder' => '',
							'class'		  => '',
							'tooltip'	  => '',
							'priority'    => 5,
							'render_row_col' => '12',
							'for_type'	  => ''
						),

						'_phone' => array(
							'label'       => __( 'Phone', 'truelysell_core' ),
							'type'        => 'text',
							'required'    => false,
							'placeholder' => '',
							'name'        => '_phone',
							'class'		  => '',
							'priority'    => 9,
							'tooltip'	  => '',
							'render_row_col' => '6',
							'for_type'	  => ''
						),		
						 
						'_email' => array(
							'label'       => __( 'E-mail', 'truelysell_core' ),
							'type'        => 'text',
							'required'    => false,
							'placeholder' => '',
							'name'        => '_email',
							'tooltip'	  => '',
							'class'		  => '',
							'priority'    => 10,
							'render_row_col' => '6',
							'for_type'	  => ''
						),
					        
						 

				),
			),
			
			'opening_hours' => array(
				'title' 	=> __('Opening Hours','truelysell_core'),
				//'class' 	=> 'margin-top-40',
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
							'for_type'	  => ''
						),	
						'_listing_timezone' => array(
								'label'       => __( 'Listing Timezone', 'truelysell_core' ),
								'type'        => 'timezone',
								'required'    => false,
								'name'        => '_listing_timezone',
						),	
						
				),
			),
			 
			 
		 
			'booking' => array(
				'title' 	=> __('Booking','truelysell_core'),
				'class' 	=> 'margin-top-4000 booking-enable',
				'onoff'		=> true,
				//'onoff_state' => 'on',
				'icon' 		=> '',
				'fields' 	=> array(
					'_booking_status' => array(
							'label'       => __( 'Booking status', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_booking_status',
							'for_type'	  => ''
							
					),
				)
			),
 			'basic_prices' => array(
				'title'		=> __('Booking prices and settings','truelysell_core'),
				//'class'		=> 'margin-top-40',
				'icon'		=> '',
				'fields'	=> array(
  
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
						'render_row_col' => '4',
						'for_type'	  => ''
						
					),	
  			
					'_instant_booking' => array(
						'label'       => __( 'Enable Instant Booking', 'truelysell_core' ),
						'type'        => 'checkbox',
						'tooltip'	  =>'',
						'required'    => false,
						'placeholder' => '',
						'name'        => '_instant_booking',
						'class'		  => '',
						'priority'    => 10,
						'priority'    => 9,
						'render_row_col' => '4',
						'for_type'	  => ''
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
    }

     /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page() {        
         add_submenu_page( 'truelysell-fields-and-form', 'Add Listing Form', 'Add Listing Form', 'manage_options', 'truelysell-submit-builder', array( $this, 'output' )); 
    }


    public function output(){

    	$this->init_fields();

 		if ( ! empty( $_GET['reset-fields'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'reset' ) ) {
            delete_option("truelysell_submit_form_fields");
            
            echo '<div class="updated"><p>' . __( 'The fields were successfully reset.', 'truelysell' ) . '</p></div>';
        }
  


        if ( ! empty( $_POST )) { /* add nonce tu*/
          
            echo $this->form_editor_save(); 
        }
        
    	$saved_fields = get_option("truelysell_submit_form_fields");
    	
        $submit_fields = (!empty($saved_fields)) ? get_option("truelysell_submit_form_fields") : $this->fields ;
//         ini_set('xdebug.var_display_max_depth', '10');
// ini_set('xdebug.var_display_max_children', '256');
// ini_set('xdebug.var_display_max_data', '1024');
   
        ?>

 		<div class="truelysell-editor-modal">
            <div class="truelysell-editor-modal-content">
            	<form id="truelysell-core-fafe-submit">
	                <div class="truelysell-editor-modal-header">
	                		<h3 class="truelysell-editor-modal-title">Edit Field</h3>
							<a title="Close" href="#" class="truelysell-modal-close">
								<span class="dashicons dashicons-no"></span>
							</a>
	                </div>
            	
					<div class="truelysell-modal-form">
					</div>
					
					<div class="truelysell-editor-modal-footer">
						<button class="button button-large truelysell-modal-close " id="truelysell-cancel">Cancel</button>
						<button class="button button-primary button-large " id="truelysell-save-field">Save Field</button>
					</div>
				</form>
            </div>
        </div>
			
		<h2>Add Listing Form Editor</h2>
		<p id="explanation">
			You can configure fields for selected listing type (Edit field and use "For Type" option). Here you can filter fields to see how form will look for each type of listing.
		</p>
		<ul class="truelysell-editor-listing-types">
			<li><a href="#" class="show-fields-type-all active">Show all fields</a></li>
			<li><a href="#" class="show-fields-type-service">Show fields for Services</a></li>
		</ul>
		<div class="wrap truelysell-forms-builder clearfix">
                           
            <form method="post" id="mainform" action="admin.php?page=truelysell-submit-builder">

                <div class="form-editor-container" id="truelysell-fafe-forms-editor" data-section="<?php echo esc_html('<div class="truelysell-fafe-section">
	<h3>
		<input type="text" value="{section_org}" name="{section}[title]">
		<a href="#" class="truelysell-fafe-section-edit button"></a>
		<a href="#" class="truelysell-fafe-section-remove-section button"></a>
		<ul class="truelysell-fafe-section-move">
    		<li><a class="truelysell-fafe-section-move-up button" href="#"></a></li>
    		<li><a class="truelysell-fafe-section-move-down button" href="#"></a></li>
    	</ul>
	</h3>
	<div class="section_options">
		<table class="form-table">
    		<tr>
    			<td>Custom class <span class="dashicons dashicons-editor-help" title="Option custom class for this section container"></span></td>
    			<td><input type="text" value="" name="{section}[class]"></td>
    		</tr>
    		<tr>
    			<td>Make it switchable <span class="dashicons dashicons-editor-help" title="If this is enabled, the section will be \'turned off\' with the swith button in right corner"></span></td>
    			<td>
    				<input name="{section}[onoff]" class="widefat" type="checkbox" value="" >
    			</td>
    		</tr>
    		<tr>
    			<td>Enabled by default <span class="dashicons dashicons-editor-help" title="If this is enabled, the section will be \'turned off\' with the swith button in right corner"></span></td>
    			<td>
    				<input name="{section}[onoff_state]" class="widefat" type="checkbox" value="" >
    			</td>
    		</tr>
    		<tr>
    			<td>Icon class <span class="dashicons dashicons-editor-help" title="Class used to display optional icon"></span></td>
    			<td><input type="text" value="" name="{section}[icon]"> Available icons list <a href="http://www.vasterad.com/themes/truelysell_082019/pages-icons.html">truelysell.pro/icons</a></td>

    		</tr>
    	</table>
	</div>
</div>
<div data-section="{section}" class="row-container row-{section}">
<div class="block-add-new"><a href="#" data-section="{section}" class="button primary">Add new field</a></div>
</div>') ?>"> 
                    <?php
                    $index = 0;
                    foreach ( $submit_fields as $field_key => $field ) { 
                    	
                    	$section  = (!empty($field_key)) ? $field_key : 'section';

                    	//var_dump($submit_fields_temp[$section]['fields']['title']);
                    	$title = isset($field['title']) ? $field['title'] : "Title";
                    	?>
                    	<div  class="truelysell-fafe-row-section">
	                    	<div class="truelysell-fafe-section">
	                    		<h3>
	                    			<input type="text" value="<?php echo stripslashes($title);?>" name="<?php echo $section ?>[title]">
	                    			<a href="#" class="truelysell-fafe-section-edit button"></a>

	                    			<?php if($field_key != 'basic_info') { ?><a href="#" class="truelysell-fafe-section-remove-section button"></a><?php } ?>
	                    			<ul class="truelysell-fafe-section-move">
			                    		<li><a class="truelysell-fafe-section-move-up button" href="#"></a></li>
			                    		<li><a class="truelysell-fafe-section-move-down button" href="#"></a></li>
			                    	</ul>
	                    		</h3>
	                    		<div class="section_options">
	                    			<table class="form-table">
			                    		<tr>
			                    			<td>Custom class <span class="dashicons dashicons-editor-help" title="Option custom class for this section container"></span></td>
			                    			<td><input type="text" value="<?php echo isset($field['class']) ? $field['class'] : "";;?>" name="<?php echo $section ?>[class]"></td>
			                    		</tr>
			                    		<tr>
			                    			<td>Make it switchable  <span class="dashicons dashicons-editor-help" title="If this is enabled, the section will be \'turned off\' with the swith button in right corner"></span></td>
			                    			<td>
												<?php 
													$value = ( isset( $field['onoff'] ) && !empty( $field['onoff'] ) ) ? true : false;
												 ?>
			                    				<input name="<?php echo $section ?>[onoff]" <?php checked(1,$value) ?> class="widefat" type="checkbox" value="<?php echo $value; ?>" >
			                    			</td>
			                    		</tr>
			                    		<tr>
			                    			<td>Enabled by default  <span class="dashicons dashicons-editor-help" title="If previous option is enabled this will make section enabled by default"></span></td>
			                    			<td>
												<?php 
													$value = ( isset( $field['onoff_state'] ) && !empty( $field['onoff_state'] ) ) ? true : false;
												 ?>
			                    				<input name="<?php echo $section ?>[onoff_state]" <?php checked(1,$value) ?> class="widefat" type="checkbox" value="<?php echo $value; ?>" >
			                    			</td>
			                    		</tr>
			                    		<tr>
			                    			<td>Icon class <span class="dashicons dashicons-editor-help" title="Class used to display optional icon"></span></td>
			                    			<td><input type="text" value="<?php echo isset($field['icon']) ? $field['icon'] : "";;?>" name="<?php echo $section ?>[icon]"> Available icons list <a href="http://www.vasterad.com/themes/truelysell_082019/pages-icons.html">truelysell.pro/icons</a></td>
			                    		</tr>
			                    	</table>
	                    		</div>
		                    	
	                    	</div>
                    	
                    	
              
						<div data-section="<?php echo esc_attr($section); ?>" class="row-container row-<?php echo esc_attr($section); ?><?php if(isset($field['title'])) { sanitize_title($field['title']); } ?>">
	
							<?php 
							foreach ($field['fields'] as $key => $field) { 
								
								if(in_array($key,array('_monday_opening_hour','_monday_closing_hour','_tuesday_opening_hour','_tuesday_closing_hour','_wednesday_opening_hour','_wednesday_closing_hour','_thursday_opening_hour','_thursday_closing_hour','_friday_opening_hour','_friday_closing_hour','_saturday_opening_hour','_saturday_closing_hour','_sunday_opening_hour','_sunday_closing_hour'))) {
									continue;
								}
								
								if(!array_key_exists('class', $field)){
									$field['class'] = '';
										
								}

								$width = $this->get_box_width($field);
								$label = (isset($field['label'])) ? $field['label'] : 'Missing Label';
								?>

								<div class='editor-block block-width-<?php echo $width;?> block-<?php echo sanitize_title($key) ?>' >
									<h5><?php echo stripslashes($label); ?></h5>
									<div class="editor-block-tools">
										<input type="text" class="block-width-input" name="<?php echo $section; ?>[<?php echo $key; ?>][render_row_col]" value="<?php echo $width;?>">
										<input type="hidden" name="section[]" value="<?php echo $section;?>">
										<input type="hidden" name="field[]" value="<?php echo $key;?>">
										
										<ul>
											<li class="block-edit"><a data-section="<?php echo $section; ?>" data-id="<?php echo $key; ?>" href="#" class="button button-primary"></a></li>
											<li class="block-narrower"><a href="#"></a></li>
											<li class="block-wider"><a href="#"></a></li>
											<?php if(!in_array( $key,array('_geolocation_long','_geolocation_lat')) ) { ?>
												<li class="block-delete"><a href="#"></a></li>
											<?php } ?>
										</ul>

										
									</div>
									<div class="editor-block-form-fields">
										<?php 
										
//'tooltip'	  => '',
										if(!empty($section) && !empty($field)){
								    		$this->init_fields();
									    	$options = get_option("truelysell_submit_form_fields");
									    	$submit_fields = (!empty($options)) ? get_option("truelysell_submit_form_fields") : $this->fields;
									    	$form = $this->generate_form_fields($submit_fields[$section]['fields'][$key],$key,$section);
									    	echo $form;
								    	} 
								    	?>
									</div>
								</div>
								
							<?php } ?>
							<div class="block-add-new"><a href="#" data-section="<?php echo esc_attr($section); ?>" class="button primary">Add new field</a></div>
						</div>
                        </div>
                       
                    <?php }  ?>
                    <div class="droppable-helper"></div>
                    
                </div>
                <div>
                	<a href="#" class="truelysell-fafe-new-section button-secondary"><?php _e( 'Add new section', 'truelysell' ); ?></a>
                </div>
              	<div class="buttons-wrapper">
              		<input type="submit" name="save_changes" class="save-fields button-primary" value="<?php _e( 'Save Changes', 'truelysell' ); ?>" />
              		
                	<a href="<?php echo wp_nonce_url( add_query_arg( 'reset-fields', 1 ), 'reset' ); ?>" class="reset button-secondary"><?php _e( 'Reset to defaults', 'truelysell' ); ?></a>
            	</div>
            	<?php wp_nonce_field( 'save-fields' ); ?>
           		<?php wp_nonce_field( 'save'); ?>
    		</form>
		</div>
        <?php
    }

     /**
     * Save the form fields
     */
    private function form_editor_save() {


      	if(isset($_POST['save_changes'])) {
			      
	      	$options = get_option('truelysell_submit_form_fields_temp',array());

	      	$new_fields = array();

	      	$field_name      = ! empty( $_POST['field'] ) ? array_map( 'sanitize_text_field', $_POST['field'] )   : array();
	        $field_section   = ! empty( $_POST['section'] ) ? array_map( 'sanitize_text_field', $_POST['section'] )   : array();
	        $field_width 	 = ! empty( $_POST['render_row_col'] ) ? array_map( 'sanitize_text_field', $_POST['render_row_col'] )                  : array(); 
	        $field_type 	 = ! empty( $_POST['type'] ) ? array_map( 'sanitize_text_field', $_POST['type'] ) : array();
	        
			$sections = array_unique($field_section);
			
			foreach ($sections as $key => $section) {
				# code...
				if(isset($_POST[$section])) {

					$section_title = isset($_POST[$section]['title']) ? sanitize_text_field(stripslashes_deep($_POST[$section]['title'])) : '';
					
					$new_fields[$section]['title'] = $section_title;
					
					$section_class = isset($_POST[$section]['class']) ? sanitize_text_field($_POST[$section]['class']) : '';
					$new_fields[$section]['class'] = $section_class;

					$section_onoff = isset($_POST[$section]['onoff']) ? true : false;
					$new_fields[$section]['onoff'] = $section_onoff;

					$section_onoff_state = isset($_POST[$section]['onoff_state']) ? 'on' : false;
					$new_fields[$section]['onoff_state'] = $section_onoff_state;

					$section_icon = isset($_POST[$section]['icon']) ? sanitize_text_field($_POST[$section]['icon']) : '';
					$new_fields[$section]['icon'] = $section_icon;


					foreach ($_POST[$section] as $key => $value) {
						if(in_array($key, array('title','class','onoff','onoff_state','icon'))){
							continue;
						};
						array_map( 'sanitize_text_field',$value);
						
						$new_fields[$section]['fields'][$key] = $value;
						if(!isset($value['required'])){ 
							$new_fields[$section]['fields'][$key]['required'] = 0; 
						}
						if($value['type'] == "term-select" && !isset($value['multi'])){ 
							$new_fields[$section]['fields'][$key]['multi'] = 0; 
						}
						
						// var_dump($new_fields[$section]['fields'][$key]);
					}
				}
			}
		
	
		$result = update_option( "truelysell_submit_form_fields", $new_fields);

	    //     foreach ( $field_name as $key => $field ) {
	    //     	$section = $field_section[ $key ];
	    //     	$name = $field_name[ $key ];

	 			// $options[$section]['fields'][$name]['render_row_col'] = $field_width[$key];
	    //     }
	      //var_dump($options);
	    //     $result = update_option( "truelysell_submit_form_fields_temp", $option);
	        

	        // if ( true === $result ) {
	        //     echo '<div class="updated"><p>' . __( 'The fields were successfully saved.', 'wp-job-manager-applications' ) . '</p></div>';
	        // }
        }

     	if(isset($_POST['publish_changes'])) {
     		$option = get_option('truelysell_submit_form_fields',array());
			$submit_fields = (!empty($options)) ? $options : $this->fields ;
	      	
	      	$new_fields = array();


	      	// get width settings and save them
	      	$field_name      = ! empty( $_POST['field'] ) ? array_map( 'sanitize_text_field', $_POST['field'] )                     : array();
	        $field_section   = ! empty( $_POST['section'] ) ? array_map( 'sanitize_text_field', $_POST['section'] )                 : array();
	        $field_width 	 = ! empty( $_POST['render_row_col'] ) ? array_map( 'sanitize_text_field', $_POST['render_row_col'] )	: array();

	        foreach ( $field_name as $key => $field ) {
	        	$section = $field_section[ $key ];
	        	$name = $field_name[ $key ];
	        	
	 			$submit_fields[$section]['fields'][$name]['render_row_col'] = $field_width[$key];
	        }

	        // get temp option settings and save them

	      	
	        $result = update_option( "truelysell_submit_form_fields", $submit_fields);
	        

	        if ( true === $result ) {
	            echo '<div class="updated"><p>' . __( 'The fields were successfully saved.', 'wp-job-manager-applications' ) . '</p></div>';
	        }
     	}
      
    }

    public function get_box_width($field){
    	
    	if(isset($field['render_row_col'])){
    		return $field['render_row_col'];
    	} else {
    		return '12';
    	}
    }

    /**
     * Sanitize a 2d array
     * @param  array $array
     * @return array
     */
    private function sanitize_array( $input ) {
        if ( is_array( $input ) ) {
            foreach ( $input as $k => $v ) {
                $input[ $k ] = $this->sanitize_array( $v );
            }
            return $input;
        } else {
            return sanitize_text_field( $input );
        }
    }

    public function editor_load_field(){
    	
    	$ajax_out = false;

    	$field =  $_POST['field'];
    	$section =  $_POST['section'];
    	if(!empty($section) && !empty($field)){
    		$this->init_fields();
	    	$options = get_option("truelysell_submit_form_fields");
	    	$submit_fields = (!empty($options)) ? get_option("truelysell_submit_form_fields") : $this->fields;

	    	$form = $this->generate_form_fields($submit_fields[$section]['fields'][$field],$field,$section);

	    	$ajax_out = $form;
    	}
		
    	wp_send_json_success( $ajax_out );

    }

    function editor_get_items(){
    	$section = $_POST['section'];
    	$visual_fields = array(
				'listing_title' => array(
					'label'       => __( 'Service Title', 'truelysell_core' ),
					'type'        => 'text',
					'name'       => 'listing_title',
					'tooltip'	  =>  '',
					'required'    => true,
					'placeholder' => '',
					'class'		  => '',
					'priority'    => 1,
					
				),
				 
				'listing_description' => array(
					'label'       => __( 'Description', 'truelysell_core' ),
					'name'       => 'listing_description',
					'type'        => 'wp-editor',
					'description' => '',
					'placeholder' => 'Upload images',
					'class'		  => '',
					'priority'    => 1,
					'required'    => true,
				),	
				'_booking_status' => array(
							'label'       => __( 'Booking status', 'truelysell_core' ),
							'type'        => 'skipped',
							'required'    => false,
							'name'        => '_booking_status',
							'for_type'	  => ''
							
				),
				
				 
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
			
				'_listing_timezone' => array(
								'label'       => __( 'Listing Timezone', 'truelysell_core' ),
								'type'        => 'timezone',
								'required'    => false,
								'name'        => '_listing_timezone',
								'for_type'	  => ''
						),	
				 
				 	

				 
				//nowe
				'_gallery' => array(
					'label'       => __( 'Gallery', 'truelysell_core' ),
					'name'       => '_gallery',
					'type'        => 'files',
					'description' => __( 'By selecting (clicking on a photo) one of the uploaded photos you will set it as Featured Image for this service (marked by icon with star). Drag and drop thumbnails to re-order images in gallery.', 'truelysell_core' ),
					'placeholder' => 'Upload images',
					'class'		  => '',
					'priority'    => 1,
					'required'    => false,
					'for_type'	  => ''
				),					
				 
			 
				 
			 
				 
				'_normal_price' => array(
						'label'       => __( 'Regular Price', 'truelysell_core' ),
						'type'        => 'number',
						'tooltip'	  => '',
						'required'    => false,
						'default'           => '0',
						'placeholder' => '',
						'unit'		  => $currency,
						'name'        => '_normal_price',
						'class'		  => '',
						'priority'    => 10,
						'priority'    => 9,
						'render_row_col' => '4',
						'for_type'	  => ''
						
					),	
 
						
					'_instant_booking' => array(
						'label'       => __( 'Enable Instant Booking', 'truelysell_core' ),
						'type'        => 'checkbox',
						'tooltip'	  => __('With this option enabled booking request will be immediately approved ', 'truelysell_core'),
						'required'    => false,
						'placeholder' => '',
						'name'        => '_instant_booking',
						'class'		  => '',
						'priority'    => 10,
						'priority'    => 9,
						'value'    	  => 'on',
						'render_row_col' => '6',
						'for_type'	  => ''
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
					 
					 
		

			
		);


    	$price_fields = Truelysell_Core_Meta_Boxes::meta_boxes_prices();
    	$meta_fields = array(
    		Truelysell_Core_Meta_Boxes::meta_boxes_prices(),
    		Truelysell_Core_Meta_Boxes::meta_boxes_location(),
    		Truelysell_Core_Meta_Boxes::meta_boxes_contact(),
    		Truelysell_Core_Meta_Boxes::meta_boxes_event(),
    		Truelysell_Core_Meta_Boxes::meta_boxes_service(),
    		Truelysell_Core_Meta_Boxes::meta_boxes_rental(),
			Truelysell_Core_Meta_Boxes::meta_boxes_classifieds(),
    		Truelysell_Core_Meta_Boxes::meta_boxes_video(),
    		Truelysell_Core_Meta_Boxes::meta_boxes_custom(),
    		
    	);

    	foreach ($meta_fields as $key) {
    		foreach ($key['fields'] as $key => $field) { 
    			
	        	if(in_array($field['type'], array('select','select_multiple','multicheck_split','multicheck'))){ 
	        		$visual_fields[] = array(
						'label'       => $field['name'],
						'type'        => $field['type'],
						'placeholder' => $field['name'],
						'name'        => $field['id'],
						'tooltip'	  => '',
						'priority'    => 10,
						'default'	  => '',
						'render_row_col' => '4',
						'multi'    	  => false,
						'required'    => false,
						'options' => $field['options'],
						'for_type'	  => ''
					);

	        	} else {
 					$visual_fields[] = array(
						'label'       => $field['name'],
						'type'        => $field['type'],
						'placeholder' => $field['name'],
						'name'        => $field['id'],
						'tooltip'	  => '',
						'priority'    => 10,
						'default'	  => '',
						'render_row_col' => '4',
						'multi'    	  => false,
						'required'    => false,
						'for_type'	  => ''
					);
	        	}
	        	
	        }
    	}
        
    	
		$taxonomy_objects = get_object_taxonomies( 'listing', 'objects' );
        
        foreach ($taxonomy_objects as $tax) {
            $visual_fields[] = array(
				'label'       => $tax->label,
				'type'        => 'term-select',
				'placeholder' => $tax->label,
				'name'        => 'tax-'.$tax->name,
				'taxonomy'	  => $tax->name,
				'tooltip'	  => '',
				'priority'    => 10,
				'default'	  => '',
				'render_row_col' => '4',
				'multi'    	  => false,
				'required'    => false,
				'for_type'	  => ''
			);
            
        }

        $form = $this->generate_new_form_fields($visual_fields,$section);

       

        $response = array(
	        'items'	=> $form
	    );

    	wp_send_json_success(  $response );
    }

    function editor_save_field(){
    	

    	$field =  $_POST['field'];
    	$section =  $_POST['section'];
    	$fields = json_decode( wp_unslash($_POST['fields']) );
    	$field_name = '';
    	$field_section = '';
    	$option = get_option('truelysell_submit_form_fields_temp',array());

    	foreach ( $fields as $key => $value ) {
    		
    		if($value->name == 'field'){
    			$field_name = $value->value;
    		} else if( $value->name == 'section' ){
    			$field_section = $value->value;
    		} else {
    			$option[$field_section]['fields'][$field_name][$value->name] = $value->value;
    		}
    	
    	}

    	update_option('truelysell_submit_form_fields_temp',$option);
    	
    	$response = array(
	        'message' => 'Saved'
	    );

    	wp_send_json_success(  $response );
    }

    // function editor_delete_field(){

    // 	$field =  $_POST['field'];
    // 	$section =  $_POST['section'];
    // 	$fields_to_delete = get_option('truelysell_submit_form_fields_delete_temp',awrray());
    // 	$fields_to_delete[$section] = $field;
    	
    // 	update_option('truelysell_submit_form_fields_delete_temp',$fields_to_delete);

    // 	$response = array(
	   //      'message' => 'Removed'
	   //  );

    // 	wp_send_json_success(  $response );
    // }

    public function get_label_nicename($label){
    	switch ($label) {
    		case 'label':
    			$label = __('Label <span class="dashicons dashicons-editor-help" title="Text that is displayed before/next to the input field"></span>','truelysell-fafe');
    			break;

    		case 'name':
    			$label = __('Name <span class="dashicons dashicons-editor-help" title="Name attribute on the input field, do not edit if you don\'t know what you are doing :)"></span>','truelysell-fafe');
    			break;

    		case 'type':
    			$label = __('Type','truelysell-fafe');
    			break;
    		case 'required':
    			$label = __('Required <span class="dashicons dashicons-editor-help" title="Tick the checkbox to make this field required in submit form"></span>','truelysell-fafe');
    			break;
    		case 'placeholder':
    			$label = __('Placeholder <span class="dashicons dashicons-editor-help" title="Text that is displayed before/next to the input field"></span>','truelysell-fafe');
    			break;	
    		case 'taxonomy':
    			$label = __('Taxonomy','truelysell-fafe');
    			break;	
    		case 'priority':
    			$label = __('Priority ','truelysell-fafe');
    			break;
    		case 'value':
    			$label = __('Default value','truelysell-fafe');
    			break;
    		case 'default':
    			$label = __('Default value','truelysell-fafe');
    			break;	
    		case 'render_row_col':
    			$label = __('Width in form row','truelysell-fafe');
    			break;
    		case 'description':
    			$label = __('Description <span class="dashicons dashicons-editor-help" title="Additional option description for the field"></span>','truelysell-fafe');
    			break;
    		case 'tooltip':
    			$label = __('Tooltip','truelysell-fafe');
    			break;
    		case 'class':
    			$label = __('CSS class','truelysell-fafe');
    			break;
    		case 'atts':
    			$label = __('Attributes','truelysell-fafe');
    			break;
    		case 'multi':
    			$label = __('Multiple choice','truelysell-fafe');
    			break;
    		case 'options':
    			$label = __('Options','truelysell-fafe');
    			break;
    		case 'for_type':
    			$label = __('Field for type <span class="dashicons dashicons-editor-help" title="Select for which listing type this fields should be displayed"></span>','truelysell-fafe');
    			break;
    		
    		default:
    			return $label;
    			break;
    	}
    	return $label;
    }


    public function generate_form_fields($fields,$field,$section){
    	if($fields):
	    	$saved_fields = get_option("truelysell_submit_form_fields");
	        $submit_fields = (!empty($saved_fields)) ? get_option("truelysell_submit_form_fields") : $this->fields ;
	        
    		$fields = $submit_fields[$section]['fields'][$field];
    		if(!isset($fields['tooltip'])){
    			$fields['tooltip'] = '';
    		}
			if(!isset($fields['value'])){
    			$fields['value'] = '';
    		}

    		if(!isset($fields['class'])){
    			$fields['class'] = '';
    		}

    		ob_start();
    		
    		?>
			
				<table class="truelysell-modal-table form-table">
					<tbody>
					
					<?php
		
	    			foreach ($fields as $key => $value) { 

	    				
	    				if(isset($fields_temp[$key])) {
	    					$value = $fields_temp[$key];
	    				}


	    				if(in_array($key,array('render_row_col','priority'))){
	    					continue;
	    				}

	    				?>
	    				<tr valign="top" class="field-edit-<?php echo $key;?>" 
	    					<?php  if(in_array($key,array('taxonomy','name'))){ ?>style="display: none;" <?php } ?>>
	    					<th scope="row">
								<label for="field_meta_key">
								<?php echo $this->get_label_nicename($key); ?></label>
							</th>

							<td>
							<?php 
							
							switch ($key) {

								case 'type':
									?>
									<select name="<?php echo $section.'['.$field.']['.$key.']'; ?>" class="widefat" type="text" ref="config" id="field_<?php echo $key; ?>" >
										<option <?php selected('wp-editor',$value); ?> value="wp-editor">WYSIWG Editor</option>
										<option <?php selected('text',$value); ?> value="text">Text input</option>
										<option <?php selected('select',$value); ?> value="select">Select dropdown</option>
										<option <?php selected('checkboxes',$value); ?> value="checkboxes">Checkboxes list</option>
										<option <?php selected('checkbox',$value); ?> value="checkbox">Single On/Off checkbox</option>
										<option <?php selected('number',$value); ?> value="number">Number input</option>

										<option <?php selected('term-select',$value); ?> value="term-select">Select taxonomy</option>
										<option <?php selected('term-checkboxes',$value); ?> value="term-checkboxes">Taxonomy checkboxes</option>

										<option <?php selected('slots',$value); ?> value="slots">Time slots (booking)</option>
										<option <?php selected('calendar',$value); ?> value="calendar">Availability Calendar</option>
										<option <?php selected('hours',$value); ?> value="hours">Opening hours</option>

										<option <?php selected('skipped',$value); ?> value="skipped">Skipped input</option>
										<option <?php selected('pricing',$value); ?> value="pricing">Pricing (menu)</option>
										
										<option <?php selected('hidden',$value); ?> value="hidden">Hidden input</option>
										<option <?php selected('files',$value); ?> value="files">Files (Gallery)</option>
										<option <?php selected('file',$value); ?> value="file">Single File Upload</option>
										<option <?php selected('timezone',$value); ?> value="timezone">Timezone field</option>
									</select>
									
									<?php
									break;
								case 'multi': 
								case 'required': ?>
									<input name="<?php echo $section.'['.$field.']['.$key.']'; ?>" <?php checked(1,$value) ?> class="widefat" type="checkbox" id="field_<?php echo $key; ?>" value="1" >
								<?php
								break;

								case 'atts':
								case 'options':
									
									echo '<table>';
									foreach ($value as $option_key => $value) { ?>
											<tr>
												<td><?php echo stripslashes($option_key) ?></td>
												<td><input name="<?php echo $section.'['.$field.']['.$key.']['.$option_key.']'; ?>" class="widefat" type="text" ref="config" id="field_<?php echo $option_key; ?>" value="<?php echo esc_attr(stripslashes($value)); ?>" ></td>
											</tr>
									<?php }
									echo '</table>';
								break;

								case 'for_type':
									?>
									<select name="<?php echo $section.'['.$field.']['.$key.']'; ?>" class="widefat"  type="text" ref="config" id="field_<?php echo $key; ?>" >
										<option value="">All</option>
										<option <?php selected('rental',$value); ?> value="rental">Rental type</option>
										<option <?php selected('service',$value); ?>  value="service">Service type</option>
										<option <?php selected('event',$value); ?> value="event">Event type</option>
										<option <?php selected('classifieds',$value); ?> value="classifieds">Classified type</option>
										
									</select>
									<?php
								break;
								
								
								default:
									?>
									<input name="<?php echo $section.'['.$field.']['.$key.']'; ?>" class="widefat" type="text" ref="config" id="field_<?php echo $key; ?>" value="<?php echo stripslashes(esc_attr($value)); ?>" >
									<?php
									break;
							} ?>
								
								
							</td>
						</tr>
		    		<?php } ?>
		    		</tbody>
				</table>
		<?php 
	    	return ob_get_clean();
	    endif;	

    }

    public function generate_new_form_fields($fields,$section = 'new_field_section'){
    	if($fields):

    		ob_start();
	    			foreach ($fields as $field_key => $field) { ?>
						<div class="truelysell-fafe-forms-editor-new-elements-container">
							<a href="#" data-section="<?php echo $section; ?>" class="insert-field button"><?php echo $field['label']; ?></a>
	    				<div style="display:none;" class='editor-block block-width-12 block-<?php echo sanitize_title($field['name']) ?>' >
							<h5><?php echo $field['label']; ?></h5>
							<div class="editor-block-tools">
								<input type="text" class="block-width-input" name="<?php echo $section; ?>[<?php echo $field['name']; ?>][render_row_col]" value="12">
								<input type="hidden" name="section[]" value="<?php echo $section; ?>">
								<input type="hidden" name="field[]" value="<?php echo $field['name']; ?>">
								
								<ul>
									<li class="block-edit"><a data-section="<?php echo $section; ?>" data-id="<?php echo $field['name']; ?>" href="#" class="button button-primary"></a></li>
									<li class="block-narrower"><a href="#"></a></li>
									<li class="block-wider"><a href="#"> </a></li>
									<li class="block-delete"><a href="#"></a></li>
								</ul>

								
							</div>
							<div class="editor-block-form-fields">
							<table class="editor-field-preset truelysell-modal-table form-table">
								<tbody>
		    					<?php 
		    						foreach ($field as $key => $value) { ?>
		    					
				    				<tr valign="top">
				    					<th scope="row">
											<label for="field_meta_key">
											<?php echo $this->get_label_nicename($key); ?></label>
										</th>

										<td>
										<?php 
										
										switch ($key) {
											
											case 'type':
												if($value == 'datetime') {
												$value = 'text'; ?>
													<input name="<?php echo $section.'['.$field['name'].'][class]'; ?>" class="widefat" type="text" ref="config" id="field_class" value="input-datetime" >
												<?php }
												if($value == 'multicheck') { $value = 'checkboxes'; }
												if($value == 'select_multiple') { $value = 'select'; }
												?>
												<select name="<?php echo $section.'['.$field['name'].']['.$key.']'; ?>" class="widefat" type="text" ref="config" id="field_<?php echo $key; ?>" >

													<option <?php selected('wp-editor',$value); ?> value="wp-editor">WYSIWG Editor</option>
													<option <?php selected('text',$value); ?> value="text">Text input</option>
													<option <?php selected('select',$value); ?> value="select">Select dropdown</option>
													<option <?php selected('checkboxes',$value); ?> value="checkboxes">Checkboxes list</option>
													<option <?php selected('checkbox',$value); ?> value="checkbox">Single On/Off checkbox</option>
													<option <?php selected('number',$value); ?> value="number">Number input</option>

													<option <?php selected('term-select',$value); ?> value="term-select">Select taxonomy</option>
													<option <?php selected('term-checkboxes',$value); ?> value="term-checkboxes">Taxonomy checkboxes</option>

													<option <?php selected('slots',$value); ?> value="slots">Time slots (booking)</option>
													<option <?php selected('calendar',$value); ?> value="calendar">Availability Calendar</option>
													<option <?php selected('hours',$value); ?> value="hours">Opening hours</option>

													<option <?php selected('skipped',$value); ?> value="skipped">Skipped input</option>
													<option <?php selected('pricing',$value); ?> value="pricing">Pricing (menu)</option>
													
													<option <?php selected('hidden',$value); ?> value="hidden">Hidden input</option>
													<option <?php selected('files',$value); ?> value="files">Files (Gallery)</option>
													<option <?php selected('file',$value); ?> value="file">Single File Upload</option>
													
												</select>
												
												<?php
												break;
											case 'required': ?>
												<input name="<?php echo $section.'['.$field['name'].']['.$key.']'; ?>" <?php checked(1,$value) ?> class="widefat" type="checkbox" id="field_<?php echo $key; ?>" value="<?php echo $value; ?>" >
											<?php
											break;

											case 'atts':
											case 'options':
												
												echo '<table>';
												foreach ($value as $temp_key => $value) { ?>
														<tr>
															<td><?php echo $temp_key ?></td>
															<td><input name="<?php echo $section.'['.$field['name'].']['.$key.']['.$temp_key.']'; ?>" class="widefat" type="text" ref="config" id="field_<?php echo $temp_key; ?>" value="<?php echo esc_attr($value); ?>" ></td>
														</tr>
												<?php }
												echo '</table>';
											break;
											case 'for_type':
												?>
												<select name="<?php echo $section.'['.$field['name'].']['.$key.']'; ?>" class="widefat"  type="text" ref="config" id="field_<?php echo $key; ?>" >
													<option value="">All</option>
													<option <?php selected('rental',$value); ?> value="rental">Rental type</option>
													<option <?php selected('service',$value); ?>  value="service">Service type</option>
													<option <?php selected('event',$value); ?> value="event">Event type</option>
													
												</select>
												<?php
											break;
											
											default:
												?>
												<input name="<?php echo $section.'['.$field['name'].']['.$key.']'; ?>" class="widefat" type="text" ref="config" id="field_<?php echo $key; ?>" value="<?php echo (stripslashes($value)); ?>" >
												<?php
												break;
										} ?>
											
											
										</td>
									</tr>
									
									<?php
		    					} ?>
		    					</tbody>
							</table>

							</div>
						</div>
						</div>
					
	    			<?php } ?>
		    		
		<?php 
	    	return ob_get_clean();
	    endif;	

    }



}
