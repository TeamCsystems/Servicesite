<?php 
if(isset($data)) :

 
endif;
if($data->comment == 'owner reservations'){
	return;
} 
$class = array();
$tag = array();
$show_approve = false;
$show_reject = false;
$show_cancel = false;

$payment_method = '';
if(isset($data->order_id) && !empty($data->order_id) && $data->status == 'confirmed'){
	$payment_method = get_post_meta( $data->order_id, '_payment_method', true );
	if(truelysell_fl_framework_getoptions('disable_payments')){
		$payment_method = 'cod';
	}
}
$_payment_option = get_post_meta($data->listing_id, '_payment_option', true);
if (empty($_payment_option)) {
	$_payment_option = 'pay_now';
}
if($_payment_option == "pay_cash"){
	$payment_method = 'cod';
}



switch ($data->status) {
	case 'waiting' :
		$class[] = 'waiting-booking';
		$tag[] = '<span class="badge badge-pill badge-prof badge-warning booking-status pending">'.esc_html__('Pending', 'truelysell_core').'</span>';
		$show_approve = true;
		$show_reject = true;
		$show_renew = false;
	break;

	case 'pay_to_confirm' :
		$class[] = 'waiting-booking';		
		if($data->price>0){
			$tag[] = '<span class="booking-status unpaid">'.esc_html__('Waiting for user payment', 'truelysell_core').'</span>';	
		}
		
		$show_approve = false;
		$show_reject = false;
		$show_renew = false;
		$show_cancel = true;
	break;

	case 'confirmed' :
		$class[] = 'approved-booking';
		$tag[] = '<span  class="booking-status badge badge-pill badge-prof badge-success">'.esc_html__('Approved', 'truelysell_core').'</span>';
		
		if($data->price>0){
			if($_payment_option == "pay_cash"){
				$tag[] = '<span class="booking-status unpaid">' . esc_html__('Cash payment', 'truelysell_core') . '</span>';	
			} else {
				$tag[] = '<span class="booking-status badge badge-pill badge-prof badge-danger unpaid">' . esc_html__('Unpaid', 'truelysell_core') . '</span>';	
			}
			
		}
		
		$show_approve = false;
		$show_reject = false;
		$show_renew = false;
		$show_cancel = true;
	break;

	case 'paid' :

		$class[] = 'approved-booking';
		$tag[] = '<span class="booking-status badge badge-pill badge-prof badge-success">'.esc_html__('Approved', 'truelysell_core').'</span>';
		if($data->price>0){
			$tag[] = '<span class="booking-status paid badge badge-pill badge-prof badge-success">'.esc_html__('Paid', 'truelysell_core').'</span>';
		}
		$show_approve = false;
		$show_renew = false;
		$show_reject = false;
		$show_cancel = false;
	break;

	case 'cancelled' :

		$class[] = 'canceled-booking';
		$tag[] = '<span class="booking-status badge badge-pill badge-prof badge-danger">'.esc_html__('Cancelled', 'truelysell_core').'</span>';
		$show_approve = false;
		$show_reject = false;
		$show_renew = false;
		$show_delete = true;
	break;
	case 'expired' :

		$class[] = 'expired-booking';
		$tag[] = '<span class="booking-status badge badge-pill badge-prof badge-warning">'.esc_html__('Expired', 'truelysell_core').'</span>';
		$show_approve = false;
		$show_reject = false;
		$show_renew = true;
		$show_delete = true;
	break;
	
	default:
		# code...
		break;
}

