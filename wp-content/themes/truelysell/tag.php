<?php
get_header(); 
$sidebar_side = get_option('pp_blog_layout'); 
?>
  <?php if( is_active_sidebar( 'sidebar-1' )) {
		$blog_type ="col-lg-8 col-md-12";
	} else {
		$blog_type ="col-lg-12 col-md-12"; 
	}
 ?>
<div class="content">
<div class="container ">
	<!-- Blog Posts -->
		<div class="row">
			<div class="<?php echo esc_attr($blog_type); ?>">
			<?php
			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/blog/content', get_post_format() );
				
				endwhile;
			
				?>
				<!-- Pagination -->
				<div class="clearfix"></div>
					<?php truelysell_blog_pagination(); ?>
			<!-- Pagination / End -->
 <?php else :
				get_template_part( 'template-parts/content', 'none' );
			endif; ?>

			</div>
			<!-- Widgets -->
			<?php   if( is_active_sidebar( 'sidebar-1' )) {  ?>
			<div class="col-lg-4 col-md-12 blog-sidebar  <?php if(in_array('truelysell-core/truelysell-core.php', apply_filters('active_plugins', get_option('active_plugins'))))
{ ?> site_sidebar <?php } else { ?> default_sidebar <?php } ?> theiaStickySidebar">
				 
			   <?php get_sidebar(); ?>
			</div>
			<?php } ?>
			<!-- Sidebar / End -->
		</div>
	<!-- Sidebar / End -->
	</div>
</div>
<?php get_footer(); ?>