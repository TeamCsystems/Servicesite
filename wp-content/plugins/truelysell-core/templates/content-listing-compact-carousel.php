    <?php
    if (isset($data)) :
        $style        = (isset($data->style)) ? $data->style : '';
        $grid_columns        = (isset($data->grid_columns)) ? $data->grid_columns : '';
    endif;

    $template_loader = new Truelysell_Core_Template_Loader;
    $listing_type = get_post_meta($post->ID, '_listing_type', true);
    $is_featured = truelysell_core_is_featured($post->ID);  ?>
    
    <?php if (isset($style) && $style == 'compact') {
        if ($grid_columns == 3) { ?>
            <div class="col-lg-4 col-md-6">
            <?php } else { ?>
                <div class="col-lg-6 col-md-12">
            <?php }
    } ?>
        <div class="service-widget 1">
            <div class="service-img">
                <?php $template_loader->get_template_part('content-listing-image-small');  
                $rating_value = get_post_meta($post->ID, 'truelysell-avg-rating', true);
                ?>
        
                <div class="item-info">
               
                    
                        <div class="cate-list">
                        <?php 
                            $terms = get_the_terms($post->ID, 'listing_category' ); 
                            if ( $terms && ! is_wp_error( $terms ) ) :  
                                $main_term = array_pop($terms); ?>
                                <span class="cat_name bg-yellow"><?php echo $main_term->name; ?></span>
                            <?php endif; ?>
                        
                        </div>

                        <div class="fav-item">
                                  <span class="serv-rating"><i class="fa-solid fa-star"></i><?php echo esc_attr(round($rating_value, 1));	?></span>
                        </div>
                </div>
            </div>
            <div class="service-content">
                <h3 class="title">
                  
                <a href="<?php the_permalink();?>">
                <?php echo get_the_title(); ?></a></h3>

                <?php if(get_the_listing_address()) { ?>
                            <p><i class="feather-map-pin me-2"></i><?php the_listing_address(); ?></p>
                            <?php } ?>
                <?php  $listing_type = get_post_meta( $post->ID,'_listing_type',true ); 
                $is_instant = truelysell_core_is_instant_booking($post->ID); 
                
                ?>
        
        <div class="serv-info">
                        <h6><?php  $currency_abbr = truelysell_fl_framework_getoptions('currency');
                                                $currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
                                        $normal_price = $currency_symbol . (float) get_post_meta(get_the_ID(), '_normal_price', true); 
                                        echo esc_html($normal_price);
                                        ?></h6>
                        <a href="<?php the_permalink();?>" class="btn btn-book"><?php echo esc_html_e('Book Now','truelysell_elementor');?></a>
        </div>
            </div>
        </div>
            <?php if (isset($style) && $style == 'compact') { ?>
                </div>
            <?php } ?>


    