<?php 
$flag_enabled = false;

if(isset($data->state) && $data->state == 'on'){
	$flag_enabled = true;
}
if(isset($_GET[$data->name.'_range']) && !empty($_GET[$data->name.'_range']) && $_GET[$data->name.'_range'] != 'NaN') {
	$min = sanitize_text_field($_GET[''.$data->name.'_range']);
	$min = array_map( 'absint', explode( ',', $min ) );
	$min = (int)preg_replace('/[^0-9]/', '', $min[0]);
	$flag_enabled = true;
	if($data->name == '_price'){
		$data->min = Truelysell_Core_Search::get_min_meta_value($data->name);
	} else {
		$data->min = Truelysell_Core_Search::get_min_meta_value($data->name);
	}
} else {
	if($data->min == 'auto') {

		if($data->name == '_price'){

			$min = Truelysell_Core_Search::get_min_meta_value($data->name);
			$data->min = $min;
		} else {
			$min = Truelysell_Core_Search::get_min_meta_value($data->name);
			$data->min = Truelysell_Core_Search::get_min_meta_value($data->name);
		}
		
	} else {
		$min = $data->min;	
	}
} 

if(isset($_GET[$data->name.'_range']) && !empty($_GET[$data->name.'_range']) && $_GET[$data->name.'_range'] != 'NaN') {
	$max = sanitize_text_field($_GET[$data->name.'_range']);
	$max = array_map( 'absint', explode( ',', $max ) );
	$max = (int)preg_replace('/[^0-9]/', '', $max[1]);
	$flag_enabled = true;
	if($data->name == '_price'){
		$data->max = Truelysell_Core_Search::get_max_meta_value($data->name);
	} else {
		$data->max = Truelysell_Core_Search::get_max_meta_value($data->name);
	}
} else {
	if($data->max == 'auto') {
		if($data->name == '_price'){
			$max = Truelysell_Core_Search::get_max_meta_value($data->name);
			$data->max = $max;
		} else {
			$max = Truelysell_Core_Search::get_max_meta_value($data->name);
			$data->max = Truelysell_Core_Search::get_max_meta_value($data->name);
		}
	} else {
		$max = $data->max;	
	}
	
} 
if(!$max){
	$max = 1;
}
if(!$min){
	$min = 0;
}
?>

<!-- Range Slider -->

<div class="<?php if(isset($data->class)) { echo esc_attr($data->class); } ?> <?php if(isset($data->css_class)) { echo esc_attr($data->css_class); }?>" >
	<!-- Range Slider -->
	<?php 
		$currency_abbr = truelysell_fl_framework_getoptions('currency' );
		$currency_postion = truelysell_fl_framework_getoptions('currency_postion' );
		$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
	?>			
	
</div>