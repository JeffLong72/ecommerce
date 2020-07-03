<div class="content">
	<h1>Forgot Password</h1>
	
		<?php if($this->session->flashdata('msg')): ?>
			<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
		<?php endif; ?>

		<?php if(validation_errors()): ?>
			<p class="checkout_required_field" style="margin-left:10px;">
				<!-- Oops, some required fields are empty! //-->
			</p>
		<?php endif; ?>	
			
		<form action="<?php echo base_url();?>user/forgot_password.html" method="post" name="customer_forgot_password_form" class="customer_forgot_password_form">	
			<div class="customer_login_details">
					<div>					
						<p>
							Enter your account email address below and we will send you instructions to reset your password.
						</p>

						<?php if(!empty($result)):?>
							<p class="checkout_required_field" style="margin-left:10px;">
								<?php if($result=="sent") {?>
									<p>
										Please check your mail box we have sent you an email.
									</p>
								<?php } else {?>
									<p>
										Sorry, we could not find a customer account with that email address.
									</p>				
								<?php }?>
							</p>
						<?php endif; ?>		
		
						<?php echo form_error('email'); ?>
						<label for="email">Email Address</label> <span class="checkout_required_field">*</span><br />
						<input type="text" name="email" id="email" value=""><br /><br>		
						<input type="submit" name="submit" value="Resend Password" class="user_button_update data-tag"  data-tag="user-forgot-password-resend-password-button" data-tag="login-button" style="cursor:pointer;"><br /><br />
					</div>
			</div>
		</form>
</div>
