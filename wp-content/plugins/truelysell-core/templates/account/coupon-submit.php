<?php

/**
 * Coupon Submission Form
 */
if (!defined('ABSPATH')) exit;


$current_user = wp_get_current_user();
$roles = $current_user->roles;
$role = array_shift($roles);
if (!in_array($role, array('administrator', 'admin', 'owner', 'seller'))) :
	$template_loader = new Truelysell_Core_Template_Loader;
	$template_loader->get_template_part('account/owner_only');
	return;
endif;

if (isset($data->coupon_data)) {
	$coupon_id = $data->coupon_data->ID;
}
?>
<div class="submit-page submit-coupon">

	<?php
	if (isset($data->coupon_edit) && $data->coupon_edit) {
	?>
		<div class="notification row closeable notice">
			<p><strong><?php esc_html_e('You are editing coupon.', 'truelysell_core'); ?></strong></p>
		</div>
	<?php }
	$coupons_page = get_option('truelysell_coupons_page');

	?>
	<form action="<?php echo get_permalink($coupons_page); ?>" method="post" id="submit-listing-form" class="listing-manager-form" enctype="multipart/form-data">
		<?php if (isset($data->coupon_edit) && $data->coupon_edit) { ?>
			<input type="hidden" name="truelysell-coupon-edit" value="1">
			<input type="hidden" name="truelysell-coupon-id" value="<?php echo $coupon_id; ?>">
		<?php } else { ?>
			<input type="hidden" name="truelysell-coupon-submission" value="1">
		<?php } ?>

		<div class="add-listing-section row">
			<div class="add-listing-headline">
				<h3class="mb-4"><i class="sl sl-icon-doc"></i> <?php esc_html_e('General Coupon Settings', 'truelysell_core'); ?></h3>
			</div>
			<div class="col-md-12">
				<p class="form-field coupon_amount_field ">
					<label for="coupon_amount"><?php esc_html_e('Coupon code', 'truelysell_core'); ?></label>
					<input type="text" required class="short wc_input_decimal" style="" value="<?php if (isset($coupon_id)) echo esc_html($data->coupon_data->post_title); ?>" name="title" id="coupon_title">
				</p>
			</div>
			<div class="col-md-12">
				<p class="form-field coupon_amount_field ">
					<label for="coupon_amount"><?php esc_html_e('Coupon Description', 'truelysell_core'); ?></label>
					<textarea style="min-height: 100px;" id="woocommerce-coupon-description" name="excerpt" cols="5" rows="1" placeholder="<?php esc_html_e('Description (optional)', 'truelysell_core'); ?>"><?php if (isset($coupon_id)) echo esc_html($data->coupon_data->post_excerpt); ?></textarea>
				</p>
			</div>
			<div class="col-md-12">

				<?php
				$coupon_bg = false;
				if (isset($coupon_id)) {


					$coupon_bg = get_post_meta($coupon_id, 'coupon_bg-uploader-id', true);
					$coupon_bg_url = wp_get_attachment_url($coupon_bg);
				}

				if (isset($coupon_bg) && !empty($coupon_bg)) { ?>
					<div data-photo="<?php echo $coupon_bg_url; ?>" data-name="<?php esc_html_e('Coupon Widget Background', 'truelysell_core'); ?>" data-size="<?php echo filesize(get_attached_file($coupon_bg)); ?>" class="edit-coupon-photo">

					<?php } else { ?>
						<div class="edit-coupon-photo">
						<?php } ?>

						<div id="coupon_bg-uploader" class="dropzone">
							<div class="dz-message" data-dz-message><span><?php esc_html_e('Upload Widget Background', 'truelysell_core'); ?></span></div>
						</div>
						<input class="hidden" name="truelysell_coupon_bg_id" type="text" id="coupon_bg-uploader-id" value="<?php echo $coupon_bg; ?>" />
						</div>



					</div>
					<div id="general_coupon_data" class="panel woocommerce_options_panel" style="display: block;">
						<div class="col-md-4">
							<p class=" form-field discount_type_field">
								<label for="discount_type"><?php esc_html_e('Discount type', 'truelysell_core'); ?></label>
								<select class="select2-single" id="discount_type" name="discount_type">
									<?php $discount_type_value = (isset($coupon_id)) ? get_post_meta($coupon_id, 'discount_type', true) : ''; ?>
									<option <?php selected($discount_type_value, 'percent'); ?> value="percent"><?php esc_html_e('Percentage discount', 'truelysell_core'); ?></option>
									<option <?php selected($discount_type_value, 'fixed_product'); ?> value="fixed_product"><?php esc_html_e('Fixed product discount', 'truelysell_core'); ?></option>

								</select>
							</p>
						</div>

						<div class="col-md-4">
							<p class="form-field coupon_amount_field ">
								<label for="coupon_amount"><?php esc_html_e('Coupon amount', 'truelysell_core'); ?></label>
								<input type="number" min=1 class="short wc_input_decimal" style="" required name="coupon_amount" id="coupon_amount" value="<?php if (isset($coupon_id)) echo esc_html(get_post_meta($coupon_id, 'coupon_amount', true)); ?>" placeholder="0">
							</p>
						</div>

						<div class="col-md-4">
							<p class="form-field expiry_date_field ">
								<label for="expiry_date"><?php esc_html_e('Coupon expiry date', 'truelysell_core'); ?></label>
								<?php
								if (isset($data->coupon_edit) && $data->coupon_edit) {
									$date_expires = get_post_meta($coupon_id, 'date_expires', true);
								} ?>
								<input type="text" class="input-date" style="" name="expiry_date" autocomplete="off" id="expiry_date" value="<?php if (isset($data->coupon_edit) && $date_expires) echo date('Y-m-d', $date_expires);  ?>" placeholder="YYYY-MM-DD" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])">
							</p>
						</div>
					</div>
			</div>

			<div class="add-listing-section row">
				<div class="add-listing-headline">
					<h3 class="mb-4"><i class="sl sl-icon-doc"></i> <?php esc_html_e('Usage restrictions', 'truelysell_core'); ?></h3>
				</div>
				<div id="usage_restriction_coupon_data" class="panel woocommerce_options_panel" style="display: block;">


					<div class="col-md-6">
						<p class="form-field minimum_amount_field ">
							<label for="minimum_amount"><?php esc_html_e('Minimum spend', 'truelysell_core'); ?></label>

							<input type="number" class="short wc_input_price" style="" name="minimum_amount" id="minimum_amount" value="<?php if (isset($coupon_id)) echo esc_html(get_post_meta($coupon_id, 'minimum_amount', true)); ?>" placeholder="<?php esc_html_e('No minimum', 'truelysell_core'); ?>">
						</p>
					</div>
					<div class="col-md-6">
						<p class="form-field maximum_amount_field ">
							<label for="maximum_amount"><?php esc_html_e('Maximum spend', 'truelysell_core'); ?></label>
							<input type="number" class="short wc_input_price" style="" name="maximum_amount" id="maximum_amount" value="<?php if (isset($coupon_id)) echo esc_html(get_post_meta($coupon_id, 'maximum_amount', true)); ?>" placeholder="<?php esc_html_e('No maximum', 'truelysell_core'); ?>">
						</p>
					</div>
					<div class="col-md-6">
						<p class="form-field">
							<?php
							if (isset($coupon_id)) {

								$listing_ids = get_post_meta($coupon_id, 'listing_ids', true);

								if (!empty($listing_ids)) {
									$listing_ids_array = explode(',', $listing_ids);
								} else {
									$listing_ids_array = array();
								}
							} else {

								$listing_ids_array = array();
							}
							?>
							<label><?php esc_html_e('For products:', 'truelysell_core'); ?><i class="tip" data-tip-content="<?php esc_html_e('Leave empty to apply for all your listings', 'truelysell_core'); ?>"></i></label>
							<select class="select2-single" multiple="" style="width: 50%;" name="listing_ids[]" data-placeholder="<?php esc_html_e('Search for a listing', 'truelysell_core'); ?>" data-action="" tabindex="-1" aria-hidden="true">

								<?php

								$args = array(
									'author'        	=>  $current_user->ID,
									'posts_per_page'  	=> -1,
									'post_type'		  	=> 'listing',
									'post_status'	  	=> 'publish'

								);
								$posts = get_posts($args);

								foreach ($posts as $post) : setup_postdata($post); ?>
									<option <?php if (in_array($post->ID, $listing_ids_array)) {
												echo "selected";
											} ?> value="<?php echo $post->ID; ?>"><?php the_title(); ?></option>
								<?php endforeach; ?>
							</select>

							</select>


						</p>
					</div>
					<div class="col-md-6">
						<p class="form-field individual_use_field ">
							<label for="individual_use"><?php esc_html_e('Individual use only', 'truelysell_core'); ?> <i class="tip" data-tip-content="<?php esc_html_e('Check this box if the coupon cannot be used in conjunction with other coupons.', 'truelysell_core'); ?>"></i></label>

						<div class="switch_box box_1"><input <?php if (isset($data->coupon_edit) && get_post_meta($coupon_id, 'individual_use', true) == "yes") {
																	echo 'checked="checked"';
																} ?>type="checkbox" class="input-checkbox switch_1" style="" name="individual_use" id="individual_use" value="yes"> </div>


						</p>
					</div>


					<div class="col-md-12">
						<p class="form-field customer_email_field ">
							<label for="customer_email"><?php esc_html_e('Allowed emails', 'truelysell_core'); ?></label>
							<input type="email" class="" style="" name="customer_email" id="customer_email" value="" placeholder="<?php esc_html_e('No restrictions', 'truelysell_core'); ?>" multiple="multiple">
						</p>
					</div>

				</div>
			</div>

			<div class="add-listing-section row">
				<div class="add-listing-headline">
					<h3 class="mb-4"><i class="sl sl-icon-doc"></i> <?php esc_html_e('Usage limits', 'truelysell_core'); ?></h3>
				</div>
				<div id="usage_limit_coupon_data" class="panel woocommerce_options_panel" style="display: block;">
					<div class="col-md-6">
						<p class="form-field usage_limit_field ">
							<label for="usage_limit"><?php esc_html_e('Usage limit per coupon', 'truelysell_core'); ?></label>
							<input type="number" class="short" style="" name="usage_limit" id="usage_limit" value="<?php if (isset($coupon_id)) echo esc_html(get_post_meta($coupon_id, 'usage_limit', true)); ?>" placeholder="<?php esc_html_e('Unlimited usage', 'truelysell_core'); ?>" step="1" min="0">
						</p>
					</div>

					<div class="col-md-6">
						<p class="form-field usage_limit_per_user_field ">
							<label for="usage_limit_per_user"><?php esc_html_e('Usage limit per user', 'truelysell_core'); ?></label>
							<input type="number" class="short" style="" name="usage_limit_per_user" id="usage_limit_per_user" value="<?php if (isset($coupon_id)) echo esc_html(get_post_meta($coupon_id, 'usage_limit_per_user', true)); ?>" placeholder="<?php esc_html_e('Unlimited usage', 'truelysell_core'); ?>" step="1" min="0">
						</p>
					</div>
				</div>
			</div>

			<div class="divider margin-top-40"></div>

			<p>

				<input type="hidden" name="truelysell_core_form" value="submit_coupon" />

				<button type="submit" value="Submit Coupon" name="submit_coupon" class="button margin-top-20"><i class="fa fa-arrow-circle-right"></i>
					<?php if (isset($data->coupon_edit) && $data->coupon_edit) {
						esc_html_e('Update Coupon', 'truelysell_core');
					} else {
						esc_html_e('Submit Coupon', 'truelysell_core');
					} ?>
				</button>

			</p>

	</form>