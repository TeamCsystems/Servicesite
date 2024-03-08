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
class TS_Callaction extends Widget_Base {

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
		return 'truelysell-callaction';
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
		return __( 'Truelysell Call to Action', 'truelysell_elementor' );
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
		return 'eicon-call-to-action';
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
			'text_visible',
			array(
				'label'   => __( 'Title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Title', 'truelysell_elementor' ),
				'condition' => [
					'style' => [ 'default','style1','style2','style3','style4','style5'], 
					
				],
			)
		);	
		$this->add_control(
			'text_hidden',
			array(
				'label'   => __( 'Sub-title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Text after hover', 'truelysell_elementor' ),
				'condition' => [
					'style' => [ 'default','style1','style2','style3','style4','style5'], 
					
				],
			)
		);
		$this->add_control(
			'content',
			array(
				'label'   => __( 'Content', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Text after hover', 'truelysell_elementor' ),
				'condition' => [
					'style' => ['style1','style2','style3','style5'], 
					
				],
			)
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
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'style' => [ 'default','style1','style2','style3','style4','style5'], 
					
				],
			]
		);

		$this->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'BOOK AN APPOINTMENT NOW',
				'condition' => [
					'style' => [ 'default','style1','style2','style3','style4','style5'], 
					
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
					'default' => __( 'Default', 'truelysell_elementor' ),
 					'style1' => __( 'Style 1', 'truelysell_elementor' ),
					'style2' => __( 'Style 2', 'truelysell_elementor' ),
					'style3' => __( 'Style 3', 'truelysell_elementor' ),
					'style4' => __( 'Style 4', 'truelysell_elementor' ),
					'style5' => __( 'Style 5', 'truelysell_elementor' ),
				],
			]
		);


		$this->add_control(
			'background',
			[
				'label' => __( 'Choose Background Image', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'style!' => ['style5'], 
					
				],
			]
		);

		$this->add_control(
			'background1',
			[
				'label' => __( 'Choose Right Image', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'style' => ['style3'], 
					
				],
			]
		);
		$this->add_control(
			'background3',
			[
				'label' => __( 'Image 1', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'style' => ['style5'], 
					
				],
			]
		);
		$this->add_control(
			'background4',
			[
				'label' => __( 'Image 2', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'style' => ['style5'], 
					
				],
			]
		);


		$this->add_control(
			'color',
			[
				'label' => __( 'Overlay Color', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR, 
				'condition' => [
					'style' => [ 'default'], 
					
				],
				// 'selectors' => [
				// 	'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				// ],
			]
		);
		$this->add_control(
			'opacity',
			[
				'label' => __( 'Overlay Opacity', 'truelysell_elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [  '%' ],
				'range' => [
					
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 70,
				],
				'condition' => [
					'style' => [ 'default'], 
					
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
		
		$target = $settings['website_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['website_link']['nofollow'] ? ' rel="nofollow"' : '';
		if(!empty($settings['website_link']['url'])){
			$full_url = $settings['website_link']['url'];	
		} else {
			$full_url = '';
		}
		
		?>

		<!-- Flip banner -->

		<?php if($settings['style']=='default'){ ?>

		<a href="<?php echo $full_url; ?>" <?php echo $target; ?>  <?php echo $nofollow; ?> class="flip-banner parallax" data-background="<?php echo esc_url($settings['background']['url']); ?>" data-color="<?php echo esc_attr($settings['color']); ?>" data-color-opacity="<?php echo esc_attr($settings['opacity']['size']/100); ?>" data-img-width="2500" data-img-height="1600">
 			<div class="flip-banner-content">
				<h2 class="flip-visible"><?php echo esc_html($settings['text_visible']); ?></h2>
				<h2 class="flip-hidden"><?php echo esc_html($settings['text_hidden']); ?> <i class="sl sl-icon-arrow-right"></i></h2>
			</div>
		</a>
		<?php } else if($settings['style']=='style1'){ ?>

			<section class="appointment-section aos aos-init aos-animate" data-aos="fade-up"  style = "background-image:url(<?php echo esc_url($settings['background']['url']); ?>);" >
			<div class="container">
				<div class="appointment-main">
					<h6><?php echo esc_html($settings['text_hidden']); ?></h6>
					<h1><?php echo esc_html($settings['text_visible']); ?></h1>
					<p><?php echo esc_html($settings['content']); ?></p>
					<div class="appointment-btn">
						<a href="<?php echo $full_url; ?>" <?php echo $target; ?>  <?php echo $nofollow; ?>  class="btn btn-primary"><?php echo esc_attr($settings['label']); ?></a>
					</div>
				</div>
			</div>
		</section>
 <?php } else if($settings['style']=='style2'){ ?>
		<section class="register-section aos aos-init aos-animate" data-aos="fade-up"  style = "background-image:url(<?php echo esc_url($settings['background']['url']); ?>);">
			<div class="container">
			  	<div class="row">
					<div class="col-lg-12 col-12">
				  		<div class="register-content">
							<div>
							<?php if($settings['text_hidden']!='') { ?> 
								<h6><?php echo esc_html($settings['text_hidden']); ?></h6>
						   <?php } ?>
							<h4><?php echo esc_html($settings['text_visible']); ?></h4>
							
							<?php if($settings['content']!='') { ?> 
								<p><?php echo esc_html($settings['content']); ?></p>
						   <?php } ?>
							</div>
						  
							<div class="register-btn">
								<a href="<?php echo $full_url; ?>" <?php echo $target; ?>  <?php echo $nofollow; ?> ><i class="feather-users me-2"></i><?php echo esc_attr($settings['label']); ?></a>
 							</div>
				  		</div>
					</div>
			 	 </div>
			</div>
		  </section>

 <?php } else if($settings['style']=='style3'){ ?>

	<div class="offering-five-all">
					<div class="offering-five-all-img">
						<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="">
					</div>
					<div class="row">
						<div class="col-lg-8 col-md-12">
							<div class="offering-five-main">
								<h2><?php echo esc_html($settings['text_visible']); ?></h2>
								<?php if($settings['content']!='') { ?> 
								<p><?php echo esc_html($settings['content']); ?></p>
						   <?php } ?>
								<div class="offering-five-button">
									<a href="<?php echo $full_url; ?>" <?php echo $target; ?> class="btn btn-primary"><?php echo esc_attr($settings['label']); ?><i class="feather-arrow-right-circle"></i> </a>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12">
							<div class="offering-five-img">
								<img src="<?php echo esc_url($settings['background1']['url']); ?>" alt="">
							</div>
						</div>
					</div>
				</div>
				
 <?php } else if($settings['style']=='style4'){ ?>
	<div class="passion-eight-content">
								<div class="passion-content-top">
									<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="">
									<div class="passion-content-bottom">
										<h2><?php echo esc_html($settings['text_visible']); ?></h2>
										<p><?php echo esc_html($settings['text_hidden']); ?></p>
									</div>
									<a href="<?php echo $full_url; ?>" <?php echo $target; ?>  <?php echo $nofollow; ?> class="btn btn-primary"><?php echo esc_attr($settings['label']); ?></a>
								</div>
 </div>
 <?php } else if($settings['style']=='style5'){ ?>

	<div class="free-service-all">
					<div class="row aos" data-aos="fade-up">
						<div class="col-lg-6 col-12">
							<div class="free-service-nine">
								<div class="free-service-img-one">
									<img src="<?php echo esc_url($settings['background3']['url']); ?>" alt="">
								</div>
								<div class="free-service-img-two">
									<img src="<?php echo esc_url($settings['background4']['url']); ?>" alt="">
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-12">
							<div class="free-service-bottom-content">
								<div class="section-heading section-heading-nine free-heading-nine aos"
									data-aos="fade-up">
									<p><?php echo esc_html($settings['text_hidden']); ?></p>
									<h2><?php echo esc_html($settings['text_visible']); ?></h2>
								</div>
								<p>Lorem Ipsum is simply dummy text of the printing and
									ypesetting industry. Lorem Ipsum has been the industry's standard.</p>
								<a href="<?php echo $full_url; ?>" <?php echo $target; ?>  <?php echo $nofollow; ?> class="btn btn-primary"><?php echo esc_attr($settings['label']); ?></a>
							</div>
						</div>
					</div>
				</div>
  <?php } ?>
		<?php
	}




}