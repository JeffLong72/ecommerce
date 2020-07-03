<div class="content">

		<?php if($this->session->flashdata('msg')): ?>
			<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
		<?php endif; ?>

		<?php if(validation_errors()): ?>
			<p class="checkout_required_field" style="margin-left:10px;">
				<!-- Oops, some required fields are empty! //-->
			</p>
		<?php endif; ?>	
		
		<?php if(!empty($spam)): ?>
			<p class="checkout_required_field" style="margin-left:10px;">
				<?php echo $spam;?>
			</p>
		<?php endif; ?>			
			
		<form action="<?php echo current_url();?>" method="post" name="contact_us_form" class="">	
			<input type="hidden" name="spam_timer" value="<?php echo $spam_timer;?>">
			<div class="">
					<div>
						<label for="contact_name">Your Name</label><br />
						<?php echo form_error('contact_name'); ?>
						<input type="text" name="contact_name" id="contact_name" value="<?php echo set_value('contact_name'); ?>" maxlength="255"><br />
						<label for="contact_email">Your Email</label><br />
						<?php echo form_error('contact_email'); ?>
						<input type="text" name="contact_email" id="contact_email" value="<?php echo set_value('contact_email'); ?>" maxlength="255"><br />	
						<label for="contact_message">Your Message</label><br />						
						<?php echo form_error('contact_message'); ?>						
						<textarea name="contact_message" id="contact_message"><?php echo set_value('contact_message'); ?></textarea>
						<br /><br />
						<input type="submit" name="submit" value="Send" class="contact_us_form_button data-tag" data-tag="contact-us-send-button" style="cursor:pointer;"><br /><br />					
					</div>
			</div>
		</form>
</div>