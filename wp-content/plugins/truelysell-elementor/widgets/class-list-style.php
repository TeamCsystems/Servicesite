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
class Widget_Truelysell_list extends Widget_Base {

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
		return 'truelysell-list-style';
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
		return __( 'Truelysell List Style', 'truelysell_elementor' );
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
		return 'eicon-editor-list-ul';
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
			'section_toggle',
			[
				'label' => esc_html__( 'Toggle', 'truelysell_elementor' ),
			]
		);
 		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Title & Description', 'elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Toggle Title', 'elementor' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'tab_content',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Toggle Content', 'elementor' ),
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);


		$this->add_control(
			'tabs',
			[
				'label' => esc_html__( 'Toggle Items', 'truelysell_elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Toggle #1', 'truelysell_elementor' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'truelysell_elementor' ),
					],
					[
						'tab_title' => esc_html__( 'Toggle #2', 'truelysell_elementor' ),
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
		<div class="faq-section">

		
							
			<?php
			foreach ( $settings['tabs'] as $index => $item ) :
				$tab_count = $index + 1;

				$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

				$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

				$this->add_render_attribute( $tab_title_setting_key, [
					'id' => 'elementor-tab-title-' . $id_int . $tab_count,
					'class' => [ 'elementor-tab-title' ],
					'data-tab' => $tab_count,
					'role' => 'tab',
					'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
					'aria-expanded' => 'false',
				] );

				$this->add_render_attribute( $tab_content_setting_key, [
					'id' => 'elementor-tab-content-' . $id_int . $tab_count,
					'class' => [ 'elementor-tab-content', 'elementor-clearfix' ],
					'data-tab' => $tab_count,
					'role' => 'tabpanel',
					'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
				] );

				$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
				?>

                              <div class="faq-card">
								<h4 class="faq-title">
									<a class="collapsed" data-bs-toggle="collapse" href="#faq_<?php echo $tab_count; ?>" aria-expanded="false"><?php $this->print_unescaped_setting( 'tab_title', 'tabs', $index ); ?></a>
								</h4>
								<div class="card-collapse collapse" id="faq_<?php echo $tab_count; ?>">
				          	    <?php Utils::print_unescaped_internal_string( $this->parse_text_editor( $item['tab_content'] ) ); ?>
								</div>
							</div>	

				 
			<?php endforeach; ?>
					
		</div>

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
