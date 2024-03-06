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
class Truelysell_HowItWorks extends Widget_Base {

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
		return 'eicon-flip-box';
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
		return __( 'Truelysell Box Style', 'truelysell_elementor' );
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
		return ' eicon-flip-box';
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
			'title',
			array(
				'label'   => __( 'Title', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Title', 'truelysell_elementor' ),
			)
		);	
		$this->add_control(
			'ctext',
			array(
				'label'   => __( 'Counter text', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '1', 'truelysell_elementor' ),
				'condition' => [
					'style!' => [ 'style1','style3','style6','style9','style10'], 
				],
			)
		);
		
		$this->add_control(
			'aclass',
			array(
				'label'   => __( 'Class', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
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
					'style' => [ 'style5'], 
				],
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' =>  __( 'Style 1', 'truelysell_elementor' ),
					'style2' =>  __( 'Style 2', 'truelysell_elementor' ),
					'style3' =>  __(  'Style 3', 'truelysell_elementor' ),
					'style4' =>  __(  'Style 4', 'truelysell_elementor' ),
					'style5' =>  __(  'Style 5', 'truelysell_elementor' ),
					'style6' =>  __(  'Style 6', 'truelysell_elementor' ),
					'style7' =>  __(  'Style 7', 'truelysell_elementor' ),
					'style8' =>  __(  'Style 8', 'truelysell_elementor' ),
					'style9' =>  __(  'Style 9', 'truelysell_elementor' ),
					'style10' =>  __(  'Style 10', 'truelysell_elementor' ),
 				
					
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
					'style!' => [ 'style3'], 
				],
			]
		);

		

		$this->add_control(
			'content',
			array(
				'label'   => __( 'Content', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Title', 'truelysell_elementor' ),
				'condition' => [
					'style!' => [ 'style6','style7','style9'], 
				],
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
if($settings['website_link']!='') {
		$target = $settings['website_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['website_link']['nofollow'] ? ' rel="nofollow"' : '';
	}
 		if(!empty($settings['website_link']['url'])){
			$full_url =  $settings['website_link']['url'];	
		} else {
			$full_url = '';
		}
 		 ?>

		

		<!-- Flip banner -->
		 

		 
			<?php if($settings['style']=='style1') { ?>

				<div class="work-wrap-box  <?php echo $settings['aclass']; ?> aos aos-init aos-animate" data-aos="fade-up">
							<div class="work-icon">
								<span>
									<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="img">
								</span>
							</div>
							<h5><?php echo esc_html($settings['title']); ?></h5>
							<p><?php echo  $settings['content']; ?></p>
				 </div>

    
		    <?php } else if($settings['style']=='style2') { ?>
				 

				<div class="works-main aos aos-init aos-animate" data-aos="fade-right">
							<div class="works-tops">
								<div class="works-top-img ">
									<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="">
									<span><?php echo  $settings['ctext']; ?></span>
								</div>
							</div>
							<div class="works-bottom">
								<a href="javascript:void(0);"><h2><?php echo esc_html($settings['title']); ?></h2></a>
								<p><?php echo  $settings['content']; ?></p>
							</div>
						</div>

 <?php } else if($settings['style']=='style3') { ?>
				 

	<div class="trust-us-main">
						<div class="trust-us-img">
						<?php echo $settings['aclass']; ?> 
						</div>
						<h6><?php echo  $settings['title']; ?></h6>
						<p><?php echo  $settings['content']; ?></p>
					</div>

 <?php } else if($settings['style']=='style4') { ?>
				 
	<div class="working-four-main">
						<h6><?php echo  $settings['ctext']; ?></h6>
						<div class="working-four-img">
						<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="img">
						</div>
						<h4><?php echo  $settings['title']; ?></h4>
						<p><?php echo  $settings['content']; ?></p>
					</div>
 <?php } else if($settings['style']=='style5') { ?>
        <div class="works-it-five-button-main <?php echo $settings['aclass']; ?>">
							<div class="works-it-five-button">
								<h4><?php echo  $settings['ctext']; ?></h4>
							</div>
							<div class="works-it-dots">
								<span></span>
							</div>
							<?php if($settings['aclass']!='noline') { ?> 
							<div class="works-it-lines">
								<span></span>
							</div>
							<?php } ?>
						</div>
						<div class="works-five-main">
							<div class="works-five-img">
								<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="">
							</div>
        <div class="works-five-bottom">
						 <h5><?php echo  $settings['title']; ?></h5>
						 <p><?php echo  $settings['content']; ?></p>
						 <a href="<?php echo  $full_url; ?>" <?php echo  $target; ?>  <?php echo  $nofollow;?> > <i class="feather-arrow-right"></i> </a>
		 </div>
        </div>
 		
		<?php } else if($settings['style']=='style6') { ?>
     		<div class="professional-cleaning-main <?php echo $settings['aclass']; ?>">
							<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="">
							<h5><?php echo  $settings['title']; ?></h5>
						</div>

	   <?php } else if($settings['style']=='style7') { ?>

		<div class="how-it-works-six">
							<div class="works-six-num <?php echo $settings['aclass']; ?>">
								<h2><?php echo  $settings['ctext']; ?></h2>
							</div>
							<div class="work-online-schedule">
								<div class="work-online-img">
                                      <?php if($settings['aclass']=='') { ?>
								     	<img src="<?php echo get_template_directory_uri(); ?>/assets/images/works-six-1.png" alt="" class="img-fluid">
									<?php } else if($settings['aclass']=='works-six-num-two') {?>
										<img src="<?php echo get_template_directory_uri(); ?>/assets/images/works-six-2.png" alt="" class="img-fluid">
									<?php } else if($settings['aclass']=='works-six-num-three') {?>
										<img src="<?php echo get_template_directory_uri(); ?>/assets/images/works-six-3.png" alt="" class="img-fluid">
									<?php } ?>

								</div>
								<div class="work-online-bottom">
									<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="">
									<h4><?php echo  $settings['title']; ?></h4>
								</div>
							</div>
						</div>
 <?php } else if($settings['style']=='style8') { ?>

	<div class="work-box-seven aos aos-init aos-animate" data-aos="fade-up">
							<div class="work-icon-seven <?php echo $settings['aclass']; ?>">
								<h1><?php echo  $settings['ctext']; ?></h1>
								<span>
									<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="img">
								</span>
							</div>
							<h5><?php echo  $settings['title']; ?></h5>
 							<p><?php echo  $settings['content']; ?></p>
						</div>
 <?php } else if($settings['style']=='style9') { ?>

	<div class="works-eights-main">
							<div class="works-eights-img">
								<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="">
								<div class="works-eights-arrow <?php echo $settings['aclass']; ?>">
								<?php if($settings['aclass']=='works-eights-arrow-one') { ?> 
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-eight-1.svg" alt="">
								<?php } else if($settings['aclass']=='works-eights-arrow-two') { ?>
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-eight-2.svg" alt="">

									<?php } ?>
								</div>
							</div>
							<p><?php echo  $settings['title']; ?></p>
							
						</div>
 <?php } else if($settings['style']=='style10') { ?>

 		<div class="reasonable-all <?php echo $settings['aclass']; ?>">
			<img src="<?php echo esc_url($settings['background']['url']); ?>" alt="">
				<h5><?php echo  $settings['title']; ?></h5>
				<p><?php echo  $settings['content']; ?></p>
		</div>
  					
     <?php } ?>
		<?php
	}




}