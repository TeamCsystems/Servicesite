<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package truelysell
 */ 
?>

<?php
$layout = get_post_meta($post->ID, 'truelysell_page_layout', true); if(empty($layout)) { $layout = 'full-width'; }
$class  = ($layout !="full-width") ? "col-blog  padding-right-30 page-container-col" : "col-md-12 page-container-col";
?>
<div class="content_elementor">
		<article id="post-<?php the_ID(); ?>">
			<?php the_content(); ?>
		</article>
</div>
  
 

