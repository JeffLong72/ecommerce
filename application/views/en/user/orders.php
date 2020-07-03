<div class="content">
	<h1>Customer Area</h1>

	<?php echo $user_menu;?>
	
	<div class="user_account_right">
			<h2>All Orders</h2>
					<?php if (!empty($orders)) {?>
						<?php foreach($orders as $order) {?>				
							<div class="customer_order_details">
								<strong>Order ID:</strong> <?php echo $order['order_id'];?>
							</div>
							<div class="customer_order_details">							
								<strong>Date:</strong> <?php echo date("H:ia d/m/Y", strtotime($order['order_date']));?>	
							</div>
							<div class="customer_order_details">
								<strong>Status:</strong> 
								<?php 
								switch($order['order_status']) {

                                    case 1:
                                        echo " Pending Payment";
                                        break;
									case 2:
										echo " (Paid) Waiting shipment";
                                        break;
                                    case 3:
                                        echo "Completed";
                                        break;
                                    case 4:
										echo "Cancelled";
										break;
                                    case 7:
                                        echo " (Paid) Waiting shipment";
                                        break;
									default:
										echo "---";
								}								
								?>
							</div>							
							<div class="customer_order_details">							
								<strong>Ship To:</strong> <br />
								<?php if($order['use_billing_address']) {?>
									<?php echo $order['billing_first_name'];?> <?php echo $order['billing_last_name'];?>,<br />
									<?php echo $order['billing_house_number'];?>, <?php echo $order['billing_street_name'];?>,<br />
									<?php echo $order['billing_town_city'];?>, <br />
									<?php echo $order['billing_county'];?>,<br />								
									<?php echo $order['billing_country'];?>, <br />
									<?php echo $order['billing_postcode'];?><br />		
								<?php } else { ?>
									<?php echo $order['shipping_first_name'];?> <?php echo $order['shipping_last_name'];?>,<br />
									<?php echo $order['shipping_house_number'];?>, <?php echo $order['shipping_street_name'];?>,<br />
									<?php echo $order['shipping_town_city'];?>, <br />
									<?php echo $order['shipping_county'];?>,<br />								
									<?php echo $order['shipping_country'];?>, <br />
									<?php echo $order['shipping_postcode'];?><br />									
								<?php } ?>
							</div>
							<div class="customer_order_details">							
								<strong>Products:</strong>
								<?php
									$this->load->model('user_model');
									$products = $this->user_model->get_user_orders_products($order['order_id']);
									if(!empty($products)) {
										foreach($products as $product) {
										?>
										<div style="margin: 5px 0px 5px 0px;">
											Sku: <?php echo $product['sku'];?><br />
											Title: <?php echo $product['product_title'];?><br />
											Qty: <?php echo $product['qty'];?><br />
											Price: <?php echo $product['currency_html'];?><?php echo number_format((float)( $product['cost'] * $product['exchange_rate'] ), 2, '.', '');?>
										</div>
										<?php 
										}
									}
								?>
							</div>
							<div class="customer_order_details">							
								<strong>Store Credit:</strong> <?php echo $order['currency_html'];?><?php echo number_format((float)( $order['order_store_credit'] * $order['order_exchange_rate'] ), 2, '.', '');?>
							</div>								
							<div class="customer_order_details">							
								<strong>Delivery:</strong> <?php echo $order['currency_html'];?><?php echo number_format((float)( $order['order_delivery_cost'] * $order['order_exchange_rate'] ), 2, '.', '');?>
							</div>								
							<div class="customer_order_details">							
								<strong>Total:</strong> <?php echo $order['currency_html'];?><?php echo number_format((float)( $order['order_total_cost']* $order['order_exchange_rate'] ), 2, '.', '');?>
							</div>	
							<div class="customer_order_details">							
								<strong>Payment Method:</strong> 
								<?php 
								switch($order['order_payment_method']) {
									case 1:
										echo "Sagepay";
										break;
									case 2:
										echo "Alipay";
										break;
									case 3:
										echo "Paypal";
										break;
									default:
										echo "---";
								}								
								?>	
							<p>
								<?php if ( $order['order_status'] == 3 ) {?>
									<a class="user_button_update" href="<?php echo base_url();?>user/print_invoice_pdf/<?php echo $order['order_id'];?>" target="_blank">View Invoice</a>
								<?php } else { ?>
									<a class="user_button_update" style="background-color: #ff9447 !important;" href="<?php echo base_url();?>checkout/basket/<?php echo $order['order_id'];?>">Proceed to Payment</a>
								<?php }?>
							</p>
							</div>								
							<hr>
						<?php }?>
					<?php } else {?>
						<p>
							You have not made any orders yet.
						</p>
					<?php }?>
	</div>
	
</div>

<div style="clear:both;"></div>

	<style>
	#customer_order_table th {
		width: 110px;
		text-align:left;
	}
	#customer_order_table td {
		width: 110px;	
		text-align:left;		
	}
	.customer_order_details {
		padding: 3px;
	}
	</style>