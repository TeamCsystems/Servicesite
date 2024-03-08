<?php 		
if(has_post_thumbnail()){ 
	the_post_thumbnail('truelysell-listing-grid'); 
} else {
	$gallery = (array) get_post_meta( $post->ID, '_gallery', true );
	if(!empty($gallery)){
		$ids = array_keys($gallery);
		if(!empty($ids[0])){ 
			echo  wp_get_attachment_image($ids[0],'truelysell-listing-grid'); 
		}	
	} else { ?>
			<img src="<?php echo get_truelysell_core_placeholder_image(); ?>">
	<?php } 
} 
?>