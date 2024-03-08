<?php 
if (isset($_GET['s']) && $_GET['s'] != "")
{
	$list_s = $_GET['s'];
}
$args_search = array('post_type' => 'post','s'=>$list_s);
$wp_query_search = new WP_Query($args_search);
?>
<?php 
if ( $wp_query_search->have_posts() )
{ 
    while (  $wp_query_search->have_posts() )
    { $wp_query_search->the_post();
	 $post_id = get_the_ID();
    ?>
<div id="post-<?php the_ID(); ?>" class="blog blog-list">
    <div class="blog-image">
        <?php 
            if(has_post_thumbnail()) { 
            $thumb = get_post_thumbnail_id();
            $img_url = wp_get_attachment_url( $thumb,'full');
            //resize & crop the image ?>
               <?php if($img_url){ ?>
                    <div class="blog-img-wrapper">
                    <a href="<?php the_permalink();?>">
                        <img src="<?php echo esc_url($img_url); ?>">
                    </a>
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
    <h3 class="blog-title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
    <p class="mb-0"><?php echo mb_strimwidth(get_the_excerpt(), 0, 400, '...'); ?></p>
</div>
 </div>
<?php 
}
}
else
{
    get_template_part( 'template-parts/content', 'none' );
}
?>
 