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

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * truelysell widget class.
 *
 * @since 1.0.0
 */
class Headline extends Widget_Base {

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
		return 'truelysell-headline';
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
		return __( 'Truelysell Headline', 'truelysell_elementor' );
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
		return 'eicon-heading';
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
			'subtitle',
			array(
				'label'   => __( 'Subtitle', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'link',
			array(
				'label'   => __( 'Link', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-link.com', 'truelysell_elementor' ),
				'default' => '',
				'condition' => [
					'style' => [ 'style1', 'style2', 'style4', 'style6'], 
				],
			)
		);
   
		$this->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'View All',
				'condition' => [
					'style' => [ 'style1', 'style2', 'style4', 'style6'], 
					
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
					'default' => __( 'Style 1', 'truelysell_elementor' ),
 					'style2' => __( 'Style 2', 'truelysell_elementor' ),
					'style3' => __( 'Style 3', 'truelysell_elementor' ),
					'style4' => __( 'Style 4', 'truelysell_elementor' ),
					'style5' => __( 'Style 5', 'truelysell_elementor' ),
					'style6' => __( 'Style 6', 'truelysell_elementor' ),
					'style7' => __( 'Style 7', 'truelysell_elementor' ),
					'style8' => __( 'Style 8', 'truelysell_elementor' ),
					'style9' => __( 'Style 9', 'truelysell_elementor' ),

				],
			]
		);

		$this->add_control(
			'textstyle',
			[
				'label' => __( 'Text Style ', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Normal', 'truelysell_elementor' ),
 					'white-text' => __( 'white-text', 'truelysell_elementor' ),

				],
			]
		);
		$this->add_control(
			'aclass',
			array(
				'label'   => __( 'Class', 'truelysell_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'style' => [ 'style8'], 
					
				],
			)
		);
		
