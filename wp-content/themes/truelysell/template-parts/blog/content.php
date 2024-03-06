<?php $metas =  get_option( 'pp_blog_meta', array('author','date','com') ); ?>
 
<?php global $post; 
$post_id=$post->ID;
?>

<div id="post-<?php the_ID(); ?>" class="blog blog-list ">
<div <?php post_class(); ?>>
  <div class="blog-image">
 <a href="<?php the_permalink();?>">
  <?php 
          if(has_post_thumbnail()) { 
              $thumb = get_post_thumbnail_id();
              $img_url = wp_get_attachment_url( $thumb,'full');
              //resize & crop the image ?>
                  <?php if($img_url){ ?>
                      <div class="blog-img-wrapper">
                          <img src="<?php echo esc_url($img_url); ?>">
                      </div>
                      <?php
                  } 
                      else { 
                          the_post_thumbnail('truelysell-avatar');
                      } 
                      ?>
                  
      <?php }  ?>
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
										<h3 class="blog-title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>

 <div class="blog-category blog-category-bottom">
											<ul>
												<li>
                           <div class="post-author">
                      
                              <?php 
                              $get_author_id = get_the_author_meta('ID');
                              $get_author_gravatar = get_avatar_url($get_author_id, array('size' => 30));
                              $url = get_author_posts_url(get_the_author_meta('ID'));
                            ?>
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><img src="<?php echo esc_html($get_author_gravatar);?>"><span><?php echo get_the_author(); ?></span></a>
                                  
                           </div>
												</li>
                         <li><i class="feather-calendar me-2"></i><?php $post_date = get_the_date(); ?><?php echo esc_html($post_date); ?></li>

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

 												<li><i class="feather-message-circle me-2"></i><?php echo esc_html($comment_text); ?></li>
											</ul>
										</div>
 <p><?php echo mb_strimwidth(get_the_excerpt(), 0, 400, '...'); ?></p>			
 </div>
 </div>
</div>
 

 