//get order data
if($data->status != 'paid' && isset($data->order_id) && !empty($data->order_id) && $data->status == 'confirmed'){
	$order = wc_get_order( $data->order_id );
	if($order) {
		$payment_url = $order->get_checkout_payment_url();
	
		$order_data = $order->get_data();

		$order_status = $order_data['status'];
	}
	if (new DateTime() > new DateTime($data->expiring) ) {
   	 $payment_url = false;
   	 $class[] = 'expired-booking';
   	 unset($tag[1]);
   	 $tag[] = '<span class="booking-status">'.esc_html__('Expired', 'truelysell_core').'</span>';
   	 $show_delete = true;
	}
}
?>

 
<div class="booking-list <?php echo implode(' ',$class); ?>" id="booking-list-<?php echo esc_attr($data->ID);?>">
	<div class="booking-widget">
		<?php if (has_post_thumbnail( $data->listing_id ) ): ?>
			<div class="booking-img">
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $data->listing_id ), 'single-post-thumbnail' ); ?>
			<a href="<?php echo get_permalink($data->listing_id); ?>" class="booking-img"><img src="<?php echo $image[0]; ?>" alt="User Image"></a>
			</div>
			<?php else : ?>
			<div class="booking-img">
			<img src="<?php echo get_truelysell_core_placeholder_image(); ?>" >
			</div>
			<?php endif; ?>
		<div class="booking-det-info">
			<h3 id="title"><a href="<?php echo get_permalink($data->listing_id); ?>"><?php echo get_the_title($data->listing_id); ?></a><?php echo implode(' ',$tag); ?></h3>
			<ul class="booking-details">
				<li>
				<span class="book-item"><?php esc_html_e('Booking Date', 'truelysell_core'); ?></span> :
				<?php 
				$listing_type = get_post_meta($data->listing_id,'_listing_type', true);
				if($listing_type == 'service') { 
						?>
								<?php echo date_i18n(get_option( 'date_format' ), strtotime($data->date_start)); ?> 
								<?php 
									$time_start = date_i18n(get_option( 'time_format' ), strtotime($data->date_start));
									$time_end = date_i18n(get_option( 'time_format' ), strtotime($data->date_end));?>

						
						<?php } ?>
						 
				</li>
				<li>
					<span class="book-item"><?php esc_html_e('Booking Time', 'truelysell_core'); ?></span> :
					<?php 
					$listing_type = get_post_meta($data->listing_id,'_listing_type', true);
					if($listing_type == 'service') { 
							?>
									
									<?php 
										$time_start = date_i18n(get_option( 'time_format' ), strtotime($data->date_start));
										$time_end = date_i18n(get_option( 'time_format' ), strtotime($data->date_end));?>

									<?php echo $time_start ?> <?php if($time_start != $time_end) echo '- '.$time_end; ?>
						
						<?php } ?>
				</li>
				<li>
					<span class="book-item"><?php esc_html_e('Amount', 'truelysell_core'); ?></span> :
					<?php
					$currency_abbr = truelysell_fl_framework_getoptions('currency' );
					$currency_postion = truelysell_fl_framework_getoptions('currency_postion' );
					$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
					$decimals = truelysell_fl_framework_getoptions('number_decimals');

					if($data->price): ?>
								
								<?php if($currency_postion == 'before') { echo $currency_symbol.''; } ?><?php 	
								if(is_numeric($data->price)){
									echo number_format_i18n($data->price,$decimals);
								} else {
									echo esc_html($data->price);
								}; ?>
								<?php if($currency_postion == 'after') { echo ' '.$currency_symbol; }  ?>
					<?php endif; ?>	
				</li>

				<?php  $details = json_decode($data->comment); 
$order = new WC_Order($data->order_id); // Order id
$datas  = $order->get_data();
if($order->get_shipping_country()!='') {
$get_country  = WC()->countries->countries[ $order->get_shipping_country() ];
}

