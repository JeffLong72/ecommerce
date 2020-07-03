<div class="content">

	<h1>Thanks for your payment!</h1>
	
	<?php if(!empty($email_sent)) {?>
		<p>
			<strong>
				<?php echo $email_sent;?>
			</strong>
		</p>
		<p>
			We have sent you an email confirming your order details.
		</p>
	<?php }?>
	
</div>