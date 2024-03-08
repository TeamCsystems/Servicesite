<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package truelysell
 */


$page_top = get_post_meta($post->ID, 'truelysell_page_top', TRUE); 
$page_top = apply_filters('truelysell_default_page_top',$page_top);
switch ($page_top) {
	case 'titlebar':
		get_template_part( 'template-parts/header','titlebar');
		break;		

	case 'parallax':
		get_template_part( 'template-parts/header','parallax');
		break;	

	case 'off':

		break;
	
	default:
		get_template_part( 'template-parts/header','titlebar');
		break;
}
?>


<?php
$layout = get_post_meta($post->ID, 'truelysell_page_layout', true); if(empty($layout)) { $layout = 'full-width'; }
$class  = ($layout !="full-width") ? "col-blog  padding-right-30 page-container-col" : "col-md-12 page-container-col";
?>
<div class="content <?php echo esc_html($layout,'truelysell'); ?> <?php if(in_array('truelysell-core/truelysell-core.php', apply_filters('active_plugins', get_option('active_plugins'))))
{ ?>
 
<?php } else { ?> default_page <?php } ?>">
 
	<div class="container">
	<div class="row">
	<?php if($layout !="full-width") { ?>
		
			<div class="col-lg-3 col-md-12 theiaStickySidebar">
				<div class="service_sidebar">
 						<?php
						if ( is_active_sidebar( 'sidebar-listings' ) ) {
							dynamic_sidebar( 'sidebar-listings' );
						}
						?>
 				</div>
			</div>
		<?php } ?>
		<?php if($layout !="full-width") { ?>
			<div class="col-lg-9 col-md-12">
			<?php } ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
			<?php the_content(); ?>

			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'truelysell' ),
					'after'  => '</div>',
				) );
			?>
 
			<?php
		        if(get_option('pp_pagecomments','on') == 'on') {
		        	
		            // If comments are open or we have at least one comment, load up the comment template
		            if ( comments_open() || get_comments_number() ) :
		                comments_template();
		            endif;
		        }
		    ?>

		</article>
		<?php if($layout !="full-width") { ?>
			</div>
		<?php } ?>
		</div>
 </div>  
</div>
</div>
<div class="clearfix"></div>
<?php
$stick_footer = get_post_meta($post->ID, 'truelysell_glued_footer', TRUE); 
if(!$stick_footer) { ?>
<div class="margin-top-55"></div>
<?php } ?>

