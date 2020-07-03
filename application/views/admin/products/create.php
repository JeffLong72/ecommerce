<div class="admin_content">
	<h1>Add new product</h1>
	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>	
	<hr>
	<span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>
	<?php echo form_open('admin/products/create'); ?>
		<div class="tab-container">
			<ul class="tabs">
				<li class="tab-link current" data-tab="tab-1">Product Page</li>
				<li class="tab-link" data-tab="tab-2">Product Information</li>
				<li class="tab-link" data-tab="tab-3">Product Cost</li>
				<li class="tab-link" data-tab="tab-4">Product Images</li>
				<li class="tab-link" data-tab="tab-5">Product Stock</li>					
				<li class="tab-link" data-tab="tab-6">Product Delivery</li>	
				<li class="tab-link" data-tab="tab-7">Product Categories</li>				
				<li class="tab-link" data-tab="tab-8">Product Related</li>					
			</ul>
			<div id="tab-1" class="tab-content current">
				<h2>Product Page</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>
				<p>
					<label for="title">Sku:<span class="required">*</span></label>
					<input type="input" name="sku" id="sku" maxlength="255" value="<?php echo (!empty($product['sku'])) ? $product['sku'] : "";?>" /><br />
				</p>
				<p>
					<label for="title">Name:<span class="required">*</span></label>
					<input type="input" name="title" id="title" maxlength="255" value="<?php echo (!empty($product['title'])) ? $product['title'] : "";?>" /><br />
				</p>
				<p>
					<label for="title">Url:<span class="required">*</span></label>
					<input type="input" name="slug" id="slug" maxlength="255" value="<?php echo (!empty($product['slug'])) ? $product['slug'] : "";?>" /><br />
				</p>
				<p>
					<label for="short_description">Short description:</label><br /><br />
					<textarea id="short_description" name="short_description"><?php echo (!empty($product['short_description'])) ? $product['short_description'] : "";?></textarea><br />
				</p>
				<p>
					<label for="text">Full description:</label><br /><br />
					<textarea id="text" name="text"><?php echo (!empty($product['text'])) ? $product['text'] : "";?></textarea><br />
				</p>
				<hr>
				<h2>SEO Details</h2>
				<p>
					<label for="meta_title">Meta title (70 chars):</label>
					<input type="text" name="meta_title" id="meta_title" value="<?php echo (!empty($product['meta_title'])) ? $product['meta_title'] : "";?>" maxlength="70"/><br />
				</p>				
				<p>
					<label for="title">Meta description (160 chars):</label>
					<textarea name="meta_description" id="meta_description" maxlength="160"><?php echo (!empty($product['meta_description'])) ? $product['meta_description'] : "";?></textarea><br />
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
					<label for="title">Featured:</label>
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
					<label for="title">Status:</label>
					<select name="status">
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
				<?php for( $x = 0; $x <= 0; $x++) {?>					
					<p>
						<label for="related_products_attributes_<?php echo $x;?>">Attribute:</label>
						<select name="related_products_attributes_<?php echo $x;?>" class="related_products_attributes" data-select="<?php echo $x;?>">
								<option value="">-- please select --</option>
							<?php foreach($product_attributes['all'] as $key=>$value) {?>
								<option value="<?php echo $key;?>"><?php echo $value;?></option>
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
				
			</div>
			<div id="tab-3" class="tab-content">
				<h2>Product Cost</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>
				<p>
					<label for="title">Base cost:<span class="required">*</span></label>
					<input type="input" name="cost" id="cost" placeholder="0.00" value="<?php echo (!empty($product['cost'])) ? $product['cost'] : "";?>" /><br />
				</p>
				<p>
					<label for="title">Sell cost:<span class="required">*</span></label>
					<input type="input" name="cost" id="cost" placeholder="0.00" value="<?php echo (!empty($product['cost'])) ? $product['cost'] : "";?>" /><br />
				</p>
				<p>
					<label for="title">Special cost:</label>
					<input type="input" name="special_offer_cost" id="special_offer_cost" placeholder="0.00" value="<?php echo (!empty($product['special_offer_cost'])) ? $product['special_offer_cost'] : "";?>" /><br />
				</p>
				<p>
					<label for="title">Vat rate:<span class="required">*</span></label>
					<select name="vat_rate" id="vat_rate">
						<?php 
						if(!empty($product['vat_rates'])){
							foreach($product['vat_rates'] as $vat_rate) {
								$selected = ($vat_rate['vat_rate'] == $product['vat_rate']) ? " selected = 'selected' ": "";
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
					<label for="title">Special cost expiry date:</label>
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
			</div>
			<div id="tab-5" class="tab-content">
				<h2>Product Stock</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>
				<p>
					<label for="title">Available Stock:</label>
					<input type="input" name="stock" id="stock" placeholder="0" value="<?php echo (!empty($product['stock'])) ? $product['stock'] : "";?>" /><br />
				</p>
				<p>
					<label for="title">Stock on hold:</label>
					<input type="input" name="stock_on_hold" id="stock_on_hold" placeholder="0" value="<?php echo (!empty($product['stock_on_hold'])) ? $product['stock_on_hold'] : "";?>" /><br />
				</p>
				<p>
					<label for="title">Stock minimum:</label>
					<input type="input" name="stock_minimum" id="stock_minimum" placeholder="0" value="<?php echo (!empty($product['stock_minimum'])) ? $product['stock_minimum'] : "";?>" /><br />
				</p>
				<p>
					<label for="title">Stock status:<span class="required">*</span></label>
					<select name="stock_in_stock" id="stock_in_stock">
						<option value="0" <?php if(isset($product['stock_in_stock']) && $product['stock_in_stock'] == 0 ) { echo " selected='selected' "; }?>>Out of Stock</option>
						<option value="1" <?php if(isset($product['stock_in_stock']) && $product['stock_in_stock'] == 1 ) { echo " selected='selected' "; }?>>In Stock</option>
					</select><br />
				</p>
			</div>
			<div id="tab-6" class="tab-content">
				<h2>Product Delivery</h2>
				<p style="float:right;">
					<span class="required">*</span> Required field
				</p>
				<p>
					<label for="title">Dispatch time:</label>
					<input type="input" name="dispatch_time" id="dispatch_time" placeholder="e.g. 1-3 days" value="<?php echo (!empty($product['dispatch_time'])) ? $product['dispatch_time'] : "";?>" /><br />
				</p>
			</div>
			<div id="tab-7" class="tab-content">
				<h2>Product Categories</h2>	
				<p>Select the categories where you would like to display this product</p>
				<div style="background-color: white; padding: 15px;border: 1px solid #ccc;">
				<p><?php echo $product['category_menu'];?></p>	
				</div>
			</div>
			<div id="tab-8" class="tab-content">
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
			<input class="submit_button" type="submit" name="submit" value="Create product" />
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