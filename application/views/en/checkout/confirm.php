
<div class="content">
	<div class="container">
	
		<form name="confirm_payment" method="post" action="<?php echo base_url();?>the-payment-gateway.php">

			<h1>Confirm Your Details</h1>
			
			<p>
				Please confirm your Billing &amp; Delivery details,
			</p>
			
			<div class="checkout_details" >
				<div  id="address">
					<h3 style="margin-top: 5px;">Billing details</h3>
					
					<?php
					// payment details array
					$payment_data = ( ! empty( $payment_data ) ) ? $payment_data : ""; 
					?>
	
					<p>
						<?php echo $payment_data['billing_first_name']; ?> <?php echo $payment_data['billing_last_name']; ?>
					</p>
					
					<p>	
						<?php echo $payment_data['billing_house_number']; ?> <?php echo $payment_data['billing_street_name']; ?><br />
						<?php echo $payment_data['billing_town_city']; ?><br />
						<?php echo $payment_data['billing_county']; ?><br />					
						<?php echo $payment_data['billing_country']; ?><br />		
						<?php echo $payment_data['billing_postcode']; ?><br />
					</p>
					<p>
						<?php echo $payment_data['billing_telephone']; ?><br />		
					</p>
					
				</div>
				
				<hr>
				
				<div>
					<h3>Delivery address</h3>
					
					<?php if( ! empty( $payment_data['use_billing_address'] ) ) {?>
					
						<p>
								Use Billing Address.
						</p>
					
					<?php } else { ?>

						<p>
							<?php echo ( ! empty( $payment_data['shipping_first_name'] )) ? $payment_data['shipping_first_name'] : $payment_data['billing_first_name']; ?> 
							<?php echo ( ! empty( $payment_data['shipping_last_name'] )) ? $payment_data['shipping_last_name'] : $payment_data['billing_last_name']; ?>
						</p>
						
						<p>	
							<?php echo ( ! empty( $payment_data['shipping_house_number'] )) ? $payment_data['shipping_house_number'] : $payment_data['billing_house_number']; ?> 
							<?php echo ( ! empty( $payment_data['shipping_street_name'] )) ? $payment_data['shipping_street_name'] : $payment_data['billing_street_name']; ?><br />
							<?php echo ( ! empty( $payment_data['shipping_town_city'] )) ? $payment_data['shipping_town_city'] : $payment_data['billing_town_city']; ?><br />
							<?php echo ( ! empty( $payment_data['shipping_county'] )) ? $payment_data['shipping_county'] : $payment_data['billing_county']; ?><br />					
							<?php echo ( ! empty( $payment_data['shipping_country'] )) ? $payment_data['shipping_country'] : $payment_data['billing_country']; ?><br />		
							<?php echo ( ! empty( $payment_data['shipping_postcode'] )) ? $payment_data['shipping_postcode'] : $payment_data['billing_postcode']; ?><br />
						</p>
						<p>
							<?php echo ( ! empty( $payment_data['shipping_telephone'] )) ? $payment_data['shipping_telephone'] : $payment_data['billing_telephone']; ?><br />		
						</p>	

					<?php }?>
					
				</div>
			</div>		
			
			<div class="checkout_details">
				<div>
					<h3 style="margin-top: 5px;">Delivery method</h3>
			
						<?php
						if(!empty($delivery_rates)) {
							$delivery_sub_total = 0;
							foreach($delivery_rates as $delivery_rate) {
								
								$delivery_rate['cost'] = ( $delivery_rate['cost'] * $_SESSION['site']['currency_rate'] );
								$delivery_rate['cost'] = number_format((float)($delivery_rate['cost']), 2, '.', '');
									
								if(!empty($_POST['delivery_rates']) && $_POST['delivery_rates'] == $delivery_rate['id']) {
									$selected = " selected='selected' ";
									$delivery_sub_total = $delivery_rate['cost'];
								}
								else {
									$selected = "";
								}
								
								if(!empty($selected)) {
						?>
							<?php echo $delivery_rate['option'];?> - <?php echo $_SESSION['site']['currency_html'];?><?php echo $delivery_rate['cost'];?>
						<?php 
								}
							}
						}
						?>

				</div>
			</div>

			<?php if ( ! empty( $_SESSION['customer']['details']['id'] ) ) {?>
				<div class="checkout_details  checkout_logged_in_option">
					<div>
						<h3 style="margin-top: 5px;">Store credit</h3>
							<?php echo $_SESSION['site']['currency_html'];?><?php echo ( ! empty( $payment_data['order_store_credit'] ) ) ? $payment_data['order_store_credit'] : "0.00";?>
					</div>
				</div>			
			<?php }?>
			
			<div class="checkout_details">
				<div>
					<h3 style="margin-top: 5px;">Total Payable</h3>
					<p>
						<strong>Sub total:</strong> <?php echo $_SESSION['site']['currency_html'];?><?php echo $payment_data['order_sub_total_cost'];?>
					</p>
					<p>
						<strong>Delivery:</strong> <?php echo $_SESSION['site']['currency_html'];?><?php echo $delivery_sub_total;?>
					</p>
					<?php if ( ! empty( $_SESSION['customer']['details']['id'] ) ) {?>
						<p>
							<strong>Store Credit:</strong> <?php echo $_SESSION['site']['currency_html'];?><?php echo $total_cost_of_order = number_format((float) ( $payment_data['order_store_credit'] ), 2, '.', ''); ?>
						</p>
					<?php }?>
					<p>
						<strong>TOTAL:</strong> <?php echo $_SESSION['site']['currency_html'];?><?php echo $total_cost_of_order = number_format((float) ( $payment_data['order_total_cost'] ), 2, '.', ''); ?>
					</p>

					</div>
			</div>		
			
			<div style="clear:both;"></div>
			<div class="checkout_details" style="border: 0px">
				<a class="continue_button checkout_items_button data-tag" data-tag="checkout-basket-continue-shopping-button" href="<?php echo base_url();?>checkout/details">Edit details</a>
				<input class="update_button checkout_items_button checkout_button data-tag" data-tag="checkout-details-proceed-to-secure-payment-button"  type="submit" name="checkout_details_form_submit" value="Proceed To Secure Payment >>">
			</div>
			<div style="clear:both;"></div>
			
		</form>
			
	</div>
</div>			
		