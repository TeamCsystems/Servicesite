<?php
/**
 * Template Name: Home Page with Search Form
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Truelysell
 */

get_header(); 

if(get_option('truelysell_home_background_type')=='video'){
	$video = get_option('truelysell_search_video_mp4'); 	
} else {
	$video = false;
}

$form_type = get_option('truelysell_home_form_type','wide');
$background =  get_post_meta($post->ID, 'truelysell_parallax_image', TRUE);
if(empty($background)) {
	$background =  get_option( 'truelysell_search_bg');
}
 ?>
<!-- Banner
================================================== -->
 

<?php while ( have_posts() ) : the_post(); ?>
	<!-- 960 Container -->
	<div class="container page-container home-page-container">
	    <article <?php post_class(); ?>>
	        <?php the_content(); ?>
	    </article>
	</div>
<?php endwhile; // end of the loop.
get_footer(); ?>