<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;
/**
 * Truelysell_Core_Listing class
 */
class Truelysell_Core_Commissions {

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

	//fixed/percentage

	public function __construct() {
			
		add_action( 'woocommerce_order_status_changed', array( $this, 'order_status_change' ), 10, 3 );
		
		add_shortcode( 'truelysell_wallet', array( $this, 'truelysell_wallet' ) );
		add_shortcode( 'truelysell_payout', array( $this, 'truelysell_payout' ) );

		
	}


	public function order_status_change( $order_id, $old_status, $new_status ) {
			switch ( $new_status ) {

				case 'completed' :
					$this->register_commission( $order_id );
					
					break;

				case 'refunded' :
				case 'cancelled' :
				case 'failed' :
					$this->delete_commission( $order_id );
					break;

			}
	}

	function delete_commission($order_id){
		if ( ! $order_id ) {
				return;
			}

			global $wpdb;
			$wpdb->delete(  $wpdb->prefix . "truelysell_core_commissions", array( 'order_id' => $order_id ) );
	}

	function register_commission($order_id){
		$order = wc_get_order( $order_id );
		if(!$order){
			return;
		}
		$processed = $order->get_meta( '_truelysell_commissions_processed', true );

		if ( $processed && $processed == 'yes' ) {
			return;
		}

		$order_data = $order->get_data();
		$order_meta = get_post_meta($order_id);
		
		$args['order_id'] = $order_id;
		$args['user_id'] = get_post_meta($order_id,'owner_id',true);
		$args['booking_id'] = get_post_meta($order_id,'booking_id',true);
		$args['listing_id'] = get_post_meta($order_id,'listing_id',true);
		$args['rate'] = (float) truelysell_fl_framework_getoptions('commission_rate')/100;
		$args['status'] = 'paid';

		$order_total = $order->get_total();
		$args['amount'] = (float) $order_total * $args['rate'];
		$args['type'] = "percentage";	

		$commission_id = $this->insert_commission( $args );
		if($commission_id){
			$order->add_meta_data( '_truelysell_commissions_id', $commission_id, true );
			$order->add_meta_data( '_truelysell_commissions_processed', 'yes', true );
	        $order->save_meta_data();
		}
		// Mark commissions as processed
		
	}

	function insert_commission($args){
		global $wpdb;
		
		$defaults = array(
		    'type'          => 'percentage',
            'date'			=> current_time('mysql')
		);

		$args = wp_parse_args( $args, $defaults );

		$wpdb->insert( $wpdb->prefix . "truelysell_core_commissions", (array) $args );

		return $wpdb->insert_id;

	}

