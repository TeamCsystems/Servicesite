<?php 
$errors = array();

if ( isset( $_REQUEST['login'] ) ) {
    $error_codes = explode( ',', $_REQUEST['login'] );
 
    foreach ( $error_codes as $code ) {
       switch ( $code ) {
	        case 'empty_username':
	            $errors[] = esc_html__( 'You do have an email address, right?', 'truelysell_core' );
	   		break;
	        case 'empty_password':
	            $errors[] =  esc_html__( 'You need to enter a password to login.', 'truelysell_core' );
	   		break;
	        case 'invalid_username':
	            $errors[] =  esc_html__(
	                "We don't have any users with that email address. Maybe you used a different one when signing up?",
	                'truelysell_core'
	            );
	   		break;
	        case 'incorrect_password':
	            $err = __(
	                "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
	                'truelysell_core'
	            );
	            $errors[] =  sprintf( $err, wp_lostpassword_url() );
	 		break;
	        default:
	            break;
	    }
    }
} 
 // Retrieve possible errors from request parameters
if ( isset( $_REQUEST['register-errors'] ) ) {
    $error_codes = explode( ',', $_REQUEST['register-errors'] );
 
    foreach ( $error_codes as $error_code ) {
 		
         switch ( $error_code ) {
	        case 'email':
			     $errors[] = esc_html__( 'The email address you entered is not valid.', 'truelysell_core' );
			   break;
			case 'email_exists':
			     $errors[] = esc_html__( 'An account exists with this email address.', 'truelysell_core' );
			 	  break;
			case 'closed':
			     $errors[] = esc_html__( 'Registering new users is currently not allowed.', 'truelysell_core' );
			     break;
	 		case 'captcha-no':
			     $errors[] = esc_html__( 'Please check reCAPTCHA checbox to register.', 'truelysell_core' );
			     break;
			case 'captcha-fail':
			     $errors[] = esc_html__( "You're a bot, aren't you?.", 'truelysell_core' );
			     break;
			case 'password-no':
			     $errors[] = esc_html__( "You have forgot about password.", 'truelysell_core' );
			     break;
	 
	        case 'incorrect_password':
	            $err = esc_html__(
	                "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
	                'truelysell_core'
	            );
	            $errors[] =  sprintf( $err, wp_lostpassword_url() );
	   			break;
	        default:
	            break;
	    }
    }
} ?>

	<div class="row">
	<div class="col-md-4 col-md-offset-4">
 	<!--Tab -->
		<div class="my-account style-1 margin-top-5 margin-bottom-40">

				<?php if ( isset( $_REQUEST['registered'] ) ) : ?>
				    <div class="notification success closeable">
				    <p>
				        <?php
				            printf(
				                __( 'You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'truelysell_core' ),
				                get_bloginfo( 'name' )
				            );
				        ?>
				    </p></div>
				<?php endif; ?>
					<?php if ( count( $errors ) > 0 ) : ?>
					    <?php foreach ( $errors  as $error ) : ?>
					        <div class="notification error closeable">
								<p><?php echo $error; ?></p>
								<a class="close"></a>
							</div>
					    <?php endforeach; ?>
					<?php endif; ?>
			<ul class="tabs-nav">
				<li class=""><a href="#tab1"><?php esc_html_e('Log In','truelysell_core'); ?></a></li>
				<li><a href="#tab2"><?php esc_html_e('Register','truelysell_core'); ?></a></li>
			</ul>

 	</div>