<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Account Register Message</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
	<div>
		<div style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header">
			<img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 300px" src="<?php echo base_url();?>/assets/images/skuecommerce.png" alt="" width="300" height="95">
		</div>
		<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">
			Hello <?php echo $billing_first_name;?> <?php echo $billing_last_name;?>,<br /><br />
			Please below your tracking codes for Order ID <strong><?php echo $order_id;?></strong> which you recently made on <?php echo base_url();?><br /><br />
			
				<table id="products_table" class="all_table display">
					<thead>
						<tr>
							<th colspan="2">Order Tracking</th>				
						</tr>
					</thead>
					<tbody>
						<?php 
						for( $x = 0; $x < 10; $x++) { 
							if( ! empty ( $tracking_sku[$x] ) && ! empty ( $tracking_code[$x] ) ) {
							?>
							<tr>
								<td>
									<strong>Product SKU:</strong> <input type="text" name="tracking_sku[]" value="<?php echo (!empty($tracking_sku[$x])) ? $tracking_sku[$x] : "";?>">								 
								</td>
								<td>
										<strong>Tracking Code:</strong>	<input type="text" name="tracking_code[]" value="<?php echo (!empty($tracking_code[$x])) ? $tracking_code[$x] : "";?>">						 
								</td>						
							</tr>	
							<?php
							}
						}
						?>	
					</tbody>
				</table>
		
		</p>
	</div>
</body>
</html>