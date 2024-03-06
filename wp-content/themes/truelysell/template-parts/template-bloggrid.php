<?php
/**
 * Template Name: Blog Grid Template
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WPVoyager
 */
get_header();
?>
<div class="content">
	<div class="container">
			<div class="row">
                <?php 
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $post_per_page = 6;
                $args=array(
                    'post_type' => 'post',
                    'paged' => $paged,
                    'posts_per_page' => $post_per_page,
                    'parent'                   => '',
                    'orderby'                  => 'id',
                    'order'                    => 'ASC'

                );
            
                $wp_query = new WP_Query($args);
                if( have_posts() ) :
               while ($wp_query->have_posts()) : $wp_query->the_post(); 
                ?>
                <div class="col-md-4 d-flex">
				<div class="blog grid-blog flex-fill">
						<div class="blog-image">
						<?php if (has_post_thumbnail( $post->ID ) ): ?>
							<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
							<img src="<?php echo esc_html($image[0]); ?>"/>
							<?php endif; ?>
						</div>
						 
							<div class="blog-content">

							<div class="blog-category">
							<?php 
										$get_author_id = get_the_author_meta('ID');
										$get_author_gravatar = get_avatar_url($get_author_id, array('size' => 30));
										$url = get_author_posts_url(get_the_author_meta('ID'));
										?>
											<ul>
											 <?php 
                            $categories_list = wp_get_post_categories($wp_query->post->ID);
                            $cats = array();

                            $output = '';
                            foreach($categories_list as $c){
                                $cat = get_category( $c );
                                $cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug, 'url' => get_category_link($cat->cat_ID) );
                            }
                            $single_cat = array_shift( $cats ); ?>
							<li><a href="<?php echo esc_url(get_term_link($cat->slug, 'category')) ?>"><span class="cat-blog"> <?php $single_cat_display= $single_cat['name']; echo esc_html($single_cat_display,'truelysell'); ?> </span></a></li>
						  
										</div>
  								<h3 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>

								  <div class="blog-category blog-category-bottom">
							<?php 
										$get_author_id = get_the_author_meta('ID');
										$get_author_gravatar = get_avatar_url($get_author_id, array('size' => 30));
										$url = get_author_posts_url(get_the_author_meta('ID'));
										?>
											<ul>
												<li>
													<div class="post-author 3">
														 <img src="<?php echo esc_html($get_author_gravatar);?>"><span><?php echo get_the_author(); ?></span> 
													</div>
												</li>
												<li><i class="feather-calendar me-2"></i><?php echo get_the_date();?></li>
											</ul>
										</div>
								<p><?php echo wp_trim_words( get_the_content(), 25, '...' );?></p>
								<a href="<?php the_permalink(); ?>" class="read-more btn btn-primary"><?php echo esc_html__( 'Read More', 'truelysell' );?></a>
							</div>
						 
						</div>
                </div>

        <?php endwhile; 
        truelysell_blog_pagination(); 
        wp_reset_query();
        ?>
<?php endif; ?>
</div>
</div>
</div>
<?php get_footer(); ?>