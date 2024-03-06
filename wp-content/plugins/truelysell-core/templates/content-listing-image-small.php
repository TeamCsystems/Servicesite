<?php 	
if(has_post_thumbnail()){ 
	the_post_thumbnail('truelysell-listing-grid-small', array('class' => 'serv-img img-fluid')); 
} else { 
	
	$gallery = (array) get_post_meta( $id, '_gallery', true );

	$ids = array_keys($gallery);
	if(!empty($ids[0]) && $ids[0] !== 0){ 
		$image_url = wp_get_attachment_image_url($ids[0],'truelysell-listing-grid-small'); 
	} else {
		$image_url = get_truelysell_core_placeholder_image();
	}
	?>
	<img src="<?php echo esc_attr($image_url); ?>" class="img-fluid serv-img">
<?php
}