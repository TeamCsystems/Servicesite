<?php 
if(isset($data)) :

 
endif;
if($data->comment == 'owner reservations'){
	return;
}
if (isset($data->order_id) && !empty($data->order_id) && $data->status == 'confirmed') {
	$payment_method = get_post_meta($data->order_id, '_payment_method', true);
	if (truelysell_fl_framework_getoptions('disable_payments')) {
		$payment_method = 'cod';
	}
}

$_payment_option = get_post_meta($data->listing_id, '_payment_option', true);
if (empty($_payment_option)) {
	$_payment_option = 'pay_now';
}
if ($_payment_option == "pay_cash") {
	$payment_method = 'cod';
}

$class = array();
$tag = array();
$show_approve = false;
$show_reject = false;
switch ($data->status) {
	case 'waiting' :
		$class[] = 'waiting-booking';
		$tag[] = '<span class="booking-status badge badge-pill badge-prof bg-primary pending">'.esc_html__('Waiting for confirmation', 'truelysell_core').'</span>';
		$show_approve = true;
		$show_reject = true;
	break;
	case 'pay_to_confirm' :
		if($data->price>0){
			if ($_payment_option == "pay_cash") {
				$tag[] = '<span class="booking-status unpaid">' . esc_html__('Cash payment', 'truelysell_core') . '</span>';
			} else {
				$tag[] = '<span class="booking-status unpaid badge badge-pill badge-prof badge-danger">' . esc_html__('Unpaid', 'truelysell_core') . '</span>';
			}
		}
		$show_approve = false;
		$show_reject = true;
	break;

	case 'confirmed' :
		$class[] = 'approved-booking';
		$tag[] = '<span  class="booking-status badge badge-pill badge-prof badge-success">'.esc_html__('Approved', 'truelysell_core').'</span>';
		if($data->price>0){
			if ($_payment_option == "pay_cash") {
				$tag[] = '<span class="booking-status unpaid">' . esc_html__('Cash payment', 'truelysell_core') . '</span>';
			} else {
				$tag[] = '<span class="badge badge-pill badge-prof badge-danger booking-status unpaid">' . esc_html__('Unpaid', 'truelysell_core') . '</span>';
			}
		}
		$show_approve = false;
		$show_reject = true;
	break;

	case 'paid' :

		$class[] = 'approved-booking';
		$tag[] = '<span class="booking-status badge badge-pill badge-prof badge-success">'.esc_html__('Approved', 'truelysell_core').'</span>';
		if($data->price>0){
			$tag[] = '<span class="booking-status badge badge-pill badge-prof badge-success paid">'.esc_html__('Paid', 'truelysell_core').'</span>';
		}
		$show_approve = false;
		$show_reject = false;
	break;

	case 'cancelled' :

		$class[] = 'canceled-booking';
		$tag[] = '<span class="booking-status badge badge-pill badge-prof badge-danger">'.esc_html__('Cancelled', 'truelysell_core').'</span>';
		$show_approve = false;
		$show_reject = false;
		$show_delete = true;
	break;
	case 'expired' :

		$class[] = 'expired-booking';
		$tag[] = '<span class="booking-status badge badge-pill badge-prof badge-warning">'.esc_html__('Expired', 'truelysell_core').'</span>';
		$show_approve = false;
		$show_reject = false;
		$show_delete = true;
	break;
	
	default:
		if ( Truelysell_Core_Bookings_Calendar::is_booking_external( $data->status ) ) {
			$class[]      = 'external-booking';
			$tag[]        = '<span class="booking-status" style="background-color:gray">' . esc_html__( 'External', 'truelysell_core' ) . '</span>';
			$show_approve = false;
			$show_reject  = false;
			$show_delete  = false;
			break;
		}
		# code...
		break;
}
 

