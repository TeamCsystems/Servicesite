<?php
/*
 * Plugin Name: Truelysell - Forms & Fields Editor
 * Version: 1.8.0
 * Plugin URI: https://dreamstechnologies.com/
 * Description: Editor for Truelysell
 * Author: Dreams Technologies
 * Text Domain: truelysell-fafe
 * Domain Path: /languages/
 * Author URI: https://dreamstechnologies.com/
 */


class Truelysell_Forms_And_Fields_Editor {


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
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;

	/**
     * Initiate our hooks
     * @since 0.1.0
     */
	public function __construct($file = '', $version = '1.0.0') {
        $this->_version = $version;
        add_action( 'admin_menu', array( $this, 'add_options_page' ) ); //create tab pages
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) ); 

        // Load plugin environment variables
        $this->file = __FILE__;
        $this->dir = dirname( $this->file );
        $this->assets_dir = trailingslashit( $this->dir ) . 'assets';
        $this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

        include( 'includes/class-truelysell-forms-builder.php' );
        include( 'includes/class-truelysell-fields-builder.php' );
        include( 'includes/class-truelysell-user-fields-builder.php' );
        include( 'includes/class-truelysell-reviews-criteria.php' );
        include( 'includes/class-truelysell-submit-builder.php' );
        include( 'includes/class-truelysell-registration-form-builder.php' );
        //include( 'includes/class-truelysell-import-export.php' );

        $this->fields  = Truelysell_Fields_Editor::instance();
        $this->submit  = Truelysell_Submit_Editor::instance();
        $this->forms  = Truelysell_Forms_Editor::instance();
        $this->reviews_criteria  = Truelysell_Reviews_Criteria::instance();
        $this->users  = Truelysell_User_Fields_Editor::instance();
        //$this->import_export  = Truelysell_Forms_Import_Export::instance();
        
        $this->registration  = Truelysell_Registration_Form_Editor::instance();
        
        add_action( 'admin_init', array( $this,'truelysell_process_settings_export' ));
        add_action( 'admin_init', array( $this,'truelysell_process_settings_import' ));
        add_action( 'admin_init', array( $this,'truelysell_process_featured_fix' ));
        add_action( 'admin_init', array( $this,'truelysell_process_events_fix' ));
        add_action( 'admin_init', array( $this,'truelysell_fix_author_dropdown' ));
        
    }


    public function enqueue_scripts_and_styles($hook){

    if ( !in_array( $hook, array('truelysell-editor_page_truelysell-submit-builder','truelysell-editor_page_truelysell-forms-builder','truelysell-editor_page_truelysell-fields-builder','truelysell-editor_page_truelysell-reviews-criteria','truelysell-editor_page_truelysell-user-fields-builder', 'truelysell-editor_page_truelysell-user-registration-builder','truelysell-editor_page_truelysell-user-fields-registration') ) ){
        return;
    }

        wp_enqueue_script('truelysell-fafe-script', esc_url( $this->assets_url ) . 'js/admin.js', array('jquery','jquery-ui-droppable','jquery-ui-draggable', 'jquery-ui-sortable', 'jquery-ui-dialog','jquery-ui-resizable'));
        
        wp_register_style( 'truelysell-fafe-styles', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
        wp_enqueue_style( 'truelysell-fafe-styles' );
        wp_enqueue_style (  'wp-jquery-ui-dialog');
    }

      /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page() {        
                 add_menu_page('Truelysell Forms and Fields Editor', 'Truelysell Editor', 'manage_options', 'truelysell-fields-and-form',array( $this, 'output' ),'dashicons-forms',80);
               
            //add_submenu_page( 'truelysell-fields-and-form', 'Property Fields', 'Property Fields', 'manage_options', 'realte-fields-builder', array( $this, 'output' ));
    }

    public function output(){ 
        if ( ! empty( $_GET['import'] ) ) {
                echo '<div class="updated"><p>' . __( 'The file was imported successfully.', 'truelysell' ) . '</p></div>';
        }?>
        <div class="metabox-holder">
            <div class="postbox">
                <h3><span><?php _e( 'Export Settings' ); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'Export fields and forms settings for this site as a .json file. This allows you to easily import the configuration into another site or make a backup.' ); ?></p>
                    <form method="post">
                        <p><input type="hidden" name="truelysell_action" value="export_settings" /></p>
                        <p>
                            <?php wp_nonce_field( 'truelysell_export_nonce', 'truelysell_export_nonce' ); ?>
                            <?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->

            <div class="postbox">
                <h3><span><?php _e( 'Import Settings' ); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.' ); ?></p>
                    <form method="post" enctype="multipart/form-data">
                        <p>
                            <input type="file" name="import_file"/>
                        </p>
                        <p>
                            <input type="hidden" name="truelysell_action" value="import_settings" />
                            <?php wp_nonce_field( 'truelysell_import_nonce', 'truelysell_import_nonce' ); ?>
                            <?php submit_button( __( 'Import' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->
            <div class="postbox">
                <h3><span><?php _e( 'Fix Featured listings ' ); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'We have changed the way featured listings information is storred since version 1.3.3. If you have updated from older version, please run the fix function by clicking button below' ); ?></p>
                        <?php $args = array(
                            'post_type' => 'listing',
                            'posts_per_page'   => -1,
                        );
                        $counter = 0;
                        $post_query = new WP_Query($args);
                        $posts_array = get_posts( $args );
                        foreach($posts_array as $post_array)
                        {
                            $featured = get_post_meta($post_array->ID, '_featured', true);
                         
                            if($featured !== 'on' && $featured !== "0"){
                                $counter++;
                               //update_post_meta($post_array->ID, '_featured', false);
                            }
                            
                        } 
                        wp_reset_query();
                        echo "There are ".$counter." listings to be fixed"; ?>
                    <form method="post" enctype="multipart/form-data">
                       
                        <p>
                            <input type="hidden" name="truelysell_action" value="fix_featured" />
                            <?php wp_nonce_field( 'fix_featured_nonce', 'fix_featured_nonce' ); ?>
                            <?php submit_button( __( 'Fix Featured' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->

            <div class="postbox">
                <h3><span><?php _e( 'Fix Event Dates ' ); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'We have changed the way search by date works to make it comaptible with Events. If you have updated from older version than 1.4.2, please run the fix function by clicking button below' ); ?></p>
                        <?php $args = array(
                            'post_type' => 'listing',
                            'posts_per_page'   => -1,
                            'meta_key' => '_listing_type',
                            'meta_value' => 'event',
                        );
                        $counter = 0;
                        $post_query = new WP_Query($args);
                        $posts_array = get_posts( $args );

                        foreach($posts_array as $post_array)
                        {
                            $_event_date = get_post_meta($post_array->ID, '_event_date_timestamp', true);
                       
                            if(!$_event_date){
                                $counter++;
                               
                            }
                            
                        } 
                        wp_reset_query();
                        echo "There are ".$counter." listings to be fixed"; ?>
                        <form method="post" enctype="multipart/form-data">
                           
                            <p>
                                <input type="hidden" name="truelysell_action" value="fix_events" />
                                <?php wp_nonce_field( 'fix_events_nonce', 'fix_events_nonce' ); ?>
                                <?php submit_button( __( 'Fix Events' ), 'secondary', 'submit', false ); ?>
                            </p>

                        </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->


             <div class="postbox">
                <h3><span><?php _e( 'Fix Users ' ); ?></span></h3>
                <div class="inside">
                  <?php _e( 'If you do not see all users available in your Author dropdown, please click the button below' ); ?></p>
                   <form method="post" enctype="multipart/form-data">
                           
                            <p>
                                <input type="hidden" name="truelysell_action" value="fix_author_dropdown" />
                                <?php wp_nonce_field( 'fix_author_dropdown_nonce', 'fix_author_dropdown_nonce' ); ?>
                                <?php submit_button( __( 'Fix Author dropdown' ), 'secondary', 'submit', false ); ?>
                            </p>

                        </form>
                </div>

            </div>
        </div><!-- .metabox-holder -->
        <?php
    }

   
    /**
         * Process a settings export that generates a .json file of the shop settings
         */
        function truelysell_process_settings_export() {

            if( empty( $_POST['truelysell_action'] ) || 'export_settings' != $_POST['truelysell_action'] )
                return;

            if( ! wp_verify_nonce( $_POST['truelysell_export_nonce'], 'truelysell_export_nonce' ) )
                return;

            if( ! current_user_can( 'manage_options' ) )
                return;

            $settings = array();
            $settings['property_types']         = get_option('truelysell_property_types_fields');
            $settings['property_rental']        = get_option('truelysell_rental_periods_fields');
            $settings['property_offer_types']   = get_option('truelysell_offer_types_fields');

            $settings['submit']                 = get_option('truelysell_submit_form_fields');
            
            $settings['price_tab']              = get_option('truelysell_price_tab_fields');
            $settings['main_details_tab']       = get_option('truelysell_main_details_tab_fields');
            $settings['details_tab']            = get_option('truelysell_details_tab_fields');
            $settings['location_tab']           = get_option('truelysell_locations_tab_fields');

            $settings['sidebar_search']         = get_option('truelysell_sidebar_search_form_fields');
            $settings['full_width_search']      = get_option('truelysell_full_width_search_form_fields');
            $settings['half_map_search']        = get_option('truelysell_search_on_half_map_form_fields');
            $settings['home_page_search']       = get_option('truelysell_search_on_home_page_form_fields');
            $settings['home_page_alt_search']   = get_option('truelysell_search_on_home_page_alt_form_fields');

            ignore_user_abort( true );

            nocache_headers();
            header( 'Content-Type: application/json; charset=utf-8' );
            header( 'Content-Disposition: attachment; filename=truelysell-settings-export-' . date( 'm-d-Y' ) . '.json' );
            header( "Expires: 0" );

            echo json_encode( $settings );
            exit;
        }

        /**
     * Process a settings import from a json file
     */
    function truelysell_process_settings_import() {

        if( empty( $_POST['truelysell_action'] ) || 'import_settings' != $_POST['truelysell_action'] )
            return;

        if( ! wp_verify_nonce( $_POST['truelysell_import_nonce'], 'truelysell_import_nonce' ) )
            return;

        if( ! current_user_can( 'manage_options' ) )
            return;

        $extension = end( explode( '.', $_FILES['import_file']['name'] ) );

        if( $extension != 'json' ) {
            wp_die( __( 'Please upload a valid .json file' ) );
        }

        $import_file = $_FILES['import_file']['tmp_name'];

        if( empty( $import_file ) ) {
            wp_die( __( 'Please upload a file to import' ) );
        }

        // Retrieve the settings from the file and convert the json object to an array.
        $settings = json_decode( file_get_contents( $import_file ), true );

        update_option('truelysell_property_types_fields'   ,$settings['property_types']);
        update_option('truelysell_rental_periods_fields'   ,$settings['property_rental']);
        update_option('truelysell_offer_types_fields'      ,$settings['property_offer_types']);

        update_option('truelysell_submit_form_fields'      ,$settings['submit']);

        update_option('truelysell_price_tab_fields'        ,$settings['price_tab']);
        update_option('truelysell_main_details_tab_fields' ,$settings['main_details_tab']);
        update_option('truelysell_details_tab_fields'      ,$settings['details_tab']);
        update_option('truelysell_locations_tab_fields'    ,$settings['location_tab']);

        update_option('truelysell_sidebar_search_form_fields',$settings['sidebar_search']);
        update_option('truelysell_full_width_search_form_fields',$settings['full_width_search']);
        update_option('truelysell_search_on_half_map_form_fields',$settings['half_map_search']);
        update_option('truelysell_search_on_home_page_form_fields',$settings['home_page_search']);
        update_option('truelysell_search_on_home_page_alt_form_fields',$settings['home_page_alt_search']);

       
        wp_safe_redirect( admin_url( 'admin.php?page=truelysell-fields-and-form&import=success' ) ); exit;

    }

    
    function truelysell_fix_author_dropdown(){
            if( empty( $_POST['truelysell_action'] ) || 'fix_author_dropdown' != $_POST['truelysell_action'] )
                return;

            if( ! current_user_can( 'manage_options' ) )
                return;

        $ownerusers = get_users(array('role__in' => array('owner', 'seller')));
            
            foreach ( $ownerusers as $user ) {
                $user->add_cap('level_1');
            }


    }
    function truelysell_process_featured_fix(){
            if( empty( $_POST['truelysell_action'] ) || 'fix_featured' != $_POST['truelysell_action'] )
                return;

            if( ! wp_verify_nonce( $_POST['fix_featured_nonce'], 'fix_featured_nonce' ) )
                return;

            if( ! current_user_can( 'manage_options' ) )
                return;

            $args = array(
            'post_type' => 'listing',
            'posts_per_page'   => -1,
        );
        $counter = 0;
        $post_query = new WP_Query($args);
        $posts_array = get_posts( $args );
        foreach($posts_array as $post_array)
        {
            $featured = get_post_meta($post_array->ID, '_featured', true);
           
            if($featured !== 'on' && $featured !== "0"){
              
               update_post_meta($post_array->ID, '_featured', '0');
            }
            
        } 
    }

    function truelysell_process_events_fix(){
            if( empty( $_POST['truelysell_action'] ) || 'fix_events' != $_POST['truelysell_action'] )
                return;

            if( ! wp_verify_nonce( $_POST['fix_events_nonce'], 'fix_events_nonce' ) )
                return;

            if( ! current_user_can( 'manage_options' ) )
                return;

            $args = array(
                'post_type' => 'listing',
                'posts_per_page'   => -1,
                'meta_key' => '_listing_type',
                'meta_value' => 'event',
            );

            $counter = 0;

            $post_query = new WP_Query($args);

            $posts_array = get_posts( $args );

            foreach($posts_array as $post_array) {

                $event_date = get_post_meta($post_array->ID, '_event_date', true);
                 
                if($event_date){
                    $meta_value_date = explode(' ', $event_date,2); 
                    if(is_array($meta_value_date)){
                   
                    $meta_value_stamp = DateTime::createFromFormat(truelysell_date_time_wp_format_php(), $meta_value_date[0])->getTimestamp();
                    update_post_meta($post_array->ID, '_event_date_timestamp', $meta_value_stamp );        
                    }
                    
                }

                $event_date_end = get_post_meta($post_array->ID, '_event_date_end', true);
                
                if($event_date_end){
                    $meta_value_date_end = explode(' ', $event_date_end, 2); 
                    if(is_array($meta_value_date_end)){
                        $meta_value_stamp_end = DateTime::createFromFormat(truelysell_date_time_wp_format_php(), $meta_value_date_end[0])->getTimestamp();
                        update_post_meta( $post_array->ID, '_event_date_end_timestamp', $meta_value_stamp_end );    
                    }
                }   
                
            } 
    }

 
}

$Truelysell_Form_Editor = new Truelysell_Forms_And_Fields_Editor();