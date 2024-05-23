<?php
if (!defined('ABSPATH')) {
	exit;
}


$template_loader = new Truelysell_Core_Template_Loader;

get_header(get_option('header_bar_style', 'standard'));

$layout = get_option('truelysell_single_layout', 'right-sidebar');
$mobile_layout = get_option('truelysell_single_mobile_layout', 'right-sidebar');

$gallery_style = get_post_meta($post->ID, '_gallery_style', true);

if (empty($gallery_style)) {
	$gallery_style = truelysell_fl_framework_getoptions('gallery_type');
}

$count_gallery = truelysell_count_gallery_items($post->ID);

if ($count_gallery < 4) {
	$gallery_style = 'content';
}
if ($count_gallery == 1) {
	$gallery_style = 'none';
}


$packages_disabled_modules = truelysell_fl_framework_getoptions('listing_packages_options', array());
if (empty($packages_disabled_modules)) {
	$packages_disabled_modules = array();
}

$user_package = get_post_meta($post->ID, '_user_package_id', true);

if ($user_package) {
	$package = truelysell_core_get_user_package($user_package);
} else {
	$package = false;
}


$load_gallery = false;
if (in_array('option_gallery', $packages_disabled_modules)) {
	if ($package && $package->has_listing_gallery() == 1) {
		$load_gallery = true;
	}
} else {
	$load_gallery = true;
}

$load_video = false;
if (in_array('option_video', $packages_disabled_modules)) {
	if ($package && $package->has_listing_video() == 1) {
		$load_video = true;
	}
} else {
	$load_video = true;
}

$load_reviews = false;
if (in_array('option_reviews', $packages_disabled_modules)) {
	if ($package && $package->has_listing_reviews() == 1) {
		$load_reviews = true;
	}
} else {
	$load_reviews = true;
}
$listing_type = get_post_meta(get_the_ID(), '_listing_type', true);
if (have_posts()) :
?>




	<!-- Content
================================================== -->
	<div class="content">
		 
	<div class="container <?php echo esc_attr($listing_type); ?>">
		
			<!-- Sidebar
		================================================== -->
			<!-- " -->

			<?php while (have_posts()) : the_post();  ?>
				<!--  -->
				<div class="row">

				<div class="col-lg-8">
				<h2 class="service_title"><?php the_title(); ?></h2>
				<div class="row align-items-center">
				<div class="col-md-8">
				<div class="serv-profile">
				
							<ul>
								<li>									
									<?php
									$terms = get_the_terms(get_the_ID(), 'listing_category');
									if ($terms && !is_wp_error($terms)) :
										$categories = array();
										foreach ($terms as $term) { ?>
										
										<?php
											$categories[] = sprintf(
												'<span class="badge"><a href="%1$s">%2$s</a> </span>',
												esc_url(get_term_link($term->slug, 'listing_category')),
												esc_html($term->name)
											); ?>
											 
											<?php
										}
               ?>
			  
			   <?php
										$categories_list = join(" ", $categories);
									?>
											<?php echo ($categories_list) ?>
								<?php endif; ?>
								</li>
								<li class="service-map"><i class="feather-map-pin me-2"></i><?php if (get_the_listing_address()) : ?><?php the_listing_address(); ?><?php endif; ?></li>
							</ul>
						</div>
				</div>
				<div class="col-md-4">
						
						
						<div class="serv-action">
						<a class="btn btn-danger" id="share-button" href="#"><i class="feather-share-2"></i></a>
						</br></br>
