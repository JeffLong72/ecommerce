<div class="content">
	<h1>Reset Password</h1>
	
		<?php if($this->session->flashdata('msg')): ?>
			<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
		<?php endif; ?>

		<?php if(validation_errors()): ?>
			<p class="checkout_required_field" style="margin-left:10px;">
				<!-- Oops, some required fields are empty! //-->
			</p>
		<?php endif; ?>	
			
		<form action="<?php echo current_url();?>" method="post" name="customer_forgot_password_form" class="customer_forgot_password_form">	
			<div class="customer_login_details">
					<div>					
					
						<?php if(!empty($result)) {?>

							<p class="checkout_required_field" style="margin-left:10px;">
								<?php if($result == "password_update_success") {?>
									<p>
										Your password has been updated. 
										
											Please continue to <a href="<?php echo base_url();?>user/login.html">customer login<a> area.
									</p>
								<?php } else {?>
									<p>
										We are unable to update your password. Please try again or contact customer services for support.
									</p>				
								<?php }?>
							</p>
							
						<?php } else { ?>

							<p>
								Enter your new password.
							</p>						
						
							<p>
								<span class="customer_passwords_error"></span>
								<?php echo form_error('customer_password'); ?>
								<label for="password">Create New Password <span class="checkout_required_field">*</span></label><br />
								<input type="password" name="customer_password" id="customer_password" value=""><br />		
								<?php echo form_error('confirm_customer_password'); ?>					
								<label for="confirm_customer_password">Confirm New Password <span class="checkout_required_field">*</span></label><br />
								<input type="password" name="confirm_customer_password" id="confirm_customer_password" value=""><br />							
							</p>	
							<p>
								<input class="user_button_update data-tag" data-tag="user-update-password-button"  type="submit" name="submit" value="Update Password";?>
							</p>	

						<?php }?>
						
					</div>
			</div>
		</form>
</div>
