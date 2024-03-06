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
<div id="listing-reviews 2" class="listing-section">
	<h5><?php
		printf( // WPCS: XSS OK.
			esc_html( _nx(  'Review %1$s','Reviews %1$s', truelysell_get_reviews_number(), 'comments title', 'truelysell_core' ) ),
			'<span class="reviews-amount">(' . number_format_i18n( truelysell_get_reviews_number() ). ')</span>'
		);
	?></h5>

	<!-- Rating Overview -->
		<?php $rating_value = get_post_meta($post->ID, 'truelysell-avg-rating', true); 
	if($rating_value){ ?>
	
	<!---<div class="rating-overview">
		<div class="rating-overview-box">
			<span class="rating-overview-box-total"><?php esc_attr(round($rating_value,1)); printf("%0.1f",$rating_value);  ?></span>
			<span class="rating-overview-box-percent"><?php esc_html_e('out of 5.0','truelysell_core'); ?></span>
			<div class="star-rating" data-rating="<?php echo esc_attr(round($rating_value,2)); ?>"></div>
		</div>

		<div class="rating-bars">
			<?php $criteria_fields = truelysell_get_reviews_criteria(); ?>
			<?php foreach ($criteria_fields as $key => $value) {
				$rating = get_post_meta($post->ID, $key.'-avg', true); 
				if($rating) { ?>
				<div class="rating-bars-item">
					<span class="rating-bars-name"><?php echo stripslashes(esc_html($value['label'])) ?> 
								<?php if(isset($value['tooltip']) && !empty($value['tooltip'])) : ?><i class="tip" data-tip-content="<?php echo stripslashes(esc_html($value['tooltip'])); ?>"></i> <?php endif; ?></span>
					<span class="rating-bars-inner">
						<span class="rating-bars-rating" data-rating="<?php echo esc_attr($rating); ?>">
							<span class="rating-bars-rating-inner"></span>
						</span>
						<strong><?php esc_attr(round($rating,1)); printf("%0.1f",$rating);  ?></strong>
					</span>
				</div>
			<?php }
			} ?>
				
		</div>
</div>-->	
	<!-- Rating Overview / End -->
<?php } ?>

	<div class="clearfix"></div>
	
	<!-- Reviews -->
	<section class="comments listing-reviews 4">
	<ul>
			<?php
				wp_list_comments( array(
					'style'      	=> 'ul',
					'short_ping' 	=> true,
					'callback' 		=> 'truelysell_comment_review',
				),$comments );
			?>
			</ul>
	</section>

	<!-- Pagination -->
	<div class="clearfix"></div>
	
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		
			<div class="row">
				<div class="col-md-12">
					<!-- Pagination -->
					<div class="pagination-container margin-top-30">
						<nav class="pagination">
							<div class="nav-links">

								<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'truelysell_core' ) ); ?></div>
								<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', '$class = new _WP_List_Table_Compat( $screen, $columns );' ) ); ?></div>

							</div>
						</nav>
					</div>
				</div>
			</div>
		<div class="clearfix"></div>
		<!-- Pagination / End -->
		<?php endif; // Check for comment navigation. ?>
</div>
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
			<div class="notification notice margin-bottom-50 margin-top-50"><p><?php esc_html_e("You've already reviewed this service, your review is waiting for approval.",'truelysell_core'); ?></p></div>
    	 
    	<?php } else { ?>
        <div class="notification notice margin-bottom-50 margin-top-50"><p><?php esc_html_e("Thank you for your review.",'truelysell_core'); ?></p></div>
    	<?php } 
    }

     if(truelysell_fl_framework_getoptions('reviews_only_booked')){

	    $table_name = $wpdb->prefix . 'bookings_calendar';
	  	$has_booked = $wpdb->get_results( $wpdb->prepare( "
	            SELECT * FROM {$table_name}
	            WHERE bookings_author = %d
	            AND listing_id = %d
	            
		", get_current_user_id(),$post->ID ) );

    }
	
	

	//check if user has bought

	//get_current_user_id(),

	 endif; ?>