<div id="social-icons" style="display: none;">
    <a class="social-icon" href="#" data-network="twitter"><i class="feather-twitter" style="font-size: 30px;display: inline-block;vertical-align: text-bottom;" ></i></a>
    <a class="social-icon" href="#" data-network="facebook"><i class="feather-facebook" style="font-size: 30px;display: inline-block;vertical-align: text-bottom;"></i></a>
    <a class="social-icon" href="#" data-network="linkedin"><i class="feather-linkedin" style="font-size: 30px;display: inline-block;vertical-align: text-bottom;"></i></a>
    <a class="social-icon" href="#" data-network="whatsapp"><img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2024/03/whatsapp.png" style="width: 32px !important; height: 32px !important; border: none; display: inline-block;vertical-align: text-bottom;"></a>
</div>
</br>
						<div class="fav-btn fav-btn-big">			
										<?php
										if ( is_active_sidebar( 'bookmark_widget' ) ) {
											dynamic_sidebar( 'bookmark_widget' );
										}
										?>
									</div>
						</div>
				</div>
				</div>



				<?php 


					$listing_type = get_post_meta(get_the_ID(), '_listing_type', true);

					if ($gallery_style == 'top' && $load_gallery == true) :

						$gallery = get_post_meta( $post->ID, '_gallery', true );
						?>
 
						<div class="service-gal">
							<div class="row">
							<div class="col-md-12">
							<?php if(!empty($gallery)) : ?>
 								<?php 
								$count = 1;
  								foreach ( (array) $gallery as $attachment_id => $attachment_url ) {
									$image = wp_get_attachment_image_src( $attachment_id, 'truelysell-gallery' );
									$thumb = wp_get_attachment_image_src( $attachment_id, 'medium' ); ?>
								 
									<div class="service-images big-gallery">
										<img src="<?php echo esc_attr($image[0]); ?>" class="img-fluid" alt="img">
										<a href="<?php echo esc_attr($image[0]); ?>" data-fancybox="gallery" class="btn btn-show"><i class="feather-image me-2"></i> <?php echo esc_html_e('Show all photos','truelysell_core'); ?></a>
									</div>
								
							 <?php 	if ($count == 1) {
								   break;
								}
								  ?>
								  <?php $count++; } ?>
                            </div>
								

								 
							</div>
						</div>

						<?php endif;
					else : ?>

<div class="service-gal">
							<div class="row">
							<div class="col-md-12">
							<div class="service-images big-gallery">
							<?php 						 
