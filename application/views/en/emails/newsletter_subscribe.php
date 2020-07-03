<!DOCTYPE html>
<?php 
$email = urlencode($email);
?>
<html>
<head>
  <meta charset="utf-8" />
  <title>Newsletter Subscription Message</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
	<div>
		<div style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header">
			<img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 300px" src="<?php echo base_url();?>/assets/images/skuecommerce.png" alt="" width="300" height="95">
		</div>
		<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">
			Hello,<br /><br />
			You are receiving this email to confirm your subscription to <?php echo base_url();?><br /><br />
			Please click the following link to continue: <a href="<?php echo base_url().'newsletter-confirm/'.$email.'/'.$token;?>"><?php echo base_url().'newsletter-confirm/'.$email.'/'.$token;?></a><br /><br />
			
			
		</p>
	</div>
</body>
</html>