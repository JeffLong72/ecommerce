<div class="content">
	<div class="container">
		<form action="<?php echo base_url();?>checkout/details" method="post" name="checkout_details_form" class="checkout_details_form">
		
			<h1>Your Details</h1>
			<span class="checkout_required_field" style="margin-left:10px;">* required field</span>
			
			<?php if($this->session->flashdata('msg')): ?>
				<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
			<?php endif; ?>

			<?php if(validation_errors()): ?>
				<p class="checkout_required_field" style="margin-left:10px;">
					Oops, some required fields are empty!
				</p>
			<?php endif; ?>			

			<div class="checkout_details login_details">
				<?php if(empty($_SESSION['customer']['details'])) {?>
					<div>
						<h3 style="margin-top: 5px;">Existing Customer?</h3>
						<span class="login_info"></span>
						<label for="login_email">Email</label><br />
						<input type="text" name="login_email" id="login_email" value="<?php echo set_value('login_email'); ?>"><br />
						<label for="login_password">Password</label><br />
						<input type="password" name="login_password" id="login_password" value=""><br /><br />
						<a class="checkout_login_button data-tag" data-tag="checkout-details-login-button" href="#">Login</a><br /><br />
					</div>
					<hr>
					<div>
						<h3>or, Continue as Guest</h3>
						<?php echo form_error('guest_email'); ?>
						<label for="guest_email">Your email</label><br />
						<input type="text" name="guest_email" id="guest_email" value="<?php echo set_value('guest_email'); ?>">
						<br /><br />
					</div>
				<?php } else { ?>
					<h3>Welcome <?php echo $_SESSION['customer']['details']['first_name'];?>!</h3><p>Please enter your details below to proceed to payment.<p>
					<input type="hidden" name="login_email" id="login_email" value="<?php echo $_SESSION['customer']['details']['email']; ?>">
				<?php }?>
			</div>
			
			<div class="checkout_details" >
				<div  id="address">
					<h3 style="margin-top: 5px;">Billing address</h3>
					
					    <p id="locationField">
							Find your address (optional): <br />
							<input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="text"></input>
						</p>
	
					<?php echo form_error('billing_first_name'); ?>
					<?php $billing_first_name = (!empty($_SESSION['customer']['details']['first_name'])) ? $_SESSION['customer']['details']['first_name']  : set_value('billing_first_name'); ?>
					<label for="billing_first_name">First name <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="billing_first_name" id="billing_first_name" value="<?php echo $billing_first_name; ?>"><br />
					<?php echo form_error('billing_last_name'); ?>
					<?php $billing_last_name = (!empty($_SESSION['customer']['details']['last_name'])) ? $_SESSION['customer']['details']['last_name']  : set_value('billing_last_name'); ?>
					<label for="billing_last_name">Last name <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="billing_last_name" id="billing_last_name" value="<?php echo $billing_last_name; ?>"><br />					
					<?php echo form_error('billing_house_number'); ?>
					<?php $billing_house_number = (!empty($_SESSION['customer']['details']['house_number'])) ? $_SESSION['customer']['details']['house_number']  : set_value('billing_house_number'); ?>
					<label for="billing_house_number">House No. <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="billing_house_number" id="billing_house_number" value="<?php echo $billing_house_number; ?>"><br />
					<?php echo form_error('billing_street_name'); ?>
					<?php $billing_street_name = (!empty($_SESSION['customer']['details']['street_name'])) ? $_SESSION['customer']['details']['street_name']  : set_value('billing_street_name'); ?>					
					<label for="billing_street_name">Street name <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="billing_street_name" id="billing_street_name" value="<?php echo $billing_street_name; ?>"><br />
					<?php echo form_error('billing_town_city'); ?>
					<?php $billing_town_city = (!empty($_SESSION['customer']['details']['town_city'])) ? $_SESSION['customer']['details']['town_city']  : set_value('billing_town_city'); ?>		
					<label for="billing_town_city">Town/City <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="billing_town_city" id="billing_town_city" value="<?php echo $billing_town_city; ?>"><br />
					<?php echo form_error('billing_county'); ?>
					<?php $billing_county = (!empty($_SESSION['customer']['details']['county'])) ? $_SESSION['customer']['details']['county']  : set_value('billing_county'); ?>	
					<label for="billing_county">County</label><br />
					<input type="text" name="billing_county" id="billing_county" value="<?php echo $billing_county; ?>"><br />					
					<?php echo form_error('billing_country'); ?>
					<?php $billing_country = (!empty($_SESSION['customer']['details']['country'])) ? $_SESSION['customer']['details']['country']  : set_value('billing_country'); ?>
					<label for="billing_country">Country <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="billing_country" id="billing_country" value="<?php echo $billing_country; ?>"><br />		
					<?php echo form_error('billing_postcode'); ?>
					<?php $billing_postcode = (!empty($_SESSION['customer']['details']['postcode'])) ? $_SESSION['customer']['details']['postcode']  : set_value('billing_postcode'); ?>
					<label for="billing_postcode">Postcode <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="billing_postcode" id="billing_postcode" value="<?php echo $billing_postcode; ?>"><br />		
					<?php echo form_error('billing_telephone'); ?>
					<?php $billing_telephone = (!empty($_SESSION['customer']['details']['telephone'])) ? $_SESSION['customer']['details']['telephone']  : set_value('billing_telephone'); ?>
					<label for="billing_telephone">Telephone <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="billing_telephone" id="billing_telephone" value="<?php echo $billing_telephone; ?>"><br />		
					<br />					
				</div>
				<hr>
				<div>
					<h3>Delivery address</h3>
					<input class="data-tag" data-tag="checkout-details-delivery-address-checkbox" type="checkbox" name="use_billing_address" id="use_billing_address" value="1" checked="checked"> Use billing address?
					<br /><br />
						<div id="show_shipping_address" style="display:none;">
							<label for="shipping_first_name">First name</label><br />
							<input type="text" name="shipping_first_name" id="shipping_first_name" value="<?php echo set_value('shipping_first_name'); ?>"><br />
							<label for="shipping_last_name">Last name</label><br />
							<input type="text" name="shipping_last_name" id="shipping_last_name" value="<?php echo set_value('shipping_last_name'); ?>"><br />								
							<label for="shipping_house_number">House No.</label><br />
							<input type="text" name="shipping_house_number" id="shipping_house_number" value="<?php echo set_value('shipping_house_number'); ?>"><br />
							<label for="shipping_street_name">Street name</label><br />
							<input type="text" name="shipping_street_name" id="shipping_street_name" value="<?php echo set_value('shipping_street_name'); ?>"><br />
							<label for="shipping_town_city">Town/City</label><br />
							<input type="text" name="shipping_town_city" id="shipping_town_city" value="<?php echo set_value('shipping_town_city'); ?>"><br />
							<label for="shipping_county">County</label><br />
							<input type="text" name="shipping_county" id="shipping_county" value="<?php echo set_value('shipping_county'); ?>"><br />					
							<label for="shipping_country">Country</label><br />
							<input type="text" name="shipping_country" id="shipping_country" value="<?php echo set_value('shipping_country'); ?>"><br />		
							<label for="shipping_postcode">Postcode</label><br />
							<input type="text" name="shipping_postcode" id="shipping_postcode" value="<?php echo set_value('shipping_postcode'); ?>"><br />		
							<label for="shipping_telephone">Telephone</label><br />
							<input type="text" name="shipping_telephone" id="shipping_telephone" value="<?php echo set_value('shipping_telephone'); ?>"><br />		
							<br />					
						</div>							
				</div>
			</div>		
			
			<div class="checkout_details">
				<div>
					<h3 style="margin-top: 5px;">Choose delivery method</h3>
					<?php echo form_error('delivery_rates'); ?>
					<select class="data-tag" data-tag="checkout-details-delivery-address-method-drop" name="delivery_rates" id="delivery_rates">
						<option value=""> -- Select Option -- </option>					
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
						?>
							<option data-cost="<?php echo $delivery_rate['cost'];?>" value="<?php echo $delivery_rate['id'];?>" <?php echo $selected;?> ><?php echo $delivery_rate['option'];?> - <?php echo $_SESSION['site']['currency_html'];?><?php echo $delivery_rate['cost'];?></option>
						<?php 
							}
						}
						?>
					</select> <span class="checkout_required_field">*</span>
				</div>
			</div>

			<?php /* ?>
			<div class="checkout_details">
				<div>
					<h3 style="margin-top: 5px;">Optional: Discount voucher</h3>
					<label for="order_voucher_code">Voucher code</label><br />
					<input type="text" name="order_voucher_code" id="order_voucher_code" value="<?php echo set_value('order_voucher_code'); ?>"> <span id="voucher_valid"></span><br />
				</div>
			</div>
			<?php */ ?>
			
			<div class="checkout_details  checkout_logged_in_option">
				<div>
					<h3 style="margin-top: 5px;">Optional: Store credit</h3>
					<?php 
					$available_store_credit = (!empty($_SESSION['customer']['details'])) ? $_SESSION['customer']['details']['store_credit'] : "0.00"; 
					$available_store_credit = ( $available_store_credit * $_SESSION['site']['currency_rate'] );
					$available_store_credit = number_format((float)($available_store_credit), 2, '.', '');					
					?>
					<input type="hidden" name="available_store_credit" id="available_store_credit" value="<?php echo $available_store_credit;?>">
					<?php echo form_error('order_store_credit'); ?>
					<p>Your available store credit is: <?php echo $_SESSION['site']['currency_html'];?><?php echo $available_store_credit;?></p>
					<label for="order_store_credit">Amount</label><br />
					<?php echo $_SESSION['site']['currency_html'];?><input type="text" name="order_store_credit" id="order_store_credit" value="<?php echo set_value('order_store_credit'); ?>" placeholder="0.00" style="min-width: 80px;max-width: 80px;"><br />
				</div>
			</div>			
			
			<div class="checkout_details">
				<div>
					<h3 style="margin-top: 5px;">Payment method</h3>
					<?php $checked = (empty($_POST['order_payment_method'])) ? " checked='checked' " : "";?>
					<?php $checked = (!empty($_POST['order_payment_method']) && $_POST['order_payment_method'] == 1 ) ? " checked='checked' " : $checked;?>
					<input class="data-tag" data-tag="checkout-details-payment-method-radio-sagepay" type="radio" name="order_payment_method" id="sagepay" value="1" <?php echo $checked;?> > SagePay <br />
					<?php $checked = (!empty($_POST['order_payment_method']) && $_POST['order_payment_method'] == 2 ) ? " checked='checked' " : "";?>
					<input class="data-tag" data-tag="checkout-details-payment-method-radio-alipay" type="radio" name="order_payment_method" id="alipay" value="2" <?php echo $checked;?> > Alipay <br />
					<?php $checked = (!empty($_POST['order_payment_method']) && $_POST['order_payment_method'] == 3 ) ? " checked='checked' " : "";?>
					<input class="data-tag" data-tag="checkout-details-payment-method-radio-paypal" type="radio" name="order_payment_method" id="paypal" value="3" <?php echo $checked;?> > Paypal <br /><br />
					<img style="width:250px;" src="<?php echo base_url();?>assets/images/credit-cards-color.png" alt="Secure online payments" title="Secure online payments" border="0">
				</div>
			</div>

			<div class="checkout_details">
				<div>
					<h3 style="margin-top: 5px;">Total Payable</h3>
					<p>
						<strong>Sub total:</strong> <?php echo $_SESSION['site']['currency_html'];?><span id="sub_total"><?php echo $total_cost;?></span>
					</p>
					<p>
						<strong>Delivery:</strong> <?php echo $_SESSION['site']['currency_html'];?><span id="delivery_sub_total"><?php echo $delivery_sub_total;?></span>
					</p>
						<p class="checkout_logged_in_option">
							<strong>Store Credit:</strong> <?php echo $_SESSION['site']['currency_html'];?><span id="minus_store_credit"><?php echo $total_cost_of_order = number_format((float) ( set_value('order_store_credit') ), 2, '.', ''); ?></span>
						</p>
					<p>
						<strong>TOTAL:</strong> <?php echo $_SESSION['site']['currency_html'];?><span id="total_cost_of_order"><?php echo $total_cost_of_order = number_format((float) ( $total_cost + $delivery_sub_total ), 2, '.', ''); ?></span>
					</p>
					<p>
						<?php $checked = (!empty($_POST['optin_newsletter'])) ? " checked='checked' " : "";?>
						<input class="data-tag" data-tag="checkout-details-newsletter-checkbox" type="checkbox" name="optin_newsletter" id="optin_newsletter" value="1" <?php echo $checked;?> > Subscribe to our newsletter for Special Offers &amp; News about our latest deals!
					</p>
					<p>
						<?php echo form_error('agree_to_terms'); ?>
						<input class="data-tag" data-tag="checkout-details-terms-and-conditions-checkbox" type="checkbox" name="agree_to_terms" id="agree_to_terms" value="1"> I have read and agree to the <a class="data-tag" data-tag="checkout-details-terms-and-conditions-link" href="#">Terms &amp; Conditions</a> of this sale.<span class="checkout_required_field">*</span>
					</p>
					</div>
			</div>		
			
			<div style="clear:both;"></div>
			<div class="checkout_details" style="border: 0px">
				<input class="update_button checkout_items_button checkout_button data-tag" data-tag="checkout-details-proceed-to-secure-payment-button"  type="submit" name="checkout_details_form_submit" value="Proceed >>">
			</div>
			<div style="clear:both;"></div>
			<?php $order_id = (!empty($_POST['order_id'])) ? $_POST['order_id'] : "";?>
			<input type="hidden" name="order_id" id="order_id" value="<?php echo (!empty($_SESSION['customer']['order_id'])) ? $_SESSION['customer']['order_id'] : $order_id;?>">						
			<input type="hidden" name="order_sub_total_cost" id="order_sub_total_cost" value="<?php echo ( $total_cost ); ?>">			
			<input type="hidden" name="order_total_cost" id="order_total_cost" value="<?php echo ( $total_cost_of_order );?>">
		</form>
	</div>
</div>

<style>
.checkout_logged_in_option {
<?php echo ( ! empty ( $_SESSION['customer']['details'] ) ) ? "display:block;" : "display: none;";?>
}
</style>
<script src="<?php echo base_url("assets/js/google-maps-api.js");?>" async defer></script>		
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1Xoc-hpY7ACgJV5SMcimv-zo7ge0rCNM&libraries=places&callback=initAutocomplete" async defer></script>	