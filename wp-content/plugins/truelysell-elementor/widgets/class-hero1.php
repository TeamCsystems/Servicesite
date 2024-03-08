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
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class Truelysell_HomeHero1 extends Widget_Base {

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
		return 'truelysell-hero1';
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
		return __( 'Truelysell Hero 1', 'truelysell_elementor' );
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
		return 'eicon-slides';
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
	protected function register_controls() {
 // 'title' 		=> 'Service Title',
	// 	    'url' 			=> '',
	// 	    'url_title' 	=> '',

	// 	   	'icon'          => 'im im-icon-Office',
	// 	    'type'			=> 'box-1', // 'box-1, box-1 rounded, box-2, box-3, box-4'
	// 	    'with_line' 	=> 'yes',
	// 	    
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Professional Services For Your Home & Commercial', 'truelysell_elementor' ),
			)
		);	

		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Sub-Title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search From 100 Awesome Verified Ads', 'truelysell_elementor' ),
			)
		);

		$this->add_control(
			'shape_1',
			[
				'label' => __( 'Choose shape 1', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

		$this->add_control(
			'shape_2',
			[
				'label' => __( 'Choose shape 2', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

		$this->add_control(
			'shape_3',
			[
				'label' => __( 'Choose shape 3', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

		$this->add_control(
			'shape_4',
			[
				'label' => __( 'Choose shape 4', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
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



		?>

<!-- Banner
================================================== -->
	

<section class="hero-section">
 				<div class="container">
					<div class="home-banner">
					<div class="row align-items-center w-100">
						<div class="col-lg-7 col-md-10 mx-auto">
							<div class="section-search aos aos-init aos-animate" data-aos="fade-up">
							 
								<h1><?php echo $settings['title']; ?></h1>
 
							 
								<p><?php  echo $settings['subtitle']; ?></p>
							 
									 
								<div class="search-box">
									<?php echo do_shortcode('[truelysell_search_form action='.get_post_type_archive_link( 'listing' ).' source="home"  custom_class="main-search-form"]') ?>
								</div>
							</div>
						</div>

						<div class="col-lg-5">
							<div class="banner-imgs">
							<?php if(isset($settings['shape_1']['url']) && !empty(isset($settings['shape_1']['url']))){ ?>
								<div class="banner-1 shape-1">
									<img class="img-fluid" alt="banner" src="<?php echo esc_url($settings['shape_1']['url']); ?>">
								</div>
								<?php } ?>
								<?php if(isset($settings['shape_2']['url']) && !empty(isset($settings['shape_2']['url']))){ ?>
								<div class="banner-2 shape-3">
								<img class="img-fluid" alt="banner" src="<?php echo esc_url($settings['shape_2']['url']); ?>">
								</div>
								<?php } ?>
								<?php if(isset($settings['shape_3']['url']) && !empty(isset($settings['shape_3']['url']))){ ?>
								<div class="banner-3 shape-3">
								<img class="img-fluid" alt="banner" src="<?php echo esc_url($settings['shape_3']['url']); ?>">
								</div>
								<?php } ?>
								<?php if(isset($settings['shape_4']['url']) && !empty(isset($settings['shape_4']['url']))){ ?>
								<div class="banner-4 shape-2">
								<img class="img-fluid" alt="banner" src="<?php echo esc_url($settings['shape_4']['url']); ?>">
								</div>
								<?php } ?>
							</div>
						</div>
						
					</div>
					</div>
				</div>
		</section>
 <div class="home-search-carousel-placeholder"><div class="home-search-carousel-loader"></div></div>
		<?php
		
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