<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Truelysell_Core_Settings {

	/**
	 * The single instance of Truelysell_Core_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

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
		/*prefix for all settings*/
		$this->base = 'truelysell_core_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		$page = add_menu_page( __( 'Truelysell Core Settings', 'truelysell_core' ) , __( 'Truelysell Core Settings', 'truelysell_core' ) , 'manage_options' , 'truelysell_core_settings' ,  array( $this, 'settings_page' ) );
				add_submenu_page( 'truelysell_core_settings', __( 'Truelysell Core Settings', 'truelysell_core' ), __( 'Truelysell Core Settings', 'truelysell_core' ),	'manage_options', 'truelysell_core_settings');
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
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

    	wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'farbtastic', 'jquery' ), '1.0.0' );
    	wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'truelysell_core' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['listing'] = array(
			'title'					=> __( 'Listing Options', 'truelysell_core' ),
			'description'			=> __( 'Single listing related options', 'truelysell_core' ),
			'fields'				=> array(
				array(
					'id' 			=> 'currency',
					'label'			=> __( 'Currency', 'truelysell_core' ),
					'description'	=> __( 'Choose a currency.', 'truelysell_core' ),
					'type'			=> 'select',
					'options'		=> array(
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
							'EUR' => esc_html__( 'Euros', 'truelysell_core' ),
							'HKD' => esc_html__( 'Hong Kong Dollar', 'truelysell_core' ),
							'HRK' => esc_html__( 'Croatia kuna', 'truelysell_core' ),
							'HUF' => esc_html__( 'Hungarian Forint', 'truelysell_core' ),
							'ISK' => esc_html__( 'Icelandic krona', 'truelysell_core' ),
							'IDR' => esc_html__( 'Indonesia Rupiah', 'truelysell_core' ),
							'INR' => esc_html__( 'Indian Rupee', 'truelysell_core' ),
							'NPR' => esc_html__( 'Nepali Rupee', 'truelysell_core' ),
							'ILS' => esc_html__( 'Israeli Shekel', 'truelysell_core' ),
							'JPY' => esc_html__( 'Japanese Yen', 'truelysell_core' ),
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
					'default'		=> 'USD'
				),
				array(
					'id' 			=> 'currency_postion',
					'label'			=> __( 'Currency symbol postion', 'truelysell_core' ),
					'description'	=> __( 'After or before the price.', 'truelysell_core' ),
					'type'			=> 'radio',
					'options'		=> array( 'after' => 'After', 'before' => 'Before' ),
					'default'		=> 'after'
				),
				array(
					'id' 			=> 'maps_api',
					'label'			=> __( 'Google Maps API key' , 'truelysell_core' ),
					'description'	=> __( 'Generate API key for google maps functionality.', 'truelysell_core' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'API key', 'truelysell_core' )
				),
				array(
					'id' 			=> 'scale',
					'label'			=> __( 'Scale', 'truelysell_core' ),
					'description'	=> __( 'Choose a scale.', 'truelysell_core' ),
					'type'			=> 'select',
					'options'		=> array(
							'sq m' => esc_html__( 'Square meter', 'truelysell_core' ),
							'sq ft' => esc_html__( 'Square feet', 'truelysell_core' ),
						),
					'default'		=> 'sq ft'
				),

			)
		);
/*
		$settings['extra'] = array(
			'title'					=> __( 'Extra', 'truelysell_core' ),
			'description'			=> __( 'These are some extra input fields that maybe aren\'t as common as the others.', 'truelysell_core' ),
			'fields'				=> array(
				array(
					'id' 			=> 'number_field',
					'label'			=> __( 'A Number' , 'truelysell_core' ),
					'description'	=> __( 'This is a standard number field - if this field contains anything other than numbers then the form will not be submitted.', 'truelysell_core' ),
					'type'			=> 'number',
					'default'		=> '',
					'placeholder'	=> __( '42', 'truelysell_core' )
				),
				array(
					'id' 			=> 'colour_picker',
					'label'			=> __( 'Pick a colour', 'truelysell_core' ),
					'description'	=> __( 'This uses WordPress\' built-in colour picker - the option is stored as the colour\'s hex code.', 'truelysell_core' ),
					'type'			=> 'color',
					'default'		=> '#21759B'
				),
				array(
					'id' 			=> 'an_image',
					'label'			=> __( 'An Image' , 'truelysell_core' ),
					'description'	=> __( 'This will upload an image to your media library and store the attachment ID in the option field. Once you have uploaded an imge the thumbnail will display above these buttons.', 'truelysell_core' ),
					'type'			=> 'image',
					'default'		=> '',
					'placeholder'	=> ''
				),
				array(
					'id' 			=> 'multi_select_box',
					'label'			=> __( 'A Multi-Select Box', 'truelysell_core' ),
					'description'	=> __( 'A standard multi-select box - the saved data is stored as an array.', 'truelysell_core' ),
					'type'			=> 'select_multi',
					'options'		=> array( 'linux' => 'Linux', 'mac' => 'Mac', 'windows' => 'Windows' ),
					'default'		=> array( 'linux' )
				)
			)
		);
*/
		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

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
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			$html .= '<h2>' . __( 'Plugin Settings' , 'truelysell_core' ) . '</h2>' . "\n";

			$tab = '';
			if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
				$tab .= $_GET['tab'];
			}

			// Show page tabs
			if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

				$html .= '<h2 class="nav-tab-wrapper">' . "\n";

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
					$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

					++$c;
				}

				$html .= '</h2>' . "\n";
			}

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();

				$html .= '<p class="submit">' . "\n";
					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'truelysell_core' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Main Truelysell_Core_Settings Instance
	 *
	 * Ensures only one instance of Truelysell_Core_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Truelysell_Core()
	 * @return Main Truelysell_Core_Settings instance
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
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'truelysell_core' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'truelysell_core' ), $this->parent->_version );
	} // End __wakeup()

}