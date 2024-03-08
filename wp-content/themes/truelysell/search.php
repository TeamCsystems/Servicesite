<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package truelysell
 */

get_header();
?>

<?php if( is_active_sidebar( 'sidebar-1' )) {
		$blog_type ="col-lg-8 col-md-12";
	} else {
		$blog_type ="col-lg-12 col-md-12"; 
	}
 ?>

<!-- Titlebar
================================================== -->
<div class="content">
<div id="titlebar">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				
				<h2 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'truelysell' ), '<em>' . get_search_query() . '</em>' ); ?></h2>
	
 			</div>
		</div>
	</div>
</div>
<?php $sidebar_side = get_option('pp_blog_layout'); 
 ?>
<!-- Content
================================================== -->
<div class="container <?php echo esc_attr($sidebar_side); ?>">

	<!-- Blog Posts -->
	<div class="row">
			<div class="<?php echo esc_attr($blog_type); ?>">
			    <?php
				  get_template_part( 'template-parts/blog/content','loop-search' );  
				?>
				<!-- Pagination -->
				<div class="clearfix"></div>
					<?php truelysell_blog_pagination_search(); ?>
		     	<!-- Pagination / End -->
			</div>
			 <!-- Widgets -->
			<?php   if( is_active_sidebar( 'sidebar-1' )) {  ?>
			<div class="col-lg-4 col-md-12 blog-sidebar <?php if(in_array('truelysell-core/truelysell-core.php', apply_filters('active_plugins', get_option('active_plugins'))))
{ ?> site_sidebar <?php } else { ?> default_sidebar <?php } ?> theiaStickySidebar">
				 
					<?php get_sidebar(); ?>
 			</div>
			 <?php } ?>
			<!-- Sidebar / End -->
		</div>

</div>
</div>

<?php get_footer(); ?>