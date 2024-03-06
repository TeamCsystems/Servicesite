<?php

/**
 * Template Name: All Staff Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Truelysell
 */

if (!is_user_logged_in()) {

	$errors = array();

	if (isset($_REQUEST['login'])) {
		$error_codes = explode(',', $_REQUEST['login']);

		foreach ($error_codes as $code) {
			switch ($code) {
				case 'empty_username':
					$errors[] = esc_html__('You do have an email address, right?', 'truelysell');
					break;
				case 'empty_password':
					$errors[] =  esc_html__('You need to enter a password to login.', 'truelysell');
					break;
				case 'username_exists':
					$errors[] =  esc_html__('This username already exists.', 'truelysell');
					break;
				case 'authentication_failed':
				case 'invalid_username':
					$errors[] =  esc_html__(
						"We don't have any users with that email address. Maybe you used a different one when signing up?",
						'truelysell'
					);
					break;
				case 'incorrect_password':
					$err = __(
						"The password you entered wasn't quite right.",
						'truelysell'
					);
					$errors[] =  sprintf($err, wp_lostpassword_url());
					break;
				default:
					break;
			}
		}
	}
	// Retrieve possible errors from request parameters
	if (isset($_REQUEST['register-errors'])) {
		$error_codes = explode(',', $_REQUEST['register-errors']);

		foreach ($error_codes as $error_code) {

			switch ($error_code) {
				case 'email':
					$errors[] = esc_html__('The email address you entered is not valid.', 'truelysell');
					break;
				case 'email_exists':
					$errors[] = esc_html__('An account exists with this email address.', 'truelysell');
					break;
				case 'closed':
					$errors[] = esc_html__('Registering new users is currently not allowed.', 'truelysell');
					break;
				case 'captcha-no':
					$errors[] = esc_html__('Please check reCAPTCHA checbox to register.', 'truelysell');
					break;
				case 'username_exists':
					$errors[] =  esc_html__('This username already exists.', 'truelysell');
					break;
				case 'captcha-fail':
					$errors[] = esc_html__("You're a bot, aren't you?.", 'truelysell');
					break;
				case 'policy-fail':
					$errors[] = esc_html__("Please accept the Privacy Policy to register account.", 'truelysell');
					break;
				case 'terms-fail':
					$errors[] = esc_html__("Please accept the Terms and Conditions to register account.", 'truelysell');
					break;
				case 'first_name':
					$errors[] = esc_html__("Please provide your first name", 'truelysell');
					break;
				case 'last_name':
					$errors[] = esc_html__("Please provide your last name", 'truelysell');
					break;
				case 'empty_user_login':
					$errors[] = esc_html__("Please provide your user login", 'truelysell');
					break;
				case 'password-no':
					$errors[] = esc_html("You have forgot about password.", 'truelysell', 'truelysell');
					break;
				case 'incorrect_password':
					$err = __(
						"The password you entered wasn't quite right. ",
						'truelysell'
					);
					$errors[] =  sprintf($err, wp_lostpassword_url());
					break;
				default:
					break;
			}
		}
	}
	get_header();

	$page_top = get_post_meta($post->ID, 'truelysell_page_top', TRUE);

	switch ($page_top) {
		case 'titlebar':
			get_template_part('template-parts/header', 'titlebar');
			break;

		case 'parallax':
			get_template_part('template-parts/header', 'parallax');
			break;

		case 'off':

			break;

		default:
			get_template_part('template-parts/header', 'titlebar');
			break;
	}

	$layout = get_post_meta($post->ID, 'truelysell_page_layout', true);
	if (empty($layout)) {
		$layout = 'right-sidebar';
	}
	$class  = ($layout != "full-width") ? "col-lg-9 col-md-8 padding-right-30" : "col-md-12"; ?>
	<div class="content">
	<div class="container <?php echo esc_attr($layout); ?>">

		<div class="row">
		<div class="col-md-12 col-lg-12">
			<div class="login-wrap">
 					<?php if (count($errors) > 0) : ?>
						<?php foreach ($errors  as $error) : ?>
							<div class="alert alert-danger">
								<p><?php echo esc_html($error);  ?></p>
								<a class="close"></a>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if (isset($_REQUEST['registered'])) : ?>
						<div class="badge text-bg-success closeable">
							<p>
								<?php
								$password_field = truelysell_fl_framework_getoptions('display_password_field');
								if ($password_field) {
									printf(
										esc_html__('You have successfully registered to %s.', 'truelysell'),
										'<strong>' . get_bloginfo('name') . '</strong>'
									);
								} else {
									printf(
										esc_html__('You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'truelysell'),
										get_bloginfo('name')
									);
								}

								?>
							</p>
						</div>
					<?php endif; ?>
					</div>
	</div>
	</div>
	<div class="clearfix"></div>
     <?php do_action('truelysell_login_form');	 ?>
 </div>
 </div>
		
<?php
	get_footer();
} else { //is logged

	get_header('dashboard');
	$current_user = wp_get_current_user();
	$user_id = get_current_user_id();
	$roles = $current_user->roles;
	$role = array_shift($roles);

	if (!empty($current_user->user_firstname)) {
		$name = $current_user->user_firstname;
	} else {
		$name =  $current_user->display_name;
	}

?>
	<!-- Dashboard -->
	<div class="content">
	 
		<div class="container">
			<div class="row">
			<div class="col-md-4 col-lg-3 theiaStickySidebar dashboard-nav">
				<div class="settings-widget">
				<div class="settings-header">
								<div class="settings-img">
								<?php echo get_avatar($current_user->user_email, 60); ?>
								</div>
								<h6><?php echo esc_html($name);?></h6>
								<p><?php echo esc_html('Member Since','truelysell'); ?> <?php echo date("M Y", strtotime(get_userdata($user_id)->user_registered)); ?></p>
							</div>
 
				<div class="widget settings-menu sidebar-menu" id="sidebar-menu">
					<ul >
					<?php if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) : ?>
						<?php $dashboard_page = truelysell_fl_framework_getoptions('dashboard_page');
						if ($dashboard_page) : ?>
							<li <?php if ($post->ID == $dashboard_page) : ?> <?php endif; ?>><a   href="<?php echo esc_url(get_permalink($dashboard_page)); ?>"><i class="feather-grid"></i><?php esc_html_e('Dashboard', 'truelysell'); ?></a></li>
						<?php endif; ?>
					<?php endif; ?>
					<!-- Staff  -->
					

						<?php if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) : ?>
											
							<li class="active"><a><i class="feather-user"></i> <?php esc_html_e('My Staffs', 'truelysell'); ?> <span class="menu-arrow"></span></a>

								<ul>
									<li>

										<a href="<?php echo get_template_directory_uri(); ?>/add-staff"><?php esc_html_e('Add Staff', 'truelysell'); ?>
										</a>
									</li>
									<li class="active">
										<a href="<?php echo get_template_directory_uri(); ?>/all-staffs"><?php esc_html_e('All Staffs', 'truelysell'); ?>
										</a>
									</li>

								</ul>
							</li>
										
						<?php endif; ?>
						<!-- ------------------------- -->
					<!-- My Services -->
					<?php if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) : ?>
						<?php $submit_page = truelysell_fl_framework_getoptions('submit_page');
						if ($submit_page) : ?>
							<li <?php if ($post->ID == $submit_page) : ?> class="active" <?php endif; ?> ><a href="<?php echo esc_url(get_permalink($submit_page)); ?>"><i class="feather-gift"></i> <?php esc_html_e('Add Service', 'truelysell'); ?> </a></li>
						<?php endif; ?>

						<?php if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) : ?>
						<?php $listings_page = truelysell_fl_framework_getoptions('listings_page');
						if ($listings_page) : ?>
							<li  id="myservices_list" ><a <?php if ($post->ID == $listings_page) {?> class="submenu active my_services " <?php } else { ?> class="submenu my_services" <?php } ?>><i class="feather-briefcase"></i> <?php esc_html_e('My Services', 'truelysell'); ?> <span class="menu-arrow"></span></a>

								<ul>
									<li>
										<a href="<?php echo esc_url(get_permalink($listings_page)); ?>?status=active"><?php esc_html_e('Active', 'truelysell'); ?>
											<?php
											$count_published =  truelysell_count_posts_by_user($user_id, 'listing', 'publish');
											if (isset($count_published)) : ?><span class="badge bg-success"><?php echo esc_html($count_published); ?></span><?php endif; ?>
										</a>
									</li>
									<li>
										<a href="<?php echo esc_url(get_permalink($listings_page)); ?>?status=pending"><?php esc_html_e('Pending', 'truelysell'); ?>
											<?php
											$count_pending =  truelysell_count_posts_by_user($user_id, 'listing', 'pending');
											$count_pending_payment =  truelysell_count_posts_by_user($user_id, 'listing', 'pending_payment');
											$count_draft =  truelysell_count_posts_by_user($user_id, 'listing', 'draft');
											$total_pending_count = $count_pending + $count_pending_payment + $count_draft;
											if ($total_pending_count) : ?><span class="badge bg-info"><?php echo esc_html($total_pending_count); ?></span><?php endif; ?>
										</a>
									</li>
									<li>
										<a href="<?php echo esc_url(get_permalink($listings_page)); ?>?status=expired">
											<?php esc_html_e('Expired', 'truelysell'); ?>
											<?php
											$count_expired =  truelysell_count_posts_by_user($user_id, 'listing', 'expired');
											if ($count_expired) : ?><span class="badge bg-danger"><?php echo esc_html($count_expired) ?></span><?php endif; ?>
										</a>
									</li>

								</ul>
							</li>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>



					<!-- My Services End -->

					<?php if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) : ?>
						<?php $bookings_page = truelysell_fl_framework_getoptions('bookings_page');
						if ($bookings_page) : ?>
							<li id="bookservices_list" <?php if ($post->ID == $bookings_page) { ?>class="submenu active" <?php  } else { ?> class="submenu"  <?php } ?>><a><i class="feather-calendar"></i> <?php esc_html_e('Booking List', 'truelysell'); ?> <span class="menu-arrow"></span></a>
								<ul>
									<li>
										<a href="<?php echo esc_url(get_permalink($bookings_page)); ?>?status=waiting"><?php esc_html_e('Pending', 'truelysell'); ?>
											<?php
											$count_pending = truelysell_count_bookings($user_id, 'waiting');
											if (isset($count_pending)) : ?><span class="badge bg-info"><?php echo esc_html($count_pending); ?></span><?php endif; ?>
										</a>
									</li>
									<li>
										<a href="<?php echo esc_url(get_permalink($bookings_page)); ?>?status=approved"><?php esc_html_e('Approved', 'truelysell'); ?>
											<?php
											$count_approved = truelysell_count_bookings($user_id, 'approved');
											if (isset($count_approved)) : ?><span class="badge bg-success"><?php echo esc_html($count_approved); ?></span><?php endif; ?>
										</a>
									</li>
									<li>
										<a href="<?php echo esc_url(get_permalink($bookings_page)); ?>?status=cancelled"><?php esc_html_e('Cancelled', 'truelysell'); ?>
											<?php
											$count_cancelled = truelysell_count_bookings($user_id, 'cancelled');
											if (isset($count_cancelled)) : ?><span class="badge bg-danger"><?php echo esc_html($count_cancelled); ?></span><?php endif; ?>
										</a>
									</li>
									<?php if (truelysell_fl_framework_getoptions('show_expired')) : ?>
										<li>
											<a href="<?php echo esc_url(get_permalink($bookings_page)); ?>?status=expired"><?php esc_html_e('Expired', 'truelysell'); ?>
												<?php
												$count_cancelled = truelysell_count_bookings($user_id, 'expired');
												if (isset($count_cancelled)) : ?><span class="badge bg-danger"><?php echo esc_html($count_cancelled); ?></span><?php endif; ?>
											</a>
										</li>
									<?php endif; ?>


								</ul>

							</li>
						<?php endif; ?>
					<?php endif; ?>

					<?php
					$user_bookings_page = truelysell_fl_framework_getoptions('user_bookings_page');
					if (truelysell_fl_framework_getoptions('owners_can_book')) {

						if ($user_bookings_page) : ?>
							<li <?php if ($post->ID == $user_bookings_page) : ?> class="active" <?php endif; ?> ><a href="<?php echo esc_url(get_permalink($user_bookings_page)); ?>"> <i class="feather-grid"></i><?php esc_html_e('Booking List', 'truelysell'); ?></a></li>
						<?php endif;
					} else {
						if (!in_array($role, array('owner', 'seller'))) : ?>
							<?php if ($user_bookings_page) : ?>
								<li <?php if ($post->ID == $user_bookings_page) : ?>class="active" <?php endif; ?>><a  href="<?php echo esc_url(get_permalink($user_bookings_page)); ?>"><i class="feather-smartphone"></i> <?php esc_html_e('My Bookings', 'truelysell'); ?></a></li>
							<?php endif; ?>
					<?php endif;
					} ?>

                     <?php if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) : ?>
						<?php $payout_page = truelysell_fl_framework_getoptions('payout_page');
						if ($payout_page) : ?>
							<li <?php if ($post->ID == $payout_page) : ?>class="active" <?php endif; ?> ><a href="<?php echo esc_url(get_permalink($payout_page)); ?>"><i class="feather-shopping-bag"></i> <?php esc_html_e('Payout', 'truelysell'); ?></a>
							</li>
						<?php endif; ?>
					<?php endif; ?>
								
					<!-- wallet -->
					<?php if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) : ?>
						<?php $wallet_page = truelysell_fl_framework_getoptions('wallet_page');
						if ($wallet_page) : ?>
							<li <?php if ($post->ID == $wallet_page) : ?>class="active" <?php endif; ?> ><a href="<?php echo esc_url(get_permalink($wallet_page)); ?>"><i class="feather-dollar-sign"></i> <?php esc_html_e('Earnings', 'truelysell'); ?></a>
							</li>
						<?php endif; ?>
					<?php endif; ?>


                     <?php if (!in_array($role, array('owner', 'seller'))) : ?>
						<?php $bookmarks_page = truelysell_fl_framework_getoptions('bookmarks_page');
						if ($bookmarks_page) : ?>
							<li id="<?php echo esc_html($role);?>" <?php if ($post->ID == $bookmarks_page) : ?>class="active" <?php endif; ?> ><a  href="<?php echo esc_url(get_permalink($bookmarks_page)); ?>"><i class="feather-heart"></i> <?php esc_html_e('Favourites', 'truelysell'); ?></a></li>
						<?php endif; ?>
					<?php endif; ?>
                       <!-- Reviews -->
					<?php $reviews_page = truelysell_fl_framework_getoptions('get_reviews_infos_page');
					if ($reviews_page) : ?>
						<li id="<?php echo esc_html($role);?>" <?php if ($post->ID == $reviews_page) : ?>class="active" <?php endif; ?>><a   href="<?php echo esc_url(get_permalink($reviews_page)); ?>"><i class="feather-star"></i> <?php esc_html_e('Reviews', 'truelysell'); ?></a></li>
					<?php endif; ?>
 
                    
						<!-- Messages -->
					<?php $messages_page = truelysell_fl_framework_getoptions('messages_page');
					if ($messages_page) : ?>
						<li id="<?php echo esc_html($role);?>" <?php if ($post->ID == $messages_page) : ?> class="active" <?php endif; ?>><a href="<?php echo esc_url(get_permalink($messages_page)); ?>"><i class="feather-message-circle"></i> <?php esc_html_e('Chat ', 'truelysell'); ?>
								<?php
								$counter = truelysell_get_unread_counter();
								if ($counter) { ?>
									<span class="nav-tag messages"> (<?php echo esc_html($counter); ?>)</span>
								<?php } ?>
							</a>
						</li>
				<?php endif; ?>
			
					<!-- Profile settings -->
					<?php $profile_page = truelysell_fl_framework_getoptions('profile_page');
					if ($profile_page) : ?>
						<li <?php if ($post->ID == $profile_page) : ?>class="active" <?php endif; ?> ><a  href="<?php echo esc_url(get_permalink($profile_page)); ?>"><i class="feather-settings"></i> <?php esc_html_e('Settings', 'truelysell'); ?></a></li>
					<?php endif; ?>
			
					<!-- Logout -->
					<li><a href="<?php echo wp_logout_url(home_url()); ?>"><i class="feather-log-out"></i> <?php esc_html_e('Logout', 'truelysell'); ?></a></li>
				</ul>
				</div>
		
				<?php if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) : ?>
					<?php if (class_exists('WeDevs_Dokan')) : ?>
						<ul data-submenu-title="<?php esc_html_e('Store', 'truelysell'); ?>">
							<?php
							$home_url = home_url();
							$active_class = ' class="active"';
							global $wp;

							$request = $wp->request;

							$active = explode('/', $request);


							if ($active) {
								$active_menu = implode('/', $active);

								if ($active_menu == 'new-product') {
									$active_menu = 'products';
								}

								if (get_query_var('edit') && is_singular('product')) {
									$active_menu = 'products';
								}
								if ($active_menu == 'store-dashboard') {
									$active_menu = 'dashboard';
								} else if ($active_menu == 'dashboard') {

									$active_menu = 'store-dashboard';
								}
							} else {
							}
							global $allowedposttags;

							// These are required for the hamburger menu.
							if (is_array($allowedposttags)) {
								$allowedposttags['input'] = [
									'id'      => [],
									'type'    => [],
									'checked' => []
								];
							}

							echo wp_kses(dokan_dashboard_nav($active_menu), $allowedposttags); ?>
						</ul>
					<?php endif; ?>
				<?php endif; ?>
				<ul data-submenu-title="<?php esc_html_e('Account', 'truelysell'); ?>">
					<?php
					$orders_page_status = truelysell_fl_framework_getoptions('orders_page');
					 if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) : ?>
						<?php
						$subscription_page_status = truelysell_fl_framework_getoptions('subscription_page');
						if (class_exists('WC_Subscriptions') && $subscription_page_status) {
							$subscription_page =  wc_get_endpoint_url('subscriptions', '', get_permalink(get_option('woocommerce_myaccount_page_id')));

							if ($subscription_page) : ?>
								<li <?php if ($post->ID == $subscription_page) : ?>class="active" <?php endif; ?>><a href="<?php echo esc_url($subscription_page); ?>"><i class="sl sl-icon-refresh"></i> <?php esc_html_e('My Subscriptions', 'truelysell'); ?></a></li>
						<?php endif;
						} ?>
					<?php endif; ?>
				</ul>
			
			</div>
			</div>
			<!-- Content
			================================================== -->
			<?php
			$current_user = wp_get_current_user();
			$roles = $current_user->roles;
			$role = array_shift($roles);
			if (!empty($current_user->user_firstname)) {
				$name = $current_user->user_firstname;
			} else {
				$name =  $current_user->display_name;
			}
			?>
			<div class="col-md-8 col-lg-9" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<!-- Titlebar -->
			<?php
			if (truelysell_is_payout_active()) {
				$is_payout_email_added = esc_attr(get_user_meta(get_current_user_id(), 'truelysell_paypal_payout_email', true));
				if (empty($is_payout_email_added)) {
					if (in_array($role, array('administrator', 'admin', 'owner', 'seller'))) :
				?>

						<div class="alert alert-info mb-3" id="unpaid_listing_in_cart">
							<span style="display: block; font-weight: bold;"><?php esc_html_e('PayPal email missing!', 'truelysell') ?></span>
							<?php esc_html_e('Please add your PayPal email address. This is required to get your payments for booking using PayPal Payout service.', 'truelysell'); ?>
							<a  href="<?php echo get_permalink(truelysell_fl_framework_getoptions('payout_page')); ?>"><strong><?php esc_html_e('View Wallet and set the Payout Method there &#8594;', 'truelysell') ?></strong></a>
						</div>
			<?php endif;
				}
			}; ?>
