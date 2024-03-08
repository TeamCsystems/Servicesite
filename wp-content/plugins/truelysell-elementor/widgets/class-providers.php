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
class Truelysell_Providers extends Widget_Base {

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
		return 'TruelysellProviders';
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
		return __( 'Truelysell Top Providers', 'truelysell_elementor' );
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
		return 'eicon-gallery-grid';
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
  		//Content
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'truelysell_elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'style',
			[
				'label' => __( 'Style ', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' =>  __( 'Style 1', 'truelysell_elementor' ),
					'style2' =>  __(  'Style 2 ', 'truelysell_elementor' ),
					'style3'=>  __(  'Style 3', 'truelysell_elementor' ),
   				],
			]
		);
  
		$this->add_control(
			'limit',
			[
				'label' => __( 'Doctors to display', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 21,
				'step' => 1,
				'default' => 6,
			]
		);
 
	 
		
	 
		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' =>  __( 'Descending', 'truelysell_elementor' ),
					'ASC' =>  __(  'Ascending. ', 'truelysell_elementor' ),
				
					
				],
			]
		);
			
		
		
	$this->add_control(
		'link',
		array(
			'label'   => __( 'Link', 'truelysell_elementor' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => '',
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
 		$limit = $settings['limit'] ? $settings['limit'] : 4;
 		$order = $settings['order'] ? $settings['order'] : 'ASC';
  
  $args = array(
    'role'    => 'owner',
	'number'  => $limit, // limit
    //'orderby' => 'user_nicename',
    'order'   => $order
);
$users = get_users( $args );
 ?>
 
 <?php if($settings['style']=='style1') { ?>
 <div class="row aos aos-init aos-animate" data-aos="fade-up">
	<?php foreach ( $users as $user ) { ?>
					<div class="col-lg-3 col-sm-6">

					<div class="providerset provider-box">
							<div class="providerset-img">
							<?php
								  $owner_id = $user->ID;
							     ?>
								<a href="#">
								<?php echo get_avatar($owner_id);  ?>
								</a>
							</div>
							<div class="providerset-content">
								<div class="providerset-price">
									<div class="providerset-name">
										<h4><a href="#"><?php echo esc_html_e( $user->display_name ); ?></a></h4>
										<span><?php echo esc_html_e( $user->user_email ); ?></span>
									</div>
									
								</div>
								
							</div>
						</div>
						 
					</div>
					<?php 
  } ?>
  
 </div>

 <?php } else if($settings['style']=='style2') { ?>

	<div class="row aos aos-init aos-animate" data-aos="fade-up">
	<?php foreach ( $users as $user ) { ?>
					<div class="col-lg-3 col-sm-6">

					<div class="providerset provider-box">
							<div class="providerset-img">
							<?php
								  $owner_id = $user->ID;
							     ?>
								<a href="#">
								<?php echo get_avatar($owner_id);  ?>
								</a>
							</div>
							<div class="providerset-content">
								<div class="providerset-price">
									<div class="providerset-name">
										<h4><a href="#"><?php echo esc_html_e( $user->display_name ); ?></a></h4>
										<span><?php echo esc_html_e( $user->user_email ); ?></span>
									</div>
									
								</div>
								
							</div>
						</div>
						 
					</div>
					<?php 
  } ?>
  
 </div>
 <?php } else if($settings['style']=='style3') { ?>

<div class="row aos aos-init aos-animate" data-aos="fade-up">
<?php foreach ( $users as $user ) { ?>

	<div class="col-lg-3 col-md-4 col-sm-6 col-12">
						<div class="our-expert-six">
							<div class="our-expert-img">
							<?php $owner_id = $user->ID; ?>
							<?php $custom_avatar_id = get_user_meta($owner_id, 'truelysell_core_avatar_id', true); 
							  $custom_avatar = wp_get_attachment_image_src($custom_avatar_id,'truelysell_core-avatar');
 							 ?>
  							<img src="<?php echo $custom_avatar[0]; ?>" class="img-fluid">
							</div>
							<div class="our-expert-six-content">
								<h6><?php echo esc_html_e( $user->display_name ); ?></h6>
								<p><?php echo esc_html_e( $user->user_email ); ?></p>
								 
 							</div>
						</div>
					</div>				 
				<?php 
} ?>

</div>
 

 <?php } ?>

 		  <?php 
 
 		 
	}
 
 
}