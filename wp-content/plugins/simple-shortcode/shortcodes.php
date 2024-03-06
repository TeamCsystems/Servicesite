<?php
/**
 * Plugin Name: Simple Shortcode
 * Description:       This is simple shortcode plugin.
 * Version:           1.8.0
 * Author: Dreams Technologies
 * Author URI: https://dreamstechnologies.com/
 * Plugin URI: https://dreamstechnologies.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-shortcode
 */
function services_loop_shortcode() {
	ob_start();
	$args = array(
		'post_type' => 'listing',
		'post_status' => 'publish',
		'posts_per_page' =>6,
		'orderby' => 'meta_value_num',
    	'order' => 'DESC'
	);
	$my_query = null;
	$my_query = new WP_query($args);
	?>
	<div class="service-carousel-old">
	<div class="service-slider-old owl-carousel-old owl-theme-old">
	<?php
	if($my_query->have_posts()):
		while($my_query->have_posts()) : $my_query->the_post();
			$custom = get_post_custom( get_the_ID() );
			 if ( has_post_thumbnail() ) { 
					  $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
				 
		  }else{ 
				 $url=get_template_directory_uri( ) ."/assets/images/pricing-cover-placeholder.jpg";
		  } 
  
			ob_start();
  
			?>
			<div class="service-widget">
									<div class="service-img">
										<a href="<?php the_permalink();?>">
											<img src="<?php echo  esc_html($url);?>"/>
										</a>
										<div class="fav-btn">
										<?php
										if (truelysell_core_check_if_bookmarked(get_the_ID())) {
											$nonce = wp_create_nonce("truelysell_core_bookmark_this_nonce"); ?>
											<span class="like-icon truelysell_core-unbookmark-it liked" data-post_id="<?php echo esc_attr(get_the_ID()); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"></span>
											<?php } else {
											if (is_user_logged_in()) {
												$nonce = wp_create_nonce("truelysell_core_remove_fav_nonce"); ?>
												<span class="save truelysell_core-bookmark-it like-icon" data-post_id="<?php echo esc_attr(get_the_ID()); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"></span>
											<?php } else { ?>
												<span class="save like-icon tooltip left" title="<?php esc_html_e('Login To Bookmark Items', 'truelysell_core'); ?>"></span>
											<?php } ?>
										<?php } ?>
										</div>
										<div class="item-info">
											<div class="service-user">
											<?php 
												
												$user_id = get_current_user_id();
												$current_user = wp_get_current_user();
												echo get_avatar($current_user->user_email, 32); ?>
													
												<span class="service-price 2">
												<?php 
											$currency_abbr = get_option('truelysell_currency');
											$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
									$normal_price = $currency_symbol . (float) get_post_meta(get_the_ID(), '_normal_price', true); 
									echo esc_html($normal_price);
									?>
												</span>
											</div>
											<div class="cate-list">
											<?php 
											$terms = get_the_terms( get_the_ID(), 'listing_category' );            
											if ( $terms && ! is_wp_error( $terms ) ) :  
												$main_term = array_pop($terms); ?>
												<a class="bg-yellow" href="#"><?php echo esc_html($main_term->name); ?></a>
										<?php endif; ?>
											</div>
										</div>
									</div>
									<div class="service-content">
										<h3 class="title">
											<a href="<?php the_permalink();?>"><?php the_title(); ?></a>
										</h3>
										<?php 
										$is_instant = truelysell_core_is_instant_booking(get_the_ID());

										if ($is_instant) { ?>
											<div class="listing-small-badge instant-badge"><i class="fa fa-bolt"></i> <?php esc_html_e('Online Payment', 'truelysell_core'); ?></div>
										<?php } ?>
										<div class="rating 1">
										<?php
										
											$listing_type = get_post_meta(get_the_ID(),'_listing_type', true);

												if(!get_option('truelysell_disable_reviews')){
													

													if($listing_type=='classifieds'){
										
														
																$price = get_post_meta(get_the_ID(), '_classifieds_price', true);
																$currency_abbr = get_option( 'truelysell_currency' );
																$currency_postion = get_option( 'truelysell_currency_postion' );
																$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
																if($price){ ?>
																	<div class="listing-classifieds-badges-container">
															<div class="listing-small-badge pricing-badge classifieds-pricing-badge"><i class="fa fa-<?php echo esc_attr(get_option('truelysell_price_filter_icon','tag')); ?>"></i><?php if($currency_postion == "before") { echo esc_html($currency_symbol);} echo get_post_meta(get_the_ID(), '_classifieds_price', true);  if($currency_postion == "after") { echo esc_html($currency_symbol);}?></div></div>
															<?php } 
													
													} else {


													$rating = get_post_meta(get_the_ID(), 'truelysell-avg-rating', true); 
													if(!$rating && get_option('truelysell_google_reviews_instead')){
															$reviews = truelysell_get_google_reviews($post);
															if(!empty($reviews['result']['reviews'])){
																$rating = number_format_i18n($reviews['result']['rating'],1);
															$rating = str_replace(',', '.', $rating);
															}
														}
														if(isset($rating) && $rating > 0 ) : $rating_type = get_option('truelysell_rating_type','star');
														if($rating_type == 'numerical') { ?>
															<div class="numerical-rating" data-rating="<?php $rating_value = esc_attr(round($rating,1)); printf("%0.1f",$rating_value); ?>">
														<?php } else { ?>
															<div class="star-rating" data-rating="<?php echo esc_html($rating); ?>">
														<?php } ?>
														<?php $number = truelysell_get_reviews_number(get_the_ID()); 
														if(!get_post_meta(get_the_ID(), 'truelysell-avg-rating', true) && get_option('truelysell_google_reviews_instead')){ 
																			$number = $reviews['result']['user_ratings_total'];
																			}  ?>
														<div class="rating-counter 1">(<?php printf( _n( '%s review', '%s reviews', $number,'truelysell_core' ), number_format_i18n( $number ) );  ?>)</div>
														
														</div>
													<?php else: ?>
															<div class="star-rating" data-rating="<?php echo esc_html($rating); ?>">
															<span class="star empty"></span>
															<span class="star empty"></span>
															<span class="star empty"></span>
															<span class="star empty"></span>
															<span class="star empty"></span>
															<?php 
															$number = truelysell_get_reviews_number(get_the_ID()); 
														if(!get_post_meta(get_the_ID(), 'truelysell-avg-rating', true) && get_option('truelysell_google_reviews_instead')){ 
																			$number = $reviews['result']['user_ratings_total'];
																			}  ?>
															<div class="rating-counter 2">(<?php printf( _n( '%s review', '%s reviews', $number,'truelysell_core' ), number_format_i18n( $number ) );  ?>)</div>
															</div>
													<?php endif;
													}   
												} ?>
										</div>
										<div class="user-info">
											<div class="row">	
											<span class="col-auto ser-contact"><i class="fas fa-phone me-1"></i> 
													<span>
														<?php if(get_the_listing_phone()) { 
														the_listing_phone(); 
													} ?></span>
												</span>
												<span class="col ser-location">
												<?php if(get_the_listing_address()) { ?><span><?php the_listing_address(); ?></span><?php } ?>
												<i class="fas fa-map-marker-alt ms-1"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
			<?php 
			echo ob_get_clean();
  
		endwhile;
		wp_reset_postdata();
	else :
		esc_html( 'Sorry, no posts matched your criteria.' );
	endif;
	?>
	</div>
	</div>

  <?php 
  return ob_get_clean(); }
  
  add_shortcode( 'services_loop', 'services_loop_shortcode' );

  function provider_loop_shortcode() {
	ob_start();
    $args = array(
    'role'    => 'owner',
	'number'  => 4, // limit
    //'orderby' => 'user_nicename',
    'order'   => 'ASC'
);
$users = get_users( $args ); ?>

<div class="row aos aos-init aos-animate" data-aos="fade-up">
	<?php foreach ( $users as $user ) { ?>
					<div class="col-lg-3 col-sm-6">
						<div class="providerset">
							<div class="providerset-img">
 								<?php
								  $owner_id = $user->ID;
							     ?>
								 
								 <?php echo get_avatar($owner_id);  ?>
							</div>
							<div class="providerset-content">
								<div class="providerset-price mb-0">
									<div class="providerset-name">
										<h4 class="mb-2"><a href="#"><?php echo esc_html_e( $user->display_name ); ?></a></h4>
										<span><?php echo esc_html_e( $user->user_email ); ?></span>
									</div>
								 
								</div>
								 
							</div>
						</div>
					</div>
					<?php 
  } ?>
  
   				</div>

 <?php return ob_get_clean(); } 
 add_shortcode( 'provider_loop', 'provider_loop_shortcode' ); 


  function truelysell_posttype() {

  register_post_type( 'testimonial',
  // CPT Options
	  array(
		  'labels' => array(
			  'name' => __( 'Testimonials' ),
			  'singular_name' => __( 'testimonial' )
		  ),
		  'public' => true,
		  'has_archive' => true,
		  'supports' => array('title','editor','thumbnail','custom-fields','excerpt'),
		  'rewrite' => array('slug' => 'testimonial'),
		  'show_in_rest' => true,

	  )
  );
  
}
// Hooking up our function to theme setup
add_action( 'init', 'truelysell_posttype' );