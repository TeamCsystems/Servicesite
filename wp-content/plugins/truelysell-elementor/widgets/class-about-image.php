<?php
/**
 * Truelysell Elementor Address Box class.
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
class Trulesell_AbtImage extends Widget_Base {

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
		return 'truelysell-AbtImage';
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
		return __( 'Truelysell About Image', 'truelysell_elementor' );
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
		return 'eicon-featured-image';
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

		$this->add_control(
			'title1',
			array(
				'label'   => __( 'Title 1', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '12+', 'truelysell_elementor' ),
			)
		);	
		$this->add_control(
			'sub-title1',
			array(
				'label'   => __( 'Sub-Title 1', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Years Experience', 'truelysell_elementor' ),
			)
		);
		 
		$this->add_control(
			'title_image',
			[
				'label' => __( 'Title Image', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
 			]
		);

		$this->add_control(
			'title2',
			array(
				'label'   => __( 'Title 2', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Call us Today!', 'truelysell_elementor' ),
			)
		);	
		$this->add_control(
			'sub-title2',
			array(
				'label'   => __( 'Sub-Title 2', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '+012 345678', 'truelysell_elementor' ),
			)
		);

		$this->add_control(
			'url',
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
			'background',
			[
				'label' => __( 'Background Image', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

		$this->add_control(
			'background1',
			[
				'label' => __( 'Choose Image', 'truelysell_elementor' ),
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
		$target = $settings['url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['url']['nofollow'] ? ' rel="nofollow"' : '';
		
		?>



<div class="our-company-ryt">
							<div class="our-company-img">
								<img src="<?php echo esc_url($settings['background1']['url']); ?>" alt="image" class="img-fluid">
							</div>
							<div class="our-company-bg">
								<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="image" class="img-fluid">
							</div>
							<div class="our-company-first-content">
								<div class="company-top-content">
									<p><?php echo  $settings['sub-title2']; ?></p>
									<h3><?php echo  $settings['title2']; ?></h3>
								</div>	
								<a href="<?php echo $settings['url']['url']; ?>" <?php echo $target; ?> <?php echo $nofollow; ?>>
									<i class="feather-arrow-right"></i>
								</a>
							</div>
							<div class="our-company-two-content">
								<div class="company-two-top-content">
									<h4><?php echo  $settings['title1']; ?></h4>
									<img src="<?php echo esc_url($settings['title_image']['url']); ?>" alt="">
								</div>
								<p><?php echo  $settings['sub-title1']; ?></p>
							</div>
						</div>
 
		<?php
	}

}