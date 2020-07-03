<div class="admin_content">
    <h1>Add new voucher</h1>
    <hr>
	
	<p style="float:right;">
		<span class="required">*</span> Required field
	</p>	

	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>	
	
	<form action="<?php current_url();?>" name="create_voucher" class="create_voucher" method="post">
	
		<p>Enter your voucher details below.</p>
		
		<h2 style="margin-top: 30px;">Voucher Details</h2>
		
		<!-- voucher code //-->
		<p>
			<?php echo form_error('voucher_code'); ?>
			<label for="voucher_code">Voucher Code:<span class="required">*</span></label>
			<input type="text" name="voucher_code" id="voucher_code" value="<?php echo set_value('voucher_code'); ?>" placeholder="eg. 25OFF">
		</p>		

		<!-- voucher  description //-->
		<p>
			<?php echo form_error('voucher_description'); ?>
			<label for="voucher_description">Voucher Description:</label>
			<input type="text" name="voucher_description" id="voucher_description" value="<?php echo set_value('voucher_description'); ?>" placeholder="eg. 25% off all products">
		</p>
		
		<h2>Voucher Type</h2>	
		
		<p>Enter any monetary values in GBP only. This will be automatically converted to the users chosen currency.</p>				

		<!-- voucher type //-->
		<p>
			<label for="voucher_type">Please select:</label>
			<select name="voucher_type">
				<option value="">-- select --</option>
				<option value="1">Money Off</option>
				<option value="2">Percentage Off</option>	
			</select>
		</p>

		<!-- voucher money off  //-->
		<p>
			<?php echo form_error('voucher_money_off'); ?>
			<label for="voucher_money_off">If money off, how much?</label>
			<input type="text" name="voucher_money_off" id="voucher_money_off" value="<?php echo set_value('voucher_money_off'); ?>" placeholder="eg. 10.00" style="width: 100px;">
		</p>

			<!-- voucher percent off //-->
		<p>
			<?php echo form_error('voucher_percent_off'); ?>
			<label for="voucher_percent_off">If percentage off, how much?</label>
			<input type="text" name="voucher_percent_off" id="voucher_percent_off" value="<?php echo set_value('voucher_percent_off'); ?>" placeholder="eg. 20" style="width: 100px;">%
		</p>
		
		<h2>Voucher Section</h2>
		
		<p>Is the voucher for all orders, or limited to brands, categories &amp; products only?</p>		
			<label for="voucher_all_products">Please select</label>
			<select name="voucher_all_products">
				<option value="">-- select --</option>
					<option value="1">All Orders</option>
					<option value="0">Brands, Categories & Products Only</option>					
			</select>		
		
		
		<p>If the voucher is for a specific brand, category or product, enter the details below.</p>
		
		<!-- voucher brand //-->
		<p>
			<label for="voucher_brand">If for specific brand, which?</label>
			<select name="voucher_brand">
				<option value="">-- select brand --</option>
				<?php foreach( $brands as $brand ) {?>
					<option value="<?php echo $brand['brand'];?>"><?php echo $brand['brand'];?></option>
				<?php }?>
			</select>
		</p>		

		<!-- voucher category //-->
		<p>
			<label for="voucher_category">Or, for specific category, which?</label>
			<select name="voucher_category">
				<option value="">-- select category --</option>
				<?php foreach( $category_names as $category_name ) {?>
					<option value="<?php echo $category_name['id'];?>"><?php echo $category_name['category_name'];?></option>
				<?php }?>
			</select>			
		</p>

		<!-- voucher product //-->
		<p>
			<label for="voucher_product">Or, for specific product, which?</label>
			<select name="voucher_product">
				<option value="">-- select product sku --</option>
				<?php foreach( $product_skus as $product_sku ) {?>
					<option value="<?php echo $product_sku['sku'];?>"><?php echo $product_sku['sku'];?></option>
				<?php }?>
			</select>					
		</p>
		
		<h2>Voucher Maximum Uses</h2>	

		<p>This is the total amount of times the voucher can be used before becoming inactive. Leave field blank if voucher can be used unlimited times.</p>

		<!-- voucher max uses //-->
		<p>
			<?php echo form_error('voucher_max_uses'); ?>
			<label for="voucher_max_uses">Voucher Max Uses:</label>
			<input type="text" name="voucher_max_uses" id="voucher_max_uses" value="<?php echo set_value('voucher_max_uses'); ?>" placeholder="eg. 10" style="width: 100px;">
		</p>
		
		<h2>Voucher Expires Date</h2>

		<p>Leave field blank if voucher never expires.</p>		

		<!-- voucher expires date //-->
		<p>
			<label for="voucher_expires_date">Voucher Expires Date:</label>
			<input type="text" name="voucher_expires_date" id="voucher_expires_date" value="<?php echo set_value('voucher_expires_date'); ?>" placeholder="DD/MM/YYYY" readonly>
		</p>
		
		<h2>Voucher Status</h2>		
	
		<!-- active  //-->
		<p>
			<label for="active">Active:</label>
			<select name="active">
				<option value="0">Inactive</option>
				<option value="1">Active</option>
			</select>
		</p>
		
		<p>&nbsp;</p>
	
		<!-- add item button //-->
		<p>
			<label for="submit">&nbsp;</label>
			<input class="submit_button" type="submit" name="create_voucher_submit" value="Add New Voucher">
		</p>
	
	</form>

</div>

<style>
.create_voucher label {
	width: 220px;
}
.create_voucher h2 {
	margin-top: 40px;
}
</style>

<script>
$(document).ready(function () {
	$( "#voucher_expires_date" ).datepicker({dateFormat: 'dd/mm/yy'});
}); 
</script>