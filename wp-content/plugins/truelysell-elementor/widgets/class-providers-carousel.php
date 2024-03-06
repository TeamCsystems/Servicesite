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
class Truelysell_Providers_Carousel extends Widget_Base {

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
		return 'TruelysellProvidersCarousel';
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
		return __( 'Truelysell Top Providers Carousel', 'truelysell_elementor' );
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
		return 'eicon-posts-carousel';
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
					'style4'=>  __(  'Style 4', 'truelysell_elementor' ),
					'style5'=>  __(  'Style 5', 'truelysell_elementor' ),
					'style6'=>  __(  'Style 6', 'truelysell_elementor' ),
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

	<div class="">
							
	</div>
 <div class="owl-carousel stylists-slider aos" data-aos="fade-up">
	<?php foreach ( $users as $user ) { ?>

		<div class="stylists-all">
								<div class="stylists-main-img">
								<?php  $owner_id = $user->ID; ?>
									<a href="#">
 									<?php echo get_avatar($owner_id);  ?>
									</a>
								</div>
								<div class="stylists-bottom">
									<div class="stylists-foot">
										<a href="#"><h4><?php echo esc_html_e( $user->display_name ); ?></h4></a>
										<h6><?php echo esc_html_e( $user->user_email ); ?></h6>
										 
									</div>
								</div>
							</div>
  					<?php 
  } ?>
  
 </div>

 <?php } else if($settings['style']=='style2') { ?>
  
	<div class="owl-carousel common-four-slider  top-providers-catering aos" data-aos="fade-up">

	<?php foreach ( $users as $user ) { ?>

<div class="stylists-all">
						<div class="stylists-main-img">
						<?php  $owner_id = $user->ID; ?>
							<a href="#">
							 <?php echo get_avatar($owner_id);  ?>
							</a>
						</div>
						<div class="stylists-bottom">
							<div class="stylists-foot">
								<a href="#"><h4><?php echo esc_html_e( $user->display_name ); ?></h4></a>
								<h6><?php echo esc_html_e( $user->user_email ); ?></h6>
								 
							</div>
						</div>
					</div>
			  <?php 
} ?>

	</div>

<?php } else if($settings['style']=='style3') { ?>
  
  <div class="owl-carousel top-providers-five">
  <?php foreach ( $users as $user ) { ?>
	<div class="providerset">
		<div class="providerset-img">
				 <?php  $owner_id = $user->ID; ?>
					<a href="#">
						 <?php echo get_avatar($owner_id);?>
					</a>
		</div>
		<div class="providerset-content">
				<h4><a href="#"><?php echo esc_html_e( $user->display_name ); ?></a> </h4>
				<h5><?php echo esc_html_e( $user->user_email ); ?></h5>
		</div>
    </div>
<?php } ?>
  </div>
  <?php } else if($settings['style']=='style4') { ?>
	<div class="mynavholder">
	<div class="owl-nav  mynav-seven-three"></div>
	</div>
  <div class="owl-carousel top-projects-seven">
  <?php foreach ( $users as $user ) { ?>

	<div class="providerset">
								<div class="providerset-img providerset-img-seven ">
									<a href="#">
									<?php  $owner_id = $user->ID; ?>
									<?php echo get_avatar($owner_id);  ?>
									</a>
									 
								</div>
								<div class="providerset-content providerset-content-seven">
									<div class="providerset-price mb-0">
										<div class="providerset-name">
											<h4><a href="#"><?php echo esc_html_e( $user->display_name ); ?></a></h4>
											<span><?php echo esc_html_e( $user->user_email ); ?></span>
										</div>
										 
									</div>
 								</div>
    </div>
<?php } ?>
  </div>

  <?php } else if($settings['style']=='style5') { ?>
	 
  <div class="owl-carousel professional-eight-slider">
  <?php foreach ( $users as $user ) { ?>

	<div class="professional-eight-main">
								<div class="professional-eight-img-ryt">
								<?php  $owner_id = $user->ID; ?>
									<?php echo get_avatar($owner_id);  ?>
								</div>
								 
								<a href="#"><h6><?php echo esc_html_e( $user->display_name ); ?></h6></a>
								<span class="act"><?php echo esc_html_e( $user->user_email ); ?></span>
 							</div>

 
<?php } ?>
  </div>
  <?php } else if($settings['style']=='style6') { ?>
	 <div class="owl-carousel provider-nine-slider common-nine-slider">
	 <?php foreach ( $users as $user ) { ?>
   
		<div class="providerset providerset-nine">
            <div class="providerset-img ">
                <a href="#">
                    <?php  $owner_id = $user->ID; ?>
                    <?php echo get_avatar($owner_id);  ?>
                </a>
            </div>
            <div class="providerset-content">
                <h4><?php echo esc_html_e( $user->display_name ); ?></h4>
                    <h6 class="mb-0"><?php echo esc_html_e( $user->user_email ); ?></h6>
            </div>
        </div>
   <?php } ?>
	 </div>

 <?php } ?>

 		  <?php 
 
 		 
	}
 
 
}