
	<!-- welcome message //-->
	<p>Welcome<?php echo (!empty($_SESSION['customer']['details']['first_name'])) ? " ".$_SESSION['customer']['details']['first_name'] : "";?>, please choose an option below.</p>
	
		<!-- confirm registration email link //-->
	<?php if(empty($_SESSION['customer']['details']['confirmed_email'])) {?>
		<div class="confirm_email_address">
			Please confirm your email address to complete your registration ( See your Welcome Email for more details ).
		</div>
	<?php }?>

	<!-- customer area menu links //-->
	<div class="user_account_left">
		<a class="data-tag" data-tag="user-account-orders-menu-link" href="<?php echo base_url();?>user/orders.html"><h3>Orders</h3></a>	
		<a class="data-tag" data-tag="user-account-wishlist-menu-link" href="<?php echo base_url();?>user/wishlist.html"><h3>Wishlist</h3></a>			
		<a class="data-tag" data-tag="user-account-newsletter-menu-link" href="<?php echo base_url();?>user/newsletter.html"><h3>Newsletter</h3></a>	
		<a class="data-tag" data-tag="user-account-store-credit-menu-link" href="<?php echo base_url();?>user/store_credit.html"><h3>Store Credit</h3></a>			
		<a class="data-tag" data-tag="user-account-personal-menu-link" href="<?php echo base_url();?>user/personal.html"><h3>Personal</h3></a>			
		<a class="data-tag" data-tag="user-account-logout-menu-link" href="<?php echo base_url();?>user/logout"><h3>Logout</h3></a>		
	</div>
	
	<style>
	.confirm_email_address {
		width: 100%;
		height: auto;;
		background-color: #FFFACD;
		border-radius: 10px 10px 10px 10px;
		border-color: 1px solid #FFE4B5;
		padding: 5px;
		text-align: center;
	}
	</style>