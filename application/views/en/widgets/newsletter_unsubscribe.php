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
						<br />
						<h3>If you have a moment, please let us know why you unsubscribed:</h3>
					    <input type="hidden" name=unsubscription-reason" value="NONE" id="r0"> 
						<label class="radio" for="r1"><input type="radio" name="unsubscription-reason" value="NORMAL" id="r1">I no longer want to receive these emails</label><br/>
						<label class="radio" for="r2"><input type="radio" name="unsubscription-reason" value="NOSIGNUP" id="r2">I never signed up for this mailing list</label><br/>
						<label class="radio" for="r3"><input type="radio" name="unsubscription-reason" value="INAPPROPRIATE" id="r3">The emails are inappropriate</label><br/>
						<label class="radio" for="r4"><input type="radio" name="unsubscription-reason" value="SPAM" id="r4">The emails are spam and should be reported</label><br/>
						
						<br />
						<input type="submit" name="submit" value="Send" class="newsletter_subscribe_form_button data-tag" data-tag="newsletter_subscribe-send-button" style="cursor:pointer;"><br /><br />					
					</div>
			</div>
		</form>
</div>