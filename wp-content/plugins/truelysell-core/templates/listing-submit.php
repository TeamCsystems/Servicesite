<?php
/**
 * listing Submission Form
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if(isset($_GET["action"]) && $_GET["action"] == 'edit' && !truelysell_core_if_can_edit_listing($data->listing_id) ){ ?>
	<div class="notification closeable notice">
		<?php esc_html_e('You can\'t edit that listing' , 'truelysell_core');?>
	</div>
<?php 
		return;
	}	
$current_user = wp_get_current_user();
$roles = $current_user->roles;
$role = array_shift( $roles ); 
if(!in_array($role,array('administrator','admin','owner','seller'))) :
	$template_loader = new Truelysell_Core_Template_Loader; 
	$template_loader->get_template_part( 'account/owner_only'); 
	return;
endif;

/* Get the form fields */
$fields = array();
if(isset($data)) :
	$fields	 	= (isset($data->fields)) ? $data->fields : '' ;
endif;

/* Determine the type of form */
	if(isset($_GET["action"])) {
		$form_type = $_GET["action"];
	} else {
		$form_type = 'submit';
	}
	
?>

<?php 
	if(isset($_POST['_listing_type'])) {
		$listing_type = $_POST['_listing_type'];
	} else {
		$listing_type = get_post_meta( $data->listing_id , '_listing_type', true );
		if(empty($listing_type)) {
			$listing_types = get_option('truelysell_listing_types',array( 'service', 'rental', 'event' ));
			if(is_array($listing_types) && sizeof($listing_types) == 1 ){
				$listing_type = $listing_types[0];
			} else {
				$listing_type = 'service';	
			}
			
		}
	}?>

<div class="submit-page <?php echo esc_attr('type-'.$listing_type); ?>">
<?php if ( $form_type === 'edit') { 
	?>
	<div class="alert alert-info"><?php esc_html_e('You are currently editing:' , 'truelysell_core'); if(isset($data->listing_id) && $data->listing_id != 0) {   $listing = get_post( $data->listing_id ); echo ' <a href="'.get_permalink( $data->listing_id ).'">'.$listing->post_title .'</a>';  }?></div> 
<?php } ?>
<?php
	if ( isset( $data->listing_edit ) && $data->listing_edit ) {
		?>
		<div class="alert alert-info">
		<?php printf( '<strong>' . __( "You are editing an existing listing. %s", 'truelysell_core' ) . '</strong>', '<a href="?new=1&key=' . $data->listing_edit . '">' . __( 'Add A New Listing', 'truelysell_core' ) . '</a>' ); ?>
		</div>
	<?php }
	?>
<form action="<?php  echo esc_url( $data->action ); ?>" method="post" id="submit-listing-form" class="listing-manager-form" enctype="multipart/form-data">
	

