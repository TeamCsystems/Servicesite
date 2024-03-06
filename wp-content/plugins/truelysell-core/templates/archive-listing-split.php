<?php
$template_loader = new Truelysell_Core_Template_Loader;
get_header();
?>
<!-- Content
================================================== -->

		
<?php 

$term = get_queried_object();
$term_id = $term->term_id;

$count_main_cat_count = truelysell_get_term_post_count('listing_category',$term_id); 

$taxonomy_name = $term->taxonomy;
$termchildren = get_term_children( $term_id, $taxonomy_name );
if (!empty($termchildren)) { 
?>


<div class="fs-container content pb-0">
	<div class="container  ">
		<div class="row">
			
<div class="col-md-12 mb-3 ">
	<h3><?php esc_html_e('Sub Categories','truelysell'); ?></h3>
</div>

<?php
 foreach ( $termchildren as $child ) {
	$term_sub = get_term_by( 'id', $child, $taxonomy_name );
	$cover_id = get_term_meta($term_sub->term_id,'_cover',true);
	$count_main_subcat_count = truelysell_get_term_post_count('listing_category',$term_sub->term_id); 
?>


<div class="col-md-6 col-lg-4 ">
					 <div class="category-card flex-fill">
					 <div class="category-img category-img-parent">
						 <?php 
						 if($cover_id) {
							 $cover = wp_get_attachment_image_src($cover_id,'truelysell-blog-post');  ?>
							 
							 <a href="<?php echo get_category_link( $term_sub->term_id ) ?>"><img src="<?php echo esc_html($cover[0]);  ?>"></a>
							
						 <?php } else  { ?>
							<a href="<?php echo get_category_link( $term_sub->term_id ) ?>"><img src="<?php echo get_template_directory_uri();?>/assets/images/category-placeholder.jpg"></a>

						 <?php } ?>
 
						 </div>
 
								 <div class="category-info">
 								 <div class="category-name">
								 <span class="category-icon">
								 <?php 
				 $cover_idicon = get_term_meta($term_sub->term_id,'_covericon',true);
				 if($cover_idicon) {
					 $cover_idicon = wp_get_attachment_image_src($cover_idicon,'truelysell-blog-post');  ?>
					 <img src="<?php echo esc_html($cover_idicon[0],'truelysell'); ?>">
				 <?php }   ?>
								 </span>	 
								 <h6><a href="<?php echo get_category_link( $term_sub->term_id ) ?>"><?php echo esc_html($term_sub->name); ?></a></h6>
							 </div>
							 <p><?php 
									 $count = truelysell_get_term_post_count('listing_category',$term_sub->term_id); 
									 echo esc_html($count);
									 if($count==1) { ?>
									  <?php esc_html_e('Service','truelysell'); ?>
									 <?php }else if($count>1) { ?> 
									  <?php esc_html_e('Services','truelysell'); ?> 
									 <?php }  else { ?>
									 <?php esc_html_e('Service','truelysell'); ?>
								  <?php } ?></p> 
								  </div>
								  
					 </div>
				  
			 </div>

<?php }  ?>
		</div>
	</div>
</div>
<?php 
if (have_posts()) {  ?>


	<div class="fs-container content">
	
		<div class="container  ">
			<div class="">
			<div class="col-md-12 ">
	<h3 class="mb-3"><?php esc_html_e('Services','truelysell'); ?></h3>
</div>
				<?php $content_layout = get_option('pp_listings_layout', 'list'); ?>
				<section class="listings-container margin-top-45 test">
					<!-- Sorting / Layout Switcher -->
					<div class="row fs-switcher">
						<?php if (get_option('truelysell_show_archive_title') == 'enable') { ?>
							<div class="col-md-12">
								<?php 
								$title = get_option('truelysell_listings_archive_title');
								if (!empty($title) && is_post_type_archive('listing')) { ?>
									<h1 class="page-title"><?php echo esc_html($title); ?></h1>
								<?php } else {
									the_archive_title('<h1 class="page-title">', '</h1>');
								} ?>
							</div>
						<?php } ?>
	
						<?php $top_buttons = get_option('truelysell_listings_top_buttons');
	
						if ($top_buttons == 'enable') {
							$top_buttons_conf = get_option('truelysell_listings_top_buttons_conf');
							if (is_array($top_buttons_conf) && !empty($top_buttons_conf)) {
	
								if (($key = array_search('radius', $top_buttons_conf)) !== false) {
									unset($top_buttons_conf[$key]);
								}
								if (($key = array_search('filters', $top_buttons_conf)) !== false) {
									unset($top_buttons_conf[$key]);
								}
								$list_top_buttons = implode("|", $top_buttons_conf);
							} else {
								$list_top_buttons = '';
							}
						?>
	
							<?php do_action('truelysell_before_archive', $content_layout, $list_top_buttons); ?>
	
						<?php
						} ?>
	
					</div>
	
					<!-- Listings -->
					<div class="fs-listings">
	
						<?php
	
						switch ($content_layout) {
							case 'list':
							case 'grid':
								$container_class = $content_layout . '-layout';
								break;
	
							case 'compact':
								$container_class = $content_layout;
								break;
	
							default:
								$container_class = 'list-layout';
								break;
						}
	
						$data = '';
						if ($content_layout == 'grid') {
	
						}
						$data .= ' data-region="' . get_query_var('region') . '" ';
						$data .= ' data-category="' . get_query_var('listing_category') . '" ';
						$data .= ' data-feature="' . get_query_var('listing_feature') . '" ';
						$data .= ' data-service-category="' . get_query_var('service_category') . '" ';
						$data .= ' data-rental-category="' . get_query_var('rental_category') . '" ';
						$data .= ' data-event-category="' . get_query_var('event_category') . '" ';
						$orderby_value = isset($_GET['truelysell_core_order']) ? (string) $_GET['truelysell_core_order']  : truelysell_fl_framework_getoptions('sort_by');
						?>
						<!-- Listings -->
						<div data-grid_columns="2" <?php echo $data; ?> data-orderby="<?php echo $orderby_value;  ?>" data-style="<?php echo esc_attr($content_layout) ?>" class="listings-container <?php echo esc_attr($container_class) ?>" id="truelysell-listings-container">
							<div class="loader-ajax-container" style="">
								<div class="loader-ajax"></div>
							</div>
							<?php
							if (have_posts()) :
	
	
								/* Start the Loop */
								while (have_posts()) : the_post();
	
									switch ($content_layout) {
										case 'list':
											$template_loader->get_template_part('content-listing');
											break;
	
										case 'grid':
											echo '<div class="col-lg-6 col-md-12"> ';
											$template_loader->get_template_part('content-listing-grid');
											echo '</div>';
											break;
	
										case 'compact':
											echo '<div class="col-lg-6 col-md-12"> ';
											$template_loader->get_template_part('content-listing-compact');
											echo '</div>';
											break;
	
										default:
											$template_loader->get_template_part('content-listing');
											break;
									}
	
								endwhile;
	
	
							else :
	
								$template_loader->get_template_part('archive/no-found');
	
							endif; ?>
	
							<div class="clearfix"></div>
						</div>
						<?php $ajax_browsing = truelysell_fl_framework_getoptions('ajax_browsing'); ?>
								<?php
								if ($ajax_browsing == 'on') {
									global $wp_query;
									$pages = $wp_query->max_num_pages;
									?>
								<?php if($pages> 1) {  ?>
									<div class="pagination-container margin-top-45 margin-bottom-60 row <?php if (isset($ajax_browsing) && $ajax_browsing == 'on') {
							 echo esc_attr('ajax-search');
						 } ?>">
							<nav class="pagination col-md-12">
								<?php echo truelysell_core_ajax_pagination($pages, 1); ?>
								</nav>
									</div>
								<?php } ?>
							
									<?php
								} else  { ?>
								<?php
								if (function_exists('wp_pagenavi')) {
									wp_pagenavi(array(
										'next_text' => '<i class="fa fa-chevron-right"></i>',
										'prev_text' => '<i class="fa fa-chevron-left"></i>',
										'use_pagenavi_css' => false,
									));
								} else {
									the_posts_navigation();
								}  } ?>
							
						
						
					</div>
				</section>
	
			</div>
		</div>
		
	</div>
	<?php 
	}  
	?>

	 
