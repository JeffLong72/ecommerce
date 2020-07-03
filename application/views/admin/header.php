<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Area</title>
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
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/admin.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('third_party/multiselect/css/multi-select.css');?>">
<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
<script src="<?php echo base_url('third_party/ckeditor/ckeditor.js');?>"/></script>
<script src="<?php echo base_url('third_party/ckfinder/ckfinder.js');?>"/></script>
<script src="<?php echo base_url('third_party/multiselect/js/jquery.multi-select.js');?>"/></script>
<script src="<?php echo base_url('third_party/quicksearch/jquery.quicksearch.js');?>"/></script>
<script src="<?php echo base_url('assets/js/admin_script.js');?>"/></script>
<script>
var base_url="<?php echo base_url(); ?>";
</script>
</head>
<body class="body">

<div class="admin_header hidden-print">
	<p style="float:left;padding-left:10px;">
		<strong>
			<img class="menu_container_toggle" style="vertical-align:middle;margin-right:10px;width:30px;height:auto;cursor:pointer;" src="<?php echo base_url();?>assets/images/mobile.png">
			<span style="font-size: 18px;">SkueCommerce - Admin Area</span>
		</strong>
	</p>
	<p style="float:right;padding-right:10px;">
		<a href="<?php echo base_url();?>admin/logout">
			<img style="width:28px;vertical-align:middle" alt="Logout"" title="Logout" src="<?php echo base_url('assets/images/logout.png');?>">&nbsp;Logout
		</a>
	</p>
	<p style="float:right;padding-right:50px;">
		Site: 
		<select name="site" id="site" style="width:100px;" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
			<option value="<?php echo base_url();?>admin/dashboard/?site=en" <?php if(!empty($_SESSION['admin']['site']) && $_SESSION['admin']['site'] == "en") { echo " selected = 'selected' ";}?>>EN</option>
			<option value="<?php echo base_url();?>admin/dashboard/?site=cn" <?php if(!empty($_SESSION['admin']['site']) && $_SESSION['admin']['site'] == "cn") { echo " selected = 'selected' ";}?>>CN</option>
		</select>
	</p>
	<div style="clear:both;"></div>
</div>

<?php 
$pointer = "&#9657;";
?>