if (isset($details->billing_address_1) && !empty($details->billing_address_1)) {  ?>
<li><span  class="book-item"><?php esc_html_e('Location', 'truelysell_core'); ?></span> : 

  		 <?php if(isset($details->billing_address_1)) echo esc_html(stripslashes($details->billing_address_1)).','; ?> 
		 <?php if(isset($details->billing_city)) echo esc_html(stripslashes($details->billing_city)).','; ?>
		 <?php if(isset($get_country)) echo esc_html(stripslashes($get_country)).','; ?>
		 <?php if(isset($details->billing_postcode)) echo esc_html(stripslashes($details->billing_postcode)); ?>
</li>
 <?php } else { ?>

	<?php $address = get_post_meta( $data->listing_id, '_address', true ); 
					if($address) {  ?>
				<li>
					<?php $address = get_post_meta( $data->listing_id, '_address', true );  ?>
					
						<span class="book-item"><?php esc_html_e('Location', 'truelysell_core'); ?></span> :
							<?php echo esc_html($address); ?>
					
				</li>
				<?php } } ?>

				
				<li>
					 
					<span class="book-item"><?php esc_html_e('Customer', 'truelysell_core'); ?></span> :
					<div class="user-book">
					<div class="avatar avatar-xs">
						<?php echo get_avatar($data->bookings_author, '26', '', '', array('class' => 'avatar-img rounded-circle')) ?>
					</div>
					<?php  $details = json_decode($data->comment); 
					if( isset($details->first_name) || isset($details->last_name) ) : ?>
							<?php if(isset($details->first_name)) echo esc_html(stripslashes($details->first_name)); ?> <?php 
							    if(isset($details->last_name)) echo esc_html(stripslashes($details->last_name)); ?>
					<?php endif; ?>
					</div>
				 
 					<?php if(get_the_author_meta('user_email', $data->bookings_author)) {  ?>
						<p><?php echo get_the_author_meta('user_email', $data->bookings_author); ?> </p><?php 
 					if( isset($details->phone)) : ?>
						<p><?php echo esc_html($details->phone); ?> </p>
					<?php endif; ?> 
                       <?php } ?>

					   
					   

				</li>
				

			</ul>
		</div>
	</div>
	<div class="buttons-to-right booking-action">
	       <!--Assign Staff for a service-->
		<span><?php esc_html_e('Assign Staff','truelysell_core') ?></span>
		<?php 
		
		$user_id = get_current_user_id();
		global $wpdb;
		$table_name = $wpdb->prefix . 'staffs'; 

		// Query the database for data associated with the current user
		$query = $wpdb->prepare("SELECT * FROM $table_name WHERE current_userid = %d AND status = 'active'", $user_id);
		$results = $wpdb->get_results( $query );

		?><select class="form-select" id="staff_select">
		  <option><?php esc_html_e('Choose Staff..','truelysell_core') ?></option>
		  <?php
		  foreach($results as $result ){
		    $resultArray = (array) $result;
		    $staff_id = $resultArray['id'];
		    $staff_name = $resultArray['staff_name'];
		    $booking_id = $data->ID;
		    $table_names = $wpdb->prefix . 'bookings_calendar';
		    $additional_query = $wpdb->prepare("SELECT * FROM $table_names WHERE staff_id = %d", $staff_id);
    $booking_result = $wpdb->get_results( $additional_query );
    
    foreach ($booking_result as $booking_results) {
    print_r($booking_results);
        $assigned_booking_id = $booking_results->ID; 
        $assigned_staff_id = $booking_results->staff_id; 
        $assigned_staff_name = $booking_results->staff_name; 
    }
    if($assigned_staff_id == $staff_id ){
    echo '<option value="' . esc_attr($assigned_staff_id) . '" data-booking-id="' . esc_attr($assigned_booking_id) . '"data-staff-name="' . esc_attr($assigned_staff_name) . '" class="yes" selected>' . esc_html($assigned_staff_name) . '</option>';}
    else{
		    echo '<option value="' . esc_attr($staff_id) . '" data-booking-id="' . esc_attr($booking_id) . '"data-staff-name="' . esc_attr($staff_name) . '">' . esc_html($staff_name) . '</option>';
		    }
		  }
		  ?>
		</select>
		<!-----End------>
		<a data-bs-toggle="modal" data-bs-target="#booking_messages" data-recipient="<?php echo esc_attr($data->bookings_author); ?>" data-booking_id="booking_<?php echo esc_attr($data->ID); ?>" class="btn btn-sm btn btn-primary booking-message rate-review popup-with-zoom-anim"><i class="far fa-eye me-2"></i><?php esc_attr_e('Chat','truelysell_core') ?></a>
		<?php if($payment_method == 'cod'){ ?>
			<a href="#" class="button gray mark-as-paid" data-booking_id="<?php echo esc_attr($data->ID); ?>"><i class="sl sl-icon-check me-2"></i><?php esc_html_e('Confirm Payment', 'truelysell_core'); ?></a>
		<?php } ?>

		<?php if($show_reject) : ?>
			<a href="#" class="btn btn-sm badge badge-danger button gray reject" data-booking_id="<?php echo esc_attr($data->ID); ?>"><i class="fas fa-times me-2"></i><?php esc_html_e('Reject', 'truelysell_core'); ?></a>
		<?php endif; ?>

		<?php if($show_cancel) : ?>
			<a href="#" class="button btn btn-sm badge badge-danger gray cancel" data-booking_id="<?php echo esc_attr($data->ID); ?>"><i class="fas fa-times me-2"></i><?php esc_html_e('Cancel', 'truelysell_core'); ?></a>
		<?php endif; ?>

		<?php if(isset($show_delete) && $show_delete == true) : ?>
			<a href="#" class="button gray delete btn btn-sm badge badge-danger" data-booking_id="<?php echo esc_attr($data->ID); ?>"><i class="far fa-trash-alt me-2"></i><?php esc_html_e('Delete', 'truelysell_core'); ?></a>
		<?php endif; ?>

		<?php if($show_approve) : ?>
			<a href="#" class="btn btn-sm badge badge-success button gray approve" data-booking_id="<?php echo esc_attr($data->ID); ?>"><i class="fas fa-check me-2"></i><?php esc_html_e('Approve', 'truelysell_core'); ?></a>
		<?php endif; ?>
		<?php if($show_renew) : ?>
			<a href="#" class="button gray renew_booking badge badge-warning" data-booking_id="<?php echo esc_attr($data->ID); ?>"><i class="sl sl-icon-check me-2"></i><?php esc_html_e('Renew', 'truelysell_core'); ?></a>
		<?php endif; ?>
	</div>
</div>
