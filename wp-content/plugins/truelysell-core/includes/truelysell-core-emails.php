<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;
/**
 * truelysell_core_listing class
 */
class Truelysell_Core_Emails {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.0
	 */
	private static $_instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since  1.0
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

		add_action( 'truelysell_core_listing_submitted', array($this, 'new_listing_email'));
		add_action( 'truelysell_core_listing_submitted', array($this, 'new_listing_email_admin'));
		add_action( 'truelysell_core_listing_edited', 	 array($this, 'new_listing_email_admin'));
		add_action( 'truelysell_core_expired_listing', 	 array($this, 'expired_listing_email'));
		add_action( 'truelysell_core_expiring_soon_listing', array($this, 'expiring_soon_listing_email'));

		add_action( 'pending_to_publish', array( $this, 'published_listing_email' ) );
		add_action( 'pending_payment_to_publish', array( $this, 'published_listing_email' ) );
		add_action( 'preview_to_publish', array( $this, 'published_listing_email' ) );
		
		add_action( 'truelysell_welcome_mail', array($this, 'welcome_mail'));

		//booking emails
		add_action( 'truelysell_mail_to_user_waiting_approval', array($this, 'mail_to_user_waiting_approval'));
		add_action( 'truelysell_mail_to_user_instant_approval', array($this, 'mail_to_user_instant_approval'));
		add_action( 'truelysell_mail_to_user_free_confirmed', array($this, 'mail_to_user_free_confirmed'));
		add_action( 'truelysell_mail_to_user_pay_cash_confirmed', array($this, 'mail_to_user_pay_cash_confirmed'));

		add_action( 'truelysell_mail_to_owner_new_reservation', array($this, 'mail_to_owner_new_reservation'));
		add_action( 'truelysell_mail_to_owner_new_instant_reservation', array($this, 'mail_to_owner_new_instant_reservation'));

		add_action( 'truelysell_mail_to_user_canceled', array($this, 'mail_to_user_canceled'));
		
		add_action( 'truelysell_mail_to_user_pay', array($this, 'mail_to_user_pay'));
		add_action( 'truelysell_mail_to_owner_paid', array($this, 'mail_to_owner_paid'));
		add_action( 'truelysell_mail_to_user_paid', array($this, 'mail_to_user_paid'));
		
