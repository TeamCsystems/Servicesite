<?php
/**
* Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woocommerce-form-coupon-toggle">
	<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'truelysell' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'truelysell' ) . '</a>' ), 'notice' ); ?>
</div>

<form class="checkout_coupon woocommerce-form-coupon row" method="post" style="display:none">
<div class="row">
	<p class="col-md-12 pl-0"><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'truelysell' ); ?></p>
	<div class="col-md-10 pl-0">
	<div class="form-group mb-0">
			<div class="input-group mb-0">
			<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'truelysell' ); ?>" id="coupon_code"/>
			
		</div>
	</div>
	</div>
	<div class="col-md-2">
	<div class="input-group-append">
				<button type="submit" class="button btn btn-primary" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'truelysell' ); ?>"><?php esc_html_e( 'Apply coupon', 'truelysell' ); ?></button>
			</div>
	</div>
</div>

	<div class="clear"></div>
</form>
