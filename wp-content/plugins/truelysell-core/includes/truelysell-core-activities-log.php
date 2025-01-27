<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;
/**
 * Truelysell_Core_Listing class
 */
class Truelysell_Core_Activities_Log {
	
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
		add_shortcode( 'truelysell_activities', array( $this, 'show_activities' ) );

		//hooks
		add_action( 'transition_post_status', array( $this, 'hooks_transition_post_status' ), 10, 3 );
		add_action( 'truelysell_listing_bookmarked', array( $this, 'add_bookmarked_activity' ), 10, 2 );
		add_action( 'truelysell_listing_unbookmarked', array( $this, 'add_unbookmarked_activity' ), 10, 2 );
		add_action( 'transition_comment_status', array( $this, 'add_review_activity' ), 10, 3 );
		add_action( 'wp_ajax_remove_activity', array( $this, 'remove_activity' ) );
		add_action( 'wp_ajax_remove_all_activities', array( $this, 'remove_all_activities' ) );
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

	public function remove_activity(){
		
	

		$id = esc_sql($_POST['id']);

		global $wpdb;
		$wpdb->query( 
			$wpdb->prepare( 
				"DELETE FROM {$wpdb->prefix}truelysell_core_activity_log
				 WHERE id = %d
				",
			        $id
		        )
		);
		wp_send_json_success(array( 'success' => true ));
		die();
	}

	public function remove_all_activities(){
		
		$current_user = wp_get_current_user();	
		$id = $current_user->ID;

		global $wpdb;
		$wpdb->query( 
			$wpdb->prepare( 
				"DELETE FROM {$wpdb->prefix}truelysell_core_activity_log
				 WHERE related_to_id = %d
				",
			        $id
		        )
		);
		wp_send_json_success(array( 'success' => true ));
		die();
	}

