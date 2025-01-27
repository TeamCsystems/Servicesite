<?php
/**
 * Single Product Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$count   = $product->get_rating_count();
$average = $product->get_average_rating();
$avclass = truelysell_get_rating($average);

if ( $count > 0 ) : ?>
    
	<div class="woocommerce-product-rating reviews-counter" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
            <div class="woo-star-rating">
            <div class="star-rating rating <?php echo esc_attr($avclass); ?>" title="<?php printf( esc_html__( 'Rated %s out of 5', 'truelysell' ), $average ); ?>">
                <div class="star-rating"></div>
                <div class="star-bg"></div>
            </div>
            </div>
        </a>
	</div>

<?php endif; ?>