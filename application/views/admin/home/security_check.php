<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Area</title>
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url();?>images/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url();?>assets/images/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url();?>assets/images/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url();?>assets/images/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url();?>assets/images/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url();?>assets/images/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url();?>assets/images/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url();?>assets/images/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url();?>assets/images/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url();?>assets/images/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url();?>assets/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url();?>assets/images/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>assets/images/favicon/favicon-16x16.png">
<link rel="manifest" href="<?php echo base_url();?>assets/images/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo base_url();?>assets/images/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/admin.css">
<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/js/admin_script.js"/></script>
</head>
<body class="body">

<div class="admin_content">
	<form action="<?php echo base_url();?>admin/security_check" method="post" name="admin_security_check_form" class="admin_security_check_form">
		<div style="margin: 0 auto; margin-top: 100px;border: 1px solid #CCC; width: 298px;text-align:center;background-color:#FFF;padding:5px;border-radius: 15px 15px 15px 15px;">
			<div class="admin_area_login_logo" style="margin-top: 5px;">
				<img src="<?php echo base_url();?>assets/images/mobile-logo.png">
			</div>
			<div class="admin_area_login_details">
				<div>
					<h3 style="margin-top: 5px;">Security Pass Required</h3>

					<?php if($this->session->flashdata('msg')): ?>
						<p class="login_error"><?php echo $this->session->flashdata('msg'); ?></p>
					<?php endif; ?>
					
					<p>Please select 3 characters from your personal security pass, then press confirm.<p>					
					
					<?php
					$value = array();				
					$keys = array_rand(range('0', '6'), 3);
					foreach( $keys as $key ) {
						$value[] = ( $key + 1 );
					}
					function correct_english ($value) {
						switch( $value ) {
							case 1:
								$value = $value."st";
								break;
							case 2:
								$value = $value."nd";
								break;
							case 3:
								$value = $value."rd";
								break;
							default:
								$value = $value."th";
								break;							
						}
						return $value;
					}
					?>						

					<table style="margin:0 auto;">
						<tr>
							<td style="width:60px;">
							<?php echo correct_english($value[0]);?>
							</td>
							<td style="width:60px;">
							<?php echo correct_english($value[1]);?>
							</td>	
							<td style="width:60px;">
							<?php echo correct_english($value[2]);?>
							</td>								
						</tr>
						<tr>
							<td>
								<select name="values[<?php echo $value[0];?>]" style="width:50px;">
								<option value=""></option> 
								<?php 
								foreach (range('a', 'z') as $letter){?>
									<option value="<?php echo $letter;?>"><?php echo $letter;?></option>  
								<?php } ?>
								<?php 
								foreach (range('0', '9') as $num){?>
									<option value="<?php echo $num;?>"><?php echo $num;?></option>  
								<?php } ?>	
								</select>
							</td>
							<td>
								<select name="values[<?php echo $value[1];?>]" style="width:50px;">
								<option value=""></option> 								
								<?php
								foreach (range('a', 'z') as $letter){?>
									<option value="<?php echo $letter;?>"><?php echo $letter;?></option>  
								<?php } ?>
								<?php 
								foreach (range('0', '9') as $num){?>
									<option value="<?php echo $num;?>"><?php echo $num;?></option>  
								<?php } ?>									
								</select>
							</td>
							<td>
								<select name="values[<?php echo $value[2];?>]" style="width:50px;">
								<option value=""></option> 								
								<?php 
								foreach (range('a', 'z') as $letter){?>
									<option value="<?php echo $letter;?>"><?php echo $letter;?></option>  
								<?php } ?>
								<?php 
								foreach (range('0', '9') as $num){?>
									<option value="<?php echo $num;?>"><?php echo $num;?></option>  
								<?php } ?>									
								</select>
							</td>						
						</tr>
					</table>								
					
					<p>&nbsp;<p>
					
					<input type="submit" name="submit" value="Confirm" style="cursor:pointer;" class="admin_login_button"><br><br>
				</div>		
			</div>	
			&copy; SkueCommerce <?php echo date("Y");?>
		</div>
	</form>
</div>

</body>
</html>
