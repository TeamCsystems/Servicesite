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
class Truelysell_HomeHero6 extends Widget_Base {

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
		return 'truelysell-hero6';
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
		return __( 'Truelysell Hero 6', 'truelysell_elementor' );
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
				'default' => __( 'Professional Cleaning Service <span>You Can Trust</span>', 'truelysell_elementor' ),
			)
		);	
		
		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Sub-Title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'AFFORDABLE & RELIABLE', 'truelysell_elementor' ),
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
			'background',
			[
				'label' => __( 'Background', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

		$this->add_control(
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
			'bannerimagearrow',
			[
				'label' => __( 'Banner Title Image', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);
		$this->add_control(
			'bannersideimage',
			[
				'label' => __( 'Banner Side Image', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
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
			[
				'label' => __( 'Element tag ', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'fa-google' => __( 'Google', 'truelysell_elementor' ),
					'fa-facebook-f' => __( 'Facebook', 'truelysell_elementor' ),
					'fa-linkedin' => __( 'Linkedin', 'truelysell_elementor' ),
					'fa-instagram' => __( 'Instagram', 'truelysell_elementor' ),
					'fa-twitter' => __( 'Twitter', 'truelysell_elementor' ),
					'fa-whatsapp' => __( 'whatsapp', 'truelysell_elementor' ),
				], 
			]
		);
	 
 		$repeater->add_control(
			'tab_website_link',
			[
				'label' => __( 'Link','truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-link.com', 'truelysell_elementor' ),
				 
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
 						'tab_title' => esc_html__( 'Google', 'truelysell_elementor' ),
   					],
					[
 						'tab_title' => esc_html__( 'Google', 'truelysell_elementor' ),
 						 
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

<!-- Banner
================================================== -->
<section class="hero-section-six">

<div class="container">
    <div class="side-social-media-six">
    <ul>
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

				<li><a href="<?php  $this->print_unescaped_setting( 'tab_website_link', 'tabs', $index ); 
 				?>"><i class="fa-brands <?php $this->print_unescaped_setting( 'tab_title', 'tabs', $index ); ?>"></i></a>
				</li>
		<?php endforeach; ?>	 
    </ul>
    </div>
				<div class="home-banner home-banner-six">
					<div class="row w-100 justify-content-center ">
						<div class="col-lg-6 col-12">
							<div class="home-banner-main">
								<img src="<?php echo $settings['bannerimage']['url']; ?>" alt="Image" class="img-fluid">
								
								<div class="home-banner-six-bg">
									<img src="<?php echo $settings['background']['url']; ?>" alt="">
								</div>
							</div>

						</div>
						<div class="col-lg-6 col-12">
							<div class="home-banner-six-bottom">
								<div class="home-banner-six-detergent">
									<img src="<?php echo $settings['bannerimagearrow']['url']; ?>" alt="" class="img-fluid">
									<h5><?php echo $settings['subtitle']; ?></h5>
								</div>
								<h1><?php echo $settings['title']; ?></h1>
								<p><?php echo $settings['content']; ?></p>
								<div class="search-box-two search-box-six">
								<?php echo do_shortcode('[truelysell_search_form action='.get_post_type_archive_link( 'listing' ).' source="home"  custom_class="main-search-form"]') ?> 

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="banner-six-side-img">
				<img src="<?php echo $settings['bannersideimage']['url']; ?>" alt="">
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