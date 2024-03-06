<?php $template_loader = new Truelysell_Core_Template_Loader; 
$current_user = wp_get_current_user();
$roles = $current_user->roles;
$role = array_shift( $roles ); 

$type='';

if(isset($data->type)){
	if($data->type == 'user_booking') { $type="user"; }
}?>
<div class="row">
	<!-- Listings -->
<div class="col-lg-12 col-md-12">
	<div class="dashboard-list-box  margin-top-0">
	<!-- Booking Requests Filters     -->
<div class="booking-requests-filter">
<?php if( $type == "user") : ?>
	<input type="hidden" id="dashboard_type" name="dashboard_type" value="user">
<?php endif; ?>
<?php if( ( $type !== "user" && !isset($_GET['status']) ) || ( $type!== "user" && isset($_GET['status']) && $_GET['status'] == 'approved')) : ?>
<!-- Sort by -->
<div class="sort-by-status" style="display: none;">
	<div class="sort-by-select">
		<select data-placeholder="<?php esc_attr_e('Default order',) ?>" class="select2-bookings-status" id="listing_status">
		   <option value="approved"><?php echo esc_html__( 'All Statuses', ) ?></option>	
		   <option value="confirmed"><?php echo esc_html__( 'Unpaid', ) ?></option>	
		   <option value="paid"><?php echo esc_html__( 'Paid', ) ?></option>	
		</select>
	</div>
</div>
<?php endif; ?>
<?php if(isset($_GET['status']) && $_GET['status'] != 'approved'){ ?>
	<input type="hidden" id="listing_status" value="<?php echo $_GET['status']; ?>">
<?php } ?>
<?php if( $type!== "user" && isset($data->listings) && !empty($data->listings)) : ?>
<?php endif; ?>
<!-- Date Range -->
<div id="booking-date-range-enabler" style="display: none;">
    <span><?php esc_html_e('Pick a Date',); ?></span>
</div>
		
<div id="booking-date-range" style="display: none;">
    <span></span>
</div>

		<!-- Reply to review popup -->
		<ul id="no-bookings-information" class="alert alert-info" style="display: none">
			<?php esc_html_e( 'We haven\'t found any bookings for that criteria', 'truelysell_core' ); ?>
		</ul>
		<?php if(isset($data->bookings) && empty($data->bookings)) { ?>
			<div class="alert alert-info">
			<?php esc_html_e( 'You don\'t have any bookings yet', 'truelysell_core' ); ?>
			</div>
		<?php } else { ?>
		<div class="bookings" id="booking-requests">
			<?php
			foreach ($data->bookings as $key => $value) {
                $value['listing_title'] = get_the_title( $value['listing_id'] );
                if($type == "user" ){
					$template_loader->set_template_data( $value )->get_template_part( 'booking/content-user-booking' );  	
                } else {
                	$template_loader->set_template_data( $value )->get_template_part( 'booking/content-booking' );  	
                }
                
            } ?>
		</div>
		<?php } ?>
		
	</div>
	</div>
	<div class="pagination-container ">
		<?php echo truelysell_core_ajax_pagination( $data->pages, 1  ); ?>
	</div>

	<div class="modal fade custom-modal" id="booking_messages" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Send Message', 'truelysell_core'); ?></h5>
		<button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="feather-x"></i></button>
      </div>
      <div class="modal-body">
	  <form action="" id="send-message-from-widget" class="booking_message" data-booking_id="">
		<div class="form-group">
					<textarea 
					data-recipient=""  
					data-referral="" 
					required 
					cols="40" id="contact-message" class="form-control" name="message" rows="3" placeholder="<?php esc_attr_e('Your message','truelysell_core'); // echo $owner_data->first_name; ?>"></textarea>
					</div>
					<button class="btn btn-primary btn-block btn-lg  msg-button">
					<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i><?php esc_html_e('Send Message', 'truelysell_core'); ?></button>	
					<div class="notification closeable success mt-4"></div>
		
				</form>
      </div>
      
    </div>
  </div>
</div>
 
 </div>
</div>