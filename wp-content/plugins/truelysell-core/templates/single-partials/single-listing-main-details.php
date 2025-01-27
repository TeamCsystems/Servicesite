<!-- Main Details -->
<?php 

$type = get_post_meta($post->ID, '_listing_type',true);
$details_list = array();
switch ($type) {
	case 'service':
		$details_list = Truelysell_Core_Meta_Boxes::meta_boxes_service(); 
		break;	
		
	case 'rental':
		$details_list = Truelysell_Core_Meta_Boxes::meta_boxes_rental(); 
		break;	
	case 'event':
		$details_list = Truelysell_Core_Meta_Boxes::meta_boxes_event(); 
		break;
	case 'classifieds':
		$details_list = Truelysell_Core_Meta_Boxes::meta_boxes_classifieds(); 
		if(!empty($details_list)){
			unset($details_list['fields']['_classifieds_price']);	
		}
		
		break;
	
	default:

		break;
}


$class = (isset($data->class)) ? $data->class : 'listing-details' ;
$output = '';
?>


<?php 
if(isset($details_list['fields'])) :
	foreach ($details_list['fields'] as $detail => $value) {

		if(isset($value['icon']) && !empty($value['icon'])){
				$check_if_im = substr($value['icon'], 0, 3);
                if($check_if_im == 'im ') {
                   $icon = ' <i class="'.esc_attr($value['icon']).'"></i>'; 
                } else {
                  $icon = ' <i class="fa '.esc_attr($value['icon']).'"></i>'; 
                }
			
		} else {
			$icon = '<i class="fas fa-check"></i>';
		}

		if( in_array($value['type'], array('select_multiple','multicheck_split','multicheck')) ) {
			$meta_value = get_post_meta($post->ID, $value['id'],false);	
		} else {
			$meta_value = get_post_meta($post->ID, $value['id'],true);	
		};
	
		if($meta_value == 'check_on' || $meta_value == 'on') {
			$output .= '<li class="checkboxed single-property-detail-'.$value['id'].'"><div class="single-property-detail-label-'.$value['id'].'">'. $value['name'].'</div></li>';	
		} else {
			if(!empty($meta_value)){
				if($value['type'] == 'datetime' || in_array($value['id'], array('_event_date','_event_date_end')) ){
					
						$meta_value_date = explode(' ', $meta_value,2); 
						
						$date_format = get_option( 'date_format' );
					
						
						
						
						$meta_value_ = DateTime::createFromFormat(truelysell_date_time_wp_format_php(), $meta_value_date[0]);
						if(!is_string($meta_value_)) {
						    $meta_value_stamp = $meta_value_->getTimestamp();
						    $meta_value = date_i18n(get_option( 'date_format' ),$meta_value_stamp);
						} else {
						    $meta_value = $meta_value_date[0];
						}
												
						if( isset($meta_value_date[1]) ) { 
							$time = str_replace('-','',$meta_value_date[1]);
							$meta_value .= esc_html__(' at ','truelysell_core'); 
							$meta_value .= date_i18n(get_option( 'time_format' ), strtotime($time));

						}

				}
			}
			if(in_array($value['id'], array('_id','_ID','_Id'))){
				$meta_value = apply_filters('truelysell_listing_id',$post->ID);
			}
		
			if(!empty($meta_value)) {
				if($value['id'] == '_area'){
					$scale = get_option( 'truelysell_scale', 'sq ft' );			
					if( isset($value['invert']) && $value['invert'] == true ) {		

						$output .= '<li class="main-detail-'.$value['id'].'">'.$icon. apply_filters('truelysell_scale',$scale).' <span>'.$meta_value.'</span> </li>';
					} else {
						$output .= '<li class="main-detail-'.$value['id'].'">'.$icon.'<span>'.$meta_value.'</span> '.apply_filters('truelysell_scale',$scale).' </li>';	
					}
					
				} else if($details_list['fields'][$detail]['type']=='file') {
					$output .= '<li class="main-detail-'.$value['id'].' truelysell-download-detail"> <a href="'.$meta_value.'" /> '.esc_html__('Download','truelysell_core').' '.wp_basename($meta_value).' </a></li>';
				} else {
			
					if(isset($details_list['fields'][$detail]['options']) && !empty($details_list['fields'][$detail]['options'])) {

						if(is_array($meta_value) && !empty($meta_value)) {
							
									
							if( isset($value['invert']) && $value['invert'] == true ) {		
									$output .= '<li class="main-detail-'.$value['id'].'">'.$icon.'<span>';
									$i=0;
									$last = count($meta_value);

									
									foreach ($meta_value as $key => $saved_value) {	
										$i++;
										if(isset($details_list['fields'][$detail]['options'][$saved_value]))
											$output .= $details_list['fields'][$detail]['options'][$saved_value];
											if($i >= 1 && $i < $last) : $output .= ", "; endif;
										
										
									}
									$output .= '</span> <div class="single-property-detail-label-'.$value['id'].'">'. $value['name'].'</div> </li>';			
								
							} else {						
									
								$output .= '<li class="main-detail-'.$value['id'].'">'.$icon.'<div class="single-property-detail-label-'.$value['id'].'">'. $value['name'].'</div> <span>';
									
									$i=0;
									
								
									$last = count($meta_value);
									
									foreach ($meta_value as $key => $saved_value) {	
										$i++;
										if($details_list['fields'][$detail]['type'] == 'select_multiple') {

											if(isset($details_list['fields'][$detail]['options'][$saved_value]))
												$output .= $details_list['fields'][$detail]['options'][$saved_value];
												if($i >= 0 && $i < $last) : $output .= ", "; 
											endif;
										
										} else {
										
											if(isset($details_list['fields'][$detail]['options'][$saved_value]))
												$output .= $details_list['fields'][$detail]['options'][$saved_value];
												if($i >= 0 && $i < $last) : $output .= ", "; 
											endif;	
										}
										
										
									}
								$output .= '</span></li>';
							}
							

						} else {

							if( isset($value['invert']) && $value['invert'] == true ) {	
								if(isset($details_list['fields'][$detail]['options'][$meta_value])){
									$output .= '<li class="main-detail-'.$value['id'].'">'.$icon.'<span>'.$details_list['fields'][$detail]['options'][$meta_value].'</span> <div class="single-property-detail-label-'.$value['id'].'">'. $value['name'].'</div> </li>';			
								}	
							} else {
								$output .= '<li class="main-detail-'.$value['id'].'">'.$icon.'<div class="single-property-detail-label-'.$value['id'].'">'. $value['name'].'</div> <span>'.$details_list['fields'][$detail]['options'][$meta_value].'</span></li>';
							}

						}
						
						
					} else {

						if( isset($value['invert']) && $value['invert'] == true ) {	
							$output .= '<li class="main-detail-'.$value['id'].'">'.$icon.'<div class="single-property-detail-label-'.$value['id'].'">'. $value['name'].'</div> <span>';
							$output .= (is_array($meta_value)) ? implode(",", $meta_value) : $meta_value ;
							$output .= '</span></li>';	
						} else {
							$output .= '<li class="main-detail-'.$value['id'].'">'.$icon.'<span>';
							$output .= (is_array($meta_value)) ? implode(",", $meta_value) : $meta_value ;
							$output .= '</span> <div class="single-property-detail-label-'.$value['id'].'">'. $value['name'].'</div> </li>';		
						}
						
					}
					
				}
				
			}
		}
	}
endif;
if(!empty($output)) : ?>
<ul class="<?php esc_attr_e($class); ?>">
	<?php echo $output; ?>
</ul>
<?php endif; ?>