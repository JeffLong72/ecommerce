<div class="content">
	<h1>Customer Area</h1>

	<?php echo $user_menu;?>
	
	<div class="user_account_right">
			<h2>Wishlist</h2>
			<?php if(!empty($wishlist)) {?>
				<?php foreach($wishlist as $product) {?>

					<?php
					$this->load->model('user_model');
					$images = $this->user_model->get_wishlist_images($product['sku']);			
					?>

					<p>
						<img style="width:65px;height:auto;vertical-align: middle;" src="<?php echo base_url();?><?php echo (!empty($images[0]['category'])) ? $images[0]['category'] : "assets/images/10.jpg";?>">
						<strong>
							<?php echo $product['title'];?>
						</strong>
					</p>

					
					<p>
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
							<?php } ?>
						</p>
						
						<p>
							Added to Wishlist: <?php echo date("H:ia d/m/Y", strtotime($product['date_item_added']));?>
						</p>

						<p>
						<a class="user_button_update" href="<?php echo base_url();?><?php echo $product['slug'];?>">View Product Details</a>
						</p>
						
						<hr>
				
				<?php }?>
				
			<?php } else { ?>
			
				<p>
					There are <strong>0</strong> products in your wishlist.
				</p>
				
			<?php }?>
			
	</div>
	
</div>

<div style="clear:both;"></div>