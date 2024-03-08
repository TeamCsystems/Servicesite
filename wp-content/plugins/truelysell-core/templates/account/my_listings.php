<?php 
$ids = '';
if(isset($data)) :
	$ids	 	= (isset($data->ids->posts)) ? $data->ids->posts : '' ;
	$status	 	= (isset($data->status)) ? $data->status : '' ;
endif;
$message = $data->message;
$current_user = wp_get_current_user();
$roles = $current_user->roles;
$role = array_shift( $roles ); 
if(!in_array($role,array('administrator','admin','owner','seller'))) :
	$template_loader = new Truelysell_Core_Template_Loader; 
	$template_loader->get_template_part( 'account/owner_only'); 
	return;
endif; 

$max_num_pages = $data->ids->max_num_pages;
?> 
<div class="row">
<div class="col-md-12">
	<?php if( empty($ids) ) : ?>
		<?php if( $status == 'active') : ?>
		<div class="alert alert-info">
			 <?php printf( _e( 'You haven\'t submitted any services yet, you can add your first one <a href="%s">below</a>', 'truelysell_core' ), get_permalink( truelysell_fl_framework_getoptions('submit_page' ) ) ); ?> 
		</div>
	<div class=""><a href="<?php echo get_permalink( truelysell_fl_framework_getoptions('submit_page' ) ); ?>" class="btn btn-primary"><?php esc_html_e('Submit New Listing','truelysell_core'); ?></a></div>
		<?php else : ?>
			<div class="alert alert-info">
				 <?php esc_html_e( 'You don\'t have any services here', 'truelysell_core' ); ?> 
			</div>
		<?php endif; ?>
	<?php else: ?>
		
	<?php if(!empty($message )) { echo $message; } ?>

	<?php $search_value = isset($_GET['search']) ? $_GET['search'] : '';?>
	<div class="dashboard-list-box">
		 
		<div class="row">
		<?php 	
			foreach ($ids as $listing_id) {
			$listing = get_post($listing_id); 
			$listing_data = get_post_meta($listing_id);
			?> 
			<div class="col-xl-4 col-md-6">
				<div class="service-widget provider_services fav-itemtwoside">
					<div class="service-img">
						<a href="<?php echo get_permalink( $listing ); ?>"><?php 
						
						if(has_post_thumbnail($listing_id)){ 
							echo get_the_post_thumbnail($listing_id, 'truelysell-listing-grid-small', array('class' => 'serv-img img-fluid'));
 						} else {
							$gallery = (array) get_post_meta( $listing_id, '_gallery', true );

							$ids = array_keys($gallery);
							if(!empty($ids[0]) && $ids[0] !== 0){ 
								$image_url = wp_get_attachment_image_url($ids[0],'truelysell-listing-grid-small'); 
							} else {
								$image_url = get_truelysell_core_placeholder_image();
							}
							?>
							<img src="<?php echo esc_attr($image_url); ?>" class="img-fluid serv-img">
						<?php } ?></a>
						<div class="item-info">
						<div class="cate-list">

<?php 
$terms = get_the_terms($listing_id, 'listing_category' ); 
if ( $terms && ! is_wp_error( $terms ) ) :  
$main_term = array_pop($terms); ?>
<span class="cat_name bg-yellow"><?php echo $main_term->name; ?></span>
<?php endif; ?>
</div>
<?php  $rating_value = get_post_meta($listing_id, 'truelysell-avg-rating', true); ?>
<div class="fav-item">
<span class="serv-rating"><i class="fa-solid fa-star"></i><?php echo esc_attr(round($rating_value, 1));	?></span>
</div>
										</div>
						</div>
  					
					<div class="service-content">
						<h3 class="title">
                            <a href="<?php echo get_permalink( $listing ); ?>"><?php echo get_the_title( $listing );?></a>
                        </h3>
						<?php $address = get_post_meta( $listing_id, '_friendly_address', true );  ?>
  						<p><i class="feather-map-pin me-2"></i><?php echo esc_html($address); ?> </p>
 						<div class="serv-info justify-content-between align-items-center">
						 
					 
					<?php
						$actions = array();

						switch ( $listing->post_status ) {
							case 'publish' :
									$actions['edit'] = array( 'label' => __( 'Edit', 'truelysell_core' ), 'icon' => 'feather-edit','class' => 'serv-edit', 'status' => $listing->post_status, 'nonce' => false,'nolink' => 'link' );
									//if(truelysell_fl_framework_getoptions('new_listing_requires_purchase')){
									//	$actions['renew'] = array( 
										//	'label' => __( 'Change Package', 'truelysell_core' ), 
										//	'icon' => 'feather-settings', 
										//	'class' => 'serv-changepackage',
										//	'nolink' => 'link',
										//	'nonce' => false
											// );	
									//}
 									
								break;
							
							case 'pending_payment' :
							case 'pending' :
								
								$actions['edit'] = array( 'label' => __( 'Edit', 'truelysell_core' ), 'icon' => 'feather-edit','class' => 'serv-edit', 'nonce' => false,'nolink' => 'link' );
								
							break;

							case 'expired' :
								
								$actions['renew'] = array( 'label' => __( 'Renew', 'truelysell_core' ), 'icon' => 'feather-refresh-ccw', 'class' => 'serv-edit','nonce' => true,'nolink' => 'link' );
								
							break;
						}

						$actions['statusnew'] = array( 'label' => $listing->post_status, 'icon' => 'feather-alert-circle', 'class' => 'serv-edit text-capitalize','nonce' => false,'nolink' => 'nolink'  );

						$actions['delete'] = array( 'label' => __( 'Delete', 'truelysell_core' ), 'icon' => 'feather-trash-2', 'class' => 'serv-edit','nonce' => true,'nolink' => 'link'  );

						$actions = apply_filters( 'truelysell_core_my_listings_actions', $actions, $listing );

						foreach ( $actions as $action => $value ) {
							 
 							
							if($action == 'edit' || $action == 'renew'){
								$action_url = add_query_arg( array( 'action' => $action,  'listing_id' => $listing->ID ), get_permalink( truelysell_fl_framework_getoptions('submit_page' )) );
							} else {
								$action_url = add_query_arg( array( 'action' => $action,  'listing_id' => $listing->ID ) );
							}
							if(!truelysell_fl_framework_getoptions('new_listing_requires_purchase') && $action == 'renew'){
								$action_url = add_query_arg( array( 'action' => $action,  'listing_id' => $listing->ID ) );
							}
							if ( $value['nonce'] ) {
								$action_url = wp_nonce_url( $action_url, 'truelysell_core_my_listings_actions' );
							} ?>

							<div class="col_space">
							<?php 
 							if($value['nolink']=='link') {
							echo '<a  href="' . esc_url( $action_url ) . '" class="button gray ' . esc_attr( $value["class"]) .  ' ' . esc_attr( $action ) .  '  truelysell_core-dashboard-action-' . esc_attr( $action ) . '">';
							
							if(isset($value['icon']) && !empty($value['icon'])) {
								echo '<i class="'.$value['icon'].'"></i> ';
							} ?>

							 <?php echo esc_html( $value['label'] ) . '</a>'; ?>
							 <?php  } else { 
								 
								echo '<span class="button gray ' . esc_attr( $value["class"]) .  ' ' . esc_attr( $action ) .  '  truelysell_core-dashboard-action-' . esc_attr( $action ) . '">';
							
							if(isset($value['icon']) && !empty($value['icon'])) {
								echo '<i class="'.$value['icon'].'"></i> ';
							} ?>
							
							 
							 <?php echo esc_html( $value['label'] ) . '</span>'; ?>

							<?php  }
							  ?>
							 

							 </div>
						<?php }
					?>
				
					 
					 
						</div>
			</div>
			</div>
			</div>

		<?php } ?>

	</div>
	</div>
	 <?php if ( $paged!='') {?>
	<?php
		
		$paged = (isset($_GET['listings_paged'])) ? $_GET['listings_paged'] : 1;
		
				  ?>
		<div class="clearfix"></div>
			<div class="pagination-container margin-top-30 margin-bottom-0">
				<nav class="pagination">
				<?php 
				$big = 999999999; 
				echo paginate_links( array(
					'base'      => add_query_arg('listings_paged','%#%'),
					'format' 	=> '?listings_paged=%#%',
					'current' 	=> max( 1, $paged ),
					'total' 	=> $max_num_pages,
					'type' 		=> 'list',
					'prev_next'    => true,
			        'prev_text'    => '<i class="fas fa-angle-left"></i>',
			        'next_text'    => '<i class="fas fa-angle-right"></i>',
			         'add_args'        => false,
   					 'add_fragment'    => ''
				    
				) );?>
				</nav>
		</div>
	 
	<?php } endif; ?>

</div>
</div>