if(has_post_thumbnail($post->ID)){ 
	echo get_the_post_thumbnail($post->ID, 'full', array('class' => 'serv-img img-fluid'));
 } 
 ?></div>
                            </div>
								 
							</div>
						</div>
						
				

 					<?php endif; ?>

						<div class="service-wrap">
							<h5><?php echo esc_html_e('Service Details','truelysell_core'); ?></h5>
							<?php the_content(); ?>
						</div>
						<div class="service-wrap provide-service">
							<h5><?php echo esc_html_e('Service Provider','truelysell_core'); ?></h5>
							<div class="row">
								<div class="col-md-4">
								    	<?php $author_id=$post->post_author; 
								          $owner_data = get_userdata($author_id);
									?>
									
									<div class="provide-box">										
									<?php echo get_avatar($author_id, 56);  ?>									
										<div class="provide-info">
											<h6><?php echo esc_html_e('Provider','truelysell_core'); ?></h6>
								         	<p><?php echo $owner_data->display_name; ?></p>
										</div>	 
										 
									</div>
								</div>
								<div class="col-md-4">
									<div class="provide-box">
										<span><i class="feather-user"></i></span>
										<div class="provide-info">
											<h6><?php echo esc_html_e('Member Since','truelysell_core'); ?></h6>
											<p><?php if(get_userdata($author_id)) { echo date("M Y", strtotime(get_userdata($author_id)->user_registered)); }  ?></p>
										</div>
									</div>
								</div>
								 
								<div class="col-md-4">
									<div class="provide-box">
										<span><i class="feather-map-pin"></i></span>
										<div class="provide-info">
											<h6><?php echo esc_html_e('Address','truelysell_core'); ?></h6>
											<p><?php if (get_the_listing_address()) : ?><?php the_listing_address(); ?><?php endif; ?></p>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="provide-box">
										<span><i class="feather-mail"></i></span>
										<div class="provide-info">
											<h6><?php echo esc_html_e('Email','truelysell_core'); ?></h6>
											<p><?php if (isset($owner_data->user_email)) : $email = $owner_data->user_email; ?>
										<?php echo esc_html($email); ?> 
									<?php endif; ?></p>

										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="provide-box">
										<span><i class="feather-phone"></i></span>
										<div class="provide-info">
											<h6><?php echo esc_html_e('Phone','truelysell_core'); ?></h6>
											<p><?php if(get_the_listing_phone()) { 
														the_listing_phone(); 
													} ?></p>
										</div>
									</div>
								</div>
								<!--Added New Icon-->
								<div class="col-md-4">
									<div class="provide-box">
										<span><img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2024/03/whatsapp.png" style="width: 30px !important; height: 30px !important; border: none; display: inline-block;vertical-align: text-bottom;"></i></span>
										<div class="provide-info">
											<h6><?php echo esc_html_e('Whatsapp','truelysell_core'); ?></h6>
											<p><?php the_author_meta( '_whatsapp', $current_user->ID ); ?></p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="provide-box">
										<span><i class="feather-instagram"></i></span>
										<div class="provide-info">
											<h6><?php echo esc_html_e('Instagram','truelysell_core'); ?></h6>
											<p><?php the_author_meta( '_instagram_link', $current_user->ID ); ?></p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="provide-box">
										<span><img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2024/03/gps.png" style="width: 30px !important; height: 30px !important; border: none; display: inline-block;vertical-align: text-bottom;"></span>
										<div class="provide-info">
											<h6><?php echo esc_html_e('Waze','truelysell_core'); ?></h6>
											<p><?php the_author_meta( '_waze', $current_user->ID ); ?></p>
										</div>
									</div>
								</div>
								<!--End-->
								
								<div class="col-md-4">
									<div class="provide-box">
										<span><i class="feather-star"></i></span>
										 

										<div class="provide-info">
											<h6><?php echo esc_html_e('Reviews','truelysell_core'); ?></h6>

											<?php $rating = get_post_meta($post->ID, 'truelysell-avg-rating', true);
									if (isset($rating) && $rating > 0) {  ?>
											<?php $rating_type = get_option('truelysell_rating_type', 'star');  ?>
											<?php $number = truelysell_get_reviews_number($post->ID);  ?>
											<?php $rating_value = get_post_meta($post->ID, 'truelysell-avg-rating', true);  ?>
											<div class="serv-review"><i class="fa-solid fa-star"></i> <span><?php esc_attr(round($rating_value, 1)); printf("%0.1f", $rating_value);  ?></span> (<?php printf(_n('%s review', '%s reviews', $number, 'truelysell_core'), number_format_i18n($number));  ?>)</div>
											<?php  } else { ?>
												<div class="serv-review"><i class="fa-solid fa-star"></i> <span>0</span> (<?php echo esc_html_e( 'No Reviews', 'truelysell_core');  ?>)</div>
											<?php } ?>

 
										</div>	

									</div>
								</div>

								 
							</div>
						</div>
						 						
						<?php if(!empty($gallery)) { ?>
						<div class="service-wrap service_gallery">
							<div class="row">
								<div class="col-md-6">
									<h5><?php echo esc_html_e('Gallery','truelysell_core'); ?></h5>
								</div>
								<div class="col-md-6 text-md-end">
									<div class="owl-nav mynav3"></div>
								</div>
							</div>
							<div class="owl-carousel gallery-slider">
							<?php 
							 
								$count = 1;
  								foreach ( (array) $gallery as $attachment_id => $attachment_url ) {
									$image = wp_get_attachment_image_src( $attachment_id, 'truelysell-gallery' );
									$thumb_new = wp_get_attachment_image_src( $attachment_id, 'truelysell-listing-grid' ); ?>
								 
								 <div class="gallery-widget">
									<a href="<?php echo esc_attr($image[0]); ?>" data-fancybox="gallery">
										<img class="img-fluid" alt="Image" src="<?php echo esc_attr($thumb_new[0]); ?>">
									</a>
								</div>	
								  <?php $count++; }    ?>
 															
 							</div>
						</div>
						<?php } ?>

						<div class="service-wrap">
							<h5><?php echo esc_html_e('Video','truelysell_core'); ?></h5>
							<?php $template_loader->get_template_part('single-partials/single-listing', 'video');  ?>
					</div>

				<?php if (is_user_logged_in()): ?>	
	<?php 
	$current_customer_id = get_current_user_id();
	global $wpdb;
	$table_b = $wpdb->prefix . 'bookings_calendar';
	$query = $wpdb->prepare("SELECT * FROM $table_b WHERE bookings_author = %d AND listing_id = %d", $current_customer_id, $post->ID );
	$result = $wpdb->get_results( $query );
	$has_booking = false;
	foreach($result as $customer) {
		if ($customer->bookings_author == $current_customer_id) {
			$has_booking = true;
			break;
		}
	}

	if ($has_booking):
	?>
	<div class="service-wrap">
		<?php if ($load_reviews && !truelysell_fl_framework_getoptions('disable_reviews')) {
			$template_loader->get_template_part('single-partials/single-listing', 'reviews');
		} ?>

		<?php if (!truelysell_fl_framework_getoptions('disable_reviews')) { 
			$template_loader->get_template_part('single-partials/single-listing', 'addreviews');
		} ?>
	</div>
	<?php endif; ?>
