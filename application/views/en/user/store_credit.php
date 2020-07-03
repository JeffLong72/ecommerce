<div class="content">
	<h1>Customer Area</h1>
	<?php echo $user_menu;?>
	
	<div class="user_account_right">
			<h2>Store Credit</h2>
			<?php
				$store_credit = ( $user['store_credit'] * $_SESSION['site']['currency_rate'] );
				$store_credit = number_format((float)($store_credit), 2, '.', '');
			?>
			<p>
				You currently have <strong><?php echo $_SESSION['site']['currency_html'];?><?php echo $store_credit;?></strong> store credit available.
			</p>
	</div>
</div>
<div style="clear:both;"></div>