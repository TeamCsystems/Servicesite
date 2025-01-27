<?php
/**
 * The template for displaying listings
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package truelysell
 */
$top_layout = get_option('pp_listings_top_layout','map');
($top_layout == 'half') ? get_header('split') : get_header();

$template_loader = new Truelysell_Core_Template_Loader; 
 

$content_layout = get_option('pp_listings_layout','list');

switch ($top_layout) {
	case 'map_searchform':
		$template_loader->get_template_part( 'archive/map' ); 
		break;
	case 'map': ?>
		<!-- Map
		================================================== -->
		
		<div id="map-container" class="fullwidth-home-map">

	    <div id="map" data-map-zoom="<?php echo get_option('truelysell_map_zoom_global',9); ?>" >
		        <!-- map goes here -->
		    </div>

		    <!-- Scroll Enabling Button -->
			<a href="#" id="scrollEnabling" title="<?php esc_html_e('Enable or disable scrolling on map','truelysell_core') ?>"><?php esc_html_e('Enable Scrolling','truelysell_core'); ?></a>
			
		</div>
		<a href="#" id="show-map-button" class="show-map-button" data-enabled="<?php  esc_attr_e('Show Map ','truelysell'); ?>" data-disabled="<?php  esc_attr_e('Hide Map ','truelysell'); ?>"><?php esc_html_e('Show Map ','truelysell') ?></a>
		<?php
		break;
	
	
	case 'half':
	case 'disable':
		break;

	default:
		$template_loader->get_template_part( 'archive/titlebar' ); 
		break;
}

