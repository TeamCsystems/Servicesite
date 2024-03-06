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
class Testimonials extends Widget_Base {

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
		return 'truelysell-testimonials';
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
		return __( 'Truelysell Testimonials', 'truelysell_elementor' );
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
		return 'eicon-accordion';
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
 // 'title' =>'We collect reviews from our customers so you can get an honest opinion of what an apartment is really like!',


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
				'label' => __( 'Order by', 'plugin-domain' ),
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
				'label' => __( 'Order', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' =>  __( 'Descending', 'truelysell_elementor' ),
					'ASC' =>  __(  'Ascending. ', 'truelysell_elementor' ),
				
					
				],
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
				'hide_avatar',
				[
					'label' => __( 'Hide User photo', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);
			$this->add_control(
				'hide_job',
				[
					'label' => __( 'Hide User position', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);	
			$this->add_control(
				'hide_username',
				[
					'label' => __( 'Hide User name', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);

		$this->end_controls_section();
		/* Add the options you'd like to show in this tab here */
 
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
		 

		$args = array(
            'post_type' => 'testimonial',
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

     
      

        $i = 0;

        $wp_query = new \WP_Query( $args ); ?>
		

			<?php if ( $wp_query->have_posts() ) { ?>
				 
				<div class="row">
					<div class="col-md-12">
						<div class="owl-carousel testimonial-slider">
    			
				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
	                    $id = $wp_query->post->ID;
	                    $company = get_post_meta($id, 'truelysell_pp_company', true); ?>
	                    <!-- Item -->
						<div class="client-widget aos">
						<?php if($settings['hide_avatar'] != "yes") { ?>
						        <div class="client-img">
									<?php 
									if ( has_post_thumbnail() ) {
										if(the_post_thumbnail()!='') {  echo the_post_thumbnail();  }
									}
									else { 
									 ?>
									 <img src="<?php echo get_avatar_url( get_current_user_id(), array( 'size' => 110 ) ); ?>" class="img-fluid">
									<?php } ?>
								</div>
							<?php }  else { ?>
								<?php } ?>
 							<div class="testimonial-box">
								<div class="testimonial"><?php the_content();  ?></div>
							</div>
							<div class="testimonial-author">
								<?php if($settings['hide_avatar'] != "yes") { //the_post_thumbnail(); 
								} ?>
								<h4><?php if($settings['hide_username'] != "yes") {  the_title();  } ?><?php if($settings['hide_job'] != "yes") { ?><span><?php echo $company; ?></span><?php } ?></h4>
							</div>
 						</div>

	            <?php 	endwhile;  // close the Loop   ?>
			</div>
			</div>
			</div>
 		<?php } else {
			//do_action( "woocommerce_shortcode_{$loop_name}_loop_no_results" );
		}
        ?>
  	
    
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
			$posts = get_posts( array( 'numberposts' => -1, 'post_type' => 'testimonial') );

			$options = [ '' => '' ];
			
			if ( !empty($posts) ) :
				foreach ( $posts as $post ) {
					$options[ $post->ID ] = get_the_title($post->ID);
				}
			endif;

			return $options;
		}
	
}