		$this->add_control(
			'background',
			[
				'label' => __( 'Title Image', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'style' => [ 'style2', 'style3', 'style5', 'style6', 'style8' ], 
				],
 			]
		);
		$this->add_control(
			'background2',
			[
				'label' => __( 'Title Image 2', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'style' => 'style5',
				],
 			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
		  'style_section',
		  [
		    'label' => __( 'Style Section', 'truelysell_elementor' ),
		    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		  ]
		);

		$this->add_control(
			'type',
			[
				'label' => __( 'Element tag ', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => __( 'H1', 'truelysell_elementor' ),
					'h2' => __( 'H2', 'truelysell_elementor' ),
					'h3' => __( 'H3', 'truelysell_elementor' ),
					'h4' => __( 'H4', 'truelysell_elementor' ),
					'h5' => __( 'H5', 'truelysell_elementor' ),
				],
			]
		);


		$this->add_control(
		  'text_align',
		  [
		    'label' => __( 'Text align', 'truelysell_elementor' ),
		    'type' => \Elementor\Controls_Manager::CHOOSE,
		    'options' => [
		      'left' => [
		        'title' => __( 'Left', 'truelysell_elementor' ),
		        'icon' => 'eicon-text-align-left',
		      ],
		      'center' => [
		        'title' => __( 'Center', 'truelysell_elementor' ),
		        'icon' => 'eicon-text-align-center',
		      ],
		       
		    ],
		    'default' => 'center',
		    'toggle' => true,
		  ]
		);

	 

		/* Add the options you'd like to show in this tab here */

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
		$this->add_inline_editing_attributes( 'link', 'none' );
		$css_class = 'headline ';
		if(isset($settings['text_align'])) {
				switch ($settings['text_align']) {
					case 'left':
						$css_class .= ' headline-aligned-to-left ';
						break;
					case 'right':
						$css_class .= ' headline-aligned-to-right ';
						break;
					case 'center':
						$css_class .= ' headline-aligned-to-center headline-extra-spacing';
						break;
					
					default:
						# code...
						break;
				}	
			}
		 

		if ( !empty($settings['subtitle']) ) {
			$css_class .= ' headline-with-subtitle ';
		}

		if ( !empty($settings['link']) ) {
			$css_class .= ' headline-with-subtitlelink ';
		}

		
		$style = 'style="';
		$style .= (isset($settings['text_align'])) ? 'text-align:'.$settings['text_align'].';' : '' ;
		$style .= '"';
		?>
 <?php if($settings['style']=='default'){
	if($settings['text_align']=='center') { ?>

<div class="section-heading">
		<div class="row">
  			<div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
 		<<?php echo  $settings['type']; ?> 
		<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
			<?php endif; ?></<?php echo $settings['type'] ?>>
			<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>
		</div>
			<?php if($settings['link']!='') { ?>
			<div class="col-md-12  text-md-center mt-3 aos aos-init aos-animate" data-aos="fade-up">
		<a class="btn btn-primary btn-view" href="<?php echo $settings['link']; ?>"  <?php echo $style; ?>><?php echo $settings['label']; ?> <i class="feather-arrow-right-circle ms-2"></i></a></div>
	 <?php } ?>
	</div>

		</div>
		

<?php } else {?>
		<div class="section-heading">
		<div class="row">
		<?php if($settings['link']!='') { ?>
		<div class="col-md-6 aos aos-init aos-animate" data-aos="fade-up">
		 <?php } else { ?>
			<div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
			 <?php } ?>
		<<?php echo  $settings['type']; ?> 
		<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
			<?php endif; ?></<?php echo $settings['type'] ?>>
			<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>
		</div>
			<?php if($settings['link']!='') { ?>
			<div class="col-md-6 text-md-end aos aos-init aos-animate" data-aos="fade-up">
		<a class="btn btn-primary btn-view" href="<?php echo $settings['link']; ?>"><?php echo $settings['label']; ?> <i class="feather-arrow-right-circle ms-2"></i></a></div>
	 <?php } ?>
	</div>

		</div>
		<?php } ?>

		<?php } else if($settings['style']=='style2'){  ?>

		 <?php if($settings['text_align']=='center') { ?>
 		<div class="row">
 		<div class="col-md-12 text-center">
						<div class="section-heading <?php echo $settings['textstyle']; ?> sec-header aos aos-init aos-animate" data-aos="fade-up">
						<<?php echo  $settings['type']; ?> 
		<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
			<?php endif; ?></<?php echo $settings['type'] ?>>
			<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>
			<?php if($settings['link']!='') { ?>
					<div class=" text-md-center mt-3 aos aos-init aos-animate" data-aos="fade-up">
						<a href="<?php echo $settings['link']; ?>" class="btn btn-pink btn-viewall" <?php echo $style; ?>><?php echo $settings['label']; ?> <i class="feather-arrow-right-circle"></i></a>
					</div>
					<?php } ?>
						</div>
 					</div>
 				</div>
  <?php } else {?>

			<div class="row">
			<?php if($settings['link']!='') { ?>
					<div class="col-md-6 aos aos-init aos-animate" data-aos="fade-up">	
					<?php } else { ?>
			<div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
			 <?php } ?>	
						<div class="section-heading-two <?php echo $settings['textstyle']; ?>">
 							<<?php echo  $settings['type']; ?> 
		<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
			<?php endif; ?></<?php echo $settings['type'] ?>>
			<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>

						</div>
					</div>
					<?php if($settings['link']!='') { ?>
					<div class="col-md-6 text-md-end aos aos-init aos-animate" data-aos="fade-up">
						<a href="<?php echo $settings['link']; ?>" class="btn btn-pink btn-viewall"><?php echo $settings['label']; ?> <i class="feather-arrow-right-circle"></i></a>
					</div>
					<?php } ?>
				</div>
 <?php } ?>

 <?php } else if($settings['style']=='style3'){  ?>

<?php if($settings['text_align']=='center') { ?>

	<div class="row">
						<div class="col-md-12">
							<div class="section-content">
							<<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>

   <?php if($settings['background']['url']!='') { ?> 
								<div class="our-img-all">
								<img src="<?php echo $settings['background']['url']; ?>" alt="">
								</div>
    <?php } ?>
    <p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>

	<?php if($settings['link']!='') { ?>
		   <div class=" text-md-center mt-3 aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="btn btn-pink btn-viewall" <?php echo $style; ?>><?php echo $settings['label']; ?> <i class="feather-arrow-right-circle"></i></a>
		   </div>
		   <?php } ?>
		   
							</div>
						</div>
					</div>
   			  
    
 <?php } else {?>

   <div class="row">
   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 aos aos-init aos-animate" data-aos="fade-up">	
		   <?php } else { ?>
   <div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
	<?php } ?>	
			   <div class="section-heading-two <?php echo $settings['textstyle']; ?>">
					<<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>
   <p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>

			   </div>
		   </div>
		   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 text-md-end aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="btn btn-pink btn-viewall"><?php echo $settings['label']; ?> <i class="feather-arrow-right-circle"></i></a>
		   </div>
		   <?php } ?>
	   </div>
<?php } ?>

<?php } else if($settings['style']=='style4'){
	if($settings['text_align']=='center') { ?>

<div class="section-heading section-heading-four">
		<div class="row align-items-center">
  			<div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
 		<<?php echo  $settings['type']; ?> 
		<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
			<?php endif; ?></<?php echo $settings['type'] ?>>
			<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>
		</div>
			<?php if($settings['link']!='') { ?>
			<div class="col-md-12  text-md-center mt-3 aos aos-init aos-animate" data-aos="fade-up">
		<a class="btn btn-primary btn-view" href="<?php echo $settings['link']; ?>"  <?php echo $style; ?>><?php echo $settings['label']; ?> <i class="feather-arrow-right ms-2"></i></a></div>
	 <?php } ?>
	</div>

		</div>
<?php } else {?>

		<div class="section-heading section-heading-four" >
		<div class="row align-items-center">
		<?php if($settings['link']!='') { ?>
		<div class="col-md-6 aos aos-init aos-animate" data-aos="fade-up">
		 <?php } else { ?>
			<div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
			 <?php } ?>
		<<?php echo  $settings['type']; ?> 
		<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
			<?php endif; ?></<?php echo $settings['type'] ?>>
			<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>
		</div>
			<?php if($settings['link']!='') { ?>
			<div class="col-md-6 text-md-end aos aos-init aos-animate" data-aos="fade-up">
		<a class="btn btn-primary btn-view" href="<?php echo $settings['link']; ?>"><?php echo $settings['label']; ?> <i class="feather-arrow-right ms-2"></i></a></div>
	 <?php } ?>
	</div>

		</div>
		<?php } ?>

		
		<?php } else if($settings['style']=='style5'){
	if($settings['text_align']=='center') { ?>
 <div class="row">
					<div class="col-md-12 text-center">
						<div class="section-heading car-wash-heading aos aos-init aos-animate" data-aos="fade-up">
							<div class="car-wash-img-five">
								<?php if($settings['background']['url']!='') { ?> 
 								<img src="<?php echo $settings['background']['url']; ?>" alt="" class="car-wash-header-one">
								 <?php } ?>
								 <<?php echo  $settings['type']; ?> 
		<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
			<?php endif; ?></<?php echo $settings['type'] ?>>
								<?php if($settings['background2']['url']!='') { ?> 
								<img src="<?php echo $settings['background2']['url']; ?>" alt="" class="car-wash-header-two">
								<?php } ?>
							</div>
							<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>

							<?php if($settings['link']!='') { ?>
		            	<div class=" text-md-center mt-3  "  >
	                    <a class="btn btn-primary btn-view" href="<?php echo $settings['link']; ?>"  <?php echo $style; ?> >
						 <?php echo $settings['label']; ?> <i class="feather-arrow-right ms-2"></i></a></div>
	 <?php } ?>
						</div>
					</div>
 </div>
  
<?php } else {?>
	<div class="section-heading car-wash-heading  aos aos-init aos-animate" data-aos="fade-up">
	<div class="row">

 	<?php if($settings['link']!='') { ?>
		<div class="col-md-6 aos aos-init text-left aos-animate " data-aos="fade-up">
		<div class="car-wash-img-five justify-content-start">

		<?php if($settings['background']['url']!='') { ?> 
 								<img src="<?php echo $settings['background']['url']; ?>" alt="" class="car-wash-header-one">
								 <?php } ?>
								 <<?php echo  $settings['type']; ?> 
		<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
			<?php endif; ?></<?php echo $settings['type'] ?>>
								<?php if($settings['background2']['url']!='') { ?> 
								<img src="<?php echo $settings['background2']['url']; ?>" alt="" class="car-wash-header-two">
								<?php } ?>
								</div>
 							<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>
 					</div>

		 <?php } else { ?>
					<div class="col-md-12 text-left text-start">
					<div class="car-wash-img-five justify-content-start">

					<?php if($settings['background']['url']!='') { ?> 
 								<img src="<?php echo $settings['background']['url']; ?>" alt="" class="car-wash-header-one">
								 <?php } ?>
								 <<?php echo  $settings['type']; ?> 
		<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
			<?php endif; ?></<?php echo $settings['type'] ?>>
								<?php if($settings['background2']['url']!='') { ?> 
								<img src="<?php echo $settings['background2']['url']; ?>" alt="" class="car-wash-header-two">
								<?php } ?>
								</div>
 							<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>> <?php echo $settings['subtitle']; ?></p>
							
					</div>

		 <?php } ?>
							
   		 <?php if($settings['link']!='') { ?>
			<div class="col-md-6 text-md-end aos aos-init aos-animate" data-aos="fade-up">
		       <a class="btn btn-primary btn-view" href="<?php echo $settings['link']; ?>"><?php echo $settings['label']; ?> <i class="feather-arrow-right ms-2"></i></a>
	        </div>
	    <?php } ?>


 </div>


		 
 <?php } ?>

 <?php } else if($settings['style']=='style6'){  ?>

<?php if($settings['text_align']=='center') { ?>

	<div class="row ">
        <div class="col-md-12">
            <div class="section-heading section-heading-six">
			<div class="reason-six">
			<?php if($settings['background']['url']!='') { ?> 
				<img src="<?php echo $settings['background']['url']; ?>" alt="">
			<?php } ?>
			<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>><?php echo $settings['subtitle']; ?></p>
			</div>

              <<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>

	<?php if($settings['link']!='') { ?>
		   <div class=" text-md-center mt-3 aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="btn btn-pink btn-viewall" <?php echo $style; ?>><?php echo $settings['label']; ?></a>
		   </div>
    <?php } ?>
		   
							</div>
						</div>
					</div>
   			  
    
 <?php } else {?>

   <div class="row align-items-center">
   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 aos aos-init aos-animate" data-aos="fade-up">	
		   <?php } else { ?>
   <div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
	<?php } ?>	
			   <div class="section-heading section-heading-six <?php echo $settings['textstyle']; ?>">
			   <div class="reason-six">
			<?php if($settings['background']['url']!='') { ?> 
				<img src="<?php echo $settings['background']['url']; ?>" alt="">
			<?php } ?>
			<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>><?php echo $settings['subtitle']; ?></p>
			</div>
			   <<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>
 
			   </div>
		   </div>
		   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 text-md-end aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="afford-btn"><?php echo $settings['label']; ?></a>
		   </div>
		   <?php } ?>
	   </div>
