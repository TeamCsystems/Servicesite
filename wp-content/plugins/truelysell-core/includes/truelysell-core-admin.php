<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Truelysell_Core_Admin {

    /**
     * The single instance of WordPress_Plugin_Template_Settings.
     * @var     object
     * @access  private
     * @since   1.0.0
     */
    private static $_instance = null;

    /**
     * The main plugin object.
     * @var     object
     * @access  public
     * @since   1.0.0
     */
    public $parent = null;


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
     * Prefix for plugin settings.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $base = '';

    /**
     * Available settings for plugin.
     * @var     array
     * @access  public
     * @since   1.0.0
     */
    public $settings = array();

    public function __construct ( $parent ) {

        $this->parent = $parent;
        $this->_token = 'truelysell';

        
        $this->dir = dirname( $this->file );
        $this->assets_dir = trailingslashit( $this->dir ) . 'assets';
        $this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

        $this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';



        $this->base = 'truelysell_';

        // Initialise settings
        add_action( 'init', array( $this, 'init_settings' ), 11 );

        // Register plugin settings
        add_action( 'admin_init' , array( $this, 'register_settings' ) );

        // Add settings page to menu
        add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

        add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 1 );

        // Add settings link to plugins page
        add_action( 'current_screen', array( $this, 'conditional_includes' ) );
    }

    /**
     * Initialise settings
     * @return void
     */
    public function init_settings () {
        $this->settings = $this->settings_fields();

    }


    /**
     * Include admin files conditionally.
     */
    public function conditional_includes() {
        $screen = get_current_screen();
        if ( ! $screen ) {
            return;
        }
        switch ( $screen->id ) {
            case 'options-permalink':
                include 'truelysell-core-permalinks.php';
                break;
        }
    }


    /**
     * Add settings page to admin menu
     * @return void
     */
    public function add_menu_item () {
        $page = add_menu_page( __( 'Truelysell Core ', 'truelysell_core' ) , __( 'Truelysell Core', 'truelysell_core' ) , 'manage_options' , $this->_token . '_settings' ,  array( $this, 'settings_page' ) );
        add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );

    
        
        add_submenu_page($this->_token . '_settings', 'Submit Service', 'Submit Service', 'manage_options', 'truelysell_settings&tab=submit_listing',  array( $this, 'settings_page' ) ); 
        
        add_submenu_page($this->_token . '_settings', 'Single Service', 'Single Service', 'manage_options', 'truelysell_settings&tab=single',  array( $this, 'settings_page' ) );   
         
        add_submenu_page($this->_token . '_settings', 'Packages Options', 'Packages Options', 'manage_options', 'truelysell_settings&tab=listing_packages',  array($this, 'settings_page'));

        add_submenu_page($this->_token . '_settings', 'Booking Settings', 'Booking Settings', 'manage_options', 'truelysell_settings&tab=booking',  array( $this, 'settings_page' ) );   
        
        add_submenu_page($this->_token . '_settings', 'Browse/Search Options', 'Browse/Search Options', 'manage_options', 'truelysell_settings&tab=browse',  array( $this, 'settings_page' ) );   
        
        add_submenu_page($this->_token . '_settings', 'Registration', 'Registration', 'manage_options', 'truelysell_settings&tab=registration',  array( $this, 'settings_page' ) );   
        
        add_submenu_page($this->_token . '_settings', 'Pages', 'Pages', 'manage_options', 'truelysell_settings&tab=pages',  array( $this, 'settings_page' ) ); 
        
        add_submenu_page($this->_token . '_settings', 'Emails', 'Emails', 'manage_options', 'truelysell_settings&tab=emails',  array( $this, 'settings_page' ) ); 

        add_submenu_page($this->_token . '_settings', 'PayPal Payout', 'PayPal Payout', 'manage_options', 'truelysell_settings&tab=paypal_payout',  array( $this, 'settings_page' ) );
    }

    /**
     * Load settings JS & CSS
     * @return void
     */
    public function settings_assets () {

        // We're including the farbtastic script & styles here because they're needed for the colour picker
        // If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below
        wp_enqueue_style( 'farbtastic' );
        wp_enqueue_script( 'farbtastic' );

        // We're including the WP media scripts here because they're needed for the image upload field
        // If you're not including an image upload then you can leave this function call out
        wp_enqueue_media();
        

    }


    /**
     * Build settings fields
     * @return array Fields to be displayed on settings page
     */
    private function settings_fields () {

        
        $settings['general'] = array(
            'title'                 => __( '<i class="fa fa-gear"></i> General', 'truelysell_core' ),
            'fields'                => array(
               
             
                
                array(
                    'label'      => __('Clock format', 'truelysell_core'),
                    'description'      => __('Set  clock format  for timepickers', 'truelysell_core'),
                    'id'        => 'clock_format',
                    'type'      => 'radio',
                    'options'   => array( 
                            '12' => '12H' 
                        ),
                    'default'   => '12'
                ),
               
                array(
                    'label'      => __('Date format separator', 'truelysell_core'),
                    'description'      => __('Choose hyphen (-), slash (/), or dot (.)', 'truelysell_core'),
                    'id'        => 'date_format_separator',
                    'type'      => 'text',
                    'default'   => '/'
                ),
              
                
                array(
                    'label'      => __('Commission rate', 'truelysell_core'),
                    'description'      => __('Set commision % for bookings', 'truelysell_core'),
                    'id'        => 'commission_rate',
                    'type'      => 'number',
                    'placeholder'      => 'Put just a number',
                    'default'   => '10'
                ),
                
           
            
                array(
                    'label'      => __('Currency', 'truelysell_core'),
                    'description'      => __('Choose a currency used.', 'truelysell_core'),
                    'id'        => 'currency', //each field id must be unique
                    'type'      => 'select',
                    'options'   => array(
                            'none' => esc_html__( 'Disable Currency Symbol', 'truelysell_core' ),
                            'USD' => esc_html__( 'US Dollars', 'truelysell_core' ),
                            'AED' => esc_html__( 'United Arab Emirates Dirham', 'truelysell_core' ),
                            'ARS' => esc_html__( 'Argentine Peso', 'truelysell_core' ),
                            'AUD' => esc_html__( 'Australian Dollars', 'truelysell_core' ),
                            'BDT' => esc_html__( 'Bangladeshi Taka', 'truelysell_core' ),
                            'BHD' => esc_html__( 'Bahraini Dinar', 'truelysell_core' ),
                            'BRL' => esc_html__( 'Brazilian Real', 'truelysell_core' ),
                            'BGN' => esc_html__( 'Bulgarian Lev', 'truelysell_core' ),
                            'CAD' => esc_html__( 'Canadian Dollars', 'truelysell_core' ),
                            'CLP' => esc_html__( 'Chilean Peso', 'truelysell_core' ),
                            'CNY' => esc_html__( 'Chinese Yuan', 'truelysell_core' ),
                            'COP' => esc_html__( 'Colombian Peso', 'truelysell_core' ),
                            'CZK' => esc_html__( 'Czech Koruna', 'truelysell_core' ),
                            'DKK' => esc_html__( 'Danish Krone', 'truelysell_core' ),
                            'DOP' => esc_html__( 'Dominican Peso', 'truelysell_core' ),
                            'MAD' => esc_html__( 'Moroccan Dirham', 'truelysell_core' ),
                            'EUR' => esc_html__( 'Euros', 'truelysell_core' ),
                            'GHS' => esc_html__( 'Ghanaian Cedi', 'truelysell_core' ),
                            'HKD' => esc_html__( 'Hong Kong Dollar', 'truelysell_core' ),
                            'HRK' => esc_html__( 'Croatia kuna', 'truelysell_core' ),
                            'HUF' => esc_html__( 'Hungarian Forint', 'truelysell_core' ),
                            'ISK' => esc_html__( 'Icelandic krona', 'truelysell_core' ),
                            'IDR' => esc_html__( 'Indonesia Rupiah', 'truelysell_core' ),
                            'INR' => esc_html__( 'Indian Rupee', 'truelysell_core' ),
                            'NPR' => esc_html__( 'Nepali Rupee', 'truelysell_core' ),
                            'ILS' => esc_html__( 'Israeli Shekel', 'truelysell_core' ),
                            'JPY' => esc_html__( 'Japanese Yen', 'truelysell_core' ),
                            'JOD' => esc_html__( 'Jordanian Dinar', 'truelysell_core' ),
                            'KZT' => esc_html__( 'Kazakhstani tenge', 'truelysell_core' ),
                            'KIP' => esc_html__( 'Lao Kip', 'truelysell_core' ),
                            'KRW' => esc_html__( 'South Korean Won', 'truelysell_core' ),
                            'LKR' => esc_html__( 'Sri Lankan Rupee', 'truelysell_core' ),
                            'MYR' => esc_html__( 'Malaysian Ringgits', 'truelysell_core' ),
                            'MXN' => esc_html__( 'Mexican Peso', 'truelysell_core' ),
                            'NGN' => esc_html__( 'Nigerian Naira', 'truelysell_core' ),
                            'NOK' => esc_html__( 'Norwegian Krone', 'truelysell_core' ),
                            'NZD' => esc_html__( 'New Zealand Dollar', 'truelysell_core' ),
                            'PYG' => esc_html__( 'Paraguayan Guaraní', 'truelysell_core' ),
                            'PHP' => esc_html__( 'Philippine Pesos', 'truelysell_core' ),
                            'PLN' => esc_html__( 'Polish Zloty', 'truelysell_core' ),
                            'GBP' => esc_html__( 'Pounds Sterling', 'truelysell_core' ),
                            'RON' => esc_html__( 'Romanian Leu', 'truelysell_core' ),
                            'RUB' => esc_html__( 'Russian Ruble', 'truelysell_core' ),
                            'SGD' => esc_html__( 'Singapore Dollar', 'truelysell_core' ),
                            'SRD' => esc_html__( 'Suriname Dollar', 'truelysell_core' ),
                            'ZAR' => esc_html__( 'South African rand', 'truelysell_core' ),
                            'SEK' => esc_html__( 'Swedish Krona', 'truelysell_core' ),
                            'CHF' => esc_html__( 'Swiss Franc', 'truelysell_core' ),
                            'TWD' => esc_html__( 'Taiwan New Dollars', 'truelysell_core' ),
                            'THB' => esc_html__( 'Thai Baht', 'truelysell_core' ),
                            'TRY' => esc_html__( 'Turkish Lira', 'truelysell_core' ),
                            'UAH' => esc_html__( 'Ukrainian Hryvnia', 'truelysell_core' ),
                            'USD' => esc_html__( 'US Dollars', 'truelysell_core' ),
                            'VND' => esc_html__( 'Vietnamese Dong', 'truelysell_core' ),
                            'EGP' => esc_html__( 'Egyptian Pound', 'truelysell_core' ),
                            'ZMK' => esc_html__( 'Zambian Kwacha', 'truelysell_core' )
                        ),
                    'default'       => 'USD'
                ), 

                array(
                    'label'      => __('Currency position', 'truelysell_core'),
                    'description'      => __('Set currency symbol before or after', 'truelysell_core'),
                    'id'        => 'currency_postion',
                    'type'      => 'radio',
                    'options'   => array( 
                            'after' => 'After', 
                            'before' => 'Before' 
                        ),
                    'default'   => 'after'
                ),

                array(
                    'label'      => __('Decimal places for prices', 'truelysell_core'),
                    'description'      => __('Set Precision of the number of decimal places (for example 4.56$ instead of 5$)', 'truelysell_core'),
                    'id'        => 'number_decimals',
                    'type'      => 'number',
                    'placeholder'      => 'Put just a number',
                    'default'   => '2'
                ),
               
                array(
                    'label'      => __('By default sort listings by:', 'truelysell_core'),
                    'description'      => __('sort by', 'truelysell_core'),
                    'id'        => 'sort_by',
                    'type'      => 'select',
                    'options'   => array( 
                            'date-asc' => esc_html__( 'Oldest Services', 'truelysell_core' ),
                            'date-desc' => esc_html__( 'Newest Services', 'truelysell_core' ),
                            'featured' => esc_html__( 'Featured', 'truelysell_core' ),
                            'highest-rated' => esc_html__( 'Highest Rated', 'truelysell_core' ),
                            'reviewed' => esc_html__( 'Most Reviewed Rated', 'truelysell_core' ),
                            'title' => esc_html__( 'Alphabetically', 'truelysell_core' ),
                            'views' => esc_html__( 'Views', 'truelysell_core' ),
                            'rand' => esc_html__( 'Random', 'truelysell_core' ),
                            'rand' => esc_html__( 'Random', 'truelysell_core' ),
                        ),
                    'default'   => 'date-desc'
                ),
              

                array(
                    'label'      => __('Provider contact information visibility', 'truelysell_core'),
                    'description'      => __('By enabling this option phone and emails fields will be visible only for:', 'truelysell_core'),
                    'id'        => 'user_contact_details_visibility',
                    'type'      => 'select',
                    'options'   => array( 
                            'show_all' => esc_html__( 'Always show', 'truelysell_core' ),
                          
                        ),
                    'default'   => 'show_all'
                ),  
                
               
            )
        ); 



        $settings['header'] = array(
            'title'                 => __( '<i class="fa fa-header"></i> Header', 'truelysell_core' ),
            'fields'                => array(
                 
                array(
                    'label'      => __('Logo ', 'truelysell_core'),
                    'description'      => __('Site logo here', 'truelysell_core'),
                    'id'        => 'logo_image',
                    'type'      => 'image',
                     'default'   => ''
                ),
                   
                array(
                    'label'      => __('Mobile logo ', 'truelysell_core'),
                    'description'      => __('Site mobile logo here', 'truelysell_core'),
                    'id'        => 'logo_image_mobile',
                    'type'      => 'image',
                     'default'   => ''
                ),

                array(
                    'label'      => __('Header Style:', 'truelysell_core'),
                    'description'      => __('Change header style', 'truelysell_core'),
                    'id'        => 'header_style',
                    'type'      => 'select',
                    'options'   => array( 
                            'style1' => esc_html__( 'Style 1', 'truelysell_core' ),
                            'style2' => esc_html__( 'Style 2', 'truelysell_core' ),
                         ),
                    'default'   => 'style1'
                ),

                array(
                    'label'      => __('Email', 'truelysell_core'),
                    'description'      => __('Header style 2 only', 'truelysell_core'),
                    'id'        => 'header_email',
                    'type'      => 'text',
                    'placeholder' => 'truelysell@example.com',
                    'default'   => 'truelysell@example.com'
                ),

                array(
                    'label'      => __('Phone', 'truelysell_core'),
                    'description'      => __('Header style 2 only', 'truelysell_core'),
                    'id'        => 'header_mobile',
                    'type'      => 'text',
                    'placeholder' => '(888) 888-8888',
                    'default'   => '(888) 888-8888'
                ),

                array(
                    'label'      => __('Address', 'truelysell_core'),
                    'description'      => __('Header style 2 only', 'truelysell_core'),
                    'id'        => 'header_address',
                    'type'      => 'text',
                    'placeholder' => '367 Hillcrest Lane, Irvine, California, United States',
                    'default'   => '367 Hillcrest Lane, Irvine, California, United States'
                ),
                
 
               
            )
        ); 


        $settings['footer'] = array(
            'title'                 => __( '<i class="fa fa-sliders-h"></i> Footer', 'truelysell_core' ),
            'fields'                => array(
                 
                

                array(
                    'label'      => __('Footer Style:', 'truelysell_core'),
                    'description'      => __('Change footer style', 'truelysell_core'),
                    'id'        => 'footer_style',
                    'type'      => 'select',
                    'options'   => array( 
                            'style1' => esc_html__( 'Style 1', 'truelysell_core' ),
                            'style2' => esc_html__( 'Style 2', 'truelysell_core' ),
                         ),
                    'default'   => 'style1'
                ),
                array(
                    'label'      => __('Footer copyright', 'truelysell_core'),
                    'description'      => __('Copyright Text Here', 'truelysell_core'),
                    'id'        => 'copy_right',
                    'type'      => 'text',
                    'placeholder' => 'Copyright Text Here',
                    'default'   => '© 2023 Truelysell. All rights reserved.'
                ),
                
               
            )
        ); 


        
        $settings['submit_listing'] = array(
            'title'                 => __( '<i class="fa fa-plus-square"></i> Submit Service', 'truelysell_core' ),
            'fields'                => array(
                 /// array(
                   /// 'id'            => 'listing_types',
                   /// 'label'         => __( 'Supported service types', 'truelysell_core' ),
                   /// 'description'   => __( 'If you select one it will be the default type and Choose Service Type step in Submit Service form will be skipped. If you deselect all the default type will always be Service', 'truelysell_core' ),
                   /// 'type'          => 'checkbox_multi',
                   /// 'options'       => array( 
                    ///    'listing' => esc_html__('Service', 'truelysell_core' )
                        
                  ///  ),  

                   /// 'default'       => array( 'listing')
              ///  ),

              

                array(
                    'label'      => __('Admin approval required for new services', 'truelysell_core'),
                    'description'      => __('Require admin approval for any new services added', 'truelysell_core'),
                    'id'        => 'new_listing_requires_approval',
                    'type'      => 'checkbox',
                ),    

                array(
                    'label'      => __('Admin approval required for editing service', 'truelysell_core'),
                    'description'      => __('Require admin approval for any edited service', 'truelysell_core'),
                    'id'        => 'edit_listing_requires_approval',
                    'type'      => 'checkbox',
                ),          
               // array(
                    //'label'      => __('Notify admin by email about new service waiting for approval', 'truelysell_core'),
                    //'description'      => __('Send email about any new services added', 'truelysell_core'),
                    //'id'        => 'new_listing_admin_notification',
                    //'type'      => 'checkbox',
               // ),       
                
                array(
                    'label' => __('Service duration', 'truelysell_core'),
                    'description' => __('Set default listing duration. Set to 0 if you don\'t want listings to have an expiration date.', 'truelysell_core'),
                    'id'   => 'default_duration', //field id must be unique
                    'type' => 'text',
                    'default' => '30',
                ),

                array(
                    'label' => __( 'Service images upload limit', 'truelysell_core' ),
                    'description' => __( 'Number of images that can be uploaded to one service', 'truelysell_core' ),
                    'id'   => 'max_files', //field id must be unique
                    'type' => 'text',
                    'default' => '10',    
                ),   
                array(
                    'label' => __( 'Service image maximum size (in MB)', 'truelysell_core' ),
                    'description' => __( 'Maximum file size to upload ', 'truelysell_core' ),
                    'id'   => 'max_filesize', //field id must be unique
                    'type' => 'text',
                    'default' => '2',    
                ),               
                
            )
        );





        $settings['listing_packages'] = array(
            'title'                 => __('<i class="fa fa-cubes"></i> Packages Options', 'truelysell_core'),
            // 'description'           => __( 'Settings for single listing view.', 'truelysell_core' ),
            'fields'                => array(

                array(
                    'label'      => __('Paid listings', 'truelysell_core'),
                    'description'      => __('Adding listings by users will require purchasing a Listing Package', 'truelysell_core'),
                    'id'        => 'new_listing_requires_purchase',
                    'type'      => 'checkbox',
                ),
                array(
                    'label'         => __('Allow packages to only be purchased once per client', 'truelysell_core'),
                    'description'   => __('Selected packages can be bought only once, useful for demo/free packages', 'truelysell_core'),
                    'id'            => 'buy_only_once',
                    'type'          => 'checkbox_multi',
                    'options'       => truelysell_core_get_listing_packages_as_options(),
                    //'options'       => array( 'linux' => 'Linux', 'mac' => 'Mac', 'windows' => 'Windows' ),
                    'default'       => array()
                ),
                array(
                    'id'            => 'listing_packages_options',
                    'label'         => __('Check module to disable it in Submit Listing form if you want to make them available only in packages', 'truelysell_core'),
                    'description'   => __('If you want to use packages with ', 'truelysell_core'),
                    'type'          => 'checkbox_multi',
                    'options'       => array(
                       /// 'option_booking' => esc_html__('Booking Module', 'truelysell_core'),
                        'option_reviews' => esc_html__('Reviews Module', 'truelysell_core'),
                        'option_gallery' => esc_html__('Gallery Module', 'truelysell_core'),
                       /// 'option_social_links' => esc_html__('Social Links Module', 'truelysell_core'),
                        'option_opening_hours' => esc_html__('Opening Hours Module', 'truelysell_core'),
                        'option_video' => esc_html__('Video Module', 'truelysell_core'),
                        ///'option_coupons' => esc_html__('Coupons Module', 'truelysell_core'),
                    ), //service


                ),
                array(
                    'label'      => __('Show extra package options automatically in pricing table', 'truelysell_core'),

                    'id'        => 'populate_listing_package_options',
                    'type'      => 'checkbox',
                ),

            )
        );

        $settings['single'] = array(
            'title'                 => __( '<i class="fa fa-file"></i> Single Service', 'truelysell_core' ),
            'fields'                => array(
                 array(
                    'id'            => 'gallery_type',
                    'label'         => __( 'Default Gallery Type', 'truelysell_core' ),
                    'type'          => 'select',
                    'options'       => array( 
                            'top'       => __('Gallery on top (requires minimum 4 photos)', 'truelysell_core' )
                    ),
                    'default'       => 'top'
                ),
                
                array(
                    'id'            => 'owners_can_review',
                    'label'         => __( 'Allow providers to add reviews', 'truelysell_core' ),
                    'type'          => 'checkbox',
                ),
                array(
                    'id'            => 'disable_reviews',
                    'label'         => __( 'Disable reviews on services', 'truelysell_core' ),
                    'type'          => 'checkbox',
                )
            )
        ); 

        $settings['booking'] = array(
            'title'                 => __( '<i class="fa fa-calendar-alt"></i> Booking', 'truelysell_core' ),
            'fields'                => array(

                array(
                    'label'      => __('Disable Bookings module', 'truelysell_core'),
                    'description'      => __('By default bookings are enabled, check this checkbox to disable it and remove booking options from Submit Service', 'truelysell_core'),
                    'id'        => 'bookings_disabled',
                    'type'      => 'checkbox',
                ), 
                
                   array(
                    'id'            => 'disable_service_availability',
                    'label'         => __( 'Disable Service Availability', 'truelysell_core' ),
                    'description'   => __( 'Disable service availability in servcie details page', 'truelysell_core' ),
                   'type'          => 'checkbox',
                  ), 

               /// array(
                   /// 'id'            => 'disable_payments',
                   /// 'label'         => __( 'Disable payments in bookings', 'truelysell_core' ),
                    ///'description'   => __( 'Bookings will have prices but the payments won\'t be handled by the site. Disable Wallet page in Truelysell Core -> Pages', 'truelysell_core' ),
                   /// 'type'          => 'checkbox',
               /// ),   
                
                ///array(
                  ///  'id'            => 'instant_booking_require_payment',
                   /// 'label'         => __( 'For "online payment option" require payment first to confirm the booking', 'truelysell_core' ),
                    ///'description'   => __( 'Users will have to pay for booking immediately to confirm the booking.', 'truelysell_core' ),
                   // 'type'          => 'checkbox',
                ///),   


                array(
                    'id'            => 'booking_email_required',
                    'label'         => __('Make Email field required in booking confirmation form', 'truelysell_core'),
                    'type'          => 'checkbox',

                ),    array(
                    'id'            => 'booking_phone_required',
                    'label'         => __('Make Phone field required in booking confirmation form', 'truelysell_core'),
                    'type'          => 'checkbox',

                ),

                array(
                    'id'            => 'add_address_fields_booking_form',
                    'label'         => __('Add address field to booking confirmation form', 'truelysell_core'),
                    'type'          => 'checkbox',
                    'description'   => __('Used in WooCommerce Orders and required for some payment gateways ', 'truelysell_core'),
                ),

                array(
                    'id'            => 'show_expired',
                    'label'         => __( 'Show Expired Bookings in Dashboard page', 'truelysell_core' ),
                    'description'   => __( 'Adds "Expired" subpage to Bookings page in provider Dashboard, with list of expired bookings ', 'truelysell_core' ),
                    'type'          => 'checkbox',
                ),  
                array(
                    'id'            => 'default_booking_expiration_time',
                    'label'         => __( 'Set how long booking will be waiting for payment before expiring', 'truelysell_core' ),
                    'description'   => __( 'Default is 48 hours, set to 0 to disable', 'truelysell_core' ),
                    'type'          => 'text',
                    'default'       => '48',
                ), 

            )
        );
        

        $settings['browse'] = array(
            'title'                 => __( '<i class="fa fa-search-location"></i> Browse/Search Options', 'truelysell_core' ),
            'fields'                => array(
                array(
                    'id'            => 'ajax_browsing',
                    'label'         => __( 'Ajax based service browsing', 'truelysell_core' ),
                    'description'   => __( '.', 'truelysell_core' ),
                    'type'          => 'select',
                    'options'       => array( 
                            'on'    => __('Enabled', 'truelysell_core' ),
                            'off'   => __('Disabled', 'truelysell_core' ),  
                    ),
                    'default'       => 'on'
                )
            )
        );

        $settings['registration'] = array(
            'title'                 => __( '<i class="fa fa-user-friends"></i> Registration', 'truelysell_core' ),
            'fields'                => array(
                array(
                    'id'            => 'front_end_login',
                    'label'         => __( 'Enable Forced Front End Login', 'truelysell_core' ),
                    'description'   => __( 'Enabling this option will redirect all wp-login request to frontend form. Be aware that on some servers or some configuration, especially with security plugins, this might cause a redirect loop, so always test this setting on different browser, while being still logged in Dashboard to have option to disable that if things go wrong.', 'truelysell_core' ),
                    'type'          => 'checkbox',
                ),
                array(
                    'id'            => 'popup_login',
                    'label'         => __( 'Login/Registration Form Type', 'truelysell_core' ),
                    'description'   => __( '.', 'truelysell_core' ),
                    'type'          => 'select',
                    'options'       => array( 
                            'page'   => __('Separate page', 'truelysell_core' ), 
                    ),
                    'default'       => 'page'
                ),
                 array(
                    'id'            => 'autologin',
                    'label'         => __( 'Automatically login user after successful registration', 'truelysell_core' ),
                    'description'   => __( '.', 'truelysell_core' ),
                    'type'          => 'checkbox',
                ),
               
                array(
                    'id'            => 'registration_form_default_role',
                    'label'         => __( 'Set default role for Registration Form', 'truelysell_core' ),
                    'description'   => __( 'If you set it hidden, set default role in Settings -> General -> New User Default Role', 'truelysell_core' ),
                    'type'          => 'select',
                    'default'       => 'guest',
                    'options'       => array(
                        'owner' => esc_html__('Owner','truelysell_core'), 
                        'guest' => esc_html__('Guest','truelysell_core'), 
                    ),
                ),
              
                array(
                    'id'            => 'registration_hide_username',
                    'label'         => __( 'Hide Username field in Registration Form', 'truelysell_core' ),
                    'description'   => __( 'Username will be generated from email address (part before @)', 'truelysell_core' ),
                    'type'          => 'checkbox',
                ),
               
                array(
                    'id'            => 'display_first_last_name',
                    'label'         => __( 'Display First and Last name fields in registration form', 'truelysell_core' ),
                    'description'   => __( 'Adds optional input fields for first and last name', 'truelysell_core' ),
                    'type'          => 'checkbox',
                ), 
                array(
                    'id'            => 'display_first_last_name_required',
                    'label'         => __( 'Make First and Last name fields required', 'truelysell_core' ),
                    'description'   => __( 'Enable to make those fields required', 'truelysell_core' ),
                    'type'          => 'checkbox',
                ),
                array(
                    'id'            => 'display_password_field',
                    'label'         => __('Add Password pickup field to registration form', 'truelysell_core'),
                    'description'   => __('Enable to add password field, when disabled it will be randomly generated and sent via email', 'truelysell_core'),
                    'type'          => 'checkbox',
                ),
              
                  array(
                    'id'            => 'owner_registration_redirect',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Provider redirect after registration to page' , 'truelysell_core' ),
                    'type'          => 'select',
                ),
                array(
                    'id'            => 'owner_login_redirect',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Provider  redirect after login to page' , 'truelysell_core' ),
                    'type'          => 'select',
                ),  
                array(
                    'id'            => 'guest_registration_redirect',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Customer redirect after registration to page' , 'truelysell_core' ),
                    'type'          => 'select',
                ),
                array(
                    'id'            => 'guest_login_redirect',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Customer redirect after login to page' , 'truelysell_core' ),
                    'type'          => 'select',
                )
               
            )
        );
    if (class_exists('WeDevs_Dokan') ) : 
        $settings['dokan'] = array(
            'title'                 => __('<i class="fa fa-shopping-cart"></i> Dokan', 'truelysell_core'),
            'fields'                => array(
                array(
                    'label'      => __('Default user role for new users with Dokan active', 'truelysell_core'),
                    'description'      => __('Choose if you want all new owners to be vendors', 'truelysell_core'),
                    'id'        => 'role_dokan', //each field id must be unique
                    'type'      => 'select',
                    'options'   => array(
                        'seller' => esc_html__('Vendor', 'truelysell_core'),
                        'owner' => esc_html__('Owner', 'truelysell_core')
                    ),
                    'default'       => 'no'
                ),
                array(
                    'label'         => __('Disable product categories from Dokan', 'truelysell_core'),
                    'description'   => __('Selected which taxnomies should not be disaplyed in stores and products screen', 'truelysell_core'),
                    'id'            => 'dokan_exclude_categories',
                    'type'          => 'checkbox_multi',
                    'options'       => truelysell_core_get_product_taxonomies_as_options(),
                    'default'       => array('truelysell-booking')
                ),      
            )
        );
    endif;
          $settings['paypal_payout'] = array(
            'title'                 => __( '<i class="fa fa-paypal"></i> PayPal Payout', 'truelysell_core' ),
            'fields'                => array(
                array(
                    'label'      => __('Activate / Deactivate PayOut feature', 'truelysell_core'),
                    'description'      => __('Activate/Deactivate PayPal Payout feature', 'truelysell_core'),
                    'id'        => 'payout_activation', //each field id must be unique
                    'type'      => 'select',
                    'options'   => array(
                        'no' => esc_html__( 'Deactivate', 'truelysell_core' ),
                        'yes' => esc_html__( 'Activate', 'truelysell_core' )
                    ),
                    'default'       => 'no'
                ),

                array(
                    'label'      => __('Live/Sandbox', 'truelysell_core'),
                    'description'      => __('Select the Environment', 'truelysell_core'),
                    'id'        => 'payout_environment', //each field id must be unique
                    'type'      => 'select',
                    'options'   => array(
                        'sandbox' => esc_html__( 'Sandbox / Testing', 'truelysell_core' ),
                        'live' => esc_html__( 'Live / Production', 'truelysell_core' )
                    ),
                    'default'       => 'sandbox'
                ),

                array(
                    'label'      => __('PayPal Client ID', 'truelysell_core'),
                    'id'        => 'payout_sandbox_client_id', //each field id must be unique
                    'type'      => 'text',
                    'description'      => __('PayPal Client ID for Sand box', 'truelysell_core'),
                ),
                array(
                    'label'      => __('PayPal Client Secret', 'truelysell_core'),
                    'id'        => 'payout_sandbox_client_secret', //each field id must be unique
                    'type'      => 'password',
                    'description'      => __('PayPal Client Secret for Sand box', 'truelysell_core'),
                    'placeholder'      => __('PayPal Client Secret for Sand box', 'truelysell_core'),
                ),

                array(
                    'label'      => __('PayPal Client ID', 'truelysell_core'),
                    'id'        => 'payout_live_client_id', //each field id must be unique
                    'type'      => 'text',
                    'description'      => __('PayPal Client ID for Production / Live Environment', 'truelysell_core'),
                ),
                array(
                    'label'      => __('PayPal Client Secret', 'truelysell_core'),
                    'id'        => 'payout_live_client_secret', //each field id must be unique
                    'type'      => 'password',
                    'description'      => __('PayPal Client Secret for Production / Live Environment', 'truelysell_core'),
                ),

                array(
                    'label'      => __('Email Subject', 'truelysell_core'),
                    'description'      => __('Default Email Subject', 'truelysell_core'),
                    'id'        => 'payout_email_subject', //each field id must be unique
                    'type'      => 'textarea',
                    'default'   => 'Here is your commission.'
                ),
                array(
                    'label'      => __('Email Message', 'truelysell_core'),
                    'description'      => __('Default Email Message', 'truelysell_core'),
                    'id'        => 'payout_email_message', //each field id must be unique
                    'type'      => 'textarea',
                    'default'   => 'You have received a payout (commission)! Thanks for using our listing!'
                ),
                array(
                    'label'      => __('Transaction Note', 'truelysell_core'),
                    'description'      => __('Any note that you want to add', 'truelysell_core'),
                    'id'        => 'payout_trx_note', //each field id must be unique
                    'type'      => 'textarea',
                    'default'   => ''
                ),
            )
        );

       $settings['pages'] = array(
            'title'                 => __( '<i class="fa fa-layer-group"></i> Pages', 'truelysell_core' ),
            'fields'                => array(
                array(
                    'id'            => 'dashboard_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Dashboard Page' , 'truelysell_core' ),
                    'description'   => __( 'Main Dashboard page for user, content: [truelysell_dashboard]', 'truelysell_core' ),
                    'type'          => 'select',
                ),
                array(
                    'id'            => 'messages_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Messages Page' , 'truelysell_core' ),
                    'description'   => __( 'Main page for user messages, content: [truelysell_messages]', 'truelysell_core' ),
                    'type'          => 'select',
                ),
                array(
                    'id'            => 'bookings_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Bookings Page' , 'truelysell_core' ),
                    'description'   => __( 'Page for owners to manage their bookings, content: [truelysell_bookings]', 'truelysell_core' ),
                    'type'          => 'select',
                ),  
                array(
                    'id'            => 'user_bookings_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'My Bookings Page' , 'truelysell_core' ),
                    'description'   => __( 'Page for guest to see their bookings,content: [truelysell_my_bookings]', 'truelysell_core' ),
                    'type'          => 'select',
                ), 
                array(
                    'id'            => 'booking_confirmation_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Booking confirmation' , 'truelysell_core' ),
                    'description'   => __( 'Displays page for booking confirmation, content: [truelysell_booking_confirmation]', 'truelysell_core' ),
                    'type'          => 'select',
                ), 
                array(
                    'id'            => 'listings_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'My Services Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays or listings added by user, content [truelysell_my_listings]', 'truelysell_core' ),
                    'type'          => 'select',
                ),    
                array(
                    'id'            => 'wallet_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Wallet Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays or owners earnings, content [truelysell_wallet]', 'truelysell_core' ),
                    'type'          => 'select',
                ), 
                array(
                    'id'            => 'payout_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Payout Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays or payout history, content [truelysell_payout]', 'truelysell_core' ),
                    'type'          => 'select',
                ),                  
                array(
                    'id'            => 'reviews_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Reviews Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays reviews of user listings, content: [truelysell_reviews]', 'truelysell_core' ),
                    'type'          => 'select',
                ),                
                array(
                    'id'            => 'bookmarks_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Bookmarks Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays user bookmarks, content: [truelysell_bookmarks]', 'truelysell_core' ),
                    'type'          => 'select',
                ),
                array(
                    'id'            => 'submit_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'Submit Service Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays submit listing page, content: [truelysell_submit_listing]', 'truelysell_core' ),
                    'type'          => 'select',
                ),                
                array(
                    'id'            => 'profile_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'My Profile Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays user profile page, content: [truelysell_my_account]', 'truelysell_core' ),
                    'type'          => 'select',
                ),

                array(
                    'id'            => 'notification_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'My Notification Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays notification page, content: [truelysell_allnotification]', 'truelysell_core' ),
                    'type'          => 'select',
                ),

                array(
                    'id'            => 'login_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'My Login Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays login page', 'truelysell_core' ),
                    'type'          => 'select',
                ),

                array(
                    'id'            => 'register_page',
                    'options'       => truelysell_core_get_pages_options(),
                    'label'         => __( 'My Register Page' , 'truelysell_core' ),
                    'description'   => __( 'Displays user register page', 'truelysell_core' ),
                    'type'          => 'select',
                ),
                    
                array(
                    'label'          => __('Lost Password Page', 'truelysell_core'),
                    'description'          => __('Select page that holds [truelysell_lost_password] shortcode', 'truelysell_core'),
                    'id'            =>  'lost_password_page',
                    'type'          => 'select',
                    'options'       => truelysell_core_get_pages_options(),
                ),                
                array(
                    'label'          => __('Reset Password Page', 'truelysell_core'),
                    'description'          => __('Select page that holds [truelysell_reset_password] shortcode', 'truelysell_core'),
                    'id'            =>  'reset_password_page',
                    'type'          => 'select',
                    'options'       => truelysell_core_get_pages_options(),
                ),  
               
                

            )
        );

       $settings['emails'] = array(
            'title'                 => __( '<i class="fa fa-envelope"></i> Emails', 'truelysell_core' ),
            'fields'                => array(
        
                array(
                    'label'  => __('"From name" in email', 'truelysell_core'),
                    'description'  => __('The name from who the email is received, by default it is your site name.', 'truelysell_core'),
                    'id'    => 'emails_name',
                    'default' =>  get_bloginfo( 'name' ),                
                    'type'  => 'text',
                ),

                array(
                    'label'  => __('"From" email ', 'truelysell_core'),
                    'description'  => __('This will act as the "from" and "reply-to" address. This emails should match your domain address', 'truelysell_core'),
                    'id'    => 'emails_from_email',
                    'default' =>  get_bloginfo( 'admin_email' ),               
                    'type'  => 'text',
                ),
                array(
                    'id'            => 'email_logo',
                    'label'         => __( 'Logo for emails' , 'truelysell_core' ),
                    'description'   => __( 'Set here logo for emails, if nothing is set emails will be using default site logo', 'truelysell_core' ),
                    'type'          => 'image',
                    'default'       => '',
                    'placeholder'   => ''
                ),
                array(
                    'label' => __('Registration/Welcome email for new users', 'truelysell_core'),
                    'description' =>  __('Registration/Welcome email for new users', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_welcome',
                    'description' => ''.__('Available tags are: ').'<strong>{user_mail}, {user_name}, {site_name}, {password}, {login}</strong>',
                ),
                array(
                    'label'      => __('Disable Welcome email to user (enabled by default)', 'truelysell_core'),
                    'description'      => __('Check this checkbox to disable sending emails to new users', 'truelysell_core'),
                    'id'        => 'welcome_email_disable',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Welcome Email Subject', 'truelysell_core'),
                    'default'      => __('Welcome to {site_name}', 'truelysell_core'),
                    'id'        => 'listing_welcome_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Welcome Email Content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
Welcome to our website.<br>
<ul>
<li>Username: {login}</li>
<li>Password: {password}</li>
</ul>
<br>
Thank you.
<br>")),
                    'id'        => 'listing_welcome_email_content',
                    'type'      => 'editor',
                ),   


                /*----------------*/

                array(
                    
                    'label' =>  __('Service Published notification email', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_published'
                ), 
                array(
                    'label'      => __('Enable service published notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to listing authors', 'truelysell_core'),
                    'id'        => 'listing_published_email',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Published notification Email Subject', 'truelysell_core'),
                    'default'      => __('Your listing was published - {listing_name}', 'truelysell_core'),
                    'id'        => 'listing_published_email_subject',
                    'type'      => 'text',

                ),
                 array(
                    'label'      => __('Published notification Email Content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
We are pleased to inform you that your submission '{listing_name}' was just published on our website.<br>
<br>
Thank you.
<br>")),
                    'id'        => 'listing_published_email_content',
                    'type'      => 'editor',
                ),   

                /*----------------New listing notification email' */
                array(
                  
                    'label'      =>  __('New service notification email', 'truelysell_core'),
                    'type'      => 'title',
                    'id'        => 'header_new'
                ), 
                array(
                    'label'      => __('Enable new listing notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to listing authors', 'truelysell_core'),
                    'id'        => 'listing_new_email',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('New service notification email subject', 'truelysell_core'),
                    'default'      => __('Thank you for adding a listing', 'truelysell_core'),
                    'id'        => 'listing_new_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('New service notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    Thank you for submitting your listing '{listing_name}'.<br>
                    <br>")),
                    'id'        => 'listing_new_email_content',
                    'type'      => 'editor',
                ),  

                /*----------------*/
                array(
                   
                    'label' =>  __('Expired service notification email', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_expired'
                ), 
                array(
                    'label'      => __('Enable expired service notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to listing authors', 'truelysell_core'),
                    'id'        => 'listing_expired_email',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Expired service notification email subject', 'truelysell_core'),
                    'default'      => __('Your listing has expired - {listing_name}', 'truelysell_core'),
                    'id'        => 'listing_expired_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Expired service notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    We'd like you to inform you that your listing '{listing_name}' has expired and is no longer visible on our website. You can renew it in your account.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'listing_expired_email_content',
                    'type'      => 'editor',
                ),

                /*----------------*/
                array(
                 
                    'label' =>  __('Expiring service in next 5 days notification email ', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_expiring_soon'
                ), 
                array(
                    'label'      => __('Enable Expiring soon service notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to service authors', 'truelysell_core'),
                    'id'        => 'listing_expiring_soon_email',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Expiring soon service notification email subject', 'truelysell_core'),
                    'default'      => __('Your service is expiring in 5 days - {listing_name}', 'truelysell_core'),
                    'id'        => 'listing_expiring_soon_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Expiring soon service notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    We'd like you to inform you that your service '{listing_name}' is expiring in 5 days.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'listing_expiring_soon_email_content',
                    'type'      => 'editor',
                ),  
           
           /*----------------*/
                array(
                   
                    'label' =>  __('Booking confirmation to user (paid - not instant booking) ', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_booking_confirmation'
                ), 
                array(
                    'label'      => __('Enable Booking confirmation notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to users after they request booking', 'truelysell_core'),
                    'id'        => 'booking_user_waiting_approval_email',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Booking confirmation notification email subject', 'truelysell_core'),
                    'default'      => __('Thank you for your booking - {listing_name}', 'truelysell_core'),
                    'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                        ,{dates},{user_message},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                    'id'        => 'booking_user_waiting_approval_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Booking confirmation notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    Thank you for your booking request on {listing_name} for {dates}. Please wait for confirmation and further instructions.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'booking_user_waiting_approval_email_content',
                    'type'      => 'editor',
                ),   
                /*----------------*/
                array(
                    
                    'label' =>  __('Instant Booking confirmation to user', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_instant_booking_confirmation'
                ), 
                array(
                    'label'      => __('Enable Instant Booking confirmation notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to users after they request booking', 'truelysell_core'),
                    'id'        => 'instant_booking_user_waiting_approval_email',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Instant Booking confirmation notification email subject', 'truelysell_core'),
                    'default'      => __('Thank you for your booking - {listing_name}', 'truelysell_core'),
                    'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                        {payment_url},{expiration},{dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                    'id'        => 'instant_booking_user_waiting_approval_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Instant Booking confirmation notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    Thank you for your booking request on {listing_name} for {dates}. Please wait for confirmation and further instructions.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'instant_booking_user_waiting_approval_email_content',
                    'type'      => 'editor',
                ),  

   /*----------------*/
                array(
               
                    'label' =>  __('New booking request notification to owner ', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_booking_notification_owner'
                ), 
                array(
                    'label'      => __('Enable Booking request notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to owners when new booking was requested', 'truelysell_core'),
                    'id'        => 'booking_owner_new_booking_email',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Booking request notification email subject', 'truelysell_core'),
                    'default'      => __('There is a new booking request for {listing_name}', 'truelysell_core'),
                    'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                       {dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                    'id'        => 'booking_owner_new_booking_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Booking request notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    There's a new booking request on '{listing_name}' for {dates}. Go to your Bookings Dashboard to accept or reject it.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'booking_owner_new_booking_email_content',
                    'type'      => 'editor',
                ),   


                array(
                    
                    'label' =>  __('New Instant booking notification to owner ', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_instant_booking_notification_owner'
                ), 
                array(
                    'label'      => __('Enable Instant Booking notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to owners when new instant booking was made', 'truelysell_core'),
                    'id'        => 'booking_instant_owner_new_booking_email',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Instant Booking notification email subject', 'truelysell_core'),
                    'default'      => __('There is a new instant booking for {listing_name}', 'truelysell_core'),
                    'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                        {payment_url},{expiration},{dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                    'id'        => 'booking_instant_owner_new_booking_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Instant Booking notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    There's a new booking  on '{listing_name}' for {dates}.
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'booking_instant_owner_new_booking_email_content',
                    'type'      => 'editor',
                ),   

                 /*----------------*/
                array(
                  
                    'label' =>  __('Free booking confirmation to user', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_free_booking_notification_user'
                ), 
                array(
                    'label'      => __('Enable Booking confirmation notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to users when booking was accepted by owner', 'truelysell_core'),
                    'id'        => 'free_booking_confirmation',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Booking request notification email subject', 'truelysell_core'),
                    'default'      => __('Your booking request was approved {listing_name}', 'truelysell_core'),
                     'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                        {payment_url},{expiration},{dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                    'id'        => 'free_booking_confirmation_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Booking request notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    Your booking request on '{listing_name}' for {dates} was approved. See you soon!.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'free_booking_confirmation_email_content',
                    'type'      => 'editor',
                ),     


                 /*----------------*/
                 /*----------------*/
                array(
                  
                    'label' =>  __('Booking confirmation to user, pay in cash only', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_cash_booking_notification_user'
                ), 
                array(
                    'label'      => __('Enable Booking pay in cash confirmation notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to users when booking was accepted by owner and requires payment in cash', 'truelysell_core'),
                    'id'        => 'mail_to_user_pay_cash_confirmed',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Booking confirmation "pay with cash" notification email subject', 'truelysell_core'),
                    'default'      => __('Your booking request was approved {listing_name}', 'truelysell_core'),
                     'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                        {payment_url},{expiration},{dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                    'id'        => 'mail_to_user_pay_cash_confirmed_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Booking confirmation "pay with cash" notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    Your booking request on '{listing_name}' for {dates} was approved. See you soon!.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'mail_to_user_pay_cash_confirmed_email_content',
                    'type'      => 'editor',
                ),     


                 /*----------------*/
                array(
             
                    'label' =>  __('Booking approved - payment needed email to user', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_pay_booking_notification_owner'
                ), 
                array(
                    'label'      => __('Enable Booking confirmation notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to users when booking was accepted by owner and they need to pay', 'truelysell_core'),
                    'id'        => 'pay_booking_confirmation_user',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Booking request notification email subject', 'truelysell_core'),
                    'default'      => __('Your booking request was approved {listing_name}, please pay', 'truelysell_core'),
                     'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},{payment_url},{expiration}',
                    'id'        => 'pay_booking_confirmation_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Booking request notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    Your booking request on '{listing_name}' for {dates} was approved. Here's the payment link {payment_url}, the booking will expire after {expiration} if not paid!.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'pay_booking_confirmation_email_content',
                    'type'      => 'editor',
                ),  

                   /*----------------*/
                array(
                 
                    'label' =>  __('Booking paid notification  to owner', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_pay_booking_confirmation_owner'
                ), 
                array(
                    'label'      => __('Enable Booking paid confirmation notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to owner when booking was paid by use', 'truelysell_core'),
                    'id'        => 'paid_booking_confirmation',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Booking paid notification email subject', 'truelysell_core'),
                    'default'      => __('Your booking was paid by user - {listing_name}', 'truelysell_core'),
                    'id'        => 'paid_booking_confirmation_email_subject',
                    'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},{payment_url},{expiration}',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Booking paid notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    The booking for '{listing_name}' on {dates} was paid by user.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'paid_booking_confirmation_email_content',
                    'type'      => 'editor',
                ),  
                   /*----------------*/
                array(
                 
                    'label' =>  __('Booking paid confirmation  to user', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_pay_booking_confirmation_user'
                ), 
                array(
                    'label'      => __('Enable Booking paid confirmation email to user', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to user with confirmation of payment', 'truelysell_core'),
                    'id'        => 'user_paid_booking_confirmation',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Booking paid confirmation email subject', 'truelysell_core'),
                    'default'      => __('Your booking was paid {listing_name}', 'truelysell_core'),
                    'id'        => 'user_paid_booking_confirmation_email_subject',
                    'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},{payment_url},{expiration}',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Booking paid confirmation email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    Here are details about your paid booking for '{listing_name}' on {dates}.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'user_paid_booking_confirmation_email_content',
                    'type'      => 'editor',
                ),  
                
                // booking cancelled
                array(
              
                    'label' =>  __('Booking cancelled notification to user ', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_booking_cancellation_user'
                ), 
                array(
                    'label'      => __('Enable Booking cancellation notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to user when booking is cancelled', 'truelysell_core'),
                    'id'        => 'booking_user_cancallation_email',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('Booking cancelled notification email subject', 'truelysell_core'),
                    'default'      => __('Your booking request for {listing_name} was cancelled', 'truelysell_core'),
                    'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details}',
                    'id'        => 'booking_user_cancellation_email_subject',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('Booking cancelled notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    Your booking '{listing_name}' for {dates} was cancelled.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'booking_user_cancellation_email_content',
                    'type'      => 'editor',
                ),   
               
                /*New message in conversation*/
                array(
             
                    'label' =>  __('Email notification about new conversation', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_new_converstation'
                ), 
                array(
                    'label'      => __('Enable new conversation notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to user when there was new conversation started', 'truelysell_core'),
                    'id'        => 'new_conversation_notification',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('New conversation notification email subject', 'truelysell_core'),
                    'default'      => __('You got new conversation', 'truelysell_core'),
                    'id'        => 'new_conversation_notification_email_subject',
                    'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{sender},{conversation_url},{site_name},{site_url}',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('New conversation notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    There's a new conversation waiting for your on {site_name}.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'new_conversation_notification_email_content',
                    'type'      => 'editor',
                ),  

                /*New message in conversation*/
                array(
             
                    'label' =>  __('Email notification about new message', 'truelysell_core'),
                    'type' => 'title',
                    'id'   => 'header_new_message'
                ), 
                array(
                    'label'      => __('Enable new message notification email', 'truelysell_core'),
                    'description'      => __('Check this checkbox to enable sending emails to user when there was new message send', 'truelysell_core'),
                    'id'        => 'new_message_notification',
                    'type'      => 'checkbox',
                ), 
                array(
                    'label'      => __('New message notification email subject', 'truelysell_core'),
                    'default'      => __('You got new message', 'truelysell_core'),
                    'id'        => 'new_message_notification_email_subject',
                    'description' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{listing_name},{listing_url},{listing_address},{sender},{conversation_url},{site_name},{site_url}',
                    'type'      => 'text',
                ),
                 array(
                    'label'      => __('New message notification email content', 'truelysell_core'),
                    'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                    There's a new message waiting for your on {site_name}.<br>
                    <br>
                    Thank you
                    <br>")),
                    'id'        => 'new_message_notification_email_content',
                    'type'      => 'editor',
                ),  

               
              


            ),
        );

        $settings = apply_filters( $this->_token . '_settings_fields', $settings );

        return $settings;
    }

    /**
     * Register plugin settings
     * @return void
     */
    public function register_settings () {
        if ( is_array( $this->settings ) ) {

            // Check posted/selected tab
            $current_section = '';
            if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
                $current_section = $_POST['tab'];
            } else {
                if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
                    $current_section = $_GET['tab'];
                }
            }

            foreach ( $this->settings as $section => $data ) {

                if ( $current_section && $current_section != $section ) continue;

                // Add section to page
                add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->_token . '_settings' );

                foreach ( $data['fields'] as $field ) {

                    // Validation callback for field
                    $validation = '';
                    if ( isset( $field['callback'] ) ) {
                        $validation = $field['callback'];
                    }

                    // Regisster field
                    $option_name = $this->base . $field['id'];

                    register_setting( $this->_token . '_settings', $option_name, $validation );

                    // Add field to page

                    add_settings_field( $field['id'], $field['label'], array($this, 'display_field'), $this->_token . '_settings', $section, array( 'field' => $field, 'class' => 'truelysell_map_settings '.$field['id'],  'prefix' => $this->base ) );
                }

                if ( ! $current_section ) break;
            }
        }
    }

    public function settings_section ( $section ) {
        if(isset($this->settings[ $section['id'] ]['description'] )){
        $html = '' . $this->settings[ $section['id'] ]['description'] . '' . "\n";
        echo $html;
        }
    }

    /**
     * Load settings page content
     * @return void
     */
    public function settings_page () {

        // Build page HTML
        $html = '<div class="wrap" id="' . $this->_token . '_settings">' . "\n";
            $html .= '<h2>' . __( 'Plugin Settings' , 'truelysell_core' ) . '</h2>' . "\n";

            $tab = '';
            if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
                $tab .= $_GET['tab'];
            }

            // Show page tabs
            if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

                $html .= '<div id="truelysell-core-ui"><div id="nav-tab-container"><h2 class="nav-tab-wrapper">' . "\n";

                $c = 0;
                foreach ( $this->settings as $section => $data ) {

                    // Set tab class
                    $class = 'nav-tab';
                    if ( ! isset( $_GET['tab'] ) ) {
                        if ( 0 == $c ) {
                            $class .= ' nav-tab-active';
                        }
                    } else {
                        if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
                            $class .= ' nav-tab-active';
                        }
                    }

                    // Set tab link
                    $tab_link = add_query_arg( array( 'tab' => $section ) );
                    if ( isset( $_GET['settings-updated'] ) ) {
                        $tab_link = remove_query_arg( 'settings-updated', $tab_link );
                    }

                    // Output tab
                    $html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' .  $data['title']  . '</a>' . "\n";

                    ++$c;
                }
              
                $html .= '</h2></div>' . "\n";
            }

            $html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

                // Get settings fields
                ob_start();
                settings_fields( $this->_token . '_settings' );
                $this->do_truelysell_settings_sections( $this->_token . '_settings' );
                $html .= ob_get_clean();

                $html .= '<p class="submit">' . "\n";
                    $html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
                    $html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'truelysell_core' ) ) . '" />' . "\n";
                $html .= '</p>' . "\n";
            $html .= '</form></div>' . "\n";
        $html .= '</div>' . "\n";

        echo $html;
    }

   public function do_truelysell_settings_sections( $page ) {
        global $wp_settings_sections, $wp_settings_fields;
     
        if ( ! isset( $wp_settings_sections[ $page ] ) ) {
            return;
        }
     
        foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
            if ( $section['title'] ) {
                echo "<h2>{$section['title']}";
                echo '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'truelysell_core' ) ) . '" />' . "\n";
                echo "</h2>\n ";
            }
     
            if ( $section['callback'] ) {
                call_user_func( $section['callback'], $section );
            }
     
            if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
                continue;
            }
            echo '<table class="form-table" role="presentation">';
            $this->do_truelysell_settings_fields( $page, $section['id'] );
            echo '</table>';
        }
    }

    public function  do_truelysell_settings_fields( $page, $section ) {
    global $wp_settings_fields;
 
    if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
        return;
    }
 
    foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
        $class = '';
 
        if ( ! empty( $field['args']['class'] ) ) {
            $class =' class= "truelysell_settings_' . esc_attr( $field['args']['field']['type'] ) . '"';
        }
 
        echo "<tr{$class}>";
 
        if ( ! empty( $field['args']['label_for'] ) ) {
            echo '
            <th class="truelysell_settings_' . esc_attr( $field['args']['field']['type'] ) . '" scope="row"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label>';
                if(isset($field['args']['field']['description']) && !empty($field['args']['field']['description'] )) {
                    echo  '<span class="description">' . $field['args']['field']['description'] . '</span>' . "\n";    

                }
                
            echo '</th>';
        } else {
           
            echo '<th class="truelysell_settings_' . esc_attr( $field['args']['field']['type'] ) . '" scope="row">' . $field['title'];
                if(isset($field['args']['field']['description']) && !empty($field['args']['field']['description'] )) {
                    echo  '<span class="description">' . $field['args']['field']['description'] . '</span>' . "\n";    

                }
            echo '</th>';
        }
 
        echo '<td>';
        call_user_func( $field['callback'], $field['args'] );
        echo '</td>';
        echo '</tr>';
    }
}

    /**
     * Generate HTML for displaying fields
     * @param  array   $field Field data
     * @param  boolean $echo  Whether to echo the field HTML or return it
     * @return void
     */
    public function display_field ( $data = array(), $post = false, $echo = true ) {

        // Get field info
        if ( isset( $data['field'] ) ) {
            $field = $data['field'];
        } else {
            $field = $data;
        }

        // Check for prefix on option name
        $option_name = '';
        if ( isset( $data['prefix'] ) ) {
            $option_name = $data['prefix'];
        }

        // Get saved data
        $data = '';
        if ( $post ) {

            // Get saved field data
            $option_name .= $field['id'];
            $option = get_post_meta( $post->ID, $field['id'], true );

            // Get data to display in field
            if ( isset( $option ) ) {
                $data = $option;
            }

        } else {

            // Get saved option
            $option_name .= $field['id'];
            $option = get_option( $option_name );

            // Get data to display in field
            if ( isset( $option ) ) {
                $data = $option;
            }

        }

        // Show default data if no option saved and default is supplied
        if ( $data === false && isset( $field['default'] ) ) {
            $data = $field['default'];
        } elseif ( $data === false ) {
            $data = '';
        }

        $html = '';

        switch( $field['type'] ) {

            case 'text':
            case 'url':
            case 'email':
                $html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" class="regular-text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( (isset($field['placeholder'])) ? $field['placeholder'] : '' ) . '" value="' . esc_attr( $data ) . '" />' . "\n";
            break;

            case 'password':
            case 'number':
            case 'hidden':
                $min = '';
                if ( isset( $field['min'] ) ) {
                    $min = ' min="' . esc_attr( $field['min'] ) . '"';
                }

                $max = '';
                if ( isset( $field['max'] ) ) {
                    $max = ' max="' . esc_attr( $field['max'] ) . '"';
                }
                $html .= '<input step="0.1" id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '"' . $min . '' . $max . '/>' . "\n";
            break;

            case 'text_secret':
                $html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="" />' . "\n";
            break;

            case 'textarea':
                $html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '">' . $data . '</textarea><br/>'. "\n";
            break;

            case 'checkbox':
                $checked = '';
                if ( $data && 'on' == $data ) {
                    $checked = 'checked="checked"';
                }
                $html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";
            break;

            case 'checkbox_multi':
                foreach ( $field['options'] as $k => $v ) {
                    $checked = false;
                    if ( in_array( $k, (array) $data ) ) {
                        $checked = true;
                    }
                    $html .= '<p><label for="' . esc_attr( $field['id'] . '_' . $k ) . '" class="checkbox_multi"><input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '[]" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label></p> ';
                }
            break;

            case 'radio':
                foreach ( $field['options'] as $k => $v ) {
                    $checked = false;
                    if ( $k == $data ) {
                        $checked = true;
                    }
                    $html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label><br> ';
                }
            break;

            case 'select':
                $html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '">';
                foreach ( $field['options'] as $k => $v ) {
                    $selected = false;
                    if ( $k == $data ) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
                }
                $html .= '</select> ';
            break;

            case 'select_multi':
                $html .= '<select name="' . esc_attr( $option_name ) . '[]" id="' . esc_attr( $field['id'] ) . '" multiple="multiple">';
                foreach ( $field['options'] as $k => $v ) {
                    $selected = false;
                    if ( in_array( $k, (array) $data ) ) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v . '</option>';
                }
                $html .= '</select> ';
            break;

            case 'image':
                $image_thumb = '';
                if ( $data ) {
                    $image_thumb = wp_get_attachment_thumb_url( $data );
                }
                $html .= '<img id="' . $option_name . '_preview" class="image_preview" src="' . $image_thumb . '" /><br/>' . "\n";
                $html .= '<input id="' . $option_name . '_button" type="button" data-uploader_title="' . __( 'Upload an image' , 'truelysell_core' ) . '" data-uploader_button_text="' . __( 'Use image' , 'truelysell_core' ) . '" class="image_upload_button button" value="'. __( 'Upload new image' , 'truelysell_core' ) . '" />' . "\n";
                $html .= '<input id="' . $option_name . '_delete" type="button" class="image_delete_button button" value="'. __( 'Remove image' , 'truelysell_core' ) . '" />' . "\n";
                $html .= '<input id="' . $option_name . '" class="image_data_field" type="hidden" name="' . $option_name . '" value="' . $data . '"/><br/>' . "\n";
            break;

            case 'color':
                ?><div class="color-picker" style="position:relative;">
                    <input type="text" name="<?php esc_attr_e( $option_name ); ?>" class="color" value="<?php esc_attr_e( $data ); ?>" />
                    <div style="position:absolute;background:#FFF;z-index:99;border-radius:100%;" class="colorpicker"></div>
                </div>
                <?php
            break;
            
            case 'editor':
                wp_editor($data, $option_name, array(
                    'textarea_name' => $option_name,
                    'editor_height' => 150
                ) );
            break;

        }

        switch( $field['type'] ) {

            case 'checkbox_multi':
            case 'radio':
            case 'select_multi':
            break;
            case 'title':
            break;
      
            default:
                if ( ! $post ) {
                    $html .= '<label for="' . esc_attr( $field['id'] ) . '">' . "\n";
                }

                

                if ( ! $post ) {
                    $html .= '</label>' . "\n";
                }
                 if($field['id']=='maps_api_server' && !empty($data)){
                       $html .= '<div class="truelysell-admin-test-api"><a target="_blank" href="https://maps.google.com/maps/api/geocode/json?address=%22New%20York%22&key='.$data.'">Test your API key</a> if it is correclty configured you should see results array with location data for New York. If no, make sure the key is not restricted to domain</div>';

                    }
            break;
        }

        if ( ! $echo ) {
            return $html;
        }

        echo $html;

    }

    /**
     * Validate form field
     * @param  string $data Submitted value
     * @param  string $type Type of field to validate
     * @return string       Validated value
     */
    public function validate_field ( $data = '', $type = 'text' ) {

        switch( $type ) {
            case 'text': $data = esc_attr( $data ); break;
            case 'url': $data = esc_url( $data ); break;
            case 'email': $data = is_email( $data ); break;
        }

        return $data;
    }

    /**
     * Add meta box to the dashboard
     * @param string $id            Unique ID for metabox
     * @param string $title         Display title of metabox
     * @param array  $post_types    Post types to which this metabox applies
     * @param string $context       Context in which to display this metabox ('advanced' or 'side')
     * @param string $priority      Priority of this metabox ('default', 'low' or 'high')
     * @param array  $callback_args Any axtra arguments that will be passed to the display function for this metabox
     * @return void
     */
    public function add_meta_box ( $id = '', $title = '', $post_types = array(), $context = 'advanced', $priority = 'default', $callback_args = null ) {

        // Get post type(s)
        if ( ! is_array( $post_types ) ) {
            $post_types = array( $post_types );
        }

        // Generate each metabox
        foreach ( $post_types as $post_type ) {
            add_meta_box( $id, $title, array( $this, 'meta_box_content' ), $post_type, $context, $priority, $callback_args );
        }
    }

    /**
     * Display metabox content
     * @param  object $post Post object
     * @param  array  $args Arguments unique to this metabox
     * @return void
     */
    public function meta_box_content ( $post, $args ) {

        $fields = apply_filters( $post->post_type . '_custom_fields', array(), $post->post_type );

        if ( ! is_array( $fields ) || 0 == count( $fields ) ) return;

        echo '<div class="custom-field-panel">' . "\n";

        foreach ( $fields as $field ) {

            if ( ! isset( $field['metabox'] ) ) continue;

            if ( ! is_array( $field['metabox'] ) ) {
                $field['metabox'] = array( $field['metabox'] );
            }

            if ( in_array( $args['id'], $field['metabox'] ) ) {
                $this->display_meta_box_field(  $post, $field );
            }

        }

        echo '</div>' . "\n";

    }

    /**
     * Dispay field in metabox
     * @param  array  $field Field data
     * @param  object $post  Post object
     * @return void
     */
    public function display_meta_box_field (  $post, $field = array() ) {

        if ( ! is_array( $field ) || 0 == count( $field ) ) return;

        $field = '<p class="form-field"><label for="' . $field['id'] . '">' . $field['label'] . '</label>' . $this->display_field( $field, $post, false ) . '</p>' . "\n";

        echo $field;
    }

    /**
     * Save metabox fields
     * @param  integer $post_id Post ID
     * @return void
     */
    public function save_meta_boxes ( $post_id = 0 ) {

        if ( ! $post_id ) return;

        $post_type = get_post_type( $post_id );

        $fields = apply_filters( $post_type . '_custom_fields', array(), $post_type );

        if ( ! is_array( $fields ) || 0 == count( $fields ) ) return;

        foreach ( $fields as $field ) {
            if ( isset( $_REQUEST[ $field['id'] ] ) ) {
                update_post_meta( $post_id, $field['id'], $this->validate_field( $_REQUEST[ $field['id'] ], $field['type'] ) );
            } else {
                update_post_meta( $post_id, $field['id'], '' );
            }
        }
    }

    /**
     * Main WordPress_Plugin_Template_Settings Instance
     *
     * Ensures only one instance of WordPress_Plugin_Template_Settings is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see WordPress_Plugin_Template()
     * @return Main WordPress_Plugin_Template_Settings instance
     */
    public static function instance ( $parent ) {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self( $parent );
        }
        return self::$_instance;
    } // End instance()

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone () {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
    } // End __clone()

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup () {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
    } // End __wakeup()

}

$settings = new Truelysell_Core_Admin( __FILE__ );