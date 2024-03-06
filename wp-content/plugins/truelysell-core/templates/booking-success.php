<?php
if(isset($data->order_id) && $data->order_id ) {
	
	$order = wc_get_order( $data->order_id );
	
	if($order){
		$payment_url = $order->get_checkout_payment_url();	
		$total_value = 	$order->get_total();
	}
	
}
?>
<div class="container">
<div class="row">
	<div class="col-md-12">
<?php
if(isset($data->error) && $data->error == true){  ?>

	<div class="booking-confirmation-page booking-confrimation-error">
 	<h2 class="margin-top-30"><?php esc_html_e('Oops, we have some problem.','truelysell_core'); ?></h2>
	<p><?php echo  $data->message  ?></p>

</div>

<?php } else { ?>

<?php if($data->status =="pay_to_confirm") { ?>
	
	<div class="booking-confirmation-page">
 		<h2 class="margin-top-30"><?php esc_html_e('Please wait while you are redirected to payment page','truelysell_core'); ?></h2>
		<meta http-equiv="refresh" content="1; url=<?php echo $payment_url; ?>">
		<p><?php printf( __( '<a href="%s">Click Here</a> if you do not want to wait.', 'truelysell_core' ), $payment_url ); ?>
        </p>
<?php } else { ?>

	<div class="booking-confirmation-page">
 		<h2 class="margin-top-30"><?php esc_html_e('Thank you for your booking!','truelysell_core'); ?></h2>
		<p><?php echo $data->message  ?></p>

		<?php 
		if(isset($payment_url) && $total_value > 0) { 
			if(!get_option('truelysell_disable_payments')){?>
			<a href="<?php echo esc_url($payment_url); ?>" class="button btn btn-primary mr-2 color"><?php esc_html_e('Pay now','truelysell_core'); ?></a>
		<?php } 
		}?>

		<?php $user_bookings_page = truelysell_fl_framework_getoptions('user_bookings_page');  
		if( $user_bookings_page ) : ?>
		<a href="<?php echo esc_url(get_permalink($user_bookings_page)); ?>" class="button btn btn-primary"><?php esc_html_e('Go to My Bookings','truelysell_core'); ?></a>
		<?php endif; ?>
	</div>

<?php }
} ?>
</div>
</div>
</div>

