<?php
/* Get user info. */
global $wp_roles;
$current_user = wp_get_current_user();
$roles = $current_user->roles;
$role = array_shift( $roles ); 
$template_loader = new Truelysell_Core_Template_Loader; 

if ( isset($_GET['updated']) && $_GET['updated'] == 'true' ) : ?> 
	<div class="notification success closeable alert alert-success alert-dismissible mb-4"><?php esc_html_e('Your profile has been updated.', 'truelysell_core'); ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div> 
<?php endif; ?>
     

<?php if ( !is_user_logged_in() ) : ?>
    <div class="warning alert alert-dismissible  alert-danger  mb-4">
        <?php esc_html_e('You must be logged in to edit your profile.', 'truelysell_core'); ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div><!-- .warning -->
<?php else : ?>

<div class="row">

		<!-- Profile -->
		<div class="col-lg-12 col-md-12">
			<div class="dashboard-list-box margin-top-0">
				<h6 class="user-title"><?php esc_html_e('Profile Picture','truelysell_core') ?></h6>
 				<form enctype="multipart/form-data" method="post" id="edit_user" action="<?php the_permalink(); ?>">
				<div class="dashboard-list-box-static">
					<?php 
					$custom_avatar = $current_user->truelysell_core_avatar_id;
					$custom_avatar = wp_get_attachment_url($custom_avatar); 
					if(!empty($custom_avatar)) { ?>
					<div data-photo="<?php echo $custom_avatar; ?>" 
					     data-name="<?php esc_html_e('Your Avatar', 'truelysell_core'); ?>" 
						 data-size="<?php echo filesize( get_attached_file( $current_user->truelysell_core_avatar_id ) ); ?>" 
					     class="edit-profile-photo">
					<?php } else { ?>
					<div class="edit-profile-photo">
					<?php } ?>

						<div id="avatar-uploader" class="dropzone">
							<div class="dz-message" data-dz-message> <div class="img-upload"><i class="feather-upload-cloud me-1"></i> <?php esc_html_e('Upload', 'truelysell_core'); ?>
							
						</div> 
						<p>*image size should be at least 320px big,and less then 500kb. Allowed files .png and .jpg.</p>
						</div>
						</div>
						<input class="hidden d-none" name="truelysell_core_avatar_id" type="hidden" id="avatar-uploader-id" value="<?php echo $current_user->truelysell_core_avatar_id; ?>" />
					</div>
		
					<!-- Details -->
					<div class="my-profile">
					<h6 class="user-title"><?php echo esc_html('General Information', 'truelysell_core'); ?></h6>
					<div class="general-info ">
					 
 							<?php if(get_option('truelysell_profile_allow_role_change')): ?>
								<?php if(in_array($role, array('owner','guest'))): ?>
									<label for="role"><?php esc_html_e('Change your role', 'truelysell_core'); ?></label>
									<select name="role" id="role">
										<option <?php selected($role,'guest'); ?> value="guest"><?php esc_html_e('Guest','truelysell_core') ?></option>
										<option <?php selected($role,'owner'); ?> value="owner"><?php esc_html_e('Owner','truelysell_core') ?></option>
									</select>
								<?php endif; ?>
							<?php endif; ?>
							<div class="row">
								<div class="form-group col-xl-6">
									<label for="first-name" class="col-form-label"><?php esc_html_e('First Name', 'truelysell_core'); ?></label>
									<input class="text-input form-control" name="first-name" type="text" id="first-name" value="<?php  echo $current_user->user_firstname; ?>" />
								</div>
								<div class="form-group col-xl-6">
									<label for="last-name" class="col-form-label"><?php esc_html_e('Last Name', 'truelysell_core'); ?></label>
			               			 <input class="text-input form-control" name="last-name" type="text" id="last-name" value="<?php echo $current_user->user_lastname; ?>" />
								</div>
								<div class="form-group col-xl-12">
									<?php  if ( isset($_GET['user_err_pass']) && !empty($_GET['user_err_pass'])  ) : ?> 
									<div class="notification error closeable margin-top-35"><p>
										<?php
										switch ($_GET['user_err_pass']) {
											case 'error_1':
												echo esc_html_e('The Email you entered is not valid or empty. Please try again.','truelysell_core');
												break;
											case 'error_2':
												echo esc_html_e('This email is already used by another user, please try a different one.','truelysell_core');
												break;					 	
											
											
											default:
												# code...
												break;
										}  ?>
											
										</p><a class="close" href="#"></a>
									</div> 
									<?php endif; ?>
									<label for="email" class="col-form-label"><?php esc_html_e('E-mail', 'truelysell_core'); ?></label>
									<input class="text-input form-control" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
								</div>
								<div class="form-group col-xl-12">
									<label for="description" class="col-form-label"><?php esc_html_e('About me', 'truelysell_core'); ?></label>
									<?php 
									$user_desc = get_the_author_meta( 'description' , $current_user->ID);
									$user_desc_stripped = strip_tags($user_desc, '<p>'); //replace <p> and <a> with whatever tags you want to keep after the strip
									?>
									<textarea name="description" id="description" class="form-control" cols="57" rows="4"><?php echo $user_desc_stripped; ?></textarea>
								</div>
								<div class="row">
								<?php 
							 switch ($role) {
				              	case 'owner':
				              		$fields = Truelysell_Core_Meta_Boxes::meta_boxes_user_owner();
				              		break;
				              	case 'guest':
				              		$fields = Truelysell_Core_Meta_Boxes::meta_boxes_user_guest();
				              		break;              	
				              	default:
				              		$fields = Truelysell_Core_Meta_Boxes::meta_boxes_user_owner();
				              	break;
				              }
				        
			              	
							foreach ( $fields as $key => $field ) : 
							
								$field['value'] = $current_user->$key;
								?>
								
								<?php 
								if( $field['type'] == 'header') { ?>
									</div>
								</div>
								<h4 class="gray submit-section-header"><?php echo $field['label']; ?></h4>
								<div class="dashboard-list-box-static">
									<div class="my-profile">
									
								<?php } else { ?>
									<div class="form-group col-xl-12">
									<?php 

									 if($field['type'] != 'hidden'): ?>
									 
										<label class="col-form-label label-<?php echo esc_attr( $key ); ?>" for="<?php echo esc_attr( $key ); ?>">
											<?php echo $field['label'];?>
											<?php if( isset($field['tooltip']) && !empty($field['tooltip']) ) { ?>
												<i class="tip" data-tip-content="<?php esc_attr_e( $field['tooltip'] ); ?>"></i>
											<?php } ?>
										</label>
										<?php endif; ?>
										
										<?php
											$template_loader = new Truelysell_Core_Template_Loader;

											// fix the name/id mistmatch
											if(isset($field['id'])){
												$field['name'] = $field['id'];
						 					}

						 					if($field['type']=='select_multiple') {
						 					
						 						$field['type'] = 'select';
						 						$field['multi'] = 'on';
						 					}	

						 					if($field['type']=='multicheck_split') {
						 					
						 						$field['type'] = 'checkboxes';
						 					}
                                            $template_loader->set_template_data( array( 'key' => $key, 'field' => $field, ) )->get_template_part( 'form-fields/' . $field['type'] ); ?>
									</div>
											<?php 
									 } 
							endforeach; ?>

							
								</div>
							</div>
							<input type="hidden" name="my-account-submission" value="1" />
							<button type="submit" form="edit_user" value="<?php esc_html_e( 'Submit', 'truelysell_core' ); ?>" class="button btn btn-primary"><?php esc_html_e('Save Changes', 'truelysell_core'); ?></button>
						<?php endif; ?>
					

					
					</div>
					</div>
						
					</div>
				</div>
			</div>
		

		</form>
		<!-- Change Password -->
			<div class="col-lg-12 col-md-12">
				<div class="dashboard-list-box change_password_admin mt-5">
				<div class="general-info">
 					<h6 class="user-title"><?php echo esc_html('Change Password', 'truelysell_core'); ?></h6>
					<div class="dashboard-list-box-static">

						<!-- Change Password -->
						<div class="my-profile">
							<div class="row">
								<div class="col-md-12">
									<div class="notification notice margin-top-0 margin-bottom-0">
										<p><?php esc_html_e('Your password should be at least 12 random characters long to be safe','truelysell_core') ?></p>
									</div>
								</div>
							</div>
							<?php if ( isset($_GET['updated_pass']) && $_GET['updated_pass'] == 'true' ) : ?> 
								<div class="notification success closeable margin-bottom-35"><p><?php esc_html_e('Your password has been updated.', 'truelysell_core'); ?></p><a class="close" href="#"></a></div> 
							<?php endif; ?>

							<?php  if ( isset($_GET['err_pass']) && !empty($_GET['err_pass'])  ) : ?> 
							<div class="notification error closeable margin-bottom-35"><p>
								<?php
								switch ($_GET['err_pass']) {
								 	case 'error_1':
								 		echo esc_html_e('Your current password does not match. Please retry.','truelysell_core');
								 		break;
								 	case 'error_2':
								 		echo esc_html_e('The passwords do not match. Please retry..','truelysell_core');
								 		break;					 	
								 	case 'error_3':
								 		echo esc_html_e('A bit short as a password, don\'t you think?','truelysell_core');
								 		break;					 	
								 	case 'error_4':
								 		echo esc_html_e('Password may not contain the character "\\" (backslash).','truelysell_core');
								 		break;
								 	case 'error_5':
								 		echo esc_html_e('An error occurred while updating your profile. Please retry.','truelysell_core');
								 		break;	
								 	case 'error_6':
								 		echo esc_html_e('Please fill all password fields correctly.','truelysell_core');
								 		break;
								 	
								 	default:
								 		# code...
								 		break;
								 }  ?>
									
								</p><a class="close" href="#"></a>
							</div> 
							<?php endif; ?>
							<form name="resetpasswordform" action="" method="post">
							
							
 
								<div class="row">
									<div class="form-group col-xl-12">
										<label class="col-form-label"><?php esc_html_e('Current Password','truelysell_core'); ?></label>
										<input class="form-control" type="password" name="current_pass"  >
									</div>
									<div class="form-group col-xl-6">
										<label class="col-form-label" for="pass1"><?php esc_html_e('New Password','truelysell_core'); ?></label>
										<input class="form-control" name="pass1" type="password"  >
									</div>
									<div class="form-group col-xl-6">
										<label class="col-form-label" for="pass2"><?php esc_html_e('Confirm New Password','truelysell_core'); ?></label>
										<input class="form-control" name="pass2" type="password"  >
									</div>
									
								</div>
								<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary button" value="<?php esc_html_e('Save Changes','truelysell_core'); ?>" />
								
									<input type="hidden" name="truelysell_core-password-change" value="1" />
								

						

							

								
							</form>

						</div>
						
					</div>
				</div>
				</div>
			</div>
			<?php if ( class_exists( 'plugin_delete_me' ) ) : ?>
				<div class="col-lg-6 col-md-12 delete-account-section margin-top-40">
					<div class="dashboard-list-box margin-top-0">
						<h4 class="gray"><?php esc_html_e('Delete Your Account','truelysell_core') ?></h4>
						<div class="dashboard-list-box-static">
							<?php echo do_shortcode( '[plugin_delete_me /]' ); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

		</div>

		