<?php 
} elseif (have_posts() && (!!empty($termchildren))) {  ?>


<div class="fs-container content">

	<div class="container  ">
		<div class="fs-content">

			

			<?php $content_layout = get_option('pp_listings_layout', 'list'); ?>
			<section class="listings-container margin-top-45 test">
				<!-- Sorting / Layout Switcher -->
				<div class="row fs-switcher">
					<?php if (get_option('truelysell_show_archive_title') == 'enable') { ?>
						<div class="col-md-12">
							<?php 
							$title = get_option('truelysell_listings_archive_title');
							if (!empty($title) && is_post_type_archive('listing')) { ?>
								<h1 class="page-title"><?php echo esc_html($title); ?></h1>
							<?php } else {
								the_archive_title('<h1 class="page-title">', '</h1>');
							} ?>
						</div>
					<?php } ?>

					<?php $top_buttons = get_option('truelysell_listings_top_buttons');

					if ($top_buttons == 'enable') {
						$top_buttons_conf = get_option('truelysell_listings_top_buttons_conf');
						if (is_array($top_buttons_conf) && !empty($top_buttons_conf)) {

							if (($key = array_search('radius', $top_buttons_conf)) !== false) {
								unset($top_buttons_conf[$key]);
							}
							if (($key = array_search('filters', $top_buttons_conf)) !== false) {
								unset($top_buttons_conf[$key]);
							}
							$list_top_buttons = implode("|", $top_buttons_conf);
						} else {
							$list_top_buttons = '';
						}
					?>

						<?php do_action('truelysell_before_archive', $content_layout, $list_top_buttons); ?>

					<?php
					} ?>

				</div>

				<!-- Listings -->
				<div class="fs-listings">

					<?php

					switch ($content_layout) {
						case 'list':
						case 'grid':
							$container_class = $content_layout . '-layout';
							break;

						case 'compact':
							$container_class = $content_layout;
							break;

						default:
							$container_class = 'list-layout';
							break;
					}

					$data = '';
					if ($content_layout == 'grid') {

					}
					$data .= ' data-region="' . get_query_var('region') . '" ';
					$data .= ' data-category="' . get_query_var('listing_category') . '" ';
					$data .= ' data-feature="' . get_query_var('listing_feature') . '" ';
					$data .= ' data-service-category="' . get_query_var('service_category') . '" ';
					$data .= ' data-rental-category="' . get_query_var('rental_category') . '" ';
					$data .= ' data-event-category="' . get_query_var('event_category') . '" ';
					$orderby_value = isset($_GET['truelysell_core_order']) ? (string) $_GET['truelysell_core_order']  : truelysell_fl_framework_getoptions('sort_by');
					?>
					<!-- Listings -->
					<div data-grid_columns="2" <?php echo $data; ?> data-orderby="<?php echo $orderby_value;  ?>" data-style="<?php echo esc_attr($content_layout) ?>" class="listings-container <?php echo esc_attr($container_class) ?>" id="truelysell-listings-container">
						<div class="loader-ajax-container" style="">
							<div class="loader-ajax"></div>
						</div>
						<?php
						if (have_posts()) :


							/* Start the Loop */
							while (have_posts()) : the_post();

								switch ($content_layout) {
									case 'list':
										$template_loader->get_template_part('content-listing');
										break;

									case 'grid':
										echo '<div class="col-lg-6 col-md-12"> ';
										$template_loader->get_template_part('content-listing-grid');
										echo '</div>';
										break;

									case 'compact':
										echo '<div class="col-lg-6 col-md-12"> ';
										$template_loader->get_template_part('content-listing-compact');
										echo '</div>';
										break;

									default:
										$template_loader->get_template_part('content-listing');
										break;
								}

							endwhile;


						else :

							$template_loader->get_template_part('archive/no-found');

						endif; ?>

						<div class="clearfix"></div>
					</div>
					<?php $ajax_browsing = truelysell_fl_framework_getoptions('ajax_browsing'); ?>
							<?php
							if ($ajax_browsing == 'on') {
								global $wp_query;
								$pages = $wp_query->max_num_pages;
								?>
							<?php if($pages> 1) {  ?>
								<div class="pagination-container margin-top-45 margin-bottom-60 row <?php if (isset($ajax_browsing) && $ajax_browsing == 'on') {
						 echo esc_attr('ajax-search');
					 } ?>">
						<nav class="pagination col-md-12">
							<?php echo truelysell_core_ajax_pagination($pages, 1); ?>
							</nav>
								</div>
							<?php } ?>
						
								<?php
							} else  { ?>
							<?php
							if (function_exists('wp_pagenavi')) {
								wp_pagenavi(array(
									'next_text' => '<i class="fa fa-chevron-right"></i>',
									'prev_text' => '<i class="fa fa-chevron-left"></i>',
									'use_pagenavi_css' => false,
								));
							} else {
								the_posts_navigation();
							}  } ?>
						
					
					
				</div>
			</section>

		</div>
	</div>
	
</div>
<?php 
} else {
echo '<h6><'._e('Sorry, no posts matched your criteria.').'</h6>';
}
?>
<div class="clearfix"></div>

<?php get_footer(); ?>