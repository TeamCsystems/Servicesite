<?php $ids = '';
//user_1 ten co wysyla
//user_2 ten co dostaje
if(isset($data)) :
	$ids = (isset($data->ids)) ? $data->ids : '' ;
endif;
if( isset( $_GET["action"]) && $_GET["action"] == 'view' )  {

	$messages = new Truelysell_Core_Messages();

	//check if user can

	$conversation_id = $_GET["conv_id"]; 
	
	$current_user_id = get_current_user_id();
	
	// get this conversation data
	$this_conv = $messages->get_conversation($conversation_id);	
	if(!$this_conv) { ?>
		<h4><?php esc_html_e('This message does not exists.','truelysell_core'); ?></h4>
		<?php return;
	}
	if($current_user_id == (int)$this_conv[0]->user_1 || $current_user_id == (int)$this_conv[0]->user_2 ) :

		// mark this message as read
		$messages->mark_as_read($conversation_id);	
		
		// set who is adversary on that converstation
		$adversary = ($this_conv[0]->user_1 == $current_user_id) ? $this_conv[0]->user_2 : $this_conv[0]->user_1 ;
		$recipient = get_userdata( $adversary ); 
		if(!$recipient){
			$name = esc_html__('User has been removed','truelysell_core');
		} else {
			if(empty($recipient->first_name) && empty($recipient->last_name)) {
			$name_selected = $recipient->user_nicename;
		} else {
			$name_selected = $recipient->first_name .' '.$recipient->last_name;
		} 
		
		}
		
		$referral = $messages->get_conversation_referral($this_conv[0]->referral);
		
		?>


<div class="row justify-content-center">
					<div class="col-lg-12">
					
						<div class="row chat-window">
						
							<!-- Chat User List -->

							<div class="col-lg-5 col-xl-5 chat-cont-left">
								<div class="card mb-sm-3 mb-md-0 contacts_card flex-fill">
								<div class="chat-header">
    								<div>
    									<h6><?php echo esc_html('Chats','truelysell_core') ?></h6>
										<p class="mb-0 users_left"><?php echo esc_html_e('Users','truelysell_core') ?></p>
     								</div>
    								 
    							</div>

								<?php if($ids) { ?>
									<div class="card-body contacts_body chat-users-list chat-scroll">
									<ul>
									<?php 

							foreach ($ids as $key => $conversation) {
									
									$message_url = add_query_arg( array( 'action' => 'view',  'conv_id' => $conversation->id ), get_permalink( truelysell_fl_framework_getoptions('messages_page' )) );
		
									$last_msg = $messages->get_last_message($conversation->id);
									$conversation_data = $messages->get_conversation($conversation->id);	

									$if_read = $messages->check_if_read($conversation_data);

									$_conv_list_adversary = ($conversation_data[0]->user_1 == $current_user_id) ? $conversation_data[0]->user_2 : $conversation_data[0]->user_1 ;	
									$user_data = get_userdata( $_conv_list_adversary );

									$referral = $messages->get_conversation_referral($conversation->referral);
								?>

								
								
										<li><a href="<?php echo esc_url($message_url) ?>" class="mediames d-flex  <?php if($conversation_id==$conversation->id): echo esc_attr('active'); endif; ?> <?php if(!$if_read) : echo esc_attr('sent');  endif; ?>">
											<div class="media-img-wrap flex-shrink-0 me-3">
												<div class="avatar">
												<?php $argss = array('class' => 'rounded-circle user_img');
echo get_avatar($_conv_list_adversary, 70, null, null, $argss ); ?>
												</div>
											</div>

											<div class="media-body d-flex justify-content-between flex-grow-1">
												<div>
												
												<?php
												if(!$user_data){
													$name = esc_html__('User has been removed','truelysell_core');
												} else {
												if(empty($user_data->first_name) && empty($user_data->last_name)) {
													$name = $user_data->user_nicename;
												} else {
													$name = $user_data->first_name .' '.$user_data->last_name;
												} } ?>
												
													<div class="user-name"><b><?php echo esc_html($name); ?></b>	
												<?php if(!$if_read) : ?><i class="badge badge-success"><?php esc_html_e('Unread','truelysell_core') ?></i><?php endif; ?></div>
													<div class="user-last-chat">
														
													<p><?php  
													echo wp_trim_words( esc_html($last_msg[0]->message), 3, '...' );
													?></p>
													</div>
												</div>
												<div>
													<div class="last-chat-time">
													<span><?php echo human_time_diff( $last_msg[0]->created_at, current_time('timestamp')  );  ?></span>
													</div>
												</div>
											</div>
										</a>
										</li>

										<?php } ?>

										 
									</div>
								</div>
							</div>
							<?php } ?>
							 
							<!-- Chat User List -->
							
							<!-- Chat Content -->
							<div class="col-lg-7 col-xl-7 chat-cont-right">
							
								<!-- Chat History -->
								<div class="card mb-0">

									<div class="card-header msg_head">
										<div class="d-flex bd-highlight">
											<a id="back_user_list" href="javascript:void(0)" class="back-user-list">
												<i class="fas fa-chevron-left"></i>
											</a>
											<div class="img_cont">

 <?php $argss = array('class' => 'rounded-circle user_img');
echo get_avatar($adversary, 70, null, null, $argss ); ?>

											</div>
											<div class="user_info">
												<span><strong id="receiver_name"><?php echo esc_html($name_selected); ?> </strong></span>
												<p class="mb-0">Messages</p>
											</div>
										</div>
									</div>

									<div class="card-body msg_card_body chat-scroll">

									<ul class="list-unstyled message-bubbles">
								        	<?php
							                $conversation = $messages->get_single_conversation($current_user_id,$conversation_id);
											
							                 foreach ($conversation as $key => $message) { 
 												$last_msgn =$message->created_at;
							             	?>
											<li class="media message-bubble <?php if($current_user_id == (int) $message->sender_id ) echo esc_attr('sent'); else  echo esc_attr('received'); ?>  d-flex">
												<div class="avatar flex-shrink-0">

												<?php $argss = array('class' => 'rounded-circle user_img'); 
												echo get_avatar($message->sender_id, 70, null, null, $argss ); ?>

												</div>
												<div class="media-body flex-grow-1">
													<div class="msg-box">
														<div>
														<?php echo wpautop(esc_html($message->message)); ?>
															<ul class="chat-msg-info">
																<li>
																	<div class="chat-time">
																		<span><?php echo human_time_diff( $last_msgn, current_time('timestamp')  ); ?>
 																	</span>
																	</div>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</li>
											<?php } ?>
											<img style="display: none; " src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/loader.gif" class="loading">
					<!-- Reply Area -->
					<div class="clearfix"></div>

									</ul>

									 
					
										 
									
									</div>
									
									<div class="card-footer">
										
 
											<form action="" id="send-message-from-chat">
											<div class="input-group">
							<textarea id="contact-message" class="form-control type_msg mh-auto empty_check" name="message" required   placeholder="<?php esc_html_e('Type your message...', 'truelysell_core'); ?>"></textarea>
							<input type="hidden" id="conversation_id" name="conversation_id" value="<?php echo esc_attr($_GET["conv_id"]) ?>"><div class="send-action">
							<input type="hidden" id="recipient" name="recipient" value="<?php echo esc_attr($adversary) ?>">
							<button class="btn btn-primary btn_send"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
							</div>
							</div>
						</form>
						
										
									</div>
									
								</div>

							</div>
							<!-- Chat Content -->
							
						</div>

					</div>
				</div>

		 
	<?php else: ?>
		<?php esc_html_e("It's not your converstation!",'truelysell_core'); ?>
	<?php endif; ?>
<?php } else {
	die();
} ?>