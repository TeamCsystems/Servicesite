<?php
/**
 * Template Name: Categories Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WPVoyager
 */

get_header(); 
?>
<div class="content">
	<div class="container">
		<div class="catsec  1 clearfix">
			<div class="row">
				<?php
 				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $post_per_page = 9;
				$args = array(
							'post_type' => 'listing',
                    		'paged' => $paged,
							'number'        => $post_per_page,
							'taxonomy' => 'listing_category',
							'orderby' => 'name',
							'order'   => 'ASC',
							'parent' => 0
						);

				$cats = get_categories($args);

				foreach($cats as $cat) {
					$cover_id = get_term_meta($cat->term_id,'_cover',true);
 				?>
				<div class="col-md-6 col-lg-4 d-flex">
					 
						<div class="category-card flex-fill">
							<?php 
							if($cover_id) {
								$cover = wp_get_attachment_image_src($cover_id,'truelysell-blog-post');  ?>
								<div class="category-img">
								<a href="<?php echo get_category_link( $cat->term_id ) ?>"><img src="<?php echo esc_html($cover[0]);  ?>"></a>
								</div>
							<?php } ?>
									<div class="category-info">

									<div class="category-name">
									<span class="category-icon">
									<?php 
					$cover_idicon = get_term_meta($cat->term_id,'_covericon',true);
					if($cover_idicon) {
						$cover_idicon = wp_get_attachment_image_src($cover_idicon,'truelysell-blog-post');  ?>
						<img src="<?php echo esc_html($cover_idicon[0],'truelysell'); ?>">
					<?php }   ?>
									</span>	 
									<h6><a href="<?php echo get_category_link( $cat->term_id ) ?>"><?php echo esc_html($cat->name); ?></a></h6>
								</div>
								<p><?php 
										$count = truelysell_get_term_post_count('listing_category',$cat->term_id); 
										echo esc_html($count);
										if($count==1) { ?>
										 <?php esc_html_e('Service','truelysell'); ?>
										<?php }else if($count>1) { ?> 
										 <?php esc_html_e('Services','truelysell'); ?> 
										<?php }  else { ?>
										<?php esc_html_e('0 Service','truelysell'); ?>
									 <?php } ?></p> 
 									</div>
									 
						</div>
					 
				</div>
				<?php
				}
				?>
				
			</div>
			
		</div>
	</div>
</div>
<?php get_footer(); ?>