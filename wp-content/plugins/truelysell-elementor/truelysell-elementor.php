<?php
/*
 * Plugin Name: Truelysell Elementor
 * Version: 1.8.0
 * Plugin URI: https://dreamstechnologies.com/
 * Description: Truelysell widgets for Elementor
 * Author: Dreams Technologies
 * Author URI: https://dreamstechnologies.com/
 * Text Domain: truelysell_elementor
 * Domain Path: /languages/
 * @package WordPress
 * @author Truelysell
 * @since 1.0.0
 */


define( 'ELEMENTOR_TRUELYSELL', __FILE__ );


/**
 * Include the Elementor_Truelysell class.
 */
require plugin_dir_path( ELEMENTOR_TRUELYSELL ) . 'class-elementor-truelysell.php';