<?php
/*
 * Plugin Name: Truelysell-Core
 * Version: 1.8.0
 * Description: Services Listing  Plugin 
 * Author: Dreams Technologies
 * Text Domain: truelysell_core
 * Domain Path: /languages/
 * Author URI: https://dreamstechnologies.com/
 * Plugin URI: https://dreamstechnologies.com/
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'REALTEO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
/* load CMB2 for meta boxes*/
if ( file_exists( dirname( __FILE__ ) . '/lib/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/lib/cmb2/init.php';
	require_once dirname( __FILE__ ) . '/lib/cmb2-tabs/plugin.php';
} else {
	add_action( 'admin_notices', 'truelysell_core_missing_cmb2' );
}
// Load plugin class files

global $current_commission_table_version;
$current_commission_table_version = '1.0';

global $truelysell_core_db_version;
$truelysell_core_db_version = "2.0";

include_once( 'includes/truelysell-paypal-payout.php' );
require_once( 'includes/truelysell-core-admin.php' );
require_once( 'includes/class-truelysell-core.php' );



/**
 * Returns the main instance of truelysell_core to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object truelysell_core
 */
function Truelysell_Core () {
	$instance = Truelysell_Core::instance( __FILE__, '1.7.20' );

	return $instance;
}
$GLOBALS['truelysell_core'] = Truelysell_Core();


/* load template engine*/
if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
	require_once dirname( __FILE__ ) . '/lib/class-gamajo-template-loader.php';
}
include( dirname( __FILE__ ) . '/includes/truelysell-core-templates.php' );

include( dirname( __FILE__ ) . '/includes/paid-services/truelysell-core-paid-services.php' );
include( dirname( __FILE__ ) . '/includes/paid-services/class-wc-product-listing-package.php' );
include( dirname( __FILE__ ) . '/includes/class-wc-product-listing-booking.php' );
include( dirname( __FILE__ ) . '/includes/paid-services/truelysell-core-paid-services-admin.php' );
include( dirname( __FILE__ ) . '/includes/paid-services/truelysell-core-paid-services-admin-listings.php' );




function truelysell_core_pricing_install() {
	global $wpdb;

	$collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate .= " COLLATE $wpdb->collate";
		}
	}

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


	

	/**
	 * Table for user packages
	 */
	$sql = "
	CREATE TABLE {$wpdb->prefix}truelysell_core_user_packages (
	  id bigint(20) NOT NULL auto_increment,
	  user_id bigint(20) NOT NULL,
	  product_id bigint(20) NOT NULL,
	  order_id bigint(20) NOT NULL default 0,
	  package_featured int(1) NULL,
	  package_duration bigint(20) NULL,
	  package_limit bigint(20) NOT NULL,
	  package_count bigint(20) NOT NULL,
	  package_option_booking int(1) NULL,
	  package_option_reviews int(1) NULL,
	  package_option_gallery int(1) NULL,
	  package_option_gallery_limit bigint(20) NULL,
	  package_option_social_links int(1) NULL,
	  package_option_opening_hours int(1) NULL,
	  package_option_video int(1) NULL,
	  package_option_coupons int(1) NULL,
	  PRIMARY KEY  (id)
	) $collate;
	";
	
	dbDelta( $sql );

}

register_activation_hook( __FILE__, 'truelysell_core_pricing_install' );



function truelysell_core_activity_log() {
	global $wpdb;

	$collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate .= " COLLATE $wpdb->collate";
		}
	}

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	/**
	 * Table for user packages
	 */
	$sql = "
	CREATE TABLE {$wpdb->prefix}truelysell_core_activity_log (
	  id bigint(20) NOT NULL auto_increment,
	  user_id bigint(20) NOT NULL,
	  post_id  bigint(20) NOT NULL,
	  related_to_id bigint(20) NOT NULL,
	  action varchar(255) NOT NULL,
	  log_time int(11) NOT NULL DEFAULT '0',
	  PRIMARY KEY  (id)
	) $collate;
	";
	
	dbDelta( $sql );

}
register_activation_hook( __FILE__, 'truelysell_core_activity_log' );


