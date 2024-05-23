<?php

/**
 * Template Name: Login Page
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

		 <div class="login-wrap">
							<?php if (count($errors) > 0) : ?>
						<?php foreach ($errors  as $error) : ?>
							<div class="notification error closeable">
							<div class="alert alert-danger"><?php echo esc_html($error);  ?>
								 </div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if (isset($_REQUEST['registered'])) : ?>
						<div class="notification success closeable">
						<div class="badge text-bg-success closeable"><p>
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
							</p></div>
						</div>
					<?php endif; ?>
					
							 
							<!-- Login Form -->
							<?php
			/*WPEngine compatibility*/
			if (defined('PWP_NAME')) { ?>
				<form method="post" id="login" class="login needs-validationlogin" action="<?php echo wp_login_url().'?wpe-login=';echo PWP_NAME;?>"  >
			<?php } else { ?>
				<form method="post" id="login"  class="login needs-validationlogin" action="<?php echo wp_login_url(); ?>">
			<?php } ?>
			      <?php do_action( 'truelysell_before_login_form' ); ?>
 								<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Email','truelysell'); ?></label>
 									<input type="text" class="input-text form-control" name="log" id="user_login" value="" required ="" placeholder="example@example.com"/>
						            <div id="" class="invalid-feedback"><?php esc_html_e('Email is required.','truelysell'); ?></div>
								</div>
								<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Password','truelysell'); ?></label>
									<div class="pass-group">
 										<input class="input-text form-control pass-input" type="password" name="pwd" id="user_pass" required placeholder="*************"/>
										<span class="toggle-password feather-eye"></span>
						                <div id="" class="invalid-feedback"><?php esc_html_e('Password is required.','truelysell'); ?></div>

									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="char-length">
 										
											<p><?php esc_html_e('Must be 6 Characters at Least','truelysell'); ?></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="text-md-end">
											<a class="forgot-link" href="<?php echo esc_url(get_permalink(truelysell_fl_framework_getoptions('lost_password_page'))); ?>"><?php esc_html_e( 'Forgot password?', 'truelysell' ); ?></a>
 										</div>
									</div>
								</div>
								 
 								<?php wp_nonce_field( 'truelysell-ajax-login-nonce', 'login_security' ); ?>
					            <input type="submit" class="btn btn-primary w-100 login-btn mb-0" id="loginbtn" name="login" value="<?php esc_html_e('Sign in','truelysell') ?>" />
								  <p class="no-acc "><?php esc_html_e("Don't have an account ?",'truelysell'); ?>  <a href="<?php echo esc_url(get_permalink(truelysell_fl_framework_getoptions('register_page'))); ?>"><?php esc_html_e('Register','truelysell'); ?></a></p>
							</form>
							<!-- /Login Form -->
											
						</div>
 <?php if(function_exists('_wsl_e')) { ?>
<div class="social-login-separator"><span><?php esc_html_e('Sign In with Social Network','truelysell'); ?></span></div>
<?php do_action( 'wordpress_social_login' ); ?>
<?php } ?>

<?php
if(function_exists('mo_openid_initialize_social_login')) { ?>
	<div class="social-miniorange-container">
		<div class="social-login-separator"><span><?php esc_html_e('Sign In with Social Network','truelysell'); ?></span></div><?php echo do_shortcode( '[miniorange_social_login  view="horizontal" heading=""]' ); 
		?>
</div>
<?php } ?>

<?php
if(class_exists('NextendSocialLogin', false)){
    echo NextendSocialLogin::renderButtonsWithContainer();
}
?>
	</div>
	</div>
	<div class="clearfix"></div>
<?php
	get_footer();
}  ?>
