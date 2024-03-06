<?php

/**
 * Template Name: Register Page
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
							<div class="notification error closeable">
							<div class="badge text-bg-danger closeable"><p><?php echo esc_html($error);  ?></p></div>
								<a class="close"></a>
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

 			<?php
		if ( !get_option('users_can_register') ) : ?>
				<div class="notification error closeable" style="display: block">
					<p><?php esc_html_e( 'Registration is disabled', 'truelysell' ) ?></p>	
				</div>
		<?php else :
			/*WPEngine compatibility*/
			if (defined('PWP_NAME')) { ?>
				<form  enctype="multipart/form-data" class="register truelysell-registration-form needs-validationregister" id="register" action="<?php echo wp_registration_url().'&wpe-login=';echo PWP_NAME; ?>" method="post"  >
			<?php } else { ?>
				<form  enctype="multipart/form-data" class="register truelysell-registration-form needs-validationregister" id="register" action="<?php echo wp_registration_url(); ?>" method="post"  >

			<?php } ?>	
				<?php 
				$default_role = truelysell_fl_framework_getoptions('registration_form_default_role');
				if(!get_option('truelysell_registration_hide_role')) : ?>
				<div class="form-group">
				<div class="account-type">
 
					 
 					<div class="account-selection form-check-inline">
						<input type="radio" name="user_role" id="employer-radio" value="owner" class="form-check-input account-type-radio"  <?php if($default_role == 'owner'){ ?> checked <?php  } ?> />
						<label for="employer-radio" for="employer-radio" ><i class="sl sl-icon-briefcase"></i> <?php esc_html_e('Business','truelysell') ?></label>
					</div>
 
					<div class="account-selection form-check-inline">
						<input type="radio" name="user_role" id="freelancer-radio" value="guest" class="form-check-input account-type-radio" <?php if($default_role == 'guest'){ ?> checked <?php  } ?> />
						<label for="freelancer-radio" for="freelancer-radio"><i class="sl sl-icon-user"></i> <?php esc_html_e('Customer','truelysell') ?></label>
					</div>

				</div>
				</div>
				<div class="clearfix"></div>
				<?php endif; ?>
				<?php if(truelysell_fl_framework_getoptions('display_password_field')) : ?>
				
				<?php endif; ?>

				<?php if(truelysell_fl_framework_getoptions('display_first_last_name')) : ?>
					<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('First Name','truelysell'); ?></label>
 									<input type="text" class="input-text form-control" <?php if(truelysell_fl_framework_getoptions('display_first_last_name_required')) { ?>required <?php } ?> placeholder="Enter First Name" name="first_name" id="first-name" maxlength="20"/>
						            <div id="" class="invalid-feedback"><?php esc_html_e('First Name is required.','truelysell'); ?></div>
								</div>

								<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Last Name','truelysell'); ?></label>
 									<input type="text" class="input-text form-control" <?php if(truelysell_fl_framework_getoptions('display_first_last_name_required')) { ?>required <?php } ?> placeholder="Enter Last Name" name="last_name" id="last-name" maxlength="20"/>
						            <div id="" class="invalid-feedback"><?php esc_html_e('Last Name is required.','truelysell'); ?></div>

								</div>

								<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Email','truelysell'); ?></label>
 									<input type="email" class="input-text form-control" placeholder="example@example.com" name="email" id="email" value="" required/>
						            <div id="" class="invalid-feedback"><?php esc_html_e('Email is required.','truelysell'); ?></div>

								</div>
		        <?php endif; ?>
				<?php if(!truelysell_fl_framework_getoptions('registration_hide_username')) : ?>

					<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Username','truelysell'); ?></label>
 									<input type="text" class="input-text form-control" placeholder="Enter Username" name="username" id="username2" />
						            <div id="" class="invalid-feedback"><?php esc_html_e('Username is required.','truelysell'); ?></div>

								</div>
				<?php endif; ?>

				<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Password','truelysell'); ?></label>
									<div class="pass-group">
 										<input class="input-text form-control pass-input" type="password" name="password" id="password1" required placeholder="*************"/>
										<span class="toggle-password feather-eye"></span>
						<div id="" class="invalid-feedback"><?php esc_html_e('Password is required.','truelysell'); ?></div>
									</div>
								</div>

				<!-- //extra fields -->
				<div id="truelysell-core-registration-fields">
					<?php echo truelysell_get_extra_registration_fields($default_role); ?>	
				</div>
				
 				<!-- eof custom fields -->
				
				<?php if(!truelysell_fl_framework_getoptions('display_password_field')) : ?>
				<p class="form-row form-row-wide margin-top-30 margin-bottom-30"><?php esc_html_e( 'Note: Your password will be generated automatically and sent to your email address.', 'truelysell' ); ?>
		        </p>
		        <?php endif; ?>
				<?php $recaptcha = get_option('truelysell_recaptcha');
				$recaptcha_version = get_option('truelysell_recaptcha_version','v2');
	         	if($recaptcha && $recaptcha_version == 'v2'){ ?>
                
                <p class="form-row captcha_wrapper">
                    <div class="g-recaptcha" data-sitekey="<?php echo get_option('truelysell_recaptcha_sitekey'); ?>"></div>
                </p>
                <?php } 

                if($recaptcha && $recaptcha_version == 'v3'){ ?>
                    <input type="hidden" id="rc_action" name="rc_action" value="ws_register">
                    <input type="hidden" id="token" name="token">
                <?php } ?>

				<?php wp_nonce_field( 'truelysell-ajax-login-nonce', 'register_security' ); ?>
				
				<input type="submit" class="btn btn-primary w-100 login-btn register-btn mb-0" name="register" id="regbtn" value="<?php esc_html_e( 'Signup', 'truelysell' ); ?>" />
				<p class="no-acc "><?php esc_html_e("Already have an account?",'truelysell'); ?>  <a href="<?php echo esc_url(get_permalink(truelysell_fl_framework_getoptions('login_page'))); ?>"><?php esc_html_e('Login','truelysell'); ?></a></p>
				<div class="notification error closeable" style="display: none;margin-top: 20px; margin-bottom: 0px;">
							 
				</div>
			</form>
			
			
		<?php endif; ?>
     </div>
  </div>
</div>
<div class="truelysell-custom-fields-wrapper d-none"  >
						<?php echo truelysell_get_extra_registration_fields('owner'); ?>
						<?php echo truelysell_get_extra_registration_fields('guest'); ?>
					</div>
<!-- Register -->
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