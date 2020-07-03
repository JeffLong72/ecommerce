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

		<?php if(!empty($dupes)): ?>
			<p class="checkout_required_field" style="margin-left:10px;">
				<?php echo $dupes;?>
			</p>
		<?php endif; ?>	
		
			
		<form action="<?php echo current_url();?>" method="post" name="newsletter_subscribe_form" class="">	
			<input type="hidden" name="spam_timer" value="<?php echo $spam_timer;?>">
			<div class="">
					<div>
						<label for="contact_email">Your Email</label><br />
						<?php echo form_error('newsletter_email'); ?>
						<input type="text" name="newsletter_email" id="contact_email" value="<?php echo set_value('newsletter_email'); ?>" maxlength="255"><br />	
						<br /><br />
						<input type="submit" name="submit" value="Send" class="newsletter_subscribe_form_button data-tag" data-tag="newsletter_subscribe-send-button" style="cursor:pointer;"><br /><br />					
					</div>
			</div>
		</form>
</div>