function truelysell_core_messages_db() {
	global $wpdb;

	$collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate .= " COLLATE $wpdb->collate";
		}
	}

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	/**
	 * Table for user packages
	 */
	$sql = "
	CREATE TABLE {$wpdb->prefix}truelysell_core_messages (
	  id bigint(20) NOT NULL auto_increment,
	  conversation_id bigint(20) NOT NULL,
	  sender_id bigint(20) NOT NULL,
	  message  text NOT NULL,
	  created_at bigint(20) NOT NULL,
	  PRIMARY KEY  (id)
	) $collate;
	";
	
	dbDelta( $sql );

}
register_activation_hook( __FILE__, 'truelysell_core_messages_db' );


function truelysell_core_conversations_db() {
	global $wpdb;

	$collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate .= " COLLATE $wpdb->collate";
		}
	}

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	/**
	 * Table for user packages
	 */
	$sql = "
	CREATE TABLE {$wpdb->prefix}truelysell_core_conversations (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `timestamp` varchar(255) NOT NULL DEFAULT '',
	  `user_1` int(11) NOT NULL,
	  `user_2` int(11) NOT NULL,
	  `referral` varchar(255) NOT NULL DEFAULT '',
	  `read_user_1` int(11) NOT NULL,
	  `read_user_2` int(11) NOT NULL,
	  `last_update` bigint(20) DEFAULT NULL,
	  `notification` varchar(20) DEFAULT '',
	  PRIMARY KEY  (id)
	) $collate;
	";
	
	dbDelta( $sql );

}
register_activation_hook( __FILE__, 'truelysell_core_conversations_db' );



function truelysell_core_commisions_db() {
	global $wpdb, $truelysell_core_db_version;

    $collate = '';
    if ( $wpdb->has_cap( 'collation' ) ) {
        if ( ! empty( $wpdb->charset ) ) {
            $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
        }
        if ( ! empty( $wpdb->collate ) ) {
            $collate .= " COLLATE $wpdb->collate";
        }
    }

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $current_commission_table_version = get_option('truelysell_commission_table_version'); //1

    //2
    if ($truelysell_core_db_version != $current_commission_table_version){
        // upgrade

        $sql = "
        CREATE TABLE {$wpdb->prefix}truelysell_core_commissions (
            id bigint(20) UNSIGNED NOT NULL auto_increment,
            user_id bigint(20) NOT NULL,
            order_id bigint(20) NOT NULL,
            amount double(15,4) NOT NULL,
            rate  decimal(5,4) NOT NULL,
            status  varchar(255) NOT NULL,
            `date`  DATETIME NOT NULL,
            type  varchar(255) NOT NULL,
            booking_id  bigint(20) NOT NULL,
            listing_id  bigint(20) NOT NULL,
            
            pp_status_code varchar (50) DEFAULT NULL, 
            payout_batch_id varchar (50) DEFAULT NULL,
            batch_status varchar (50) DEFAULT NULL,
            time_created DATETIME DEFAULT NULL,
            time_completed DATETIME DEFAULT NULL,
            fees_currency varchar (5) DEFAULT NULL,
            fee_value double (15, 4) DEFAULT NULL,
            funding_source varchar (50) DEFAULT NULL,
            sent_amount_currency varchar (5) DEFAULT NULL,
            sent_amount_value double (15, 4) DEFAULT NULL,
            payout_item_id varchar (50) DEFAULT NULL,
            payout_item_transaction_id varchar (50) DEFAULT NULL,
            payout_item_activity_id varchar (50) DEFAULT NULL,
            payout_item_transaction_status varchar (50) DEFAULT NULL,
            error_name varchar (100) DEFAULT NULL,
            error_message mediumtext DEFAULT NULL,
            payout_item_link varchar(255) DEFAULT NULL,
          
          
          PRIMARY KEY  (id)
        ) $collate;
        ";

        dbDelta( $sql );
        update_option( "truelysell_commission_table_version", '2.0' );

    }

}
register_activation_hook( __FILE__, 'truelysell_core_commisions_db' );