<?php } ?>
<?php } else if($settings['style']=='style7'){  ?>

<?php if($settings['text_align']=='center') { ?>

	<div class="row ">
        <div class="col-md-12">
            <div class="section-heading section-heading-seven">
					
            <<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>
   <p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>><?php echo $settings['subtitle']; ?></p>

	<?php if($settings['link']!='') { ?>
		   <div class=" text-md-center mt-3 aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="btn btn-pink btn-viewall" <?php echo $style; ?>><?php echo $settings['label']; ?></a>
		   </div>
    <?php } ?>
		   
							</div>
						</div>
					</div>
   			  
    
 <?php } else {?>

   <div class="row align-items-center">
   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 aos aos-init aos-animate" data-aos="fade-up">	
		   <?php } else { ?>
   <div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
	<?php } ?>	
			   <div class="section-heading section-heading-seven <?php echo $settings['textstyle']; ?>">
			   <<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>
   <p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>><?php echo $settings['subtitle']; ?></p>
			   </div>
		   </div>
		   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 text-md-end aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="afford-btn"><?php echo $settings['label']; ?></a>
		   </div>
		   <?php } ?>
	   </div>
<?php } ?>

<?php } else if($settings['style']=='style8'){  ?>

<?php if($settings['text_align']=='center') { ?>

	<div class="row ">
        <div class="col-md-12 text-center">
            <div class="section-heading section-heading-eight <?php echo $settings['aclass']; ?> aos aos-init aos-animate">
			<?php if($settings['background']['url']!='') { ?> 
				<img src="<?php echo $settings['background']['url']; ?>" alt="">
			<?php } ?>
            <<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>
   <p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>><?php echo $settings['subtitle']; ?></p>

	<?php if($settings['link']!='') { ?>
		   <div class=" text-md-center mt-3 aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="btn btn-pink btn-viewall" <?php echo $style; ?>><?php echo $settings['label']; ?></a>
		   </div>
    <?php } ?>
		   
							</div>
						</div>
					</div>
   			  
    
 <?php } else {?>

   <div class="row align-items-center">
   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 aos aos-init aos-animate" data-aos="fade-up">	
		   <?php } else { ?>
   <div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
	<?php } ?>	
			   <div class="section-heading section-heading-eight <?php echo $settings['aclass']; ?> aos aos-init aos-animate <?php echo $settings['textstyle']; ?>">

			   <?php if($settings['background']['url']!='') { ?> 
				<img src="<?php echo $settings['background']['url']; ?>" alt="">
			<?php } ?>

			   <<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>
   <p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>><?php echo $settings['subtitle']; ?></p>
			   </div>
		   </div>
		   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 text-md-end aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="afford-btn"><?php echo $settings['label']; ?></a>
		   </div>
		   <?php } ?>
	   </div>
