<?php 
$current_user = wp_get_current_user();	
$user_post_count = count_user_posts( $current_user->ID , 'listing' ); 
$roles = $current_user->roles;
$role = array_shift( $roles ); 

if(!in_array($role,array('administrator','admin','owner','seller'))) :
	$template_loader = new Truelysell_Core_Template_Loader; 
	$template_loader->get_template_part( 'account/owner_only'); 
	return;
endif; 

?>


<!-- Notice -->
<!--  -->

<!-- Content -->
<div class="row">
	<div class="col-xl-4 col-sm-6 col-12 d-flex" id="dashboard-active-listing-tile">
	
						<div class="dash-card flex-fill">
							<div class="dash-header">
								<div class="dash-widget-header mb-0">
									<span class="dash-widget-icon">
										<i class="feather-briefcase"></i>
									</span>
									<div class="dash-widget-info">
						 
									<?php 
		$listings_page = truelysell_fl_framework_getoptions('listings_page');   
		if($listings_page) : ?>
		<a href="<?php echo esc_url(get_permalink($listings_page)); ?>?status=active" class="">
		<?php endif; ?>
		
		<h6><?php esc_html_e('Services','truelysell_core'); ?></h6>
										<h5><?php $user_post_count = count_user_posts( $current_user->ID , 'listing' ); echo $user_post_count; ?></h5>

										<?php if($listings_page) : ?>
	</a>
	<?php endif; ?>

								 
									</div>
									 
								</div>
							 
							</div>
							 
						</div>
   
    </div>

	<?php $total_views = get_user_meta( $current_user->ID, 'truelysell_total_listing_views', true ); ?>

	<div class="col-xl-4 col-sm-6 col-12 d-flex" id="dashboard-active-listing-tile">
 
						<div class="dash-card flex-fill">
							<div class="dash-header">
								<div class="dash-widget-header  mb-0">
									<span class="dash-widget-icon">
										<i class="feather-eye"></i>
									</span>
									<div class="dash-widget-info">
										<h6><?php esc_html_e('Total Views','truelysell_core'); ?></h6>
										<h5><?php echo esc_html($total_views); ?></h5>
									</div>
									 
								</div>
							 
							</div>
							 
 
    </div>
	</div>
	
	<?php 

$author_posts_comments_count = truelysell_count_user_comments(
	array(
		'author_id' => $current_user->ID , // Author ID
		'author_email' => $current_user->user_email, // Author ID
		'approved' => 1, // Approved or not Approved
	)
);
 
?>
<?php $reviews_page = truelysell_fl_framework_getoptions('reviews_page');
  ?>

	<div class="col-xl-4 col-sm-6 col-12 d-flex" id="dashboard-active-listing-tile">
 
						<div class="dash-card flex-fill">
							<div class="dash-header">
								<div class="dash-widget-header  mb-0">
									<span class="dash-widget-icon">
										<i class="feather-star"></i>
									</span>
									<div class="dash-widget-info">
										<?php if($reviews_page): ?>
											<a href="<?php echo esc_url(get_permalink($reviews_page)); ?>" class="">
										<?php endif; ?>
										<h6><?php esc_html_e('Total Reviews','truelysell_core'); ?></h6>
										<h5><?php echo esc_html($author_posts_comments_count); ?></h5>
										<?php if($reviews_page):  ?>
											</a>
	                                     <?php endif; ?>
									</div>
									 
								</div>
							 
							</div>
							 
 
    </div>
	</div>
	 

	<?php 

	$author_posts_comments_count = truelysell_count_user_comments(
	    array(
	        'author_id' => $current_user->ID , // Author ID
	        'author_email' => $current_user->user_email, // Author ID
	        'approved' => 1, // Approved or not Approved
	    )
	);
	 
	?>
	<?php $reviews_page = truelysell_fl_framework_getoptions('reviews_page');
	if($reviews_page):  ?>
	<!-- Item -->
  	<?php endif; ?>
  	<?php if($reviews_page):  ?>

<?php endif; ?>
</div>
 
<div class="widget-title mt-3">
	<h4><?php esc_html_e('Recent Bookings','truelysell_core'); ?></h4>
   </div>
<?php 

global $wpdb;
// setting dates to MySQL style
// filter by parameters from args
$WHERE = '';

$args = array (
	'owner_id' => get_current_user_id(),
	'type' => 'reservation',
	'status' => 'paid',
);
$limit =  4;
if ( is_array ($args) )
{
	foreach ( $args as $index => $value ) 
	{

		$index = esc_sql( $index );
		$value = esc_sql( $value );

		if ( $value == 'approved' ){ 
			$WHERE .= " AND status IN ('confirmed','paid','approved')";
		   
		} else 
		if ( $value == 'waiting' ){ 
			$WHERE .= " AND status IN ('waiting','pay_to_confirm')";
			
		} else {
		  $WHERE .= " AND (`$index` = '$value')";  
		} 
	
	
	}
}
  
