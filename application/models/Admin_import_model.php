<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_import_model extends CI_Model {

	public function import_products($data)
	{
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

		/* start: debug */
		//$csv = array_map('str_getcsv', file($data['full_path']));
		//$file = fopen($data['full_path'], "r");
		//$col = fgetcsv($file, 10000, ",");
		//echo "<pre>";
		//return print_r($col);
		//echo "</pre>";
		//die();
		/* end: debug */
		
		// csv fields
		$csv_fields = $this->input->post();
		// get data from file
		$file = fopen($data['full_path'], "r");
		// for each row
		$row = 0;
		// sku parent ( for related products )
		$sku_related = "";
		while (($col = fgetcsv($file, 10000, ",")) !== FALSE){
			
			// Import: Unineed EN ( magento 1.9 )
			$sku = (!empty($col[$csv_fields['sku']])) ? $col[$csv_fields['sku']] : ""; // sku
			$slug = (!empty($col[$csv_fields['slug']])) ? $col[$csv_fields['slug']] : ""; // url_key
			$brand = (!empty($col[$csv_fields['brand']])) ? $col[$csv_fields['brand']] : ""; // brands		
			$manufacturer = (!empty($col[$csv_fields['manufacturer']])) ? $col[$csv_fields['manufacturer']] : ""; // manufacturer					
			$color = (!empty($col[$csv_fields['color']])) ? $col[$csv_fields['color']] : ""; // color	
			$size = (!empty($col[$csv_fields['size']])) ? $col[$csv_fields['size']] : ""; // size	
			$differentsize = (!empty($col[$csv_fields['differentsize']])) ? $col[$csv_fields['differentsize']] : ""; // differentsize				
			$dimensions = (!empty($col[$csv_fields['dimensions']])) ? $col[$csv_fields['dimensions']] : ""; //		
			$net_weight = (!empty($col[$csv_fields['net_weight']])) ? $col[$csv_fields['net_weight']] : ""; // weight
			$prod_weight = (!empty($col[$csv_fields['prod_weight']])) ? $col[$csv_fields['prod_weight']] : ""; // weight
			$stock = (!empty($col[$csv_fields['stock']])) ? $col[$csv_fields['stock']] : ""; // qty
			$stock_on_hold = (!empty($col[$csv_fields['stock_on_hold']])) ? $col[$csv_fields['stock_on_hold']] : ""; //
			$stock_minimum = (!empty($col[$csv_fields['stock_minimum']])) ? $col[$csv_fields['stock_minimum']] : ""; // min_qty			
			$stock_in_stock = (!empty($col[$csv_fields['stock_in_stock']])) ? $col[$csv_fields['stock_in_stock']] : ""; // is_in_stock				
			$featured = (!empty($col[$csv_fields['featured']])) ? $col[$csv_fields['featured']] : ""; //				
			$status = (!empty($col[$csv_fields['status']])) ? $col[$csv_fields['status']] : ""; // status
			$title = (!empty($col[$csv_fields['title']])) ? $col[$csv_fields['title']] : ""; // name	
			$short_description = (!empty($col[$csv_fields['short_description']])) ? $col[$csv_fields['short_description']] : ""; // short_description		
			$text = (!empty($col[$csv_fields['text']])) ? $col[$csv_fields['text']] : ""; // description	
			$meta_description = (!empty($col[$csv_fields['meta_description']])) ? $col[$csv_fields['meta_description']] : ""; // meta_description		
			$dispatch_time = (!empty($col[$csv_fields['dispatch_time']])) ? $col[$csv_fields['dispatch_time']] : ""; // dispatch time	
			$cost = (!empty($col[$csv_fields['cost']])) ? $col[$csv_fields['cost']] : ""; // price	
			$special_offer_cost = (!empty($col[$csv_fields['special_offer_cost']])) ? $col[$csv_fields['special_offer_cost']] : ""; // special_price	
			$special_offer_expires = (!empty($col[$csv_fields['special_offer_expires']])) ? $col[$csv_fields['special_offer_expires']] : NULL; // 
			$vat_rate = (!empty($col[$csv_fields['vat_rate']])) ? $col[$csv_fields['vat_rate']] : ""; // tax_class_id
			$image = (!empty($col[$csv_fields['image']])) ? $col[$csv_fields['image']] : ""; // image
			$small_image = (!empty($col[$csv_fields['small_image']])) ? $col[$csv_fields['small_image']] : ""; // small_image			
			$thumbnail = (!empty($col[$csv_fields['thumbnail']])) ? $col[$csv_fields['thumbnail']] : ""; // thumbnail
			$category = (!empty($col[$csv_fields['category']])) ? $col[$csv_fields['category']]."," : ""; // _category (eg. category/sub-category)
			$sku_related = (!empty($col[$csv_fields['related_sku']])) ? $col[$csv_fields['related_sku']]."," : ""; // related sku			
			
			/*
			// Import: Unineed EN ( magento 1.9 )
			$sku = $col[0]; // sku
			$slug = $col[78]; // url_key
			$brand = $col[10]; // brands		
			$manufacturer = $col[43]; // manufacturer					
			$color = $col[11]; // color	
			$size = $col[66]; // size	
			$differentsize = $col[27]; // differentsize				
			$dimensions = NULL; //		
			$net_weight = $col[81]; // weight
			$prod_weight = $col[81]; // weight
			$stock = $col[83]; // qty
			$stock_on_hold = 0; //
			$stock_minimum = $col[84]; // min_qty			
			$stock_in_stock = $col[93]; // is_in_stock				
			$featured = 0; //				
			$status = $col[73]; // status
			$title = $col[52]; // name	
			$short_description = $col[65]; // short_description		
			$text = $col[26]; // description	
			$meta_description = $col[45]; // meta_description		
			$dispatch_time = $col[25]; // dispatch time	
			$cost = $col[58]; // price	
			$special_offer_cost = $col[71]; // special_price	
			$special_offer_expires = NULL; // 
			$vat_rate = $col[74]; // tax_class_id
			$image = $col[37]; // image
			$small_image = $col[68]; // small_image			
			$thumbnail = $col[75]; // thumbnail
			$category = $col[4].","; // _category (eg. category/sub-category)	
			$sku_related = $col[104]; // related sku			
			*/
			
			if( $row > 0 ) {

				// update products
				if(!empty($sku)) {
					$insert_products = array(
											'sku' => $sku,
											'slug' => $slug,
											'brand' => $brand,
											'color' => $color,
											'size' => $size,
											'dimensions' => $dimensions,								
											'net_weight' => $net_weight,
											'prod_weight' => $prod_weight,								
											'stock' => $stock,
											'stock_on_hold' => $stock_on_hold,
											'stock_minimum' => $stock_minimum,
											'stock_in_stock' => $stock_in_stock,
											'featured' => $featured,
											'active' => $status
											);
					$this->db->insert('products', $insert_products);
				}
				
				// update products_text
				if(!empty($sku)) {
					$insert_products_text = array(
											'site' => $site,
											'sku' => $sku,
											'title' => $title,
											'short_description' => $short_description,
											'text' => $text,
											'meta_description' => $meta_description,
											'dispatch_time' => $dispatch_time
											);
					$this->db->insert('products_text', $insert_products_text);	
				}
				
				// update products_sites
				if(!empty($sku)) {
					$insert_products_sites = array(
											'site' => $site,
											'sku' => $sku
											);
					$this->db->insert('products_sites', $insert_products_sites);		
				}

				// update products_cost
				if(!empty($sku)) {
					$insert_products_cost = array(
												'site' => $site,
												'sku' => $sku,
												'cost' => $cost,
												'special_offer_cost' => $special_offer_cost,
												'special_offer_expires' => $special_offer_expires,
												'vat_rate' => $vat_rate,
												);
												
					$this->db->insert('products_cost', $insert_products_cost);	
				}
				
				// update menu structure
				$category = strtolower($category);
				$category = str_replace(" /", "/", $category);
				$category = str_replace(" ", "-", $category);
				$category = str_replace("'", "", $category);
				$category = str_replace(",", "", $category);
				$category = preg_replace('/-$/', '', $category);
				$c = explode("/", $category);

				for($j = 0; $j < count($c); $j++) {
					
					$parent_id = ($j > 0) ? $c[$j-1] : ""; 
					$query = $this->db->query('SELECT id FROM menu WHERE url = "'.$parent_id.'" ');
					$parent_row = $query->result_array();

					$query = $this->db->query('SELECT id FROM menu WHERE url = "'.$c[$j].'" ');
					$row = $query->result_array();

					if(empty($row) && !empty($c[$j])) {
					
						$insert = array( 
										'site' => $site,
										'parent_id' => (!empty($parent_row)) ? $parent_row[0]['id'] : 0 ,
										'level' => $j,
										'url' => $c[$j],
										'category_name' => ucwords(str_replace("-"," ", $c[$j])),									
										);
											
						$this->db->insert('menu', $insert);
								
					}
					
					// update products_category
					if(!empty($sku)) {
						
						$query = $this->db->query('SELECT id FROM menu WHERE url = "'.$c[$j].'" ');
						$parent_row = $query->result_array();
					
						$insert = array( 
										'site' => $site,
										'sku' => $sku,										
										'menu_id' => (!empty($parent_row)) ? $parent_row[0]['id'] : 0 , 								
										);
											
						$this->db->insert('products_category', $insert);
					}

				}
				
				// products_related
				if(!empty($sku)) {
					$sku_parent = $sku;
				}
				if(!empty($sku_related)) {
					$insert = array( 
									'site' => $site,
									'sku' => $sku_parent,	
									'sku_related' => str_replace(",", "", $sku_related),																	
									);
												
					$this->db->insert('products_related', $insert);
				}
				
				// product_images
				if(!empty($sku)) {
					$insert = array( 
									'sku' => $sku_parent,	
									'image' => $image,
									'small_image' => $small_image,	
									'thumbnail' => $thumbnail,										
									);
												
					$this->db->insert('products_images', $insert);	
				}
			}
			
			$row++;
		}
		// close file
		fclose($file);

		return "CSV File has been successfully Imported.";
	}

}
