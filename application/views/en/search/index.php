<div class="content">
	<div class="container">
	
		<!-- product options menu //-->
		<div class="left_33_content">
			FILTERS <a style="float:right;" href="<?php echo base_url().'search';?>">(Reset)</a>
			<hr>
			<div id="filter_dept">
				Department
			</div>
			<div id="filter_dept_content" style="max-height:200px;overflow:auto;">
				<?php echo (!empty($filter['departments'])) ? $filter['departments'] : "";?>
			</div>
			<hr>
			<div id="filter_brands">
				Brands
			</div>
			<div id="filter_brands_content" style="max-height:200px;overflow:auto;margin-top:5px;">
				<?php echo (!empty($filter['brands'])) ? $filter['brands'] : "No results";?>
			</div>	
			<hr>
			<div id="filter_price">
				Price
			</div>
			<div id="filter_costs_content" style="max-height:235px;overflow:auto;margin-top:5px;">
				<?php echo (!empty($filter['cost'])) ? $filter['cost'] : "No results";?>
			</div>	
			<hr>
		</div>

		<!-- content //-->
		<div class="right_70_content">
			
			<h1>Search <?php echo (!empty($products['keyword'])) ? " results for <i>\"<span style='color:#006BB4;'>".$products['keyword']."</span>\"</i>" : "Products";?></h1>
			
			<?php if(!empty($products['products'])) { ?>
			
				<div style="float:left;margin-left: 5px;">
					Total <?php echo count($products['count']);?> products found
				</div>
				
				<br /><br />
				
				<div style="clear:both;"></div>
				
				<div style="float:left;">
					<label for="sort_by">Sort by</label>
					<?php 
					$keyword = (!empty($products['keyword'])) ? $products['keyword'] : "-";
					$dept = $this->uri->segment(5);
					$brand = $this->uri->segment(6);
					$price = $this->uri->segment(7);					
					?>
					
					<select class="sort_by data-tag" data-tag="search-index-product-item-image-<?php echo $products['keyword'];?>" id="sort_by" name="sort_by" style="min-width:150px;">
						<option value="<?php echo $products['sort_by'];?>all/<?php echo urlencode($keyword);?>/-/<?php echo urlencode($dept);?>/<?php echo $brand;?>/<?php echo urlencode($price);?>" <?php if($products['sort_by_selected'] == "all") { echo " selected='selected' ";}?> selected="selected" >All</option>					
						<option value="<?php echo $products['sort_by'];?>newest/<?php echo urlencode($keyword);?>/-/<?php echo urlencode($dept);?>/<?php echo $brand;?>/<?php echo urlencode($price);?>" <?php if($products['sort_by_selected'] == "newest") { echo " selected='selected' ";}?>>Newest</option>					
						<option value="<?php echo $products['sort_by'];?>price-low-high/<?php echo urlencode($keyword);?>/-/<?php echo urlencode($dept);?>/<?php echo $brand;?>/<?php echo urlencode($price);?>" <?php if($products['sort_by_selected'] == "price-low-high") { echo " selected='selected' ";}?>>Price Low - High</option>
						<option value="<?php echo $products['sort_by'];?>price-high-low/<?php echo urlencode($keyword);?>/-/<?php echo urlencode($dept);?>/<?php echo $brand;?>/<?php echo urlencode($price);?>" <?php if($products['sort_by_selected'] == "price-high-low") { echo " selected='selected' ";}?>>Price High - Low</option>
						<option value="<?php echo $products['sort_by'];?>most-popular/<?php echo urlencode($keyword);?>/-/<?php echo urlencode($dept);?>/<?php echo $brand;?>/<?php echo urlencode($price);?>" <?php if($products['sort_by_selected'] == "most-popular") { echo " selected='selected' ";}?>>Most Popular</option>
						<option value="<?php echo $products['sort_by'];?>brand-a-z/<?php echo urlencode($keyword);?>/-/<?php echo urlencode($dept);?>/<?php echo $brand;?>/<?php echo urlencode($price);?>" <?php if($products['sort_by_selected'] == "brand-a-z") { echo " selected='selected' ";}?>>Brand A-Z</option>
						<option value="<?php echo $products['sort_by'];?>brand-z-a/<?php echo urlencode($keyword);?>/-/<?php echo urlencode($dept);?>/<?php echo $brand;?>/<?php echo urlencode($price);?>" <?php if($products['sort_by_selected'] == "brand-z-a") { echo " selected='selected' ";}?>>Brand Z-A</option>
					</select>				
				</div>

			<?php }?>

			<div class="pagination">
				<?php echo $products['pagination'];?>
			</div>				
			
			<div style="clear:both;"></div>
	
			<div class="products">
			
					<?php 
					if(!empty($products['products'])) {
						foreach($products['products'] as $product) {
							// category image
							$category_image = (!empty($product['category'])) ? $product['category'] : '/assets/images/10.jpg';
							// mouseover image
							$this->load->model('search_model');
							$mouseover_image = $this->search_model->get_product_mouseover_image($product['sku']);
							$mouseover_image = (!empty($mouseover_image)) ? $mouseover_image[0]['mouseover'] : '/assets/images/10-hover.jpg';
							?>
							<div class="products_items">
								<div class="products_item_image_container" style="margin-top:5px;height:250px;">
									<a href="<?php echo base_url().$product['slug']; ?>.html">
										 <img class="products_item_image data-tag" data-tag="search-index-product-item-image-<?php echo $product['slug'];?>"
                                              src="<?php echo base_url().$category_image; ?>"
											  onmouseover="this.src='<?php echo base_url().$mouseover_image; ?>';" 
											  onmouseout="this.src='<?php echo base_url().$category_image; ?>';" 
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
											<?php echo $_SESSION['site']['currency_html'];?><?php echo $product_cost;?>
										</span>
										<span class="special_price">
											<?php echo $_SESSION['site']['currency_html'];?><?php echo $product_special_offer_cost;?>
										</span><br />
										<span class="savings">
											<?php
											$price_reduction = number_format((float)($product_cost - $product_special_offer_cost), 2, '.', '');
											$price_reduction_percent = ($price_reduction > 0) ? round( ( $price_reduction / $product_cost ) * 100 ) : 0;
											?>
											You save <?php echo $_SESSION['site']['currency_html'];?><?php echo $price_reduction ;?> <!--(<?php echo $price_reduction_percent;?>&#37;)//-->
										</span>
									<?php } else { ?>
										<span class="cost_price">
											<?php echo $_SESSION['site']['currency_html'];?><?php echo $product_cost;?>
										</span>
										<div>&nbsp;</div>
									<?php } ?>
								</div>
								<div class="products_items_button">
									<a class="button data-tag" data-tag="search-index-add-to-bag-button-<?php echo $products['keyword'];?>" rel="nofollow" href="<?php echo base_url();?>checkout/basket/add/<?php echo $product['id'];?>/redirect/<?php echo str_replace("=", "",base64_encode(current_url()));?>">Add to Bag</a>
								</div>
							</div>
						<?php 
						}
					}
					else { ?>
						<!-- no results //-->
						<div class="no_results" style="min-height: 150px;">
							<p>Sorry, we couldn't find any products for that search term.</p>					
						</div>
					<?php } ?>

				<div style="clear:both;"></div>
			</div>	
			
			<div style="clear:both;"></div>	

			<br /><br />

			<p class="pagination">
				<?php echo $products['pagination'];?>
			</p>	
			
		</div>

	</div>
	<div class="clr_float"></div>	
</div>