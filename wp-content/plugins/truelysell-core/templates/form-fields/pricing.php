<!-- Section -->
<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
$field = $data->field;
$key = $data->key;

$currency_abbr = truelysell_fl_framework_getoptions('currency');
$currency = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);


if (isset($field['value']) && is_array($field['value'])) :
	$i = 0;


?>

	<div class="row">
		<div class="col-md-12">

			<table id="pricing-list-container">
				<?php foreach ($field['value'] as $m_key => $menu) { ?>
					<?php if (isset($menu['menu_title'])) { ?>
						<tr class="pricing-list-item pricing-submenu" data-number="<?php echo esc_attr($i); ?>">
							<td>
								<div class="fm-move"><i class="fas fa-arrows"></i></div>
								<div class="fm-input"><input type="text" name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($i); ?>][menu_title]" value="<?php echo $menu['menu_title']; ?>" placeholder="<?php esc_html_e('Category Title', 'truelysell_core'); ?>"></div>
								<div class="fm-close"><a class="delete" href="#"><i class="fa fa-remove"></i></a></div>
							</td>
						</tr>
						<?php }
					$z = 0;
					if (isset($menu['menu_elements'])) {
						foreach ($menu['menu_elements'] as $el_key => $menu_el) { ?>
							<tr class="pricing-list-item <?php if ($z === 0) {
																echo 'pattern';
															} ?>" data-iterator="<?php echo esc_attr($z); ?>">
								<td>
									<div class="fm-move"><i class="fas fa-arrows"></i></div>
									<div class="fm-input pricing-cover">
										<div class="pricing-cover-wrapper" data-tippy-placement="bottom" title="<?php esc_html('Change Cover', 'truelysell_core'); ?>">
											<?php if (isset($menu_el['cover']) && !empty($menu_el['cover'])) {
												$thumb = wp_get_attachment_image_src($menu_el['cover'], 'small'); ?>
												<img class="cover-pic" src="<?php echo $thumb[0]; ?>" alt="" />
												<a class="remove-cover" href="#"><?php echo esc_html('Remove Cover', 'truelysell_core'); ?></a>
												<input type="hidden" class="menu-cover-id" name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($i); ?>][menu_elements][<?php echo esc_attr($z); ?>][cover]" value="<?php echo $menu_el['cover']; ?>" />
											<?php } else { ?>
												<img class="cover-pic" src="<?php echo get_template_directory_uri(); ?>/assets/images/pricing-cover-placeholder.jpg" alt="" />

											<?php } ?>
											<div class="upload-button"></div>
											<input class="file-upload" type="file" accept="image/*" name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($i); ?>][menu_elements][<?php echo esc_attr($z); ?>][cover]" />
										</div>

									</div>
									<div class="fm-input pricing-name">
										<input type="text" name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($i); ?>][menu_elements][<?php echo esc_attr($z); ?>][name]" value="<?php echo $menu_el['name']; ?>" placeholder="<?php esc_html_e('Title', 'truelysell_core'); ?>" />
									</div>
									<div class="fm-input pricing-ingredients">
										<input type="text" name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($i); ?>][menu_elements][<?php echo esc_attr($z); ?>][description]" value="<?php echo $menu_el['description']; ?>" placeholder="<?php esc_html_e('Description', 'truelysell_core'); ?>" />
									</div>
									<div class="fm-input pricing-price">
										<input type="number" step="0.01" name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($i); ?>][menu_elements][<?php echo esc_attr($z); ?>][price]" value="<?php echo $menu_el['price']; ?>" placeholder="<?php esc_html_e('Price (optional)', 'truelysell_core'); ?>" data-unit="<?php echo esc_attr($currency) ?>" />
									</div>
 									 
									 
									 
									 
									 
									 
									 
									 
									 
									 
									 
									 
									<div class="fm-close"><a class="delete" href="#"><i class="fa fa-remove"></i></a></div>
								</td>
							</tr>
				<?php
							$z++;
						}
					} // menu
					$i++;
				} ?>
			</table>
			<a href="#" class="button add-pricing-list-item"><?php esc_html_e('Add Item', 'truelysell_core'); ?></a>
			 
		</div>
	</div>

<?php else : ?>
	<div class="row">
		<div class="col-md-12">
			<table id="pricing-list-container">

				<tr class="pricing-list-item pattern" data-iterator="0">
					<td>
						<div class="fm-move"><i class="fas fa-arrows-alt"></i></div>
						<div class="fm-input pricing-cover">
							<div class="pricing-cover-wrapper" data-tippy-placement="bottom" title="<?php esc_html('Change Cover', 'truelysell_core'); ?>">
								<img class="cover-pic" src="<?php echo get_template_directory_uri(); ?>/assets/images/pricing-cover-placeholder.jpg" alt="" />
								<div class="upload-button"></div>
								<input class="file-upload" type="file" accept="image/*" name="_menu[0][menu_elements][0][cover]" />
							</div>

						</div>
						<div class="fm-input pricing-name"><input type="text" placeholder="<?php esc_html_e('Title', 'truelysell_core'); ?>" name="_menu[0][menu_elements][0][name]" /></div>
						<div class="fm-input pricing-ingredients"><input type="text" placeholder="<?php esc_html_e('Description', 'truelysell_core'); ?>" name="_menu[0][menu_elements][0][description]" /></div>
						<div class="fm-input pricing-price"><input type="number" step="0.01" name="_menu[0][menu_elements][0][price]" placeholder="<?php esc_html_e('Price (optional)', 'truelysell_core'); ?>" data-unit="<?php echo esc_attr($currency) ?>" /></div>
						 
 						 
						 
						 
						 
						 
						 
						 
						 
						 
						 
						 
						 
						 
						<div class="fm-close"><a class="delete" href="#"><i class="fa fa-remove"></i></a></div>
					</td>
				</tr>
			</table>
			<a href="#" class="button add-pricing-list-item"><?php esc_html_e('Add Item', 'truelysell_core'); ?></a>
			 
		</div>
	</div>
<?php endif; ?>