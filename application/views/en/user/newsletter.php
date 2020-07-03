<div class="content">
	<h1>Customer Area</h1>
	<?php echo $user_menu;?>
	
	<div class="user_account_right">
			<h2>Newsletter</h2>
		<form action="<?php echo base_url();?>user/newsletter.html" method="post" name="customer_newsletter_form" >
			<?php if(!empty($newsletters)) {?>
				<span class="checkout_required_field" style="float:right;">* required field</span>
				<p>
					You are currently subscribed with the details below.
				</p>
				<?php foreach($newsletters as $newsletter) {?>
						<p>
							<strong>
								Subscribed Date:
							</strong>							
								<?php echo date("H:ia d/m/Y", strtotime($newsletter['user_confirmed_date']));?>
						</p>				
						<p>
							<?php echo form_error('newsletter_email'); ?>
							<strong>							
								Email
							</strong>				
							<span class="checkout_required_field">*</span><br />
							<input type="text" name="newsletter_email" value="<?php echo $newsletter['user_email'];?>";?>
						</p>	
						<p>
							<input class="data-tag" data-tag="user-newsletter-unsubscribe-checkbox" type="checkbox" name="unsubscribe_newsletter_email" id="unsubscribe_newsletter_email" value="1";?> Unsubscribe me from this mailing list.
						</p>
						<p class="user_account_unsubscribe" style="display:none;">
							Reason for unsubscribing: <br />
							<textarea class="data-tag" data-tag="user-newsletter-reason-textarea" style="width:300px;height:100px;" name="unsubscription_reason" value="";?></textarea>
						</p>						
						<p>
							<input class="user_button_update data-tag" data-tag="user-newsletter-update-details-button" type="submit" name="submit" value="Update Details";?>
						</p>
				<?php }?>							
			<?php } else { ?>
					You are not currently subscribed to any newsletters.
			<?php } ?>
		</form>			
	</div>
</div>
<div style="clear:both;"></div>