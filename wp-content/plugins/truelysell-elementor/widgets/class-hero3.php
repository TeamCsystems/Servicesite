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
class Truelysell_HomeHero3 extends Widget_Base {

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
		return 'truelysell-hero3';
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
		return __( 'Truelysell Hero 3', 'truelysell_elementor' );
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
				'default' => __( 'Find the Awesome Saloon&#39;s Near you', 'truelysell_elementor' ),
			)
		);	

		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Sub-Title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Best Saloon Template', 'truelysell_elementor' ),
			)
		);

		$this->add_control(
			'content',
			array(
				'label'   => __( 'Content', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Hair and SPA Salons and fully integrated with theme tools that you can use for the promotion of your business.', 'truelysell_elementor' ),
			)
		);

		$this->add_control(
			'button',
			array(
				'label'   => __( 'Label', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'MAKE AN APPOINTMENT', 'truelysell_elementor' ),
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
				'label' => __( 'Background', 'dreamslms_elementor' ),
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

		$target = $settings['url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['url']['nofollow'] ? ' rel="nofollow"' : '';

		?>

<!-- Banner
================================================== -->
     <section class="hero-section-three" style="background-image:url(<?php echo $settings['background']['url']; ?>);">			
			<div class="container">
				<div class="home-banner home-banner-three aos" data-aos="fade-up">
					<div class="row align-items-center w-100">
						<div class="col-lg-6 col-md-12 mx-auto">
							<div class="section-search section-section-three">
								<h4><?php echo $settings['subtitle']; ?></h4>
								<h1><?php echo $settings['title']; ?></h1>
								<p><?php echo $this->get_settings( 'content' ) ?></p>
								<a  href="<?php echo $settings['url']['url']; ?>" <?php echo $target; ?> <?php echo $nofollow; ?> class="make-appointment"><?php echo $settings['button']; ?></a>
							</div>
						</div>
						<div class="col-lg-6">
						</div>
					</div>
				</div>	
				<div class="search-box search-box-three aos" data-aos="fade-up">
				<?php echo do_shortcode('[truelysell_search_form action='.get_post_type_archive_link( 'listing' ).' source="home"  custom_class="main-search-form"]') ?>
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