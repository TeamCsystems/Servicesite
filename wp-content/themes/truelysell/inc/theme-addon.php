<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package truelysell
 */


 if ( ! function_exists( 'truelysell_string_limit_words' ) )  {
	function truelysell_string_limit_words($string, $word_limit) {
	    $words = explode(' ', $string, ($word_limit + 1));
	    if (count($words) > $word_limit) {
	        array_pop($words);
	        //add a ... at last article when more than limit word count
	        return implode(' ', $words) ;
	    } else {
	        //otherwise
	        return implode(' ', $words);
	    }
	}
}

 if ( ! function_exists( 'truelysell_display_review_stars' ) ) {
	/**
	 * Displays review stars
	 * 
	 * @param float $average_rating
	 */
	function truelysell_display_review_stars( $rating ) {
		for ( $i = 0; $i < 5; $i ++ ) {
			$diff = $rating - $i;

			$i_class = '';

			if ( 1 <= $diff ) {
				$i_class .= ' fas fa-star filled';
			} elseif ( 0 < $diff && 0.95 > $diff ) {
				$i_class .= ' fas fa-star-half-alt filled';
			} else {
				$i_class .= ' fas fa-star';
			}

			if ( $i == 4 ) {
				$i_class .= ' me-n1-half';
			} else {
				$i_class .= ' me-n1';
			}

			$i_class .= ' ' . $diff;
		
			?>
			<i class="<?php echo esc_attr( $i_class ); ?>"></i>
			<?php
		}
	}
}

if ( ! function_exists( 'truelysell_display_course_rating' ) ) {
	/**
	 * Displays course rating
	 */
	function truelysell_display_course_rating( $rating_avg,  $text_class = ' text-muted' ) {
		if ( 0 < floatval( $rating_avg ) ): ?>
			<span>
				<?php truelysell_display_review_stars( $rating_avg ); ?>
			</span>
 		 
		<?php else: ?>
			<span class="<?php echo esc_attr( $text_class ); ?>">
			<i class="fas fa-star"></i>
			<i class="fas fa-star"></i>
			<i class="fas fa-star"></i>
			<i class="fas fa-star"></i>
			<i class="fas fa-star"></i>
			</span>
		<?php endif;
	}
}



function truelysell_get_svgicon( $value ) {
    if ( ! isset( $value) ) {
      return '';
    }

    return truelysell_get_inline_svg( $value );
  }

function truelysell_get_inline_svg( $attachment_id ) {
    $svg = get_post_meta( $attachment_id, '_elementor_inline_svg', true );

    if ( ! empty( $svg ) ) {
      return $svg;
    }

    $attachment_file = get_attached_file( $attachment_id );

    if ( ! $attachment_file ) {
      return '';
    }

    $svg = WP_Filesystem( $attachment_file );

    if ( ! empty( $svg ) ) {
      update_post_meta( $attachment_id, '_elementor_inline_svg', $svg );
    }

    return $svg;
  }


add_action('pre_user_query', 'truelysell_get_users_search');
function truelysell_get_users_search( $args ) {
    if( isset( $args->query_vars['and2or'] ) )
        $args->query_where = str_replace(') AND (', ') OR (', $args->query_where);
}

function truelysell_get_elementor_widget( $data, $findkey ) {
        if ( is_array( $data ) ) {
          foreach ( $data as $d ) {

            if ( $d && ! empty( $d['id'] ) && $d['id'] === $findkey ) {
              return $d;
            }
            if ( $d && ! empty( $d['elements'] ) && is_array( $d['elements'] ) ) {
              $value = truelysell_get_elementor_widget( $d['elements'], $findkey );
              if ( $value ) {
                return $value;
              }
            }
          }
        }

        return false;
      }

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function truelysell_array_search($needle,$haystack) {
  foreach($haystack as $key=>$value) {
      $current_key=$key;
      if($needle===$value OR (is_array($value) && truelysell_array_search($needle,$value) !== false)) {
          return $current_key;
      }
  }
  return false;
}


function truelysell_get_rating($average) {
     if(!$average) {
               $class="no-stars";
     } else {
          switch ($average) {
               
               case $average >= 1 && $average < 1.5:
                    $class="one-stars";
                    break;
               case $average >= 1.5 && $average < 2:
                    $class="one-and-half-stars";
                    break;
               case $average >= 2 && $average < 2.5:
                    $class="two-stars";
                    break;
               case $average >= 2.5 && $average < 3:
                    $class="two-and-half-stars";
                    break;
               case $average >= 3 && $average < 3.5:
                    $class="three-stars";
                    break;
               case $average >= 3.5 && $average < 4:
                    $class="three-and-half-stars";
                    break;
               case $average >= 4 && $average < 4.5:
                    $class="four-stars";
                    break;
               case $average >= 4.5 && $average < 5:
                    $class="four-and-half-stars";
                    break;
               case $average >= 5:
                    $class="five-stars";
                    break;

               default:
                    $class="no-stars";
                    break;
          }
     }
     return $class;
     }

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function truelysell_auto_discovery_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'truelysell_auto_discovery_header' );