<?php endif; ?>


<div class="service-wrap related_services_carousel">

<div class="row align-items-center">
							<div class="col-md-6">
								<div class="service-wrap-title">
								<h5><?php echo esc_html_e('Related Services','truelysell_core'); ?></h5>
								</div>
							</div>
							<div class="col-md-6 text-md-end">
								<div class="owl-nav mynav"></div>
							</div>
						</div>

<div class="service-carousel">
							<div class="owl-carousel related-slider">
								<?php 

									$args = array(
										'post_type' => 'listing',
										'post_status' => 'publish',
										'posts_per_page' =>6,
										'post__not_in' => array (get_the_ID()),
									);
								  
									$my_query = null;
									$my_query = new WP_query($args);
									if($my_query->have_posts()):
										while($my_query->have_posts()) : $my_query->the_post();
											$custom = get_post_custom( get_the_ID() );
											 if ( has_post_thumbnail() ) { 
													  $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
												 
										  }else{ 
												 $url=get_template_directory_uri( ) ."/assets/images/pricing-cover-placeholder.jpg";
										  } 
								?>

<div class="service-widget 4">
        <div class="service-img">
            <?php $template_loader->get_template_part('content-listing-image');  
            $rating_value = get_post_meta($post->ID, 'truelysell-avg-rating', true);
            ?>
            

          

             <div class="item-info">
				 
					<div class="cate-list">
                    <?php 
                        $terms = get_the_terms($post->ID, 'listing_category' ); 
                        if ( $terms && ! is_wp_error( $terms ) ) :  
							$main_term = array_pop($terms); ?>
							<span class="cat_name bg-yellow"><?php echo $main_term->name; ?></span>
						<?php endif; ?>
					
					</div>

					<div class="fav-item">
										 
										 <span class="serv-rating"><i class="fa-solid fa-star"></i><?php echo esc_attr(round($rating_value, 1));	?></span>
					  </div>

			</div>
        </div>
        <div class="service-content">
            <h3 class="title"><a href="<?php the_permalink();?>">
            <?php echo get_the_title(); ?></a></h3>

            <?php if(get_the_listing_address()) { ?>
                        <p><i class="feather-map-pin me-2"></i><?php the_listing_address(); ?></p>
                         <?php } ?>

            <?php  $listing_type = get_post_meta( $post->ID,'_listing_type',true ); 
               $is_instant = truelysell_core_is_instant_booking($post->ID); 
               
               ?>
	 
     <div class="serv-info">
					 <h6><?php  $currency_abbr = truelysell_fl_framework_getoptions('currency');
											$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
									$normal_price = $currency_symbol . (float) get_post_meta(get_the_ID(), '_normal_price', true); 
									echo esc_html($normal_price);
                                     ?></h6>
					 <a href="<?php the_permalink(); ?>" class="btn btn-book"><?php echo esc_html_e('Book Now','truelysell_core'); ?></a>
	   </div>
        </div>
    </div>
							 
								<?php 
								endwhile;
								wp_reset_postdata();
							    else :
							_e( 'Sorry, no posts matched your criteria.' );
							endif;
								?>
							</div>
						</div>

					</div>
					</div>


					<?php if ($layout == "right-sidebar" && $mobile_layout != "left-sidebar") : ?>
						<div class="col-lg-4 col-md-12 service_sidebar theiaStickySidebar">
							<div class="card card-provide">
							<div class="card-body">

							<div class="provide-widget mb-0">
									<div class="service-amount">
										<h5><?php 
									$currency_abbr = truelysell_fl_framework_getoptions('currency');
									$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
									$normal_price = $currency_symbol . (float) get_post_meta($post->ID, '_normal_price', true); 
										echo esc_html($normal_price);
										?></h5>

