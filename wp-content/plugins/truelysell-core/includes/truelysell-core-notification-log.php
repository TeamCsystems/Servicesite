<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;
/**
 * Truelysell_Core_Listing class
 */
class Truelysell_Core_Notification_Log {
	
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.26
	 */
	private static $_instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since  1.26
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {

		// table is set in truelysell-core.php
		add_shortcode( 'truelysell_notification', array( $this, 'show_activities_notification' ) );
		add_shortcode( 'truelysell_allnotification', array( $this, 'showall_activities_notification' ) );

		//hooks
		add_action( 'transition_post_status', array( $this, 'hooks_transition_post_status' ), 10, 3 );
   		add_action( 'wp_ajax_remove_notification', array( $this, 'remove_notification' ) );
		add_action( 'wp_ajax_delete_notification', array( $this, 'delete_notification' ) );
 		add_action( 'wp_ajax_clearall_notification', array( $this, 'clearall_notification' ) );
		
	}

	/**
	 * @since 1.0.0
	 * 
	 * @param array $args
	 * @return void
	 */
	public function insert( $args ) {
		global $wpdb;

		$args = wp_parse_args(
			$args,
			array(
				
				'log_time'  => current_time( 'timestamp' ),
			)
		);

		$user = get_user_by( 'id', get_current_user_id() );
		if ( $user ) {	
			if ( empty( $args['user_id'] ) )
				$args['user_id'] = $user->ID;
		} else {
			//guest
			if ( empty( $args['user_id'] ) )
				$args['user_id'] = 0;
		}
		
		
		// Make sure for non duplicate.
		$check_duplicate = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT id FROM {$wpdb->prefix}truelysell_core_activity_log
					WHERE 	action = %s
						AND related_to_id = %d
						AND user_id = %d
						AND post_id = %d
						AND log_time = %s
				;",
				$args['action'],
				$args['related_to_id'],
				$args['user_id'],
				$args['post_id'],
				$args['log_time']
			)
		);
		
		if ( $check_duplicate )
			return;

		$wpdb->insert(
			$wpdb->prefix.'truelysell_core_activity_log',
			array(
				'action'    	=> $args['action'], // action name
				'related_to_id' => $args['related_to_id'], //ID of user who will se this action
				'user_id'   	=> $args['user_id'], // ID of user who did the action
				'post_id' 		=> $args['post_id'], // ID of object the action was done on
				'log_time'  	=> $args['log_time'], //time of action
				
			),
			array( '%s', '%d', '%d', '%d', '%s',  )
		);

		// Remove old items.
		do_action( 'truelysell_insert_activity', $args );
	}

	/**
	 * @since 1.0.0
	 * @return void
	 */
	public function erase_all_activities() {
		global $wpdb;
		
		$wpdb->query( "TRUNCATE {$wpdb->prefix}truelysell_core_activity_log" );
	}

	public function remove_notification(){
		
	

		$id = esc_sql($_POST['id']);

		global $wpdb;
		///$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}truelysell_core_notification WHERE ID = %d", $id));
		$current_readed ="read";
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}truelysell_core_notification SET rstatus='$current_readed' WHERE ID= %d", $id));

		wp_send_json_success(array( 'success' => true ));
		die();
	}

	public function delete_notification(){

		$id = esc_sql($_POST['deid']);

		global $wpdb;
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}truelysell_core_notification WHERE ID = %d", $id));
 		wp_send_json_success(array( 'success' => true ));
		die();
	}


	public function clearall_notification(){
		
		$current_user = wp_get_current_user();	
		$id = $current_user->ID;

		global $wpdb;

 		$roles = $current_user->roles;
		$role = array_shift($roles);

		$current_readed ="read";

		if($role == "owner"){ 
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}truelysell_core_notification SET rstatus='$current_readed' WHERE owner_id= %d", $id));
		}  else if($role == "guest"){
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}truelysell_core_notification SET rstatus='$current_readed' WHERE bookings_author= %d", $id));
	}  else if($role == "administrator"){

		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}truelysell_core_notification SET rstatus='$current_readed' WHERE bookings_author= %d OR owner_id= %d ", $id));
	}
	
		 
		 
		wp_send_json_success(array( 'success' => true ));
		die();
	}

	public function show_activities_notification( $atts ){
		
		extract( shortcode_atts( array(
			'items_per_page' => '5',
		), $atts ) );

		global $wpdb;

		$current_user = wp_get_current_user();	 
		$user_id = $current_user->ID;
 		
		$offset = '0';
		
		$current_user = wp_get_current_user();
		$roles = $current_user->roles;
		$role = array_shift($roles);
		if($role == "owner"){ 

		$items = $wpdb->get_results( $wpdb->prepare(
			'SELECT * FROM '.$wpdb->prefix.'truelysell_core_notification
					WHERE owner_id = '.$user_id.' AND rstatus="unread"
					ORDER BY  created DESC
					LIMIT  %d , %d;',
			$offset, $items_per_page
		) );
		} else if($role == "guest"){

			$items = $wpdb->get_results( $wpdb->prepare(
				'SELECT * FROM '.$wpdb->prefix.'truelysell_core_notification
						WHERE bookings_author = '.$user_id.' AND rstatus="unread"
						ORDER BY  created DESC
						LIMIT  %d , %d;',
				$offset, $items_per_page
			) );

		}  else if($role == "administrator"){

			$items = $wpdb->get_results( $wpdb->prepare(
				'SELECT * FROM '.$wpdb->prefix.'truelysell_core_notification
						WHERE bookings_author = '.$user_id.' AND rstatus="unread" OR owner_id = '.$user_id.' AND rstatus="unread"
						ORDER BY  created DESC
						LIMIT  %d , %d;',
				$offset, $items_per_page
			) );

		} 

		
	 		 
		
		
		ob_start(); ?>

<div class="topnav-dropdown-header">
 <span class="notification-title"><?php echo esc_html_e('Notifications','truelysell'); ?></span>
 <a href="javascript:void(0)" id="truelysell-clear-notification" data-nonce="<?php echo wp_create_nonce('delete_activities'); ?>" class="clear-noti"><?php echo esc_html_e('Clear All','truelysell'); ?>  </a>
  
 </div>

<div class="noti-content">
			<ul id="dashboard_notification_list" class="notification-list">
				<?php
 				if($items) :

					foreach ($items as $item) { 
					
					$post_title = get_the_title( $item->listing_id );
					$post_url	= get_permalink( $item->listing_id );
 
				 
					 $details = json_decode($item->comment);
					 
					 
					 $start_booking_text= '<p class="noti-details"> <span class="noti-title">'. $details->first_name.' '.esc_html__('has booked','truelysell_core').' '.$post_title.' '.esc_html__('and paid','truelysell_core').' </span></p>';
					 

					$start = '<li class="notification-message">';
					$nonce = wp_create_nonce( 'delete_activity-' . $item->listing_id  );
					$default="40";
					$alt= "";
 					switch ($item->status) {
							case 'paid':
								echo $start.'
									
								

									<div class="media d-flex">
													<span class="avatar avatar-sm flex-shrink-0">
													'.get_avatar($item->bookings_author, 40, $default, $alt, array( 'class' => array( 'rounded-circle' ) )).'
													</span>
													<div class="media-body flex-grow-1">
													<a href='.esc_url(get_permalink(truelysell_fl_framework_getoptions('bookings_page'))).'?status=approved>
														'.$start_booking_text.'

														<p class="noti-time"><span class="notification-time">'.  human_time_diff( strtotime( $item->created ), current_time( 'timestamp', 1 ) )
 														.' '.esc_html__('Ago','truelysell_core').' </span></p>
														 </a>
														
													</div>
													<a href="#" data-nonce="'.$nonce.'" data-id="'.$item->ID.'" class="close-notilist-item"> <i class="fa fa-close"></i></a>
												</div>
												
												</li>';
								break;

							case 'pay_to_confirm':
								echo $start. '
								<div class="media d-flex">
													<span class="avatar avatar-sm flex-shrink-0">
														 '.get_avatar($item->bookings_author, 40, $default, $alt, array( 'class' => array( 'rounded-circle' ) )).'
													</span>
													<div class="media-body flex-grow-1">
													<a href='.esc_url(get_permalink(truelysell_fl_framework_getoptions('bookings_page'))).'?status=waiting> 
														<p class="noti-details"> <span class="noti-title">'. $details->first_name.' '.esc_html__('has booked','truelysell_core').' '.$post_title.' '.esc_html__('and waiting to pay','truelysell_core').'</span></p>

														<p class="noti-time"><span class="notification-time">'.  human_time_diff( strtotime( $item->created ), current_time( 'timestamp', 1 ) )
 														.' '.esc_html__('Ago','truelysell_core').' </span></p>
</a> 	
													</div>
													<a href="#" data-nonce="'.$nonce.'" data-id="'.$item->ID.'" class="close-notilist-item"><i class="fa fa-close"></i></a>
												</div>
												
												</li>';
 								break;

							 

							 
							default:
								# code...
								break;
						} 
					}
					echo '<li style="display:none;" class="no-icon cleared">'.esc_html__("You don't have any notifications.",'truelysell_core').'</li>';
				else : ?>
					<li class="no-icon"><?php esc_html_e("You don't have any notifications.",'truelysell_core') ?></li>
				<?php endif; ?>
			</ul>
</div>

<div class="topnav-dropdown-footer">
 <a href="<?php echo esc_url(get_permalink(truelysell_fl_framework_getoptions('notification_page'))); ?>"><?php echo esc_html_e('View More','truelysell'); ?> <i class="feather-arrow-right-circle"></i></a>
 </div>

		<?php return ob_get_clean();
	}


	public function showall_activities_notification( $atts ){
		
		extract( shortcode_atts( array(
			'items_per_page' => '5',
		), $atts ) );

		global $wpdb;

		$current_user = wp_get_current_user();	 
		$user_id = $current_user->ID;
		$paged = (isset($_GET['activity_paged'])) ? $_GET['activity_paged'] : 1;
		$offset = ($paged - 1) * $items_per_page;
		
		$current_user = wp_get_current_user();
		$roles = $current_user->roles;
		$role = array_shift($roles);
		
		if($role == "owner"){ 

		$items = $wpdb->get_results( $wpdb->prepare(
			'SELECT * FROM '.$wpdb->prefix.'truelysell_core_notification
					WHERE owner_id = '.$user_id.'  
					ORDER BY  created DESC
					LIMIT  %d , %d;',
			$offset, $items_per_page
		) );

		$rowcount = $wpdb->get_var(
			
			'SELECT COUNT(*) FROM '.$wpdb->prefix.'truelysell_core_notification
					WHERE owner_id = '.$user_id.'  
					ORDER BY  created DESC'
			
			);
		$max_num_pages = ceil($rowcount / $items_per_page);

		} else if($role == "guest"){

			$items = $wpdb->get_results( $wpdb->prepare(
				'SELECT * FROM '.$wpdb->prefix.'truelysell_core_notification
						WHERE bookings_author = '.$user_id.'  
						ORDER BY  created DESC
						LIMIT  %d , %d;',
				$offset, $items_per_page
			) );

			$rowcount = $wpdb->get_var(
			
				'SELECT COUNT(*) FROM '.$wpdb->prefix.'truelysell_core_notification
						WHERE bookings_author = '.$user_id.'  
						ORDER BY  created DESC'
				
				);
			$max_num_pages = ceil($rowcount / $items_per_page);


		}  else if($role == "administrator"){

			$items = $wpdb->get_results( $wpdb->prepare(
				'SELECT * FROM '.$wpdb->prefix.'truelysell_core_notification
						WHERE bookings_author = '.$user_id.'  
						ORDER BY  created DESC
						LIMIT  %d , %d;',
				$offset, $items_per_page
			) );

			$rowcount = $wpdb->get_var(
			
				'SELECT COUNT(*) FROM '.$wpdb->prefix.'truelysell_core_notification
						WHERE bookings_author = '.$user_id.'  
						ORDER BY  created DESC'
				
				);
			$max_num_pages = ceil($rowcount / $items_per_page);

		} 
 
		ob_start(); ?>

 						 	
							 
<div class="notification-details">
<div class="row">
<div class="col-md-12 mx-auto">

<div class="notify-head">
 <ul class="notify-list nav" role="tablist">
									<li>
										<a href="#" class="active" data-bs-toggle="tab" data-bs-target="#all" aria-selected="true" role="tab"><?php esc_html_e('All','truelysell_core'); ?></a>
									</li>
									 
								</ul>
  </div>

 <ul id="dashboard_notification_list_all" class="detail-list notification-list">
 				<?php
 				if($items) :

					foreach ($items as $item) { 
					
					$post_title = get_the_title( $item->listing_id );
					$post_url	= get_permalink( $item->listing_id );
 
				 
					 $details = json_decode($item->comment);
					 
					$start = '<li class="notification-message">';
					$nonce = wp_create_nonce( 'delete_activity-' . $item->listing_id  );
					$default="40";
					$alt= "";
 					switch ($item->status) {
							case 'paid':
								echo $start.'
									
									
								<div class="notification-item">
										<div class="notification-media">
											<span class="avatar avatar-sm flex-shrink-0">
											'.get_avatar($item->bookings_author, 40, $default, $alt, array( 'class' => array( 'rounded-circle' ) )).'
											</span>
										</div>
										<div class="notification-info">
											<h6><span>'. $details->first_name.' </span> '.esc_html__('booked','truelysell_core').' '.$post_title.' '.esc_html__('and paid','truelysell_core').'</h6>
											<p><i class="feather-clock"></i> '. human_time_diff( strtotime( $item->created ), current_time( 'timestamp', 1 ) )
											.' ' .esc_html__('Ago ','truelysell_core').'</p>
										</div>
									</div>
									<div class="notification-dropdown">
										<a href="#" data-bs-toggle="dropdown"><i class="feather-more-vertical"></i></a>
										<div class="dropdown-menu">
 											<a href="#" data-nonce="'.$nonce.'" data-deid="'.$item->ID.'" class="close-deletetilist-item dropdown-item"><i class="feather-trash-2 me-1"></i> '.esc_html__('Delete','truelysell_core').'</a>

										</div>
									</div>

									 
												
												</li>';
								break;

							case 'pay_to_confirm':
								echo $start. '
								<div class="notification-item">
										<div class="notification-media">
											<span class="avatar avatar-sm flex-shrink-0">
											'.get_avatar($item->bookings_author, 40, $default, $alt, array( 'class' => array( 'rounded-circle' ) )).'
											</span>
										</div>
										<div class="notification-info">
											<h6><span>'. $details->first_name.' </span> '.esc_html__('booked','truelysell_core').' '.$post_title.' '.esc_html__('and waiting to pay','truelysell_core').'</h6>
											<p><i class="feather-clock"></i> '.human_time_diff( strtotime( $item->created ), current_time( 'timestamp', 1 ) )
											.' '.esc_html__('Ago','truelysell_core').'</p>
										</div>
									</div>
									<div class="notification-dropdown">
										<a href="#" data-bs-toggle="dropdown"><i class="feather-more-vertical"></i></a>
										<div class="dropdown-menu">
										<a href="#" data-nonce="'.$nonce.'" data-deid="'.$item->ID.'" class="close-deletetilist-item dropdown-item"><i class="feather-trash-2 me-1"></i>  '.esc_html__('Delete ','truelysell_core').'</a>
										</div>
									</div>
												
												</li>';
 								break;

							 

							 
							default:
								# code...
								break;
						} 
					}
					echo '<li style="display:none;" class="no-icon cleared">'.esc_html__("You don't have any notifications.",'truelysell_core').'</li>';
				else : ?>
					<li class="no-icon"><?php esc_html_e("You don't have any notifications.",'truelysell_core') ?></li>
				<?php endif; ?>
			</ul>
</div>
</div>
</div>
 			<div class="clearfix"></div>
			<div class="pagination-container recent_pagination">
				<nav class="pagination">
				<?php 
				$big = 999999999; 
				echo paginate_links( array(
					'base'         => add_query_arg('activity_paged','%#%'),
					'format' => '?activity_paged=%#%',
					'current' => max( 1, $paged ),
					'total' => $max_num_pages,
					'type' => 'list',
					'prev_next'    => true,
			        'prev_text'    => '<i class="fas fa-angle-left"></i>',
			        'next_text'    => '<i class="fas fa-angle-right"></i>',
			         'add_args'        => false,
   					 'add_fragment'    => ''
				    
				) );?>
				</nav>
		</div>	

		<?php return ob_get_clean();
	}

	//hooks
	public function hooks_transition_post_status( $new_status, $old_status, $post ) {
		if ( 'listing' !== get_post_type( $post->ID ) )
			return;
		$action = '';
		if( 'preview' === $old_status && 'publish' == $new_status ) {
			$action = 'listing_created';
		} elseif ( 'preview' === $old_status && 'pending_payment' == $new_status ){
			$action = 'listing_created';
		} elseif ( 'pending' === $old_status && 'publish' == $new_status ){
			$action = 'listing_approved';
		} elseif ( 'trash' === $new_status ) {
			// page was deleted.
			$action = 'listing_trashed';
		} elseif ( 'trash' === $old_status ) {
		 	$action = 'listing_restored';
		} elseif ('publish' == $old_status ) {
			$action = 'listing_updated';
		}
		if ( wp_is_post_revision( $post->ID ) )
			return;

		// Skip for menu items.
		
		if($action) {
			truelysell_noti_insert_log(
				array(
					'action' => $action,
					'related_to_id' => get_post_field( 'post_author', $post->ID ),
					'user_id' => '',
					'post_id' => $post->ID,
					
				)
			);	
		}
		
	}

 

 


 

}


function truelysell_noti_insert_log( $args = array() ) {
	Truelysell_Core_Notification_Log::instance()->insert( $args );
}