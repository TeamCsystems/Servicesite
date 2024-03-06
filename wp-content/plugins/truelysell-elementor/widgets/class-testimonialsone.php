<?php
/**
 * truelysell class.
 *
 * @category   Class
 * @package    Elementortruelysell
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
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
 
/**
 * truelysell widget class.
 *
 * @since 1.0.0
 */
class Widget_Truelusell_Testimonials extends Widget_Base {

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
		return 'truelysell-testimonialone';
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
		return __( 'Truelysell Testimonials 1', 'truelysell_elementor' );
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
		return 'eicon-accordion';
	}

	public function get_keywords() {
		return [ 'tabs', 'accordion', 'toggle' ];
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
			'section_query',
			array(
				'label' => __( 'Query Settings', 'truelysell_elementor' ),
			)
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
					'style3' =>  __( 'Style 3', 'truelysell_elementor' ),
					'style4' =>  __( 'Style 4', 'truelysell_elementor' ),
					'style5' =>  __( 'Style 5', 'truelysell_elementor' ),
					'style6' =>  __( 'Style 6', 'truelysell_elementor' ),
					'style7' =>  __( 'Style 7', 'truelysell_elementor' ),
					'style8' =>  __( 'Style 8', 'truelysell_elementor' ),
					'style9' =>  __( 'Style 9', 'truelysell_elementor' ),
  					
				],
			]
		);
	 
		$this->add_control(
			'background',
			[
				'label' => __( 'Title Image', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'style' => 'style7',
				],
 			]
		);
		
		$this->add_control(
			'backgroundleft',
			[
				'label' => __( 'Image Left', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'style' => 'style9',
				],
 			]
		);
		
		$this->add_control(
			'backgroundright',
			[
				'label' => __( 'Image Right', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'style' => 'style9',
				],
 			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle',
			[
				'label' => esc_html__( 'Testimonials', 'truelysell_elementor' ),
			]
		);
 		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Title', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'It was a very good experience', 'truelysell_elementor' ),
				'label_block' => true,
				 
			]
		);

		$repeater->add_control(
			'tab_name',
			[
				'label' => esc_html__( 'Name', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Name', 'truelysell_elementor' ),
				'label_block' => true,
				 
			]
		);
		

		$repeater->add_control(
			'client_designation',
			[
				'label'   => __( 'Designation', 'truelysell_elementor' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('Managing Director','truelysell_elementor'),
			]
		);

		$repeater->add_control(
			'srating',
			[
				'label' => __( 'Style', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' =>  __( '1 Star', 'truelysell_elementor' ),
					'2' =>  __( '2 Star', 'truelysell_elementor' ),
					'3' =>  __( '3 Star', 'truelysell_elementor' ),
					'4' =>  __( '4 Star', 'truelysell_elementor' ),
					'5' =>  __( '5 Star', 'truelysell_elementor' ),
				],
			]
		);

		$repeater->add_control(
			'client_image',
			[
				'label' => __( 'Image', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				]
			]
		);

		$repeater->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'client_imagesize',
				'default' => 'large',
				'separator' => 'none',
			]
		);


		$repeater->add_control(
			'tab_content',
			[
				'label' => esc_html__( 'Content', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Testimonial Content', 'truelysell_elementor' ),
				'show_label' => true,
			]
		);


		$this->add_control(
			'tabs',
			[
				'label' => esc_html__( 'Testimonial Items', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Testimonial #1', 'truelysell_elementor' ),
						'tab_name' => esc_html__( 'Name #1', 'truelysell_elementor' ),
						'srating' => esc_html__( '1', 'truelysell_elementor' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'truelysell_elementor' ),
					],
					[
						'tab_title' => esc_html__( 'Testimonial #2', 'truelysell_elementor' ),
						'tab_name' => esc_html__( 'Name #2', 'truelysell_elementor' ),
						'srating' => esc_html__( '1', 'truelysell_elementor' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'truelysell_elementor' ),
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);
		

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);
		
		$this->end_controls_section();
 		/* Add the options you'd like to show in this tab here */
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
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );

		if ( ! isset( $settings['icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			// add old default
			$settings['icon'] = 'fa fa-caret' . ( is_rtl() ? '-left' : '-right' );
			$settings['icon_active'] = 'fa fa-caret-up';
			$settings['icon_align'] = $this->get_settings( 'icon_align' );
		}

		$is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
		$has_icon = ( ! $is_new || ! empty( $settings['selected_icon']['value'] ) );

		?>

 <?php if($settings['style'] == 'style1'){ ?>
<div class="row">
		        <div class="col-lg-5">			
				   <div class="testimonial-heading d-flex" style="left: 108px;">
 					   <img src="<?php echo get_template_directory_uri(); ?>/assets/images/quotes.png" alt="quotes">
				   </div>
			    </div>
				<div class="col-lg-7">			
				   <div class="rightimg"></div>
			    </div>
			</div>

		<div class="container">
		<div class="row">
			 
		<div class="testimonials-slidersection">
			        	<div class="owl-nav mynav1"></div>
			            <div class="owl-carousel testi-slider">
							
			<?php
			foreach ( $settings['tabs'] as $testimonial ) :
  
				?>
						    <div class="testimonial-info">
							    <div class="testimonialslider-heading d-flex">
								    
								<?php 
												  if( !empty($testimonial['client_image']['url']) ){
													echo '<div class="testi-img">'.\Elementor\Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
												} 
												?>

 									 
									 <div class="testi-author">
 										  <?php  if( !empty($testimonial['tab_title']) ){
														echo '<h6>'.esc_html__( $testimonial['tab_title'], 'truelysell_elementor' ).'</h6>';
													}
													?>

 
										  <?php 
												if( !empty($testimonial['client_designation']) ){
													echo '<p>'.esc_html__( $testimonial['client_designation'], 'truelysell_elementor' ).'</p>';
												}
												?>

									 </div>
									
								</div>
								 <div class="testimonialslider-content">
								 <?php 
if( !empty($testimonial['tab_content']) ){
	echo '<p>'.esc_html__( $testimonial['tab_content'], 'truelysell_elementor' ).'</p>';
}
?>
								</div>
							</div>
							 
              <?php endforeach; ?>	 
							 
						</div>
					</div>
			 
		 
		</div>
 </div>
 <?php } else if($settings['style'] == 'style2'){  ?>
 
	 <div class="owl-carousel client-slider">

	 <?php foreach ( $settings['tabs'] as $testimonial ) : ?>
						     

							 <div class="client-box aos" data-aos="fade-up">
									<div class="client-content">
										<div class="rating">

										<?php  if( !empty($testimonial['srating']) ){
														 echo truelysell_display_course_rating($testimonial['srating']); 
													}
													?>

  										</div>
 
										<?php  if( !empty($testimonial['tab_title']) ){
														echo '<h6>“'.esc_html__( $testimonial['tab_title'], 'truelysell_elementor' ).'”</h6>';
													}
													?>

<?php 
if( !empty($testimonial['tab_content']) ){
	echo '<p>'.esc_html__( $testimonial['tab_content'], 'truelysell_elementor' ).'</p>';
}
?>
									</div>
									<div class="client-img">
										
										<?php 
												  if( !empty($testimonial['client_image']['url']) ){
													echo '<a href="#">'.\Elementor\Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</a>';
												} 
												?>
										
										<div class="client-name">
 
											<?php 
												if( !empty($testimonial['tab_name']) ){
													echo '<h5>'.esc_html__( $testimonial['tab_name'], 'truelysell_elementor' ).'</h5>';
												}
												?>
												
 											<?php 
												if( !empty($testimonial['client_designation']) ){
													echo '<h6>'.esc_html__( $testimonial['client_designation'], 'truelysell_elementor' ).'</h6>';
												}
												?>
										</div>
									</div>
								</div>
							 
 <?php endforeach; ?>	 

								
								
	 </div>

	 <?php } else if($settings['style'] == 'style3'){ ?>

		<div class=" slider say-about slider-for aos" data-aos="fade-up">
		<?php foreach ( $settings['tabs'] as $testimonial ) : ?>
					<div>
						<div class="review-love-group">
							<div class="quote-love-img">
								<img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/images/quote.svg" alt="">
							</div>
 							<?php 
if( !empty($testimonial['tab_content']) ){
	echo '<p class="review-passage">“'.esc_html__( $testimonial['tab_content'], 'truelysell_elementor' ).' “</p>';
}
?>

							<div class="say-name-blk text-center">
 								<?php 
												if( !empty($testimonial['tab_name']) ){
													echo '<h5>'.esc_html__( $testimonial['tab_name'], 'truelysell_elementor' ).'</h5>';
												}
												?>
 								<?php 
												if( !empty($testimonial['client_designation']) ){
													echo '<p>'.esc_html__( $testimonial['client_designation'], 'truelysell_elementor' ).'</p>';
												}
												?>
							</div>
						</div>
					</div>
 <?php endforeach; ?>	
		</div>


		<div class="slider client-img client-images slider-nav client-pro aos" data-aos="fade-up">
		<?php foreach ( $settings['tabs'] as $testimonial ) : ?>
					<div class="testimonial-thumb">
 						<?php 
												  if( !empty($testimonial['client_image']['url']) ){
													echo '<a href="#">'.\Elementor\Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</a>';
												} 
                          ?>

					</div>
        <?php endforeach; ?>
 				</div>


 <?php } else if($settings['style'] == 'style4'){ ?>
				
<div class="owl-carousel client-four-slider common-four-slider">
<?php foreach ( $settings['tabs'] as $testimonial ) : ?>
 	<div class="client-review-main">
        <div class="client-review-top">
            <div class="client-review-name">
                <?php if( !empty($testimonial['tab_name']) ){
					echo '<h6>'.esc_html__( $testimonial['tab_name'], 'truelysell_elementor' ).'</h6>';
					}
				?>

				<div class="rating">
					<?php  if( !empty($testimonial['srating']) ){
                        echo truelysell_display_course_rating($testimonial['srating']); 
					    }
					?>
				</div>
			</div>
				<?php 
				if( !empty($testimonial['tab_content']) ){
				echo '<p>'.esc_html__( $testimonial['tab_content'], 'truelysell_elementor' ).'</p>';
				}
				?>
        </div>
							
	    <?php if( !empty($testimonial['client_image']['url']) ){
			  echo '<div class="client-review-img">'.\Elementor\Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
	    } 
        ?>
 </div>
<?php endforeach; ?>	
</div>
<?php } else if($settings['style'] == 'style5'){ ?>
				
 <div class="car-wash-bg-five-main">
 <div class="car-wash-bg-five">
 <div class="row justify-content-center">
 <div class="col-md-8">
 <div class="owl-carousel car-testimonials-five-slider">
 
 <?php foreach ( $settings['tabs'] as $testimonial ) : ?>
  <div class="testimonials-five-top">
 
	<?php if( !empty($testimonial['client_image']['url']) ){
			  echo '<div class="test-five-img">'.\Elementor\Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
	    } 
     ?>
<?php  if( !empty($testimonial['tab_title']) ){
 echo '<h2>'.esc_html__( $testimonial['tab_title'], 'truelysell_elementor' ).'</h2>';
 }
 ?>

 <?php 
        if( !empty($testimonial['tab_content']) ){
		echo '<p>'.esc_html__( $testimonial['tab_content'], 'truelysell_elementor' ).'</p>';
		}
 ?>
 <div class="rating">
 <?php  if( !empty($testimonial['srating']) ){
        echo truelysell_display_course_rating($testimonial['srating']); 
     }
 ?>
 </div>
  <?php if( !empty($testimonial['tab_name']) ){
      echo '<h5>'.esc_html__( $testimonial['tab_name'], 'truelysell_elementor' ).'</h5>';
     }
 ?>

  <?php if( !empty($testimonial['client_designation']) ){
      echo '<h6>'.esc_html__( $testimonial['client_designation'], 'truelysell_elementor' ).'</h6>';
     }
 ?>
 </div>
 <?php endforeach; ?>	
 </div>
 </div>
 </div>	
 </div>
 </div>
 <?php } else if($settings['style'] == 'style6'){ ?>
			
	<div class="row">
	<?php foreach ( $settings['tabs'] as $testimonial ) : ?>
	<div class="col-lg-4 col-md-6 col-12">
 <div class="customer-review-main-six">
 <div class="customer-review-top">
 <?php if( !empty($testimonial['client_image']['url']) ){
			  echo '<div class="testfiveimg1">'.\Elementor\Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
	    } 
?>

   <?php if( !empty($testimonial['tab_name']) ){
      echo '<h5>'.esc_html__( $testimonial['tab_name'], 'truelysell_elementor' ).'</h5>';
     }
 ?>
 <?php 
        if( !empty($testimonial['tab_content']) ){
		echo '<p>'.esc_html__( $testimonial['tab_content'], 'truelysell_elementor' ).'</p>';
		}
 ?>
     </div>

 <div class="customer-review-bottom">
    <div class="rating">
      <?php if( !empty($testimonial['srating']) ){
        echo truelysell_display_course_rating($testimonial['srating']); 
     }
     ?>
 </div>

 </div>
 <div class="customer-review-quote">
 <img src="<?php echo get_template_directory_uri(); ?>/assets/images/reviews-quote.svg" alt="">
 </div>
 </div>
 </div>
 <?php endforeach; ?>	
 </div>

 <?php } else if($settings['style'] == 'style7'){ ?>
			
	<div class="row align-items-center">
					<div class="col-lg-6 col-12">
						<div class="testimonals-top-seven">
							<img src="<?php echo $settings['background']['url']; ?>" alt="">
						</div>
					</div>
					<div class="col-lg-6 col-12">
			<div class="owl-carousel testimonals-seven-slider">
			<?php foreach ( $settings['tabs'] as $testimonial ) : ?>

 <div class="testimonials-main-ryt">
    <div class="testimonials-content-seven">
        <div class="testimonials-seven-img">
            <?php if( !empty($testimonial['client_image']['url']) ){
				echo '<div class="clientimg">'.\Elementor\Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
				} 
		    ?>
					<div class="testimonials-img-content">
 					<?php if( !empty($testimonial['tab_name']) ){
			        echo '<h6>'.esc_html__( $testimonial['tab_name'], 'truelysell_elementor' ).'</h6>';
			        }
		            ?>

					<div class="rating">
						<?php if( !empty($testimonial['srating']) ){
						echo truelysell_display_course_rating($testimonial['srating']); 
						}
						?>
					</div>
					</div>
		</div>
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/test-quote.svg" alt="" class="img-fluid">
								</div>
								<?php 
				if( !empty($testimonial['tab_content']) ){
				echo '<p>'.esc_html__( $testimonial['tab_content'], 'truelysell_elementor' ).'</p>';
				}
		 ?>
							</div>

			 
		 <?php endforeach; ?>	
		 </div>
		 </div>
		 </div>
 <?php } else if($settings['style'] == 'style8'){ ?>
			
 							 
 					<div class="owl-carousel testimonals-eight-slider">
					<?php foreach ( $settings['tabs'] as $testimonial ) : ?>
		

						<div class="testimonials-main-ryt customers-eight-main">
								<div class="testimonials-content-seven">
									<div class="testimonials-seven-img">
									<?php if( !empty($testimonial['client_image']['url']) ){
						echo '<div class="clientimg1">'.\Elementor\Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
						} 
					?>
									</div>
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/test-quote.svg" alt="" class="img-fluid">
								</div>
								<div class="testimonials-img-content">
 									<?php if( !empty($testimonial['tab_name']) ){
							echo '<h6>'.esc_html__( $testimonial['tab_name'], 'truelysell_elementor' ).'</h6>';
							}
							?>

									<div class="rating">
									<?php if( !empty($testimonial['srating']) ){
								echo truelysell_display_course_rating($testimonial['srating']); 
								}
								?>
									</div>
								</div>
								<?php 
						if( !empty($testimonial['tab_content']) ){
						echo '<p>'.esc_html__( $testimonial['tab_content'], 'truelysell_elementor' ).'</p>';
						}
				 ?>
							</div>
				 <?php endforeach; ?>	
				 </div>
 <?php } else if($settings['style'] == 'style9'){ ?>

	<div class="customer-side-main-all">
				<div class="customer-side-left-img">
					<img src="<?php echo $settings['backgroundleft']['url']; ?>" alt="">
				</div>
				<div class="customer-side-right-img">
					<img src="<?php echo $settings['backgroundright']['url']; ?>" alt="">
				</div>
    </div>
			
			<div class="owl-carousel customer-review-slider common-nine-slider">
		   <?php foreach ( $settings['tabs'] as $testimonial ) : ?>

			<div class="customer-reviews-all aos" data-aos="fade-up">
								<div class="customer-reviews-main">
								<?php if( !empty($testimonial['client_image']['url']) ){
			   echo '<div class="clientimg1">'.\Elementor\Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ).'</div>';
			   } 
		    ?>
									<div class="customer-quote">
										<img src="<?php echo get_template_directory_uri(); ?>/assets/images/customer-quote.svg" alt="">
									</div>
								</div>
 								<?php if( !empty($testimonial['tab_name']) ){
									echo '<h4>'.esc_html__( $testimonial['tab_name'], 'truelysell_elementor' ).'</h4>';
									}
								?>
 								<?php if( !empty($testimonial['client_designation']) ){
									 echo '<span>'.esc_html__( $testimonial['client_designation'], 'truelysell_elementor' ).'</span>';
								} ?>
								<div class="rating">
								<?php if( !empty($testimonial['srating']) ){
					             echo truelysell_display_course_rating($testimonial['srating']); 
					              }
					             ?>
								</div>
								<?php 
			   if( !empty($testimonial['tab_content']) ){
			   echo '<p>'.esc_html__( $testimonial['tab_content'], 'truelysell_elementor' ).'</p>';
			   }
		?>
 </div>

			   
		<?php endforeach; ?>	
		</div>
   	<?php } ?>

		<?php
	}

	
	/**
	 * Render toggle widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
 }