<?php $rating = get_post_meta($post->ID, 'truelysell-avg-rating', true);
									if (isset($rating) && $rating > 0) : ?>
											<?php $rating_type = "star"  ?>
											<?php $number = truelysell_get_reviews_number($post->ID);  ?>
											<?php $rating_value = get_post_meta($post->ID, 'truelysell-avg-rating', true);  ?>
											<div class="serv-review"><i class="fa-solid fa-star"></i> <span><?php esc_attr(round($rating_value, 1)); printf("%0.1f", $rating_value);  ?></span> (<?php printf(_n('%s review', '%s reviews', $number, 'truelysell_core'), number_format_i18n($number));  ?>)</div>
											<?php endif; 
									 ?>

 									</div>
									<div class="serv-proimg">
									<?php $author_id=$post->post_author; 
								          $owner_data = get_userdata($author_id);
									?>
									<?php echo get_avatar($author_id, 56);  ?>
										 
									</div>

									
								</div>
 
	
							
						

						</div>
						<!-- Sidebar / End -->

						
 						</div>
	<!-- Bookservice Modal -->
						 <?php
							$classifieds_price = get_post_meta($post->ID, '_classifieds_price', true);
							
							if ($listing_type == 'classifieds' && !empty($classifieds_price)) {
								
								$currency_abbr = truelysell_fl_framework_getoptions('currency');
								$currency_postion = truelysell_fl_framework_getoptions('currency_postion');
								$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);

							?>
								<span id="classifieds_price">
									<?php if ($currency_postion == "before") {
											echo $currency_symbol;
										}
										echo get_post_meta($post->ID, '_classifieds_price', true);
										if ($currency_postion == "after") {
											echo $currency_symbol;
										} ?>
									</span>
							<?php } ?>

							<?php  if(!truelysell_fl_framework_getoptions('disable_service_availability')) { ?>
							<?php get_sidebar('listing'); ?>
							<?php } ?>
							<?php  if(!truelysell_fl_framework_getoptions('bookings_disabled')) { ?>
							<!-- <div class="service-book "></div>-->
							<?php if (is_user_logged_in()): ?>
							<div class="d-grid gap-2">
									<!--<button type="button" class="btn btn-primary btn-block popup-with-zoom-anim" data-bs-toggle="modal" data-bs-target="#bookservice-dialog">-->

								<button type="button" class="btn btn-primary btn-block popup-with-zoom-anim book_now" id="cbook_now">
								<?php echo esc_html_e('Book Service','truelysell_core'); ?></button>
								<!--<button type="button" class="btn btn-primary btn-block popup-with-zoom-anim book_now">-->
								<!--test</button>-->
								
								<!--<button type="button" class="btn btn-primary btn-block popup-with-zoom-anim book_now">-->
								<!--test</button>-->
							</div>
							<?php else: ?>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-primary btn-block popup-with-zoom-anim book_now" id="cbook_now" title="Please login as a Customer" disabled>
                                        <?php echo esc_html_e('Book Service (Please login as a Customer)', 'truelysell_core'); ?>
                                    </button>
                                </div>
                            <?php endif; ?>
							<?php } ?>
								<!-- /Bookservice Modal -->

							</div>
							</div>

							<!-- Vertically centered modal -->
 

							<div class="modal fade  custom-modal modal fade zoom-anim-dialog mfp-hide" id="bookservice-dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Book Service', 'truelysell_core'); ?></h1>
        <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="feather-x"></i></button>
      </div>
      <div class="modal-body">
	 

					<?php
					if(!is_user_logged_in()){ ?>
						<div class="notification error">
								<span><?php echo esc_html('Sorry You need to login as customer for booking','truelysell_core'); ?></span>
						</div>
					<?php }
					if (is_user_logged_in()) {
						$user_id = get_current_user_id();
						$current_user = wp_get_current_user();
						$roles = $current_user->roles;
						if($roles[0] == "owner"){ ?>
							<div class="notification error">
								<span><?php echo esc_html('Sorry You need to login as customer for booking','truelysell_core'); ?></span>
							</div>
						<?php }
						else{
							if ( is_active_sidebar( 'bookservice_widget' ) ) {
								dynamic_sidebar( 'bookservice_widget' );
							}
						}
					}
						
					?>
      </div>
       
    </div>
  </div>
