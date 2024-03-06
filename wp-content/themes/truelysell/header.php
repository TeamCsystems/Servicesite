<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package truelysell
 */

?>
 
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
 <head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="main-wrapper">

<?php

$header_style =  truelysell_fl_framework_getoptions('header_style');
?>

<?php if($header_style=='style1') { ?>
	<?php get_template_part( 'template-parts/header/header', 'style-one' ); ?>
	<?php } else if($header_style=='style2') {  ?> 
	<?php get_template_part( 'template-parts/header/header', 'style-two' ); ?>
	<?php } else {  ?>
		<?php get_template_part( 'template-parts/header/header', 'style-one' ); ?>
	<?php } ?>

	<?php if(is_page_template( 'template-home-search.php' )  ) 
	{ ?>
	<div class="bg-img">
			<img src="<?php echo get_template_directory_uri();?>/assets/images/bg/feature-bg-03.png" alt="img" class="bgimg3">
			<img src="<?php echo get_template_directory_uri();?>/assets/images/bg/about-bg-01.png" alt="img" class="bgimg4 img-fluid">
		</div>
		<?php } ?>
<?php if(is_page_template( 'template-login.php' ) || is_page_template( 'template-register.php' )   ) 
	{ ?>
	<?php } else {  ?>
	<?php 
	if(!is_front_page()){
		if( is_home()) { ?>
			<div class="breadcrumb-bar">
					<div class="container">

					<div class="row">
					<div class="col-md-12 col-12">
						<h2 class="breadcrumb-title"><?php echo esc_html_e('Blog','truelysell'); ?></h2>
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">	
								<li class="breadcrumb-item">
									<a href="<?php echo home_url();?>"><?php echo esc_html_e('Home','truelysell'); ?></a>
									
								</li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo esc_html_e('Blog List','truelysell'); ?></li>
							</ol>
						</nav>
					</div>
	                </div>
					</div>
			</div>
		<?php }  else if (is_singular('post')) { ?>

			<div class="breadcrumb-bar">
					<div class="container">

					<div class="row">
					<div class="col-md-12 col-12">
						<h2 class="breadcrumb-title"><?php echo esc_html_e('Blog','truelysell'); ?></h2>
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">	
								<li class="breadcrumb-item">
									<a href="<?php echo home_url();?>"><?php echo esc_html_e('Home','truelysell'); ?></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page"><?php the_title();?></li>
							</ol>
						</nav>
					</div>
	                </div>
					</div>
			</div>
			
		 <?php }
		else if(is_archive()){ ?>
			<div class="breadcrumb-bar">
					<div class="container">

					<div class="row">
					<div class="col-md-12 col-12">
						<h2 class="breadcrumb-title"><?php echo get_the_archive_title();?></h2>
						<nav aria-label="breadcrumb" class="page-breadcrumb">
						<ol class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="<?php echo home_url();?>"><?php echo esc_html_e('Home','truelysell'); ?></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo get_the_archive_title();?></li>
						</ol>
						</nav>
					</div>
	                </div>
					</div>
			</div>
		<?php }
		else if(is_404()){ ?>
			<div class="breadcrumb-bar">
					<div class="container">

					<div class="row">
					<div class="col-md-12 col-12">
						<h2 class="breadcrumb-title"><?php echo esc_html_e('Page Not Found','truelysell'); ?></h2>
						<nav aria-label="breadcrumb" class="page-breadcrumb">
						    <ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="<?php echo home_url();?>"><?php echo esc_html_e('Home','truelysell'); ?></a>
										</li>
										<li class="breadcrumb-item active" aria-current="page"><?php echo esc_html_e('404','truelysell'); ?></li>
						    </ol>
						</nav>
					</div>
	                </div>
					</div> 
			</div>
		<?php } else if(is_search()){ ?>
			<div class="breadcrumb-bar">
					<div class="container">

					<div class="row">
					<div class="col-md-12 col-12">
						<h2 class="breadcrumb-title"><?php echo esc_html_e('Search','truelysell'); ?></h2>
						<nav aria-label="breadcrumb" class="page-breadcrumb">
						    <ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="<?php echo home_url();?>"><?php echo esc_html_e('Home','truelysell'); ?></a>
										</li>
										<li class="breadcrumb-item active" aria-current="page"><?php echo esc_html(get_search_query()); ?></li>
						    </ol>
						</nav>
					</div>
	                </div>
					</div>
			</div>
		<?php }
		else if(is_singular('listing')){ ?>
			<div class="breadcrumb-bar">
					<div class="container">

					<div class="row">
					<div class="col-md-12 col-12">
						<h2 class="breadcrumb-title"><?php echo esc_html_e('Service Detail','truelysell'); ?></h2>
						<nav aria-label="breadcrumb" class="page-breadcrumb">
						    <ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="<?php echo home_url();?>"><?php echo esc_html_e('Home','truelysell'); ?></a>
										</li>
										<li class="breadcrumb-item active" aria-current="page"><?php the_title();?></li>
							</ol>
						</nav>
					</div>
	                </div>
					</div>
			</div>
		<?php }
		else { ?>
			<div class="breadcrumb-bar">
					<div class="container">

					<div class="row">
					<div class="col-md-12 col-12">
						<h2 class="breadcrumb-title"><?php the_title();?></h2>
						<nav aria-label="breadcrumb" class="page-breadcrumb">
						<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="<?php echo home_url();?>"><?php echo esc_html_e('Home','truelysell'); ?></a>
										</li>
										<li class="breadcrumb-item active" aria-current="page"><?php the_title();?></li>
									</ol>
						</nav>
					</div>
	                </div>
					</div>
			</div>
		<?php } }
	
	?>
	<?php } ?>