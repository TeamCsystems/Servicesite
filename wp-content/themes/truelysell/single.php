<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Truelysell
 */

get_header();
$layout = get_post_meta($post->ID, 'truelysell_page_layout', true); if(empty($layout)) { $layout = 'right-sidebar'; }
$class  = ($layout !="full-width") ? "col-md-9 col-sm-7 extra-gutter-right" : "col-md-12"; ?>

<?php $titlebar_status = get_option('truelysell_blog_titlebar_status','show');
$sidebar_side = get_option('pp_blog_layout'); 
?>

<?php if( is_active_sidebar( 'sidebar-1' )) {
		$blog_type ="col-lg-8 col-md-12";
	} else {
		$blog_type ="col-lg-12 col-md-12"; 
	}
 ?>

<div class="content">
<div class="container">
	<div class="row">
		<div id="primary" class="<?php echo esc_attr($blog_type); ?> blog-details">
			 
			<article id="post-<?php the_ID(); ?>">
			<!-- Post Content -->
				<?php
				while ( have_posts() ) : the_post(); ?>

				 <?php get_template_part( 'template-parts/content', 'single' ); ?>
					
				 

				 <div class="blog blog-list">			 
					<div class="blog-content">
					 <div class="row">
				        <div class="col-md-6 col-6">
					        <?php $previous = get_previous_post();
	                          $next = get_next_post(); ?>
                            <a href="<?php echo esc_url(get_the_permalink($previous)) ?>">
                              <div class="single-pagination-content">
							    <span class="btn btn-primary"><i class="fa fa-angle-left mr-2"></i> <?php esc_html_e('Prev Post', 'truelysell'); ?></span>
							  </div>
							</a>
						</div>

					<div class="col-md-6 col-6">
						<div class="float-end">
					      	<a class="text-end" href="<?php echo esc_url(get_the_permalink($next)) ?>">
							<div class="single-pagination-content">
								<span  class="btn btn-primary"><?php esc_html_e('Next Post', 'truelysell'); ?> <i class="fa fa-angle-right ml-2"></i></span>
							</div>
				         	</a>
						</div>
					</div>
				    </div>
				</div>
				</div>
 
				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
			
				?>
<article>
			 
</div>
	<!-- Content / End -->

<?php 
 if( is_active_sidebar( 'sidebar-1' )) {  ?>
	 <div class="col-lg-4 col-md-12 blog-sidebar <?php if(in_array('truelysell-core/truelysell-core.php', apply_filters('active_plugins', get_option('active_plugins'))))
{ ?> site_sidebar <?php } else { ?> default_sidebar <?php } ?> theiaStickySidebar">
    <?php get_sidebar(); ?>
 	</div>
 <?php } ?>
</div> <!-- row -->
</div> <!-- container -->
</div> <!-- content -->
<?php get_footer();