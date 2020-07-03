<div class="admin_content">
	<h1>View all orders</h1>
	<hr>
	
	<p style="float:left;margin-bottom:10px;">
		Total <?php echo count($count);?> records found
	</p>	
	
	<form method="post"  action="<?php echo base_url();?>admin/sales/all">
	
		<p style="float:right;margin-top:10px;" >
			<strong>Search:</strong> 
			<input type="text" name="keyword" placeholder="" value="<?php echo (!empty($keyword)) ? $keyword : "";?>">
			<input type="submit" name="submit" value=">>" style="width:50px;">
		</p>
		
		<div style="clear:both;"></div>

		<?php if($this->session->flashdata('msg')): ?>
			<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
		<?php endif; ?>
		
		<table id="products_table" class="all_table display">
			<thead>
				<tr>	
					<th style="width:100px;">Order #</th>
					<th style="width:100px;">Date</th>
					<th style="width:100px;">Bill to Name</th>
					<th style="width:100px;">Country ( Billing )</th>				
					<th style="width:100px;">Ship to Name</th>
					<th style="width:100px;">Country ( Shipping )</th>	
					<th style="width:100px;">Method ( Payment )</th>	
					<th style="width:100px;">Total ( Currency )</th>
					<th style="width:100px;">Order Status</th>				
					<th style="width:45px;">Edit</th>
					<th style="width:45px;">Delete</th>						
				</tr>
				
				<tr>
					<th style="width:100px;text-align:right;">
					&nbsp;
					</th>
					<th style="width:100px;">
						<input type="text" name="start_date" id="start_date" value="" placeholder="Start Date" style="width:70px;" readonly> - <input type="text" name="end_date" id="end_date" value="" placeholder="End Date"  style="width:70px;" readonly>	
					</th>
					<th style="width:100px;">
						&nbsp;				
					</th>
					<th style="width:100px;">
						&nbsp;
					</th>				
					<th style="width:100px;">
						&nbsp;
					</th>
					<th style="width:100px;">
						&nbsp;
					</th>	
					<th style="width:100px;">
						
						<select name="order by_payment_system" style="width:110px;">
							<option value="">-- select --</option>								
							<option value="1">Sagepay</option>
							<option value="3">Paypal</option>		
							<option value="2">Alipay</option>			
							<option value="4">Store Credit</option>								
						</select>		
					</th>	
					<th style="width:100px;">
						&nbsp;
					</th>
					<th style="width:120px;">
						<!-- 1 pending payment, 2, awaiting shipping, 3 completed, 4 cancelled, 7 confirming order -->
						<select name="order by_status" style="width:120px;">
							<option value="">-- select --</option>								
							<option value="1">Pending Payment</option>
							<option value="2">Awaiting Shipping</option>		
							<option value="3">Completed</option>	
							<option value="4">Cancelled</option>	
							<option value="7">Confirming Order</option>							
						</select>					
					</th>				
					<th style="width:45px;">
						&nbsp;
					</th>
					<th style="width:45px;">
						&nbsp;
					</th>						
				</tr>
				
			</thead>
			<tbody>
		
			<?php
			$this->load->model('admin_sales_model');		
			if(!empty($orders)) {
				$x = 0;
				foreach ($orders as $order){
					$style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
				?>
						<tr style="<?php echo $style_row;?>">
							<td style="text-align:center;">
								<?php echo $order['order_id'];?>					
							</td>
							<td style="text-align:center;">
									<?php echo date("H:ia d/m/Y", strtotime($order['order_date']));?>
							</td>
							<td style="text-align:center;">
								<?php echo $order['billing_first_name'];?> <?php echo $order['billing_last_name'];?>
							</td>
							<td style="text-align:center;">
								<?php echo $order['billing_country'];?>
							</td>
							<td style="text-align:center;;">
								<?php echo (!empty($order['shipping_first_name'])) ? $order['shipping_first_name'] : $order['billing_first_name']; ?> <?php echo (!empty($order['shipping_last_name'])) ? $order['shipping_last_name'] : $order['billing_last_name']; ?>
							</td>
							<td style="text-align:center;">
								<?php echo (!empty($order['shipping_country'])) ? $order['shipping_country'] : $order['billing_country']; ?>
							</td>
							<td style="text-align:center;">
								<?php
								switch($order['order_payment_method']) {
									case 1:
										$value = "Sagepay";
										break;
									case 2:
										$value = "Alipay";
										break;
									case 3:
										$value = "Paypal";
										break;		
									case 4:
										$value = "Paypal";
										break;										
									default:
										$value = "";
								}
								echo $value ;					
								?>
							</td>	
							<td style="text-align:center;">
								<?php echo (!empty($order['order_total_cost'])) ? $order['order_total_cost'] ." ( ".$order['order_currency']." ) ": "";?>
							</td>
								<?php 	
								switch($order['order_status']) {
									case 1:
										$value = "Pending Payment";
										$style = " font-weight: bold; color: #fff; background-color: #FF7373; ";
										break;
									case 2:
										$value = "Awaiting Shipping";
										$style = " font-weight: bold; color: #fff; background-color: #FFBB7D; ";									
										break;
									case 3:
										$value = "Completed";
										$style = " font-weight: bold; color: #fff; background-color: #4AE371; ";									
										break;
									case 4:
										$value = "Cancelled";
										$style = " font-weight: bold; color: #fff; background-color: #D69E87; ";								
										break;	
									case 7:
										$value = "Confirming Order";
										$style = " font-weight: bold; color: #fff; background-color: #2FAACE; padding: 5px;";				
										$status = 7;			
										break;										
									default:
										$value = "In Progress";
										$style = " font-weight: bold; color: #fff; background-color: #DD75DD; ";											
								}
								
								// mark orders that have not been checked out within 1 hour as abandoned
								$checkout_expired = 3600; // seconds ( 1 hour = 3600 )
								$order_time = date("U", strtotime($order['order_date'] ));
								if( $value == "In Progress" && ( date("U")  - $order_time )  > $checkout_expired ) {
									$value = "Abandoned Cart";
									$style = " font-weight: bold; color: #4F5155; background-color: #E9F1EA; ";									
								}
								?>						
							<td style="text-align:center; <?php echo $style;?> ">
								<div class="tooltip"><?php echo $value ;?>
									<?php $order_comment = $this->admin_sales_model->get_last_order_comment( $order['order_id'] );?>							
										<?php if ( ! empty($order_comment ) ) {?>
											<span class="tooltiptext">										
												<strong>Date:</strong> <?php echo date("H:ia d/m/Y", strtotime($order_comment[0]['date_added']));?><br />
												<strong>Admin:</strong> <?php echo $order_comment[0]['username'];?><br />
												<strong>Comment:</strong> <?php echo $order_comment[0]['comments'];?>									
											</span>	
										<?php } else { ?>
											<div class="tooltiptext" style="text-align:center;">		
												<strong>Order Status: </strong> <?php echo $value ;?>
											</div>
										<?php }?>
								</div>
							</td>		
							<td style="text-align:center;">
								<a href="<?php echo base_url('admin/sales/details/'.$order['id']);?>">
									<img src="<?php echo base_url('assets/images/edit.png');?>" border="0">
								</a>
							</td>
							<td style="text-align:center;"><img src="<?php echo base_url('assets/images/delete.png');?>"></td>
						</tr>

				<?php 
					$x++;
				} 
			} else { ?>
					<tr>
						<td colspan="11" style="text-align: center;">
							<p>No orders to display ...</p>
						</td>
					</tr>
			<?php } ?>
		
			</tbody>
		</table>
		
		<p class="pagination">
			<?php echo $pagination;?>
		</p>

	</form>
		
	
</div>

<style>
.tooltip {
    position: relative;
    display: inline-block;
	cursor: help;
}

.tooltip .tooltiptext {
    visibility: hidden;
    min-width: 240px;
    background-color: #F0F0F0;
    color: #777;
    text-align: left;
    border-radius: 5px;
	border: 1px solid #777;
    padding: 5px;
	font-weight: normal;
	right: 50px;

    /* Position the tooltip */
    position: absolute;
    z-index: 1;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>

<script>
// set datepicker field
$( "#start_date, #end_date" ).datepicker({dateFormat: 'dd-mm-yy'});
</script>