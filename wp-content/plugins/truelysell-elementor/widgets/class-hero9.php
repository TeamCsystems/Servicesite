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
class Truelysell_HomeHero9 extends Widget_Base {

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
		return 'truelysell-hero9';
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
		return __( 'Truelysell Hero 9', 'truelysell_elementor' );
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
				'default' => __( 'Providing A Professional Reliable service', 'truelysell_elementor' ),
			)
		);	
		
		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Sub-Title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'welcome to truely sell Mechanic', 'truelysell_elementor' ),
			)
		);
		$this->add_control(
			'content',
			array(
				'label'   => __( 'Content', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Our professional cleaning service comes up with a complete solution that makes your space sparkle!', 'truelysell_elementor' ),
			)
		);
		$this->add_control(
			'link',
			array(
				'label'   => __( 'Link', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-link.com', 'truelysell_elementor' ),
				'default' => '',
				 
			)
		);
   
		$this->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Book Appointment',
 			)
		);

		

		$this->add_control(
			'background',
			[
				'label' => __( 'User Image', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

	
		$this->add_control(
			'usertext1',
			array(
				'label'   => __( 'Text 1', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Got a question about our service?',
 			)
		);
		$this->add_control(
			'usertext2',
			array(
				'label'   => __( 'Text 2', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Call us: 092837644',
 			)
		);
		 
		$this->end_controls_section();
		$this->start_controls_section(
			'section_toggle',
			[
				'label' => esc_html__( 'Toggle', 'truelysell_elementor' ),
			]
		);
 		$repeater = new \Elementor\Repeater();

		 $repeater->add_control(
			'tab_title',
			array(
				'label'   => __( 'Class', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'shape-1', 'truelysell_elementor' ),
			)
		);	

		
		$repeater->add_control(
			'bannerimage',
			[
				'label' => __( 'Banner Image', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);
 

 		$this->add_control(
			'tabs',
			[
				'label' => esc_html__( 'Toggle Items', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
 						'tab_title' => esc_html__( 'shape-1', 'truelysell_elementor' ),
    					],
					[
 						'tab_title' => esc_html__( 'shape-3', 'truelysell_elementor' ),
  						 
					],
					[
 						'tab_title' => esc_html__( 'shape-3', 'truelysell_elementor' ),
  						 
					],
					[
 						'tab_title' => esc_html__( 'shape-2', 'truelysell_elementor' ),
  						 
					] 
 				],
				'title_field' => '{{{ tab_title }}}',
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
		$id_int = substr( $this->get_id_int(), 0, 3 );
		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'subtitle', 'none' );
  		?>

<!-- Banner ================================================== -->

<section class="hero-section-nine">
			<div class="container">
				<div class="home-banner home-banner-nine">
					<div class="row align-items-center w-100">
						<div class="col-lg-6">

							<div class="banner-imgs banner-imgs-nine">

							<?php foreach ( $settings['tabs'] as $index => $item ) :
				$tab_count = $index + 1;
				$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );
				$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );
				$this->add_render_attribute( $tab_title_setting_key, [
					'id' => 'elementor-tab-title-' . $id_int . $tab_count,
					'class' => [ 'elementor-tab-title' ],
					'data-tab' => $tab_count,
					'role' => 'tab',
					'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
					'aria-expanded' => 'false',
				] );
				$this->add_render_attribute( $tab_content_setting_key, [
					'id' => 'elementor-tab-content-' . $id_int . $tab_count,
					'class' => [ 'elementor-tab-content', 'elementor-clearfix' ],
					'data-tab' => $tab_count,
					'role' => 'tabpanel',
					'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
				] );
				$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
			 
				?>
 
 <div class="banner-<?php echo $tab_count; ?> <?php $this->print_unescaped_setting( 'tab_title', 'tabs', $index ); ?>">
  		 <img class="img-fluid" alt="banner" src="<?php Utils::print_unescaped_internal_string( $this->parse_text_editor( $item['bannerimage']['url']) ); ?>">
 </div>
 <?php endforeach; ?>	

 							</div>
						</div>
						<div class="col-lg-6 col-md-10 mx-auto">
							<div class="section-search section-search-nine aos" data-aos="fade-up">
								<div class="arrow-ryt-all">
									<h6><?php echo $settings['subtitle']; ?></h6>
 								</div>
								<h1><?php echo $settings['title']; ?></h1>
								<p><?php echo $settings['content']; ?></p>
								<a href="<?php echo $settings['link']; ?>" class="btn btn-primary appoints-btn"><?php echo $settings['label']; ?></a>
								<div class="banner-appointment-nine">
									<img src="<?php echo $settings['background']['url']; ?>" alt="">
									<div class="banner-appointment-content">
										<p><?php echo $settings['usertext1']; ?></p>
										<h5><?php echo $settings['usertext2']; ?></h5>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
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