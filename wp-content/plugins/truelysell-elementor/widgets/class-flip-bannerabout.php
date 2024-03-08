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
class FlipBannerAbout extends Widget_Base {

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
		return 'truelysell-flip-bannerabout';
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
		return __( 'Truelysell About Us Banner', 'truelysell_elementor' );
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
			'style',
			[
				'label' => __( 'Style ', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Style 1', 'truelysell_elementor' ),
 					'style2' => __( 'Style 2', 'truelysell_elementor' ),
  				],
			]
		);
		

		$this->add_control(
			'text_visible',
			array(
				'label'   => __( 'Title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Title', 'truelysell_elementor' ),
			)
		);	
		$this->add_control(
			'text_hidden',
			array(
				'label'   => __( 'Flipped text', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Text after hover', 'truelysell_elementor' ),
				'condition' => [
					'style' => [ 'default'], 
					
				],
			)
		);

	 

		$this->add_control(
			'background',
			[
				'label' => __( 'Choose Image', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);
  
		$this->add_control(
			'background1',
			[
				'label' => __( 'Background', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
 				'condition' => [
					'style' => [ 'style2'], 
 				],
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
		 ?>


<?php if($settings['style']=='default'){ ?>
<div class="about-img">
<div class="about-exp">
<span><?php echo esc_html($settings['text_hidden']); ?> </span>
</div>
<div class="abt-img">
<img src="<?php echo esc_url($settings['background']['url']); ?>" class="img-fluid">
</div>
</div>
<?php } if($settings['style']=='style2'){  ?>

	<div class="about-eight-main">
							<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="">
							<?php if($settings['background1']!='') { ?> 
							<div class="truely-eight-bg">
								<img src="<?php echo esc_url($settings['background1']['url']); ?>" alt="" class="img-fluid">
							</div>
							<div class="truely-eight-bg-two">
								<img src="<?php echo esc_url($settings['background1']['url']); ?>" alt="">
							</div>
							<?php } ?>
						</div>
 <?php } ?>
		<?php
	}

}