	public function show_activities( $atts ){
		
		extract( shortcode_atts( array(
			'items_per_page' => '5',
		), $atts ) );

		global $wpdb;

		$current_user = wp_get_current_user();	 
		$user_id = $current_user->ID;
		$paged = (isset($_GET['activity_paged'])) ? $_GET['activity_paged'] : 1;
		
		$offset = ($paged - 1) *$items_per_page;
		
		
		$items = $wpdb->get_results( $wpdb->prepare(
			'SELECT * FROM '.$wpdb->prefix.'truelysell_core_activity_log
					WHERE related_to_id = '.$user_id.'
					ORDER BY  log_time DESC
					LIMIT  %d , %d;',
			$offset, $items_per_page
		) );

		$rowcount = $wpdb->get_var(
			
			'SELECT COUNT(*) FROM '.$wpdb->prefix.'truelysell_core_activity_log
					WHERE related_to_id = '.$user_id.'
					ORDER BY  log_time DESC'
			
			);
		$max_num_pages = ceil($rowcount / $items_per_page);		 
		
		
		ob_start(); ?>

			<ul id="dashboard_serviceslist">
				<?php

				if($items) :

					foreach ($items as $item) { 
					
					$post_title = get_the_title( $item->post_id );
					$post_url	= get_permalink( $item->post_id );
					$start = '<li>';
					$nonce = wp_create_nonce( 'delete_activity-' . $item->post_id  );
					$end = '<span class="activity-time">'.human_time_diff( $item->log_time, current_time('timestamp') ) . esc_html__(' ago','truelysell_core').'</span>';
					$end .= '<a href="#" data-nonce="'.$nonce.'" data-id="'.$item->id.'" class="close-list-item"><i class="fa fa-close"></i></a></li>';
					
					switch ($item->action) {
							case 'bookmarked':
								echo $start.'
									<i class="list-box-icon fa fa-star"></i> '.esc_html__('Someone bookmarked your ','truelysell_core').' <strong><a href="'.esc_url($post_url).'">'.$post_title.'</a></strong> '.__('listing','truelysell_core').'!
								'.$end;
								break;

							case 'unbookmarked':
								echo $start.'
									<i class="list-box-icon fa fa-star"></i> '.esc_html__('Someone unbookmarked your','truelysell_core').' <strong><a href="'.esc_url($post_url).'">'.$post_title.'</a></strong> '.__('listing','truelysell_core').'
								'.$end;
								break;

							case 'listing_updated':
								echo $start.'
									<i class="list-box-icon fa fa-layer-group"></i> '.esc_html__('Service','truelysell_core').' <strong><a href="'.esc_url($post_url).'">'.$post_title.'</a></strong> '.esc_html__('was updated','truelysell_core').'.
								'.$end;
								break;
							case 'listing_created':
								echo $start.'
									<i class="list-box-icon fa fa-layer-group"></i> '.esc_html__('Service','truelysell_core').' <strong><a href="'.esc_url($post_url).'">'.$post_title.'</a></strong> '.esc_html__('was created.','truelysell_core').$end;
								break;
							case 'listing_created':
								echo $start.'
									<i class="list-box-icon fa fa-layer-group"></i>'.esc_html__('Your Service','truelysell_core').' <strong><a href="'.esc_url($post_url).'">'.$post_title.'</a></strong> '.esc_html__('was approved','truelysell_core').'
								'.$end;
								break;
							case 'listing_trashed':
								echo $start.'
									<i class="list-box-icon fa fa-layer-group"></i> '.esc_html__('Service','truelysell_core').' <strong><a href="'.esc_url($post_url).'">'.$post_title.'</a></strong> '.esc_html__('was removed','truelysell_core').'
								'.$end;
								break;

							case 'approved':
								echo '
								<li>
									<i class="list-box-icon fa fa-layer-group"></i> '.esc_html__('Your Service','truelysell_core').' <strong><a href="'.esc_url($post_url).'">'.$post_title.'</a></strong> '.esc_html__('has been approved!','truelysell_core').'
									<a href="#" class="close-list-item"><i class="fa fa-close"></i></a>
								</li>';
								break;
							case 'added':
								echo '
								<li>
									<i class="list-box-icon fa fa-layer-group"></i> '.esc_html__('You have added service','truelysell_core').' <strong><a href="'.esc_url($post_url).'">'.$post_title.'</a></strong> 
									<a href="#" class="close-list-item"><i class="fa fa-close"></i></a>
								</li>';
								break;

							case 'reviewed':
								$rating = get_comment_meta( $item->user_id , 'truelysell-rating', true ); 
								$comment_author = get_comment_author( $item->user_id ); 
								$rating_value = esc_attr(round($rating,1)); 
								
								echo '
								<li>
									<i class="list-box-icon fa  fa-star"></i> '.$comment_author.' '.esc_html__('left a review','truelysell_core').' <div class="numerical-rating" data-rating="'.sprintf("%0.1f",$rating_value).'"></div> '.esc_html__('on','truelysell_core').' <strong><a href="'.esc_url($post_url).'">'.$post_title.'</a></strong>
									<a href="#" class="close-list-item"><i class="fa fa-close"></i></a>
								</li>';
								break;
							default:
								# code...
								break;
						} 
					}
					echo '<li style="display:none;" class="no-icon cleared">'.esc_html__("You don't have any activities logged yet.",'truelysell_core').'</li>';
				else : ?>
					<li class="no-icon"><?php esc_html_e("You don't have any services logged yet.",'truelysell_core') ?></li>
				<?php endif; ?>
			</ul>
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
			truelysell_insert_log(
				array(
					'action' => $action,
					'related_to_id' => get_post_field( 'post_author', $post->ID ),
					'user_id' => '',
					'post_id' => $post->ID,
					
				)
			);	
		}
		
	}

	public function add_bookmarked_activity( $post_id, $user_id ){

		truelysell_insert_log(
			array(
				'action' => "bookmarked",
				'related_to_id' => get_post_field( 'post_author', $post_id ),
				'user_id' => $user_id,
				'post_id' => $post_id,
				
			)
		);

	}	

	public function add_unbookmarked_activity( $post_id, $user_id ){

		truelysell_insert_log(
			array(
				'action' => "unbookmarked",
				'related_to_id' => get_post_field( 'post_author', $post_id ),
				'user_id' => $user_id,
				'post_id' => $post_id,
				
			)
		);

	}


	public function add_review_activity($new_status, $old_status, $comment) {

	    if($old_status != $new_status) {
	        if($new_status == 'approved') {
	            // check if comment_post_id is a listing
	            if(get_post_type($comment->comment_post_ID) == 'listing') {
	            	$rating = get_comment_meta( $comment->comment_ID, 'truelysell-rating', true ); 
	            	
	            	if($rating){
	            		truelysell_insert_log(
							array(
								'action' => "reviewed",
								'related_to_id' => get_post_field( 'post_author', $comment->comment_post_ID ),
								'user_id' => $comment->comment_ID,
								'post_id' => $comment->comment_post_ID,
								
							)
						);

	            	}
	            }
	            // check if it has rating
	            // check author of that comment_post_id
	        }
	    }
	}


}


function truelysell_insert_log( $args = array() ) {
	Truelysell_Core_Activities_Log::instance()->insert( $args );
}