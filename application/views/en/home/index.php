<div class="content">
	<div class="top_content">
		<div class="top_content_inner">
			<h2>Some Sample Text</h2>
			<h3>Some description text goes here etc etc</h3>
			<a class="products_items_button data-tag" data-tag="TODO" style="font-weight:bold; color: #fff;" href="#">Shop Now</a>
		</div>
	</div>
	<div>
		<div class="left_content">
			<div class="left_content_inner">
				<h2>Title text here</h2>
				<h3>Some description text goes here etc etc</h3>
				<a class="products_items_button data-tag" data-tag="TODO" style="font-weight:bold; color: #fff;" href="#">Shop Now</a>
			</div>
		</div>
		<div class="right_content_top">
			<div class="right_content_top_inner">
				<h2>Title text here</h2>
				<h3>Some description text goes here etc etc</h3>
				<a class="products_items_button data-tag" data-tag="TODO" style="font-weight:bold; color: #fff;" href="#">Shop Now</a>
			</div>
		</div>
		<div class="right_content_bottom">
			<div class="right_content_bottom_inner">
				<h2>Title text here</h2>
				<h3>Some description text goes here etc etc</h3>
				<a class="products_items_button data-tag" data-tag="TODO" style="font-weight:bold; color: #fff;" href="#">Shop Now</a>
			</div>
		</div>
	</div>
	<div class="clr_float"></div>
	<div class="bottom_content">
	</div>
	<div class="hot_sellers">
		<hr>
		<h1>Hot Sellers</h1>
		<h3>Here is what's trending on SkueCommerce right now</h3>
		<hr>
	</div>
			<div class="products" style="margin-left:45px;">
			
					<?php 
					if(!empty($products)) {
						foreach($products as $product) {?>
							<div class="products_items">
								<div class="products_item_image_container" style="margin-top:5px;">
									<a class="data-tag" data-tag="home-index-image-<?php echo $product['slug'];?>" href="<?php echo base_url().$product['slug']; ?>.html">
										 <img class="products_item_image" 
											  src="<?php echo base_url().'/assets/images/10.jpg'; ?>" 
											  onmouseover="this.src='<?php echo base_url().'/assets/images/10-hover.jpg'; ?>';" 
											  onmouseout="this.src='<?php echo base_url().'/assets/images/10.jpg'; ?>';" 
											  alt="<?php echo htmlspecialchars($product['title']);?>" 
											  title="<?php echo htmlspecialchars($product['title']);?>" 
											  border="0">
									</a>
								</div>
								<div class="products_items_title">
									<?php echo $product['title'];?>
								</div>
								<div class="products_items_rate">
									*****		
								</div>								
								<div class="products_items_price">
								
									<?php 
										$product_cost = ( $product['cost'] * $_SESSION['site']['currency_rate'] );
										$product_cost = number_format((float)($product_cost), 2, '.', '');
										$product_special_offer_cost = ( $product['special_offer_cost'] * $_SESSION['site']['currency_rate'] );
										$product_special_offer_cost = number_format((float)($product_special_offer_cost), 2, '.', '');
									?>
											
									<?php if(!empty($product['special_offer_cost']) && $product['special_offer_cost'] > 0 ) { ?>
										<span class="cost_price_small">
											<?php echo $_SESSION['site']['currency_html'];?><?php echo ( $product_cost );?>
										</span>
										<span class="special_price">
											<?php echo $_SESSION['site']['currency_html'];?><?php echo ( $product_special_offer_cost );?>
										</span><br />
										<span class="savings">
											<?php
											$price_reduction = number_format((float)($product_cost - $product_special_offer_cost), 2, '.', '');
											$price_reduction_percent = ($price_reduction > 0) ? round( ( $price_reduction / $product_cost ) * 100 ) : 0;
											?>
											You save <?php echo $_SESSION['site']['currency_html'];?><?php echo $price_reduction ;?> (<?php echo $price_reduction_percent;?>&#37;)
										</span>
									<?php } else { ?>
										<span class="cost_price">
											<?php echo $_SESSION['site']['currency_html'];?><?php echo $product_cost;?>
										</span>
										<div>&nbsp;</div>
									<?php } ?>
								</div>
								<div class="products_items_button">
									<a class="button data-tag" data-tag="home-index-add-to-bag-button-<?php echo $product['slug'];?>" rel="nofollow" href="<?php echo base_url();?>checkout/basket/add/<?php echo $product['id'];?>/redirect/<?php echo str_replace("=", "",base64_encode(current_url()));?>">Add to Bag</a>
								</div>
							</div>
						<?php 
						}
					}
					?>

				<div style="clear:both;"></div>
			</div>	
</div>