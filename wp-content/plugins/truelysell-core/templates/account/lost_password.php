<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 my-account   login_registertabs">
			<div class="login-wrap">
		<?php 
		$errors = array();
		if(isset($data)) :
			$errors	 	= (isset($data->errors)) ? $data->errors : '' ;
		endif;
		?>
		<?php if ( count( $errors ) > 0 ) : ?>
			<?php foreach ( $errors as $error ) : ?>
				<p>
					<?php echo $error; ?>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php
		/*WPEngine compatibility*/
		if (defined('PWP_NAME')) { ?>
			<form id="lostpasswordform" class="sign-in-form"  action="<?php echo wp_lostpassword_url().'&wpe-login=';echo PWP_NAME;?>" method="post">
		<?php } else { ?>
			<form id="lostpasswordform" class="sign-in-form" action="<?php echo wp_lostpassword_url(); ?>" method="post">
		<?php } ?>
		
			<div class="form-group form-focus mb-0">
				<label class="focus-label">Email</label>
					<input type="text" name="user_login" placeholder="example@example.com" class="input-text form-control" id="user_login" required>
				
			

			<div class="lostpassword-submit">
				<input type="submit" name="submit" class="btn btn-primary btn-block btn-lg login-btn mt-3"
				       value="<?php _e( 'Reset Password', 'truelysell_core' ); ?>"/>
			</div>
		</form>
	</div>
		</div>
	</div>
</div>
</div>