	function get_commissions($args){

		global $wpdb;

			$default_args = array(
				
				'order_id'     => 0,
				'user_id'      => 0,
				'booking_id'    => 0,
				'status'       => 'unpaid',
				'm'            => false,
				'date_query'   => false,
				'number'       => '',
				'offset'       => '',
				'paged'        => '',
				'orderby'      => 'ID',
				'order'        => 'DESC',
				'fields'       => 'ids',
                'table'        => $wpdb->prefix . "truelysell_core_commissions"
			);

			$args = wp_parse_args( $args, $default_args );

			$table = $args['table'];

		

			// First let's clear some variables
			$where = '';
			$limits = '';
			$join = '';
			$groupby = '';
			$orderby = '';

			// query parts initializating
			$pieces = array( 'where', 'groupby', 'join', 'orderby', 'limits' );

			
			if ( ! empty( $args['order_id'] ) ) {
				$where .= $wpdb->prepare( " AND c.order_id = %d", $args['order_id'] );
			}
			if ( ! empty( $args['user_id'] ) ) {
				$where .= $wpdb->prepare( " AND c.user_id = %d", $args['user_id'] );
			}
			if ( ! empty( $args['booking_id'] ) ) {
				$where .= $wpdb->prepare( " AND c.booking_id = %d", $args['booking_id'] );
			}
            if ( ! empty( $args['type'] ) && 'all' != $args['type'] ) {
                $where .= $wpdb->prepare( " AND c.type = %s", $args['type'] );
            }
			if ( ! empty( $args['status'] ) && 'all' != $args['status'] ) {
				if ( is_array( $args['status'] ) ) {
					$args['status'] = implode( "', '", $args['status'] );
				}
				$where .= sprintf( " AND c.status IN ( '%s' )", $args['status'] );
			}

			// Order
			if ( ! is_string( $args['order'] ) || empty( $args['order'] ) ) {
				$args['order'] = 'DESC';
			}

			if ( 'ASC' === strtoupper( $args['order'] ) ) {
				$args['order'] = 'ASC';
			} else {
				$args['order'] = 'DESC';
			}

			// Order by.
			if ( empty( $args['orderby'] ) ) {
				/*
				 * Boolean false or empty array blanks out ORDER BY,
				 * while leaving the value unset or otherwise empty sets the default.
				 */
				if ( isset( $args['orderby'] ) && ( is_array( $args['orderby'] ) || false === $args['orderby'] ) ) {
					$orderby = '';
				} else {
					$orderby = "c.ID " . $args['order'];
				}
			} elseif ( 'none' == $args['orderby'] ) {
				$orderby = '';
			} else {
				$orderby_array = array();
				if ( is_array( $args['orderby'] ) ) {
					foreach ( $args['orderby'] as $_orderby => $order ) {
						$orderby = addslashes_gpc( urldecode( $_orderby ) );

						if ( ! is_string( $order ) || empty( $order ) ) {
							$order = 'DESC';
						}

						if ( 'ASC' === strtoupper( $order ) ) {
							$order = 'ASC';
						} else {
							$order = 'DESC';
						}

						$orderby_array[] = $orderby . ' ' . $order;
					}
					$orderby = implode( ', ', $orderby_array );

				} else {
					$args['orderby'] = urldecode( $args['orderby'] );
					$args['orderby'] = addslashes_gpc( $args['orderby'] );

					foreach ( explode( ' ', $args['orderby'] ) as $i => $orderby ) {
						$orderby_array[] = $orderby;
					}
					$orderby = implode( ' ' . $args['order'] . ', ', $orderby_array );

					if ( empty( $orderby ) ) {
						$orderby = "c.ID " . $args['order'];
					} elseif ( ! empty( $args['order'] ) ) {
						$orderby .= " {$args['order']}";
					}
				}
			}

			// Paging
			if ( ! empty($args['paged']) && ! empty($args['number']) ) {
				$page = absint($args['paged']);
				if ( !$page )
					$page = 1;

				if ( empty( $args['offset'] ) ) {
					$pgstrt = absint( ( $page - 1 ) * $args['number'] ) . ', ';
				}
				else { // we're ignoring $page and using 'offset'
					$args['offset'] = absint( $args['offset'] );
					$pgstrt      = $args['offset'] . ', ';
				}
				$limits = 'LIMIT ' . $pgstrt . $args['number'];
			}

			$clauses = compact( $pieces );

			$where   = isset( $clauses['where'] ) ? $clauses['where'] : '';
			$groupby = isset( $clauses['groupby'] ) ? $clauses['groupby'] : '';
			$join    = isset( $clauses['join'] ) ? $clauses['join'] : '';
			$orderby = isset( $clauses['orderby'] ) ? $clauses['orderby'] : '';
			$limits  = isset( $clauses['limits'] ) ? $clauses['limits'] : '';

			if ( ! empty($groupby) )
				$groupby = 'GROUP BY ' . $groupby;
			if ( !empty( $orderby ) )
				$orderby = 'ORDER BY ' . $orderby;

			$found_rows = '';
			if ( ! empty( $limits ) ) {
				$found_rows = 'SQL_CALC_FOUND_ROWS';
			}

			$fields = 'c.ID';

			if( 'count' != $args['fields'] && 'ids' != $args['fields'] ){
				if( is_array( $args['fields'] ) ){
					$fields = implode( ',', $args['fields'] );
				}

				else {
					$fields = $args['fields'];
				}
			}

			$res = $wpdb->get_col( "SELECT $found_rows DISTINCT $fields FROM $table c $join WHERE 1=1 $where $groupby $orderby $limits" );

			// return count
			if ( 'count' == $args['fields'] ) {
				return ! empty( $limits ) ? $wpdb->get_var( 'SELECT FOUND_ROWS()' ) : count( $res );
			}

			return $res;
		}

