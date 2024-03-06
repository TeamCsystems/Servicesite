<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WorkScout
 */

get_header();
$parallax 			= truelysell_fl_framework_getoptions('shop_header_bg');
$parallax_color 	= truelysell_fl_framework_getoptions('shop_header_color');
$parallax_opacity 	= truelysell_fl_framework_getoptions('shop_header_bg_opacity');
$parallax_output  	= '';
$parallax_output .= (!empty($parallax)) ? ' data-background="' . esc_url($parallax) . '" ' : '';
$parallax_output .= (!empty($parallax_color)) ? ' data-color="' . esc_attr($parallax_color) . '" ' : '';
$parallax_output .= (!empty($parallax_opacity)) ? ' data-color-opacity="' . esc_attr($parallax_opacity) . '" ' : '';

if ( class_exists('WeDevs_Dokan') && is_single()) {
	$authordata = get_userdata($post->post_author);
	$author = $authordata->ID;

	$store_user = dokan()->vendor->get($author);
	$header_background = $store_user->get_banner();
?>

	<div id="titlebar" class="store-titlebar  no-store-bg">
		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php dokan_get_template_part('store-header'); ?>
				</div>
			</div>
		</div>
	</div>
	
		<div class="container single-product-titlebar">
			<div class="row">
				<div class="col-md-12">
  					<!-- Breadcrumbs -->
					<?php if (function_exists('bcn_display')) { ?>
						<nav id="breadcrumbs">
							<ul>
								<?php bcn_display_list(); ?>
							</ul>
						</nav>
					<?php } ?>

				</div>
			</div>
		</div>
	
	<?php }  ?>

<?php

$layout = get_option('pp_shop_layout', 'full-width');
$class  = ($layout != "full-width") ? "col-md-12 col-lg-12" : "col-md-12";
?>
<div class="content ">
<div class="container truelysell-shop-grid <?php echo esc_attr($layout); ?>">
	<div class="row">
		<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
			<?php woocommerce_content(); ?>
		</article>
</div>
</div>
</div>
<?php 
get_footer(); ?>