<div class="admin_content">

	<?php 
	
	switch($order_details['order_status']) {
		case 1:
			$order_status = "Pending Payment";
			$style = " font-weight: bold; color: #fff; background-color: #FF7373; padding: 5px; ";
			$status = 1;
			break;
		case 2:
			$order_status = "Awaiting Shipping";
			$style = " font-weight: bold; color: #fff; background-color: #FFBB7D; padding: 5px;";		
			$status = 2;			
			break;
		case 3:
			$order_status = "Completed";
			$style = " font-weight: bold; color: #fff; background-color: #4AE371; padding: 5px;";		
			$status = 3;			
			break;
		case 4:
			$order_status = "Cancelled";
			$style = " font-weight: bold; color: #fff; background-color: #D69E87; padding: 5px;";				
			$status = 4;			
			break;	
		case 7:
			$order_status = "Confirming Order";
			$style = " font-weight: bold; color: #fff; background-color: #2FAACE; padding: 5px;";				
			$status = 7;			
			break;				
		default:
			$order_status = "In Progress";
			$style = " font-weight: bold; color: #fff; background-color: #DD75DD; padding: 5px;";	
			$status = 5;			
	}
							
	// mark orders that have not been checked out within 1 hour as abandoned
	$checkout_expired = 3600; // seconds ( 1 hour = 3600 )
	$order_time = date("U", strtotime($order_details['order_date'] ));
	if( $order_status == "In Progress" && ( date("U")  - $order_time )  > $checkout_expired ) {
		$order_status = "Abandoned Cart";
		$style = " font-weight: bold; color: #4F5155; background-color: #E9F1EA; padding: 5px; border: 1px solid #777;";
		$status = 6;					
	}	
	?>
	
	<h1>View Order # <?php echo $order_details['order_id'];?> <span style="border-radius: 5px 5px 5px 5px;<?php echo $style;?>"> <?php echo $order_status;?> </span></h1>
	<hr>

	<div style="text-align:right; margin:20px;">			
		<a class="action_button" href="<?php echo base_url();?>admin/sales/print_invoice_pdf/<?php echo $order_details['order_id'];?>" target="_blank">Print Invoice</a>			
		<a class="action_button" href="<?php echo base_url();?>admin/sales/royal_mail/<?php echo $order_details['order_id'];?>">Royal Mail</a>
		<a class="action_button" href="<?php echo base_url();?>admin/sales/bpost/<?php echo $order_details['order_id'];?>">Bpost</a>
		<a class="action_button" href="<?php echo base_url();?>admin/sales/parcel_force/<?php echo $order_details['order_id'];?>">Parcel Force</a>		
	</div>
	
	<div style="clear:both;"></div>

	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>
	
	<?php if(validation_errors()): ?>
		<p style="color:red;" style="margin-left:10px;">
			Oops, some required form fields are empty!
		</p>
	<?php endif; ?>	

	<form action="<?php echo current_url();?>" method="post" name="order_details" id="order_details">
	<input type="hidden" name="order_id" value="<?php echo $order_details['order_id'];?>">
	
	<!-- order details //-->
	<div style="float:left; width:49%;margin-bottom: 20px;margin-right: 20px;">

		<table id="products_table" class="all_table">
			<thead>
				<tr>
					<th colspan="2" style="width:400px;">Order Details</th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<strong>Order #</strong>
					</td>
					<td>
						<?php echo $order_details['order_id'];?>
					</td>			
				</tr>			
				<tr>
					<td>
						<strong>Order Date:</strong>
					</td>
					<td>
						<?php echo date("H:ia d/m/Y", strtotime($order_details['order_date']));?>
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Order Status:</strong>
					</td>
					<td>
						<input type="hidden" name="original_order_status" value="<?php echo $order_details['order_status'];?>">
						<select name="order_status">
							<?php if ( $status < 5  || $status == 7 ) {?>
								<option value="1" <?php if($order_details['order_status'] == 1) { echo " selected='selected' "; } ?>>Pending Payment</option>
								<option value="7" <?php if($order_details['order_status'] == 7) { echo " selected='selected' "; } ?>>Confirming Order</option>								
								<option value="2" <?php if($order_details['order_status'] == 2) { echo " selected='selected' "; } ?>>Awaiting Shipping</option>
								<option value="3" <?php if($order_details['order_status'] == 3) { echo " selected='selected' "; } ?>>Completed</option>
								<option value="4" <?php if($order_details['order_status'] == 4) { echo " selected='selected' "; } ?>>Cancelled</option>
							<?php } ?>
							<?php if ( $status == 5 ) {?>
								<option value="" <?php if($order_details['order_status'] == 5) { echo " selected='selected' "; } ?>>In Progress</option> 
							<?php } ?>
							<?php if ( $status == 6 ) {?>
								<option value="" <?php if($order_details['order_status'] == 5) { echo " selected='selected' "; } ?>>Abandoned Cart</option>
							<?php } ?>							
						</select>
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Order Device:</strong>
					</td>
					<td>
						<?php echo (!empty($order_details['user_device'])) ? ucfirst( $order_details['user_device'] ) : "Unknown";?>
					</td>			
				</tr>					
			</tbody>
		</table>
	
	</div>
	
	<!-- account information //-->
	<div style="float:left; width:49%;margin-bottom: 20px;">

		<table id="products_table" class="all_table display">
			<thead>
				<tr>
					<th colspan="2" style="width:400px;">Customer Details</th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<strong>Status:</strong>
					</td>
					<td>
						<?php 
						$user_status = $this->admin_sales_model->get_customer_status( $order_details['email'] );
						echo $user_status;
						?>
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Email:</strong>
					</td>
					<td>
						<input type="text" name="email" value="<?php echo $order_details['email'];?>">
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Total Orders:</strong>
					</td>
					<td>
					<?php 
					$count= $this->admin_sales_model->get_customer_purchase_count( $order_details['email'] );
					echo $count;
					?>
					</td>			
				</tr>		
				<tr>
					<td>
						<strong>Last Page Viewed:</strong>
					</td>
					<td>
					<?php 
					$page_url = $this->admin_sales_model->get_customer_page_view( $order_details['user_session'] ); 
					if(!empty($page_url)) {?>
					<a href="<?php echo $page_url;?>" target="_blank"><?php echo $page_url;?></a>
					<?php } else { ?>
						No page details available
					<?php } ?>
					</td>			
				</tr>					
			</tbody>
		</table>
	
	</div>	
	
	<div style="clear:both;"></div>
	
	<!-- billing address //-->
	
	<div style="float:left; width:49%;margin-bottom: 20px;margin-right: 20px;">

		<table id="products_table" class="all_table display">
			<thead>
				<tr>
					<th colspan="2" style="width:400px;">Billing Address</th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<strong>First Name:</strong>
					</td>
					<td>
						<input type="text" name="billing_first_name" value="<?php echo $order_details['billing_first_name'];?>">
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Last Name:</strong>
					</td>
					<td>
						<input type="text" name="billing_last_name" value="<?php echo $order_details['billing_last_name'];?>">
					</td>			
				</tr>							
				<tr>
					<td>
						<strong>House Number:</strong>
					</td>
					<td>
						<input type="text" name="billing_house_number" value="<?php echo $order_details['billing_house_number'];?>">
					</td>
				</tr>
				<tr>
					<td>
						<strong>Street Name:</strong>
					</td>
					<td>
						<input type="text" name="billing_street_name" value="<?php echo $order_details['billing_street_name'];?>">
					</td>
				</tr>
				<tr>
					<td>
						<strong>Town/City:</strong>
					</td>
					<td>
						<input type="text" name="billing_town_city" value="<?php echo $order_details['billing_town_city'];?>">
					</td>
				</tr>					
				<tr>
					<td>
						<strong>County:</strong>
					</td>
					<td>
						<input type="text" name="billing_county" value="<?php echo $order_details['billing_county'];?>">
					</td>
				</tr>	
				<tr>
					<td>
						<strong>Country:</strong>
					</td>
					<td>
						<input type="text" name="billing_country" value="<?php echo $order_details['billing_country'];?>">
					</td>
				</tr>	
				<tr>
					<td>
						<strong>Postcode:</strong>
					</td>
					<td>
						<input type="text" name="billing_postcode" value="<?php echo $order_details['billing_postcode'];?>">
					</td>
				</tr>
				<tr>
					<td>
						<strong>Telephone:</strong>
					</td>
					<td>
						<input type="text" name="billing_telephone" value="<?php echo $order_details['billing_telephone'];?>">
					</td>
				</tr>				
			</tbody>
		</table>
	
	</div>
	
	<!-- shipping address //-->
	
	<div style="float:left; width:49%;margin-bottom: 20px;">

		<table id="products_table" class="all_table display">
			<thead>
				<tr>
					<th colspan="2" style="width:400px;">Shipping Address</th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<strong>First Name:</strong>
					</td>
					<td>
						<input type="text" name="shipping_first_name" value="<?php echo (!empty($order_details['shipping_first_name'])) ? $order_details['shipping_first_name'] :  $order_details['billing_first_name'];?>">
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Last Name:</strong>
					</td>
					<td>
						<input type="text" name="shipping_last_name" value="<?php echo (!empty($order_details['shipping_last_name'])) ? $order_details['shipping_last_name'] :  $order_details['billing_last_name'];?>">
					</td>			
				</tr>					
				<tr>
					<td>
						<strong>House Number:</strong>
					</td>
					<td>
						<input type="text" name="shipping_house_number" value="<?php echo (!empty($order_details['shipping_house_number'])) ? $order_details['shipping_house_number'] : $order_details['billing_house_number'];?>">
					</td>
				</tr>
				<tr>
					<td>
						<strong>Street Name:</strong>
					</td>
					<td>
						<input type="text" name="shipping_street_name" value="<?php echo (!empty($order_details['shipping_street_name'])) ? $order_details['shipping_street_name'] : $order_details['billing_street_name'];?>">
					</td>
				</tr>
				<tr>
					<td>
						<strong>Town/City:</strong>
					</td>
					<td>
						<input type="text" name="shipping_town_city" value="<?php echo (!empty($order_details['shipping_town_city'])) ? $order_details['shipping_town_city'] : $order_details['billing_town_city'];?>">
					</td>
				</tr>					
				<tr>
					<td>
						<strong>County:</strong>
					</td>
					<td>
						<input type="text" name="shipping_county" value="<?php echo (!empty($order_details['shipping_county'])) ? $order_details['shipping_county'] : $order_details['billing_county'];?>">
					</td>
				</tr>	
				<tr>
					<td>
						<strong>Country:</strong>
					</td>
					<td>
						<input type="text" name="shipping_country" value="<?php echo (!empty($order_details['shipping_country'])) ? $order_details['shipping_country'] : $order_details['billing_country'];?>">
					</td>
				</tr>	
				<tr>
					<td>
						<strong>Postcode:</strong>
					</td>
					<td>
						<input type="text" name="shipping_postcode" value="<?php echo (!empty($order_details['shipping_postcode'])) ? $order_details['shipping_postcode'] : $order_details['billing_postcode'];?>">
					</td>
				</tr>
				<tr>
					<td>
						<strong>Telephone:</strong>
					</td>
					<td>
						<input type="text" name="shipping_telephone" value="<?php echo (!empty($order_details['shipping_telephone'])) ? $order_details['shipping_telephone'] : $order_details['billing_telephone'];?>">
					</td>
				</tr>				
			</tbody>
		</table>
	
	</div>
	
	<!-- payment information //-->
	
	<div style="float:left; width:49%;margin-bottom: 20px;margin-right: 20px;">

		<table id="products_table" class="all_table display">
			<thead>
				<tr>
					<th style="width:400px;">Payment Information</th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>
							<?php
							switch($order_details['order_payment_method']) {
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
									$value = "Store Credit";
									break;										
								default:
									$value = "";
							}
							$order_payment = "<p>".$value."</p>";	
							$order_payment.= "<p>Order was placed using ". $order_details['order_currency']."</p>";
							echo (!empty($value)) ? $order_payment : " Not Available ";
							?>
						</p>
					</td>			
				</tr>			
			</tbody>
		</table>
	
	</div>

	<!-- shipping information //-->
	
	<div style="float:left; width:49%;margin-bottom: 20px;">

		<table id="products_table" class="all_table display">
			<thead>
				<tr>
					<th style="width:400px;">Shipping &amp; Handling Information</th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>
							<?php 
							if( !empty($order_details['order_delivery_rate'])) {							
								$this->load->model('admin_sales_model');
								$delivery_option = $this->admin_sales_model->get_delivery_option( $order_details['order_delivery_rate'] );
								$delivery_cost = number_format((float)( $delivery_option['cost'] * $order_details['order_exchange_rate'] ), 2, '.', '');
								$delivery_details =  $delivery_option['option'].", ".$delivery_cost." ( ".$order_details['order_currency']." )";								
							}
							$delivery_details = (!empty($delivery_details)) ? $delivery_details : " None Selected ";
							echo $delivery_details;
							?>
						</p>
					</td>			
				</tr>			
			</tbody>
		</table>
	
	</div>
	
	<div style="clear:both;"></div>
	
	<!-- items ordered //-->
	
	<div style="float:left; width:99%;margin-bottom: 20px;">

		<table id="products_table" class="all_table display">
			<thead>
				<tr>
					<th colspan="8" style="width:400px;">Items Ordered</th>				
				</tr>
				<tr>
					<td>
						<strong>Image</strong>
					</td>
					<td>
						<strong>Description</strong>
					</td>				
					<td style="text-align:center;">
						<strong>Cost</strong>
					</td>
					<td style="text-align:center;">
						<strong>Qty </strong>
					</td>						
					<td style="text-align:center;">
						<strong>Subtotal</strong>
					</td>
					<td style="text-align:center;">
						<strong>Tax</strong>
					</td>				
					<td style="text-align:center;">
						<strong>Total</strong>
					</td>					
				</tr>				
			</thead>
			<tbody>
			<?php 
			$x = 0;
			$tax = 0;
			$this->load->model('products_model');
			foreach( $order_details_items as $item ) 
			{
				$style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
				
				// get product details 
				$item_details = $this->products_model->get_product_by_sku($item['sku']);
				
				// get product images
				$item_images = $this->products_model->get_product_images($item['sku']);
				$item_image = "";
				foreach($item_images as $image) {
					if(!empty($image['category'])) {
						$item_image = $image['category'];
					}
				}
				if(empty($item_image)) {
					$item_image = "assets/images/10.jpg";
				}
				
				$item_cost = (!empty($item['cost'])) ? number_format((float)( $item['cost'] * $item['exchange_rate'] ), 2, '.', '') : 0;
				?>
				
				<tr style="<?php echo $style_row;?>">
					<td>
						<img src="<?php echo base_url().$item_image;?>" style="width: 80px;">
					</td>
					<td>
						<?php echo $item['product_title'];?>
						<p><a href="<?php echo base_url();?>admin/products/all/<?php echo $item['sku'];?>" target="_blank"><?php echo $item['sku'];?></a></p>
					</td>				
					<td style="text-align:center;">
						<?php echo $item_cost ;?>
					</td>
					<td style="text-align:center;">
						<input style="width:25px;" type="text" name="product_qty[<?php echo $item['id'];?>]" value="<?php echo $item['qty'];?>">
					</td>						
					<td style="text-align:center;">
						<?php
						$subtotal = number_format((float)( $item_cost * $item['qty'] ), 2, '.', '');
						echo $subtotal;
						?>
					</td>	
					<td style="text-align:center;">
						<?php
						$vat = ( 20 / 100 ) + 1; // 20%
						$vat = $item_cost - ( $item_cost / $vat );
						$vat = number_format((float)( $vat * $item['qty'] ), 2, '.', '');
						$tax += $vat;
						echo $vat;
						?>
					</td>				
					<td style="text-align:center;">
						<?php
						$total = number_format((float)( $subtotal  ), 2, '.', '');
						echo $total;
						?>
					</td>					
				</tr>

				<?php $x++; } ?>
				
					<tr>
						<td colspan="7" style="text-align:right;">
							<strong>Refunded items cost as ... ( if applicable )</strong>
							<select name="order_refunded_type">
								<option value="">-- please select --</option>						
								<option value="1" <?php if($order_details['order_refunded_type'] == 1) {?>selected='selected' <?php }?>>Store Credit</option>
								<option value="2"<?php if($order_details['order_refunded_type'] == 2) {?>selected='selected'<?php }?>>Direct To Customer</option>	
							</select>
						</td>							
					</tr>	
				
				<?php if(empty($order_details_items)) { ?>
				
					<tr>
						<td colspan="7" style="text-align:center;">No items found for this order ...</td>				
					</tr>	
					
				<?php } ?>
		
			</tbody>
		</table>
	
	</div>	
	
	</form>
	
	<!-- comments history //-->
	
	<div style="float:left; width:49%;margin-bottom: 20px;margin-right: 20px;">
		<form action="<?php echo current_url();?>" method="post" name="add_new_order_comment_form" class="add_new_order_comment_form">
			<input type="hidden" name="add_comment_order_id" value="<?php echo $order_details['order_id'];?>">
			<table id="products_table" class="all_table display">
				<thead>
					<tr>
						<th colspan="2" style="width:400px;">Comments History</th>				
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2">
							<?php echo form_error('add_order_comment'); ?>
							<textarea style="width: 96%; height: 100px;" id="add_order_comment" name="add_order_comment"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;">
							<input class="action_button" style="margin-bottom: 0px;" type="submit" name="submit" value="Add New Comment">
						</td>
					</tr>	
					<?php if(!empty($order_comments)) {?>
						<?php foreach($order_comments as $comment) {?>
							<tr>
								<td colspan="2">
									<hr>
								</td>
							</tr>					
							<tr style="border-bottom: 1px solid #ccc;">
								<td>
									<strong>Date:</strong>
								</td>
								<td>					
										<?php echo date("H:ia d/m/Y", strtotime($comment['date_added']));?>
								</td>			
							</tr>
							<tr>
								<td>
									<strong>Admin:</strong>
								</td>
								<td>					
										<?php echo $comment['username'];?>
								</td>			
							</tr>					
							<tr>
								<td>
									<strong>Comment:</strong>
								</td>
								<td>					
									<?php echo $comment['comments'];?>
								</td>			
							</tr>
						<?php }?>				
					<?php }?>
					<tr>
						<td colspan="2">
							<hr>
						</td>
					</tr>				
				</tbody>
			</table>
		</form>
	</div>
	
	<!-- tracking details //-->
	
	<form action="<?php echo current_url();?>" method="post" name="order_tracking_codes">
	<input type="hidden" name="order_id" value="<?php echo $order_details['order_id'];?>">
	<input type="hidden" name="update_tracking_codes" value="1">	
	<div style="float:left; width:49%;margin-bottom: 20px;">
		<table id="products_table" class="all_table display">
			<thead>
				<tr>
					<th colspan="2" style="width:400px;">Order Tracking</th>				
				</tr>
			</thead>
			<tbody>
				<?php 
				$tracking_codes = $this->admin_sales_model->get_tracking_codes( $order_details['order_id'] );
				for( $x = 0; $x < 10; $x++) {
					?>
				<tr>
					<td>
						<strong>Product SKU:</strong> <input type="text" name="tracking_sku[]" value="<?php echo (!empty($tracking_codes[$x])) ? $tracking_codes[$x]['tracking_sku'] : "";?>">								 
					</td>
					<td>
							<strong>Tracking Code:</strong>	<input type="text" name="tracking_code[]" value="<?php echo (!empty($tracking_codes[$x])) ? $tracking_codes[$x]['tracking_code'] : "";?>">						 
					</td>						
				</tr>	
				<?php }?>
				<tr>
					<td colspan="2" style="text-align:center;">
							<input style="width:25px;" type="checkbox" name="email_tracking_codes" value="1"> Send tracking code(s) to the customer			 
					</td>						
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;">
						<p>
							<input class="action_button" style="margin-bottom: 0px;" type="submit" name="submit" value="Update Tracking Codes">
						 </p>							 
					</td>			
				</tr>		
			</tbody>
		</table>
	</form>
	</div>	
	
	<!-- order totals //-->
	
	<div style="float:right; width:49%; margin-bottom: 50px; margin-right: 16px;">

		<table id="products_table" class="all_table display">
			<thead>
				<tr>
					<th colspan="2" style="width:400px;">Order Totals</th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<strong>Subtotal</strong>
					</td>
					<td>
						<?php 
						$subtotal = (!empty($order_details['order_sub_total_cost'])) ? number_format((float)( $order_details['order_sub_total_cost'] * $order_details['order_exchange_rate'] ), 2, '.', '') : "0.00";
						echo $subtotal;
						?>
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Shipping &amp; Handling</strong>
					</td>
					<td>
						<?php echo (!empty($order_details['order_delivery_cost'])) ? $order_details['order_delivery_cost'] : "0.00";?>
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Discount ( VOUCHER-ID )</strong>
					</td>
					<td>
						0.00
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Tax</strong>
					</td>
					<td>
						<?php echo number_format((float)( $tax ), 2, '.', '');?> 
					</td>			
				</tr>	
				<tr>
					<td colspan="2" style="text-align:center;">
						<hr>
					</td>			
				</tr>					
				<tr>
					<td>
						<strong>Grand Total</strong>
					</td>
					<td>
						<?php echo $order_details['order_total_cost'];?> ( <?php echo $order_details['order_currency'];?> )
					</td>			
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;">
						<hr>
					</td>			
				</tr>					
				<tr>
					<td>
						<strong>Total Paid</strong>
					</td>
					<td>
						<?php $paid_amount = (!empty($order_details['order_paid_amount'])) ? $order_details['order_paid_amount'] : "0.00"; ?>
						<?php echo $paid_amount ;?>				
					</td>			
				</tr>
				<tr>
					<td>
						<strong>Total Refunded</strong>
					</td>
					<td>
						<?php // $total_refunded = (!empty($order_details['order_refunded_amount'])) ? $order_details['order_refunded_amount'] : "0.00"; ?>
						<?php $total_refunded = $paid_amount - $order_details['order_total_cost']; ?>
						<?php $total_refunded = ( $total_refunded <= 0 ) ? "0.00" : $total_refunded; ?>
						<?php echo number_format((float)( $total_refunded ), 2, '.', '');?>
					</td>			
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;">
						<hr>
					</td>			
				</tr>						
				<tr>
					<td>
						<strong>Total Due</strong>
					</td>
					<td>
						<?php 
						$total_due = ( $order_details['order_total_cost'] - $paid_amount );
						$total_due = number_format((float)( $total_due ), 2, '.', '');
						$total_due = ( $total_due <= 0 ) ? "0.00" : $total_due;
						echo $total_due." ( ".$order_details['order_currency']." )";
						?>
						
						<?php
						// update total refund and tax
						$data = array();
						$data['tax'] = $tax;
						$data['refund'] = $total_refunded;
						$data['order_id'] = $order_details['order_id'];						
						$result = $this->admin_sales_model->update_order_totals( $data );
						?>
					</td>			
				</tr>	
				<tr>
					<td colspan="2" style="text-align:center;">
						<hr>
					</td>			
				</tr>			
				<tr>
					<td colspan="2" style="text-align:center;">
						<?php if(!empty($result)) {?>
							<p style="color:green;">
								<strong>
									<?php echo $result;?>
								</strong>
							</p>
						<?php }?>
						<p>
							<input class="action_button" style="margin-bottom: 0px;" type="submit" name="submit" id="submit_order_details_form" value="Update Order">
						 </p>							 
					</td>			
				</tr>					
			</tbody>
		</table>
	
	</div>

	<div style="clear:both;"></div>
	
</div>

<style>
.admin_content th {
	margin: 0px;
	background-color: #F8F8F8;
	border-bottom: 1px solid #ccc;
	padding: 5px;
}
.admin_content td {
	padding: 5px;
	padding-left: 10px;
}
.admin_content .all_table {
	border: 1px solid #ccc;
}

</style>