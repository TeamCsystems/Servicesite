<?php 
$ids = '';
if(isset($data)) :
	$ids	 	= (isset($data->ids)) ? $data->ids : '' ;
endif;
$messages = new Truelysell_Core_Messages();

?>

<div class="messages-container row chat-window">
    					<!-- Chat User List -->
    					<div class="col-lg-12 messages-inbox col-xl-12 chat-cont-left">
    						<div class="card mb-sm-3 mb-md-0 contacts_card flex-fill">
    							<div class="chat-header">
    								<div>
    									<h6><?php echo esc_html_e('Inbox','truelysell_core') ?></h6>
     								</div>
    								 
    							</div>
    							 
    							 
    							<div class="card-body contacts_body chat-users-list chat-scroll">
    	  <ul>
			<?php 
			if($ids) { 
			foreach ($ids as $key => $conversation) {
				$message_url = add_query_arg( array( 'action' => 'view',  'conv_id' => $conversation->id ), get_permalink( truelysell_fl_framework_getoptions('messages_page' )) );

				$last_msg = $messages->get_last_message($conversation->id);
				$conversation_data = $messages->get_conversation($conversation->id);
				$referral = $messages->get_conversation_referral($conversation->referral);
				$if_read  = $messages->check_if_read($conversation_data);	
				?>

                      <li ><a href="<?php echo esc_url($message_url); ?>" class="d-flex justify-content-between" >
						<?php
					
						if($last_msg) {
							//set adversary
							$adversary = ($conversation_data[0]->user_1 == get_current_user_id()) ? $conversation_data[0]->user_2 : $conversation_data[0]->user_1 ;
							
							$user_data = get_userdata( $adversary ); ?>
 		
							<div class="media-img-wrap flex-shrink-0 me-3">
    										<div class="avatar">
 												<?php echo get_avatar($adversary, '70', null, null, array( 'class' => array( 'avatar-img', ' rounded-circle' ) )) ?>
    										</div>
    									</div>

							<div class="media-body justify-content-between d-flex flex-fill">

							<?php
								 
									if(empty($user_data->first_name) && empty($user_data->last_name)) {
											 
									} else {
										$name = $user_data->first_name .' '.$user_data->last_name;
									} ?>

						                 	<div>
    											<div class="user-name">
													<p class="mb-1"><b><?php echo esc_html($name); ?></b> <?php if(!$if_read) : ?>
											<span class="badge badge-success"><i><?php esc_html_e('Unread','truelysell_core') ?></i></span>
											<?php endif; ?></p>
												<?php if($referral) : ?> 
													<p class="mb-1"><span class="mes_referral" style="float:none;"> <?php echo esc_html($referral);  ?></span></p>
													<?php endif; ?>
										</div>
											
											<div>
												<span class="msg_text">
											<?php 
										echo ( $last_msg[0]->sender_id == get_current_user_id() ) ? '<i class="fa fa-mail-forward" ></i>' : '<i class="fa fa-mail-reply"></i>';
										?> <?php echo wp_trim_words( esc_html($last_msg[0]->message), 10, '...' ); ?>
												</span>
											</div>

											</div>
							 
							</div>

							<div>

											<div class="user-last-chat"><?php if(isset($last_msg[0]->created_at) && !empty($last_msg[0]->created_at)) : ?><span class="mes_time"><?php echo human_time_diff( $last_msg[0]->created_at, current_time('timestamp')  );  ?></span><?php endif; ?> </div>
    										</div>

						<?php } ?>
					</a> </li>
			<?php }
			} else { ?>
				<li><p><?php esc_html_e("You don't have any messages yet",'truelysell_core'); ?></p></li>
			<?php } ?>

    							</div>
    						</div>
    					</div>
    					<!-- Chat User List -->
    					
    					<!-- Chat Content -->
    					
    					<!-- /Chat Content -->
    					
    					<!-- Chat Profile -->
    					
    					<!-- /Chat Content -->
    					
    				</div>


 
 

<?php
$current_page = (isset($_GET['messages-page'])) ? $_GET['messages-page'] : 1;

if($data->total_pages > 1) { ?>
	<div class="clearfix"></div>
	<div class="pagination-container margin-top-30 margin-bottom-0">
		<nav class="pagination">
			<?php 
				echo paginate_links( array(
					'base'         	=> @add_query_arg('messages-page','%#%'),
					'format'       	=> '?messages-page=%#%',
					'current' 		=> $current_page,
					'limit' => "300",
					'total' 		=> $data->total_pages,
					'type' 			=> 'list',
					'prev_next'    	=> true,
			        'prev_text'    	=> '<i class="fas fa-angle-left"></i>',
			        'next_text'    	=> '<i class="fas fa-angle-right"></i>',
			         'add_args'     => false,
   					 'add_fragment' => ''
				    
				) );?>
		</nav>
	</div>
	<?php } ?>