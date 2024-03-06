<?php
namespace ElementorTruelysell\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Facts_Counter extends Widget_Base {
	
	public function get_name() {
		return 'facts-counter';
	}
	
	public function get_title() {
		return __( 'Truelysell Counter', 'truelysell-elementor' );
	}
	
	public function get_icon() {
		return 'eicon-counter-circle';
	}

	public function get_categories() {
		return [ 'truelysell' ];
	}
	
	public function get_script_depends() {
		return [ '' ];
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
			'section_query',
			[
				'label' => esc_html__( 'Facts Counter', 'truelysell-elementor' ),
			]
		);
		$this->add_control(
			'style',
			[
				'label' => __( 'Style ', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => __( 'Style 1', 'truelysell_elementor' ),
					'style2' => __( 'Style 2', 'truelysell_elementor' ),
 				],
			]
		);

		$this->add_control(
			'stitle', [
				'label' => __( 'Sub-Title', 'truelysell-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'style' => [ 'style2'], 
				],
			]
		);
		$this->add_control(
			'title', [
				'label' => __( 'Title', 'truelysell-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'style' => [ 'style2'], 
				],
			]
		);
		$this->add_control(
			'background',
			[
				'label' => __( 'Image', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'style' => [ 'style1'], 
				],
 			]
		);
		$this->add_control(
			'background2',
			[
				'label' => __( 'Background', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'style' => [ 'style1'], 
				],
 			]
		);
		
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'counter_title', [
				'label' => __( 'Counter Title', 'truelysell-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'counter_numbers',
			[
				'label' => __( 'Value in Digits', 'truelysell-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
			]
		);

		

		$this->add_control(
			'counter_list',
			[
				'label' => __( 'Counters', 'truelysell-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);
 
		$this->end_controls_section();
		
			
	}
	
		protected function render() {
		$settings = $this->get_settings_for_display();
		
		  $params['counter_list'] = $settings['counter_list'];
 		  $counter_lists = $params[ 'counter_list' ];
   ?>
<?php if($settings['style']=='style1'){ ?>
 <div class="row">
 <div class="col-lg-6 col-12">
 <div class="row">
<?php foreach ( $counter_lists as $counter_list ) { ?>
	<div class="col-lg-6 col-md-6 col-12">
        <div class="clients-eights-all">
            <div class="clients-eight-span">
                <h3 class="counter animated fadeInDownBig"><?php echo $counter_list[ 'counter_numbers' ];?></h3>
                <span>+</span>
            </div>
                <p><?php echo $counter_list[ 'counter_title' ];?></p>
        </div>
    </div>

 <?php } ?>
 </div>
 </div>
 <div class="col-lg-6 col-12">
	<div class="professional-eight-img">
	      <img src="<?php echo $settings['background']['url']; ?>" alt="" class="img-fluid">	
		<div class="professional-eight-bg">
		   <img src="<?php echo $settings['background2']['url']; ?>" alt="">
		</div>
	</div>
 </div>
 </div>
 <?php } else if($settings['style']=='style2'){ ?>
 	<div class="container">
	<div class="row">
					<div class="col-lg-4">
						<div class="section-heading section-heading-nine journey-heading-nine aos" data-aos="fade-up">
							<p><?php echo $settings['stitle']; ?></p>
							<h2><?php echo $settings['title']; ?></h2>
						</div>
					</div>

					<?php foreach ( $counter_lists as $counter_list ) { ?>
						<div class="col-lg-2 d-flex justify-content-center align-items-center">
						<div class="journey-client-all aos" data-aos="fade-up">
							<div class="journey-client-main">
								<div class="journey-client-counter">
									<h2 class="counter"><?php echo $counter_list[ 'counter_numbers' ];?></h2>
									<span>+</span>
								</div>
								<h5><?php echo $counter_list[ 'counter_title' ];?></h5>
							</div>
						</div>
					</div>
 <?php } ?>	 
</div>
	</div>
  <?php 	}   ?>
				 
 <?php }

	 
}