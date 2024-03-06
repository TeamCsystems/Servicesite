<?php

global $post;
global $wpdb; 	


//Gather comments for a specific page/post 
$comments = get_comments(array(
    'post_id' => $post->ID,
    'status' => 'approve' //Change this to the type of comments to be displayed
));

// You can start editing here -- including this comment!
if ( $comments ) : ?>
<?php endif; // Check for have_comments().


// If comments are closed and there are comments, let's leave a little note, shall we?
if ( ! comments_open() ): ?>
	<p class="no-comments"><?php esc_html_e( 'Reviews are closed.', 'truelysell_core' ); ?></p>
<?php
else : 
	$owners_can_review = truelysell_fl_framework_getoptions('owners_can_review');
	if($owners_can_review) {
		$show_form = true;	
	} else {
		$show_form = true;
		if(is_user_logged_in()){
			$user = wp_get_current_user();
	    	$role = ( array ) $user->roles;

	    	if(in_array('owner', $role)) {
	    	$show_form = false; ?>
				<div class="alert alert-info mb-3"><p><?php esc_html_e("You can't review listings as an owner",'truelysell_core'); ?></p></div>
	    	<?php }
		}  
		if( (int) $post->post_author == get_current_user_id() ) {
			$show_form = false;    ?>
			<div class="margin-top-50"></div>
		<?php } 

	}
	// Get the comments for the logged in user.
    $usercomment = false;
    if(is_user_logged_in()) {
		$usercomment = get_comments( array (
            'user_id' => get_current_user_id(),
            'post_id' => $post->ID,
    	) );
    }

    if ( $usercomment ) {
    	
    	$show_form = false; 
    	//check if has pending
    	$usercomment_pending = get_comments( array (
            'user_id' => get_current_user_id(),
            'post_id' => $post->ID,
            'status'  => 'hold'
    	) );

     	if($usercomment_pending){ ?>
			<div class="notification notice margin-bottom-50 margin-top-50"><p><?php ///esc_html_e("You've already reviewed this service, your review is waiting for approval.",'truelysell_core'); ?></p></div>
    	 
    	<?php } else { ?>
        <div class="notification notice margin-bottom-50 margin-top-50"><p><?php ///esc_html_e("Thank you for your review.",'truelysell_core'); ?></p></div>
    	<?php } 
    }

     if(truelysell_fl_framework_getoptions('reviews_only_booked')){

	    $table_name = $wpdb->prefix . 'bookings_calendar';
	  	$has_booked = $wpdb->get_results( $wpdb->prepare( "
	            SELECT * FROM {$table_name}
	            WHERE bookings_author = %d
	            AND listing_id = %d
	            
		", get_current_user_id(),$post->ID ) );
	    

	    if(!is_user_logged_in()){
	    	$show_form = false;
	    	?>
	    	<div id="add-review" class="alert alert-info"><p><?php esc_html_e("Only guests who have booked can leave a review.",'truelysell_core'); ?></p></div>
	    	<?php
	    } else {
	    	if(!empty($has_booked)){
	    	$show_form = true; 
		    } else {
		    	$show_form = false;
		    	?>
		    	<div id="add-review" class="alert alert-info"><p><?php esc_html_e("Only customers who have booked can leave a review.",'truelysell_core'); ?></p></div>	
		    	<?php
		    }
	    }
    }
    
	

	//check if user has bought


	if($show_form) { ?>
	<div id="add-review" class="add-review-box">
			<!-- Add Review -->
 			<?php if(isset($_GET['captcha']) && $_GET['captcha'] == 'failed'):  ?>
				<div class="notification error margin-top-10 margin-bottom-30"><p><?php esc_html_e("Please check reCAPTCHA checbox to post your review",'truelysell_core'); ?></p></div>
			<?php endif; ?>
			<?php comment_form(); ?>
	</div>
	<?php } ?>
	<!-- Add Review Box / End -->

<?php endif; ?>