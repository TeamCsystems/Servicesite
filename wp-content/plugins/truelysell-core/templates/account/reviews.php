<?php 
$ids = '';
if(isset($data)) :
	$ids	 	= (isset($data->ids)) ? $data->ids : '' ;
endif; 
$no_bookmarks = false;
$message = $data->message;
$current_user = wp_get_current_user();	
$roles = $current_user->roles;
$role = array_shift( $roles );
$limit = 3;
?> 
<div class="row">
<!-- Listings -->
	<?php if(!empty($message )) { echo $message; } ?>

<?php if(in_array($role,array('administrator','admin','owner', 'seller'))) : ?>
<div class="col-lg-12 col-md-12 <?php if(in_array($role,array('administrator','admin'))) : ?> <?php endif; ?>" >

 		<!-- Reply to review popup -->
		<div id="small-dialog" class="zoom-anim-dialog mfp-hide d-none">
			<div class="small-dialog-header">
				<h3><?php esc_html_e('Reply to review','truelysell_core') ?></h3>
			</div>
			<form action="" id="send-comment-reply">
				<div class="message-reply margin-top-0">
					<input type="hidden" id="reply-review-id" name="review_id" >
					<input type="hidden" id="reply-post-id" name="post_id" >
					<textarea id="comment_reply" required name="comment_reply" cols="40" rows="3"></textarea>
					<button id="send-comment-reply" class="button"><i class="fa fa-circle-o-notch fa-spin"></i><?php esc_html_e('Reply','truelysell_core') ?></button>
				</div>
			</form>
			
		</div>

		<!-- Edit reply to review popup -->
		<div id="small-dialog-edit" class="zoom-anim-dialog mfp-hide d-none">
			<div class="small-dialog-header">
				<h3><?php esc_html_e('Edit your reply','truelysell_core') ?></h3>
			</div>
			<form action="" id="send-comment-edit-reply">
				<div class="message-reply margin-top-0">
					
					<input type="hidden" id="reply_id" name="reply_id" >
					<textarea id="comment_reply" required name="comment_reply" cols="40" rows="3"></textarea>
					<button id="send-comment-edit-reply" class="button"><i class="fa fa-circle-o-notch fa-spin"></i><?php esc_html_e('Save changes','truelysell_core') ?></button>
				</div>
			</form>
			
		</div>

		<?php 


		
	    $visitor_reviews_page = (isset($_GET['visitor-reviews-page'])) ? $_GET['visitor-reviews-page'] : 1;
		add_filter( 'comments_clauses', 'truelysell_top_comments_only' );
		$visitor_reviews_offset = ($visitor_reviews_page * $limit) - $limit;
		$total_visitor_reviews = get_comments(
				array(
					'orderby' 	=> 'post_date' ,
            		'order' 	=> 'DESC',
           			'status' 	=> 'approve',
            		'post_author' => $current_user->ID,
					'parent'    => 0,
					'post_type' => 'listing',
            	)
			);
	  
		$visitor_reviews_args = array(

			'post_author' 	=> $current_user->ID,
			'parent'      	=> 0,
			'status' 	=> 'approve',
			'post_type' 	=> 'listing',
			'number' 		=> $limit,
			'offset' 		=> $visitor_reviews_offset,
		);
		$visitor_reviews_pages = ceil(count($total_visitor_reviews)/$limit);
		
		$visitor_reviews = get_comments( $visitor_reviews_args ); 
		remove_filter( 'comments_clauses', 'truelysell_top_comments_only' );

		if(empty($visitor_reviews)) : ?>
		<div class="alert alert-info"><?php esc_html_e('You don\'t have any reviews','truelysell_core') ?></div>
		<?php else : ?>
		
			<?php 
			foreach($visitor_reviews as $review) :
				?>
							<div class="review-list 2">
							<div class="review-list-top">
								
								<div class="review-img">
								<?php if (has_post_thumbnail( $review->comment_post_ID ) ): ?>
									<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $review->comment_post_ID ), 'single-post-thumbnail' ); ?>
									<img class="rounded img-fluid" src="<?php echo $image[0]; ?>" alt="">
									<?php endif; ?>
								</div>
								<div class="review-info">
										<h5><a href="<?php echo esc_url(get_permalink($review->comment_post_ID)); ?>"><?php echo get_the_title(
										$review->comment_post_ID) ?></a> </h5>
  										<div class="review-user">
										<?php echo get_avatar( $review->comment_author_email, 26,null, null, array( 'class' => array( 'avatar', ' rounded-circle' ) ) ); ?> <?php echo esc_html($review->comment_author); ?>
										<span class="review-date"><?php echo date_i18n(  get_option( 'date_format' ),  strtotime($review->comment_date), false ); ?></span>
										</div>
								</div>
								<div class="review-count 1">
										<div class="rating 5">
										<?php 
										$star_rating = get_comment_meta( $review->comment_ID, 'truelysell-rating', true );  
										if($star_rating) : ?>
										<div class="star-rating" data-rating="<?php echo get_comment_meta( $review->comment_ID, 'truelysell-rating', true ); ?>"></div>
										<?php endif; ?>
										</div>
									</div>
									<?php 
						            $photos = get_comment_meta( $review->comment_ID, 'truelysell-attachment-id', false );
						            if($photos) : ?>
						            <div class="review-images mfp-gallery-container">
						            	<?php foreach ($photos as $key => $attachment_id) {
						            		$image = wp_get_attachment_image_src( $attachment_id, 'truelysell-gallery' );
						            		$image_thumb = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
						            	 ?>
										<a href="<?php echo esc_attr($image[0]); ?>" class="mfp-gallery"><img src="<?php echo esc_attr($image_thumb[0]); ?>" alt=""></a>
										<?php } ?>
									</div>
									<?php endif;

									if(truelysell_check_if_review_replied($review->comment_ID,$current_user->ID)) { 
										$reply = truelysell_get_review_reply($review->comment_ID,$current_user->ID);
										
										?>
										<a href="#small-dialog-edit" class="rate-review edit-reply  popup-with-zoom-anim" 
										<?php if(!empty($reply)): ?>
										data-comment-id="<?php echo $reply[0]->comment_ID; ?>"
										data-comment-content="<?php echo $reply[0]->comment_content; ?>"
										<?php endif; ?>
										><i class="sl sl-icon-pencil"></i> <?php esc_html_e('Edit your reply','truelysell_core') ?></a>
										
									<?php } ?>
							</div>
							<div class="d-flex mt-3"><?php echo wpautop( $review->comment_content ); ?> </div>
								</div>
							
				
			<?php endforeach; ?>
		
		
		<?php endif; ?>
		 
	 
	</div>
	

