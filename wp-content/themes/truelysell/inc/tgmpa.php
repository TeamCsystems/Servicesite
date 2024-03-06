<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme truelysell for publication on WordPress.org
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'truelysell_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function truelysell_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

	    array(
	        'name'                  => 'Truelysell Core',
	        'slug'                  => 'truelysell-core',
	        'source'                => get_template_directory() . '/plugins/truelysell-core.zip',
	        'version'               => '1.8.0',
	        'required'              => true,
	    ),	 	    
	     
	     array(
	        'name'                  => 'Truelysell Forms and Fields Editor',
	        'slug'                  => 'truelysell-forms-and-fields-editor',
	        'source'                => get_template_directory() . '/plugins/truelysell-forms-and-fields-editor.zip',
	        'version'               => '1.8.0',
	        'required'              => true,
		 ),
		 array(
	        'name'                  => 'Simple Shortcode',
	        'slug'                  => 'simple-shortcode',
	        'source'                => get_template_directory() . '/plugins/simple-shortcode.zip',
	        'version'               => '1.8.0',
	        'required'              => true,
		 ),
    	array(
			'name'      			=> esc_html__('Contact Form 7','truelysell' ),
			'slug'      			=> 'contact-form-7',
			'required'  			=> false,
		),
			
    	array(
			'name'      			=> esc_html__('WooCommerce','truelysell' ),
			'slug'      			=> 'woocommerce',
			'required'  			=> true,
		),

		array(
			'name'      			=> esc_html__('Order auto complete for WooCommerce','truelysell' ),
			'slug'      			=> 'order-auto-complete-for-woocommerce',
			'required'  			=> true,
		),
		
		array(
			'name'      			=> esc_html__('Elementor Website Builder','truelysell' ),
			'slug'      			=> 'elementor',
			'required'  			=> true,
		),
		
		array(
			'name'      			=> esc_html__('Redux Framework','truelysell' ),
			'slug'      			=> 'redux-framework',
			'source'                => get_template_directory() . '/plugins/redux-framework.zip',
			'required'  			=> true,
		),
		array(
			'name'      			=> esc_html__('One Click Demo Import','truelysell' ),
			'slug'      			=> 'one-click-demo-import',
			'source'                => get_template_directory() . '/plugins/one-click-demo-import.zip',
			'required'  			=> true,
		),
	);

if(get_option('truelysell_page_builder') == 'js_composer'){
	$plugins[] = 
		   array(
	        'name'                  => 'WPBakery Page Builder', // The plugin name
	        'slug'                  => 'js_composer', // The plugin slug (typically the folder name)
	        'source'                => get_template_directory() . '/plugins/js_composer.zip', // The plugin source
	        'required'              => false, // If false, the plugin is only 'recommended' instead of required
	        'version'               => '6.8.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
	        'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
	        'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
	        'external_url'      => '', // If set, overrides default API URL and points to an external URL
	    );

	    

} else {

$plugins[] = 
	    array(
			'name'      			=> 'Elementor',
			'slug'      			=> 'elementor',
			'required'  			=> true,
		
	 );
   $plugins[] = array(
	        'name'                  => 'Truelysell Elementor',
	        'slug'                  => 'truelysell-elementor',
	        'source'                => get_template_directory() . '/plugins/truelysell-elementor.zip',
	        'version'               => '1.8.0',
	        'required'              => true,
	    ); 
}

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'truelysell',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

	
	);

	tgmpa( $plugins, $config );
}