<?php

 foreach ( $fields as $key => $section ) :  

  ?>
	<!-- Section -->
	<?php 
		if(isset($data->listing_id)) {
			$switcher_value = get_post_meta($data->listing_id, '_'.$key.'_status',true);
		} else {
			$switcher_value = false;
		}
	?>

	<div class="add-listing-section <?php echo esc_attr(' '.$key.' '); 
		if(isset($section['onoff']) && $section['onoff'] == true && $switcher_value == 'on') { 
			echo esc_attr('switcher-on'); } ?>" >
			<div class="row">
		
		<!-- Headline -->
		<div class="add-listing-headline <?php if(isset($section['class'])) echo esc_html($section['class']); ?>">
			<h5 class="mb-4"><?php if(isset($section['icon']) && !empty($section['icon'])) : ?><i class="<?php echo esc_html($section['icon']); ?>"></i> <?php endif; ?>
				<?php if(isset($section['title'])) echo esc_html($section['title']); ?>
				<?php if($key=="slots"): ?>
					<br><span id="add-listing-slots-notice"><?php esc_html_e("By default booking widget in your listing has time picker. Enable this section to configure time slots.",'truelysell_core'); ?> </span>
				<?php endif; ?>
				<?php if($key=="availability_calendar"): ?>
					<br><span id="add-listing-slots-notice"><?php esc_html_e("Click date in calendar to mark the day as unavailable.",'truelysell_core'); ?> </span>
				<?php endif; ?>
			</h5>
				<?php if(isset($section['onoff']) && $section['onoff'] == true) : ?> 
					<!-- Switcher -->
					<?php 
					if(isset($data->listing_id)) {
						$value = get_post_meta($data->listing_id, '_'.$key.'_status',true);
						
						if( $value === false && isset($section['onoff_state']) && $section['onoff_state'] == 'on' ) {
							$value = 'on';
						}
						
					} else {
						$value = false;

						if( isset($section['onoff_state']) && $section['onoff_state'] == 'on' ) {
							$value = 'on';
						}

					}
					
					?>
					<?php if($key=="booking"): ?>
			<div class="notification notice margin-top-40 margin-bottom-20">
					
				<p><?php esc_html_e("By turning on switch on the right, you'll enable booking feature, it will add Booking widget on your listing. You'll see more configuration settings below.",'truelysell_core'); ?> </p>
			
			</div>
		<?php endif; ?>
					<label class="switch"><input class="switch_1" <?php checked($value,'on') ?> id="_<?php echo esc_attr($key).'_status'; ?>" name="_<?php echo esc_attr($key).'_status'; ?>" type="checkbox"><span class="slider round"></span></label>
				<?php endif; ?>	

		</div>
		
	 
		<?php if(isset($section['onoff']) && $section['onoff'] == true) : ?> 
		<div class="switcher-content">
		<?php endif; ?>								
		<?php foreach ( $section['fields'] as $key => $field ) :?>
			
			<?php if(isset($field['type']) && $field['type'] == "skipped" ) { continue; } 
			$field['submit_type'] = $listing_type;

			
			?>
			<?php 
				if( isset($field['render_row_col']) && !empty($field['render_row_col']) ) : 
					truelysell_core_render_column( $field['render_row_col'] , $field['name'] ); 
				else:
					truelysell_core_render_column( 12, $field['name'] ); 
				endif; 
			?>
			 
			<?php if(isset($field['type']) && $field['type'] != 'hidden') : ?>
				<label class="label-<?php echo esc_attr( $key ); ?>" for="<?php echo esc_attr( $key ); ?>">
					<?php echo stripslashes($field['label']) . apply_filters( 'submit_listing_form_required_label', isset($field['required']) ? '' : ' <small>' . esc_html__( '(optional)', 'workscout' ) . '</small>', $field ); ?>
					<?php if( isset($field['tooltip']) && !empty($field['tooltip']) ) { ?>
						<i class="tip" data-tip-content="<?php (esc_attr_e( stripslashes($field['tooltip']) )); ?>"></i>
					<?php } ?>
				</label>
			<?php 
				if($field['required'] == 1){ ?>
					<span class="text-danger">*</span>
				<?php }
			endif; 
			
				$template_loader = new Truelysell_Core_Template_Loader;
				$template_loader->set_template_data( array( 'key' => $key, 'field' => $field,	) )->get_template_part( 'form-fields/' . $field['type'] );
			?>
			 
		</div>
		
			
	 
	<?php endforeach; ?> 
		<?php if(isset($section['onoff']) && $section['onoff'] == true) : ?> 
		</div>
		<?php endif; ?>	
	</div> <!-- end section  -->	
	</div>

<?php endforeach; ?> 

	<div class="divider margin-top-40"></div>
	
		<input type="hidden" name="_listing_type" value="<?php  echo esc_attr($listing_type); ?>">
		<input type="hidden" name="truelysell_core_form" value="<?php echo $data->form; ?>" />
		<input type="hidden" name="listing_id" value="<?php echo esc_attr( $data->listing_id ); ?>" />
		<input type="hidden" name="step" value="<?php echo esc_attr( $data->step ); ?>" />
		
		
		<button type="submit" value="<?php echo esc_attr( $data->submit_button_text ); ?>" name="submit_listing"  class="button btn btn-primary submit-btn"><i class="fa fa-arrow-circle-right"></i> <?php echo esc_attr( $data->submit_button_text ); ?></button>

	
</form>
</div>