<div class="admin_content">
	<h1>Import products</h1>

	<?php echo (!empty($error)) ? "<p>".$error."</p>" : "";?>
	<?php echo (!empty($result)) ? "<p>".$result."</p>" : "";?>
	
	<hr>
	
	<?php echo form_open_multipart('/admin/import/products');?>
	
	<div>
		<h2>1) Select fields to import</h2>
		Set the fields below to match the columns of the CSV file you want to import ( eg. for CSV file column A select 0, for B select 1, for C select 2, etc ).
		<?php 
			$count = 200;
			$td_count = 0;
			$new_row = 5;
			$col_array = array(
				"sku","slug","brand","manufacturer","color","size",
				"differentsize","dimensions","net_weight","prod_weight",
				"stock","stock_on_hold","stock_minimum","stock_in_stock",
				"featured","status","title","short_description","text",
				"meta_description","dispatch_time","cost","special_offer_cost",
				"special_offer_expires","vat_rate","image","small_image","thumbnail",
				"category","related_sku");
				
			echo '<table>';	
			echo '<tr>';
			
			for($i = 0; $i < count($col_array); $i++){
				
				$field_name = $col_array[$i];
				if($field_name == "stock_in_stock") {
					$field_name = "Stock Enabled";
				}
			
				echo '<td style="text-align:center;width:160px;padding-bottom: 10px;">';
				echo '<strong>'.ucwords(str_replace("_", " ",$field_name)).'</strong><br />';
				echo '<select name="'.$col_array[$i].'">';	
				echo '<option value="">-- select --</option>';
				for ($j = 0; $j <= $count; $j++) {
					
					$post_csv_field = $this->input->post($col_array[$i]);
					$selected = (!empty($post_csv_field) && $post_csv_field == $j) ? " selected = 'selected' " : "";	
					
					echo '<option value="'.$j.'" '.$selected.' >'.$j.'</option>';
				}
				echo '</select>';
				echo '</td>';
				
				if($td_count == $new_row) {
					echo '</tr><tr>';
					$td_count = 0;
				}
				else {
					$td_count++;
				}
			}
				
			echo '</tr>';	
			echo '</table>';
		?>
	<p>
		<strong>
			IMPORTANT:
		</strong> 
		The import will automatically create menu items based on the category field value for each product. 
		For example if the product category field value is "category/sub-category/sub-sub-category", menu items will be automatically 
		created for "category", "sub-category", and "sub-sub-category".
	<p>
	<p>
		<strong>
			IT IS STRONGLY RECOMMENDED THAT YOU CREATE A BACK UP YOUR DATABASE BEFORE IMPORTING YOUR CSV PRODUCTS
		</strong>
	</p>
	</div>
	
	<hr>
	
	<div>
		<h2>2) Import CSV products file</h2>
		Now select the CSV products file you want to import<br /><br />
		<input type="file" name="import_file" size="20" />
		<br /><br />
		<input class="submit_button" type="submit" value="Upload products" />
	</div>
	
	</form>

</div>

<style>
.submit_button {
	background-color: #006BB4 !important;
    height: 40px;
    width: 240px;
    color: #ffffff !important;
    font-weight: bold !important;
	font-size: 20px;
	font-family: Quicksand, Helvetica, Arial, sans-serif;
    border: none;
	border-radius: 5px 5px 5px 5px;
	padding: 5px;
	padding-left: 10px;
	padding-right: 10px;
	text-decoration: none;
	cursor:pointer;
}
</style>