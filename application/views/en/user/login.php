<div class="content">
	<h1>Customer Area</h1>
	
		<?php if($this->session->flashdata('msg')): ?>
			<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
		<?php endif; ?>

		<?php if(validation_errors()): ?>
			<p class="checkout_required_field" style="margin-left:10px;">
				<!-- Oops, some required fields are empty! //-->
			</p>
		<?php endif; ?>	
		
		<?php if(!empty($login_error)): ?>
			<p class="checkout_required_field" style="margin-left:10px;">
				<?php echo $login_error;?>
			</p>
		<?php endif; ?>
	
		<form action="<?php echo base_url();?>user/login" method="post" name="customer_login_form" class="customer_login_form">	
			<div class="customer_login_details">
				<span class="checkout_required_field" style="float:right;">* required field</span>
					<div>
						<h3 style="margin-top: 5px;">Please login</h3>
						<span class="login_info"></span>
						<label for="login_email">Email</label> <span class="checkout_required_field">*</span><br />
						<?php echo form_error('login_email'); ?>
						<input type="text" name="login_email" id="login_email" value="<?php echo set_value('login_email'); ?>"><br />
						<label for="login_password">Password</label> <span class="checkout_required_field">*</span><br />
						<?php echo form_error('login_password'); ?>
						<input type="password" name="login_password" id="login_password" value=""><br /><br>		
						<input type="submit" name="submit" value="Login" class="customer_login_button data-tag" data-tag="user-login-button" style="cursor:pointer;"><br /><br />
						<a class="data-tag" data-tag="user-login-forgotten-password" href="<?php echo base_url();?>user/forgot_password.html">Forgotten password?</a> | <a class="data-tag" data-tag="user-login-register-an-account" href="<?php echo base_url();?>user/register.html">Register an account</a>
				</div>
			</div>
		</form>
</div>
