<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post,$product;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', esc_html__( 'Product Description', 'truelysell' ) ) );

?>

<?php if ( $heading ): ?>
  <h2><?php echo esc_html($heading); ?></h2>
<?php endif; ?>
<?php if($product->get_type() == "job_package" ) { ?>
	<ul>
		<?php 
		$jobslimit = $product->get_limit();
		if(!$jobslimit){
			echo "<li>";
			esc_html_e('Unlimited number of jobs','truelysell'); 
			echo "</li>";
		} else { ?>
		<li>
			<?php esc_html_e('This plan includes ','truelysell'); printf( _n( '%d job', '%s jobs', $jobslimit, 'truelysell' ) . ' ', $jobslimit ); ?>
		</li>
		<?php } 

		$jobduration =  $product->get_duration();
		if(!empty($jobduration)){ ?>
		<li>
			<?php esc_html_e('Jobs are posted ','truelysell'); printf( _n( 'for %s day', 'for %s days', $product->get_duration(), 'truelysell' ), $product->get_duration() ); ?>
		</li>
		<?php } ?>

	</ul>
<?php } ?>

<?php if($product->get_type() == "resume_package" ) { ?>
	<ul>
		<?php 
		$jobslimit = $product->get_limit();
		if(!$jobslimit){
			echo "<li>";
			esc_html_e('Unlimited number of Resumes','truelysell'); 
			echo "</li>";
		} else { ?>
			<li>
				<?php esc_html_e('This plan includes ','truelysell'); printf( _n( '%d resume', '%s resumes', $jobslimit, 'truelysell' ) . ' ', $jobslimit ); ?>
			</li>
		<?php } 

		$jobduration =  $product->get_duration();
		if(!empty($jobduration)){ ?>
			<li>
				<?php esc_html_e('Resumes are posted ','truelysell'); printf( _n( 'for %s day', 'for %s days', $product->get_duration(), 'truelysell' ), $product->get_duration() ); ?>
			</li>
		<?php } ?>

	</ul>
<?php } ?>
<?php the_content(); ?>
