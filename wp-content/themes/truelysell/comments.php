<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package truelysell
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div class="clearfix"></div>
<div class="service-wrap1 blog-review">
  <h4>Comments (<?php printf( number_format_i18n( get_comments_number() ) ); ?>)
</div>

<section class="comments review-box-comments">
	<?php
	if ( have_comments() ) : ?>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'truelysell' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'truelysell' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'truelysell' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list">
			<?php

			if ( get_post_type( get_the_ID() ) == 'listing' ) {
			    wp_list_comments( array(
					'style'      	=> 'ul',
					'short_ping' 	=> true,
					'callback' 		=> 'truelysell_get_reviews_info',
				) );
			} else {
				wp_list_comments( array(
					'style'      	=> 'ul',
					'short_ping' 	=> true,
					'callback' 		=> 'truelysell_get_comments_info',
				) );
			}
				
			?>
		</ul><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'truelysell' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'truelysell' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'truelysell' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php
		endif; // Check for comment navigation.

	endif; // Check for have_comments().

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
<div class="clear"></div>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'truelysell' ); ?></p>
	<?php
	endif;
?>

<?php
	comment_form();
	?>
</section><!-- #comments -->


