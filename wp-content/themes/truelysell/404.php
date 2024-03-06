<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package truelysell
 */
   get_template_part( 'template-parts/header/header', 'none' ); 
?>
  <div class="bg-img">
			<img src="<?php echo get_template_directory_uri();?>/assets/images/bg/work-bg-03.png" alt="img" class="bgimg1">
			<img src="<?php echo get_template_directory_uri();?>/assets/images/bg/work-bg-03.png" alt="img" class="bgimg2">
			<img src="<?php echo get_template_directory_uri();?>/assets/images/bg/feature-bg-03.png" alt="img" class="bgimg3">
		</div>
		<div class="content">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 mx-auto">
						<div class="error-wrap">
							<div class="error-logo">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="img-fluid" src="<?php echo get_template_directory_uri();?>/assets/images/logo.svg" alt="img"></a>
							</div>
							<div class="error-img">
								<img class="img-fluid" src="<?php echo get_template_directory_uri();?>/assets/images/error-404.png" alt="img">
							</div>
							<h2><?php echo esc_html__( '404 Oops! Page Not Found', 'truelysell' );?></h2>
							<p><?php echo esc_html__( 'This page doesnt exist or was removed! We suggest you back to home.', 'truelysell' );?></p>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php echo esc_html__( 'Back to Home', 'truelysell' );?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
get_template_part( 'template-parts/footer/footer', 'none' ); 
?>
