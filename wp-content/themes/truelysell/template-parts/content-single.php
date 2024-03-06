<div class="blog-head">
			<div class="blog-category">        
			<?php 
				$get_author_id = get_the_author_meta('ID');
				$get_author_gravatar = get_avatar_url($get_author_id, array('size' => 30));
				$url = get_author_posts_url(get_the_author_meta('ID'));
			?>
 				<?php $post_date = get_the_date( ); ?>
              <?php $commentcount = get_comments_number( $post->ID );?>
    							  <ul>
                       <?php 
                        $categories_blog = get_the_terms( $post->ID, 'category' );
                        if (is_array($categories_blog) || is_object($categories_blog))
                        {
                          foreach( $categories_blog as $category_blog ) 
                          { ?>
                            <li>
                              <a href="<?php get_term_link($category_blog->slug, 'listing_category'); ?>"><span class="cat-blog"><?php echo esc_html($category_blog->name); ?></span></a>
                            </li> 
                       <?php } } ?>
										</ul>
									</div>	
									<h3><?php the_title(); ?></h3>	
                  <div class="blog-category blog-category-bottom">     
                  <ul>
                    <li>
												<div class="post-author">
												 <img src="<?php echo esc_html($get_author_gravatar);?>"><span><?php echo get_the_author(); ?></span> 
												</div>
											</li>
                      <li><i class="feather-calendar me-2"></i><?php echo esc_html($post_date); ?></li>
                      <?php
                        $comments = get_comments_number();
                        $comment_text ='';
                        if($comments >1)
                        {
                          $comment_text = esc_html(get_comments_number()).esc_html__(' Comments', 'truelysell');
                        }
                        else if ($comments == 0)
                        {
                          $comment_text = esc_html__('No Comments', 'truelysell');
                        }
                        else
                        {
                          $comment_text = esc_html(get_comments_number()).esc_html__(' Comment', 'truelysell');
                        }
                        ?>
                      <li><i class="feather-message-circle me-2"></i><?php echo esc_html($comment_text);?> </li>
										</ul>
                  </div>

								</div>

<div class="blog blog-list">
<div class="blog-image">
<?php 
if ( ! post_password_required() ) { 
    if(has_post_thumbnail()) { 
          $thumb = get_post_thumbnail_id();
          $img_url = wp_get_attachment_url( $thumb,'full');
          ?>
          <img src="<?php echo esc_url($img_url); ?>" class="post-img" alt="<?php the_title(); ?>">
        
   <?php } 
}?>
</div>
<div class="blog-content entry-content">
<?php the_content(); ?>
</div>
</div>
 
<?php  
 if (get_the_tags($post->ID)) { ?>

<div class="blog blog-list single_tags">

<div class="fr-latest-container">
					  <?php
						wp_link_pages( array(
						'before'      => '<div class="page_with_pagination"><div class="page-links">','after' => '</div></div>','next_or_number' => 'number','link_before' => '<span class="no">','link_after'  => '</span>') );
				   	?>
					<div class="clearfix"></div>
 </div>
					 
 <div class="blog-content">
 <h4 class="single_blog_tag">Tags</h4>
      <?php  
       foreach(get_the_tags($post->ID) as $tag) {
       ?>
         <a href="<?php echo esc_url(get_term_link($tag->slug, 'post_tag')) ?>" class="tag-cloud-link"><?php echo esc_html($tag->name); ?></a>
    <?php  } ?>
 </div>
 </div>
<?php  }  ?>


 
    
 

    

  