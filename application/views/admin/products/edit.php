<div class="admin_content">
	<h1>Edit product</h1>
	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>	
	<hr>
	<span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>
	<?php echo form_open_multipart('admin/products/edit/'.$product['id']); ?>
		<input type="hidden" name="id" value="<?php echo $product['id'];?>">
		<input type="hidden" name="original_sku" value="<?php echo $product['sku'];?>">
		<div class="tab-container">
			<ul class="tabs">
				<li class="tab-link current" data-tab="tab-1">Product Page</li>
				<li class="tab-link" data-tab="tab-2">Product Information</li>
				<li class="tab-link" data-tab="tab-3">Product Cost</li>
				<li class="tab-link" data-tab="tab-4">Product Images</li>
				<li class="tab-link" data-tab="tab-5">Product Stock</li>
				<li class="tab-link" data-tab="tab-6">Product Delivery</li>
				<li class="tab-link" data-tab="tab-7">Product Categories</li>				
				<li class="tab-link" data-tab="tab-8">Product ( Related Attributes )</li>				
			</ul>
			<div id="tab-1" class="tab-content current">
				<h2>Product Page</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>
				<p>
					<label for="sku">Sku:<span class="required">*</span></label>
					<input type="input" name="sku" id="sku" maxlength="255" value="<?php echo $product['sku'];?>" /><br />
				</p>
				<p>
					<label for="title">Name:<span class="required">*</span></label>
					<input type="input" name="title" id="title" maxlength="255" value="<?php echo $product['title'];?>" /><br />
				</p>
				<p>
					<label for="slug">Url:<span class="required">*</span></label>
					<input type="input" name="slug" id="slug" maxlength="255" value="<?php echo $product['slug'];?>" /><br />
				</p>
				<p>
					<label for="short_description">Short description:</label><br /><br />
					<textarea id="short_description" name="short_description"><?php echo $product['short_description'];?></textarea><br />
				</p>
				<p>
					<label for="text">Full description:</label><br /><br />
					<textarea id="text" name="text"><?php echo $product['text'];?></textarea><br />
				</p>
				<hr>				
				<h2>SEO Details</h2>
				<p>
					<label for="meta_title">Meta title (70 chars):</label>
					<input type="text" name="meta_title" id="meta_title" value="<?php echo (!empty($product['meta_title'])) ? $product['meta_title'] : "";?>" maxlength="70"/><br />
				</p>				
				<p>
					<label for="meta_description">Meta description (160 chars):</label>
					<textarea name="meta_description" id="meta_description" maxlength="160"><?php echo $product['meta_description'];?></textarea><br />
				</p>
				<p>
				This is what your page would look like in the Search Engine results,
				</p>
				<div class="search_engine">
					<div class="search_engine_title"><span id="seo_title"><?php echo !empty($product['title']) ? $product['title'] : "Product name";?></span></div>
					<div class="search_engine_url"><?php echo base_url();?><span id="seo_url"><?php echo !empty($product['slug']) ? $product['slug'] : "product-url";?>.html</span></div>
					<div class="search_engine_desc"><span id="seo_text"><?php echo !empty($product['meta_description']) ? $product['meta_description']." ..." : "The text you enter into the Meta description field ...";?></span></div>			
				</div>
				<p>
					<label for="redirect">301 Redirect:</label>
					<input type="text" name="redirect" id="redirect" placeholder="https://" value="<?php echo (!empty($product['redirect'])) ? $product['redirect'] : "";?>" /><br />
				</p>
				<p>
					<label for="canonical">Canonical:</label>
					<input type="text" name="canonical" id="canonical" placeholder="https://" value="<?php echo (!empty($product['canonical'])) ? $product['canonical'] : "";?>" /><br />
				</p>
				<hr>										
				<h2>Product Featured</h2>
				<p>
					<label for="featured">Featured:</label>
					<select name="featured">
						<option value="0" <?php if(isset($product['featured']) && $product['featured'] == 0 ) { echo " selected='selected' "; }?>>No</option>
						<option value="1" <?php if(isset($product['featured']) && $product['featured'] == 1 ) { echo " selected='selected' "; }?>>Yes</option>
					</select>	
					<br />
				</p>
				<hr>					
				<h2>Restrict Direct Access</h2>
				<p>
					If enabled this product will not be directly accessible by menu, categories or site search. <br />
					If this product is associated by attribute value to a parent product the recommended setting is Yes.<br><br>
					<label for="restrict_direct_access">Enabled:</label>
					<select name="restrict_direct_access">
						<option value="0" <?php if(isset($product['restrict_direct_access']) && $product['restrict_direct_access'] == 0 ) { echo " selected='selected' "; }?>>No</option>
						<option value="1" <?php if(isset($product['restrict_direct_access']) && $product['restrict_direct_access'] == 1 ) { echo " selected='selected' "; }?>>Yes</option>
					</select>						
				</p>					
				<hr>				
				<h2>Product Status</h2>
				<p>
					<label for="active">Status:</label>
					<select name="active">
						<option value="0" <?php if(isset($product['active']) && $product['active'] == 0 ) { echo " selected='selected' "; }?>>Inactive</option>
						<option value="1" <?php if(isset($product['active']) && $product['active'] == 1 ) { echo " selected='selected' "; }?>>Active</option>
					</select>	
					<br />
				</p>
			</div>		
			<div id="tab-2" class="tab-content">
				<h2>Product Information</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>

				<p>
					<label for="brand">Brand:</label>
					<input type="input" name="brand" id="brand" value="<?php echo (!empty($product['brand'])) ? $product['brand'] : "";?>" /><br />
				</p>				
				<p>
					<label for="dimensions">Dimensions:</label>
					<input type="input" name="dimensions" id="dimensions" value="<?php echo (!empty($product['dimensions'])) ? $product['dimensions'] : "";?>" /><br />
				</p>				
				<p>
					<label for="net_weight">Net weight:</label>
					<input type="input" name="net_weight" id="net_weight" value="<?php echo (!empty($product['net_weight'])) ? $product['net_weight'] : "";?>" /><br />
				</p>				
				<p>
					<label for="prod_weight">Product weight:</label>
					<input type="input" name="prod_weight" id="prod_weight" value="<?php echo (!empty($product['prod_weight'])) ? $product['prod_weight'] : "";?>" /><br />
				</p>
				
				<br /><br />
				
				<h2>Product Attribute</h2>
				<input type="hidden" name="product_attribute_value" id="product_attribute_value" value="<?php echo $product['product_attribute_option'];?>">
				<?php for( $x = 0; $x <= 0; $x++) {?>					
					<p>
						<label for="related_products_attributes_<?php echo $x;?>">Attribute:</label>
						<select name="related_products_attributes_<?php echo $x;?>" class="related_products_attributes" data-select="<?php echo $x;?>">
								<option value="">-- please select --</option>
							<?php foreach($product_attributes['all'] as $key=>$value) {?>
								<?php $selected = ($key == $product['product_attribute']) ? " selected='selected' " : "";?>
								<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
							<?php } ?>
						</select>
					</p>
					<p>
						<label for="related_value_<?php echo $x;?>">Value:</label>	
						<select name="related_value_<?php echo $x;?>" id="related_value_<?php echo $x;?>" class="related_value">
							<!-- populated by ajax: admin_script.js //-->
						</select>	
					</p>					
				<?php }?>				
				
				<br /><br />
				
				<?php /*
				<h2>Imported Values ( we might not need these any more )</h2>
				<p>
					<label for="color">Color:</label>
					<input type="color" name="color" id="color" value="<?php echo (!empty($product['color'])) ? $product['color'] : "#000000";?>"  style="width: 40px;height: 30px;padding: 0px;cursor: pointer;" /><br />
				<p>
					<label for="size">Size:</label>
					<input type="input" name="size" id="size" value="<?php echo (!empty($product['size'])) ? $product['size'] : "";?>" /><br />
				</p>
				*/ ?>
				
			</div>
			<div id="tab-3" class="tab-content">
				<h2>Product Cost</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>
				<p>
					<label for="base_cost">Base cost:<span class="required">*</span></label>
					<input type="input" name="base_cost" id="base_cost" placeholder="0.00" value="<?php echo (!empty($product['base_cost'])) ? $product['base_cost'] : "";?>" /><br />
				</p>
				<p>
					<label for="cost">Sell cost:<span class="required">*</span></label>
					<input type="input" name="cost" id="cost" placeholder="0.00" value="<?php echo (!empty($product['cost'])) ? $product['cost'] : "";?>" /><br />
				</p>
				<p>
					<label for="special_offer_cost">Special cost:</label>
					<input type="input" name="special_offer_cost" id="special_offer_cost" placeholder="0.00" value="<?php echo (!empty($product['special_offer_cost'])) ? $product['special_offer_cost'] : "";?>" /><br />
				</p>				
				<p>
					<label for="vat_rate">Vat rate:<span class="required">*</span></label>
					<select name="vat_rate" id="vat_rate">
						<?php 
						if(!empty($product['vat_rates'])){
							foreach($product['vat_rates'] as $vat_rate) {
								$selected = ($vat_rate['id'] == $product['vat_rate']) ? " selected = 'selected' ": "";
							?>
								<option value="<?php echo $vat_rate['vat_rate'];?>" <?php echo $selected;?>><?php echo $vat_rate['vat_rate'];?></option>
							<?php	
							}
						}
						?>
					</select><br />
				</p>
				<h2>Optional Extras</h2>				
				<p>
				If you would like the <strong>Special cost</strong> to end on a specific date, enter the date below<br /><br />
					<label for="special_offer_expires">Special cost expiry date:</label>
					<input type="input" name="special_offer_expires" id="special_offer_expires" placeholder="e.g. 06/11/<?php echo date("Y");?>" value="<?php echo (!empty($product['special_offer_expires'])) ? date("d/m/Y", strtotime($product['special_offer_expires'])) : "";?>" /> 
					
				</p>				
			</div>
			<div id="tab-4" class="tab-content">
				<h2>Product Images</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>
				<p>
					<label for="text">Image:</label>
					<input type="file" name="image" size="20" /><br />
				</p>
				<p>				
					<strong style="margin-right: 50px;">
						Alt: &nbsp;
					</strong>
					<input type="text" name="product_image_alt" value="">
				</p>
				<p>				
					<strong style="margin-right: 40px;">
						Title: &nbsp;
					</strong>
					<input type="text" name="product_image_title" value="">
				</p>
				<p>
					<strong style="margin-right: 3px;">
						Select type:
					</strong>
					<select name="product_image_type" id="product_image_type" style="width:185px;">
						<option value="category">Category & Main Image</option>	
						<option value="hover">Hover Image</option>								
						<option value="main">Other Image</option>	
					</select>
				</p>
				<br /><br />
				<table class="all_table display">
				<thead>
				<tr>
					<th style="width:80px;text-align:center;">
						Type
					</th>
					<th style="width:180px;text-align:center;">
						Image
					</th>
					<th>
						Alt
					</th>
					<th>
						Title
					</th>					
					<th style="width:150px;text-align:center;">
						Date Added
					</th>
					<th style="width:80px;text-align:center;">
						Active
					</th>					
					<th style="width:80px;text-align:center;">
						Edit
					</th>
					<th style="width:80px;text-align:center;">
						Delete
					</th>					
				</tr>
				<thead>
				<tbody>
					<?php 
					if(!empty($product['images'])) {
					$x = 0;
					foreach($product['images'] as $image) {
							$style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
							$image_type = (!empty($image['mouseover'])) ? "Hover" : "Other";	
							$image_type = (!empty($image['category'])) ? "Category<br />&amp; Main" : $image_type;								
						?>
						<tr style="<?php echo $style_row;?>">
							<td style="width:80px;text-align:center;">
								<strong>
									<?php echo $image_type;?>
								</strong>
							</td>
							<td style="width:180px;text-align:center;">
								<?php 
								$img = (!empty($image['mouseover'])) ? base_url().$image['mouseover'] : base_url().$image['thumbnail'];
								$img = (!empty($image['category'])) ? base_url().$image['category'] : $img;
								?>
								<img src="<?php echo $img;?>" style="max-width:150px;height:auto;">
							</td>	
							<td>
								<?php echo (!empty($image['alt'])) ? $image['alt'] : "";?>
							</td>
							<td>
								<?php echo (!empty($image['title'])) ? $image['title'] : "";?>
							</td>							
							<td style="width:150px;text-align:center;">
								<?php echo date("H:i:s d/m/Y", strtotime($image['date_added']));?>
							</td>	
							<td style="width:80px;text-align:center;">
                                <span style="display:none;"><?php echo $image['active'];?></span>
                                <?php $active = (!empty($image['active']) && $image['active'] == 1) ? "active" : "inactive";?>
                                <img src="<?php echo base_url('assets/images/'.$active.'.png');?>">
							</td>							
							<td style="width:80px;text-align:center;">
                                <a href="<?php echo base_url('admin/products/edit_image_details/'.$image['id']);?>">
                                    <img src="<?php echo base_url('assets/images/edit.png');?>" border="0">
                                </a>
							</td>
                            <td style="width:80px;text-align:center;">
                                <img src="<?php echo base_url('assets/images/delete.png');?>">
                            </td>
						</tr>					
					<?php
					$x++;  
					}?>
				<?php 
				}else {
				?>
				<tr>
					<td style="" colspan="8">
						<p>No images exist for this product. Upload an image using the options above.</p>
					</td>
				</tr>
				<?php }?>
				</tbody>
				</table>
			</div>
			<div id="tab-5" class="tab-content">
				<h2>Product Stock</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>
				<p>
					<label for="stock">Available Stock:</label>
					<input type="hidden" name="original_stock" id="original_stock" value="<?php echo $product['stock'];?>" />
					<input type="text" name="stock" id="stock" value="<?php echo $product['stock'];?>" /><br />
				</p>
				<p>
					<label for="stock_on_hold">Stock on hold:</label>
					<input type="text" name="stock_on_hold" id="stock_on_hold" value="<?php echo $product['stock_on_hold'];?>" /> ( total products in orders that have not been paid for )<br />
				</p>
				<p>
					<label for="stock_minimum">Stock minimum:</label>
					<input type="text" name="stock_minimum" id="stock_minimum" value="<?php echo $product['stock_minimum'];?>" /> ( the minimum stock before product is automatically flagged as 'out of stock' )<br />
				</p>
				<p>
					<label for="stock_in_stock">Stock status:<span class="required">*</span></label>
					<select name="stock_in_stock" id="stock_in_stock">
						<option value="0" <?php if(isset($product['stock_in_stock']) && $product['stock_in_stock'] == 0 ) { echo " selected='selected' "; }?>>Out of Stock</option>
						<option value="1" <?php if(isset($product['stock_in_stock']) && $product['stock_in_stock'] == 1 ) { echo " selected='selected' "; }?>>In Stock</option>
					</select><br />
				</p>
				<br />
				<hr>
				<h2>Stock Movement</h2>	
				<p>Last 50 transactions for this product</p>
				
				<table class="all_table display">
				<thead>
				<tr>		
					<th>
						Transaction Details
					</th>
					<th style="max-width:200px;text-align:center;">
						Order ID
					</th>							
					<th style="max-width:150px;text-align:center;">
						QTY
					</th>
					<th style="max-width:150px;text-align:center;">
						Admin ID
					</th>							
					<th style="max-width:150px;text-align:center;">
						Date
					</th>							
				</tr>
				<thead>
				<tbody>
				<?php 
				if(!empty( $product['stock_movements'] )) {
					
					function sortBy($field, &$array, $direction = 'asc')
					{
						usort($array, create_function('$a, $b', '
							$direction = "desc";						
							$a = $a["' . $field . '"];
							$b = $b["' . $field . '"];
							if ($a == $b) return 0;
							return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
						'));
						return true;
					}
					
					sortBy('date_added',   $product['stock_movements'], 'desc');
					
					$x = 0;
					
					foreach($product['stock_movements']  as $stock_movement ) {
						$style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
						$transaction_details =  (!empty($stock_movement['order_id'])) ? "Customer Order" : "";
						$transaction_details =  (!empty($stock_movement['reason'])) ? $stock_movement['reason'] : $transaction_details;
						$order_id =  (!empty($stock_movement['order_id'])) ? $stock_movement['order_id'] : "-";						
						$action =  (!empty($stock_movement['order_id'])) ? "-" : "";
						if(!empty($stock_movement['qty_action'])) {
							$action =  ($stock_movement['qty_action'] == 1) ? "+" : "-";
						}
						$admin_id = (!empty($stock_movement['admin_id'])) ? $stock_movement['admin_id'] : "-";
						$stock = $stock_movement['qty'];
						?>				
						<tr style="<?php echo $style_row;?>">			
							<td>
								<?php echo  $transaction_details;?>
							</td>
							<td style="max-width:200px;text-align:center;">
								<?php echo $order_id;?>								
							</td>							
							<td style="max-width:150px;text-align:center;">
								<?php echo $action.$stock; ?>
							</td>	
							<td style="max-width:150px;text-align:center;">
								<?php echo $admin_id ; ?>
							</td>								
							<td style="max-width:150px;text-align:center;">
								<?php echo date("H:i:s d/m/Y", strtotime($stock_movement['date_added']));?>
							</td>		
						</tr>
					<?php 
						$x++;
					}
				}
				else 
				{ 
				?>
					<tr>			
						<td style="" colspan="4">
							<p>No stock movement records found</p>
						</td>
					</tr>				
				<?php }?>
				</tbody>
				</table>					
				
				
			</div>
			<div id="tab-6" class="tab-content">
				<h2>Product Delivery</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>
				<p>
					<label for="dispatch_time">Dispatch time:</label>
					<input type="input" name="dispatch_time" id="dispatch_time" value="<?php echo $product['dispatch_time'];?>" placeholder="Description: e.g. 1-3 days" /><br />
				</p>
			</div>
			<div id="tab-7" class="tab-content">
				<h2>Product Categories</h2>	
				<div style="background-color: white; padding: 5px 15px 15px 15px;border: 1px solid #ccc;">
					<p style="background-color:#f8f8f8; padding: 15px; border: 1px solid #ccc;">Select the categories where you would like to display this product</p>
					<p><?php echo $product['category_menu'];?></p>	
				</div>	
			</div>
			<div id="tab-8" class="tab-content">
				<h2>Products ( Related Attributes )</h2>

				<?php if(! empty($product['related_products'])) {?>
					<table id="delivery_table" class="all_table display">
						<thead>
						<tr>
							<th style="width:95%;">SKU</th>
							<th style="width:5%;text-align:center;">Delete</th>
						</tr>
						</thead>
						<tbody>
							<?php
									$x = 0;
									foreach ( $product['related_products'] as $related_product ){
									$style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
							 ?>
										<tr style="<?php echo $style_row;?>">
											<td style="">
												<a href="<?php echo base_url();?>admin/products/all/<?php echo $related_product['sku_related'];?>" target="_blank"><?php echo $related_product['sku_related'];?></a>
											</td>
											<td style="text-align:center;"><img src="<?php echo base_url('assets/images/delete.png');?>"></td>
										</tr>

							<?php
								$x++;
						}?>
						</tbody>
					</table>
				<?php }?>
				
				<h2>Add New Product</h2>	
				<p>Enter the SKU for other single products related to this product.</p>		
				<?php for( $x = 1; $x <= 5; $x++) {?>
					<hr>
					<p><strong>Related Product:</strong></p>
					<p>
						<label for="related_sku_<?php echo $x;?>">SKU:</label>
						<input type="text" name="related_sku_<?php echo $x;?>" id="related_sku_<?php echo $x;?>" value="">
					</p>	
				<?php }?>
			</div>				
		</div>	
		<p>
			<label for="submit">&nbsp;</label>
			<input class="submit_button" type="submit" name="submit" value="Update product" />
		</p>	
	</form>
</div>

<script>
// set ckeditor field(s)
var editor = CKEDITOR.replace( 'text', {
				height: 400
			} );
CKFinder.setupCKEditor( editor );
var editor = CKEDITOR.replace( 'short_description', {
				height: 200
			} );
CKFinder.setupCKEditor( editor );
</script>