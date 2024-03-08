<!-- Content -->
<?php if(isset($data)) : 
	$commissions = $data->commissions; 
	$payouts = $data->payouts; 
	?>
<?php endif; 
$current_user = wp_get_current_user(); ?>
 
<!-- Invoices -->
<div class="row">
	<div class="col-xl-12 col-lg-6 col-md-12">


	<!-- Coupouns -->
	<div class="row">
	

					<div class="col-md-12">
					<?php if($commissions) {?>
						<div class="provide-table">
							<div class="table-responsive">
								<table class="table custom-table datatable mb-0" id="data-table">
									<thead class="thead-light">
										<tr>
 											<th><?php esc_html_e('#','truelysell_core');?> </th>
 											<th><?php esc_html_e('Price','truelysell_core');?></th>
											<th><?php esc_html_e('Fee','truelysell_core');?></th>
											<th><?php esc_html_e('Your Earning','truelysell_core');?></th>
											<th><?php esc_html_e('Order','truelysell_core');?></th>
											<th><?php esc_html_e('Date','truelysell_core');?></th>
											<th><?php esc_html_e('Status','truelysell_core');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 
							$count=1;		
				foreach ($commissions as $commission) { 
					
					$order = wc_get_order( $commission['order_id'] );
					if($order):
					$total = $order->get_total();
					$earning = $total - $commission['amount'];
					?>

										<tr>
											<td><?php echo esc_html($count,'truelysell_core'); ?></td>
											<td><?php echo wc_price($total); ?></td>
											<td><?php echo wc_price($commission['amount']); ?></td>
											<td><?php echo wc_price($earning); ?></td>
											<td><?php esc_html_e('#','truelysell_core');?><?php echo $commission['order_id']; ?></td>
											<td><?php echo date_i18n(get_option( 'date_format' ), strtotime($commission['date']));  ?></td>
											<td><span class="badge text-capitalize  <?php if($commission['status']=='paid') { ?>badge-success <?php } else { ?> badge-danger <?php } ?>"><?php echo $commission['status']; ?></span>
										</td>
 										</tr>
										 <?php 
										 $count++; endif; 
										} ?>

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
							<div class="alert alert-info"><?php esc_html_e('You don\'t have any earnings yet','truelysell_core'); ?></div>
		<?php } ?>

					</div>

					

				</div>
				<!-- /Coupouns -->


 	</div>
						
  </div>