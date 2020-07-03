<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_import_model extends CI_Model {

	public function import_products($data)
	{
		ini_set('max_execution_time', 600); // 5 minutes = 300
		ini_set('memory_limit', '512M');
		
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
		// $csv_fields = $this->input->post();
		// get data from file
		// $file = fopen($data['full_path'], "r");
		// for each row
		$row = 0;
		// sku parent ( for related products )
		$sku_related = "";
		// while (($col = fgetcsv($file, 10000, ",")) !== FALSE){
		for($x = 0; $x < 100000; $x++) {	

			if($x < 20001) {
				$site = "en";
			}
			if($x > 20000 && $x < 40001) {
				$site = "cn";
			}
			if($x > 40000 && $x < 60001) {
				$site = "usa";
			}
			
			$sku = "sku_100".$x; // sku
			$title = "title -".$sku; // name
			$slug = "title-".$sku; // url_key
			$brand = "nike"; // brands		
			$manufacturer = "niki"; // manufacturer					
			$color = ""; // color	
			$size = ""; // size	
			$differentsize = ""; // differentsize				
			$dimensions = NULL; //		
			$net_weight = 1; // weight
			$prod_weight = 1; // weight
			$stock = 9; // qty
			$stock_on_hold = 0; //
			$stock_minimum = 3; // min_qty			
			$stock_in_stock = 1; // is_in_stock				
			$featured = 0; //				
			$status = 1; // status	
			$short_description = "short description - ".$title; // short_description		
			$text = "long description - ".$title; // description	
			$meta_description = "meta description - ".$title; // meta_description		
			$dispatch_time = "1-3 days"; // dispatch time	
			$cost = "99.99"; // price	
			$special_offer_cost = "89.99"; // special_price	
			$special_offer_expires = NULL; // 
			$vat_rate = 2; // tax_class_id
			$image = "image-no-pic.jpg"; // image
			$small_image = "image-no-pic.jpg"; // small_image			
			$thumbnail = "image-no-pic.jpg"; // thumbnail
			$category = "skin-care/face-care/toners,"; // _category (eg. category/sub-category)	
			$sku_related = ""; // related sku			
			
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
		//fclose($file);

		return "CSV File has been successfully Imported.";
	}

}
