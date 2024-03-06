<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin
 */
class Truelysell_Core_Paid_Listings_Admin_Listings {

	/** @var object Class Instance */
	private static $instance;

	/**
	 * Get the class instance
	 *
	 * @return static
	 */
	public static function get_instance() {
		return null === self::$instance ? ( self::$instance = new self ) : self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		
		add_filter( 'parse_query', array( $this, 'parse_query' ) );
	}

	/**
	 * Screen IDS
	 *
	 * @param  array $ids
	 * @return array
	 */
	public function add_screen_ids( $ids ) {
		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
		return array_merge( $ids, array(
			'users_page_truelysell_core_paid_listings_package_editor'
		) );
	}

	

	/**
	 * Add menu items
	 */
	public function admin_menu() {
		
	}

	/**
	 * Manage Packages
	 */
	public function listing_packages_page() {
		global $wpdb;

		$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : '';

	

		if (  'edit' === $action ) {
			$this->edit_listing_package_page();
		} else {
			include_once( dirname( __FILE__ ) . '/truelysell-core-paid-services-admin-listings-table.php' );
			$table = new Truelysell_Core_Admin_Packages_Listings();
			$table->prepare_items();
			?>
			<div class="woocommerce wrap">
				<h2><?php _e( 'Listing\'s Packages', 'truelysell_core' ); ?> </h2>
				<form id="listing-package-management" method="POST">
					<input type="hidden" name="page" value="truelysell_core_paid_listings_package_editor" />
					<?php $table->display() ?>
					<?php wp_nonce_field( 'save', 'truelysell_core_paid_listings_package_editor_nonce' ); ?>
				</form>
			</div>
			<?php
		}
	}

	/**
	 * Add package
	 */
	public function edit_listing_package_page() {
		include_once( dirname( __FILE__ ) . '/truelysell-core-paid-services-admin-edit-listing-package.php' );
		$add_package = new Truelysell_Core_Admin_Edit_Listing_Package();
		?>
		<div class="woocommerce wrap">
			<h2><?php _e( 'Edit Listing Package', 'truelysell_core' ); ?></h2>
			<form id="package-edit-listing-form" method="post">
				<input type="hidden" name="page" value="truelysell_core_paid_listings_package_editor" />
				<?php $add_package->form() ?>
				<?php wp_nonce_field( 'save', 'truelysell_core_paid_listings_package_editor_nonce' ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Filters and sorting handler
	 *
	 * @param  WP_Query $query
	 * @return WP_Query
	 */
	public function parse_query( $query ) {
		global $typenow;

		if ( 'listing' === $typenow  ) {
			if ( isset( $_GET['package'] ) ) {
				$query->query_vars['meta_key']   = '_user_package_id';
				$query->query_vars['meta_value'] = absint( $_GET['package'] );
			}
		}

		return $query;
	}
}
Truelysell_Core_Paid_Listings_Admin_Listings::get_instance();
