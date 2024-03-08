<?php
/**
 * listing Submission Form
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$current_user = wp_get_current_user();
$roles = $current_user->roles;
$role = array_shift( $roles ); 
if(!in_array($role,array('administrator','admin','owner','seller'))) :
	$template_loader = new Truelysell_Core_Template_Loader; 
	$template_loader->get_template_part( 'account/owner_only'); 
	return;
endif;

$fields = array();
if(isset($data)) :
	$fields	 	= (isset($data->fields)) ? $data->fields : '' ;
endif;
if(isset($_GET["action"])) {
	$form_type = $_GET["action"];
} else {
	$form_type = 'submit';
}

$packages = $data->packages;
$user_packages = $data->user_packages;

global $woocommerce;
$woocommerce->cart->empty_cart();


?>
<form method="post" id="package_selection">
<?php if ( $packages || $user_packages ) :
	$checked = 1;
	?>
	
		<?php if ( $user_packages ) : ?>
 			<ul class="price-card flex-fill user-packages">

			<div class="plan">
										<div class="price-head">
								<div class="price-level">
									<h4><?php esc_html_e( 'Choose Your Package', 'truelysell_core' ); ?></h4>
								</div>
 							</div>
			</div>

				<?php 
				foreach ( $user_packages as $key => $package ) :
					$package = truelysell_core_get_package( $package );
					?>
					<li class="user-job-package">
					<div class="radio_hole"><input type="radio" <?php checked( $checked, 1 ); ?> name="package" value="user-<?php echo $key; ?>" id="user-package-<?php echo $package->get_id(); ?>" /> 
					<label for="user-package-<?php echo $package->get_id(); ?>"><?php echo $package->get_title(); ?> 
					<p>
						<?php
						if ( $package->get_limit() ) {
							printf( _n( 'You have %1$s listings posted out of %2$d', 'You have %1$s listings posted out of %2$d', $package->get_count(), 'truelysell_core' ), $package->get_count(), $package->get_limit() );
						} else {
							printf( _n( 'You have %s listings posted', 'You have %s listings posted', $package->get_count(), 'truelysell_core' ), $package->get_count() );
						}

						if ( $package->get_duration() && $package->get_duration() > 1 ) {
							printf( ', ' . _n( 'listed for %s day', 'listed for %s days', $package->get_duration(), 'truelysell_core' ), $package->get_duration() );
						}

						$checked = 0;
					?>
					</p></label>
					</div>

				</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $packages ) : ?>


			<h4 class="pricing_headline">
				<?php 
				if ( $user_packages ) : 
					esc_html_e('Or Purchase New Package','truelysell_core'); 
				else:  
					esc_html_e( 'Choose Package', 'truelysell_core' ); ?>
				<?php endif; ?>
			</h4>
			<div class="clearfix"></div>
			<div class="pricing-container row">
				
			<?php
			$counter = 0;
			$single_buy_products = get_option('truelysell_buy_only_once');
			foreach ( $packages as $key => $package ) :
				
				$product = wc_get_product( $package );
				if ( ! $product->is_type( array( 'listing_package','listing_package_subscription' ) ) || ! $product->is_purchasable() ) {
					continue;
				}
				if($single_buy_products) {
					$user = wp_get_current_user();
					if ( in_array( $product->get_id(), $single_buy_products )  && wc_customer_bought_product( $user->user_email, $user->ID, $product->get_id() ) ) {
					        continue;
					}
				}
				$user_id = get_current_user_id();
				if (  $product->is_type( array( 'listing_package_subscription' ) ) && wcs_is_product_limited_for_user( $product, $user_id ) ) {
					continue;
				}
				?>
<div class="col-md-4 d-flex">	
				<div class="price-card flex-fill">	
				<div class="plan   <?php echo ($product->is_featured()) ? 'featured' : '' ; ?>">
					<?php if( $product->is_featured() ) : ?>
						<div class="listing-badge">
							<span class="featured"><?php esc_html_e('Featured','truelysell_core'); ?></span>
						</div>
					<?php endif; ?>
					<div class="price-head">
								<div class="price-level">
									<h6><?php echo $product->get_title();?></h6>
								</div>
								<h1><?php echo $product->get_price_html(); ?>  </h1>
								<?php if($product->get_short_description() ) { ?><p><?php echo $product->get_short_description(); ?></p><?php } ?></p>
							</div>
	                

                <div class="plan-features price-body">
                    <ul class="plan-features-auto-wc">
                        <?php 
                        $listingslimit = $product->get_limit();
                        if(!$listingslimit){
                            echo "<li>";
                             esc_html_e('Unlimited number of listings','truelysell_core'); 
                             echo "</li>";
                        } else { ?>
                            <li>
                                <?php esc_html_e('This plan includes ','truelysell_core'); printf( _n( '%d listing', '%s listings', $listingslimit, 'truelysell_core' ) . ' ', $listingslimit ); ?>
                            </li>
                        <?php }
                        $duration = $product->get_duration();
                        if($duration > 0 ): ?>
                        <li>
                            <?php esc_html_e('Listings are visible ','truelysell_core'); printf( _n( 'for %s day', 'for %s days', $product->get_duration(), 'truelysell_core' ), $product->get_duration() ); ?>
                        </li>
                        <?php else : ?>
                        	<li>
	                            <?php esc_html_e('Unlimited availability of listings','truelysell_core');  ?>
	                        </li>
                        <?php endif; ?>
                        <?php if(truelysell_fl_framework_getoptions('populate_listing_package_options')): ?>
	                        <?php  
	                        $bookingOptions = $product->has_listing_booking(); 
	                        if($bookingOptions) : ?>
	                        	<li class="active">
		                            <?php esc_html_e('Booking Module enabled','truelysell_core');  ?>
		                        </li>
 	                        <?php endif; ?> 

	             
	                        <?php  
	                        $reviewsOptions = $product->has_listing_reviews(); 
	                        if($reviewsOptions) : ?>
	                        	<li class="active">
		                            <?php esc_html_e('Reviews Module enabled','truelysell_core');  ?>
		                        </li>
							<?php else :?> 
								<li class="inactive">
		                            <?php esc_html_e('Reviews Module disabled','truelysell_core');  ?>
		                        </li>
	                        <?php endif; ?>

	                        <?php  
	                        $sociallinksOptions = $product->has_listing_social_links(); 
	                        if($sociallinksOptions) : ?>
	                        	<li class="active">
		                            <?php esc_html_e('Social Links Module enabled','truelysell_core');  ?>
		                        </li>
							 
	                        <?php endif; ?>

	                        <?php  
	                        $openinghoursOptions = $product->has_listing_opening_hours(); 
	                        if($openinghoursOptions) : ?>
	                        	<li class="active">
		                            <?php esc_html_e('Opening Hours Module enabled','truelysell_core');  ?>
		                        </li>
								<?php else :?> 
									<li class="inactive">
		                            <?php esc_html_e('Opening Hours Module disabled','truelysell_core');  ?>
		                        </li>
	                        <?php endif; ?>

	                        <?php  
	                        $vidosOptions = $product->has_listing_video(); 
	                        if($vidosOptions) : ?>
	                        	<li class="active">
		                            <?php esc_html_e('Video option enabled','truelysell_core');  ?>
		                        </li>
							<?php else :?> 
								<li class="inactive">
		                            <?php esc_html_e('Video option disabled','truelysell_core');  ?>
		                        </li>
	                        <?php endif; ?> 

	                        <?php  
	                        $couponsOptions = $product->has_listing_coupons(); 
	                        if($couponsOptions == 'yes') : ?>
	                        	<li class="active">
		                            <?php esc_html_e('Coupons option enabled','truelysell_core');  ?>
		                        </li>
								 
	                        <?php endif; ?>

	                        <?php  
	                        $galleryOptions = $product->has_listing_gallery(); 
	                        if($galleryOptions == 'yes')  :  ?>
	                        	<li class="active">
		                            <?php esc_html_e('Gallery Module enabled','truelysell_core');  ?>
		                        </li>
								<?php else :?> 
								<li class="inactive">
		                            <?php esc_html_e('Gallery Module disabled','truelysell_core');  ?>
		                        </li>
								<?php endif; ?>
	                        <?php  
	                        $gallery_limitOptions = $product->get_option_gallery_limit(); 
	                        if($gallery_limitOptions) : ?>
	                        	<li class="active">
		                            <?php printf( esc_html__( 'Maximum  %s images in gallery', 'truelysell_core' ), $product->get_option_gallery_limit() );  ?>
		                        </li>
								<?php else :?> 
									<li class="inactive">
		                            <?php printf( esc_html__( 'Maximum  0 images in gallery', 'truelysell_core' ), $product->get_option_gallery_limit() );  ?>
		                        </li>
	                        <?php endif; ?>
                        <?php endif; ?>

                    </ul>
                    <?php 
                       
                    	echo $product->get_description();
                   
                    ?>
                    <div class="clearfix"></div>
					<div class="btn btn-choose">
                    <input type="radio" <?php if( !$user_packages && $counter==0) : ?> checked="checked" <?php endif; ?> name="package" value="<?php echo $product->get_id(); ?>" id="package-<?php echo $product->get_id(); ?>" />
                  	<label for="package-<?php echo $product->get_id(); ?>"><?php ($product->get_price()) ? esc_html_e('Buy this package','truelysell_core') : esc_html_e('Choose this package','truelysell_core');  ?></label> <i class="feather-arrow-right-circle ms-2"></i>
					</div>
                </div>
            </div>
			</div>
			</div>
			<?php $counter++;
			endforeach; ?>
			</div>
		<?php endif; ?>
	</ul>
<?php else : ?>

	<p><?php _e( 'No packages found', 'truelysell_core' ); ?></p>

<?php endif; ?>
<div class="submit-page">

	<p>
		<input type="hidden" 	name="truelysell_core_form" value="<?php echo $data->form; ?>" />
		<input type="hidden" 	name="listing_id" value="<?php echo esc_attr( $data->listing_id ); ?>" />
		<input type="hidden" 	name="step" value="<?php echo esc_attr( $data->step ); ?>" />
		<button type="submit" name="continue"  class="button btn btn-primary"><?php echo esc_attr( $data->submit_button_text ); ?> <i class="fa fa-arrow-circle-right"></i></button>

		
	</p>

</form>
</div>