<?php
if(isset($_GET[$data->name])) {
	$value = stripslashes(sanitize_text_field($_GET[$data->name]));
} else {
	if(isset($data->default) && !empty($data->default)){
		$value = $data->default;
	} else {
		$value = '';	
	}
} 
?>
 <?php global $template;  ?>
 

 <div class="filter-content">
	<div class="form-group mb-0">
	<h2><?php echo esc_attr($data->labeltext);?></h2>
        <input  autocomplete="off" name="<?php echo esc_attr($data->name);?>" id="<?php echo esc_attr($data->name);?>" type="text" placeholder="<?php echo esc_attr($data->placeholder);?>" value="<?php if(isset($value)){ echo esc_attr($value); }  ?>" class="form-control"/>
     </div>
 </div>

	 
