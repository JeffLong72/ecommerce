<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_products_model extends CI_Model {

	public function set_product($data = "")
	{
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		$this->load->helper('url');
		
		$slug = url_title($data['product']['slug'], 'dash', TRUE);
		
		if(!empty($data['product']['special_offer_expires'])) {
			$date = explode("/", $data['product']['special_offer_expires']);
			if(!empty($date)) {
				$special_offer_expires_date = $date[2]."/".$date[1]."/".$date[0]." 00:00:00";
			}
		}
		
		// update products
		$stock_in_stock = (!empty($data['product']['stock_in_stock'])) ? 1 : 0;
		$featured = (!empty($data['product']['featured'])) ? 1 : 0;
		$status = (!empty($data['product']['status'])) ? 1 : 0;
		$insert_products = array(
								'sku' => $data['product']['sku'],
								'slug' => $slug,
								'brand' => $data['product']['brand'],
								'color' => $data['product']['color'],
								'size' => $data['product']['size'],
								'dimensions' => $data['product']['dimensions'],								
								'net_weight' => $data['product']['net_weight'],
								'prod_weight' => $data['product']['product_weight'],	
								'product_attribute' => $data['product']['related_products_attributes_0'],
								'product_attribute_option' => $data['product']['related_value_0'],	
								'restrict_direct_access' => $data['product']['restrict_direct_access'],												
								'stock' => $data['product']['stock'],
								'stock_on_hold' => $data['product']['stock_on_hold'],
								'stock_minimum' => $data['product']['stock_minimum'],
								'stock_in_stock' => $stock_in_stock,
								'featured' => $featured,
								'active' => $status
								);
        $result_products = $this->db->insert('products', $insert_products);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_products_model', 'set_product', 'insert',  print_r($insert_products, TRUE));

        return $result_products;
		
		// update products_text
		$insert_products_text = array(
								'site' => $site,
								'sku' => $data['product']['sku'],
								'title' => $data['product']['title'],
								'short_description' => $data['product']['short_description'],
								'text' => $data['product']['text'],
								'meta_description' => $data['product']['meta_description'],
								'canonical' => $data['product']['canonical'],								
								'dispatch_time' => $data['product']['dispatch_time']
								);
        $result_products_text = $this->db->insert('products_text', $insert_products_text);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_products_model', 'set_product', 'insert',  print_r($insert_products_text, TRUE));

        return $result_products_text;
		
		// update products_sites
		$insert_products_sites = array(
								'site' => $site,
								'sku' => $data['product']['sku']
								);
        $result_products_sites = $this->db->insert('products_sites', $insert_products_sites);

        // log admin action
        $this->global_model->admin_action('Admin_products_model', 'set_product', 'insert',  print_r($insert_products_sites, TRUE));

        return $result_products_sites;

		// update products_cost
		if(!empty($data['product']['special_offer_expires'])) {
			$insert_products_cost = array(
									'site' => $site,
									'sku' => $data['product']['sku'],
									'base_cost' => $data['product']['base_cost'],									
									'cost' => $data['product']['cost'],
									'special_offer_cost' => $data['product']['special_offer_cost'],
									'special_offer_expires' => $special_offer_expires_date,
									'vat_rate' => $data['product']['vat_rate'],
									);
		}
		else {
			$insert_products_cost = array(
									'site' => $site,
									'sku' => $data['product']['sku'],
									'base_cost' => $data['product']['base_cost'],											
									'cost' => $data['product']['cost'],
									'special_offer_cost' => $data['product']['special_offer_cost'],
									'special_offer_expires' => NULL,
									'vat_rate' => $data['product']['vat_rate'],
									);
		}
        $result_products_cost = $this->db->insert('products_cost', $insert_products_cost);

        // log admin action
        $this->global_model->admin_action('Admin_products_model', 'set_product', 'insert',  print_r($insert_products_cost, TRUE));

        return $result_products_cost;

        // update products_delivery
		if(!empty($data['product']['delivery_options'])) {
			foreach($data['product']['delivery_options'] as $delivery_option) {
				$insert = array(
								'site' => $site,
								'sku' => $data['product']['sku'],
								'option' => $delivery_option['id'],
							);
                $result_delivery_options = $this->db->insert('products_delivery', $insert);

                // log admin action
                $this->global_model->admin_action('Admin_products_model', 'set_product', 'insert',  print_r($insert, TRUE));

                return $result_delivery_options;
			}
		}
		
		// updates products_category
		$products_category = $this->input->post('products_category');
		if(!empty($products_category)) {
			$this->db->delete('products_category', array('sku' => $data['product']['original_sku']));
			foreach($products_category as $category) {
				$insert = array( 
								'site' => $site,
								'sku' => $data['product']['sku'],
								'menu_id' => $category,								
							);
                $result = $this->db->insert('products_category', $insert);

                // log admin action
                $this->global_model->admin_action('Admin_products_model', 'set_product', 'insert',  print_r($insert, TRUE));

                return $result;
			}
		}

		// update products_related
		$related_products = $this->input->post('selected_products');
		if(!empty($related_products)) {
			$this->db->delete('products_related', array('sku' => $data['product']['original_sku']));
			foreach($related_products as $related_product) {
				$insert = array( 
								'site' => $site,
								'sku' =>$data['product']['sku'] ,
								'sku_related' => $related_product
							);
                $result = $this->db->insert('products_related', $insert);

                // log admin action
                $this->global_model->admin_action('Admin_products_model', 'set_product', 'insert',  print_r($insert, TRUE));

                return $result;
			}
		}		
				
		// update products_images
		// ( note we do not need a site value here as we don't want 
		//    sites having different images for the same product sku )
		if(!empty($data['products']['image'])) {
			
			$main_image = NULL;
			$thumb_image = NULL;
			$hover_image = NULL;
			$category_image = NULL;
			
			if( !empty( $data['product']['product_image_type'] ) && $data['product']['product_image_type'] == "main" ) 
			{
				$main_image = ( !empty( $data['products']['image'] ) ) ? 'uploads/'.$site.'/products/'.$data['products']['image'] : "";
				$thumb_image = ( !empty( $data['products']['image'] ) ) ? 'uploads/'.$site.'/products/thumbs/'.$data['products']['image'] : "";
			}
			
			if( !empty( $data['product']['product_image_type'] ) && $data['product']['product_image_type'] == "hover" ) 
			{
				$hover_image = ( !empty( $data['products']['image'] ) ) ? 'uploads/'.$site.'/products/'.$data['products']['image'] : "";
			}
			
			if( !empty( $data['product']['product_image_type'] ) && $data['product']['product_image_type'] == "category" ) 
			{
				$category_image = ( !empty( $data['products']['image'] ) ) ? 'uploads/'.$site.'/products/'.$data['products']['image'] : "";
			}
			
			$alt_image = ( !empty( $data['product']['product_image_alt'] ) ) ? $data['product']['product_image_alt'] : "";
			$title_image = ( !empty( $data['product']['product_image_title'] ) ) ? $data['product']['product_image_title'] : "";
	
			$ins = array( 
								'sku' => $data['product']['sku'],
								'image' => $main_image,		
								'small_image' => $small_image,									
								'thumbnail' => $thumb_image,	
								'mouseover' => $hover_image,		
								'category' => $category_image,									
								'alt' => $alt_image,
								'title' => $title_image,								
								);
            $result = $this->db->insert('products_images', $ins);

            // log admin action
            $this->global_model->admin_action('Admin_products_model', 'set_product', 'insert',  print_r($ins, TRUE));

            return $result;
		} 
		
		return "success";
	}
	
	public function update_product($data = "")
	{

		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		$this->load->helper('url');
		
		$slug = url_title($data['product']['slug'], 'dash', TRUE);
		
		if(!empty($data['product']['special_offer_expires'])) {
			$date = explode("/", $data['product']['special_offer_expires']);
			if(!empty($date)) {
				$special_offer_expires_date = $date[2]."/".$date[1]."/".$date[0]." 00:00:00";
			}
		}
		
		// update products
		$stock_in_stock = (!empty($data['product']['stock_in_stock'])) ? 1 : 0;
		$featured = (!empty($data['product']['featured'])) ? 1 : 0;
		$status = (!empty($data['product']['active'])) ? 1 : 0;
		$update_products = array(
								'sku' => $data['product']['sku'],
								'slug' => $slug,
								'brand' => $data['product']['brand'],
								'color' => $data['product']['color'],
								'size' => $data['product']['size'],
								'dimensions' => $data['product']['dimensions'],									
								'net_weight' => $data['product']['net_weight'],
								'prod_weight' => $data['product']['product_weight'],
								'product_attribute' => $data['product']['related_products_attributes_0'],
								'product_attribute_option' => $data['product']['related_value_0'],			
								'restrict_direct_access' => $data['product']['restrict_direct_access'],										
								'stock' => $data['product']['stock'],
								'stock_on_hold' => $data['product']['stock_on_hold'],
								'stock_minimum' => $data['product']['stock_minimum'],
								'stock_in_stock' => $stock_in_stock,
								'featured' => $featured,
								'active' => $status
								);
		$this->db->where('sku', $data['product']['original_sku']);
        $result_update_products = $this->db->update('products', $update_products);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_products_model', 'update_product', 'update',  print_r($update_products, TRUE));

        return $result_update_products;
		
		// update products_text
		$update_products_text = array(
								'sku' => $data['product']['sku'],
								'title' => $data['product']['title'],
								'short_description' => $data['product']['short_description'],
								'text' => $data['product']['text'],
								'meta_description' => $data['product']['meta_description'],
								'canonical' => $data['product']['canonical'],											
								'dispatch_time' => $data['product']['dispatch_time']
								);
		$this->db->where('site', $site);
		$this->db->where('sku', $data['product']['original_sku']);
        $result_update_products_text = $this->db->update('products_text', $update_products_text);

        // log admin action
        $this->global_model->admin_action('Admin_products_model', 'update_product', 'update',  print_r($update_products_text, TRUE));

        return $result_update_products_text;

        // update products_sites
		$update_products_sites = array(
								'sku' => $data['product']['sku']
								);
		$this->db->where('site', $site);
		$this->db->where('sku', $data['product']['original_sku']);
        $result_update_products_sites = $this->db->update('products_sites', $update_products_sites);

        // log admin action
        $this->global_model->admin_action('Admin_products_model', 'update_product', 'update',  print_r($update_products_sites, TRUE));

        return $result_update_products_sites;


        // update products_cost
		if(!empty($data['product']['special_offer_expires'])) {
			$update_products_cost = array(
									'sku' => $data['product']['sku'],
									'base_cost' => $data['product']['base_cost'],										
									'cost' => $data['product']['cost'],
									'special_offer_cost' => $data['product']['special_offer_cost'],
									'special_offer_expires' => $special_offer_expires_date,
									'vat_rate' => $data['product']['vat_rate'],
									);
		}
		else {
			$update_products_cost = array(
									'sku' => $data['product']['sku'],
									'base_cost' => $data['product']['base_cost'],											
									'cost' => $data['product']['cost'],
									'special_offer_cost' => $data['product']['special_offer_cost'],
									'special_offer_expires' => NULL,
									'vat_rate' => $data['product']['vat_rate'],
									);
		}
		$this->db->where('site', $site);
		$this->db->where('sku', $data['product']['original_sku']);
        $result_update_products_cost = $this->db->update('products_cost', $update_products_cost);

        // log admin action
        $this->global_model->admin_action('Admin_products_model', 'update_product', 'update',  print_r($update_products_cost, TRUE));

        return $result_update_products_cost;

		// update products_delivery
		if(!empty($data['product']['delivery_options'])) {
			$this->db->delete('products_delivery', array('site' => $site, 'sku' => $data['product']['original_sku']));			
			foreach($data['product']['delivery_options'] as $delivery_option) {
				$insert = array(
								'site' => $site,
								'sku' => $data['product']['sku'],
								'option' => $delivery_option['id'],
							);
                $result = $this->db->insert('products_delivery', $insert);

                // log admin action
                $this->load->model('global_model');
                $this->global_model->admin_action('Admin_products_model', 'update_product', 'insert',  print_r($insert, TRUE));

                return $result;
			}
		}
		
		// updates products_category
		$products_category = $this->input->post('products_category');
		if(!empty($products_category)) {
			$this->db->delete('products_category', array('sku' => $data['product']['original_sku']));
			foreach($products_category as $category) {
				$insert = array( 
								'site' => $site,
								'sku' => $data['product']['sku'],
								'menu_id' => $category,								
							);
                $result = $this->db->insert('products_category', $insert);

                // log admin action
                $this->global_model->admin_action('Admin_products_model', 'update_product', 'insert',  print_r($insert, TRUE));

                return $result;
			}
		}

		// update products_related
		for($x = 1; $x <= 5; $x++) {
			
			$related_sku = ( ! empty( $data['product']['related_sku_'.$x] ) ) ? $data['product']['related_sku_'.$x] : "";
			$related_products_attributes = ( ! empty(  $data['product']['related_products_attributes_'.$x] ) ) ?  $data['product']['related_products_attributes_'.$x] : "";
			$related_value = ( ! empty( $data['product']['related_value_'.$x] ) ) ?  $data['product']['related_value_'.$x] : "";
				
			if( !empty($related_sku) ) {
				$ins = array( 
								'site' => $site,
								'sku' => $data['product']['sku'],
								'sku_related' => $related_sku,							
							);
                $result = $this->db->insert('products_related', $ins);

                // log admin action
                $this->global_model->admin_action('Admin_products_model', 'update_product', 'insert',  print_r($ins, TRUE));

                return $result;
			}
	
		}
		
		// update products_images
		// ( note we do not need a site value here as we don't want 
		//    sites having different images for the same product sku )
		if(!empty($data['products']['image'])) {
			
			$main_image = NULL;
			$thumb_image = NULL;
			$hover_image = NULL;
			$category_image = NULL;
			
			if( !empty( $data['product']['product_image_type'] ) && $data['product']['product_image_type'] == "main" ) 
			{
				$main_image = ( !empty( $data['products']['image'] ) ) ? 'uploads/'.$site.'/products/'.$data['products']['image'] : "";
				$thumb_image = ( !empty( $data['products']['image'] ) ) ? 'uploads/'.$site.'/products/thumbs/'.$data['products']['image'] : "";
			}
			
			if( !empty( $data['product']['product_image_type'] ) && $data['product']['product_image_type'] == "hover" ) 
			{
				$hover_image = ( !empty( $data['products']['image'] ) ) ? 'uploads/'.$site.'/products/'.$data['products']['image'] : "";
			}
			
			if( !empty( $data['product']['product_image_type'] ) && $data['product']['product_image_type'] == "category" ) 
			{
				$category_image = ( !empty( $data['products']['image'] ) ) ? 'uploads/'.$site.'/products/'.$data['products']['image'] : "";
			}
			
			$alt_image = ( !empty( $data['product']['product_image_alt'] ) ) ? $data['product']['product_image_alt'] : "";
			$title_image = ( !empty( $data['product']['product_image_title'] ) ) ? $data['product']['product_image_title'] : "";
	
			$ins = array( 
								'sku' => $data['product']['sku'],
								'image' => $main_image,		
								'small_image' => $small_image,									
								'thumbnail' => $thumb_image,	
								'mouseover' => $hover_image,		
								'category' => $category_image,									
								'alt' => $alt_image,
								'title' => $title_image,								
								);
            $result = $this->db->insert('products_images', $ins);

            // log admin action
            $this->global_model->admin_action('Admin_products_model', 'update_product', 'insert',  print_r($ins, TRUE));

            return $result;
		}
		
		// update stock_movements
		if($data['product']['stock'] != $data['product']['original_stock']) {
			$this->adjust_stock_values ($data, "Admin updated the available stock");	
		}

	}
	
	function product_categories($product_sku = "") {
		
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// get categories this product has been assigned too
		$query = $this->db->query('SELECT menu_id FROM products_category WHERE sku = "'.$product_sku.'"');
		$product_categories = $query->result_array();
		$category = array();
		foreach($product_categories as $product_category) {
			if(!in_array($product_category['menu_id'], $category)){
				$category[] = $product_category['menu_id'];
			}
		}
		
		// get all categories
		$query = $this->db->query('SELECT id, category_name, url FROM menu WHERE level = 0 and site = "'.$site.'"');
		$level_zero = $query->result_array();
		
		$category_menu = "<p>No menu found</p>";
	
		if(!empty($level_zero)) {
			
			$category_menu = "<style>
					 table, th, td {
						border: 0px solid #ccc;
					 }
					 th {
						 text-align:left;
						 font-size: 16px;
					 }
					 td {
						width: 300px; 
					 }
					 .category_menu_checkbox {
						width: 20px; 
						vertical-align: middle;
					 }
					 .cat-pipe {
						float:left;
						margin-left: 13px;
					 }
					 .cat-hr {
						float:left;
						width:86%;
						margin-top:16px;
					 }
					 </style>";
			
			$category_menu.= "<table cellpadding=0 cellspacing=0>";
			$category_menu.= "<tr><th>Main Category</th><th>Sub-Category</th><th>Sub-Category</th></tr>";
			$category_menu.= "<tr><td colspan='3'><hr></td></tr>";
		
			foreach($level_zero as $zero) {
				
				$query = $this->db->query('SELECT id, category_name, url FROM menu WHERE level = 1 AND parent_id = '.$zero['id'].' and site = "'.$site.'"');
				$level_one = $query->result_array();

				$checked = (in_array($zero['id'], $category)) ? " checked='checked' " : "";	
				
				if(empty($level_one)) {
					$category_menu.= "<tr><td><input class='category_menu_checkbox' type='checkbox' name='products_category[]' value='".$zero['id']."' ".$checked.">".$zero['category_name']."</td><td></td><td></td></tr>";
				}
				else {
					$category_menu.= "<tr><td><input class='category_menu_checkbox' type='checkbox' name='products_category[]' value='".$zero['id']."' ".$checked.">".$zero['category_name']."</td><td></td><td></td></tr>";	

								if(!empty($level_one)) {
									foreach($level_one as $one) {
										$query = $this->db->query('SELECT id, category_name, url FROM menu WHERE level = 2 AND parent_id = '.$one['id'].' and site = "'.$site.'"');
										$level_two = $query->result_array();
										
										$checked = (in_array($one['id'], $category)) ? " checked='checked' " : "";	

										$category_menu.= "<tr><td><span class='cat-pipe'>|</span><hr class='cat-hr'></td><td><input class='category_menu_checkbox' type='checkbox' name='products_category[]' value='".$one['id']."' ".$checked.">".$one['category_name']."</td><td></td></tr>";								

										if(!empty($level_two)) {
											foreach($level_two as $two) {
												
												$checked = (in_array($two['id'], $category)) ? " checked='checked' " : "";	
												
												$category_menu.= "<tr><td></td><td><span class='cat-pipe'>|</span><hr class='cat-hr'></td><td><input class='category_menu_checkbox' type='checkbox' name='products_category[]' value='".$two['id']."' ".$checked.">".$two['category_name']."</td></tr>";
											}
											
										}						
									}
								}
								
					$category_menu.= "</tr>";			
				}
				
				$category_menu.= "<tr><td colspan='3'><hr></td></tr>";
			}
			
			$category_menu.= "</table>";
		}
		
		return $category_menu;
		
	}
	
	function product_images ($sku= "") {
		
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session		
		
		$query = $this->db->query('SELECT *
														FROM products_images 
														WHERE `status` = 0
														AND sku = "'.$sku.'"
														ORDER BY category DESC, mouseover DESC, image DESC');
		$result = $query->result_array();	

		return (!empty($result)) ? $result : array();
				
	}
	
	function adjust_stock_values ($data, $reason = "") {
		
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		$admin_id = (!empty($_SESSION['admin']['id'])) ? $_SESSION['admin']['id'] : 0;

		if( $data['product']['stock'] > $data['product']['original_stock']) 
		{
				$stock_qty = $data['product']['stock'] - $data['product']['original_stock'];
				$qty_action = 1; // increase stock
		}
		else
		{ 
				$stock_qty = $data['product']['original_stock'] - $data['product']['stock'];
				$qty_action = 2; // reduce stock		
		}
			
		$ins = array( 
								'site' => $site,											
								'sku' => $data['product']['sku'],
								'reason' => $reason,		
								'qty' => $stock_qty,			
								'qty_action' => $qty_action,										
								'admin_id' => $admin_id,	
								'date_added' => date("Y-m-d H:i:s"),		
								'active' => 1,	
								'status' => 0,									
								);

        $result = $this->db->insert('stock_movements', $ins);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_products_model', 'adjust_stock_values', 'insert',  print_r($ins, TRUE));

        return $result;
    }
	
	function get_stock_movement ($sku= "") {
		
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session		
		
		// stock from orders_product table
		$query = $this->db->query('SELECT *
														FROM orders_products
														WHERE active = 1
														AND `status` = 0
														AND sku = "'.$sku.'"
														ORDER BY id DESC
														LIMIT 25');
		$result_a = $query->result_array();	
		$result_a = (!empty($result_a)) ? $result_a : array();
		
		// stock from stock_movements table
		$query = $this->db->query('SELECT *
														FROM stock_movements
														WHERE active = 1
														AND `status` = 0
														AND sku = "'.$sku.'"
														ORDER BY id DESC
														LIMIT 25');
		$result_b = $query->result_array();			
		$result_b = (!empty($result_b)) ? $result_b : array();		

		return array_merge($result_a,$result_b);
				
	}

    function update_image_details($data = "")
    {

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('url');

        // update delivery
        $active = (!empty($data['image']['active'])) ? 1 : 0;
        $update_image_rates = array(
            'alt' => $data['image']['alt'],
            'title' => $data['image']['title'],
            'active' => $active
        );
        $this->db->where('id', $data['image']['id']);
        $result = $this->db->update('products_images', $update_image_rates);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_products_model', 'update_image_details', 'insert',  print_r($update_image_rates, TRUE));

        return $result;
    }	

}