<?php 
if(isset($_GET["action"])) {
	$form_edit = $_GET["action"];
} else {
	$form_edit = 'unedit';
}
?>
 				<div class="row align-items-center">
					<div class="col-md-12">
					<div class="widget-title"><?php
						$is_dashboard_page = truelysell_fl_framework_getoptions('dashboard_page');
						$is_booking_page = truelysell_fl_framework_getoptions('bookings_page');
						$is_listings_page = truelysell_fl_framework_getoptions('listings_page');
						global $post;
						if ($is_dashboard_page == $post->ID) { ?>
							<h4><?php esc_html_e('Dashboard', 'truelysell'); ?></h4>
							<?php } else if ($is_booking_page == $post->ID) {
							$status = '';
							if (isset($_GET['status'])) {
								$status = $_GET['status'];
								switch ($status) {
									case 'approved': ?><h4><?php esc_html_e('Approved Bookings', 'truelysell'); ?></h4>
									<?php
										break;
									case 'waiting': ?><h4><?php esc_html_e('Pending Bookings', 'truelysell'); ?></h4>
									<?php
										break;
									case 'expired': ?><h4><?php esc_html_e('Expired Bookings', 'truelysell'); ?></h4>
									<?php
										break;
									case 'cancelled': ?><h4><?php esc_html_e('Cancelled Bookings', 'truelysell'); ?></h4>
									<?php
										break;
									default:
									?><h4><?php esc_html_e('Bookings', 'truelysell'); ?></h4>
								<?php
										break;
								}
							} else { ?>
								<h4><?php the_title(); ?> <?php if ( $form_edit == 'edit') {   ?> <?php esc_html_e('Edit Service', 'truelysell'); ?> <?php } else { ?> <?php the_title(); } ?> </h4>
							<?php }
						} else if ($is_listings_page == $post->ID) {
							$status = '';
							if (isset($_GET['status'])) {
								$status = $_GET['status'];
								switch ($status) {
									case 'active': ?><h4><?php esc_html_e('Active Services', 'truelysell'); ?></h4>
									<?php
										break;
									case 'pending': ?><h4><?php esc_html_e('Pending Services', 'truelysell'); ?></h4>
									<?php
										break;
									case 'expired': ?><h4><?php esc_html_e('Expired Services', 'truelysell'); ?></h4>
									<?php
										break;
									case 'cancelled': ?><h4><?php esc_html_e('Cancelled Services', 'truelysell'); ?></h4>
									<?php
										break;
									default:
									?><h4><?php esc_html_e('Services', 'truelysell'); ?></h4>
								<?php
										break;
								}
							} else { ?>
								<h4><?php the_title(); ?> <?php if ( $form_edit == 'edit') {   ?> <?php esc_html_e('Edit Service', 'truelysell'); ?> <?php } else { ?> <?php   } ?> </h4>
							<?php }
						} else { ?>
							<h4><?php if ( $form_edit == 'edit') { ?> <?php esc_html_e('Edit Service', 'truelysell'); ?> <?php } else { ?><?php the_title(); } ?></h4>
						<?php } ?>
						
					</div>
				</div>
			</div>
			<?php
			while (have_posts()) : the_post();
				the_content();
			endwhile; // End of the loop. 
			?>
		</div>
		</div>
		<!-- Navigation / End -->
		</div>
	</div>
	<!-- Dashboard / End -->
<?php
	get_footer();
} ?>