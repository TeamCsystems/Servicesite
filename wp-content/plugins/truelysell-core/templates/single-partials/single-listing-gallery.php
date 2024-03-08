<!-- Content
================================================== -->
<?php $gallery = get_post_meta( $post->ID, '_gallery', true );

if(!empty($gallery)) : ?>

	<!-- Slider -->
	<?php 
echo '<div class="gallery_services"> <ul class="row"> ';
 $count = 0;
foreach ( (array) $gallery as $attachment_id => $attachment_url ) {
		$image = wp_get_attachment_image_src( $attachment_id, 'truelysell-gallery' );
		$thumb = wp_get_attachment_image_src( $attachment_id, 'medium' );
		echo '<li class="col-md-2"><a data-rel="prettyPhoto[gallery]" rel="prettyPhoto[gallery]" href="'.esc_attr($image[0]).'" rel="prettyPhoto[gallery1]">';
			echo '<img  src="'.esc_attr($thumb[0]).'" class="img-fluid"/>';
		echo '</a></li>';
	}
echo '</ul></div> ';
 endif; ?>


 