function truelysell_get_body_classes( $classes ) {
      // Adds a class of group-blog to blogs with more than 1 published author.
      if ( is_multi_author() ) {
        $classes[] = 'group-blog';
      }
    
      global $post;
    
      // Adds a class of hfeed to non-singular pages.
      if ( ! is_singular() ) {
        $classes[] = 'hfeed';
      }
    
      $submit_page = truelysell_fl_framework_getoptions('submit_page');
      if(is_page($submit_page)){
          $classes[] = 'add-listing-dashboard-template';   
      }
    
      if(!is_user_logged_in()){
          $classes[] = 'user_not_logged_in';
      }
      if( ( is_page_template('template-home-search.php') || is_page_template('template-home-search-splash.php') )  && (get_option('truelysell_home_transparent_header') == 'enable')){
          $classes[] = 'transparent-header';   
      } else {
          $classes[] = 'solid-header';   
      }
    
      if(is_page_template('template-home-search.php')  && (get_option('truelysell_home_solid_background') == 'enable')){
          $classes[] = 'solid-bg-home-banner';   
      }
    
      
      if(is_post_type_archive('listing') && get_option('pp_listings_top_layout') == 'half'){
            $classes[] = 'page-template-template-split-map';   
      }
      if(get_option('truelysell_fw_header') || is_page_template('template-home-search-splash.php')){
        $classes[] = 'full-width-header';
      }
      if(get_option('truelysell_marker_no_icon') == 'no_icon'){
        $classes[] = 'no-map-marker-icon ';
      }
      return $classes;
    }
    
    
    add_filter( 'body_class', 'truelysell_get_body_classes' );

function truelysell_get_fontawesome_icons( $provider_id, $provider_name, $authenticate_url )
{
   ?>
   <a 
      rel           = "nofollow"
      href          = "<?php echo esc_html($authenticate_url); ?>"
      data-provider = "<?php echo esc_html($provider_id) ?>"
      class         = "wp-social-login-provider wp-social-login-provider-<?php echo strtolower( $provider_id ); ?>" 
    >
      <span>
         <i class="fa fa-<?php echo strtolower( $provider_id ); ?>"></i><?php echo esc_html($provider_name); ?>
      </span>
   </a>
<?php
}
 
add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'truelysell_get_fontawesome_icons', 10, 3 );
/**
 * Customize the PageNavi HTML before it is output
 */
add_filter( 'wp_pagenavi', 'truelysell_get_pagination', 10, 2 );
function truelysell_get_pagination($html) {
    $out = '';
    //wrap a's and span's in li's
    
    $out = str_replace("<a","<li><a",$html);
    $out = str_replace("</a>","</a></li>",$out);
    $out = str_replace("<span","<li><span",$out);
    $out = str_replace("</span>","</span></li>",$out);
    $out = str_replace("<div class='wp-pagenavi' role='navigation'>","",$out);
    $out = str_replace("</div>","",$out);
    return '<div class="pagination"><ul>'.$out.'</ul></div>';
}

function truelysell_sticky_disable($sticky){
	if(is_404()){
		$sticky = false;
	}
	return $sticky;
}
add_action('truelysell_sticky_footer_filter','truelysell_sticky_disable');
 
// This filter allow a wp_dropdown_categories select to return multiple items
add_filter( 'wp_dropdown_cats', 'truelysell_willy_wp_dropdown_cats_multiple', 10, 2 );
function truelysell_willy_wp_dropdown_cats_multiple( $output, $r ) {
  if ( ! empty( $r['multiple'] ) ) {
    $output = preg_replace( '/<select(.*?)>/i', '<select$1 multiple="multiple">', $output );
    $output = preg_replace( '/name=([\'"]{1})(.*?)\1/i', 'name=$2[]', $output );
  }
  return $output;
}

// This Walker is needed to match more than one selected value
class Willy_Walker_CategoryDropdown extends Walker_CategoryDropdown {
  public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
    $pad = str_repeat('&nbsp;', $depth * 3);

    /** This filter is documented in wp-includes/category-template.php */
    $cat_name = apply_filters( 'list_cats', $category->name, $category );

    if ( isset( $args['value_field'] ) && isset( $category->{$args['value_field']} ) ) {
      $value_field = $args['value_field'];
    } else {
      $value_field = 'term_id';
    }

    $output .= "\t<option class=\"level-$depth\" value=\"" . esc_attr( $category->{$value_field} ) . "\"";

    // Type-juggling causes false matches, so we force everything to a string.
    if ( in_array( $category->{$value_field}, (array)$args['selected'], true ) )
      $output .= ' selected="selected"';
    $output .= '>';
    $output .= $pad.$cat_name;
    if ( $args['show_count'] )
      $output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
    $output .= "</option>\n";
  }
}