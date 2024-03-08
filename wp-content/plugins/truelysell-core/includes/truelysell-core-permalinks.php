<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles front admin page for WP Job Manager.
 *
 * @package truelysell-core
 * @see https://github.com/woocommerce/woocommerce/blob/3.0.8/includes/admin/class-wc-admin-permalink-settings.php  Based on WooCommerce's implementation.
 * @since 1.27.0
 */
class Truelysell_Core_Permalink_Settings {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.27.0
	 */
	private static $_instance = null;

	/**
	 * Permalink settings.
	 *
	 * @var array
	 * @since 1.27.0
	 */
	private $permalinks = array();

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since  1.27.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->setup_fields();
		$this->settings_save();
		$this->permalinks = Truelysell_Core_Post_Types::get_permalink_structure();
	}

	/**
	 * Add setting fields related to permalinks.
	 */
	public function setup_fields() {
		add_settings_field(
			'truelysell_listing_base_slug',
			__( 'Listing base', 'truelysell-core' ),
			array( $this, 'truelysell_listing_base_slug_input' ),
			'permalink',
			'optional'
		);
		add_settings_field(
			'truelysell_listing_category_slug',
			__( 'Listing category base', 'truelysell-core' ),
			array( $this, 'truelysell_listing_category_slug_input' ),
			'permalink',
			'optional'
		);
		
		add_settings_field(
			'truelysell_listings_archive_slug',
			__( 'Listings archive page', 'truelysell-core' ),
			array( $this, 'truelysell_listings_archive_slug_input' ),
			'permalink',
			'optional'
		);
		
	}


	/**
	 * Show a slug input box for listing post type slug.
	 */
	public function truelysell_listing_base_slug_input() {
		?>
		<input name="truelysell_listing_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['listing_base'] ); ?>" placeholder="<?php echo esc_attr_x( 'listing', 'Listing permalink placeholder', 'truelysell-core' ); ?>" /><br>
		 <code>http://example.com/<strong>listing</strong>/single-listing</code>
		<?php
	}

	/**
	 * Show a slug input box for listing category slug.
	 */
	public function truelysell_listing_category_slug_input() {
		?>
		<input name="truelysell_listing_category_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['category_base'] ); ?>" placeholder="<?php echo esc_attr_x( 'listing-category', 'Listing category slug', 'truelysell-core' ); ?>" /><br>
		<code>http://example.com/<strong>listing-category</strong>/hotels</code>
		<?php
	}


	/**
	 * Show a slug input box for listing archive slug.
	 */
	public function truelysell_listings_archive_slug_input() {
		?>
		<input name="truelysell_listings_archive_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['listings_archive'] ); ?>" placeholder="<?php echo esc_attr( $this->permalinks['listings_archive_rewrite_slug'] ); ?>" /><br>
		<code>http://example.com/<strong>listings</strong>/</code>
		<?php
	}



	/**
	 * Save the settings.
	 */
	public function settings_save() {
		if ( ! is_admin() ) {
			return;
		}

		if ( isset( $_POST['permalink_structure'] ) ) {
			if ( function_exists( 'switch_to_locale' ) ) {
				switch_to_locale( get_locale() );
			}

			$permalink_settings = Truelysell_Core_Post_Types::get_raw_permalink_settings();

			$permalink_settings['listing_base']      = sanitize_title_with_dashes( $_POST['truelysell_listing_base_slug'] );

			$permalink_settings['category_base'] = sanitize_title_with_dashes( $_POST['truelysell_listing_category_slug'] );
			
			$permalink_settings['listings_archive'] = sanitize_title_with_dashes( $_POST['truelysell_listings_archive_slug'] );
			

			update_option( 'truelysell_core_permalinks', wp_json_encode( $permalink_settings ) );

			if ( function_exists( 'restore_current_locale' ) ) {
				restore_current_locale();
			}
		}
	}
}

Truelysell_Core_Permalink_Settings::instance();