		add_action( 'truelysell_mail_to_user_new_conversation', array($this, 'new_conversation_mail'));
		add_action( 'truelysell_mail_to_user_new_message', array($this, 'new_message_mail'));
		
		


	}



	function new_listing_email($post_id ){
		$post = get_post($post_id);
		if ( $post->post_type !== 'listing' ) {
			return;
		}


		if(!truelysell_fl_framework_getoptions('listing_new_email')){
			return;
		}


		$is_send = get_post_meta( $post->ID, 'new_listing_email_notification', true );
		if($is_send){
			return;
		}
		
		$author   	= 	get_userdata( $post->post_author ); 
		$email 		=  $author->data->user_email;

		$args = array(
			'user_name' 	=> $author->display_name,
			'user_mail' 	=> $email,
			'listing_date' => $post->post_date,
			'listing_name' => $post->post_title,
			'listing_url'  => get_permalink( $post->ID ),
			);

		$subject 	 = truelysell_fl_framework_getoptions('listing_new_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('listing_new_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		update_post_meta( $post->ID, 'new_listing_email_notification', 'sent' );
		self::send( $email, $subject, $body );
	}

	function new_listing_email_admin($post_id ){
		$post = get_post($post_id);
	
		if ( $post->post_type !== 'listing' ) {
			return;
		}
		if ( $post->post_status !== 'pending' ) {
			return;
		}
		
		if(!truelysell_fl_framework_getoptions('new_listing_admin_notification')){
			return;
		}
		

		$email = get_option('admin_email');
		$args = array(
			
			'user_mail' 	=> get_option('admin_email'),
			'listing_name' => $post->post_title,
			);

		$subject 	 = esc_html__('There is new listing waiting for approval','truelysell_core');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = esc_html__('There is listing waiting for your approval "{listing_name}"','truelysell_core');
		$body 	 = $this->replace_shortcode( $args, $body );

		self::send( $email, $subject, $body );
	}

	function published_listing_email($post ){
		if ( $post->post_type != 'listing' ) {
			return;
		}

		if(!truelysell_fl_framework_getoptions('listing_published_email')){
			return;
		}
		if(get_post_meta($post->ID, 'truelysell_published_mail_send', true) == "sent"){
			return;
		}
		$author   	= 	get_userdata( $post->post_author ); 
		$email 		=  $author->data->user_email;

		$args = array(
			'user_name' 	=> $author->display_name,
			'user_mail' 	=> $email,
			'listing_date' => $post->post_date,
			'listing_name' => $post->post_title,
			'listing_url'  => get_permalink( $post->ID ),
			);

		$subject 	 = truelysell_fl_framework_getoptions('listing_published_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('listing_published_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		update_post_meta( $post->ID, 'truelysell_published_mail_send', 'sent' );
		self::send( $email, $subject, $body );
	}	

	function expired_listing_email($post_id ){
		$post = get_post($post_id);
		if ( $post->post_type !== 'listing' ) {
			return;
		}

		if(!truelysell_fl_framework_getoptions('listing_expired_email')){
			return;
		}
		
		$author   	= 	get_userdata( $post->post_author ); 
		$email 		=  $author->data->user_email;

		$args = array(
			'user_name' 	=> $author->display_name,
			'user_mail' 	=> $email,
			'listing_date' => $post->post_date,
			'listing_name' => $post->post_title,
			'listing_url'  => get_permalink( $post->ID ),
			);

		$subject 	 = truelysell_fl_framework_getoptions('listing_expired_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('listing_expired_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );

		self::send( $email, $subject, $body );
	}

	function expiring_soon_listing_email($post_id ){
		$post = get_post($post_id);
		if ( $post->post_type !== 'listing' ) {
			return;
		}
		$already_sent = get_post_meta( $post_id, 'notification_email_sent', true );
		if($already_sent) {
			return;
		}

		if(!truelysell_fl_framework_getoptions('listing_expiring_soon_email')){
			return;
		}
		
		$author   	= 	get_userdata( $post->post_author ); 
		$email 		=  $author->data->user_email;

		$args = array(
			'user_name' 	=> $author->display_name,
			'user_mail' 	=> $email,
			'listing_date' => $post->post_date,
			'listing_name' => $post->post_title,
			'listing_url'  => get_permalink( $post->ID ),
			);

		$subject 	 = truelysell_fl_framework_getoptions('listing_expiring_soon_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('listing_expiring_soon_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		add_post_meta($post_id, 'notification_email_sent', true );
		self::send( $email, $subject, $body );

	}
		
	function mail_to_user_waiting_approval($args){
		if(!truelysell_fl_framework_getoptions('booking_user_waiting_approval_email')){
			return;
		}
		$email 		=  $args['email'];

		$booking_data = $this->get_booking_data_emails($args['booking']);
		
		$booking = $args['booking'];
		
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['bookings_author']),
			'user_mail' 	=> $email,
			'booking_date' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			);

		$subject 	 = truelysell_fl_framework_getoptions('booking_user_waiting_approval_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 =  truelysell_fl_framework_getoptions('booking_user_waiting_approval_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}	


	function mail_to_user_instant_approval($args){
		if(!truelysell_fl_framework_getoptions('instant_booking_user_waiting_approval_email')){
			return;
		}
		$email 		=  $args['email'];
		$booking_data = $this->get_booking_data_emails($args['booking']);
		$booking = $args['booking'];
		
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['bookings_author']),
			'user_mail' 	=> $email,
			'booking_date' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			);

		$subject 	 = truelysell_fl_framework_getoptions('instant_booking_user_waiting_approval_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 =  truelysell_fl_framework_getoptions('instant_booking_user_waiting_approval_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}


	function mail_to_owner_new_instant_reservation($args){

		if(!truelysell_fl_framework_getoptions('booking_instant_owner_new_booking_email')){
			return;
		}
		$email 		=  $args['email'];
		$booking = $args['booking'];
		$booking_data = $this->get_booking_data_emails($args['booking']);
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['owner_id']),
			'user_mail' 	=> $email,
			'booking_date' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			);

		$subject 	 = truelysell_fl_framework_getoptions('booking_instant_owner_new_booking_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('booking_instant_owner_new_booking_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}

	function mail_to_owner_new_reservation($args){
		if(!truelysell_fl_framework_getoptions('booking_owner_new_booking_email')){
			return;
		}
		$email 		=  $args['email'];
		$booking = $args['booking'];
		$booking_data = $this->get_booking_data_emails($args['booking']);
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['owner_id']),
			'user_mail' 	=> $email,
			'booking_date' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			);

		$subject 	 = truelysell_fl_framework_getoptions('booking_owner_new_booking_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('booking_owner_new_booking_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}

	function mail_to_user_canceled($args){
		if(!truelysell_fl_framework_getoptions('booking_user_cancallation_email')){
			return;
		}
		$email 		=  $args['email'];
		$booking = $args['booking'];
		$booking_data = $this->get_booking_data_emails($args['booking']);
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['owner_id']),
			'user_mail' 	=> $email,
			'booking_date' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			);

		$subject 	 = truelysell_fl_framework_getoptions('booking_user_cancellation_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('booking_user_cancellation_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}

	function mail_to_user_free_confirmed($args){
		if(!truelysell_fl_framework_getoptions('free_booking_confirmation')){
			return;
		}

		$email 		=  $args['email'];
		$booking_data = $this->get_booking_data_emails($args['booking']);
		$booking = $args['booking'];
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['bookings_author']),
			'user_mail' 	=> $email,
			'booking_date' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			);

		$subject 	 = truelysell_fl_framework_getoptions('free_booking_confirmation_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('free_booking_confirmation_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}

	function mail_to_user_pay_cash_confirmed($args){
		if(!truelysell_fl_framework_getoptions('mail_to_user_pay_cash_confirmed')){
			return;
		}

		$email 		=  $args['email'];
		$booking_data = $this->get_booking_data_emails($args['booking']);
		$booking = $args['booking'];
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['bookings_author']),
			'user_mail' 	=> $email,
			'booking_date' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			);

		$subject 	 = truelysell_fl_framework_getoptions('mail_to_user_pay_cash_confirmed_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('mail_to_user_pay_cash_confirmed_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}

	function mail_to_user_pay($args){
		if(!truelysell_fl_framework_getoptions('pay_booking_confirmation_user')){
			return;
		}
		$email 		=  $args['email'];
		$booking_data = $this->get_booking_data_emails($args['booking']);

		$booking = $args['booking'];
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['bookings_author']),
			'user_mail' 	=> $email,
			'booking_date' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'payment_url'  => $args['payment_url'],
			'expiration'  => $args['expiration'],
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			
			);

		$subject 	 = truelysell_fl_framework_getoptions('pay_booking_confirmation_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('pay_booking_confirmation_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}

	function mail_to_owner_paid($args){
		if(!truelysell_fl_framework_getoptions('paid_booking_confirmation')){
			return;
		}
		$email 		=  $args['email'];
		$booking_data = $this->get_booking_data_emails($args['booking']);
		$booking = $args['booking'];
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['owner_id']),
			'user_mail' 	=> $email,
			'booking_created' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			'order_id' => (isset($booking['order_id'])) ? $booking['order_id'] : '',
			);

		$subject 	 = truelysell_fl_framework_getoptions('paid_booking_confirmation_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('paid_booking_confirmation_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}


	function mail_to_user_paid($args){
		if(!truelysell_fl_framework_getoptions('user_paid_booking_confirmation')){
			return;
		}
		$email 		=  $args['email'];
		$booking_data = $this->get_booking_data_emails($args['booking']);
		$booking = $args['booking'];
		$args = array(
			'user_name' 	=> get_the_author_meta('display_name',$booking['bookings_author']),
			'user_mail' 	=> $email,
			'booking_created' => $booking['created'],
			'listing_name' => get_the_title($booking['listing_id']),
			'listing_url'  => get_permalink($booking['listing_id']),
			'listing_address'  => get_post_meta($booking['listing_id'],'_address',true),
			'listing_phone'  => get_post_meta($booking['listing_id'],'_phone',true),
			'listing_email'  => get_post_meta($booking['listing_id'],'_email',true),
			'dates' => (isset($booking_data['dates'])) ? $booking_data['dates'] : '',
			'details' => (isset($booking_data['details'])) ? $booking_data['details'] : '',
			'service' => (isset($booking_data['service'])) ? $booking_data['service'] : '',
			'tickets' => (isset($booking_data['tickets'])) ? $booking_data['tickets'] : '',
			'adults' =>(isset($booking_data['adults'])) ? $booking_data['adults'] : '',
			'children' => (isset($booking_data['children'])) ? $booking_data['children'] : '',
			'user_message' => (isset($booking_data['message'])) ? $booking_data['message'] : '',
			'client_first_name' => (isset($booking_data['client_first_name'])) ? $booking_data['client_first_name'] : '',
			'client_last_name' => (isset($booking_data['client_last_name'])) ? $booking_data['client_last_name'] : '',
			'client_email' => (isset($booking_data['client_email'])) ? $booking_data['client_email'] : '',
			'client_phone' => (isset($booking_data['client_phone'])) ? $booking_data['client_phone'] : '',
			'billing_address' => (isset($booking_data['billing_address'])) ? $booking_data['billing_address'] : '',
			'billing_postcode' => (isset($booking_data['billing_postcode'])) ? $booking_data['billing_postcode'] : '',
			'billing_city' => (isset($booking_data['billing_city'])) ? $booking_data['billing_city'] : '',
			'billing_country' => (isset($booking_data['billing_country'])) ? $booking_data['billing_country'] : '',
			'price' => (isset($booking['price'])) ? $booking['price'] : '',
			'expiring' => (isset($booking['expiring'])) ? $booking['expiring'] : '',
			'order_id' => (isset($booking['order_id'])) ? $booking['order_id'] : '',
			);

		$subject 	 = truelysell_fl_framework_getoptions('user_paid_booking_confirmation_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('user_paid_booking_confirmation_email_content');
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}

	function welcome_mail($args){
		if (truelysell_fl_framework_getoptions('user_welcome_email')) {
			return;
		}
		$email 		=  $args['email'];

		$args = array(
			'email'         => $email,
	        'login'         => $args['login'],
	        'password'      => $args['password'],
	        'first_name' 	=> $args['first_name'],
	        'last_name' 	=> $args['last_name'],
	        'user_name' 	=> $args['display_name'],
			'user_mail' 	=> $email,
			'login_url' 	=> $args['login_url'],
			
			);
		$subject 	 = truelysell_fl_framework_getoptions('listing_welcome_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('listing_welcome_email_content');
		
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}


	function new_conversation_mail($args){
		$conversation_id = $args['conversation_id']; 
		global $wpdb;

        $conversation_data  = $wpdb -> get_results( "
        SELECT * FROM `" . $wpdb->prefix . "truelysell_core_conversations` 
        WHERE  id = '$conversation_id'

        ");

        $read_user_1 = $conversation_data[0]->read_user_1;
        if($read_user_1==0){
        	$user_who_send = $conversation_data[0]->user_2;
        	$user_to_notify = $conversation_data[0]->user_1;
        }
        $read_user_2 = $conversation_data[0]->read_user_2;
        if($read_user_2==0){
        	$user_who_send = $conversation_data[0]->user_1;
        	$user_to_notify = $conversation_data[0]->user_2;
        }


		$user_to_notify_data   	= 	get_userdata( $user_to_notify ); 
		$email 		=  $user_to_notify_data->user_email;

		$user_who_send_data = get_userdata( $user_who_send ); 
		$sender = $user_who_send_data->first_name;
		if(empty($sender)){
			$sender = $user_who_send_data->nickname;
		}

		$args = array(
			'user_mail'     => $email,
	        'user_name' 	=> $user_to_notify_data->first_name,
			'conversation_url' => get_permalink('truelysell_messages_page').'?action=view&conv_id='.$conversation_id,
			'sender'		=> $sender,
			);
		$subject 	 = truelysell_fl_framework_getoptions('new_conversation_notification_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('new_conversation_notification_email_content');
		
		$body 	 = $this->replace_shortcode( $args, $body );
		self::send( $email, $subject, $body );
	}

	function new_message_mail($id){
		$conversation_id = $id; 
		global $wpdb;

        $conversation_data  = $wpdb -> get_results( "
        SELECT * FROM `" . $wpdb->prefix . "truelysell_core_conversations` 
        WHERE  id = '$conversation_id'

        ");

        $read_user_1 = $conversation_data[0]->read_user_1;
        if($read_user_1==0){
        	$user_who_send = $conversation_data[0]->user_2;
        	$user_to_notify = $conversation_data[0]->user_1;
        }
        $read_user_2 = $conversation_data[0]->read_user_2;
        if($read_user_2==0){
        	$user_who_send = $conversation_data[0]->user_1;
        	$user_to_notify = $conversation_data[0]->user_2;
        }


		$user_to_notify_data   	= 	get_userdata( $user_to_notify ); 
		$email 		=  $user_to_notify_data->user_email;

		$user_who_send_data = get_userdata( $user_who_send ); 
		$sender = $user_who_send_data->first_name;
		if(empty($sender)){
			$sender = $user_who_send_data->nickname;
		}

		$args = array(
			'user_mail'     => $email,
	        'user_name' 	=> $user_to_notify_data->first_name,
			'sender'		=> $sender,
			'conversation_url' => get_permalink('truelysell_messages_page').'?action=view&conv_id='.$conversation_id,
			);
		$subject 	 = truelysell_fl_framework_getoptions('new_message_notification_email_subject');
		$subject 	 = $this->replace_shortcode( $args, $subject );

		$body 	 = truelysell_fl_framework_getoptions('new_message_notification_email_content');
		
		$body 	 = $this->replace_shortcode( $args, $body );
		global $wpdb;
		$result  = $wpdb->update( 
            $wpdb->prefix . 'truelysell_core_conversations', 
            array( 'notification'  => 'sent' ), 
            array( 'id' => $conversation_id ) 
        );

		if($result){
			self::send( $email, $subject, $body );	
		}

		//mark this converstaito as sent
		
	}
	

	function get_booking_data_emails($args){

		$listing_type = get_post_meta($args['listing_id'],'_listing_type',true);
		$booking_data = array();
		
		switch ($listing_type) {
			case 'rental':
				$booking_data['dates'] = date_i18n(get_option( 'date_format' ), strtotime($args['date_start'])) .' - '. date_i18n(get_option( 'date_format' ), strtotime($args['date_end'])); 
				break;
			case 'service':
		
			
					$meta_value_date = explode(' ', $args['date_start'],2); 
				
					$date_format = get_option( 'date_format' );
			
					$meta_value_stamp_obj = DateTime::createFromFormat('Y-m-d', $meta_value_date[0]);
					if($meta_value_stamp_obj){
						$meta_value_stamp = $meta_value_stamp_obj->getTimestamp();
					} else {
						$meta_value_stamp = false;
					}
					
					$meta_value = date_i18n(get_option( 'date_format' ),$meta_value_stamp);
					
					
					if( isset($meta_value_date[1]) ) { 
						$time = str_replace('-','',$meta_value_date[1]);
						$meta_value .= esc_html__(' at ','truelysell_core'); 
						$meta_value .= date(get_option( 'time_format' ), strtotime($time));

					}
						
					$booking_data['dates'] = $meta_value;
				break;
			case 'event':
					$meta_value = get_post_meta($args['listing_id'],'_event_date', true);
					$meta_value_date = explode(' ', $meta_value,2); 
					
					$date_format = get_option( 'date_format' );
				
					$meta_value_stamp_obj = DateTime::createFromFormat('Y-m-d', $meta_value_date[0]);
					if($meta_value_stamp_obj){
						$meta_value_stamp = $meta_value_stamp_obj->getTimestamp();
					} else {
						$meta_value_stamp = false;
					}
					
					$meta_value = date_i18n(get_option( 'date_format' ),$meta_value_stamp);
					
					
					if( isset($meta_value_date[1]) ) { 
						$time = str_replace('-','',$meta_value_date[1]);
						$meta_value .= esc_html__(' at ','truelysell_core'); 
						$meta_value .= date_i18n(get_option( 'time_format' ), strtotime($time));

					}
					$booking_data['dates'] = $meta_value;
				break;
			
			default:
				# code...
				break;
		}
		
		if( isset($args['expiring']) ) {
			$booking_data['expiring'] = $args['expiring'];
		}
		$booking_details = '';
		$details = json_decode($args['comment']);
		if (isset($details->childrens) && $details->childrens > 0) {
			$booking_data['children'] = sprintf( _n( '%d Child', '%s Children', $details->childrens, 'truelysell_core' ), $details->childrens );
			$booking_details .= $booking_data['children'];
		}
		if (isset($details->adults) && $details->adults > 0) {
			$booking_data['adults'] = sprintf( _n( '%d Guest', '%s Guests', $details->adults, 'truelysell_core' ), $details->adults );
			$booking_details .= $booking_data['adults'];
		}
		if (isset($details->tickets) && $details->tickets > 0) {
			$booking_data['tickets'] = sprintf( _n( '%d Ticket', '%s Tickets', $details->tickets, 'truelysell_core' ), $details->tickets );
			$booking_details .= $booking_data['tickets'];
		}
		
		if (isset($details->service)) {
			$booking_data['service'] = truelysell_get_extra_services_html($details->service);
		}
		
		//client data
		if (isset($details->first_name)) {
			$booking_data['client_first_name'] = $details->first_name;
		}
		if (isset($details->last_name)) {
			$booking_data['client_last_name'] = $details->last_name;
		}
		if (isset($details->email)) {
			$booking_data['client_email'] = $details->email;
		}
		if (isset($details->phone)) {
			$booking_data['client_phone'] = $details->phone;
		}


		if( isset($details->billing_address_1) ) {
			$booking_data['billing_address'] = $details->billing_address_1;
		}
		if( isset($details->billing_postcode) ) {
			$booking_data['billing_postcode'] = $details->billing_postcode;
		}
		if( isset($details->billing_city) ) {
			$booking_data['billing_city'] = $details->billing_city;
		}
		if( isset($details->billing_country) ) {
			$booking_data['billing_country'] = $details->billing_country;
		}

		if( isset($details->message) ) {
			$booking_data['user_message'] = $details->message;
		}


		if( isset($details->price) ) {
			$booking_data['price'] = $details->price;
		}



		$booking_data['details'] = $booking_details;

		return $booking_data;
		
	}
	/**
	 * general function to send email to agent with specify subject, body content
	 */
	public static function send( $emailto, $subject, $body ){

		$from_name 	= truelysell_fl_framework_getoptions('emails_name');
		$from_email = truelysell_fl_framework_getoptions('emails_from_email');
		$headers 	= sprintf( "From: %s <%s>\r\n Content-type: text/html", $from_name, $from_email );

		if( empty($emailto) || empty( $subject) || empty($body) ){
			return ;
		}
		$subject = html_entity_decode($subject);
		$template_loader = new truelysell_core_Template_Loader;
		ob_start();

			$template_loader->get_template_part( 'emails/header' ); ?>
			<tr>
				<td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 25px; padding-right: 25px; padding-bottom: 28px; width: 87.5%; font-size: 16px; font-weight: 400; 
				padding-top: 28px; 
				color: #666;
				font-family: sans-serif;" class="paragraph">
				<?php echo $body;?>
				</td>
			</tr>
		<?php
			$template_loader->get_template_part( 'emails/footer' ); 
		$content = ob_get_clean();
			
		wp_mail( @$emailto, @$subject, @$content, $headers );

	}

	public function replace_shortcode( $args, $body ) {

		$tags =  array(
			'user_mail' 	=> "",
			'user_name' 	=> "",
			'booking_date' => "",
			'listing_name' => "",
			'listing_url' => '',
			'listing_address' => '',
			'listing_phone' => '',
			'listing_email' => '',
			'site_name' => '',
			'site_url'	=> '',
			'payment_url'	=> '',
			'expiration'	=> '',
			'dates'	=> '',
			'children'	=> '',
			'adults'	=> '',
			'user_message'	=> '',
			'tickets'	=> '',
			'service'	=> '',
			'details'	=> '',
			'login'	=> '',
			'password'	=> '',
			'first_name'	=> '',
			'last_name'	=> '',
			'login_url'	=> '',
			'sender'	=> '',
			'conversation_url'	=> '',
			'client_first_name' => '',
			'client_last_name' => '',
			'client_email' => '',
			'client_phone' => '',
			'billing_address' => '',
			'billing_postcode' => '',
			'billing_city' => '',
			'billing_country' => '',
			'price' => '',
			'expiring' => '',
		);
		$tags = array_merge( $tags, $args );

		extract( $tags );

		$tags 	= array( '{user_mail}',
						'{user_name}',
						'{booking_date}',
						'{listing_name}',
						'{listing_url}',
						'{listing_address}',
						'{listing_phone}',
						'{listing_email}',
						'{site_name}',
						'{site_url}',
						'{payment_url}',
						'{expiration}',
						'{dates}',
						'{children}',
						'{adults}',
						'{user_message}',
						'{tickets}',
						'{service}',
						'{details}',
						'{login}',
						'{password}',
						'{first_name}',
						'{last_name}',
						'{login_url}',
						'{sender}',
						'{conversation_url}',
						'{client_first_name}',
						'{client_last_name}',
						'{client_email}',
						'{client_phone}',
						'{billing_address}',
						'{billing_postcode}',
						'{billing_city}',
						'{billing_country}',
						'{price}',
						'{expiring}',
						);

		$values  = array(   $user_mail, 
							$user_name ,
							$booking_date,
							$listing_name,
							$listing_url,
							$listing_address,
							$listing_phone,
							$listing_email,
							get_bloginfo( 'name' ) ,
							get_home_url(), 
							$payment_url,
							$expiration,
							$dates,
							$children,
							$adults,
							$user_message,
							$tickets,
							$service,
							$details,
							$login,
							$password,
							$first_name,
							$last_name,
							$login_url,
							$sender,
							$conversation_url,
							$client_first_name,
							$client_last_name,
							$client_email,
							$client_phone,
							$billing_address,
							$billing_postcode,
							$billing_city,
							$billing_country,
							$price,
							$expiring,
		);
	
		$message = str_replace($tags, $values, $body);	
		
		$message = nl2br($message);
		$message = htmlspecialchars_decode($message,ENT_QUOTES);

		return $message;
	}
}
?>