<div class="admin_menu">
	<div style="height: 500px;">
		<div style="text-align:center;">
			<div class="menu_container">
				<h1><a href="<?php echo base_url('admin/dashboard.html');?>">Dashboard</a></h1>
				<div id="dashboard_items">
                    <p><a href="<?php echo base_url('admin/dashboard.html');?>"> <?php echo $pointer?> View dashboard [todo]</a></p>
				</div>				
			</div>
			<div class="menu_container">	
				<h1 id="sales">Sales</h1>
				<div id="sales_items">
                    <p><a href="<?php echo base_url('admin/sales/all.html');?>"> <?php echo $pointer?> View all orders</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="products">Products</h1>
				<div id="products_items">
					<p><a href="<?php echo base_url('admin/products/all.html');?>"> <?php echo $pointer?> View all products</a></p>	
					<p><a href="<?php echo base_url('admin/products/create.html');?>"> <?php echo $pointer?> Add new product</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="cms">CMS Pages</h1>
				<div id="cms_items">
					<p><a href="<?php echo base_url('admin/cms/all.html');?>"> <?php echo $pointer?> View all pages</a></p>	
					<p><a href="<?php echo base_url('admin/cms/create.html');?>"> <?php echo $pointer?> Add new page</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="blogs">Blogs</h1>
				<div id="blogs_items">
					<p><a href="<?php echo base_url('admin/blogs/all.html');?>"> <?php echo $pointer?> View all blogs</a></p>	
					<p><a href="<?php echo base_url('admin/blogs/create.html');?>"> <?php echo $pointer?> Add new blog</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="vouchers">Vouchers</h1>
				<div id="vouchers_items">
                    <p><a href="<?php echo base_url('admin/vouchers/all.html');?>"> <?php echo $pointer?> View all vouchers</a></p>
                    <p><a href="<?php echo base_url('admin/vouchers/create.html');?>"> <?php echo $pointer?> Add new voucher</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="reviews">Reviews</h1>
				<div id="reviews_items">
                    <p><a href="<?php echo base_url('admin/reviews/all.html');?>"> <?php echo $pointer?> View all reviews</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="users">Customers</h1>
				<div id="users_items">
                    <p><a href="<?php echo base_url('admin/users/all.html');?>"> <?php echo $pointer?> View all customers</a></p>
                    <p><a href="<?php echo base_url('admin/users/create.html');?>"> <?php echo $pointer?> Add new customers</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="users">Admins</h1>
				<div id="users_items">
                    <p><a href="<?php echo base_url('admin/administrators/all.html');?>"> <?php echo $pointer?> View all admins</a></p>
                    <p><a href="<?php echo base_url('admin/administrators/create.html');?>"> <?php echo $pointer?> Add new admin</a></p>
				</div>
			</div>			
			<div style="clear:both;"></div>
		</div>
		<div style="text-align:center;">
			<div class="menu_container">	
				<h1 id="import">Import</h1>
				<div id="import_items">
					<p><a href="<?php echo base_url('admin/import/products.html');?>"> <?php echo $pointer?> Import products</a></p>
                    <p><a href="<?php echo base_url('admin/import/stock.html');?>"> <?php echo $pointer?> Stock update  [todo]</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="currency">Currency</h1>
				<div id="currency_items">
                    <p><a href="<?php echo base_url('admin/currency/all.html');?>"> <?php echo $pointer?> View all currencies</a></p>
                    <p><a href="<?php echo base_url('admin/currency/create.html');?>"> <?php echo $pointer?> Add new currencies</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="categories">Categories</h1>
				<div id="categories_items">
                    <p><a href="<?php echo base_url('admin/categories/all.html');?>"> <?php echo $pointer?> View all categories  [todo]</a></p>
                    <p><a href="<?php echo base_url('admin/categories/create.html');?>"> <?php echo $pointer?> Add new category  [todo]</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="menu">Menu Promo</h1>
				<div id="menu_items">
                    <p><a href="<?php echo base_url('admin/menu/all.html');?>"> <?php echo $pointer?> View all promos</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="delivery">Delivery</h1>
				<div id="delivery_items">
                    <p><a href="<?php echo base_url('admin/delivery/all.html');?>"> <?php echo $pointer?> View all deliveries</a></p>
                    <p><a href="<?php echo base_url('admin/delivery/create.html');?>"> <?php echo $pointer?> Add new delivery</a></p>
				</div>
			</div>
			<!--
			<div class="menu_container">	
				<h1 id="payment">Payment</h1>
				<div id="payment_items">
                    <p><a href="<?php echo base_url('admin/payment/edit.html');?>"> <?php echo $pointer?> Edit settings  [todo]</a></p>
				</div>
			</div>
			//-->
			<div class="menu_container">	
				<h1 id="payment">Selling Platforms</h1>
				<div id="payment_items">
                    <p><a href="<?php echo base_url('admin/amazon/index.html');?>"> <?php echo $pointer?> Amazon  [todo]</a></p>
                    <p><a href="<?php echo base_url('admin/ebay/index.html');?>"> <?php echo $pointer?> Ebay  [todo]</a></p>					
				</div>
			</div>			
			<div class="menu_container">	
				<h1 id="affiliates">Affiliates</h1>
				<div id="affiliates_items">
                    <p><a href="#"> <?php echo $pointer?> Product Feeds [todo]</a></p>
				</div>
			</div>			
			<div class="menu_container">	
				<h1 id="sitemap">Sitemap</h1>
				<div id="sitemap_items">
                    <p><a href="<?php echo base_url('admin/sitemap/pages.html');?>"> <?php echo $pointer?> Pages  [todo]</a></p>
                    <p><a href="<?php echo base_url('admin/sitemap/images.html');?>"> <?php echo $pointer?> Images  [todo]</a></p>
                    <p><a href="<?php echo base_url('admin/sitemap/video.html');?>"> <?php echo $pointer?> Video  [todo]</a></p>
                    <p><a href="<?php echo base_url('admin/sitemap/content.html');?>"> <?php echo $pointer?> Content  [todo]</a></p>
				</div>
			</div>
			<div class="menu_container">	
				<h1 id="reports">Reports</h1>
				<div id="reports_items">
					<p><strong>Sales</strong></p>
                    <p><a href="<?php echo base_url('admin/reports/orders.html');?>"> <?php echo $pointer?> Orders  [todo]</a></p>
					<p><a href="<?php echo base_url('admin/reports/vouchers.html');?>"> <?php echo $pointer?> Coupons/Vouchers  [todo]</a></p>
					<p><strong>Products</strong></p>
					<p><a href="<?php echo base_url('admin/reports/best_sellers.html');?>"> <?php echo $pointer?> Best sellers  [todo]</a></p>
					<p><a href="<?php echo base_url('admin/reports/most_viewed.html');?>"> <?php echo $pointer?> Most viewed [todo]</a></p>
					<p><strong>Search</strong></p>
					<p><a href="<?php echo base_url('admin/reports/keywords.html');?>"> <?php echo $pointer?> Keywords </a></p>
                    <p><strong>Actions</strong></p>
					<p><a href="<?php echo base_url('admin/reports/admin_updates.html');?>"> <?php echo $pointer?> Admin updates  [todo]</a></p>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<div class="menu_container_close">
		<div class="menu_container_open_text">
			<a href="javascript:void(0);">Menu</a>
		</div>
	</div>
</div>

<div class="menu_container_open">
	<div class="menu_container_open_text">
		Menu
	</div>
</div>

<style>
<?php $section = $this->uri->segment(2); ?>
#<?php echo $section;?>_items {
	display: block;
}
</style>

<script>
$(document).ready(function(){
  $(".menu_container_close").click(function(){
      //$(".admin_menu").slideUp(500);
	  $(".admin_menu").hide();
  });
  $(".menu_container_open").click(function(){
      //$(".admin_menu").slideDown(1000);
	  $(".admin_menu").show();
  });
  $(".menu_container_toggle").click(function(){
      //$(".admin_menu").slideDown(1000);
	  $(".admin_menu").toggle();
  });  	
});
</script>
