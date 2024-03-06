<!-- Content -->
<?php if(isset($data)) : 
	$commissions = $data->commissions; 
	$payouts = $data->payouts; 
	?>
<?php endif; 
$current_user = wp_get_current_user(); ?>
<div class="row" id="waller-row" data-numberFormat= <?php if(wc_get_price_decimal_separator() == ',') { echo 'euro'; } ?>>
	<?php 
	$balance = 0;

	foreach ($commissions as $commission) { 
		if($commission['status'] == "unpaid") :
			
			$order = wc_get_order( $commission['order_id'] );
			if($order){
				$total = $order->get_total();
				$earning = (float) $total - $commission['amount'];
				$balance = $balance + $earning;	
			}
			
		endif;
	}
	$currency_abbr = truelysell_fl_framework_getoptions('currency' );
	
	$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);


	 ?>
	<!-- Item -->

	<div class="col-md-4 d-flex">
						<div class="card flex-fill">
							<div class="card-body">
								<div class="balance-crad">
									<div class="balance-head">
										<h6><?php esc_html_e('Total Orders','truelysell_core'); ?></h6>
 									</div>
									<div class="balance-amt">
										<h3><span class="counters" data-count="<?php echo $data->total_orders; ?>"><?php echo $data->total_orders; ?> </span></h3>
									</div>
 								</div>
							</div>
						</div>
					</div>

					<div class="col-md-4 d-flex">
						<div class="card flex-fill">
							<div class="card-body">
								<div class="balance-crad">
									<div class="balance-head">
										<h6><?php esc_html_e('Withdraw Balance','truelysell_core') ?></h6>
 									</div>
									<div class="balance-amt">
										<h3><?php echo $currency_symbol; ?><span class="counters" ><?php echo wc_price($balance,array('currency'=>' ','decimal_separator' => '.' )); ?> </span></h3>
									</div>
 								</div>
							</div>
						</div>
					</div>


					<div class="col-md-4 d-flex">
						<div class="card flex-fill">
							<div class="card-body">
								<div class="balance-crad">
									<div class="balance-head">
										<h6><?php esc_html_e('Total Earnings','truelysell_core'); ?></h6>
 									</div>
									<div class="balance-amt">
										<h3><?php echo $currency_symbol; ?><span class="counters" ><?php echo wc_price($data->earnings_total,array('currency'=>' ','decimal_separator' => '.' )); ?> </span></h3>
									</div>
 								</div>
							</div>
						</div>
					</div>
  
 

	<!-- Item -->
	 

</div>
<!-- Invoices -->
<div class="row">						
			<!-- Invoices -->
	<div class="col-lg-12 col-md-12">
		<?php $payment_type =  (isset($current_user->truelysell_core_payment_type)) ? $current_user->truelysell_core_payment_type : '' ; ?>
		 
		<div class="modal fade custom-modal " id="payout_method_popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Payout Method','truelysell_core'); ?></h5>
       
		<button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="feather-x"></i></button>
      </div>
      <div class="modal-body">
	  <div class="message-reply margin-top-0">
				<form method="post" id="edit_user" action="<?php the_permalink(); ?>">
				<!-- Payment Methods Accordion -->
				<div class="payment payout-method-tabs">
					<?php if(!truelysell_is_payout_active()){  ?>
					<div class="payment-tab <?php if(empty($payment_type) || $payment_type == 'paypal') { ?>payment-tab-active <?php } ?>">
						<div class="payment-tab-trigger">
							<input <?php checked($payment_type,'paypal') ?> id="paypal" name="payment_type" type="radio" value="paypal">
							<label for="paypal"><?php esc_html_e('PayPal','truelysell_core'); ?></label>
						</div>

						<div class="payment-tab-content">
							 
								 
									<div class="form-group">
										<label for="ppemail"><?php esc_html_e('PayPal Email','truelysell_core'); ?></label>
										<input id="ppemail" class="form-control" name="ppemail" value="<?php if(isset($current_user->truelysell_core_ppemail)) { echo $current_user->truelysell_core_ppemail; } ?>"  type="email">
									</div>
							 
							 
						</div>
					</div>
					<?php } ?>
					<?php if(truelysell_is_payout_active()){  ?>
                    <div class="payment-tab <?php if( $payment_type == 'paypal_payout') { ?>payment-tab-active <?php } ?> ">
                        <div class="payment-tab-trigger">
                            <input <?php checked($payment_type,'paypal_payout') ?> type="radio" name="payment_type" id="paypal_payout" value="paypal_payout">
                            <label for="paypal_payout"><?php esc_html_e('PayPal Payout','truelysell_core'); ?></label>
                        </div>

                        <div class="payment-tab-content">
                             
                                 
                                    <div class="form-group">
                                        <label for="paypal_payout_email"><?php esc_html_e('PayPal Payout Email','truelysell_core'); ?></label>
                                        <input id="truelysell_paypal_payout_email" class="form-control" name="truelysell_paypal_payout_email" value="<?php if(isset($current_user->truelysell_paypal_payout_email)) { echo $current_user->truelysell_paypal_payout_email; } ?>"  type="email">
                                    </div>
                              
                            
                        </div>
                    </div>
                	<?php } ?>


				</div>
				<!-- Payment Methods Accordion / End -->

				<button class="btn btn-primary btn-block btn-md margin-top-15"><?php esc_html_e('Save','truelysell_core') ?></button>
				<input type="hidden" name="my-account-submission" value="1" />
				</form>

			</div>
      </div>
    
    </div>
  </div>
