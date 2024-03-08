<?php 

	add_action( 'cmb2_admin_init', 'truelysell_add_demo_metabox' );
	/**
	 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	function truelysell_add_demo_metabox() {
		$prefix = 'truelysell_';
		/* get the registered sidebars */
	    global $wp_registered_sidebars;

	    $sidebars = array();
	    foreach( $wp_registered_sidebars as $id=>$sidebar ) {
	      $sidebars[ $id ] = $sidebar[ 'name' ];
	    }
		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$truelysell_page_mb = new_cmb2_box( array(
			'id'            => $prefix . 'page_metabox',
			'title'         => esc_html__( 'Page Options', 'truelysell' ),
			'object_types'  => array( 'page' ), // Post type
			'priority'   => 'high',
		) );

		$truelysell_page_mb->add_field( array(
			'name' => esc_html__( 'Full-width header', 'truelysell' ),
			'desc' => esc_html__( 'Enables full-width header for this page, even if it disabled in global settings', 'truelysell' ),
			'type' => 'select',
		    'default' => 'use_global',
		    'options'     => array(
				'use_global' 	=> esc_html__( 'Use Global setting from Customizer', 'truelysell' ),
				'disable' 		=> esc_html__( 'Disable', 'truelysell' ),
				'enable'     	=> esc_html__( 'Enable, always', 'truelysell' ),
			),
		) );

		global $wpdb;

		/* video */ 
		$truelysell_page_mb->add_field( array(
			'name'             => esc_html__( 'Page Layout', 'truelysell' ),
			'desc'             => esc_html__( 'Select page layout, default is full-width', 'truelysell' ),
			'id'               => $prefix . 'page_layout',
			'type'             => 'radio_inline',
			'default'			=> 'full-width',
			'options'          => array(
				'full-width' 		=> esc_html__( 'Full width', 'truelysell' ),
				'left-sidebar'   	=> esc_html__( 'Left Sidebar', 'truelysell' ),
				'right-sidebar'     => esc_html__( 'Right Sidebar', 'truelysell' ),
			),
		) );

		$truelysell_page_mb->add_field( array( 
			'name'    => esc_html__( 'Selected Sidebar', 'truelysell' ),
			'id'      => $prefix . 'sidebar_select',
			'type'    => 'select',
			'default' => 'sidebar-1',
			'options' => $sidebars,
		) );
	}
?>