if($top_layout == 'half') {
	$template_loader->get_template_part( 'archive-listing-split' );
} else { ?>



<?php 
$layout = get_option('pp_listings_sidebar_layout','right-sidebar'); 
$mobile_layout = get_option('truelysell_listings_mobile_layout','right-sidebar');
?>

<!-- Content
================================================== -->
<div class="container <?php echo esc_attr($layout); if( $top_layout == 'map') { echo esc_attr(' margin-top-40'); } ?> ?>" >
	<div class="row sticky-wrapper">

		<?php if($layout=="left-sidebar" || ($layout== 'right-sidebar' && $mobile_layout=="left-sidebar")) : ?>
			<div class="col-lg-3 col-md-4 <?php if($layout== 'right-sidebar' && $mobile_layout=="left-sidebar") echo "col-lg-push-8"; ?> margin-top-75 sticky"> 
					<?php $template_loader->get_template_part( 'sidebar-truelysell' );?>
			</div>
			<!-- Sidebar / End -->
		<?php endif; ?>

			<?php switch ($layout) {
				case 'full-width':
					?><div class="col-md-12"><?php
					break;
				case 'left-sidebar':
					?><div class="col-lg-9 col-md-8 listings-column-content mobile-content-container <?php if($layout== 'right-sidebar' && $mobile_layout=="left-sidebar") { echo "col-lg-pull-4"; }  ?> "><?php
					break;
				case 'right-sidebar':
					?><div class="col-lg-9 col-md-8 righttest padding-right-30 listings-column-content mobile-content-container <?php if($layout== 'right-sidebar' && $mobile_layout=="left-sidebar") { echo "col-lg-pull-4"; }  ?> "><?php
					break;
				
				default:
					?><div class="col-lg-9 col-md-8 lefttest padding-right-30 listings-column-content <?php if($layout== 'right-sidebar' && $mobile_layout=="left-sidebar") { echo "col-lg-pull-4"; }  ?> "><?php
					break;
			} ?>
			<!-- Search -->
			
			<?php 
			if( $top_layout == 'search') { 
				 echo do_shortcode('[truelysell_search_form action='.get_post_type_archive_link( 'listing' ).' source="home" custom_class="gray-style margin-top-0 margin-bottom-40"]');
				} ?>
			
			<!-- Search Section / End -->
		
			<?php $top_buttons = get_option('truelysell_listings_top_buttons');
						
			if($top_buttons=='enable'){
				$top_buttons_conf = get_option('truelysell_listings_top_buttons_conf');
				if(is_array($top_buttons_conf) && !empty($top_buttons_conf)){
					$list_top_buttons = implode("|", $top_buttons_conf);
				}  else {
					$list_top_buttons = '';
				}
				?>
					<div class="row margin-bottom-15">
					<?php do_action( 'truelysell_before_archive', $content_layout, $list_top_buttons ); ?>
				</div>
				<?php 
			} ?>
				<?php 
				switch ($content_layout) {
					case 'list':
						$container_class = $content_layout.'-layout'; 
						break;
					
					case 'compact':
					case 'grid':
						$container_class = $content_layout.'-layout row'; 
						break;
					default:
						$container_class = 'list-layout'; 
						break;
				} 
				 ?>
				 
				<!-- Listings -->
				<div class="listings-container test<?php echo esc_attr($container_class) ?>">
					<?php if($content_layout == 'list'): ?>
						<div class="row">
					<?php endif;
					
					if ( have_posts() ) : 
						$data = '';
						if($content_layout == 'grid' || $content_layout == 'compact'){
							if ( $layout == 'full-width'){
								$data .= 'data-grid_columns="3"';
							} else {
								$data .= 'data-grid_columns="2"';
							}

						}
						$data .= ' data-region="'.get_query_var( 'region').'" ';
						$data .= ' data-category="'.get_query_var( 'listing_category').'"; ';
						$data .= ' data-feature="'.get_query_var( 'listing_feature').'"; ';
						$data .= ' data-service-category="'.get_query_var( 'service_category').'"; ';
						$data .= ' data-rental-category="'.get_query_var( 'rental_category').'"; ';
						$data .= ' data-event-category="'.get_query_var( 'event_category').'"; ';
						$orderby_value = isset( $_GET['truelysell_core_order'] ) ? (string) $_GET['truelysell_core_order']  : truelysell_fl_framework_getoptions('sort_by');
						?>
						<div <?php echo $data; ?> data-orderby="<?php echo $orderby_value;  ?>" data-style="<?php echo esc_attr($content_layout) ?>" id="truelysell-listings-container" >
							<div class="loader-ajax-container" style=""> <div class="loader-ajax"></div> </div>
							<?php /* Start the Loop */
							while ( have_posts() ) : the_post();

								switch ($content_layout) {
									case 'list':
									$template_loader->get_template_part( 'content-listing' ); 
										break;
									case 'grid':
										if ( $layout == 'full-width'){
											echo '<div class="col-lg-4 col-md-6 "> ';
										} else {
											echo '<div class="col-lg-6 col-md-12"> ';
										}
										
										$template_loader->get_template_part( 'content-listing-grid' ); 
										echo '</div>';
										break;
									
									case 'compact':
										if ( $layout == 'full-width'){
											echo '<div class="col-lg-4 col-md-6 "> ';
										} else {
											echo '<div class="col-lg-6 col-md-12"> ';
										}
										$template_loader->get_template_part( 'content-listing-compact' );  
										echo '</div>';
										break;

									default:
										$template_loader->get_template_part( 'content-listing' );
										break;
								}

							endwhile;

							?>
							<div class="clearfix"></div>
						</div>
						<?php $ajax_browsing = truelysell_fl_framework_getoptions('ajax_browsing'); ?>
						<div class="col-lg-12 col-md-12 pagination-container  margin-top-0 margin-bottom-60  <?php if( isset($ajax_browsing) && $ajax_browsing == 'on' ) { echo esc_attr('ajax-search'); } ?>">
							<nav class="pagination">
							<?php
								if($ajax_browsing == 'on') {
									global $wp_query;
     								$pages = $wp_query->max_num_pages;
									echo truelysell_core_ajax_pagination( $pages, 1 );
								} else 
								if(function_exists('wp_pagenavi')) { 
									wp_pagenavi(array(
										'next_text' => '<i class="fa fa-chevron-right"></i>',
										'prev_text' => '<i class="fa fa-chevron-left"></i>',
										'use_pagenavi_css' => false,
										));
								} else {
									the_posts_navigation();	
								}?>
							</nav>
						</div>
						<?php
						

					else :

						$template_loader->get_template_part( 'archive/no-found' ); 

					endif; ?>
					<?php if($content_layout == 'list'): ?>
						</div>
					<?php endif; ?>	
				</div>
		</div>

		<?php if($layout=="right-sidebar" && $mobile_layout!="left-sidebar") : ?>
			<div class="col-lg-3 col-md-4  margin-top-75 sticky">
					<?php $template_loader->get_template_part( 'sidebar-truelysell' );?>
			</div>
			<!-- Sidebar / End -->
		<?php endif; ?>

	
	</div>
</div>

<?php get_footer(); ?>
<?php } //eof split ?>
