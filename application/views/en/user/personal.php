<div class="content">
	<h1>Customer Area</h1>
	
	<?php echo $user_menu;?>
	
	<div class="user_account_right">	
		<h2>Personal Details</h2>
		
			<?php if($this->session->flashdata('msg')): ?>
				<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
			<?php endif; ?>		
		
			<span class="checkout_required_field" style="float:right;">* required field</span>
			<form action="<?php echo base_url();?>user/personal.html" method="post" name="customer_personal_form" >	
				<p>	
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
				</p>
				<p>
					<?php echo form_error('email'); ?>
					<?php $email = (!empty($_SESSION['customer']['details']['email'])) ? $_SESSION['customer']['details']['email']  : set_value('email'); ?>
					<label for="email">Email <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="email" id="email" value="<?php echo $email; ?>"><br />
				</p>
				<p>
					To update your password please enter your new password below, otherwise leave both the password fields blank. New password length must be a minimum of 8 characters.
				</p>
				<p>
					<span class="customer_passwords_error"></span>
					<?php echo form_error('password'); ?>
					<label for="password">New Password</label><br />
					<input type="password" name="customer_password" id="customer_password" value=""><br />
					<?php echo form_error('confirm_password'); ?>
					<label for="confirm_password">Confirm New Password</label><br />
					<input type="password" name="confirm_customer_password" id="confirm_customer_password" value=""><br />							
				</p>				
				<p>
					<input class="user_button_update data-tag" data-tag="user-personal-update-details-button"  type="submit" name="submit" value="Update Details";?>
				</p>
			</form>
	</div>
	
</div>

<div style="clear:both;"></div>