<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->global_model->set_referral_details(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="Cache-control" content="max-age=86400">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo (!empty($description)) ? ucfirst($description) : "SkuEcommerce";?>"/>
<title><?php echo (!empty($title)) ? ucfirst($title) : "";?> | <?php echo (!empty($site_name)) ? ucfirst($site_name) : "";?></title>
<link rel="canonical" href="<?php echo (!empty($products[0]['canonical'])) ? $products[0]['canonical'] : current_url();?>" />
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url();?>assets/images/favicon/apple-icon-57x57.png">
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
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/style.css'); ?>"</script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/style-responsive.css'); ?>"</script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('third_party/lightslider/css/lightslider.css');?>" />   
<link rel="stylesheet" type="text/css" href="<?php echo base_url('third_party/lightgallery/css/lightgallery.css');?>" /> 
<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
<script>
var base_url="<?php echo base_url(); ?>";
</script>
</head>
<body class="body">
<div class="header_notice">
FREE WORLDWIDE DELIVERY ON ALL ORDERS OVER £50
</div>
<div class="header_main">
	<div class="header_logo">
		<a class="data-tag" data-tag="header-logo" href="<?php echo base_url();?>"><img src="<?php echo base_url('/assets/images/skuecommerce.png'); ?>" border="0"  alt="<?php echo (!empty($site_name)) ? ucwords($site_name) : "SkueCommerce";?>" title="<?php echo (!empty($site_name)) ? ucwords($site_name) : "SkueCommerce";?>" ></a>
	</div>
	<div class="header_search">
		<div class="header_login">
			<div class="header_option">
				<a class="button data-tag" data-tag="header-basket-icon"  rel="nofollow" href="<?php echo base_url();?>checkout/basket/view/redirect/<?php echo str_replace("=", "",base64_encode(current_url()));?>">
				<?php $show_items_in_basket = $this->global_model->show_items_in_basket(); ?>
				<img alt="Bag" title="Bag" style="vertical-align: middle;width: 20px;" src="<?php echo base_url('/assets/images/shopping-cart.png'); ?>" border="0"> (<?php echo $show_items_in_basket['total_items'];?>) <?php echo $_SESSION['site']['currency_html'];?><?php echo $show_items_in_basket['total_cost'];?>
				</a>
			</div>
			<div class="header_option">
				<a class="data-tag" data-tag="header-wishlist" href="<?php echo base_url();?>user/wishlist.html" style="color: #4F5155;"><img alt="Wishlist" title="Wishlist"  style="vertical-align: middle;width: 20px;" src="<?php echo base_url('/assets/images/like.png'); ?>" border="0"> Wishlist</a>
			</div>	
			<div class="header_option data-tag" data-tag="header-currecy-drop" >
				<label for="switch_currency" class="hide">Search</label>
				<?php $switch_currency = $this->global_model->switch_currency_html(); ?> 
			</div>			
			<div class="header_option">
				<a class="data-tag" data-tag="header-account" href="<?php echo base_url();?>user" style="color: #4F5155;">Account</a>
			</div>
			<div style="clear:both;"></div>
		</div>	
		<div class="header_search_form">
			<form method="post" action="<?php echo base_url();?>search">
				<label for="keyword_header" class="hide">Search</label>
				<input class="header_search_input" type="text" id="keyword_header" name="keyword" value="" placeholder="Search products...">
                <input class="products_search_button_img data-tag" data-tag="header-search-loop-image-button"  type="image" value="submit" src="<?php echo base_url('/assets/images/search.png'); ?>" alt="Search">
				</form>
		</div>
	</div>
	<div class="clr_float"></div>
</div>
<div class="clr_float"></div>

<div class="mobile_menu">
	<img style="vertical-align: middle;" src="<?php echo base_url();?>assets/images/mobile.png" alt="Menu" title="Menu"> MENU
</div>

<div class="header_menu">
	<?php echo $menu;?>
</div>

<div>
	<?php
	$breadcrumbs = $this->global_model->breadcrumb();
	echo $breadcrumbs;
	?>
</div>