<?php

$_menu_status = get_post_meta(get_the_ID(), '_menu_status', true);
if (!$_menu_status) {
	return;
}
$_bookable_show_menu =  get_post_meta(get_the_ID(), '_hide_pricing_if_bookable', true);
if (!empty($_bookable_show_menu)) {
	return;
}
$_menu = get_post_meta(get_the_ID(), '_menu', 1);

$counter = 0;
if (!is_array($_menu)) {
	return;
}
foreach ($_menu as $menu) {
	$counter++;
	if (isset($menu['menu_elements']) && !empty($menu['menu_elements'])) :
		foreach ($menu['menu_elements'] as $item) {
			$counter++;
		}
	endif;
}

if (isset($_menu[0]['menu_elements'][0]['name']) && !empty($_menu[0]['menu_elements'][0]['name'])) { ?>

	<!-- Food Menu -->
		<?php if ($counter > 5) : ?><div class="show-more"><?php endif; ?>
			<div class="pricing-list-container">

				<?php foreach ($_menu as $menu) {
					$has_menu_title = false;
					if (isset($menu['menu_title']) && !empty($menu['menu_title'])) :
						$has_menu_title = true;
					endif;
					if (isset($menu['menu_elements']) && !empty($menu['menu_elements'])) :
				?>
						<ul class="<?php if (!$has_menu_title) { ?>pricing-menu-no-title<?php } ?>">
							<?php foreach ($menu['menu_elements'] as $item) { ?>
								<?php if(!empty($item['name']) && !empty($item['price']) ){  ?>
								<li>
									<?php if (isset($item['name']) && !empty($item['cover'])) {
										$image = wp_get_attachment_image_src($item['cover'], 'truelysell-gallery');
										$thumb = wp_get_attachment_image_src($item['cover'], 'thumbnail');  ?>
										<a <?php if (isset($item['name']) && !empty($item['name'])) { ?>title="<?php echo esc_html($item['name']) ?>" <?php } ?> href="<?php echo $image[0]; ?>" class="mfp-image">
											<img src="<?php echo $thumb[0]; ?>" />
										</a>
									<?php } ?>
									<div class="pricing-menu-details">
										<?php if (isset($item['name']) && !empty($item['name'])) { ?><h5><?php echo esc_html($item['name']) ?></h5><?php } ?>
										<?php if (isset($item['description']) && !empty($item['description'])) { ?><p><?php echo ($item['description']) ?></p><?php } ?>
									</div>
									<?php if (isset($item['price']) && !empty($item['price'])) { ?><span>
											<?php
											$currency_abbr = truelysell_fl_framework_getoptions('currency');
											$currency_postion = truelysell_fl_framework_getoptions('currency_postion');
											$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
											?>
											<?php
											if (empty($item['price']) || $item['price'] == 0) {
												esc_html_e('Free', 'truelysell_core');
											} else {
												if ($currency_postion == 'before') {
													echo $currency_symbol . ' ';
												}
												$price = $item['price'];
												if (is_numeric($price)) {
													$decimals = truelysell_fl_framework_getoptions('number_decimals');
													echo number_format_i18n($price, $decimals);
												} else {
													echo esc_html($price);
												}

												if ($currency_postion == 'after') {
													echo ' ' . $currency_symbol;
												}
											}
											?>
										</span><?php } else if (!isset($item['price']) || $item['price'] == '0') { ?>
										<span><?php esc_html_e('Free', 'truelysell_core'); ?></span>
									<?php }  ?>

								</li>


							<?php } } ?>	
						</ul>

				<?php endif;
				}
				?>
				<!-- Food List -->

			</div>
			<?php if ($counter > 5) : ?>
			</div>
			<a href="#" class="show-more-button" data-more-title="<?php esc_html_e('Show More', 'truelysell_core') ?>" data-less-title="<?php esc_html_e('Show Less', 'truelysell_core') ?>"><i class="fa fa-angle-down"></i></a><?php endif; ?>

<?php } ?>