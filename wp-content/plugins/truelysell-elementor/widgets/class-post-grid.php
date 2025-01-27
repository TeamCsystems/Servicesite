<?php
/**
 * Awesomesauce class.
 *
 * @category   Class
 * @package    ElementorAwesomesauce
 * @subpackage WordPress
 * @author     Ben Marshall <me@benmarshall.me>
 * @copyright  2020 Ben Marshall
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://www.benmarshall.me/build-custom-elementor-widgets/,
 *             Build Custom Elementor Widgets)
 * @since      1.0.0
 * php version 7.3.9
 */

namespace ElementorTruelysell\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class PostGrid extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'truelysell-posts-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Truelysell Posts Grid', 'truelysell_elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'truelysell' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
  // 'limit'=>'6',
  //           'orderby'=> 'date',
  //           'order'=> 'DESC',
  //           'categories' => '',
  //           'exclude_posts' => '',
  //           'include_posts' => '',
  //           'ignore_sticky_posts' => 1,
  //           'limit_words' => 15,
  //           'from_vs' => 'no'


		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Query', 'truelysell_elementor' ),
			)
		);

		$this->add_control(
			'limit',
			[
				'label' => __( 'Posts to display', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 21,
				'step' => 1,
				'default' => 3,
			]
		);


		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order by', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'none' =>  __( 'No order', 'truelysell_elementor' ),
					'ID' =>  __(  'Order by post id. ', 'truelysell_elementor' ),
					'author'=>  __(  'Order by author.', 'truelysell_elementor' ),
					'title' =>  __(  'Order by title.', 'truelysell_elementor' ),
					'name' =>  __( ' Order by post name (post slug).', 'truelysell_elementor' ),
					'type'=>  __( ' Order by post type.', 'truelysell_elementor' ),
					'date' =>  __( ' Order by date.', 'truelysell_elementor' ),
					'modified' =>  __( ' Order by last modified date.', 'truelysell_elementor' ),
					'parent' =>  __( ' Order by post/page parent id.', 'truelysell_elementor' ),
					'rand' =>  __( ' Random order.', 'truelysell_elementor' ),
					'comment_count' =>  __( ' Order by number of commen', 'truelysell_elementor' ),
					
				],
			]
		);
		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'truelysell_elementor'  ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' =>  __( 'Descending', 'truelysell_elementor' ),
					'ASC' =>  __(  'Ascending. ', 'truelysell_elementor' ),
				
					
				],
			]
		);


			$this->add_control(
				'categories',
				[
					'label' => __( 'Show from categories', 'truelysell_elementor' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple' => true,
					'default' => [],
					'options' => $this->get_terms('category'),
					
				]
			);	

			$this->add_control(
				'exclude_posts',
				[
					'label' => __( 'Exclude posts', 'truelysell_elementor' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple' => true,
					'default' => [],
					'options' => $this->get_posts(),
					
				]
			);	
			$this->add_control(
				'include_posts',
				[
					'label' => __( 'Include posts', 'truelysell_elementor' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple' => true,
					'default' => [],
					'options' => $this->get_posts(),
					
				]
			);

			$this->add_control(
				'limit_words',
				[
					'label' => __( 'Excerpt length', 'truelysell_elementor' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 5,
					'max' => 99,
					'step' => 1,
					'default' => 15,
				]
			);

			$this->add_control(
			'after_excerpt',
			[
				'label' => __( 'Add after excerpt', 'truelysell_elementor'  ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '...', 'plugin-domain' ),
				
			]);

			 
			$this->add_control(
				'show_excerpt',
				[
					'label' => __( 'Show post excerpt', 'truelysell_elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'truelysell_elementor' ),
					'label_off' => __( 'Hide', 'truelysell_elementor' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);		
			$this->add_control(
				'show_date',
				[
					'label' => __( 'Show post date', 'truelysell_elementor'  ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'truelysell_elementor' ),
					'label_off' => __( 'Hide', 'truelysell_elementor' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);
			$this->add_control(
				'show_category',
				[
					'label' => __( 'Show post category', 'truelysell_elementor'  ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'truelysell_elementor' ),
					'label_off' => __( 'Hide', 'truelysell_elementor' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);




		$this->end_controls_section();

		$this->start_controls_section(
			'section_setting',
			array(
				'label' => __( 'Settings', 'truelysell_elementor' ),
			)
		);

			$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' =>  __( 'Style 1', 'truelysell_elementor' ),
					'style2' =>  __( 'Style 2', 'truelysell_elementor' ),
					'style3' =>  __( 'Style 3', 'truelysell_elementor' ),
				],
			]
		);
		$this->add_control(
			'link',
			array(
				'label'   => __( 'Link', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'View All',
			)
		);
		
  
		$this->end_controls_section();
 
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'subtitle', 'none' );
		$limit = $settings['limit'] ? $settings['limit'] : 3;
		$orderby = $settings['orderby'] ? $settings['orderby'] : 'title';
		$order = $settings['order'] ? $settings['order'] : 'ASC';
		$exclude_posts = $settings['exclude_posts'] ? $settings['exclude_posts'] : 'ASC';
		$categories = $settings['categories'] ? $settings['categories'] : array();
		$limit_words = $settings['limit_words'] ? $settings['limit_words'] : 15;
	

		$args = array(
            'post_type' => 'post',
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'order' => $order,
            );

        if(!empty($exclude_posts)) {
            $exl = is_array( $exclude_posts ) ? $exclude_posts : array_filter( array_map( 'trim', explode( ',', $exclude_posts ) ) );
            $args['post__not_in'] = $exl;
        }

        if(!empty($include_posts)) {
            $exl = is_array( $include_posts ) ? $include_posts : array_filter( array_map( 'trim', explode( ',', $include_posts ) ) );
            $args['post__in'] = $exl;
        }

       
        if(!empty($categories)) {
            $categories         = is_array( $categories ) ? $categories : array_filter( array_map( 'trim', explode( ',', $categories ) ) );
            $args['category__in'] = $categories;
        }
      

        $i = 0;

        $wp_query = new \WP_Query( $args ); ?>
		
		 
			<div class="row">

			<?php if ( $wp_query->have_posts() ) { ?>


				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post();
				$i++;
                $id = $wp_query->post->ID;
                $thumb = get_post_thumbnail_id();
                $img_url = wp_get_attachment_url( $thumb,'truelysell-blog-related-post');
               
                

                        ?>
						<?php  if($settings['style']=="style1") {  ?>
			<div class="col-md-4 d-flex">
				<div class="blog aos aos-init aos-animate">
                     <div class="blog-image">
					<a href="<?php the_permalink(); ?>" class="">
					<?php echo the_post_thumbnail('truelysell-blog-related-post'); 
 					$get_author_id = get_the_author_meta('ID');
					$get_author_gravatar = get_avatar_url($get_author_id, array('size' => 30));
					?>
					</a>
					</div>

					<div class="blog-content">
								<ul class="blog-item">
 								<li>
										<div class="post-author">
											 <img src="<?php echo esc_html($get_author_gravatar);?>" class="me-2"><span><?php echo get_the_author(); ?></span> 
										</div>
									</li>
  					  
								<?php if($settings['show_date'] == 'yes'){ ?>
									<li><i class="feather-calendar me-2"></i><?php echo get_the_date(); ?></li>
								<?php } ?>
								<?php 
                      
					  if($settings['show_category'] == 'yes'){ ?>


						 <?php 
						  $categories_list = wp_get_post_categories($wp_query->post->ID);
						  $cats = array();

						  $output = '';
						  foreach($categories_list as $c){
							  $cat = get_category( $c );
							  $cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug, 'url' => get_category_link($cat->cat_ID) );
						  }
						  $single_cat = array_shift( $cats );
 						  ?>
						   <li><a href="<?php echo esc_url(get_term_link($cat->slug, 'category')) ?>"><span class="cat-blog"> <?php $single_cat_display= $single_cat['name']; echo esc_html($single_cat_display,'truelysell_core'); ?> </span></a></li>

					   <?php }   ?>
								 
								</ul>
 
								<h3 class="blog-title">
								<a href="<?php the_permalink(); ?>" class=""><?php the_title(); ?></a>
								</h3>

								<?php if($settings['show_excerpt'] == 'yes'){ ?>
                            <p><?php 
                                $excerpt = get_the_excerpt();
                                echo truelysell_string_limit_words($excerpt,$limit_words); echo $settings['after_excerpt']; ?>
                            </p>
							<a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html_e('Read More','truelysell_elementor'); ?> <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            <?php } ?>

							</div>
     
				</div>
            </div>
 <?php } else if($settings['style']=="style2") {  ?>

	<div class="col-md-4 d-flex">
						<div class="blog blog-new flex-fill aos aos-init aos-animate" data-aos="fade-up">
							<div class="blog-image">
								 
								<a href="<?php the_permalink(); ?>" class="">
					<?php echo the_post_thumbnail('truelysell-blog-related-post'); 
 					$get_author_id = get_the_author_meta('ID');
					$get_author_gravatar = get_avatar_url($get_author_id, array('size' => 30));
					?>
					</a>

								<div class="date">
								
									<?php if($settings['show_date'] == 'yes'){ ?>
										<?php echo get_the_date('d'); ?><span><?php echo get_the_date('M'); ?></span>
								<?php } ?>

								</div>
							</div>
							<div class="blog-content">
								<ul class="blog-item">
									<li>
										<div class="post-author">
											
											<a href="#"><i class="feather-user me-2"></i><span><?php echo get_the_author(); ?></span></a>
										</div>
									</li>
									<li><i class="feather-message-square  me-2"></i><?php echo esc_html_e('Comments','truelysell_elementor'); ?> (<?php echo get_comments_number(); ?>)</li>
								</ul>
								<h3 class="blog-title">
								<a href="<?php the_permalink(); ?>" class=""><?php the_title(); ?></a>
								</h3>
								<?php if($settings['show_excerpt'] == 'yes'){ ?>
                            <p><?php 
                                $excerpt = get_the_excerpt();
                                echo truelysell_string_limit_words($excerpt,$limit_words); 
								echo $settings['after_excerpt']; ?>
                            </p>
                             <?php } ?>
							</div>
						</div>					
					</div>
 <?php } else if($settings['style']=="style3") {  ?>


	<div class="col-lg-4 col-md-6 col-12">
						<div class="service-widget service-six aos aos-init aos-animate" data-aos="fade-up">
							<div class="service-img">
								<a href="<?php the_permalink(); ?>">
 									<?php echo the_post_thumbnail('truelysell-blog-related-post'); 
				 $get_author_id = get_the_author_meta('ID');
				$get_author_gravatar = get_avatar_url($get_author_id, array('size' => 30));
				?>

								</a>
							</div>
							<div class="service-content service-content-six">
								<div class="latest-blog-six">
									<div class="latest-blog-content">
									<?php if($settings['show_date'] == 'yes'){ ?>
										<h5><?php echo get_the_date('d'); ?><span><?php echo get_the_date('M'); ?></span></h5>
										<?php } ?>
									</div>
									<div class="latest-profile-name">
 										<img src="<?php echo esc_html($get_author_gravatar);?>" >
										<h6><?php echo get_the_author(); ?></h6>
									</div>
								</div>
								<h5 class="blog-import-service"><?php the_title(); ?></h5>
 						<?php if($settings['show_excerpt'] == 'yes'){ ?>
						  <p><?php $excerpt = get_the_excerpt();
							    echo truelysell_string_limit_words($excerpt,$limit_words); 
							    echo $settings['after_excerpt']; ?> </p>
						 <?php } ?>

								<a href="<?php the_permalink(); ?>"><?php echo esc_html_e('Read More','truelysell_elementor'); ?></a>
							</div>
						</div>
					</div>
   <?php } ?>

<?php 
			 endwhile; // end of the loop. 
		} else {
			//do_action( "woocommerce_shortcode_{$loop_name}_loop_no_results" );
		}
        ?>
  </div>
        
         
        <?php 
    
		wp_reset_postdata();
	
	
		
	}


		protected function get_terms($taxonomy) {
			$taxonomies = get_terms( array( 'taxonomy' =>$taxonomy,'hide_empty' => false) );

			$options = [ '' => '' ];
			
			if ( !empty($taxonomies) ) :
				foreach ( $taxonomies as $taxonomy ) {
					$options[ $taxonomy->term_id ] = $taxonomy->name;
				}
			endif;

			return $options;
		}

		protected function get_posts() {
			$posts = get_posts( array( 'numberposts' => 99,) );

			$options = [ '' => '' ];
			
			if ( !empty($posts) ) :
				foreach ( $posts as $post ) {
					$options[ $post->ID ] = get_the_title($post->ID);
				}
			endif;

			return $options;
		}
	
}