	/**
	 * Return the count of posts in base of query
	 *
	 * @param array $q
	 *
	 * @return int
	 * @since 1.0
	 */
	public function count_commissions( $q = array() ) {
		if ( 'last-query' == $q ) {
			global $wpdb;
			return $wpdb->get_var( 'SELECT FOUND_ROWS()' );
		}

		$q['fields'] = 'count';
		return $this->get_commissions( $q );
	}


	function get_commission($commission_id){
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ". $wpdb->prefix . "truelysell_core_commissions WHERE ID = %d", $commission_id ), ARRAY_A );
	}
	/**
	 * User wallet page shortcode
	 */
	public function truelysell_wallet( $atts ) {

		if ( ! is_user_logged_in() ) {
			return __( 'You need to be signed in to access your wallet.', 'truelysell_core' );
		}

		extract( shortcode_atts( array(
		), $atts ) );

		$commissions_ids = $this->get_commissions( array( 'user_id'=>get_current_user_id(),'status' => 'all' ) );
		$commissions_count = $this->count_commissions(array( 'user_id'=>get_current_user_id(),'status' => 'all'  ) );
		
		$earnings_total = $this->calculate_totals(array( 'user_id'=>get_current_user_id(), 'status' => 'all' ) );
		
		$commissions = array();
		foreach ($commissions_ids as $id) {
			$commissions[$id] = $this->get_commission($id);
		}


		$payouts_class = new Truelysell_Core_Payouts;
		$payouts_ids = $payouts_class->get_payouts( array( 'user_id'=>get_current_user_id(), 'status' => 'all'  ) );
		
		$payouts = array();
		$total_earnings_ever = 0;
		foreach ($payouts_ids as $id) {
			$payouts[$id] = $payouts_class->get_payout($id);

		}

		ob_start();
		$template_loader = new Truelysell_Core_Template_Loader;		
		$template_loader->set_template_data( 
			array( 
				'commissions' => $commissions,
				'total_orders' => $commissions_count,
				'earnings_total' => $earnings_total,
				'payouts' => $payouts,
			) )->get_template_part( 'account/wallet' ); 


		return ob_get_clean();
	}	

	public function truelysell_payout( $atts ) {

		if ( ! is_user_logged_in() ) {
			return __( 'You need to be signed in to access your wallet.', 'truelysell_core' );
		}

		extract( shortcode_atts( array(
		), $atts ) );

		$commissions_ids = $this->get_commissions( array( 'user_id'=>get_current_user_id(),'status' => 'all' ) );
		$commissions_count = $this->count_commissions(array( 'user_id'=>get_current_user_id(),'status' => 'all'  ) );
		
		$earnings_total = $this->calculate_totals(array( 'user_id'=>get_current_user_id(), 'status' => 'all' ) );
		
		$commissions = array();
		foreach ($commissions_ids as $id) {
			$commissions[$id] = $this->get_commission($id);
		}


		$payouts_class = new Truelysell_Core_Payouts;
		$payouts_ids = $payouts_class->get_payouts( array( 'user_id'=>get_current_user_id(), 'status' => 'all'  ) );
		
		$payouts = array();
		$total_earnings_ever = 0;
		foreach ($payouts_ids as $id) {
			$payouts[$id] = $payouts_class->get_payout($id);

		}

		ob_start();
		$template_loader = new Truelysell_Core_Template_Loader;		
		$template_loader->set_template_data( 
			array( 
				'commissions' => $commissions,
				'total_orders' => $commissions_count,
				'earnings_total' => $earnings_total,
				'payouts' => $payouts,
			) )->get_template_part( 'account/payout' ); 


		return ob_get_clean();
	}	


	public function calculate_totals($args){

		if(!isset($args['status'])) { $args['status'] = 'all'; }
		
		$q = array(
			'user_id' => $args['user_id'],
			'status' => $args['status']
		);
		
		$total_earnings = 0;
		$commissions_ids = $this->get_commissions( $q );
		$commissions = array();
		foreach ($commissions_ids as $id) {
			$commissions[$id] = $this->get_commission($id);
		}
		
		foreach ($commissions as $commission) {
			$order = wc_get_order( $commission['order_id'] );
			if($order){
			
		
			$total = $order->get_total();
			$earning = $total - $commission['amount'];
			$total_earnings = $total_earnings + $earning;
			}
		}
		return $total_earnings;
	}
}




