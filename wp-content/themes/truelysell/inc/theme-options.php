<?php
/**
 * Truelysell Theme Options
 *
 * @package truelysell
 */

/**
 * Add color styling from theme
 */
function truelysell_hex2RGB($hex) 
{
        preg_match("/^#{0,1}([0-9a-f]{1,6})$/i",$hex,$match);
        if(!isset($match[1]))
        {
            return false;
        }

        if(strlen($match[1]) == 6)
        {
            list($r, $g, $b) = array($hex[0].$hex[1],$hex[2].$hex[3],$hex[4].$hex[5]);
        }
        elseif(strlen($match[1]) == 3)
        {
            list($r, $g, $b) = array($hex[0].$hex[0],$hex[1].$hex[1],$hex[2].$hex[2]);
        }
        else if(strlen($match[1]) == 2)
        {
            list($r, $g, $b) = array($hex[0].$hex[1],$hex[0].$hex[1],$hex[0].$hex[1]);
        }
        else if(strlen($match[1]) == 1)
        {
            list($r, $g, $b) = array($hex.$hex,$hex.$hex,$hex.$hex);
        }
        else
        {
            return false;
        }

        $color = array();
        $color['r'] = hexdec($r);
        $color['g'] = hexdec($g);
        $color['b'] = hexdec($b);

        return $color;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function truelysell_theme_options_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'truelysell_theme_options_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function truelysell_js_customize() {
    wp_enqueue_script( 'truelysell_customizer', get_template_directory_uri() . 'assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'truelysell_js_customize' );



 
function theme_customize_register( $wp_customize ) {
 
 	
	$wp_customize->add_section( 'mytheme_new_section_name' , array(
		'title'      => __( 'Visible Section Name', 'truelysell' ),
		'priority'   => 30,
	   
	   ) );

      // Text color
    $wp_customize->add_setting( 'primary_color', array(
      'default'   => '#4c40ed',
      'sanitize_callback' => 'sanitize_hex_color',
       'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Primary color', 'truelysell' ),
      'show_opacity' => true,
    
    ) ) );

      // Text color
      $wp_customize->add_setting( 'primary_hover_color', array(
        'default'   => '#2229C1',
        'sanitize_callback' => 'sanitize_hex_color',
         'transport' => 'refresh',
      ) );
  
      $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_hover_color', array(
        'section' => 'colors',
        'label'   => esc_html__( 'Primary Hover color', 'truelysell' ),
        'show_opacity' => true,
      
      ) ) );

    // Text color
    $wp_customize->add_setting( 'secondary_color', array(
      'default'   => '#F7F7FF',
	    'sanitize_callback' => 'sanitize_hex_color',
      'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Secondary color', 'truelysell' ),
    ) ) );

    // Text color
    $wp_customize->add_setting( 'breadcrumb_color', array(
      'default'   => '#F7F7FF',
	    'sanitize_callback' => 'sanitize_hex_color',
      'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'breadcrumb_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Breadcrumb color', 'truelysell' ),
    ) ) );
  
    // Text color
    $wp_customize->add_setting( 'text_color', array(
      'default'   => '#74788D',
	     'sanitize_callback' => 'sanitize_hex_color',
      'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Text color', 'truelysell' ),
    ) ) );

    // Link color
    $wp_customize->add_setting( 'link_color', array(
      'default'   => '#4c40ed',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Link color', 'truelysell' ),
    ) ) );

    

    // Border color
    $wp_customize->add_setting( 'heading_color', array(
      'default'   => '#28283C',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'heading_color', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Heading color', 'truelysell' ),
    ) ) );

    // Sidebar background
    $wp_customize->add_setting( 'sidebar_background', array(
      'default'   => '#F7F7FF',
      'transport' => 'refresh',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidebar_background', array(
      'section' => 'colors',
      'label'   => esc_html__( 'Sidebar Background', 'truelysell' ),
    ) ) );
	
  }
  add_action( 'customize_register', 'theme_customize_register' );
?>


  <?php
  function theme_get_customizer_css() {
    ob_start();

    
    $primary_color = get_theme_mod( 'primary_color', '#4C40ED' );
    if ( ! empty( $primary_color ) ) {
      ?>
      :root {
        --ts_primary_color: <?php echo  $primary_color; ?>;
        --ts_primary_trans_color: <?php echo  $primary_color; ?>99;
      }

      <?php
    }
    $primary_hover_color = get_theme_mod( 'primary_hover_color', '#2229C1' );
    if ( ! empty( $primary_hover_color ) ) {
      ?>
      :root {
        --ts_primary_hover_color: <?php echo  $primary_hover_color; ?>;
       }
      <?php
    }

    

    $secondary_color = get_theme_mod( 'secondary_color', '#F7F7FF' );
    if ( ! empty( $secondary_color ) ) {
      ?>
      :root {
        --ts_secondary_color: <?php echo $secondary_color; ?>;
      }

      <?php
    }

    $breadcrumb_color = get_theme_mod( 'breadcrumb_color', '#F7F7FF' );
    if ( ! empty( $breadcrumb_color ) ) {
      ?>
      :root {
        --ts_breadcrumb_color: <?php echo $breadcrumb_color; ?>;
      }

      <?php
    }



    $text_color = get_theme_mod( 'text_color', '#74788D' );
    if ( ! empty( $text_color ) ) {
      ?>
      :root {
        --ts_text_color: <?php echo $text_color; ?>;
      }

      <?php
    }


    $link_color = get_theme_mod( 'link_color', '#4C40ED' );
    if ( ! empty( $link_color ) ) {
      ?>
      :root {
        --ts_link_color: <?php echo $link_color; ?>;
      }
      <?php
    }

    
    $heading_color = get_theme_mod( 'heading_color', '#28283c' );
    if ( ! empty( $heading_color ) ) {
      ?>
      :root {
        --ts_heading_color: <?php echo $heading_color; ?>;
      }
      <?php
    }

 
     
    $sidebar_background = get_theme_mod( 'sidebar_background', '#F7F7FF' );
    if ( ! empty( $sidebar_background ) ) {
      ?>
      :root {
        --ts_sidebar_bgcolor: <?php echo $sidebar_background; ?>;
      }
      <?php
    }

    $css = ob_get_clean();
    return $css;
  }

// Modify our styles registration like so:

function theme_enqueue_styles() {
  wp_enqueue_style( 'theme-styles', get_stylesheet_uri() ); // This is where you enqueue your theme's main stylesheet
  $custom_css = theme_get_customizer_css();
  wp_add_inline_style( 'theme-styles', $custom_css );
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