</div>
<?php endif;?>
<?php if(in_array($role,array('administrator','admin','guest'))) : ?>
<!-- Listings -->
<div class="col-lg-12 col-md-12">
 
		<!-- Edit reply to review popup -->
		
		<?php 

		$your_reviews_page = (isset($_GET['your-reviews-page'])) ? $_GET['your-reviews-page'] : 1;
		$your_reviews_offset = ($your_reviews_page * $limit) - $limit;
		$total_your_reviews = get_comments(
				array(
					'orderby' 	=> 'post_date' ,
            		'order' 	=> 'DESC',
           			'status' 	=> 'all',
            		'author__in' => array($current_user->ID),
					'post_type' => 'listing',
					'parent'      => 0,
            	)
			);
		$your_reviews_args = array(
			'author__in' 	=> array($current_user->ID),
			'post_type' 	=> 'listing',
			'status' 		=> 'all',
			'parent'      	=> 0,
			'number' 		=> $limit,
		 	'offset' 		=> $your_reviews_offset,
			
		);
		$your_reviews_pages = ceil(count($total_your_reviews)/$limit);
		$your_reviews = get_comments( $your_reviews_args ); 
		if(empty($your_reviews)) : ?>
		<div class="alert alert-info"><?php esc_html_e('You haven\'t reviewed anything','truelysell_core') ?></div>
		<?php else : ?>
		
 		 
			<?php 
			foreach($your_reviews as $review) : 
				?>
