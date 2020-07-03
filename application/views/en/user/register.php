<div class="content">
	<h1>Register An Account</h1>
	
	<div class="customer_register_details">	
		<span class="checkout_required_field" style="float:right;">* required field</span>
		<h3 style="margin-top: 5px;">To register, simply enter your new account details below.</h3>
		
			<?php if($this->session->flashdata('msg')): ?>
				<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
			<?php endif; ?>		
		
			<form action="<?php echo base_url();?>user/register.html" method="post" name="customer_register_form" >	
			
				<p>
					<?php echo form_error('email'); ?>
					<label for="email">Your Email <span class="checkout_required_field">*</span></label><br />
					<input type="text" name="email" id="email" value="<?php set_value('email'); ?>"><br />	
				</p>
				<p>
					<span class="customer_passwords_error"></span>
					<?php echo form_error('customer_password'); ?>
					<label for="password">Create Password <span class="checkout_required_field">*</span></label><br />
					<input type="password" name="customer_password" id="customer_password" value=""><br />		
					<?php echo form_error('confirm_customer_password'); ?>					
					<label for="confirm_customer_password">Confirm Password <span class="checkout_required_field">*</span></label><br />
					<input type="password" name="confirm_customer_password" id="confirm_customer_password" value=""><br />							
				</p>				
				<p>
					<input class="user_button_update data-tag" data-tag="user-register-my-account-button" type="submit" name="submit" value="Register My Account!";?>
				</p>	
				<p>
					<a class="data-tag" data-tag="user-register-forgotten-password-link" href="<?php echo base_url();?>user/forgot_password.html">Forgotten password?</a> | <a class="data-tag" data-tag="user-register-account-login-link" href="<?php echo base_url();?>user/login.html">Account Login</a>
				</p>
			</form>
	</div>
	
</div>

<div style="clear:both;"></div>
