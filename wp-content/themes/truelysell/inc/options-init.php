<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "truelysell_theme_options";

    // This line is only for altering the demo. Can be easily removed.

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }
	function truelysell_core_get_post_theme_options( $query_args ) {

        $args = wp_parse_args( $query_args, array(
            'post_type'   => 'post',
            'numberposts' => -1,
        ) );
    
        $posts = get_posts( $args );
    
        $post_options = array();
        $post_options[0] = esc_html__('--Choose page--','truelysell');
        if ( $posts ) {
            foreach ( $posts as $post ) {
              $post_options[ $post->ID ] = $post->post_title;
            }
        }
    
        return $post_options;
    }

    function truelysell_core_get_pages_theme_options() {
        return truelysell_core_get_post_theme_options( array( 'post_type' => 'page', ) );
    }
    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();
    if ( is_dir( $sample_patterns_path ) ) {

        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }
    $theme = wp_get_theme();
    $args = array(
        'opt_name'             => $opt_name,
        'display_name'         => $theme->get( 'Name' ),
        'display_version'      => $theme->get( 'Version' ),
        'menu_type'            => 'menu',
        'allow_sub_menu'       => true,
        'menu_title'           => __( 'Truelysell Options', 'truelysell' ),
        'page_title'           => __( 'Truelysell Options', 'truelysell' ),
        'google_api_key'       => '',
        'google_update_weekly' => false,
        'async_typography'     => false,
        'admin_bar'            => true,
        'admin_bar_icon'       => 'dashicons-portfolio',
        'admin_bar_priority'   => 50,
        'global_variable'      => '',
        'dev_mode'             => false,
        'update_notice'        => true,
        'customizer'           => true,

        'page_priority'        => 100,
        'page_parent'          => 'admin.php',
        'page_permissions'     => 'manage_options',
        'menu_icon'            => '',
        'last_tab'             => '',
        'page_icon'            => 'icon-themes',
        'page_slug'            => 'truelysell_theme_options',
        'save_defaults'        => true,
        'default_show'         => false,
        'default_mark'         => '',
        'show_import_export'   => true,

        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        'output_tag'           => true,

        'database'             => '',
        'use_cdn'              => true,

        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.youtube.com/dreamstechnologies',
        'title' => 'View videos on YouTube',
        'icon'  => 'el el-youtube'
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/dreamstechnologies/',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );

    Redux::setArgs( $opt_name, $args );
    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'truelysell' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'truelysell' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'truelysell' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'truelysell' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'truelysell' );
    Redux::setHelpSidebar( $opt_name, $content );
    

    /*** General Tab ***/

    Redux::setSection( $opt_name, array(
        'title'            => __( 'General', 'truelysell' ),
        'id'               => 'basic',
        'desc'             => __( '', 'truelysell' ),
        'icon'             => 'fa fa-gear',
		'fields'           => array(
				
				// array(
				// 	'id'       => 'frontend_logo',
				// 	'type'     => 'media',
				// 	'url'      => true,
				// 	'title'    => __( 'Logo for the Main Website', 'truelysell' ),
				// 	'compiler' => 'true',
				// 	'default'  => array( 'url' => trailingslashit(get_template_directory_uri()) . 'images/logo-dashboard.svg' ),
				// ),
				 
                array(
                    'id'		=> 'clock_format',
                    'type'		=> 'radio',
                    'title'		=> esc_html__( 'Clock format', 'truelysell' ),
                    'options'	=> array(
                    '12'	=> esc_html__( '12H', 'truelysell' ),
                     ),
                    'default'	=> '12',
                ),

                array(
                    'id'       => 'date_format_separator',
                    'type'     => 'text',
                    'title'    => __( 'Date format separator', 'truelysell' ),
                    'subtitle'     => esc_html__( 'Choose hyphen (-), slash (/), or dot (.)', 'truelysell' ),
                    'default'  => '/',
                ),
            

                // array(
                //     'title'         => __('Commission rate', 'truelysell'),
                //     'desc'          => __('Set commission % for bookings', 'truelysell'),
                //     'id'            => 'commission_rate',
                //     'type'          => 'float',
                //     'placeholder'   => 'Put just a number',
                //     'default'       => '10'
                // ),

                array(
                    'title'       => __('Commission rate', 'truelysell'),
                    'subtitle'        => __('Set commission % for bookings', 'truelysell'),
                    'id'          => 'commission_rate',
                    'type'        => 'spinner',
                    'placeholder' => __('Put just a number', 'truelysell'),
                    'default'     => '10',
                    'min'         => '0',
                    'step'        => '1',
                    'max'         => '100',
                ),
                
                

                array(
                    'id'		=> 'currency',
                    'subtitle'      => __('Choose a currency used.', 'truelysell'),
                    'type'		=> 'select',
                    'title'		=> esc_html__( 'Currency', 'truelysell' ),
                    'options'	=> array(
                    'none' => esc_html__( 'none', 'truelysell' ),
                    'USD'   => esc_html__( 'US Dollars', 'truelysell' ),
                    'AED'   => esc_html__( 'United Arab Emirates Dirham', 'truelysell' ),
                    'ARS' => esc_html__( 'Argentine Peso', 'truelysell' ),
                    'AUD' => esc_html__( 'Australian Dollars', 'truelysell' ),
                            'BDT' => esc_html__( 'Bangladeshi Taka', 'truelysell' ),
                            'BHD' => esc_html__( 'Bahraini Dinar', 'truelysell' ),
                            'BRL' => esc_html__( 'Brazilian Real', 'truelysell' ),
                            'BGN' => esc_html__( 'Bulgarian Lev', 'truelysell' ),
                            'CAD' => esc_html__( 'Canadian Dollars', 'truelysell' ),
                            'CLP' => esc_html__( 'Chilean Peso', 'truelysell' ),
                            'CNY' => esc_html__( 'Chinese Yuan', 'truelysell' ),
                            'COP' => esc_html__( 'Colombian Peso', 'truelysell' ),
                            'CZK' => esc_html__( 'Czech Koruna', 'truelysell' ),
                            'DKK' => esc_html__( 'Danish Krone', 'truelysell' ),
                            'DOP' => esc_html__( 'Dominican Peso', 'truelysell' ),
                            'MAD' => esc_html__( 'Moroccan Dirham', 'truelysell' ),
                            'EUR' => esc_html__( 'Euros', 'truelysell' ),
                            'GHS' => esc_html__( 'Ghanaian Cedi', 'truelysell' ),
                            'HKD' => esc_html__( 'Hong Kong Dollar', 'truelysell' ),
                            'HRK' => esc_html__( 'Croatia kuna', 'truelysell' ),
                            'HUF' => esc_html__( 'Hungarian Forint', 'truelysell' ),
                            'ISK' => esc_html__( 'Icelandic krona', 'truelysell' ),
                            'IDR' => esc_html__( 'Indonesia Rupiah', 'truelysell' ),
                            'INR' => esc_html__( 'Indian Rupee', 'truelysell' ),
                            'NPR' => esc_html__( 'Nepali Rupee', 'truelysell' ),
                            'ILS' => esc_html__( 'Israeli Shekel', 'truelysell' ),
                            'JPY' => esc_html__( 'Japanese Yen', 'truelysell' ),
                            'JOD' => esc_html__( 'Jordanian Dinar', 'truelysell' ),
                            'KZT' => esc_html__( 'Kazakhstani tenge', 'truelysell' ),
                            'KIP' => esc_html__( 'Lao Kip', 'truelysell' ),
                            'KRW' => esc_html__( 'South Korean Won', 'truelysell' ),
                            'LKR' => esc_html__( 'Sri Lankan Rupee', 'truelysell' ),
                            'MYR' => esc_html__( 'Malaysian Ringgits', 'truelysell' ),
                            'MXN' => esc_html__( 'Mexican Peso', 'truelysell' ),
                            'NGN' => esc_html__( 'Nigerian Naira', 'truelysell' ),
                            'NOK' => esc_html__( 'Norwegian Krone', 'truelysell' ),
                            'NZD' => esc_html__( 'New Zealand Dollar', 'truelysell' ),
                            'PYG' => esc_html__( 'Paraguayan Guaraní', 'truelysell' ),
                            'PHP' => esc_html__( 'Philippine Pesos', 'truelysell' ),
                            'PLN' => esc_html__( 'Polish Zloty', 'truelysell' ),
                            'GBP' => esc_html__( 'Pounds Sterling', 'truelysell' ),
                            'RON' => esc_html__( 'Romanian Leu', 'truelysell' ),
                            'RUB' => esc_html__( 'Russian Ruble', 'truelysell' ),
                            'SGD' => esc_html__( 'Singapore Dollar', 'truelysell' ),
                            'SRD' => esc_html__( 'Suriname Dollar', 'truelysell' ),
                            'ZAR' => esc_html__( 'South African rand', 'truelysell' ),
                            'SEK' => esc_html__( 'Swedish Krona', 'truelysell' ),
                            'CHF' => esc_html__( 'Swiss Franc', 'truelysell' ),
                            'TWD' => esc_html__( 'Taiwan New Dollars', 'truelysell' ),
                            'THB' => esc_html__( 'Thai Baht', 'truelysell' ),
                            'TRY' => esc_html__( 'Turkish Lira', 'truelysell' ),
                            'UAH' => esc_html__( 'Ukrainian Hryvnia', 'truelysell' ),
                            'USD' => esc_html__( 'US Dollars', 'truelysell' ),
                            'VND' => esc_html__( 'Vietnamese Dong', 'truelysell' ),
                            'EGP' => esc_html__( 'Egyptian Pound', 'truelysell' ),
                            'ZMK' => esc_html__( 'Zambian Kwacha', 'truelysell' )
                    ),
                    'default'	=> 'USD',
                ),

                array(
                    'title'      => __('Currency position', 'truelysell'),
                    'subtitle'      => __('Set currency symbol before or after', 'truelysell'),
                    'id'        => 'currency_postion',
                    'type'      => 'radio',
                    'options'   => array( 
                            'after' => 'After', 
                            'before' => 'Before' 
                        ),
                    'default'   => 'after'
                ),

                array(
                    'title'      => __('Decimal places for prices', 'truelysell'),
                    'subtitle'      => __('Set Precision of the number of decimal places (for example 4.56$ instead of 5$)', 'truelysell'),
                    'id'        => 'number_decimals',
                    'type'      => 'spinner',
                    'placeholder'      => 'Put just a number',
                    'default'   => '2',
                    'min'         => '0',
                    'step'        => '1', // Allow decimal values
                    'max'         => '100',
                    'validate'    => 'numeric', // Enforce numeric input

                ),
               
                array(
                    'title'      => __('By default sort listings by:', 'truelysell'),
                    'subtitle'      => __('sort by', 'truelysell'),
                    'id'        => 'sort_by',
                    'type'      => 'select',
                    'options'   => array( 
                            'date-asc' => esc_html__( 'Oldest Services', 'truelysell' ),
                            'date-desc' => esc_html__( 'Newest Services', 'truelysell' ),
                            'featured' => esc_html__( 'Featured', 'truelysell' ),
                            'highest-rated' => esc_html__( 'Highest Rated', 'truelysell' ),
                            'reviewed' => esc_html__( 'Most Reviewed Rated', 'truelysell' ),
                            'title' => esc_html__( 'Alphabetically', 'truelysell' ),
                            'views' => esc_html__( 'Views', 'truelysell' ),
                            'rand' => esc_html__( 'Random', 'truelysell' ),
                            'rand' => esc_html__( 'Random', 'truelysell' ),
                        ),
                    'default'   => 'date-desc'
                ),

                array(
                    'title'      => __('Provider contact information visibility', 'truelysell'),
                    'subtitle'      => __('By enabling this option phone and emails fields will be visible only for:', 'truelysell'),
                    'id'        => 'user_contact_details_visibility',
                    'type'      => 'select',
                    'options'   => array( 
                            'show_all' => esc_html__( 'Always show', 'truelysell' ),
                          
                        ),
                    'default'   => 'show_all'
                ),  
                 
				

			)
    ) );

   
    /*** Header Tab ***/
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Header', 'truelysell' ),
        'icon'       => 'fa fa-header',
        'fields'     => array(

            array(
                'title'      => __('Logo ', 'truelysell'),
                'subtitle'      => __('Site logo here', 'truelysell'),
                'id'        => 'logo_image',
                'type'      => 'media',
                 'default'   => ''
            ),

            array(
                'title'      => __('Mobile logo ', 'truelysell'),
                'subtitle'      => __('Site mobile logo here', 'truelysell'),
                'id'        => 'logo_image_mobile',
                'type'      => 'media',
                 'default'   => ''
            ),

            array(
                'title'      => __('Header Style', 'truelysell'),
                'subtitle'      => __('Change header style', 'truelysell'),
                'id'        => 'header_style',
                'type'      => 'select',
                'options'   => array( 
                        'style1' => esc_html__( 'Style 1', 'truelysell' ),
                        'style2' => esc_html__( 'Style 2', 'truelysell' ),
                     ),
                'default'   => 'style1'
            ),

            array(
                'title'      => __('Email', 'truelysell'),
                'subtitle'      => __('Header style 2 only', 'truelysell'),
                'id'        => 'header_email',
                'type'      => 'text',
                'placeholder' => 'truelysell@example.com',
                'default'   => 'truelysell@example.com'
            ),

            array(
                'title'      => __('Phone', 'truelysell'),
                'subtitle'      => __('Header style 2 only', 'truelysell'),
                'id'        => 'header_mobile',
                'type'      => 'text',
                'placeholder' => '(888) 888-8888',
                'default'   => '(888) 888-8888'
            ),

            array(
                'title'      => __('Address', 'truelysell'),
                'subtitle'      => __('Header style 2 only', 'truelysell'),
                'id'        => 'header_address',
                'type'      => 'text',
                'placeholder' => '367 Hillcrest Lane, Irvine, California, United States',
                'default'   => '367 Hillcrest Lane, Irvine, California, United States'
            ),


							        
        ),
     ) );


     /*** Footer Tab ***/
     Redux::setSection( $opt_name, array(
        'title'      => __( 'Footer', 'truelysell' ),
        'icon'       => 'fa fa-sliders-h',
        'fields'     => array(

            array(
                'title'      => __('Footer Style:', 'truelysell'),
                'subtitle'      => __('Change footer style', 'truelysell'),
                'id'        => 'footer_style',
                'type'      => 'select',
                'options'   => array( 
                        'style1' => esc_html__( 'Style 1', 'truelysell' ),
                        'style2' => esc_html__( 'Style 2', 'truelysell' ),
                     ),
                'default'   => 'style1'
            ),
            array(
                'title'      => __('Footer copyright', 'truelysell'),
                'subtitle'      => __('Copyright Text Here', 'truelysell'),
                'id'        => 'copy_right',
                'type'      => 'text',
                'placeholder' => 'Copyright Text Here',
                'default'   => '© 2023 Truelysell. All rights reserved.'
            ),
						        
        ),
        ) );
   
        /*** Submit Service Tab ***/
        Redux::setSection( $opt_name, array(
            'title'      => __( 'Submit Service', 'truelysell' ),
            'icon'       => 'fa fa-plus-square',
            'fields'     => array(
                array(
                    'title'      => __('Disable Bookings module', 'truelysell'),
                    'subtitle'      => __('By default bookings are enabled, check this checkbox to disable it and remove booking options from Submit Service', 'truelysell'),
                    'id'        => 'bookings_disabled',
                    'type'      => 'checkbox',
                ), 
                
                   array(
                    'id'            => 'disable_service_availability',
                    'title'         => __( 'Disable Service Availability', 'truelysell' ),
                    'subtitle'   => __( 'Disable service availability in servcie details page', 'truelysell' ),
                   'type'          => 'checkbox',
                  ), 
    
               
                array(
                    'title'      => __('Admin approval required for new services', 'truelysell'),
                    'subtitle'      => __('Require admin approval for any new services added', 'truelysell'),
                    'id'        => 'new_listing_requires_approval',
                    'type'      => 'checkbox',
                ),    

                array(
                    'title'      => __('Admin approval required for editing service', 'truelysell'),
                    'subtitle'      => __('Require admin approval for any edited service', 'truelysell'),
                    'id'        => 'edit_listing_requires_approval',
                    'type'      => 'checkbox',
                ),          
                array(
                    'title'      => __('Notify admin by email about new service waiting for approval', 'truelysell'),
                    'subtitle'      => __('Send email about any new services added', 'truelysell'),
                    'id'        => 'new_listing_admin_notification',
                    'type'      => 'checkbox',
                ),       
                
                array(
                    'title' => __('Service duration', 'truelysell'),
                    'subtitle' => __('Set default listing duration. Set to 0 if you don\'t want listings to have an expiration date.', 'truelysell'),
                    'id'   => 'default_duration', //field id must be unique
                    'type' => 'text',
                    'default' => '30',
                ),

                array(
                    'title' => __( 'Service images upload limit', 'truelysell' ),
                    'subtitle' => __( 'Number of images that can be uploaded to one service', 'truelysell' ),
                    'id'   => 'max_files', //field id must be unique
                    'type' => 'text',
                    'default' => '10',    
                ),   
                array(
                    'title' => __( 'Service image maximum size (in MB)', 'truelysell' ),
                    'subtitle' => __( 'Maximum file size to upload ', 'truelysell' ),
                    'id'   => 'max_filesize', //field id must be unique
                    'type' => 'text',
                    'default' => '2',    
                ),  
    
    
    
            ),
            ) );

            /*** Packages Options ***/

            Redux::setSection( $opt_name, array(
                'title'      => __( 'Packages Options', 'truelysell' ),
                'icon'       => 'fa fa-cubes',
                'fields'     => array(
        
                    array(
                        'title'      => __('Paid listings', 'truelysell'),
                        'subtitle'      => __('Adding listings by users will require purchasing a Listing Package', 'truelysell'),
                        'id'        => 'new_listing_requires_purchase',
                        'type'      => 'checkbox',
                    ),
                    // array(
                    //     'title'         => __('Allow packages to only be purchased once per client', 'truelysell'),
                    //     'subtitle'   => __('Selected packages can be bought only once, useful for demo/free packages', 'truelysell'),
                    //     'id'            => 'buy_only_once',
                    //     'type'          => 'checkbox',
                    //     // 'options'       => truelysell_core_get_listing_packages_as_options(),
                    //     //'options'       => array( 'linux' => 'Linux', 'mac' => 'Mac', 'windows' => 'Windows' ),
                    //     'default'       => array()
                    // ),
                    array(
                        'id'            => 'listing_packages_options',
                        'title'         => __('Check module to disable it in Submit Listing form if you want to make them available only in packages', 'truelysell'),
                        'subtitle'   => __('If you want to use packages with ', 'truelysell'),
                        'type'          => 'checkbox',
                        'options'       => array(
                           /// 'option_booking' => esc_html__('Booking Module', 'truelysell'),
                            'option_reviews' => esc_html__('Reviews Module', 'truelysell'),
                            'option_gallery' => esc_html__('Gallery Module', 'truelysell'),
                           /// 'option_social_links' => esc_html__('Social Links Module', 'truelysell'),
                            'option_opening_hours' => esc_html__('Opening Hours Module', 'truelysell'),
                            'option_video' => esc_html__('Video Module', 'truelysell'),
                            ///'option_coupons' => esc_html__('Coupons Module', 'truelysell'),
                        ), //service
    
    
                    ),
                    array(
                        'title'      => __('Show extra package options automatically in pricing table', 'truelysell'),
    
                        'id'        => 'populate_listing_package_options',
                        'type'      => 'checkbox',
                    ),
        
        
        
                ),
                ) );

                 /*** Single Service ***/
                Redux::setSection( $opt_name, array(
                    'title'      => __( 'Single Service', 'truelysell' ),
                    'icon'       => 'fa fa-file',
                    'fields'     => array(
            
                        array(
                            'id'            => 'gallery_type',
                            'title'         => __( 'Default Gallery Type', 'truelysell' ),
                            'type'          => 'select',
                            'options'       => array( 
                                    'top'       => __('Gallery on top (requires minimum 4 photos)', 'truelysell' )
                            ),
                            'default'       => 'top'
                        ),
                        
                        array(
                            'id'            => 'owners_can_review',
                            'title'         => __( 'Allow providers to add reviews', 'truelysell' ),
                            'type'          => 'checkbox',
                        ),
                        array(
                            'id'            => 'disable_reviews',
                            'title'         => __( 'Disable reviews on services', 'truelysell' ),
                            'type'          => 'checkbox',
                        )
            
                    ),
                    ) );

                     /*** Booking ***/
                    Redux::setSection( $opt_name, array(
                        'title'      => __( 'Booking', 'truelysell' ),
                        'icon'       => 'fa fa-calendar-alt',
                        'fields'     => array(
                
                            array(
                                'id'            => 'instant_booking_require_payment',
                                'title'         => __( 'For "online payment option" require payment first to confirm the booking', 'truelysell' ),
                                'subtitle'   => __( 'Users will have to pay for booking immediately to confirm the booking.', 'truelysell' ),
                               'type'          => 'checkbox',
                            ),  

                              array(
                                'id'            => 'booking_email_required',
                                'title'         => __('Make Email field required in booking confirmation form', 'truelysell'),
                                'type'          => 'checkbox',
            
                            ),   
                            
                            array(
                                'id'            => 'booking_phone_required',
                                'title'         => __('Make Phone field required in booking confirmation form', 'truelysell'),
                                'type'          => 'checkbox',
            
                            ),
            
                            array(
                                'id'            => 'add_address_fields_booking_form',
                                'title'         => __('Add address field to booking confirmation form', 'truelysell'),
                                'type'          => 'checkbox',
                                'subtitle'   => __('Used in WooCommerce Orders and required for some payment gateways ', 'truelysell'),
                            ),
            
                            array(
                                'id'            => 'show_expired',
                                'title'         => __( 'Show Expired Bookings in Dashboard page', 'truelysell' ),
                                'subtitle'   => __( 'Adds "Expired" subpage to Bookings page in provider Dashboard, with list of expired bookings ', 'truelysell' ),
                                'type'          => 'checkbox',
                            ),  
                            array(
                                'id'            => 'default_booking_expiration_time',
                                'title'         => __( 'Set how long booking will be waiting for payment before expiring', 'truelysell' ),
                                'subtitle'   => __( 'Default is 48 hours, set to 0 to disable', 'truelysell' ),
                                'type'          => 'text',
                                'default'       => '48',
                            ), 
                
                        ),
                        ) );

                        /*** Browse/Search Options ***/

                        Redux::setSection( $opt_name, array(
                            'title'      => __( 'Browse/Search Options', 'truelysell' ),
                            'icon'       => 'fa fa-search-location',
                            'fields'     => array(
                    
                                array(
                                    'id'            => 'ajax_browsing',
                                    'title'         => __( 'Ajax based service browsing', 'truelysell' ),
                                    'subtitle'   => __( '.', 'truelysell' ),
                                    'type'          => 'select',
                                    'options'       => array( 
                                            'on'    => __('Enabled', 'truelysell' ),
                                            'off'   => __('Disabled', 'truelysell' ),  
                                    ),
                                    'default'       => 'on'
                                )
                    
                    
                    
                            ),
                            ) );

                            /*** Registration ***/

	Redux::setSection( $opt_name, array(
        'title'      => __( 'Registration', 'truelysell' ),
        'id'         => 'register_page',
        'icon'       => 'fa fa-user-friends',
        'fields'     => array( 

            array(
                'id'            => 'front_end_login',
                'title'         => __( 'Enable Forced Front End Login', 'truelysell' ),
                'subtitle'   => __( 'Enabling this option will redirect all wp-login request to frontend form. Be aware that on some servers or some configuration, especially with security plugins, this might cause a redirect loop, so always test this setting on different browser, while being still logged in Dashboard to have option to disable that if things go wrong.', 'truelysell' ),
                'type'          => 'checkbox',
                'default'   => 'true',
                
            ),
            array(
                'id'            => 'popup_login',
                'title'         => __( 'Login/Registration Form Type', 'truelysell' ),
                'subtitle'   => __( '.', 'truelysell' ),
                'type'          => 'select',
                'options'       => array( 
                        'page'   => __('Separate page', 'truelysell' ), 
                ),
                'default'       => 'page'
            ),
             array(
                'id'            => 'autologin',
                'title'         => __( 'Automatically login user after successful registration', 'truelysell' ),
                'subtitle'   => __( '.', 'truelysell' ),
                'type'          => 'checkbox',
            ),
           
            array(
                'id'            => 'registration_form_default_role',
                'title'         => __( 'Set default role for Registration Form', 'truelysell' ),
                'subtitle'   => __( 'If you set it hidden, set default role in Settings -> General -> New User Default Role', 'truelysell' ),
                'type'          => 'select',
                'default'       => 'guest',
                'options'       => array(
                    'owner' => esc_html__('Owner','truelysell'), 
                    'guest' => esc_html__('Guest','truelysell'), 
                ),
            ),
          
            array(
                'id'            => 'registration_hide_username',
                'title'         => __( 'Hide Username field in Registration Form', 'truelysell' ),
                'subtitle'   => __( 'Username will be generated from email address (part before @)', 'truelysell' ),
                'type'          => 'checkbox',
            ),
           
            array(
                'id'            => 'display_first_last_name',
                'title'         => __( 'Display First and Last name fields in registration form', 'truelysell' ),
                'subtitle'   => __( 'Adds optional input fields for first and last name', 'truelysell' ),
                'type'          => 'checkbox',
            ), 
            array(
                'id'            => 'display_first_last_name_required',
                'title'         => __( 'Make First and Last name fields required', 'truelysell' ),
                'subtitle'   => __( 'Enable to make those fields required', 'truelysell' ),
                'type'          => 'checkbox',
                
            ),
            array(
                'id'            => 'display_password_field',
                'title'         => __('Add Password pickup field to registration form', 'truelysell'),
                'subtitle'   => __('Enable to add password field, when disabled it will be randomly generated and sent via email', 'truelysell'),
                'type'          => 'checkbox',
            ),
          
              array(
                'id'            => 'owner_registration_redirect',
                'options'       => truelysell_core_get_pages_theme_options(),
                'title'         => __( 'Provider redirect after registration to page' , 'truelysell' ),
                'type'          => 'select',
                
            ),
            array(
                'id'            => 'owner_login_redirect',
                'options'       => truelysell_core_get_pages_theme_options(),
                'title'         => __( 'Provider  redirect after login to page' , 'truelysell' ),
                'type'          => 'select',
            ),  
            array(
                'id'            => 'guest_registration_redirect',
                'options'       => truelysell_core_get_pages_theme_options(),
                'title'         => __( 'Customer redirect after registration to page' , 'truelysell' ),
                'type'          => 'select',
            ),
            array(
                'id'            => 'guest_login_redirect',
                'options'       => truelysell_core_get_pages_theme_options(),
                'title'         => __( 'Customer redirect after login to page' , 'truelysell' ),
                'type'          => 'select',
            )
        ),
     ) );


     /*** PayPal Payout ***/
     Redux::setSection( $opt_name, array(
        'title'      => __( 'PayPal Payout', 'truelysell' ),
        'icon'       => 'fa fa-paypal',
        'fields'     => array(

            array(
                'title'      => __('Activate / Deactivate PayOut feature', 'truelysell'),
                'subtitle'      => __('Activate/Deactivate PayPal Payout feature', 'truelysell'),
                'id'        => 'payout_activation', //each field id must be unique
                'type'      => 'select',
                'options'   => array(
                    'no' => esc_html__( 'Deactivate', 'truelysell' ),
                    'yes' => esc_html__( 'Activate', 'truelysell' )
                ),
                'default'       => 'no'
            ),

            array(
                'title'      => __('Live/Sandbox', 'truelysell'),
                'subtitle'   => __('Select the Environment', 'truelysell'),
                'id'         => 'payout_environment',
                'type'       => 'select',
                'options'    => array(
                    'sandbox' => esc_html__('Sandbox / Testing', 'truelysell'),
                    'live'    => esc_html__('Live / Production', 'truelysell')
                ),
                'default'    => 'sandbox'
            ),
           
            // Sandbox fields
            array(
                'title'      => __('PayPal Client ID for Sandbox', 'truelysell'),
                'id'         => 'payout_sandbox_client_id',
                'type'       => 'text',
                'subtitle'   => __('PayPal Client ID for Sandbox', 'truelysell'),
                'required' => array('payout_environment','equals','sandbox')

            ),
            array(
                'title'      => __('PayPal Client Secret for Sandbox', 'truelysell'),
                'id'         => 'payout_sandbox_client_secret',
                'type'       => 'password',
                'subtitle'   => __('PayPal Client Secret for Sandbox', 'truelysell'),
                'placeholder'=> __('PayPal Client Secret for Sandbox', 'truelysell'),
                'required' => array('payout_environment','equals','sandbox')
            ),
            // Live fields
            array(
                'title'      => __('PayPal Client ID for Live', 'truelysell'),
                'id'         => 'payout_live_client_id',
                'type'       => 'text',
                'subtitle'   => __('PayPal Client ID for Production / Live Environment', 'truelysell'),
                'required' => array('payout_environment','equals','live')
            ),
            array(
                'title'      => __('PayPal Client Secret for Live', 'truelysell'),
                'id'         => 'payout_live_client_secret',
                'type'       => 'password',
                'subtitle'   => __('PayPal Client Secret for Production / Live Environment', 'truelysell'),
                'required' => array('payout_environment','equals','live')
            ),
            
            

            array(
                'title'      => __('Email Subject', 'truelysell'),
                'subtitle'      => __('Default Email Subject', 'truelysell'),
                'id'        => 'payout_email_subject', //each field id must be unique
                'type'      => 'textarea',
                'default'   => 'Here is your commission.'
            ),
            array(
                'title'      => __('Email Message', 'truelysell'),
                'subtitle'      => __('Default Email Message', 'truelysell'),
                'id'        => 'payout_email_message', //each field id must be unique
                'type'      => 'textarea',
                'default'   => 'You have received a payout (commission)! Thanks for using our listing!'
            ),
            array(
                'title'      => __('Transaction Note', 'truelysell'),
                'subtitle'      => __('Any note that you want to add', 'truelysell'),
                'id'        => 'payout_trx_note', //each field id must be unique
                'type'      => 'textarea',
                'default'   => ''
            ),
        ),
        ) );

 /*** Pages ***/
 Redux::setSection( $opt_name, array(
    'title'      => __( 'Pages', 'truelysell' ),
    'icon'       => 'fa fa-layer-group',
    'fields'     => array(

        array(
            'id'            => 'dashboard_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'Dashboard Page' , 'truelysell' ),
            'subtitle'   => __( 'Main Dashboard page for user, content: [truelysell_dashboard]', 'truelysell' ),
            'type'          => 'select',
        ),
        array(
            'id'            => 'messages_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'Messages Page' , 'truelysell' ),
            'subtitle'   => __( 'Main page for user messages, content: [truelysell_messages]', 'truelysell' ),
            'type'          => 'select',
        ),
        array(
            'id'            => 'bookings_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'Bookings Page' , 'truelysell' ),
            'subtitle'   => __( 'Page for owners to manage their bookings, content: [truelysell_bookings]', 'truelysell' ),
            'type'          => 'select',
        ),  
        array(
            'id'            => 'user_bookings_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'My Bookings Page' , 'truelysell' ),
            'subtitle'   => __( 'Page for guest to see their bookings,content: [truelysell_my_bookings]', 'truelysell' ),
            'type'          => 'select',
        ), 
        array(
            'id'            => 'booking_confirmation_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'Booking confirmation' , 'truelysell' ),
            'subtitle'   => __( 'Displays page for booking confirmation, content: [truelysell_booking_confirmation]', 'truelysell' ),
            'type'          => 'select',
        ), 
        array(
            'id'            => 'listings_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'My Services Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays or listings added by user, content [truelysell_my_listings]', 'truelysell' ),
            'type'          => 'select',
        ),    
        array(
            'id'            => 'wallet_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'Wallet Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays or owners earnings, content [truelysell_wallet]', 'truelysell' ),
            'type'          => 'select',
        ), 
        array(
            'id'            => 'payout_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'Payout Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays or payout history, content [truelysell_payout]', 'truelysell' ),
            'type'          => 'select',
        ),                  
        array(
            'id'            => 'reviews_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'Reviews Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays reviews of user listings, content: [truelysell_reviews]', 'truelysell' ),
            'type'          => 'select',
        ),                
        array(
            'id'            => 'bookmarks_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'Bookmarks Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays user bookmarks, content: [truelysell_bookmarks]', 'truelysell' ),
            'type'          => 'select',
        ),
        array(
            'id'            => 'submit_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'Submit Service Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays submit listing page, content: [truelysell_submit_listing]', 'truelysell' ),
            'type'          => 'select',
        ),                
        array(
            'id'            => 'profile_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'My Profile Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays user profile page, content: [truelysell_my_account]', 'truelysell' ),
            'type'          => 'select',
        ),

        array(
            'id'            => 'notification_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'My Notification Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays notification page, content: [truelysell_allnotification]', 'truelysell' ),
            'type'          => 'select',
        ),

        array(
            'id'            => 'login_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'My Login Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays login page', 'truelysell' ),
            'type'          => 'select',
        ),

        array(
            'id'            => 'register_page',
            'options'       => truelysell_core_get_pages_theme_options(),
            'title'         => __( 'My Register Page' , 'truelysell' ),
            'subtitle'   => __( 'Displays user register page', 'truelysell' ),
            'type'          => 'select',
        ),
            
        array(
            'title'          => __('Lost Password Page', 'truelysell'),
            'subtitle'          => __('Select page that holds [truelysell_lost_password] shortcode', 'truelysell'),
            'id'            =>  'lost_password_page',
            'type'          => 'select',
            'options'       => truelysell_core_get_pages_theme_options(),
        ), 
                   
        array(
            'title'          => __('Reset Password Page', 'truelysell'),
            'subtitle'          => __('Select page that holds [truelysell_reset_password] shortcode', 'truelysell'),
            'id'            =>  'reset_password_page',
            'type'          => 'select',
            'options'       => truelysell_core_get_pages_theme_options(),
        ),


    ),
    ) );


    /*** Emails ***/
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Emails', 'truelysell' ),
        'icon'       => 'fa fa-envelope',
        'fields'     => array(


            array(
                'title'  => __('"From name" in email', 'truelysell'),
                'subtitle'  => __('The name from who the email is received, by default it is your site name.', 'truelysell'),
                'id'    => 'emails_name',
                'default' =>  get_bloginfo( 'name' ),                
                'type'  => 'text',
            ),

            array(
                'title'  => __('"From" email ', 'truelysell'),
                'subtitle'  => __('This will act as the "from" and "reply-to" address. This emails should match your domain address', 'truelysell'),
                'id'    => 'emails_from_email',
                'default' =>  get_bloginfo( 'admin_email' ),               
                'type'  => 'text',
            ),
            array(
                'id'            => 'email_logo',
                'title'         => __( 'Logo for emails' , 'truelysell' ),
                'subtitle'   => __( 'Set here logo for emails, if nothing is set emails will be using default site logo', 'truelysell' ),
                'type'          => 'media',
                'default'       => '',
                'placeholder'   => ''
            ),
            
            array(
                'title' => __('<span style="font-size: 20px;">Registration/Welcome email for new users</span>', 'truelysell'),
                
                'type' => 'info',
                'id'   => 'header_welcome',
                'desc' => ''.__('Available tags are: ').'<strong>{user_mail}, {user_name}, {site_name}, {password}, {login}</strong>',
            ),
            array(
                'title'      => __('Disable Welcome email to user (enabled by default)', 'truelysell'),
                'subtitle'      => __('Check this checkbox to disable sending emails to new users', 'truelysell'),
                'id'        => 'welcome_email_disable',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Welcome Email Subject', 'truelysell'),
                'default'      => __('Welcome to {site_name}', 'truelysell'),
                'id'        => 'listing_welcome_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Welcome Email Content', 'truelysell'),
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
                
                'title' =>  __('<span style="font-size: 20px;">Service Published notification email</span>', 'truelysell'),
                // 'subtitle' => '<span style="font-size: 16px;">This is some information.</span>',

                'type' => 'info',
                'id'   => 'header_published'
            ), 
            array(
                'title'      => __('Enable service published notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to listing authors', 'truelysell'),
                'id'        => 'listing_published_email',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Published notification Email Subject', 'truelysell'),
                'default'      => __('Your listing was published - {listing_name}', 'truelysell'),
                'id'        => 'listing_published_email_subject',
                'type'      => 'text',

            ),
             array(
                'title'      => __('Published notification Email Content', 'truelysell'),
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
              
                'title'      =>  __('<span style="font-size: 20px;">New service notification email</span>', 'truelysell'),
                'type'      => 'info',
                'id'        => 'header_new'
            ), 
            array(
                'title'      => __('Enable new listing notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to listing authors', 'truelysell'),
                'id'        => 'listing_new_email',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('New service notification email subject', 'truelysell'),
                'default'      => __('Thank you for adding a listing', 'truelysell'),
                'id'        => 'listing_new_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('New service notification email content', 'truelysell'),
                'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                Thank you for submitting your listing '{listing_name}'.<br>
                <br>")),
                'id'        => 'listing_new_email_content',
                'type'      => 'editor',
            ),  

            /*----------------*/
            array(
               
                'title' =>  __('<span style="font-size: 20px;">Expired service notification email</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_expired'
            ), 
            array(
                'title'      => __('Enable expired service notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to listing authors', 'truelysell'),
                'id'        => 'listing_expired_email',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Expired service notification email subject', 'truelysell'),
                'default'      => __('Your listing has expired - {listing_name}', 'truelysell'),
                'id'        => 'listing_expired_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Expired service notification email content', 'truelysell'),
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
             
                'title' =>  __('<span style="font-size: 20px;">Expiring service in next 5 days notification email</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_expiring_soon'
            ), 
            array(
                'title'      => __('Enable Expiring soon service notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to service authors', 'truelysell'),
                'id'        => 'listing_expiring_soon_email',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Expiring soon service notification email subject', 'truelysell'),
                'default'      => __('Your service is expiring in 5 days - {listing_name}', 'truelysell'),
                'id'        => 'listing_expiring_soon_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Expiring soon service notification email content', 'truelysell'),
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
               
                'title' =>  __('<span style="font-size: 20px;">Booking confirmation to user (paid - not instant booking)</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_booking_confirmation'
            ), 
            array(
                'title'      => __('Enable Booking confirmation notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to users after they request booking', 'truelysell'),
                'id'        => 'booking_user_waiting_approval_email',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Booking confirmation notification email subject', 'truelysell'),
                'default'      => __('Thank you for your booking - {listing_name}', 'truelysell'),
                'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                    ,{dates},{user_message},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                'id'        => 'booking_user_waiting_approval_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Booking confirmation notification email content', 'truelysell'),
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
                
                'title' =>  __('<span style="font-size: 20px;">Instant Booking confirmation to user</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_instant_booking_confirmation'
            ), 
            array(
                'title'      => __('Enable Instant Booking confirmation notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to users after they request booking', 'truelysell'),
                'id'        => 'instant_booking_user_waiting_approval_email',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Instant Booking confirmation notification email subject', 'truelysell'),
                'default'      => __('Thank you for your booking - {listing_name}', 'truelysell'),
                'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                    {payment_url},{expiration},{dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                'id'        => 'instant_booking_user_waiting_approval_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Instant Booking confirmation notification email content', 'truelysell'),
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
           
                'title' =>  __('<span style="font-size: 20px;">New booking request notification to owner</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_booking_notification_owner'
            ), 
            array(
                'title'      => __('Enable Booking request notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to owners when new booking was requested', 'truelysell'),
                'id'        => 'booking_owner_new_booking_email',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Booking request notification email subject', 'truelysell'),
                'default'      => __('There is a new booking request for {listing_name}', 'truelysell'),
                'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                   {dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                'id'        => 'booking_owner_new_booking_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Booking request notification email content', 'truelysell'),
                'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                There's a new booking request on '{listing_name}' for {dates}. Go to your Bookings Dashboard to accept or reject it.<br>
                <br>
                Thank you
                <br>")),
                'id'        => 'booking_owner_new_booking_email_content',
                'type'      => 'editor',
            ),   


            array(
                
                'title' =>  __('<span style="font-size: 20px;">New Instant booking notification to owner</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_instant_booking_notification_owner'
            ), 
            array(
                'title'      => __('Enable Instant Booking notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to owners when new instant booking was made', 'truelysell'),
                'id'        => 'booking_instant_owner_new_booking_email',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Instant Booking notification email subject', 'truelysell'),
                'default'      => __('There is a new instant booking for {listing_name}', 'truelysell'),
                'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                    {payment_url},{expiration},{dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                'id'        => 'booking_instant_owner_new_booking_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Instant Booking notification email content', 'truelysell'),
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
              
                'title' =>  __('<span style="font-size: 20px;">Free booking confirmation to user</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_free_booking_notification_user'
            ), 
            array(
                'title'      => __('Enable Booking confirmation notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to users when booking was accepted by owner', 'truelysell'),
                'id'        => 'free_booking_confirmation',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Booking request notification email subject', 'truelysell'),
                'default'      => __('Your booking request was approved {listing_name}', 'truelysell'),
                 'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                    {payment_url},{expiration},{dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                'id'        => 'free_booking_confirmation_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Booking request notification email content', 'truelysell'),
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
              
                'title' =>  __('<span style="font-size: 20px;">Booking confirmation to user, pay in cash only</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_cash_booking_notification_user'
            ), 
            array(
                'title'      => __('Enable Booking pay in cash confirmation notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to users when booking was accepted by owner and requires payment in cash', 'truelysell'),
                'id'        => 'mail_to_user_pay_cash_confirmed',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Booking confirmation "pay with cash" notification email subject', 'truelysell'),
                'default'      => __('Your booking request was approved {listing_name}', 'truelysell'),
                 'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},
                    {payment_url},{expiration},{dates},{children},{adults},{user_message},{tickets},{listing},{details},{client_first_name},{client_last_name},{client_email},{client_phone},{billing_address},{billing_postcode},{billing_city},{billing_country},{price}',
                'id'        => 'mail_to_user_pay_cash_confirmed_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Booking confirmation "pay with cash" notification email content', 'truelysell'),
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
         
                'title' =>  __('<span style="font-size: 20px;">Booking approved - payment needed email to user</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_pay_booking_notification_owner'
            ), 
            array(
                'title'      => __('Enable Booking confirmation notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to users when booking was accepted by owner and they need to pay', 'truelysell'),
                'id'        => 'pay_booking_confirmation_user',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Booking request notification email subject', 'truelysell'),
                'default'      => __('Your booking request was approved {listing_name}, please pay', 'truelysell'),
                 'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},{payment_url},{expiration}',
                'id'        => 'pay_booking_confirmation_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Booking request notification email content', 'truelysell'),
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
             
                'title' =>  __('<span style="font-size: 20px;">Booking paid notification  to owner</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_pay_booking_confirmation_owner'
            ), 
            array(
                'title'      => __('Enable Booking paid confirmation notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to owner when booking was paid by use', 'truelysell'),
                'id'        => 'paid_booking_confirmation',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Booking paid notification email subject', 'truelysell'),
                'default'      => __('Your booking was paid by user - {listing_name}', 'truelysell'),
                'id'        => 'paid_booking_confirmation_email_subject',
                'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},{payment_url},{expiration}',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Booking paid notification email content', 'truelysell'),
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
             
                'title' =>  __('<span style="font-size: 20px;">Booking paid confirmation to user</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_pay_booking_confirmation_user'
            ), 
            array(
                'title'      => __('Enable Booking paid confirmation email to user', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to user with confirmation of payment', 'truelysell'),
                'id'        => 'user_paid_booking_confirmation',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Booking paid confirmation email subject', 'truelysell'),
                'default'      => __('Your booking was paid {listing_name}', 'truelysell'),
                'id'        => 'user_paid_booking_confirmation_email_subject',
                'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details},{payment_url},{expiration}',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Booking paid confirmation email content', 'truelysell'),
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
          
                'title' =>  __('<span style="font-size: 20px;">Booking cancelled notification to user</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_booking_cancellation_user'
            ), 
            array(
                'title'      => __('Enable Booking cancellation notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to user when booking is cancelled', 'truelysell'),
                'id'        => 'booking_user_cancallation_email',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('Booking cancelled notification email subject', 'truelysell'),
                'default'      => __('Your booking request for {listing_name} was cancelled', 'truelysell'),
                'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{booking_date},{listing_name},{listing_url},{listing_address},{listing_phone},{listing_email},{site_name},{site_url},{dates},{details}',
                'id'        => 'booking_user_cancellation_email_subject',
                'type'      => 'text',
            ),
             array(
                'title'      => __('Booking cancelled notification email content', 'truelysell'),
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
         
                'title' =>  __('<span style="font-size: 20px;">Email notification about new conversation</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_new_converstation'
            ), 
            array(
                'title'      => __('Enable new conversation notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to user when there was new conversation started', 'truelysell'),
                'id'        => 'new_conversation_notification',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('New conversation notification email subject', 'truelysell'),
                'default'      => __('You got new conversation', 'truelysell'),
                'id'        => 'new_conversation_notification_email_subject',
                'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{sender},{conversation_url},{site_name},{site_url}',
                'type'      => 'text',
            ),
             array(
                'title'      => __('New conversation notification email content', 'truelysell'),
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
         
                'title' =>  __('<span style="font-size: 20px;">Email notification about new message</span>', 'truelysell'),
                'type' => 'info',
                'id'   => 'header_new_message'
            ), 
            array(
                'title'      => __('Enable new message notification email', 'truelysell'),
                'subtitle'      => __('Check this checkbox to enable sending emails to user when there was new message send', 'truelysell'),
                'id'        => 'new_message_notification',
                'type'      => 'checkbox',
            ), 
            array(
                'title'      => __('New message notification email subject', 'truelysell'),
                'default'      => __('You got new message', 'truelysell'),
                'id'        => 'new_message_notification_email_subject',
                'subtitle' => '<br>'.__('Available tags are:').'{user_mail},{user_name},{listing_name},{listing_url},{listing_address},{sender},{conversation_url},{site_name},{site_url}',
                'type'      => 'text',
            ),
             array(
                'title'      => __('New message notification email content', 'truelysell'),
                'default'      => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
                There's a new message waiting for your on {site_name}.<br>
                <br>
                Thank you
                <br>")),
                'id'        => 'new_message_notification_email_content',
                'type'      => 'editor',
            ),  


        ),
        ) );
	
 
    // -> START PAGES LINKS
    // Redux::setSection( $opt_name, array(
    //     'title'  => __( 'Page Links', 'truelysell' ),
    //     'id'     => 'page_links',
    //     'icon'   => 'el el-link',
    //     'fields' => array(
    //                     array(
    //                         'id' => 'my_dashboard_page',
    //                         'type' => 'select',
    //                         'data' => 'pages',
    //                         'multi' => false,
    //                         'title' => esc_html__('Dashboard Page', 'truelysell'),
    //                         'desc'     => __( 'Select page that holds [truelysell_dashboard] shortcode', 'truelysell'),
    //                     ),
    //                     array(
    //                         'id' => 'my_profile_page',
    //                         'type' => 'select',
    //                         'data' => 'pages',
    //                         'multi' => false,
    //                         'title' => esc_html__('My Profile Page', 'truelysell'),
    //                         'desc'     => __( 'Select page that holds [truelysell_my_account] shortcode', 'truelysell'),
    //                     ),
	// 					array(
	// 						'id' => 'add_listing_page',
	// 						'type' => 'select',
	// 						'data' => 'pages',
	// 						'multi' => false,
	// 						'title' => esc_html__('Add Listing Page', 'truelysell'),
    //                         'desc'     => __( 'Select page that holds [truelysell_submit_listing] shortcode', 'truelysell'),
	// 					),

    //                     array(
	// 						'id' => 'my_listing_page',
	// 						'type' => 'select',
	// 						'data' => 'pages',
	// 						'multi' => false,
	// 						'title' => esc_html__('My Listing Page', 'truelysell'),
    //                         'desc'     => __( 'Select page that holds [truelysell_my_listings] shortcode', 'truelysell'),
	// 					),

    //                     array(
	// 						'id' => 'my_favourites_page',
	// 						'type' => 'select',
	// 						'data' => 'pages',
	// 						'multi' => false,
	// 						'title' => esc_html__('My Favourites Page', 'truelysell'),
    //                         'desc'     => __( 'Select page that holds [truelysell_bookmarks] shortcode', 'truelysell'),
	// 					),
    //                     array(
	// 						'id' => 'my_messages_page',
	// 						'type' => 'select',
	// 						'data' => 'pages',
	// 						'multi' => false,
	// 						'title' => esc_html__('My Messages Page', 'truelysell'),
    //                         'desc'     => __( 'Select page that holds [truelysell_messages] shortcode', 'truelysell'),
	// 					),
    //                     array(
	// 						'id' => 'my_reviews_page',
	// 						'type' => 'select',
	// 						'data' => 'pages',
	// 						'multi' => false,
	// 						'title' => esc_html__('My Reviews Page', 'truelysell'),
    //                         'desc'     => __( 'Select page that holds [truelysell_reviews] shortcode', 'truelysell'),
	// 					),
                        
            			
						
	// 					array(
	// 						'id' => 'terms_condition_page',
	// 						'type' => 'select',
	// 						'data' => 'pages',
	// 						'multi' => false,
	// 						'title' => esc_html__('Terms and Condition page', 'truelysell'),
	// 					),
						
						 
                        
			
    //     )
    // ) );
	// -> START PAGES LINKS


   
    
    // Redux::setSection( $opt_name, array(
    //     'title'            => __( 'Footer Options', 'truelysell' ),
    //     'id'               => 'footers',
    //     'customizer_width' => '500px',
    //     'icon'             => 'el el-edit',
	// 	'fields'     => array(
    //                         array(
    //                             'id' => 'footerareone_image',
    //                             'type' => 'media',
    //                             'title' => __('Footerareone Image', 'truelysell'),
    //                             'compiler' => 'true',
	// 							'desc' => esc_html__('Upload footer logo of the website.', 'truelysell'),
	// 							'default' =>array( 'url' => trailingslashit( get_template_directory_uri () ) . 'images/logo.svg' ),
    //                         ),
                            
	// 						array(
	// 							'id' => 'footerareone_content',
	// 							'type' => 'textarea',
	// 							'title' => __('Footerareone Description', 'truelysell')
	// 						),
    //                         array(
	// 							'id' => 'footerareafour_title',
	// 							'type' => 'text',
	// 							'title' => __('Footerarefour Title', 'truelysell')
	// 						),
    //                         array(
	// 							'id' => 'footerareafour_email',
	// 							'type' => 'text',
	// 							'title' => __('Footerarefour Email', 'truelysell')
	// 						),
    //                         array(
	// 							'id' => 'footerareafour_phone',
	// 							'type' => 'text',
	// 							'title' => __('Footerarefour Phone', 'truelysell')
	// 						),
                           
                             
    //                         array(
	// 							'id' => 'footerareabottom_areafour',
	// 							'type' => 'text',
	// 							'title' => __('Footerbottomarea Four Text', 'truelysell')
	// 						),
	// 						array(
	// 							'id' => 'footer_page_links',
	// 							'type' => 'select',
	// 							'title' => __('Footer privacy pages', 'truelysell'),
	// 							'multi' => true,
	// 							'sortable' => true,
	// 							'data' => 'pages',
	// 						),
    //                         array(
	// 							'id' => 'footer_copyright_text',
	// 							'type' => 'textarea',
	// 							'title' => __('Footercopyright', 'truelysell')
	// 						),
    //                         array(
	// 							'id' => 'footernews_title',
	// 							'type' => 'text',
	// 							'title' => __('FooterNewsletter Title', 'truelysell')
	// 						),
    //                         array(
	// 							'id' => 'footernews_desc',
	// 							'type' => 'textarea',
	// 							'title' => __('FooterNewsletter Description', 'truelysell')
	// 						),
	// 						array(
	// 								'id'       => 'footer-section-end',
	// 								'type'     => 'section',
	// 								'indent'   => false,  
	// 							)
    //     ),
    // ) );
    Redux::setSection( $opt_name, array(
        'icon'            => 'el el-list-alt',
        'title'           => __( 'Customizer Only', 'truelysell' ),
        'desc'            => __( '<p class="description">This Section should be visible only in Customizer</p>', 'truelysell' ),
        'customizer_only' => true,
        'fields'          => array(
            array(
                'id'              => 'opt-customizer-only',
                'type'            => 'select',
                'title'           => __( 'Customizer Only Option', 'truelysell' ),
                'subtitle'        => __( 'The subtitle is NOT visible in customizer', 'truelysell' ),
                'desc'            => __( 'The field desc is NOT visible in customizer.', 'truelysell' ),
                'customizer_only' => true,
                'options'         => array(
                    '1' => 'Opt 1',
                    '2' => 'Opt 2',
                    '3' => 'Opt 3'
                ),
                'default'         => '2'
            ),
        )
    ) );
    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => __( 'Documentation', 'truelysell' ),
            'fields' => array(
                array(
                    'id'       => '17',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/../README.md', 
                ),
            ),
        );
        Redux::setSection( $opt_name, $section );
    }
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values );
            echo "</pre>";

        }
    }
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            $sections[] = array(
                'title'  => __( 'Section via hook', 'truelysell' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'truelysell' ),
                'icon'   => 'el el-paper-clip',
                'fields' => array()
            );

            return $sections;
        }
    }
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {

            return $args;
        }
    }
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }