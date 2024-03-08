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
class TaxonomyGrid extends Widget_Base {

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
		return 'truelysell-taxonomy-grid';
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
		return __( 'Truelysell Taxonomy Grid', 'truelysell_elementor' );
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
					'multiple' => true,
					'default' => [],
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
					'multiple' => true,
					'default' => [],
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
				'max' => 199,
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
			'website_link',
			[
				'label' => __( 'Link','truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'truelysell_elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
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

	
		$target = $settings['website_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['website_link']['nofollow'] ? ' rel="nofollow"' : '';
		if(!empty($settings['website_link']['url'])){
			$full_url =  $settings['website_link']['url'];	
		} else {
			$full_url = '';
		}

		$taxonomy_names = get_object_taxonomies( 'listing','object' );
		
		$taxonomy = $settings['taxonomy'];

        
	      if(empty($taxonomy)){
	      	$taxonomy = "listing_category";
	      }
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
		<div class="categories-boxes-container <?php ///if($settings['style']=='alt'){ echo "-alt"; } ?> margin-top-5 margin-bottom-30">
			<div class="row">
			<!-- Item -->
			<?php 
      		foreach ( $terms as $term ) { 
		        $t_id = $term->term_id;
		      
				// retrieve the existing value(s) for this meta field. This returns an array
				$icon = get_term_meta($t_id,'icon',true);
				$_icon_svg = get_term_meta($t_id,'_icon_svg',true);
				$_icon_svg_image = wp_get_attachment_image_src($_icon_svg,'medium');
		        if(empty($icon)) {
					$icon = 'fa fa-globe' ;
		        }
		   
		        ?>
				<div class="col-md-6  col-sm-6  col-lg-3 ">
				<a href="<?php echo get_term_link( $term ); ?>" class="feature-box aos aos-init aos-animate" data-aos="fade-up">
				<div class="feature-icon">
								<span>
 									<?php 
					$cover_idicon = get_term_meta($term->term_id,'_covericon',true);
					if($cover_idicon) {
						$cover_idicon = wp_get_attachment_image_src($cover_idicon,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover_idicon[0];  ?>">
					<?php }   ?>

								</span>
							</div>
							<h5><?php echo $term->name; ?></h5>
							<div class="feature-overlay">
							<?php 
					$cover_id = get_term_meta($term->term_id,'_cover',true);
					if($cover_id) {
						$cover = wp_get_attachment_image_src($cover_id,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover[0];  ?>">
					<?php }   ?>
							</div>
			</a>
				</div>
			 

			<?php } ?>
			</div>
		</div>
		<?php } else if($settings['style']=='style2'){ ?>
			<div class="row">
  			<?php 
      		foreach ( $terms as $term ) { 	
		        $t_id = $term->term_id;
		      
				// retrieve the existing value(s) for this meta field. This returns an array
				$icon = get_term_meta($t_id,'icon',true);
				$_icon_svg = get_term_meta($t_id,'_icon_svg',true);
				$_icon_svg_image = wp_get_attachment_image_src($_icon_svg,'medium');
		        if(empty($icon)) {
					$icon = 'fa fa-globe' ;
		        }
		   
		        ?>

<div class="col-md-6 col-lg-4">
						<div class="feature-widget">
							<div class="feature-img">
								<a href="<?php echo get_term_link( $term ); ?>">
								<?php 
					$cover_idicon = get_term_meta($term->term_id,'_cover',true);
					if($cover_idicon) {
						$cover_idicon = wp_get_attachment_image_src($cover_idicon,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover_idicon[0];  ?>">
					<?php }   ?>
								</a>
							</div>
							<div class="feature-icon">
								<span>
 								<?php 
					$cover_id = get_term_meta($term->term_id,'_covericon',true);
					if($cover_id) {
						$cover = wp_get_attachment_image_src($cover_id,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover[0];  ?>">
					<?php }   ?>
   								</span>
								<div class="feature-title">
									<h5><?php echo $term->name; ?></h5>
 
									<?php if($settings['show_counter'] == 'yes') {?>
										<p><?php echo truelysell_get_term_post_count( $settings['taxonomy'],$term->term_id); ?> <?php echo $settings['counter_text']; ?></p> 
									<?php }  ?>

								</div>
							</div>
						</div>
					</div>
 			<?php } ?>
  </div>
 <?php } else if($settings['style']=='style3'){ ?>

					<div class="row">
  			<?php 
      		foreach ( $terms as $term ) { 	
		        $t_id = $term->term_id;
		      
				// retrieve the existing value(s) for this meta field. This returns an array
				$icon = get_term_meta($t_id,'icon',true);
				$_icon_svg = get_term_meta($t_id,'_icon_svg',true);
				$_icon_svg_image = wp_get_attachment_image_src($_icon_svg,'medium');
		        if(empty($icon)) {
					$icon = 'fa fa-globe' ;
		        }
		   
		        ?>

<div class="col-lg-2 col-md-4 col-sm-4 col-12">
					<a href="<?php echo get_term_link( $term ); ?>">
					<div class="categories-main-all">
						<div class="categories-img">
							<span>
							<?php 
					$cover_id = get_term_meta($term->term_id,'_covericon',true);
					if($cover_id) {
						$cover = wp_get_attachment_image_src($cover_id,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover[0];  ?>">
					<?php }   ?>
							</span>
						</div>
						<h6><?php echo $term->name; ?></h6>
						<span class="category-bottom">
							<i class="feather-chevron-right "></i>
						</span>
					</div>
					</a>
				</div>
  			<?php } ?>
			<?php if($full_url!='') { ?>
			  <div class="col-lg-12 col-md-12 col-sm-12 col-12">
			  <div class="btn-sec btn-catering aos aos-init aos-animate" data-aos="fade-up">
					<a href="<?php echo $full_url; ?>" <?php echo  $target; ?>  <?php echo  $nofollow;?> class="btn btn-primary btn-view"><?php echo $settings['label']; ?><i class="feather-arrow-right"></i></a>
				</div>
			  </div>
			  <?php } ?>
 </div>

<?php } else if($settings['style']=='style4'){ ?>

<div class="row">
<?php 
foreach ( $terms as $term ) { 	
$t_id = $term->term_id;

// retrieve the existing value(s) for this meta field. This returns an array
$icon = get_term_meta($t_id,'icon',true);
$_icon_svg = get_term_meta($t_id,'_icon_svg',true);
$_icon_svg_image = wp_get_attachment_image_src($_icon_svg,'medium');
if(empty($icon)) {
$icon = 'fa fa-globe' ;
}

?>

<div class="col-lg-4 col-md-6 col-12">

<div class="service-widget service-two aos" data-aos="fade-up">
							<div class="service-img service-four-img">
 								<a href="<?php echo get_term_link( $term ); ?>">
								<?php 
					$cover_idicon = get_term_meta($term->term_id,'_cover',true);
					if($cover_idicon) {
						$cover_idicon = wp_get_attachment_image_src($cover_idicon,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover_idicon[0];  ?>" class="img-fluid serv-img">
					<?php }   ?>
								</a>
							</div>
							<div class="service-content service-content-five">
								<div class="feature-content-bottom">
									<p><?php echo $term->name; ?></p>
 									<?php if($settings['show_counter'] == 'yes') {?>
										<a href="javascript:void(0);"><i class="feather-users me-2"></i><?php echo truelysell_get_term_post_count( $settings['taxonomy'],$term->term_id); ?></p> 
									<?php }  ?></a>

								</div>
							</div>
						</div>

 
</div>
<?php } ?>
<?php if($full_url!='') { ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-12">
<div class="btn-sec btn-catering aos aos-init aos-animate" data-aos="fade-up">
<a href="<?php echo $full_url; ?>" <?php echo  $target; ?>  <?php echo  $nofollow;?> class="btn btn-primary btn-view"><?php echo $settings['label']; ?><i class="feather-arrow-right"></i></a>
</div>
</div>
<?php } ?>
</div>

<?php } else if($settings['style']=='style5'){ ?>

<div class="row">
<?php 
foreach ( $terms as $term ) { 	
$t_id = $term->term_id;

// retrieve the existing value(s) for this meta field. This returns an array
$icon = get_term_meta($t_id,'icon',true);
$_icon_svg = get_term_meta($t_id,'_icon_svg',true);
$_icon_svg_image = wp_get_attachment_image_src($_icon_svg,'medium');
if(empty($icon)) {
$icon = 'fa fa-globe' ;
}

?>

<div class="col-lg-3 col-md-4 col-sm-6 col-12">
						<div class="get-service-main">
							<span>
 
								<?php 
					$cover_idicon = get_term_meta($term->term_id,'_covericon',true);
					if($cover_idicon) {
						$cover_idicon = wp_get_attachment_image_src($cover_idicon,'truelysell-blog-post');  ?>
						<img src="<?php echo $cover_idicon[0];  ?>" class="">
					<?php }   ?>

							</span>
							<a href="<?php echo get_term_link( $term ); ?>"><h5><?php echo $term->name; ?></h5></a>
							<div class="get-service-btn">
								<a href="<?php echo get_term_link( $term ); ?>">
									<?php echo esc_html_e('View More','truelysell_elementor'); ?> <i class="ms-2 feather-chevron-right"></i>
								</a>
							</div>
						</div>
					</div>

 <?php } ?>
 
</div>
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
		$taxonomies = get_terms( array( 'taxonomy' =>$taxonomy,'hide_empty' => false) );

		$options = [ '' => '' ];
		
		if ( !empty($taxonomies) ) :
			foreach ( $taxonomies as $taxonomy ) {
				$options[ $taxonomy->term_id ] = $taxonomy->name;
			}
		endif;

		return $options;
	}

}