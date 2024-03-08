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
  <?php
  global $template;  
 
  if($data->css_class=='style1')  { ?>
	<div class="search-group-icon search-icon">
      <i class="<?php echo $data->icon_class; ?>"></i>
    </div>
	<div class="form-group mb-0">
    <label><?php echo esc_attr($data->labeltext);?></label>
        <input  autocomplete="off" name="<?php echo esc_attr($data->name);?>" id="<?php echo esc_attr($data->name);?>" type="text" placeholder="<?php echo esc_attr($data->placeholder);?>" value="<?php if(isset($value)){ echo esc_attr($value); }  ?>" class="form-control"/>
     </div>
 
	 <?php } else if($data->css_class=='style2') { ?>
	<i class="<?php echo $data->icon_class; ?>"></i>
	<div class="form-group mb-0">
         <input  autocomplete="off" name="<?php echo esc_attr($data->name);?>" id="<?php echo esc_attr($data->name);?>" type="text" placeholder="<?php echo esc_attr($data->placeholder);?>" value="<?php if(isset($value)){ echo esc_attr($value); }  ?>" class="form-control"/>
     </div>

 <?php } ?>