if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( ! class_exists( 'Truelysell_Balances_List_Table' ) ) {
    /**
     *
     *
     * @class class.yith-commissions-list-table
     * @package    Yithemes
     * @since      Version 1.0.0
     * @author     Your Inspiration Themes
     *
     */
    class Truelysell_Balances_List_Table extends WP_List_Table {
    /** Class constructor */
        public function __construct() {

            parent::__construct( [
                'singular' => __( 'User Balance', 'truelysell_core' ), // singular name of the listed records
                'plural'   => __( 'Users Balance', 'truelysell_core' ), // plural name of the listed records
                'ajax'     => false // does this table support ajax?
            ] );

        }


        /**
         * Returns columns available in table
         *
         * @return array Array of columns of the table
         * @since 1.0.0
         */
        public function get_columns() {
            $columns = array(
                    'user_id'   => __( 'User ID', 'truelysell_core' ),
                    'user_name' => __( 'User Name', 'truelysell_core' ),
                    'balance'   => __( 'Balance to pay', 'truelysell_core' ),
                    'orders'    => __( 'Orders counter', 'truelysell_core' ),
                    'actions'      => __( 'Actions', 'truelysell_core' ),
                
            );

            return $columns;
        }

        public function prepare_items() {            

                $columns = $this->get_columns();
                $hidden = $this->get_hidden_columns();
                $sortable = $this->get_sortable_columns();
                
                $data = $this->table_data();
                usort( $data, array( &$this, 'sort_data' ) );
                
                $perPage = 8;
                
                $currentPage = $this->get_pagenum();
                $totalItems = count($data);
                
                $this->set_pagination_args( array(
                    'total_items' => $totalItems,
                    'per_page'    => $perPage
                ) );
                
                $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
                
                $this->_column_headers = array($columns, $hidden, $sortable);
                $this->items = $data;


        }

        /**
        * Define which columns are hidden
         *
         * @return Array
         */
        public function get_hidden_columns() {
            return array();
        }

         /**
         * Get the table data
         *
         * @return Array
         */
        private function table_data() {

            $data = array();

            $args = array(
                'fields'         => 'all',
            );
            $user_query = new WP_User_Query( $args );
            $commission = new Truelysell_Core_Commissions;
            if ( ! empty( $user_query->get_results() ) ) {
                foreach ( $user_query->get_results() as $user ) {
                    
                    $balance = $commission->calculate_totals( array( 'user_id'=> $user->ID,'status' => 'unpaid' ) );
                    $orders =  $commission->get_commissions( array( 'user_id'=> $user->ID ) );
                    if($balance>0){
                       $data[] = array(
                        'user_id' => $user->ID,
                        'user_name' => $user->display_name,
                        'balance' => $balance,
                        'orders' => $orders,
                        ); 
                    }
                    
                  
                }
            } 
            return $data;
        }
         /**
         * Define what data to show on each column of the table
         *
         * @param  Array $item        Data
         * @param  String $column_name - Current column name
         *
         * @return Mixed
         */
        public function column_default( $item, $column_name )
        {
            switch( $column_name ) {
                case 'user_id':
                    return $item[ $column_name ];
                break;
                
                case 'balance':
                    if(function_exists('wc_price')) {
                        echo wc_price($item[ $column_name ]);
                    } else { echo $item[ $column_name ]; };
                break;
                
                case 'user_name':
                    return '<a href="'.esc_url( get_author_posts_url($item['user_id'])).'">'.$item[ $column_name ].'</a>';
                break;

                case 'orders':
                    echo count($item['orders']);
                break;
                
                case 'actions':
                $url = admin_url( 'admin.php?page=truelysell_payouts');
                
                $payout_url = esc_url( add_query_arg( 'make_payout', $item['user_id'], $url ) );
               
                printf( '<a class="button-primary view" href="%1$s" data-tip="%2$s">%2$s</a>', $payout_url, __( 'Make Payout', 'truelysell_core' ) );
                break;

                default:
                    return print_r( $item, true ) ;
            }
        }
        function no_items() {
            _e( 'No users found.','truelysell_core' );
        }

    }
}