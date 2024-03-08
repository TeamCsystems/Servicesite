
 						<div class="login-wrap">
							<div class="login-header">
								<h3><?php esc_html_e('Log In','truelysell_core'); ?></h3>
  							</div>
							
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
									<label class="col-form-label"><?php esc_html_e('Email / Username','truelysell_core'); ?></label>
 									<input type="text" class="input-text form-control" name="log" id="user_login" value="" required ="" placeholder="example@email.com"/>
						            <div id="" class="invalid-feedback"><?php esc_html_e('Email is required.','truelysell_core'); ?></div>

								</div>
								<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Password','truelysell_core'); ?></label>
									<div class="pass-group">
 										<input class="input-text form-control pass-input" type="password" name="pwd" id="user_pass" required   placeholder="*************"/>
										<span class="toggle-password feather-eye"></span>
						<div id="" class="invalid-feedback"><?php esc_html_e('Password is required.','truelysell_core'); ?></div>

									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="char-length">
											<p><?php esc_html_e('Must be 6 Characters at Least','truelysell_core'); ?></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="text-md-end">
											<a class="forgot-link" href="<?php echo home_url();?>/lost-password"><?php esc_html_e( 'Forgot password?', 'truelysell_core' ); ?></a>
 										</div>
									</div>
								</div>
								 
 								<?php wp_nonce_field( 'truelysell-ajax-login-nonce', 'login_security' ); ?>
								 
					<input type="submit" class="btn btn-primary w-100 login-btn mb-0" id="loginbtn" name="login" value="<?php esc_html_e('Sign in','truelysell_core') ?>" />

  								<p class="no-acc"><?php esc_html_e("Don't have an account ?",'truelysell_core'); ?>  <a href="<?php echo home_url();?>/register"><?php esc_html_e('Sign up','truelysell_core'); ?></a></p>
							</form>
							<!-- /Login Form -->
											
						</div>
				 
 
 
 
<?php if(function_exists('_wsl_e')) { ?>
<div class="social-login-separator"><span><?php esc_html_e('Sign In with Social Network','truelysell_core'); ?></span></div>
<?php do_action( 'wordpress_social_login' ); ?>

<?php } ?>

<?php
if(function_exists('mo_openid_initialize_social_login')) { ?>
	<div class="social-miniorange-container">
		<div class="social-login-separator"><span><?php esc_html_e('Sign In with Social Network','truelysell_core'); ?></span></div><?php echo do_shortcode( '[miniorange_social_login  view="horizontal" heading=""]' ); 
		?>
</div>
<?php } ?>

<?php
if(class_exists('NextendSocialLogin', false)){
    echo NextendSocialLogin::renderButtonsWithContainer();
}
?>