<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package truelysell
 */
?>
<?php

$header_style =  truelysell_fl_framework_getoptions('footer_style');

?>

<?php if($header_style=='style1') { ?>
	<?php get_template_part( 'template-parts/footer/footer', 'style-one' ); ?>
	<?php } else if($header_style=='style2') {  ?> 
	<?php get_template_part( 'template-parts/footer/footer', 'style-two' ); ?>
	<?php } else {  ?>
		<?php get_template_part( 'template-parts/footer/footer', 'style-one' ); ?>
	<?php } ?>

 

		<div class="footer-bottom">
			<div class="container">
				<div class="copyright">
					<div class="row align-items-center">
						<div class="<?php if (is_active_sidebar( 'footerarea-payment-image' ) &&  is_active_sidebar( 'footerarea-copyright-menu' ) ) { ?>col-md-4<?php } else if (is_active_sidebar( 'footerarea-payment-image' ) ||  is_active_sidebar( 'footerarea-copyright-menu' ) ) { ?>col-md-6 <?php } else { ?>col-md-12<?php } ?>">
							<div class="copyright-text">
	<?php if(truelysell_fl_framework_getoptions('copy_right') != "") { ?>
							<p class="mb-0"><?php echo  truelysell_fl_framework_getoptions('copy_right', true); ?></p>	
							<?php }
							
							else{
								?>
								<p class="mb-0"><?php echo esc_html_e('Copyright','truelysell');?> © <?php echo date( 'Y' );?> <a href="<?php echo get_home_url();?>"><?php echo wp_title();?></a>. <?php echo esc_html_e('All rights reserved.','truelysell');?></p>
								<?php
								
							}?>							
							 </div>
						</div>
						<?php  if (is_active_sidebar( 'footerarea-payment-image' ) ) { ?>
                                     <div class="<?php if (is_active_sidebar( 'footerarea-copyright-menu' ) ) { ?> col-md-4 <?php } else { ?> col-md-6 <?php } ?>">
									 <?php if (is_active_sidebar( 'footerarea-copyright-menu' ) ) { ?>  
										<?php dynamic_sidebar( 'footerarea-payment-image' ); ?>

										   <?php } else { ?> 
											<div class="float-end">
											<?php dynamic_sidebar( 'footerarea-payment-image' ); ?>
											</div>
											<?php } ?>
								      </div>
							<?php } ?>
							<?php
					         	if (is_active_sidebar( 'footerarea-copyright-menu' ) ) { ?>
                                     <div class=" <?php if (is_active_sidebar( 'footerarea-payment-image' ) ) { ?> col-md-4 <?php } else { ?> col-md-6 <?php } ?>">
								       <?php dynamic_sidebar( 'footerarea-copyright-menu' ); ?>
								      </div>

							<?php } ?>
					</div>
				 </div>
			</div>
		</div>
		
</footer><!-- #colophon -->
	   <div class="mouse-cursor cursor-outer"></div>
	   <div class="mouse-cursor cursor-inner"></div>
</div><!-- #page -->
<div class="progress-wrap active-progress">
	<svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
	<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919px, 307.919px; stroke-dashoffset: 228.265px;"></path>
	</svg>
</div>
<?php wp_footer(); ?>
</body>
</html>
