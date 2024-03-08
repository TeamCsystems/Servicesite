<!-- Section -->
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	$field = $data->field;
	$key = $data->key;
 
$days = truelysell_get_days();

?>
<!-- Day -->
<?php

 foreach ($days as $id => $dayname) { 
 	
 		$opening_val = (isset($field['value'][$id.'_opening'])) ? $field['value'][$id.'_opening'] : false;
 		$closing_val = (isset($field['value'][$id.'_closing'])) ? $field['value'][$id.'_closing'] : false;
 		$count = 0;

 		?>
		<div class="opening-day">
			<div class="row">
			<div class="col-md-12">
				<label><?php echo esc_html($dayname) ?> </label>
				<span class='day_hours_reset badge badge-danger float-end right-0'><?php esc_html_e('Clear Time','truelysell_core') ?></span>
				
  			</div>
			<?php 
			if(is_array($opening_val)) { 
				
				foreach ($opening_val as $key => $opening) { 
				 if($count>0) { 
				 	?>
				 	<div class="row">
					 	<div class="col-md-2 opening_val opening-day-tools"><br><a class="opening-day-remove button" data-remove="<?php echo sanitize_title($dayname); ?>-opening-hours-row<?php echo $count; ?>" href="#"><?php esc_html_e('Remove','truelysell_core'); ?></a>
					 	</div>
				 <?php } ?>
				 	<div class="col-md-6 opening_val <?php echo sanitize_title($dayname); ?>-opening-hours-row<?php echo $count; ?>">
					
						<input type="text" class="truelysell-flatpickr  form-control" name="_<?php echo esc_attr($id); ?>_opening_hour[]" placeholder="<?php esc_html_e('Opening Time','truelysell_core'); ?>" value="<?php echo esc_attr($opening); ?>">
							
					</div>

					<div class="col-md-6 opening_val <?php echo sanitize_title($dayname); ?>-opening-hours-row<?php echo $count; ?>" >
						
						<input type="text" class="truelysell-flatpickr  form-control" name="_<?php echo esc_attr($id); ?>_closing_hour[]" placeholder="<?php esc_html_e('Closing Time','truelysell_core'); ?>" value="<?php echo esc_attr($closing_val[$key]); ?>">
					
					</div>	

					
				</div>	
				<?php 
				$count++;
				} 
			} else { ?>
				
					<div class="col-md-6">
						<input type="text" class="truelysell-flatpickr  form-control" name="_<?php echo esc_attr($id); ?>_opening_hour[]" placeholder="<?php esc_html_e('Opening Time','truelysell_core'); ?>" value="<?php echo esc_attr($opening_val); ?>">
							
					</div>
					<div class="col-md-6">
						<input type="text" class="truelysell-flatpickr form-control" name="_<?php echo esc_attr($id); ?>_closing_hour[]" placeholder="<?php esc_html_e('Closing Time','truelysell_core'); ?>" value="<?php echo esc_attr($closing_val); ?>">
						
					</div>	
					 
				</div>	
			<?php } ?>
			
 
			
				
			
		</div>
		<!-- Day / End -->
<?php } ?>
							