<?php

/**
 * Template Name: Staff Profile
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Truelysell
 */
get_header();
    
// Check if the user ID is set in the session
if (is_user_logged_in()) {
    $current_user_id = get_current_user_id() ;
    // You can use $user_id as needed

  //print_r($current_user_id);
//  die;

global $wpdb;

// Query the database for data associated with the current user
$query = $wpdb->prepare("SELECT u.ID,u.display_name, u.user_email,u.user_pass, u.user_status, um1.meta_key AS business_id_key, um1.meta_value AS business_id_value, um2.meta_key AS access_control_key, um2.meta_value AS access_control_value
    FROM {$wpdb->users} AS u
    LEFT JOIN {$wpdb->usermeta} AS um1 ON u.ID = um1.user_id AND um1.meta_key = 'business_id'
    LEFT JOIN {$wpdb->usermeta} AS um2 ON u.ID = um2.user_id AND um2.meta_key = 'access_control'
    WHERE u.ID = %d", $current_user_id);
$results = $wpdb->get_results( $query );
foreach($results as $result ){
    $resultArray = (array) $result;
    /*echo"<pre>";
    print_r($resultArray);
    echo"</pre>";*/
}
if(empty($query)){

    do_action('truelysell_login_form');
}
else { //is logged

	// get_header('dashboard');
   $staff_id = $resultArray['ID'];
  $current_business_id = $resultArray['business_id_value'];
    $staff_email = $resultArray['user_email'];
    $staff_name = $resultArray['display_name'];
    $staff_access = $resultArray['access_control_value'];
	$staff_status = $resultArray['user_status'];
	$staff_booking_id=  $resultArray['booking_id'];
	$staff_assign=  $resultArray['assign_status'];

?>
	<!-- Dashboard -->
	<div class="content">
	 
		<div class="container">
		<?php
$login_success_message = get_transient('login_success_message');
if ($login_success_message) {
    echo '<div class="success-message" style="color: green;">' . esc_html($login_success_message) . '</div>';
    delete_transient('login_success_message'); 
}
?>		<div class="row">
			<div class="col-md-4 col-lg-3 theiaStickySidebar dashboard-nav">
				<div class="settings-widget">
					<div class="settings-header">
						<div class="settings-img">
						<img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2024/02/profile.png" width="85px"/>
						</div>
						<h6><?php echo esc_html($staff_name);?></h6>
					</div>
 
				<div class="widget settings-menu sidebar-menu" id="sidebar-menu">
					<ul >
					
					
					
						
							
						<li class=><a  href="<?php echo esc_url( home_url( '/staff-dashboard' ) )?>" ><i class="feather-book"></i> <?php esc_html_e('My Bookings', 'truelysell'); ?></a></li>
						<?php if (($staff_access == '1')) : ?>
						<li class=""><a href="<?php echo esc_url( home_url( '/staff-all-bookings' ) )?>" ><i class="feather-users"></i> <?php esc_html_e('All Bookings', 'truelysell'); ?></a></li>
					<?php endif; ?>
						
						<li class="active"><a href="<?php echo esc_url( home_url( '/staff-profile' ) )?>" ><i class="feather-user"></i> <?php esc_html_e('My Profile', 'truelysell'); ?></a></li>
					
					
					<!-- Logout -->
					<li>
					    <a href="<?php echo wp_logout_url(home_url()); ?>">
						<i class="feather-log-out"></i> <?php esc_html_e('Logout', 'truelysell'); ?>
					    </a>
					</li>



				</ul>
				</div>
		
				
				
			
			</div>
			
			<!-- Content
			================================================== -->
			
			
			
		</div>
		<div class="col-md-8 col-lg-9 dashboard-nav">
				<div id="main">
				<?php the_content();
				echo do_shortcode('[get_staff_datas]'); ?>
    					
		</div>
		
	      </div>
		<!-- Navigation / End -->
	  </div>
	</div>
	<!-- Dashboard / End -->
<?php
}
	get_footer();
}
else{
    do_action('truelysell_login_form');
}
?>