// when we searching by created date automaticly we looking where status is not null because we using it for dashboard booking
$data  = $wpdb -> get_results( "SELECT * FROM `" . $wpdb->prefix . "bookings_calendar` WHERE  NOT comment = 'owner reservations' $WHERE ORDER BY `" . $wpdb->prefix . "bookings_calendar`.`created` DESC limit $limit", "ARRAY_A" );

 foreach ($data as $data_listing) {
 ?>

 
<div class="booking-list">
	<div class="booking-widget">
		<?php if (has_post_thumbnail( $data_listing['listing_id']) ): ?>
			<div class="booking-img">
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $data_listing['listing_id']), 'single-post-thumbnail' ); ?>
			<a href="<?php echo get_permalink($data_listing['listing_id']); ?>" class="booking-img">
			<img src="<?php echo $image[0]; ?>" alt="User Image">
		</a>
			</div>

			<?php endif; ?>
		<div class="booking-det-info">
			<h3 id="title"><a href="<?php echo get_permalink($data_listing['listing_id']); ?>"><?php echo get_the_title($data_listing['listing_id']); ?></a> </h3>
			<ul class="booking-details">
				<li>
				<span class="book-item"><?php esc_html_e('Booking Date', 'truelysell_core'); ?></span> :
				<?php 
				$listing_type = get_post_meta($data_listing['listing_id'],'_listing_type', true);
				if($listing_type == 'service') { 
						?>
								<?php echo date_i18n(get_option( 'date_format' ), strtotime($data_listing['date_start'])); ?> 
								<?php 
									$time_start = date_i18n(get_option( 'time_format' ), strtotime($data_listing['date_start']));
									$time_end = date_i18n(get_option( 'time_format' ), strtotime($data_listing['date_end']));?>

						
						<?php } ?>
						 
				</li>
				<li>
					<span class="book-item"><?php esc_html_e('Booking Time', 'truelysell_core'); ?></span> :
					<?php 
					$listing_type = get_post_meta($data_listing['listing_id'],'_listing_type', true);
					if($listing_type == 'service') { 
							?>
									
									<?php 
										$time_start = date_i18n(get_option( 'time_format' ), strtotime($data_listing['date_start']));
										$time_end = date_i18n(get_option( 'time_format' ), strtotime($data_listing['date_end']));?>

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

					if($data_listing['price']): ?>
								
								<?php if($currency_postion == 'before') { echo $currency_symbol.''; } ?><?php 	
								if(is_numeric($data_listing['price'])){
									echo number_format_i18n($data_listing['price'],$decimals);
								} else {
									echo esc_html($data_listing['price']);
								}; ?>
								<?php if($currency_postion == 'after') { echo ' '.$currency_symbol; }  ?>
					<?php endif; ?>	
				</li>
				<?php 
				$address ='';
				if($address) { ?>
				<li>
					<?php $address = get_post_meta( $data_listing['listing_id'], '_address', true );  ?>
					
						<span class="book-item"><?php esc_html_e('Location', 'truelysell_core'); ?></span> :
							<?php echo esc_html($address); ?>
					
				</li>
				<?php } ?>
				<li>
					<span class="book-item"><?php esc_html_e('Customer', 'truelysell_core'); ?></span> :
					<div class="user-book">
					<div class="avatar avatar-xs">
						<?php echo get_avatar($data_listing['bookings_author'], '26', '', '', array('class' => 'avatar-img rounded-circle')) ?>
					</div>
					<?php 
					$details = json_decode($data_listing['comment']); 
					if( isset($details->first_name) || isset($details->last_name) ) : ?>
							<?php if(isset($details->first_name)) echo esc_html(stripslashes($details->first_name)); ?> <?php 
							    if(isset($details->last_name)) echo esc_html(stripslashes($details->last_name)); ?>
					<?php endif; ?>
					</div>

					<?php if(get_the_author_meta('user_email', $data_listing['bookings_author'])) {  ?>
						<p><?php echo get_the_author_meta('user_email', $data_listing['bookings_author']); ?> </p><?php 
 					if( isset($details->phone)) : ?>
						<p><?php echo esc_html($details->phone); ?> </p>
					<?php endif; ?> 
                       <?php } ?>
				</li>
			</ul>
		</div>
	</div>
	 
</div>

<?php } ?>







