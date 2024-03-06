<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package truelysell
 */
?>

<section class="no-results not-found blog-nothing-found mb-4">
	<header class="page-header">
		<h3 class=" mb-3 notfound_txt"><?php esc_html_e( 'Nothing', 'truelysell' ); ?> <span class="theme-text"> <?php esc_html_e( 'Found', 'truelysell' ); ?></span></h3>
	</header><!-- .page-header -->

	<div class="page-contentone col-md-12">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'truelysell' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'truelysell' ); ?></p>
			<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
			<div class="form-group mb-4"> 

			<div class="input-group stylish-input-group">
					
			<input type="text"  name="s" class="form-control" placeholder="<?php esc_html_e('Search...','truelysell') ?>"/>
				    <span class="input-group-append"><button class="blog-search-btn" type="submit">  <i class="fa fa-search" aria-hidden="true"></i> </button></span></div>
 			</div>

            </form>

            <a href="<?php echo home_url( '/' ); ?> " class="btn btn-primary btn-lg"> <?php echo esc_html('Back to home','truelysell'); ?></a>

			<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'truelysell' ); ?></p>
			<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
                        
			<div class="form-group mb-4"> 

			<div class="input-group stylish-input-group">
					
			<input type="text"  name="s" class="form-control" placeholder="<?php esc_html_e('What are you looking for?','truelysell') ?>"/>
				    <span class="input-group-append"><button class="blog-search-btn" type="submit">  <i class="fa fa-search" aria-hidden="true"></i> </button></span></div>
 			</div>
 
            </form>
 
            <a href="<?php echo home_url( '/' ); ?> " class="btn btn-primary btn-lg"> <?php echo esc_html('Back to home','truelysell'); ?></a>

			<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