</div>

					<?php endif; ?>


 				</div>

				 <div class="listing-titlebar-tags">
								
								<?php
								$listing_type = get_post_meta(get_the_ID(), '_listing_type', true);
								switch ($listing_type) {
									case 'service':
										$type_terms = get_the_terms(get_the_ID(), 'service_category');
										$taxonomy_name = 'service_category';
										break;
									case 'rental':
										$type_terms = get_the_terms(get_the_ID(), 'rental_category');
										$taxonomy_name = 'rental_category';
										break;
									case 'event':
										$type_terms = get_the_terms(get_the_ID(), 'event_category');
										$taxonomy_name = 'event_category';
										break;
									case 'classifieds':
										$type_terms = get_the_terms(get_the_ID(), 'classifieds_category');
										$taxonomy_name = 'classifieds_category';
										break;

									default:
										# code...
										break;
								}
								if (isset($type_terms)) {
									if ($type_terms && !is_wp_error($type_terms)) :
										$categories = array();
										foreach ($type_terms as $term) {
											$categories[] = sprintf(
												'<a href="%1$s">%2$s</a>',
												esc_url(get_term_link($term->slug, $taxonomy_name)),
												esc_html($term->name)
											);
										}

										$categories_list = join(", ", $categories);
								?>
										<span class="listing-tag">
											<?php echo ($categories_list) ?>
										</span>
								<?php endif;
								}
								?>
								
							</div>

				<!-- sidebarright ends -->


				<?php endwhile; // End of the loop. 
				?>
		 
 
    </div> <!-- content -->

	<?php else : ?>

		<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>
 <script type="text/javascript">
 jQuery(document).ready(function($){
     
     $('#cbook_now').on('click', function(){
        //  $("#cbook_now").click(function(){

         $('#date-picker').trigger('click');
         
     });
     

     
 });
 </script>

	<?php get_footer(); ?>
