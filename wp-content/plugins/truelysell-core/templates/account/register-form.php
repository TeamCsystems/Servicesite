<div class="row">
  <div class="col-md-6 col-lg-6 mx-auto">
     <div class="login-wrap">
	        
            <div class="login-header">
                <h3><?php esc_html_e('Signup','truelysell_core'); ?></h3>
            </div>

			<?php
		if ( !get_option('users_can_register') ) : ?>
				<div class="notification error closeable" style="display: block">
					<p><?php esc_html_e( 'Registration is disabled', 'truelysell_core' ) ?></p>	
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
					
					<?php if (class_exists('WeDevs_Dokan')  && get_option('truelysell_role_dokan') == 'seller') : ?>
						<div  class="account-selection form-check-inline">
							<input type="radio" name="user_role" id="employer-radio" value="seller" class="account-type-radio"  <?php if($default_role == 'owner'){ ?> checked <?php  } ?> />
							<label for="employer-radio" ><i class="sl sl-icon-briefcase"></i> <?php esc_html_e('Provider','truelysell_core') ?></label>
						</div>
					<?php else : ?> 
					<div class="account-selection form-check-inline">
						<input type="radio" name="user_role" id="employer-radio" value="owner" class="account-type-radio"  <?php if($default_role == 'owner'){ ?> checked <?php  } ?> />
						<label for="employer-radio" ><i class="sl sl-icon-briefcase"></i> <?php esc_html_e('Provider','truelysell_core') ?></label>
					</div>
					<?php endif; ?>

					<div class="account-selection form-check-inline">
						<input type="radio" name="user_role" id="freelancer-radio" value="guest" class="account-type-radio" <?php if($default_role == 'guest'){ ?> checked <?php  } ?> />
						<label for="freelancer-radio"><i class="sl sl-icon-user"></i> <?php esc_html_e('Customer','truelysell_core') ?></label>
					</div>

				</div>
				</div>
				<div class="clearfix"></div>
				<?php endif; ?>
				<?php if(truelysell_fl_framework_getoptions('display_password_field')) : ?>
				
				<?php endif; ?>

				<?php if(truelysell_fl_framework_getoptions('display_first_last_name')) : ?>
				

     
					<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('First Name','truelysell_core'); ?></label>
 									<input type="text" class="input-text form-control" <?php if(truelysell_fl_framework_getoptions('display_first_last_name_required')) { ?>required <?php } ?> placeholder="Enter First Name" name="first_name" id="first-name" maxlength="20"/>
						            <div id="" class="invalid-feedback"><?php esc_html_e('First Name is required.','truelysell_core'); ?></div>

								</div>


				 
		 
								<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Last Name','truelysell_core'); ?></label>
 									<input type="text" class="input-text form-control" <?php if(truelysell_fl_framework_getoptions('display_first_last_name_required')) { ?>required <?php } ?> placeholder="Enter Last Name" name="last_name" id="last-name" maxlength="20"/>
						            <div id="" class="invalid-feedback"><?php esc_html_e('Last Name is required.','truelysell_core'); ?></div>

								</div>

								<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Email / Username','truelysell_core'); ?></label>
 									<input type="email" class="input-text form-control" placeholder="example@email.com" name="email" id="email" value="" required maxlength="20"/>
						            <div id="" class="invalid-feedback"><?php esc_html_e('Email is required.','truelysell_core'); ?></div>

								</div>

				
				 
		        <?php endif; ?>
				<?php if(!truelysell_fl_framework_getoptions('registration_hide_username')) : ?>

					<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Username','truelysell_core'); ?></label>
 									<input type="text" class="input-text form-control" placeholder="Enter Username" name="username" id="username2"  />
						            <div id="" class="invalid-feedback"><?php esc_html_e('Username is required.','truelysell_core'); ?></div>

								</div>

					 
				<?php endif; ?>



				<div class="form-group">
									<label class="col-form-label"><?php esc_html_e('Password','truelysell_core'); ?></label>
									<div class="pass-group">
 										<input class="input-text form-control pass-input" type="password" name="password" id="password1" required placeholder="*************"/>
										<span class="toggle-password feather-eye"></span>
						<div id="" class="invalid-feedback"><?php esc_html_e('Password is required.','truelysell_core'); ?></div>

									</div>
								</div>


 

				<!-- //extra fields -->
				<div id="truelysell-core-registration-fields">
					<?php echo truelysell_get_extra_registration_fields($default_role); ?>	
				</div>
				

				<!-- eof custom fields -->
				
				<?php if(!truelysell_fl_framework_getoptions('display_password_field')) : ?>
				<p class="form-row form-row-wide margin-top-30 margin-bottom-30"><?php esc_html_e( 'Note: Your password will be generated automatically and sent to your email address.', 'truelysell_core' ); ?>
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
				<input type="submit" class="btn btn-primary w-100 login-btn mb-0" name="register" id="regbtn" value="<?php esc_html_e( 'Signup', 'truelysell_core' ); ?>" />

				<div class="notification error closeable" style="display: none;margin-top: 20px; margin-bottom: 0px;">
							<p></p>	
				</div>

			</form>
			
			<div class="truelysell-custom-fields-wrapper">
			<?php echo truelysell_get_extra_registration_fields('owner'); ?>	
			<?php echo truelysell_get_extra_registration_fields('guest'); ?>	
			</div>
		<?php endif; ?>

     </div>
  </div>
</div>

 	<!-- Register -->
 
	
<!-- Register -->
 		
 
 

  
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