<div class="review-list">
 				 <div class="review-list-top">
					<div class="review-imgs">
					<?php if (has_post_thumbnail( $review->comment_post_ID ) ): ?>
						<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $review->comment_post_ID ), 'single-post-thumbnail' ); ?>
						<img src="<?php echo $image[0]; ?>" class="rounded img-fluid"/>
						<?php endif; ?>
					</div>

					<div class="review-info">
								<h5>
								<a href="<?php echo esc_url(get_permalink($review->comment_post_ID)); ?>">
						<?php echo get_the_title($review->comment_post_ID) ?></a>
									<span class="badge badge-info"><?php if(wp_get_comment_status($review->comment_ID) == 'unapproved'){?>
							<?php esc_html_e(' is waiting for approval','truelysell_core') ?>
						<?php } ?></span>
								</h5>
								<div class="review-user">
 									<?php echo get_avatar( $review->comment_author_email, 70, null, null, array( 'class' => array( 'avatar', ' rounded-circle' ) )); ?> 
									
									<?php echo $review->comment_author;?>, 
									<span class="review-date"><?php echo date_i18n(  get_option( 'date_format' ),  strtotime($review->comment_date), false ); ?></span>
								</div>
								
							</div>
							<div class="review-count 2">
						<?php $star_rating = get_comment_meta( $review->comment_ID, 'truelysell-rating', true );
							if($star_rating) : ?>
								<div class="star-rating" data-rating="<?php echo get_comment_meta( $review->comment_ID, 'truelysell-rating', true ); ?>"></div>
							<?php endif; ?>
					</div>
				 </div>
					 
							<div class="d-flex mt-3"><?php echo wpautop( $review->comment_content ); ?></div>
 				</div>
			<?php endforeach; ?>
		 
	 
	
		<?php endif; ?>
 
	<?php if($your_reviews_pages>1): ?>
	<!-- Pagination -->
	<div class="clearfix"></div>
	<div id="your-reviews-pagination" class="pagination-container margin-top-30 margin-bottom-0">
		<nav class="pagination">
			<?php 
				
				echo paginate_links( array(
					'base'         	=> @add_query_arg('your-reviews-page','%#%'),
					'format'       	=> '?your-reviews-page=%#%',
					'current' 		=> $your_reviews_page,
					'total' 		=> $your_reviews_pages,
					'type' 			=> 'list',
					'prev_next'    	=> true,
					'prev_text'    => '<i class="fas fa-angle-left"></i>',
			        'next_text'    => '<i class="fas fa-angle-right"></i>',
			         'add_args'     => false,
   					 'add_fragment' => ''
				    
				) );?>
		</nav>
	</div>
	<!-- Pagination / End -->
	<?php endif; ?>
</div>
<?php endif; ?>
</div>

<div id="small-dialog-edit-review" class="zoom-anim-dialog mfp-hide d-none">
			<div class="small-dialog-header">
				<h3><?php esc_html_e('Edit your review','truelysell_core') ?></h3>
			</div>
			<form action="" id="send-comment-edit-review">
				<div class="message-reply margin-top-0">
					
					<input type="hidden" id="reply_id" name="reply_id" >
						<?php $criteria_fields = truelysell_get_reviews_criteria(); ?>
						<!-- Subratings Container -->
						<div class="sub-ratings-container">
							
						</div>
						<!-- Leave Rating -->
						
						<div class="clearfix"></div>
					
					<textarea id="comment_reply" required name="comment_reply" cols="40" rows="3"></textarea>
					<button id="send-comment-edit-review" class="button"><i class="fa fa-circle-o-notch fa-spin"></i><?php esc_html_e('Save changes','truelysell_core') ?></button>
				</div>
			</form>
			
		</div>