<div class="content">

	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>
			
	<?php 
		if(!empty($products)){
			foreach ($products as $product)
			{
				?>					
							
				<div class="product_image" style="overflow:hidden;">
					<ul id="imageGallery">
						<?php 
						if(!empty($product['product_images'])) {
							// product category image							
							foreach($product['product_images'] as $product_images) {
								if(!empty($product_images['category'])){ // category
									?>
								  <li data-thumb="<?php echo base_url().$product_images['category'];?>" data-src="<?php echo base_url().$product_images['category'];?>"  class="main-product-image">
									<img alt="<?php echo $product['title'];?>" src="<?php echo base_url().$product_images['category'];?>" style="height:365px;width:auto;" />
								  </li>
								<?php 
								}
								if(!empty($product_images['mouseover'])){ // hover
									?>
								  <li data-thumb="<?php echo base_url().$product_images['mouseover'];?>" data-src="<?php echo base_url().$product_images['mouseover'];?>"  class="main-product-image">
									<img alt="<?php echo $product['title'];?>" src="<?php echo base_url().$product_images['mouseover'];?>" style="height:365px;width:auto;" />
								  </li>
								<?php 
								}								
							}
							// product other  images
							foreach($product['product_images'] as $product_images) {
								if(!empty($product_images['image'])){ // other
									?>
								  <li data-thumb="<?php echo base_url().$product_images['thumbnail'];?>" data-src="<?php echo base_url().$product_images['image'];?>"  class="main-product-image">
									<img alt="<?php echo $product['title'];?>" src="<?php echo base_url().$product_images['image'];?>" style="height:365px;width:auto;" />
								  </li>
								<?php 
								}
							}
						}
						?>						
					</ul>
				</div>

				<script>
				var  one_color = '<li data-thumb="<?php echo base_url('uploads/products/thumbs/1.jpg');?>" data-src="<?php echo base_url('uploads/products/1.jpg');?>"  class="main-product-image">';
						one_color+= '<img alt="<?php echo $product['title'];?>" src="<?php echo base_url('uploads/products/1.jpg');?>" style="height:365px;width:auto;" />';
						one_color+= '</li>';
						one_color+= '<li data-thumb="<?php echo base_url('uploads/products/thumbs/2.jpg');?>" data-src="<?php echo base_url('uploads/products/2.jpg');?>"  class="main-product-image">';
						one_color+= '<img alt="<?php echo $product['title'];?>" src="<?php echo base_url('uploads/products/2.jpg');?>" style="height:365px;width:auto;" />';
						one_color+= '</li>';				
						
				var  two_color = '<li data-thumb="<?php echo base_url('assets/images/bart.png');?>" data-src="<?php echo base_url('assets/images/bart.png');?>"  class="main-product-image">';
						two_color+= '<img alt="<?php echo $product['title'];?>" src="<?php echo base_url('assets/images/bart.png');?>" style="height:365px;width:auto;" />';
						two_color+= '</li>';
						two_color+= '<li data-thumb="<?php echo base_url('assets/images/bart-hover.png');?>" data-src="<?php echo base_url('assets/images/bart-hover.png');?>"  class="main-product-image">';
						two_color+= '<img alt="<?php echo $product['title'];?>" src="<?php echo base_url('assets/images/bart-hover.png');?>" style="height:365px;width:auto;" />';
						two_color+= '</li>';						
				</script>

				<div class="product_details">
					<h1 style="margin-bottom: 0px;"><span class="item_title"><?php echo $product['title'];?></span></h1>
					
					<?php 
					if(empty($reviews)) { ?>
						<p class="add_new_review data-tag"  data-tag="product-index-review-link"><a href="<?php echo current_url();?>">Be the first to review this product</a></p>
					<?php 
					} 
					else
					{
						// set review rating total
						$rating_total = 0;
						// count total reviews
						$all_reviews = count($reviews);
						// for each review add to rating total
						foreach($reviews as $review) {
							$rating_total += $review['rating'];
						}
						// calculate avg rating as percentage 
						// ( round up to next whole number )
						$avg_rating = ceil ( $rating_total / $all_reviews );
						// loop for maximum stars (5) we want to display
						for($i = 1; $i <= 5; $i++ ) { 
								// assign css depending on avg rating
								$rating_class = ( $i <= $avg_rating ) ? "star_yellow" : "star_grey";
								// display stars
								echo '<div class="'.$rating_class.'">&#9733;</div>';
						} 
						?>
						<br />
						<div style="float:left;" class="add_new_review">
							<a href="<?php echo current_url();?>" style="float:left;text-decoration: none;" ><?php echo $all_reviews;?> customer reviews</a>
						</div>
						
						<div style="clear:both;"></div>
					<?php 	} ?>

					<p>
					
						<?php 
							$product_cost = ( $product['cost'] * $_SESSION['site']['currency_rate'] );
							$product_cost = number_format((float)($product_cost), 2, '.', '');
							$product_special_offer_cost = ( $product['special_offer_cost'] * $_SESSION['site']['currency_rate'] );
							$product_special_offer_cost = number_format((float)($product_special_offer_cost), 2, '.', '');
						?>					
					
						<?php if( isset( $product['special_offer_cost'] ) && $product['special_offer_cost'] != "0.00") {?>
							<span class="product_prev_price"><?php echo $_SESSION['site']['currency_html'];?><?php echo $product_cost;?></span>
							<span class="product_price"><?php echo $_SESSION['site']['currency_html'];?><?php echo $product_special_offer_cost;?></span>
						<?php } else { ?>
							<span class="product_price"><?php echo $_SESSION['site']['currency_html'];?><span class="item_price"><?php echo $product_cost;?></span></span>						
						<?php }?>
						
					</p>
					<p class="product_status"><?php echo (!empty($product['stock'])) ? "In Stock" : "<span style='color:red;'>Sorry, this item is out of stock!</span>";?></p>
					
						<div class="item_back_in_stock_notification out_of_stock">
						<p style="margin-top: 5px;">Enter your email address below to be notified when this product is back in stock.</p>
						<span class="back_in_stock_notification_result"></span>
						<label for="back_in_of_stock_notification_email" class="hide">Email</label>
						Email: <input type="text" name="back_in_of_stock_notification_email" id="back_in_of_stock_notification_email" value="" style="height: 30px; padding: 5px;" placeholder="your@email.com"> <a class="back_in_stock_notification_button" href="<?php echo current_url();?>">Notify Me!</a>
						</div>

					<?php if(!empty($product['dispatch_time'])) {?>
						<p class="product_dispatch_time">Dispatch time: <?php echo $product['dispatch_time'];?></p>
					<?php }?>
			
					<hr>

					<?php
					// attributes
					$attribute_id = 0;
					if(!empty($product['product_attribute'])) { ?>	
					<?php 
					// get all product attributes from config
					$pa = $this->config->item('product_attributes');
					?>
					<p><strong>Choose <?php echo $pa['all'][$product['product_attribute']];?></strong></p>
					<div class="item_details related_product_attribute_details item_details_selected" data-sku="<?php echo $product['sku'];?>" id="<?php echo $product['id'];?>">
						<?php echo $product['product_attribute_option'];?>
					</div>		
					<?php } ?>
					<?php	
					if(!empty($related_attributes[0]['product_attribute'])) { ?>			
						<?php foreach($related_attributes as $related_attribute) {
							$attribute_id ++; ?>
							<div class="item_details related_product_attribute_details" data-sku="<?php echo $related_attribute['sku'];?>" id="<?php echo $related_attribute['id'];?>">
								<?php echo $related_attribute['product_attribute_option'];?>
							</div>
						<?php } ?>
						<div class="clr_float;"></div>	
					<?php } ?>

					<div class="clr_float;"></div>	
					
					<div class="qty_and_purchase">
						<br /><br />
						<p><strong>QTY</strong></p>
						<label for="product_qty" class="hide">Quantity</label>
						<p><input type="text" name="product_qty" id="product_qty" value="1" class="product_qty"></p>
						<br />
						
						<?php if($product['stock']) {?>
							<a class="products_items_button product_item_page_add_to_cart data-tag" data-tag="products-index-add-to-cart-button-<?php echo $product['slug'];?>" rel="nofollow" href="#">Add to Cart</a>
						<?php } else {?>
							<div class="products_items_button product_item_page_add_to_cart product_purchase_disabled_button">Product Out Of Stock</div>
						<?php } ?>
						<a class="add_to_wishlist_button data-tag" data-tag="products-index-add-to-wishlist-button-<?php echo $product['slug'];?>" rel="nofollow" href="#"><img style="vertical-align: middle;margin-right:10px;" src="<?php echo base_url();?>assets/images/like.png">Add to Wishlist</a>
						<div class="clr_float"></div>
						<span class="wishlist_error"></span>
						</div>
				</div>
				<div class="clr_float"></div>
				
				<div class="tab-container">
					<ul class="tabs">
						<li class="tab-link current data-tag" data-tag="products-index-description-tab-<?php echo $product['slug'];?>" data-tab="tab-1" id="tab-1-tab">Description</li>
						<li class="tab-link current data-tag" data-tag="products-index-specifications-tab-<?php echo $product['slug'];?>" data-tab="tab-2" id="tab-2-tab">Specifications</li>
						<li class="tab-link current data-tag" data-tag="products-index-shipping-tab-<?php echo $product['slug'];?>" data-tab="tab-4" id="tab-4-tab">Shipping</li>
						<li class="tab-link current data-tag" data-tag="products-index-reviews-tab-<?php echo $product['slug'];?>" data-tab="tab-3" id="tab-3-tab">Reviews</li>
					</ul>
					<div id="tab-1" class="tab-content current">
						<?php 
						if( ! empty($product['short_description']) ) {
							echo  "<p>".$product['short_description']."</p>";
						} 
						?>
						<?php 
						if( ! empty($product['text']) ) {
							echo  "<p>".$product['text']."</p>";
						} 
						?>
						<p class="product_sku">Product SKU: <?php echo $product['sku'];?></p>
					</div>
					<div id="tab-2" class="tab-content">
						Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div>
					<div id="tab-4" class="tab-content">
						<b>Shipping information</b> - Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div>					
					<div id="tab-3" class="tab-content">
						<!-- add review //-->
						<div id="add-review">
						
							<?php if(empty($reviews)) { ?>
								<p>
									<strong>
										There are no reviews for this product yet. Be the first person to leave a review!
									</strong>
								</p>
							<?php } ?>
						
								<p class="reviews_required_field" style="float:right;margin-left:10px;">* required field</p>
								
								<h3 style="margin-top: 5px; margin-bottom: 0px;">Add Your Review</h3>
								
								<span class="review_result"></span>
								
								<p style="padding-bottom: 0px; margin-bottom: 0px;">Please chose a rating<span class="reviews_required_field">*</span></p>
								
								<div class="stars">
									<input type="radio" id="r1" name="rg1" value="5">
									<label for="r1"></label>
									<input type="radio" id="r2" name="rg1" value="4">
									<label for="r2"></label>
									<input type="radio" id="r3" name="rg1" value="3">
									<label for="r3"></label>
									 <input type="radio" id="r4" name="rg1" value="2">
									<label for="r4"></label>
									<input type="radio" id="r5" name="rg1" value="1">
									<label for="r5"></label>
								</div>	
								
								<div style="clear:both;"></div>	
				
								<label for="review_name">Your Name<span class="reviews_required_field">*</span></label><br />
								<input type="text" name="review_name" id="review_name" value="" style="min-width: 250px; padding:5px;margin-bottom: 8px;" placeholder="John Smith" maxlength="255"><br />
								<label for="review_headline">Review Headline<span class="reviews_required_field">*</span></label><br />
								<input type="text" name="review_headline" id="review_headline" value="" style="min-width: 250px; padding:5px;margin-bottom: 8px;" placeholder="I would buy this product again." maxlength="255"><br />
								<label for="review_comments">Review Comments<span class="reviews_required_field">*</span></label><br />
								<textarea name="review_comments" id="review_comments" style="width: 300px;height:100px;padding:5px;" placeholder="How you use the product. Things that are great about it. Things that are not great about it."></textarea><br /><br />
								<!-- submit form review details by ajax //-->
								<input type="hidden" name="review_sku" id="review_sku" value="<?php echo $product['sku'];?>">								
								<input type="hidden" name="spam_timer" id="spam_timer" value="<?php echo date("U");?>">
								<a class="review_submit_button current data-tag" data-tag="products-index-add-review-button-<?php echo $product['slug'];?>" href="<?php echo current_url();?>">Add review</a><br /><br />
						</div>
						<!-- all reviews //-->
						<div id="all-reviews">
						<?php
							if(!empty($reviews)){
								foreach ($reviews as $review) { 
								?>
									<p>
										<hr>									
										<?php for($i = 0; $i < (int)$review['rating']; $i++ ){ ?>
											<div class="review_star_rating">&#9733;</div>
										<?php } ?>
										<br />&nbsp;
										<strong><?php echo $review['headline'];?></strong><br /><br />	
										<?php echo $review['comments'];?><br /><br />	
										<?php echo $review['name'];?>, <?php echo date("d M Y", strtotime($review['date_submitted']));?>
										<br /><br />							
										<div style="clear:both;"></div>									
									</p>	
								<?php 
								}
							}
							?>
						</div>
						
					</div>
				</div>

				<?php
			}
		}
	?>
	<input type="hidden" name="product_id" id="product_id" value="<?php echo $product['id'];?>">
	<input type="hidden" name="product_sku" id="product_sku" value="<?php echo $product['sku'];?>">	
</div>

<div id="overlay" onclick="off()">
	<div style="width:100%;min-height:30px;background-color:#f5f5f5;">
		<div style="float:right;padding:4px;">[close]</div>
	</div>
	<div id="inner-data"></div>
</div>

<style>
.out_of_stock {
	display:<?php echo (empty($product['stock'])) ? " block " : "none";?>;
}
.main-product-image {
	background-color: #fff !important;
}
#overlay {
    position: fixed;
    display: none;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 2;
    cursor: pointer;
	background-color: #ffffff;	
}

#inner-data{
    position: absolute;
    top: 50%;
    left: 50%;
    font-size: 50px;
    color: white;
    transform: translate(-50%,-50%);
    -ms-transform: translate(-50%,-50%);
}
</style>

<script>
var basket_url = "<?php echo base_url();?>checkout/basket/add/";
var product_id = "<?php echo $product['id'];?>";
var product_redirect = "/redirect/<?php echo str_replace("=", "",base64_encode(current_url()));?>";
function off() {
    document.getElementById("overlay").style.display = "none";
}
</script>
