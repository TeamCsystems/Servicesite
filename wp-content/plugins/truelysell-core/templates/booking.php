<?php

// get user email
$current_user = wp_get_current_user();

$email = $current_user->user_email;
$first_name =  $current_user->first_name;
$last_name =  $current_user->last_name;


// get meta of listing


// get first images
$gallery = get_post_meta( $data->listing_id, '_gallery', true );
$instant_booking = get_post_meta( $data->listing_id, '_instant_booking', true );
$listing_type = get_post_meta( $data->listing_id, '_listing_type', true );

foreach ( (array) $gallery as $attachment_id => $attachment_url ) 
{
	$image = wp_get_attachment_image_src( $attachment_id, 'truelysell-gallery' );	
	break;
}

if(!$image){
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $data->listing_id ), 'truelysell-gallery' , false );
}

?>
<div class="container">
<div class="row">
	
		<!-- Content
		================================================== -->
		<div class="col-lg-8 col-md-8 padding-right-30">

			<h3 class="mb-4"><?php esc_html_e('Personal Details', 'truelysell_core'); ?></h3>

			<form id="booking-confirmation" action="" method="POST">
			<input type="hidden" name="confirmed" value="yessir" />
			<input type="hidden" name="value" value="<?php echo $data->submitteddata; ?>" />
			<input type="hidden" name="listing_id" id="listing_id" value="<?php echo $data->listing_id; ?>">
			<input type="hidden" name="coupon_code" class="input-text" id="coupon_code" value="<?php if( isset($data->coupon)) echo $data->coupon; ?>" placeholder="<?php esc_html_e('Coupon code','truelysell_core'); ?>"> 
			<div class="row">

				<div class="col-md-6">
					<div class="form-group">
						<label><?php esc_html_e('First Name', 'truelysell_core'); ?></label>
						<input type="text" name="firstname" class="form-control" value="<?php esc_html_e($first_name); ?>" >
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label><?php esc_html_e('Last Name', 'truelysell_core'); ?></label>
						<input type="text" name="lastname" class="form-control" value="<?php esc_html_e($last_name); ?>" >
					</div>
				</div>

				<?php $email_required = truelysell_fl_framework_getoptions('booking_email_required'); ?>
				<div class="col-md-6">
					<div class="form-group">
						<div class="input-with-icon medium-icons">
							<label><?php esc_html_e('E-Mail Address', 'truelysell_core'); ?><span style="color:red;">*</span></label>
							<input type="email" <?php if($email_required) { echo "required"; } ?> name="email" class="form-control" value="<?php esc_html_e($email); ?>" >
 						</div>
					</div>
				</div>
				<?php $phone_required = truelysell_fl_framework_getoptions('booking_phone_required'); ?>
				<div class="col-md-6">
					<div class="form-group">
						<div class="input-with-icon medium-icons">
							<label><?php esc_html_e('Phone', 'truelysell_core'); ?><span style="color:red;">*</span></label>
							<?php if($phone_required == "on"){ ?>
								<span class="text-danger">*</span>
							<?php } ?>
							<input type="number" <?php if($phone_required) { echo "required"; } ?> name="phone" class="form-control hide_arrows" value="<?php esc_html_e( get_user_meta( $current_user->ID, 'billing_phone', true) ); ?> " >
 						</div>
					</div>
				</div>
				<!-- /// -->
				
				<?php if (truelysell_fl_framework_getoptions('add_address_fields_booking_form')) : ?>
					<div class="col-md-6">
					<div class="form-group">
						<label><?php esc_html_e('Street Address', 'truelysell_core'); ?></label>
						<input type="text" name="billing_address_1" value="<?php esc_html_e(get_user_meta($current_user->ID, 'billing_address_1', true)); ?>">
					</div>
					</div>

					<div class="col-md-6">
					<div class="form-group">
						<label><?php esc_html_e('Postcode/ZIP', 'truelysell_core'); ?></label>
						<input type="number" name="billing_postcode" class="form-control hide_arrows" value="<?php esc_html_e(get_user_meta($current_user->ID, 'billing_postcode', true)); ?>">
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">

						<label><?php esc_html_e('Town', 'truelysell_core'); ?></label>
						<input type="text" name="billing_city" value="<?php esc_html_e(get_user_meta($current_user->ID, 'billing_city', true)); ?>">
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label><?php esc_html_e('Country', 'truelysell_core'); ?></label>
						<?php 
						global $woocommerce;
						woocommerce_form_field('billing_country', array('type' => 'country')); ?>
						<!-- <input type="text" name="billing_country" value="<?php esc_html_e(get_user_meta($current_user->ID, 'billing_country', true)); ?>"> -->
					</div>
					</div>
				<?php endif; ?>

				<!-- /// -->
				<div class="col-md-12 margin-top-15">
					<div class="form-group">
						<label><?php esc_html_e('Message', 'truelysell_core'); ?></label>
						<textarea maxlength="200" name="message" class="form-control" placeholder="<?php esc_html_e('Your short message to the service owner (optional)','truelysell_core'); ?>" id="booking_message" cols="20" rows="3"></textarea>
					</div>
				</div>
				</form>
			</div>


			<a href="#" class="button booking-confirmation-btn margin-top-20"><div class="loadingspinner"></div><span class="book-now-text btn btn-primary">
				<?php 
				if(truelysell_fl_framework_getoptions('disable_payments')) {
			 		($instant_booking == 'on') ? esc_html_e('Confirm', 'truelysell_core') : esc_html_e('Confirm and Book', 'truelysell_core') ;  
				} else {
					($instant_booking == 'on') ? esc_html_e('Confirm and Pay', 'truelysell_core') : esc_html_e('Confirm and Book', 'truelysell_core') ;  
				}
			?></span></a>
			
		</div>
	

		<!-- Sidebar
		================================================== -->
		<div class="col-lg-4 col-md-4 margin-top-0 margin-bottom-60">

			 
			<div class="booking-item-conatiner">
			<div class="boxed-widget  opening-hours summary margin-top-0">
				<h4 class="mb-4"><?php esc_html_e('Booking Summary', 'truelysell_core'); ?></h4>
				<?php 
					$currency_abbr = truelysell_fl_framework_getoptions('currency' );
					$currency_postion = truelysell_fl_framework_getoptions('currency_postion' );
					$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
				?>

                          <div class="booking-info p-0 mb-4">
												<div class="service-book">

													<?php if( is_array($image) ) { ?>
													<div class="service-book-img mb-0">
													<img src="<?php echo $image[0]; ?>" alt="" class="img-fluid">
													</div>
													<?php } ?>
													<div class="serv-profile">
 														<h2><?php echo get_the_title($data->listing_id); ?></h2>
														 <p class="mb-0"><?php if(get_the_listing_address($data->listing_id)) { ?><?php the_listing_address($data->listing_id); ?><?php } ?></p>
													</div>
												</div>
	                           </div>
				<ul id="booking-confirmation-summary" class="booking-date">

					<?php if($listing_type == 'event') { ?>
						<li id='booking-confirmation-summary-date'>
							<?php esc_html_e('Date Start', 'truelysell_core'); ?> 
							<span>
								<?php 
									$meta_value = get_post_meta($data->listing_id,'_event_date',true);
									$meta_value_timestamp = get_post_meta($data->listing_id,'_event_date_timestamp',true);
									
									if(!empty($meta_value_timestamp)){
										echo date_i18n(get_option( 'date_format' ), $meta_value_timestamp);
										$meta_value_date = explode(' ', $meta_value,2); 
										$meta_value_date[0] = str_replace('/','-',$meta_value_date[0]);
										if( isset($meta_value_date[1]) ) { 
											$time = str_replace('-','',$meta_value_date[1]);
											$meta_value = esc_html__(' at ','truelysell_core'); 
											$meta_value .= date_i18n(get_option( 'time_format' ), strtotime($time));

										} 
										echo $meta_value; 
									} else {
										$meta_value_date = explode(' ', $meta_value,2); 
										$meta_value_date[0] = str_replace('/','-',$meta_value_date[0]);
										$meta_value = date_i18n(truelysell_date_time_wp_format_php(), strtotime($meta_value_date[0])); 

										if( isset($meta_value_date[1]) ) { 
											$time = str_replace('-','',$meta_value_date[1]);
											$meta_value .= esc_html__(' at ','truelysell_core'); 
											$meta_value .= date_i18n(get_option( 'time_format' ), strtotime($time));

										} 
										echo $meta_value; 

									}
									
								?>
								
							</span>
						</li>
						<?php 
						$meta_value = get_post_meta($data->listing_id,'_event_date_end',true);
						
						if(isset($meta_value) && !empty($meta_value))  : ?>
						<li id='booking-confirmation-summary-date'>
							<?php esc_html_e('Date End', 'truelysell_core'); ?> 
							<span>
								<?php 
									$meta_value = get_post_meta($data->listing_id,'_event_date_end',true);
									$meta_value_end_timestamp = get_post_meta($data->listing_id,'_event_date_end_timestamp',true);
									if(!empty($meta_value_end_timestamp)){
										echo date_i18n(get_option( 'date_format' ), $meta_value_end_timestamp);
										$meta_value_date = explode(' ', $meta_value,2); 

										$meta_value_date[0] = str_replace('/','-',$meta_value_date[0]);
										if( isset($meta_value_date[1]) ) { 
											$time = str_replace('-','',$meta_value_date[1]);
											$meta_value = esc_html__(' at ','truelysell_core'); 
											$meta_value .= date_i18n(get_option( 'time_format' ), strtotime($time));

										} 
										echo $meta_value; 

									} else {
										$meta_value_date = explode(' ', $meta_value,2); 

										$meta_value_date[0] = str_replace('/','-',$meta_value_date[0]);
										$meta_value = date_i18n(get_option( 'date_format' ), strtotime($meta_value_date[0])); 
										
									
										//echo strtotime(end($meta_value_date));
										//echo date( get_option( 'time_format' ), strtotime(end($meta_value_date)));
										if( isset($meta_value_date[1]) ) { 
											$time = str_replace('-','',$meta_value_date[1]);
											$meta_value .= esc_html__(' at ','truelysell_core'); 
											$meta_value .= date_i18n(get_option( 'time_format' ), strtotime($time));

										} echo $meta_value; 
									}
									?>
							</span>
						</li>
						<?php endif; ?>
					<?php } else { ?>

						<li id='booking-confirmation-summary-date'>
							<?php esc_html_e('Date', 'truelysell_core'); ?> <span><?php echo $data->date_start; ?> <?php if ( isset( $data->date_end ) && $data->date_start != $data->date_end ) echo '<b> - </b>' . $data->date_end; ?></span>
						</li>
						<?php if ( isset($data->_hour) ) { ?>
						<li id='booking-confirmation-summary-time'>
							<?php esc_html_e('Time', 'truelysell_core'); ?> <span><?php echo $data->_hour; if(isset($data->_hour_end)) { echo ' - '; echo $data->_hour_end; }; ?></span>
						</li>
						<?php } ?>
						<?php if($listing_type == 'event') { ?>
							<li id='booking-confirmation-summary-time'>
							<?php 

							$event_start = get_post_meta($data->listing_id,'_event_date',true); 

							$event_start_date = explode(' ', $event_start,2); 
						
							if( isset($event_start_date[1]) ) { 
								$time = str_replace('-','',$event_start_date[1]);
								$event_hour_start = date_i18n(get_option( 'time_format' ), strtotime($time));
							} 

							$event_end  = get_post_meta($data->listing_id,'_event_date_end',true);

							$event_start_end = explode(' ', $event_end,2); 
						
							if( isset($event_start_end[1]) ) { 
								$time = str_replace('-','',$event_start_end[1]);
								$event_hour_end = date_i18n(get_option( 'time_format' ), strtotime($time));
							} 
							?>
							<?php esc_html_e('Time', 'truelysell_core'); ?> 
							<span><?php echo $event_hour_start; ?> <?php if ( isset( $event_hour_end ) && $event_hour_start != $event_hour_end ) echo '<b> - </b>' . $event_hour_end; ?></span>
						</li>
						<?php } ?>
					<?php } ?>
					<?php $max_guests = get_post_meta($data->listing_id,"_max_guests",true);  
					if(truelysell_fl_framework_getoptions('remove_guests')){
						$max_guests = 1;
					}
					if(!truelysell_fl_framework_getoptions('remove_guests')) : ?>

					<?php if ( isset( $data->adults ) || isset( $data->childrens ) ) { ?>
						<li id='booking-confirmation-summary-guests'>
							<?php esc_html_e('Guests', 'truelysell_core'); ?> <span><?php if ( isset( $data->adults ) ) echo $data->adults;
							if ( isset( $data->childrens ) ) echo $data->childrens . ' Childrens ';
							?></span>
						</li>
					<?php } 
					
					endif;
					
					if ( isset( $data->tickets )) { ?>
						<li id='booking-confirmation-summary-tickets'>
							<?php esc_html_e('Tickets', 'truelysell_core'); ?> <span><?php if ( isset( $data->tickets ) ) echo $data->tickets;?></span>
						</li>
					<?php } ?>
					
					<?php if( isset($data->services) && !empty($data->services)) { ?>
						<li id='booking-confirmation-summary-services'>
							<h5 id="summary-services"><?php esc_html_e('Additional Services','truelysell_core'); ?></h5>
							<ul>
							<?php 
							$bookable_services = truelysell_get_bookable_services($data->listing_id);
							$i = 0;
							if($listing_type == 'rental') {
								if(isset($data->date_start) && !empty($data->date_start) && isset($data->date_end) && !empty($data->date_end)){

		        					$firstDay = new DateTime( $data->date_start );
	    	    					$lastDay = new DateTime( $data->date_end . '23:59:59') ;

	        						$days = $lastDay->diff($firstDay)->format("%a");
	        						if(get_option('truelysell_count_last_day_booking')){
										$days+=1;
									}
								} else {
									$days = 1;
								} 
							} else {
								$days = 1;
							}
							if(isset($data->adults)){
								$guests = $data->adults;	
							} else{
								$guests = $data->tickets; 
							}
							

							foreach ($bookable_services as $key => $service) {
							
							 
							 	$countable = array_column($data->services,'value');
							 	
							 	if(in_array(sanitize_title($service['name']),array_column($data->services,'service'))) { 
							 		?>
							 		<li>
							 			<span><?php 
										if(empty($service['price']) || $service['price'] == 0) {
											esc_html_e('Free','truelysell_core');
										} else {
											if($currency_postion == 'before') { echo $currency_symbol.' '; } 
											$service_price = truelysell_calculate_service_price($service, $guests, $days, $countable[$i] );
											$decimals = truelysell_fl_framework_getoptions('number_decimals');
											echo number_format_i18n($service_price,$decimals);
											if($currency_postion == 'after') { echo ' '.$currency_symbol; }
										}
										?></span>
										<?php echo esc_html(  $service['name'] ); 
											if( isset($countable[$i]) && $countable[$i] > 1 ) { ?>
												<em>(*<?php echo $countable[$i];?>)</em>
											<?php } ?> 
									</li>
							 	<?php  $i++;
							 	}
							 	
							 }  ?>
						 	</ul>
						</li>
					<?php }
					$decimals = truelysell_fl_framework_getoptions('number_decimals'); ?>
					<?php 
        				$decimals = truelysell_fl_framework_getoptions('number_decimals');
        
					if($data->price>0): ?>

						<li class="total-costs <?php if(isset($data->price_sale)): ?> estimated-with-discount<?php endif;?>" data-price="<?php echo esc_attr($data->price); ?>"><?php esc_html_e('Total Cost', 'truelysell_core'); ?><span> 
						<?php if($currency_postion == 'before') { echo $currency_symbol.' '; } echo number_format_i18n($data->price,$decimals); if($currency_postion == 'after') { echo ' '.$currency_symbol; } ?></span></li>
						<?php endif; ?>	
					<?php if(isset($data->price_sale)): ?>

						<?php $decimals = truelysell_fl_framework_getoptions('number_decimals'); ?>
						<li class="total-discounted_costs"><?php esc_html_e('Final Cost', 'truelysell_core'); ?><span> 
						<?php if($currency_postion == 'before') { echo $currency_symbol.' '; } echo number_format_i18n($data->price_sale,$decimals); if($currency_postion == 'after') { echo ' '.$currency_symbol; } ?></span></li>
						
					<?php else: ?>
						<li style="display:none;" class="total-discounted_costs"><?php esc_html_e('Final Cost', 'truelysell_core'); ?><span> </span></li>
					<?php endif; ?>
				</ul>

			</div>
			</div>
			<!-- Booking Summary / End -->

		</div>
</div>
</div>
