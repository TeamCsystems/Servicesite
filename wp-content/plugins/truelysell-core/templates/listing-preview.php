<div class="alert alert-info">
	 <strong><?php esc_html_e('Notice!', 'truelysell_core'); ?></strong> <?php esc_html_e("This is preview of service you've submitted, please confirm or edit your submission using buttons at the end of that page.", 'truelysell_core'); ?>  
</div>

<div class="listing_preview_container">

	<?php
	$template_loader = new Truelysell_Core_Template_Loader;
	$post = get_post();
	$post_id = $post->ID;
	?>
	<?php


	$gallery_style = 'content';

	if ($gallery_style == 'top') :
		//$template_loader->get_template_part('single-partials/single-listing', 'gallery');
	endif; ?>
	<div id="titlebar" class="listing-titlebar">
		<div class="listing-titlebar-title">
			
			<h3><?php the_title(); ?></h3>
			<div class="item-info listing_iteminfo">
			<div class="cate-list">
				<?php
				$listing_type = get_post_meta(get_the_ID(), '_listing_type', true);
				$terms = get_the_terms(get_the_ID(), 'listing_category');
				if ($terms && !is_wp_error($terms)) :
					$categories = array();
					foreach ($terms as $term) {

						$categories[] = sprintf(
							'<a href="%1$s">%2$s</a>',
							esc_url(get_term_link($term->slug, 'listing_category')),
							esc_html($term->name)
						);
					}

					$categories_list = join(", ", $categories);
				?>
					<span class="listing-tag">
						<?php echo ($categories_list) ?>
					</span>
				<?php endif; ?>
				<?php
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
					
				
			</div>
			
			 
			<?php if (get_the_listing_address()) : ?>
				<p><i class="feather-map-pin me-2"></i><?php the_listing_address(); ?></p>
				 
			<?php endif; ?>

			<h6 class="listing-pricing-tag mb-3">
						<?php 
						$currency_abbr = truelysell_fl_framework_getoptions('currency');
						$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
						$normal_price = $currency_symbol . (float) get_post_meta(get_the_ID(), '_normal_price', true); 
						echo esc_html($normal_price);
									?>
				</h6>
 
		</div>


		




      
 



 	<?php if (truelysell_fl_framework_getoptions('edit_listing_requires_approval')) { ?>
		<div class="notification closeable notice">
			<?php esc_html_e('Editing listing requires admin approval, your listing will be unpublished if you Save Changes.', 'truelysell_core'); ?>
		</div>
	<?php } ?>

	<form method="post" id="listing_preview">
		<div class="row margin-bottom-30">
			<div class="col-md-12">

				<button type="submit" value="edit_listing" name="edit_listing" class="button btn btn-primary btn-outline-primary submit-btn margin-top-20"><i class="fa fa-edit"></i> <?php esc_attr_e('Edit service', 'truelysell_core'); ?></button>
				<button type="submit" value="<?php echo apply_filters('submit_listing_step_preview_submit_text', __('Submit Service', 'truelysell_core')); ?>" name="continue" class="button btn btn-primary submit-btn margin-top-20"><i class="fa fa-check"></i>
					<?php
					if (isset($_GET["action"]) && $_GET["action"] == 'edit') {
						esc_html_e('Save Changes', 'truelysell_core');
					} else {
						echo apply_filters('submit_listing_step_preview_submit_text', __('Submit Service', 'truelysell_core'));
					} ?>
				</button>

				<input type="hidden" name="listing_id" value="<?php echo esc_attr($data->listing_id); ?>" />
				<input type="hidden" name="step" value="<?php echo esc_attr($data->step); ?>" />
				<input type="hidden" name="truelysell_core_form" value="<?php echo $data->form; ?>" />
			</div>
		</div>
	</form>