if (! function_exists('truelysell_update_commission_table_check')){
    function truelysell_update_commission_table_check(){
        global $truelysell_core_db_version;

        if ( get_site_option( 'truelysell_commission_table_version' ) != $truelysell_core_db_version ) {
            truelysell_core_commisions_db();
        }
    }
    add_action( 'plugins_loaded', 'truelysell_update_commission_table_check' );
}


function truelysell_core_commisions_payouts_db() {
	global $wpdb;

	$collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate .= " COLLATE $wpdb->collate";
		}
	}

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	/**
	 * Table for user packages
	 */
	$sql = "
	CREATE TABLE {$wpdb->prefix}truelysell_core_commissions_payouts (
	  id bigint(20) UNSIGNED NOT NULL auto_increment,
	  user_id bigint(20) NOT NULL,
	  status  varchar(255) NOT NULL,
	  orders  varchar(255) NOT NULL,
	  payment_method  text NOT NULL,
	  payment_details  text NOT NULL,
	  `date`  DATETIME NOT NULL,
	  amount double(15,4) NOT NULL,
	  PRIMARY KEY  (id)
	) $collate;
	";
	
	dbDelta( $sql );

}
register_activation_hook( __FILE__, 'truelysell_core_commisions_payouts_db' );


function truelysell_core_booking_calendar_db() {
	global $wpdb;

	$collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate .= " COLLATE $wpdb->collate";
		}
	}

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	/**
	 * Table for booking calendar
	 */
	$sql = "
	CREATE TABLE {$wpdb->prefix}bookings_calendar (
		`ID` bigint(20) UNSIGNED  NOT NULL auto_increment,
		`bookings_author` bigint(20) UNSIGNED NOT NULL,
		`owner_id` bigint(20) UNSIGNED NOT NULL,
		`listing_id` bigint(20) UNSIGNED NOT NULL,
		`date_start` datetime DEFAULT NULL,
		`date_end` datetime DEFAULT NULL,
		`comment` text,
		`order_id` bigint(20) UNSIGNED DEFAULT NULL,
		`status` varchar(100) DEFAULT NULL,
		`type` text,
		`created` datetime DEFAULT NULL,
		`expiring` datetime DEFAULT NULL,
		`price` LONGTEXT DEFAULT NULL,
 		PRIMARY KEY  (ID)
	) $collate;
	";
	
	dbDelta( $sql );

}
register_activation_hook( __FILE__, 'truelysell_core_booking_calendar_db' );

 
 
function truelysell_core_notification_db() {
	global $wpdb;

	$collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate .= " COLLATE $wpdb->collate";
		}
	}

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	/**
	 * Table for booking calendar
	 */
	$sql = "
	CREATE TABLE {$wpdb->prefix}truelysell_core_notification (
		`ID` bigint(20) UNSIGNED  NOT NULL auto_increment,
		`bk_id` bigint(20) UNSIGNED NOT NULL,
		`bookings_author` bigint(20) UNSIGNED NOT NULL,
		`owner_id` bigint(20) UNSIGNED NOT NULL,
		`listing_id` bigint(20) UNSIGNED NOT NULL,
		`date_start` datetime DEFAULT NULL,
		`date_end` datetime DEFAULT NULL,
		`comment` text,
		`order_id` bigint(20) UNSIGNED DEFAULT NULL,
		`status` varchar(100) DEFAULT NULL,
		`type` text,
		`created` datetime DEFAULT NULL,
		`expiring` datetime DEFAULT NULL,
		`price` LONGTEXT DEFAULT NULL,
		`rstatus` varchar(100) DEFAULT NULL,
		PRIMARY KEY  (ID)
	) $collate;
	";
	
	dbDelta( $sql );

}
register_activation_hook( __FILE__, 'truelysell_core_notification_db' );


function truelysell_core_missing_cmb2() { ?>
	<div class="error">
		<p><?php _e( 'CMB2 Plugin is missing CMB2!', 'truelysell_core' ); ?></p>
	</div>
<?php }

Truelysell_Core();