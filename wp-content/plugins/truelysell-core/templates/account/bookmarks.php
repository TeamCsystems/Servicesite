<?php 
$ids = '';
if(isset($data)) :
	$ids	 	= (isset($data->ids)) ? $data->ids : '' ;
endif; 
$no_bookmarks = array();

?> 

<?php if(!empty($ids)) : ?>
 
	<ul class="row favourites_item">
	<?php
	$nonce = wp_create_nonce("truelysell_core_remove_fav_nonce");
	foreach ($ids as $listing_id) {
		if ( get_post_status( $listing_id ) !== 'publish' ) {
			$no_bookmarks[$listing_id] = true;
			continue;
			
		} 
		$listing = get_post($listing_id);
		$no_bookmarks[$listing_id] = false; ?>
		<li class="col-xl-4 col-md-6 service_widget_main">
			<div class="service-widget service-fav favourites_customer">
				<div class="service-img">
					<a href="<?php echo get_permalink( $listing ) ?>"><?php 
					
						if(has_post_thumbnail($listing_id)){ 
							echo get_the_post_thumbnail($listing_id,'truelysell-listing-grid-small','full', array('class' => 'serv-img img-fluid'));
						} else {
							$gallery = (array) get_post_meta( $listing_id, '_gallery', true );

							$ids = array_keys($gallery);
							if(!empty($ids[0]) && $ids[0] !== 0){ 
								$image_url = wp_get_attachment_image_url($ids[0],'truelysell-listing-grid-small'); 
							} else {
								$image_url = get_truelysell_core_placeholder_image();
							}
							?>
							<img src="<?php echo esc_attr($image_url); ?>" class="img-fluid serv-img">
						<?php } ?></a>
						<div class="item-info ">

						<div class="cate-list">

<?php 
$terms = get_the_terms($listing_id, 'listing_category' ); 
if ( $terms && ! is_wp_error( $terms ) ) :  
$main_term = array_pop($terms); ?>
<span class="cat_name bg-yellow"><?php echo $main_term->name; ?></span>
<?php endif; ?>
</div>
 <a href="#" class="truelysell_core-unbookmark-it delete button fav-icon selected" data-post_id="<?php echo esc_attr($listing_id); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"><i class="feather-trash-2"></i>  </a>
 </div>

				</div>
				<div class="service-content">
					<h3 class="title"><a href="<?php echo get_permalink( $listing ) ?>"><?php echo get_the_title( $listing );?></a></h3>

					<div class="serv-info">
											<div class="serv-user">
											<?php 
 												$author_id=$listing->post_author; 
 								                $owner_data = get_userdata($author_id);
												$address = get_post_meta( $listing_id, '_friendly_address', true ); 
								        	?>
 								         	<?php 
  									           echo get_avatar($author_id, 26, '', '', array('class' => 'avatar-img rounded-circle'));    
                                             ?> 
												<div class="serv-user-info">
													<h5><?php echo get_the_author_meta('display_name', $author_id); ?></h5>
													<p><i class="feather-map-pin  me-2"></i><?php echo esc_html($address); ?> </p>
												</div>
											</div>
											<a href="<?php echo get_permalink( $listing ) ?>" class="btn btn-secondary">View</a>
                    </div>
 				</div>
			</div>
			
			
	 
		</li>
	<?php } ?>
	</ul>

 

<?php else: ?>
	<div class="notification notice ">
		<p><span><?php esc_html_e('No favourites!','truelysell_core'); ?></span> <?php esc_html_e('You haven\'t saved anything yet!','truelysell_core'); ?></p>
		
	</div>
<?php endif;
?>

<?php 

$number_of_bookmarks = count($no_bookmarks);

//all have to be tru to show 
$i = 0;

foreach ($no_bookmarks as $key => $value) {

	if($value==true){
		$i++;
	}

}
if($number_of_bookmarks == $i) : ?>
	<div class="notification notice ">
	<div class="alert alert-info"><?php esc_html_e('You don\'t have any bookmarks yet.','truelysell_core'); ?></div>
				
	</div>
<?php endif; ?>