//get order data
if(isset($data->order_id) && !empty($data->order_id) && in_array($data->status, array('confirmed','pay_to_confirm'))){
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
<div class="booking-list 2 <?php echo implode(' ',$class); ?>" id="booking-list-<?php echo esc_attr($data->ID);?>">
	<div class="booking-widget">
		<?php if (has_post_thumbnail( $data->listing_id ) ): ?>
		<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $data->listing_id ), 'single-post-thumbnail' ); ?>
		<div class="booking-img">
		<a href="<?php echo get_permalink($data->listing_id); ?>" ><img src="<?php echo $image[0]; ?>" alt="User Image"></a>
		</div>
		<?php else : ?>
			<div class="booking-img">
			<img src="<?php echo get_truelysell_core_placeholder_image(); ?>" >
			</div>
		<?php endif; ?>
		<div class="booking-det-info">
			<h3><a href="<?php echo get_permalink($data->listing_id); ?>"><?php echo get_the_title($data->listing_id); ?></a> <?php echo implode(' ',$tag); ?> </h3>
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
					<span  class="book-item"><?php esc_html_e('Booking Time', 'truelysell_core'); ?></span> : 
					<?php 
					$listing_type = get_post_meta($data->listing_id,'_listing_type', true);
					if($listing_type == 'service') { 
						?>
 								<?php 
									$time_start = date_i18n(get_option( 'time_format' ), strtotime($data->date_start));
									$time_end = date_i18n(get_option( 'time_format' ), strtotime($data->date_end));?>

								<?php echo esc_html($time_start); ?> <?php if($time_start != $time_end) echo '- '.$time_end; ?>
						
						<?php } ?>
						
						
				</li>
				<li>
					<?php 
					$currency_abbr = truelysell_fl_framework_getoptions('currency' );
					$currency_postion = truelysell_fl_framework_getoptions('currency_postion' );
					$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
					$decimals = truelysell_fl_framework_getoptions('number_decimals');

					if($data->price): ?>
						<span  class="book-item"><?php esc_html_e('Amount', 'truelysell_core'); ?></span> : 
								
						<?php if($currency_postion == 'before') { echo $currency_symbol.''; } ?><?php 
								if(is_numeric($data->price)){
									echo number_format_i18n($data->price,$decimals);
								} else {
									echo esc_html($data->price);
								};
								?>
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
					if($address) { ?>
				<li>
					
						<span  class="book-item"><?php esc_html_e('Location', 'truelysell_core'); ?></span> : 
							<?php echo esc_html($address); ?>
					
					
				</li>
				<?php  } } ?>
				 
			 
				<li>
											<span class="book-item"><?php esc_html_e('Provider', 'truelysell_core'); ?></span> : 
											<div class="user-book">
												<div class="avatar avatar-xs">

												<?php 
												 $owner_id = get_post_field ('post_author', $data->listing_id);
								                $owner_data = get_userdata($owner_id);
								        	?>
								         	<?php 
  									           echo get_avatar($owner_id, 26, '', '', array('class' => 'avatar-img rounded-circle'));    
                                             ?> 
											 </div>
 												<?php echo get_the_author_meta('display_name', $owner_id); ?>
											</div>
											<?php if(get_the_author_meta('user_email', $owner_id)) {  ?>
											<p><?php echo get_the_author_meta('user_email', $owner_id); ?></p>
											<?php } ?>
											<?php 
					$details = json_decode($data->comment);
					if( isset($details->phone)) : ?>
						 
						 <p><?php echo esc_html($details->phone); ?></p>
					<?php endif; ?>
										</li>

				
			</ul>
		</div>
	</div><!-- booking-widget -->
	 
	<div class="booking-action buttons-to-right">
		<?php 
		if ( false === Truelysell_Core_Bookings_Calendar::is_booking_external( $data->status ) ): ?>
			<a data-bs-toggle="modal" data-bs-target="#booking_messages" data-recipient="<?php echo esc_attr($data->owner_id); ?>" data-booking_id="booking_<?php echo esc_attr($data->ID); ?>" class="btn btn-sm btn btn-primary booking-message rate-review popup-with-zoom-anim"><i class="far fa-eye me-2"></i><?php esc_attr_e('Chat','truelysell_core') ?></a>
		<?php endif; 
		if(isset($payment_url) && !empty($payment_url) && !truelysell_fl_framework_getoptions('disable_payments') && $_payment_option != 'pay_cash' ) :
			if($order_status != 'completed') : ?>
			<a href="<?php echo esc_url($payment_url) ?>" class="btn btn-sm badge-danger button green pay"><i class="fas fa-check  me-2"></i><?php esc_html_e('Pay', 'truelysell_core'); ?></a>
		<?php endif; 
		endif; ?>
		<?php if(isset($show_delete) && $show_delete == true) : ?>
			<a href="#" class="btn btn-sm badge-danger button gray delete" data-booking_id="<?php echo esc_attr($data->ID); ?>"><i class="far fa-trash-alt me-2"></i><?php esc_html_e('Delete', 'truelysell_core'); ?></a>
		<?php endif; ?>
		<?php if($show_reject) : ?>
		<a href="#" class="btn btn-sm badge-danger button gray reject" data-booking_id="<?php echo esc_attr($data->ID); ?>"><i class="fas fa-times me-2"></i><?php esc_html_e('Cancel', 'truelysell_core'); ?></a>
		<?php endif; ?>
		
	</div>
</div>