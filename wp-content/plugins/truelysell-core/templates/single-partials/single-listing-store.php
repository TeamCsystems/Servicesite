<?php
$set_products = get_post_meta($post->ID, '_store_products');
$vendor_id = get_post_field('post_author', $post->ID);
$vendor            = dokan()->vendor->get($vendor_id);

$store_name        = $vendor->get_shop_name();
$store_url         = $vendor->get_shop_url();
?>
<div id="listing-store" class="listing-section">
    <div class="truelysell-store-browse-more">
        <h3 class="listing-desc-headline margin-top-60 margin-bottom-30"><?php esc_html_e('Store', 'truelysell_core'); ?>

        </h3>
        <a class="button" href="<?php echo esc_url($store_url); ?>" ><?php esc_html_e('Browse All Products', 'truelysell_core'); ?></a>
    </div>

    <?php


    $orderby =   'title';
    $order =   'ASC';
    $include_posts = $set_products ? $set_products : array();


    $output = '';
    $randID = rand(1, 99); // Get unique ID for carousel

    $meta_query = array();


    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'author'    => $vendor_id,
        'orderby' => $orderby,
        'order' => $order,

    );

    if (!empty($include_posts)) {
        $inc = is_array($include_posts) ? $include_posts : array_filter(array_map('trim', explode(',', $include_posts)));
        $args['include'] = $inc;
    }



    $i = 0;
    $args['exclude_listing_booking'] = 'true';
    $args['tax_query'][] = array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => array('truelysell-booking'), // Don't display products in the clothing category on the shop page.
        'operator' => 'NOT IN'
    );
    $args['tax_query'][] = array(
        'taxonomy' => 'product_type',
        'field' => 'slug',
        'terms' => array('listing_package'), // Don't display products in the clothing category on the shop page.
        'operator' => 'NOT IN'
    );
    $products = wc_get_products($args);


    ob_start();
    ?>
    <!-- Carousel / Start -->
    <div class="simple-slick-carousel  truelysell-products-slider dots-nav">
        <?php
        if ($products) {
            $count = 0;
            foreach ($products as $product) {
                $count++;
                $thumbnail_id = $product->get_image_id();

        ?>
                <div class="fw-carousel-item">

                    <div <?php post_class('', $product->get_id()); ?>>
                        <div class="mediaholder">
                            <a href="<?php echo get_permalink($product->get_id()); ?>">
                                <?php
                                    $props            = wc_get_product_attachment_props(get_post_thumbnail_id(), $product->get_id());
                                    $image            = get_the_post_thumbnail($product->get_id(), apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
                                        'title'     => $props['title'],
                                        'alt'    => $props['alt'],
                                    ));
                                    $size = 'truelysell_core-avatar';
                                    $image_size = apply_filters('single_product_archive_thumbnail_size', $size);
                                    echo $product->get_image($image_size);
                                
                                ?>
                            </a>
                            <?php $link     = $product->add_to_cart_url();
                            $label     = apply_filters('add_to_cart_text', esc_html__('Add to cart', 'truelysell'));
                            ?>
                            <a href="<?php echo esc_url($link); ?>" class="button"><i class="fa fa-shopping-cart"></i> <?php echo esc_html($label); ?></a>
                        </div>
                        <section>
                            <span class="product-category">
                                <?php
                                $product_cats = wp_get_post_terms($product->get_id(), 'product_cat');
                                if ($product_cats && !is_wp_error($product_cats)) {
                                    $single_cat = array_shift($product_cats);
                                    echo esc_html($single_cat->name);
                                } ?>
                            </span>

                            <h5><a href="<?php echo get_permalink($product->get_id()); ?>"><?php echo $product->get_title(); ?></a></h5>

                            <?php echo $product->get_price_html(); ?>
                        </section>
                    </div>



                </div>
        <?php
            }
        }



        ?>
    </div>
    <?php wp_reset_postdata();
    wp_reset_query();

    echo ob_get_clean();
    ?>


</div>