</div>
</div>
</div>
		<!-- PAYOUT METHOD POPUP -->
		 
		
	 
 


		<!-- Payout History -->
		<div class="row">
					<div class="col-md-12">
						<div class="row mb-4 mb-3 mt-3"> 
							<div class="col-md-6">
						<div class="provider-subtitle">
							<h6><?php esc_html_e('Payout History','truelysell_core') ?></h6>
						</div>
						</div>
						<div class="col-md-6 d-flex align-items-start justify-content-md-end flex-wrap">
						<a data-bs-toggle="modal" data-bs-target="#payout_method_popup" class="button mb-0 payout-method btn btn-primary popup-with-zoom-anim"><?php esc_html_e('Set Payout','truelysell_core') ?></a>
						</div>

						

						
					</div>
						<?php if($payouts) { ?>
						<div class="provide-table">
							<div class="table-responsive">
								<table class="table custom-table datatable" id="data-table">
									<thead class="thead-light">
										<tr>
											<th><?php esc_html_e('Payout Date','truelysell_core'); ?></th>
											<th><?php esc_html_e('Amount','truelysell_core'); ?></th>
 											<th><?php esc_html_e('Mode','truelysell_core'); ?></th>
											<th><?php esc_html_e('Total','truelysell_core'); ?></th>
											<th><?php esc_html_e('Status','truelysell_core'); ?></th>
 										</tr>
									</thead>
									<tbody>
									<?php 
				foreach ($payouts as $payout) {

                    if ($payout['payment_method'] === 'paypal'){
                        $payment_method = 'PayPal';
                    }else if ($payout['payment_method'] === 'paypal_payout'){
                        $payment_method = 'PayPal Payout';
                    }else {
                        $payment_method = 'Bank Transfer';
                    }


                    ?>
										<tr>
											<td><?php echo date_i18n(get_option( 'date_format' ), strtotime($payout['date']));  ?></td>
											<td><?php echo wc_price($payout['amount']); ?></td>
											<td> <?php echo esc_html($payment_method,'truelysell_core'); ?></td>
											<td><?php echo wc_price($payout['amount']); ?></td>
 											<td><span class="badge text-capitalize <?php if($payout['status']=='paid') { ?>badge-success <?php } else { ?> badge-danger <?php } ?>"><?php echo $payout['status']; ?></span></td>
 										</tr>

										<?php } ?>
										 
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div id="tablelength"></div>
							</div>
							<div class="col-md-9">
								<div class="table-ingopage">
									<div id="tableinfo"></div>
									<div id="tablepagination"></div>
								</div>
							</div>
						</div>
						<?php } else { ?>
							<div class="alert alert-info"><?php esc_html_e('You don\'t have any payouts yet.','truelysell_core') ?></div>
			<?php } ?>

					</div>
				</div>
				<!-- /Payout History -->

		
		 