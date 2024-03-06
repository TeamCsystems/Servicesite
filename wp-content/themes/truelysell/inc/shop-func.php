<?php
/* Shop Settings */
 

function truelysell_shop_controls_start()
{
    echo '<div class="shop-global">';
}

/**
 * Closing tags for shop filter.
 *
 * @since 1.0.0
 */
function truelysell_shop_controls_end()
{
    echo '</div>';
}

add_action('woocommerce_before_shop_loop', 'truelysell_shop_controls_start', 15);
add_action('woocommerce_before_shop_loop', 'truelysell_shop_controls_end', 40);
add_filter('woocommerce_account_menu_items', 'truelysell_remove_my_account_tabs', 999);

  function truelysell_remove_my_account_tabs($items) {
    unset($items['dashboard']);
    unset($items['downloads']);
    unset($items['edit-address']);
    unset($items['payment-methods']);
    unset($items['edit-account']);
 
    return $items;
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

add_filter( 'woocommerce_sale_flash', 'truelysell_add_percentage_to_sale_badge', 20, 3 );
function truelysell_add_percentage_to_sale_badge( $html, $post, $product ) {

  if( $product->is_type('variable')){
      $percentages = array();

      // Get all variation prices
      $prices = $product->get_variation_prices();

      // Loop through variation prices
      foreach( $prices['price'] as $key => $price ){
          // Only on sale variations
          if( $prices['regular_price'][$key] !== $price ){
              // Calculate and set in the array the percentage for each variation on sale
              $percentages[] = round( 100 - ( floatval($prices['sale_price'][$key]) / floatval($prices['regular_price'][$key]) * 100 ) );
          }
      }
      // We keep the highest value
      $percentage = max($percentages) . '%';

  } elseif( $product->is_type('grouped') ){
      $percentages = array();

      // Get all variation prices
      $children_ids = $product->get_children();

      // Loop through variation prices
      foreach( $children_ids as $child_id ){
          $child_product = wc_get_product($child_id);

          $regular_price = (float) $child_product->get_regular_price();
          $sale_price    = (float) $child_product->get_sale_price();

          if ( $sale_price != 0 || ! empty($sale_price) ) {
              // Calculate and set in the array the percentage for each child on sale
              $percentages[] = round(100 - ($sale_price / $regular_price * 100));
          }
      }
      // We keep the highest value
      $percentage = max($percentages) . '%';

  } else {
      $regular_price = (float) $product->get_regular_price();
      $sale_price    = (float) $product->get_sale_price();

      if ( $sale_price != 0 || ! empty($sale_price) ) {
          $percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
      } else {
          return $html;
      }
  }
  return '<span class="onsale">' . esc_html__( '-', 'truelysell' ) . ' ' . $percentage . '</span>';
}

add_filter( 'woocommerce_format_sale_price', 'truelysell_invert_formatted_sale_price', 10, 3 );
function truelysell_invert_formatted_sale_price( $price, $regular_price, $sale_price ) {
    return '<ins>' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</ins> <del>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</del>';
}
