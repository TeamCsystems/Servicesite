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
class TaxonomyCarousel extends Widget_Base {



	// public function __construct( $data = array(), $args = null ) {
	// 	parent::__construct( $data, $args );

	// 	wp_register_script( 'truelysell-taxonomy-carousel-elementor', plugins_url( '/assets/tax-carousel/tax-carousel.js', ELEMENTOR_TRUELYSELL ), array(), '1.0.0' );
	// }


	// public function get_script_depends() {
	// 	  $scripts = ['truelysell-taxonomy-carousel-elementor'];

	// 	  return $scripts;
	// }
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
		return 'truelysell-taxonomy-carousel';
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
		return __( 'Truelysell Taxonomy Carousel', 'truelysell_elementor' );
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
		return 'eicon-posts-carousel';
	}


	 // public function get_script_depends() {
	 //    return [ 'truelysell-taxonomy-carousel-script' ];
	 //  }
	    

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
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'truelysell_elementor' ),
			)
		);
// 	'taxonomy' => '',
			// 'xd' 	=> '',
			// 'only_top' 	=> 'yes',
			// 'autoplay'      => '',
   //          'autoplayspeed'      => '3000',
		
		$this->add_control(
			'taxonomy',
			[
				'label' => __( 'Taxonomy', 'truelysell_elementor' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_taxonomies(),
				
			]
		);

		$taxonomy_names = get_object_taxonomies( 'listing','object' );
		foreach ($taxonomy_names as $key => $value) {
			
			$this->add_control(
				$value->name.'_include',
				[
					'label' => __( 'Include listing from '.$value->label, 'truelysell_elementor' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'default' => [],
					'multiple' => true,
					'options' => $this->get_terms($value->name),
					'condition' => [
						'taxonomy' => $value->name,
					],
				]
			);
			$this->add_control(
				$value->name.'_exclude',
				[
					'label' => __( 'Exclude listings from '.$value->label, 'truelysell_elementor' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'default' => [],
					'multiple' => true,
					'options' => $this->get_terms($value->name),
					'condition' => [
						'taxonomy' => $value->name,
					],
				]
			);
		}

		$this->add_control(
			'number',
			[
				'label' => __( 'Terms to display', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 99,
				'step' => 1,
				'default' => 6,
			]
		);

		$this->add_control(
			'only_top',
			[
				'label' => __( 'Show only top terms', 'truelysell_elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
				
			]
		);


		$this->add_control(
			'show_counter',
			[
				'label' => __( 'Show listings counter', 'truelysell_elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
				
			]
		);

		$this->add_control(
			'counter_text',
			array(
				'label'   => __( 'Conter text', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'placeholder' => __( 'Saloons', 'truelysell_elementor' ),
				'default' => 'Listings',
				'condition' => [
					'show_counter' => [ 'yes'], 
				],
			)
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Auto Play', 'truelysell_elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
				
			]
		);


		$this->add_control(
			'autoplayspeed',
			array(
				'label'   => __( 'Auto Play Speed', 'truelysell_elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => __( 'Subtitle', 'truelysell_elementor' ),
				'min' => 1000,
				'max' => 10000,
				'step' => 500,
				'default' => 3000,
			)
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Style ', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Style 1', 'truelysell_elementor' ),
					///'alt' => __( 'Alternative', 'truelysell_elementor' ),
					'style2' => __( 'Style 2', 'truelysell_elementor' ),
					'style3' => __( 'Style 3', 'truelysell_elementor' ),
					'style4' => __( 'Style 4', 'truelysell_elementor' ),
					'style5' => __( 'Style 5', 'truelysell_elementor' ),

				],
			]
		);

		$this->add_control(
			'link',
			array(
				'label'   => __( 'Link', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-link.com', 'truelysell_elementor' ),
				'default' => '',
				'condition' => [
					'style' => [ 'style4','style5'], 
				],
			)
		);
   
		$this->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'VIEW ALL CATEGORIES',
				'condition' => [
					'style' => [ 'style4','style5'], 
					
				],
			)
		);

		
		// $taxonomy_names = get_object_taxonomies( 'listing','object' );
		// foreach ($taxonomy_names as $key => $value) {
		// 	$shortcode_atts[$value->name.'_include'] = '';
		// 	$shortcode_atts[$value->name.'_exclude'] = '';
		// }
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

		$taxonomy_names = get_object_taxonomies( 'listing','object' );
		
		$taxonomy = $settings['taxonomy'];
                
		$query_args = array(
			'include' => $settings[$taxonomy.'_include'],
			'exclude' => $settings[$taxonomy.'_exclude'],
			'hide_empty' => false,
			'number' => $settings['number'],
		);

		if($settings['only_top'] == 'yes'){
			$query_args['parent'] = 0;
		}
       	$terms = get_terms( $settings['taxonomy'],$query_args);
       	
       	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
       	?>
<?php if($settings['style']=='default'){ ?>
		<div class="fullwidth-slick-carousel category-carousel" <?php if($settings['autoplay'] == 'yes') { ?>data-slick='{"autoplay": true, "autoplaySpeed": <?php echo $settings['autoplayspeed']; ?>}' <?php } ?>>
			<!-- Item -->
			<?php foreach ( $terms as $term ) { 
				$cover_id 	= get_term_meta($term->term_id,'_cover',true);
				$cover 		= wp_get_attachment_image_src($cover_id,'truelysell-blog-post');
				?>
				<div class="fw-carousel-item">
					<div class="category-box-container">
						<a href="<?php echo esc_url(get_term_link( $term )); ?>" class="category-box" data-background-image="<?php echo $cover[0];  ?>">
							<div class="category-box-content">
								<h3><?php echo $term->name; ?></h3>
								<?php if($settings['show_counter'] == 'yes') {?><span><?php $count = truelysell_get_term_post_count( $settings['taxonomy'],$term->term_id);  echo $count ?> <?php echo $settings['counter_text']; ?></span><?php }  ?>
							</div>
							<span class="category-box-btn"><?php esc_html_e('Browse','truelysell_elementor') ?></span>
						</a>
					</div>
				</div>

			<?php } ?>
		</div>

 <?php } else if($settings['style']=='style2'){ ?>

	<div class="owl-carousel services-slider aos" data-aos="fade-up">
	<?php foreach ( $terms as $term ) { ?>

							<div class="services-all">
								<div class="services-main-img">
									<a href="<?php echo esc_url(get_term_link( $term )); ?>">
								<?php 
								$cover_id 	= get_term_meta($term->term_id,'_cover',true);
								if($cover_id) {
					            	$cover_idimage = wp_get_attachment_image_src($cover_id,'truelysell-blog-post');  ?>
						            <img  class="img-fluid serv-image" src="<?php echo $cover_idimage[0];  ?>">
					            <?php }   ?>
   									</a>
									<div class="service-foot">
									<?php 
					$cover_idicon = get_term_meta($term->term_id,'_covericon',true);
					if($cover_idicon) {
						$covercover_idicon_dis = wp_get_attachment_image_src($cover_idicon,'truelysell-blog-post');  ?>
						<img  src="<?php echo $covercover_idicon_dis[0];  ?>">
					<?php }   ?>
										<h4><?php echo $term->name; ?></h4>
 										<?php if($settings['show_counter'] == 'yes') {?>
										<h6><?php echo truelysell_get_term_post_count( $settings['taxonomy'],$term->term_id); ?> <?php echo $settings['counter_text']; ?></h6> 
									<?php }  ?>

									</div>
								</div>
							</div>
   <?php } ?>
	</div>
	<?php } else if($settings['style']=='style3'){ ?>
		<div class="owl-nav mynav mynav-seven"></div>
		<div class="owl-carousel categories-slider-seven common-seven-slider">
	<?php foreach ( $terms as $term ) { ?>

		<a href="<?php echo get_term_link( $term ); ?>" class="feature-box feature-box-seven aos" data-aos="fade-up">
								<div class="feature-icon feature-icon-seven">
									<span>

									<?php 
					$cover_idicon = get_term_meta($term->term_id,'_covericon',true);
					if($cover_idicon) {
						$cover_idicon = wp_get_attachment_image_src($cover_idicon,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover_idicon[0];  ?>" class="">
					<?php }   ?>

									

 									</span>
								</div>
								<h5><?php echo $term->name; ?></h5>
								<div class="feature-overlay">
									
								<?php 
					$cover_id = get_term_meta($term->term_id,'_cover',true);
					if($cover_id) {
						$cover_id = wp_get_attachment_image_src($cover_id,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover_id[0];  ?>" class="">
					<?php }   ?>

 								</div>
							</a>
   <?php } ?>
	</div>

	<?php } else if($settings['style']=='style4'){ ?>
 		<div class="owl-carousel category-eight-slider">
	<?php foreach ( $terms as $term ) { ?>

		<div class="category-eight-main">
								<div class="category-eight-img">
								<?php 
					$cover_id = get_term_meta($term->term_id,'_cover',true);
					if($cover_id) {
						$cover_id = wp_get_attachment_image_src($cover_id,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover_id[0];  ?>" class="">
					<?php }   ?>
					<div class="category-eight-img-inside">
									<a href="<?php echo get_term_link( $term ); ?>">
										<img src="<?php echo get_template_directory_uri(); ?>/assets/images/dog-feet.svg" alt="">
										 <?php esc_html_e('Read more','truelysell_elementor'); ?>
									</a>	
								</div>
								
								</div>
								
								<h6><?php echo $term->name; ?></h6>
								<?php if($settings['show_counter'] == 'yes') {?>
									<span><?php $count = truelysell_get_term_post_count( $settings['taxonomy'],$term->term_id);  echo $count ?> <?php echo $settings['counter_text']; ?>
								   </span>
								<?php }  ?>
 							</div>
    <?php } ?>
	</div>
	<?php if($settings['link']!='') { ?>
 <div class="btn-sec btn-saloons btn-pets aos" data-aos="fade-up">
					<a href="<?php echo $settings['link']; ?> " class="btn btn-primary btn-view"><?php echo $settings['label']; ?> </a>
 </div>
 <?php } ?>

 <?php } else if($settings['style']=='style5'){ ?>
 		<div class="owl-carousel service-nine-slider common-nine-slider">
	<?php foreach ( $terms as $term ) { ?>


		<div class="service-widget service-widget-nine aos" data-aos="fade-up">
								<div class="service-img">
									<a href="<?php echo get_term_link( $term ); ?>">
									<?php 
					$cover_id = get_term_meta($term->term_id,'_cover',true);
					if($cover_id) {
						$cover_id = wp_get_attachment_image_src($cover_id,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover_id[0];  ?>" class="">
					<?php }   ?>
									</a>
								</div>
								<div class="service-content service-feature-nine">
									<div class="shop-content-logo">
									<?php 
					$cover_idicon = get_term_meta($term->term_id,'_covericon',true);
					if($cover_idicon) {
						$cover_idicon = wp_get_attachment_image_src($cover_idicon,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover_idicon[0];  ?>" class="">
					<?php }   ?>
									</div>
 									<?php if($settings['show_counter'] == 'yes') {?>
									<span><?php $count = truelysell_get_term_post_count( $settings['taxonomy'],$term->term_id);  echo $count ?> <?php echo $settings['counter_text']; ?>
								   </span>
								<?php }  ?>

									<p><?php echo $term->name; ?></p>
								</div>
							</div>

		 
    <?php } ?>
	</div>
	<?php if($settings['link']!='') { ?>
  <div class="btn-sec btn-service-nine aos" data-aos="fade-up">
					<a href="<?php echo $settings['link']; ?>" class="btn btn-primary btn-view"><?php echo $settings['label']; ?></a>
 </div>

 <?php } ?>

	<?php } ?>

 		<?php }

	}

	
	protected function get_taxonomies() {
		$taxonomies = get_object_taxonomies( 'listing', 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function get_terms($taxonomy) {
		$taxonomies = get_terms( $taxonomy, array(
		    'hide_empty' => false,
		) );
		$options = [ '' => '' ];
		
		if ( !empty($taxonomies) ) :
			foreach ( $taxonomies as $taxonomy ) {
				$options[ $taxonomy->term_id ] = $taxonomy->name;
			}
		endif;

		return $options;
	}

}