<?php } ?>

<?php } else if($settings['style']=='style9'){  ?>

<?php if($settings['text_align']=='center') { ?>

	<div class="row ">
        <div class="col-md-12 text-center">
            <div class="section-heading section-heading-nine <?php echo $settings['aclass']; ?> aos aos-init aos-animate">
			<p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>><?php echo $settings['subtitle']; ?></p>

            <<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>
	<?php if($settings['link']!='') { ?>
		   <div class=" text-md-center mt-3 aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="btn btn-pink btn-viewall" <?php echo $style; ?>><?php echo $settings['label']; ?></a>
		   </div>
    <?php } ?>
		   
							</div>
						</div>
					</div>
   			  
    
 <?php } else {?>

   <div class="row align-items-center">
   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 aos aos-init aos-animate" data-aos="fade-up">	
		   <?php } else { ?>
   <div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">
	<?php } ?>	
			   <div class="section-heading section-heading-nine <?php echo $settings['aclass']; ?> aos aos-init aos-animate <?php echo $settings['textstyle']; ?>">
			   <p <?php echo $this->get_render_attribute_string( 'subtitle' ); ?> <?php echo $style; ?>><?php echo $settings['subtitle']; ?></p>

			   <<?php echo  $settings['type']; ?> 
<?php echo $style; ?> class="<?php echo esc_attr($css_class); ?>"> <?php echo $settings['title']; ?> <?php if($settings['subtitle']) : ?> 
   <?php endif; ?></<?php echo $settings['type'] ?>>
			   </div>
		   </div>
		   <?php if($settings['link']!='') { ?>
		   <div class="col-md-6 text-md-end aos aos-init aos-animate" data-aos="fade-up">
			   <a href="<?php echo $settings['link']; ?>" class="afford-btn"><?php echo $settings['label']; ?></a>
		   </div>
		   <?php } ?>
	   </div>
<?php } ?>

 <?php } ?>

		<?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */

}