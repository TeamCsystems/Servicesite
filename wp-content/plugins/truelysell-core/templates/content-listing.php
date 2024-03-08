<?php
$template_loader = new Truelysell_Core_Template_Loader;
$is_featured = truelysell_core_is_featured($post->ID);
$is_instant = truelysell_core_is_instant_booking($post->ID);
$listing_type = get_post_meta($post->ID, '_listing_type', true);
$rating_value = get_post_meta($post->ID, 'truelysell-avg-rating', true);
?>
<!-- Listing Item -->

<div class="col-lg-12 col-md-12 test">

<div class="booking-list">
							<div class="booking-widget">
								<div class="booking-img">
									<a href="<?php the_permalink(); ?>">
									<?php $template_loader->get_template_part('content-listing-image');  ?>
				
									</a>
									<div class="fav-item">
										 
										<span class="serv-rating"><i class="fa-solid fa-star"></i><?php echo esc_attr(round($rating_value, 1));	?></span>
					 </div>
								 
								</div>
								<div class="booking-det-info">
									<h3>
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>

									<div class="item-info listing_iteminfo pb-0 pe-0">
   			

									<?php $terms = get_the_terms(get_the_ID(), 'listing_category');
				if ($terms && !is_wp_error($terms)) :
					$categories = array();
					foreach ($terms as $term) {
						//$categories[] = $term->name;

						$categories[] = sprintf(
							'<span class="cat_name bg-yellow">%2$s </span>',
							esc_url(get_term_link($term->slug, 'listing_category')),
							esc_html($term->name)
						);

					}

					$categories_list = join(" ", $categories);
				?>
					<div class="cate-list">
						 <?php echo ($categories_list) ?> 
					</div>
				<?php endif; ?> 
				</div>
				<div class="address"><i class="feather-map-pin me-2"></i><?php if(get_the_listing_address()) { ?><?php the_listing_address(); ?><?php } ?>
					</div>
					<h6 class="mb-0"><?php  $currency_abbr = truelysell_fl_framework_getoptions('currency');
											$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
									$normal_price = $currency_symbol . (float) get_post_meta(get_the_ID(), '_normal_price', true); 
									echo esc_html($normal_price);
                                     ?></h6>




								</div>
				                              
 
								 
							</div>
							<div class="booking-action">
								<a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php echo esc_html_e('Book Now','truelysell_core'); ?></a>
							</div>
						</div>

</div>

<!-- Listing Item / End -->