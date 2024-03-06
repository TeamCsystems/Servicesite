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
class Truelysell_HomeHero5 extends Widget_Base {

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
		return 'truelysell-hero5';
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
		return __( 'Truelysell Hero 5', 'truelysell_elementor' );
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
				'default' => __( 'The Largest Professional Car Wash', 'truelysell_elementor' ),
			)
		);	
		
		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Sub-Title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search From 150 Awesome Verified Ads!', 'truelysell_elementor' ),
			)
		);

		$this->add_control(
			'content',
			array(
				'label'   => __( 'Content', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'We value your time and therefore washing your car at our car wash will take no more than 15 minutes.', 'truelysell_elementor' ),
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
				'label' => __( 'Banner Image Arrow', 'dreamslms_elementor' ),
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
			'tab_count',
			[
				'label' => __( 'Count', 'truelysell_elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '3000+', 'truelysell_elementor' ),
 				 
			]
		);

		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Title & Description', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Toggle Title', 'truelysell_elementor' ),
				'label_block' => true,
				 
			]
		);
		$repeater->add_control(
			'tab_icon',
			[
				'label' => esc_html__( 'Icon Class', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( "feather-users", "truelysell_elementor" ),
				'label_block' => true,
				 
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
						'tab_count' => esc_html__( '1430 k', 'truelysell_elementor' ),
						'tab_title' => esc_html__( 'Cars Washed', 'truelysell_elementor' ),
   					],
					[
						'tab_count' => esc_html__( '987 k', 'truelysell_elementor' ),
						'tab_title' => esc_html__( 'Happy Clients', 'truelysell_elementor' ),
 						 
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
<section class="hero-section-five" style="background-image: url(<?php echo $settings['background']['url']; ?>);">
<div class="container">
				<div class="home-banner home-banner-three">
					<div class="row align-items-center w-100">
						<div class="col-lg-6 col-md-12 mx-auto">
							<div class="section-search section-section-five">
								<h1><?php echo $settings['title']; ?></h1>
								<h4><?php echo $settings['subtitle']; ?></h4>
								<p><?php echo $settings['content']; ?>
								</p>
								<div class="search-box search-box-five">
								<?php echo do_shortcode('[truelysell_search_form action='.get_post_type_archive_link( 'listing' ).' source="home"  custom_class="main-search-form"]') ?> 
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="floating-img">
								<img src="<?php echo $settings['bannerimage']['url']; ?>" alt="" class="img-fluid rotate-img">
							</div>
							<div class="car-five-arrow-img">
								<img src="<?php echo $settings['bannerimagearrow']['url']; ?>" alt=""> 
							</div>
						</div>
					</div>
				</div>
				

			</div>
</section>
<section class="floating-five-main-top">
<div class="container">
<div class="floating-five-main">
 				<?php
			foreach ( $settings['tabs'] as $index => $item ) :
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

                             	
<div class="floating-five-buttom">
						<div class="floating-five-buttom-all">
							<h5><?php $this->print_unescaped_setting( 'tab_count', 'tabs', $index ); ?></h5>
							<p><?php $this->print_unescaped_setting( 'tab_title', 'tabs', $index ); ?></p>
						</div>
						<div class="floating-five-buttom-img">
							<i class="<?php $this->print_unescaped_setting( 'tab_icon', 'tabs', $index ); ?>"></i>
 						</div>
					</div>
 
							 
			<?php endforeach; ?>
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