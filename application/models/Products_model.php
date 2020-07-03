<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model {

	public function get_products_page($site="",$slug="")
	{
		$this->db->cache_off();
		$query = $this->db->query('SELECT 
									p.id, p.sku, p.slug, p.brand, p.color, p.size, p.dimensions, p.net_weight, p.prod_weight, p.product_attribute, p.product_attribute_option,
									p.stock, p.stock_on_hold, p.stock_minimum, p.stock_in_stock, p.featured, p.active, 
									pc.cost, pc.special_offer_cost, pc.special_offer_expires, pc.vat_rate,
									pt.title, pt.`text`, pt.meta_title, pt.meta_description, pt.dispatch_time, pt.short_description, pt.canonical
									FROM products AS p 
									LEFT JOIN products_text AS pt ON p.sku = pt.sku AND pt.site = "'.$site.'" 
									LEFT JOIN products_sites AS ps ON p.sku = ps.sku AND ps.site = "'.$site.'"
									LEFT JOIN products_cost AS pc ON ps.sku = pc.sku AND pc.site = "'.$site.'" 
									WHERE ps.site IN ("'.$site.'")
									AND p.active = 1
									AND p.`status` = 0
									AND p.slug = "'.$slug.'"');
									
		$rows = $query->result_array();
		
		// get all images for this product
		if(!empty($rows)) {
			$rows[0]['product_images'] = $this->get_product_images($rows[0]['sku']);
		}
	
		return $rows;
	}
	
	public function get_trending_products ($site) 
	{
		$this->db->cache_on();
		$query = $this->db->query('SELECT t1.sku
									FROM (
										SELECT sku, page_views
										FROM products_text
										ORDER BY page_views DESC 
										LIMIT 0,18                                                      
									) t1
									LEFT JOIN products_category t2 ON t1.sku = t2.sku 
									AND site = "'.$site.'"  
									ORDER BY t1.page_views DESC
									LIMIT 18');
		$result = $query->result_array();
		$order_qry = "pt.page_views";	
		$dir_qry = "DESC";	

		// results data to $str so we get final results
		// ( this helps provide us with a fast result by using an IN query with only the sku's we need )
		$str = "";
		foreach($result as $r){
			$str.= '"'.addslashes($r['sku']).'",' ;
		}
		$str = rtrim($str,", ");
		$str = (!empty($str))? $str : '""';

		// get data from tables using the sku $str we just generated
		$this->db->cache_on();					
		$query = $this->db->query('SELECT 
									(pc.cost - pc.special_offer_cost) as discount,		
									p.id, p.sku, p.slug, p.brand, p.color, p.size, p.dimensions, p.net_weight, p.prod_weight, 
									p.stock, p.stock_on_hold, p.stock_minimum, p.stock_in_stock, p.featured, p.active, 
									pc.cost, pc.special_offer_cost, pc.special_offer_expires, pc.vat_rate,
									pt.title, pt.`text`, pt.meta_title, pt.meta_description, pt.dispatch_time, pt.page_views, pt.date_added 
									FROM products AS p 
									LEFT JOIN products_text AS pt ON p.sku = pt.sku AND pt.site IN ("'.$site.'")
									LEFT JOIN products_sites AS ps ON p.sku = ps.sku AND ps.site IN ("'.$site.'")
									LEFT JOIN products_cost AS pc ON ps.sku = pc.sku AND pc.site IN ("'.$site.'")
									WHERE p.`status` = 0
									AND ps.sku IN ('.$str.')
									ORDER BY '.$order_qry.' '.$dir_qry);
		$result = $query->result_array();
		
		return $result;
		
	}
	
	public function get_category_products($site = "", $menu_id = "")
	{
		$menu_id = (int)$menu_id;
		
		// set root category details
		$category = $this->uri->segment(1);
		$order = $this->uri->segment(2);
		$id = $this->uri->segment(3);
		$pagination_count_url_segment = 3;
		
		// get all url segments
		$url_segments = $this->uri->segment_array();
		
		// if first page we need to set a default value
		if(!is_numeric(end($url_segments))) {
			$order = "all";
		}
		
		// sort by filters
		$sort_by_array = array("all", "newest", "price-low-high", "price-high-low", "discount", "most-popular", "brand-a-z", "brand-z-a");

		// set category details
		if(!empty($url_segments[2]) && in_array($url_segments[2], $sort_by_array) || empty($url_segments[2]) && $order == "all") {
			$category = $this->uri->segment(1);
			$order = $this->uri->segment(2);
			$id = $this->uri->segment(3);
			$pagination_count_url_segment = 3;
		}
		
		// set sub category details
		else if(!empty($url_segments[3]) && in_array($url_segments[3], $sort_by_array) || empty($url_segments[3]) && $order == "all") {
			$category = $url_segments[1].'/'.$this->uri->segment(2);
			$order = $this->uri->segment(3);
			$id = $this->uri->segment(4);
			$pagination_count_url_segment = 4;
		}
		
		// set sub sub category details
		else if(!empty($url_segments[4]) && in_array($url_segments[4], $sort_by_array) || empty($url_segments[4]) && $order == "all") {
			$category = $url_segments[1].'/'.$url_segments[2].'/'.$this->uri->segment(3);
			$order = $this->uri->segment(4);
			$id = $this->uri->segment(5);
			$pagination_count_url_segment = 5;
		}
		
		// for sub category first pages
		if(!in_array(end($url_segments), $sort_by_array) && !is_numeric(end($url_segments))) {
			$order = "all";
		}

		// set order and id values 
		$order = (!empty($order)) ? $order : "all";
		$id = (!empty($id)) ? $id : 0;
		
		// set query/pagination variables
		$limit = 18;
		$results_per_page = 18;
		
		// get results count
		if(!empty($menu_id) && !empty($site)) {
			$this->db->cache_on();
			$query = $this->db->query('SELECT sku 
										FROM products_category 
										WHERE menu_id = '.$menu_id.' 
										AND site = "'.$site.'"');
			$result = $query->result_array();		
			$data['count'] = $result;	
		}
		
		// get results data
		switch($order) {
			case "all":		
				$this->db->cache_on();
				$query = $this->db->query('SELECT sku 
											FROM products_category 
											WHERE menu_id = '.$menu_id.' 
											AND site = "'.$site.'"
                                            ORDER BY id DESC 
											LIMIT '.(int)$id.','.(int)$limit);											
				$result = $query->result_array();
				$order_qry = "pc.id";	
				$dir_qry = "DESC";				
				break;
			case "price-low-high":
				$this->db->cache_on();			
				$query = $this->db->query('SELECT t1.sku, t1.cost
                                            FROM (
                                                    SELECT sku, cost
                                                    FROM products_cost
                                                    ORDER BY cost ASC 
													LIMIT '.(int)$id.','.(int)$limit.'                                                      
                                                 ) t1
                                            LEFT JOIN products_category t2 ON t1.sku = t2.sku 
											AND menu_id = '.$menu_id.' AND site = "'.$site.'"   
                                            ORDER BY t1.cost ASC');
				$result = $query->result_array();
				$order_qry = "pc.cost";	
				$dir_qry = "ASC";				
				break;
			case "price-high-low":		
				$this->db->cache_on();			
				$query = $this->db->query('SELECT t1.sku, t1.cost
                                            FROM (
                                                    SELECT sku, cost
                                                    FROM products_cost
                                                    ORDER BY cost DESC 
													LIMIT '.(int)$id.','.(int)$limit.'                                                      
                                                 ) t1
                                            LEFT JOIN products_category t2 ON t1.sku = t2.sku 
											AND menu_id = '.$menu_id.' AND site = "'.$site.'"   
                                            ORDER BY t1.cost DESC');
				$result = $query->result_array();
				$order_qry = "pc.cost";	
				$dir_qry = "DESC";
				break;
			case "discount":
				$this->db->cache_on();
				$query = $this->db->query('SELECT t1.sku, t1.discount
											FROM (
												SELECT sku, (cost - special_offer_cost) as discount
												FROM products_cost
												WHERE special_offer_cost > 0
												ORDER BY discount DESC 
												LIMIT '.(int)$id.','.(int)$limit.'                                                      
											) t1
											LEFT JOIN products_category t2 ON t1.sku = t2.sku 
											AND menu_id = '.$menu_id.' AND site = "'.$site.'"  
											ORDER BY t1.discount DESC');
				$result = $query->result_array();
				$order_qry = "discount";	
				$dir_qry = "DESC";
				break;
			case "most-popular":
				$this->db->cache_on();
				$query = $this->db->query('SELECT t1.sku
											FROM (
												SELECT sku, page_views
												FROM products_text
												ORDER BY page_views DESC 
												LIMIT '.(int)$id.','.(int)$limit.'                                                      
											) t1
											LEFT JOIN products_category t2 ON t1.sku = t2.sku 
											AND menu_id = '.$menu_id.' AND site = "'.$site.'"  
											ORDER BY t1.page_views DESC');
				$result = $query->result_array();
				$order_qry = "pt.page_views";	
				$dir_qry = "DESC";
				break;
			case "brand-a-z":
				$this->db->cache_on();
				$query = $this->db->query('SELECT t1.sku, t1.brand
											FROM (
												SELECT sku, brand
												FROM products
												WHERE brand != ""
												ORDER BY brand ASC 
												LIMIT '.(int)$id.','.(int)$limit.'                                                   
											) t1
											LEFT JOIN products_category t2 ON t1.sku = t2.sku 
											AND menu_id = '.$menu_id.' AND site = "'.$site.'"  
											ORDER BY t1.brand ASC');
				$result = $query->result_array();
				$order_qry = "p.brand";	
				$dir_qry = "ASC";
				break;
			case "brand-z-a":
				$this->db->cache_on();
				$query = $this->db->query('SELECT t1.sku, t1.brand
											FROM (
												SELECT sku, brand
												FROM products
												WHERE brand != ""
												ORDER BY brand DESC 
												LIMIT '.(int)$id.','.(int)$limit.'                                                      
											) t1
											LEFT JOIN products_category t2 ON t1.sku = t2.sku 
											AND menu_id = '.$menu_id.' AND site = "'.$site.'"  
											ORDER BY t1.brand DESC');
				$result = $query->result_array();
				$order_qry = "p.brand";	
				$dir_qry = "DESC";
				break;
			case "newest":
				$this->db->cache_on();
				$query = $this->db->query('SELECT t1.sku, t1.date_added
											FROM (
												SELECT sku, date_added
												FROM products_text
												ORDER BY date_added DESC 
												LIMIT '.(int)$id.','.(int)$limit.'                                                      
											) t1
											LEFT JOIN products_category t2 ON t1.sku = t2.sku 
											AND menu_id = '.$menu_id.' AND site = "'.$site.'"  
											ORDER BY t1.date_added DESC');
				$result = $query->result_array();
				$order_qry = "pt.date_added";	
				$dir_qry = "DESC";
				break;	
			default:
				$this->db->cache_on();
				$query = $this->db->query('SELECT sku 
											FROM products_category 
											WHERE menu_id = '.$menu_id.' 
											AND site = "'.$site.'"
                                            ORDER BY id ASC 
											LIMIT '.(int)$id.','.(int)$limit);											
				$result = $query->result_array();
				$order_qry = "pc.id";	
				$dir_qry = "ASC";
		}

		// results data to $str so we get final results
		// ( this helps provide us with a fast result by using an IN query with only the sku's we need )
		$str = "";
		foreach($result as $r){
			$str.= '"'.addslashes($r['sku']).'",' ;
		}
		$str = rtrim($str,", ");
		$str = (!empty($str))? $str : '""';

		// get data from tables using the sku $str we just generated
		$this->db->cache_on();
		$query = $this->db->query('SELECT 
									(pc.cost - pc.special_offer_cost) as discount,		
									p.id, p.sku, p.slug, p.brand, p.color, p.size, p.dimensions, p.net_weight, p.prod_weight, 
									p.stock, p.stock_on_hold, p.stock_minimum, p.stock_in_stock, p.featured, p.active, 
									pc.cost, pc.special_offer_cost, pc.special_offer_expires, pc.vat_rate,
									pt.title, pt.`text`, pt.meta_title, pt.meta_description, pt.dispatch_time, pt.page_views, pt.date_added,
									pimg.category, pimg.mouseover									
									FROM products AS p 
									LEFT JOIN products_text AS pt ON p.sku = pt.sku AND pt.site IN ("'.$site.'")
									LEFT JOIN products_sites AS ps ON p.sku = ps.sku AND ps.site IN ("'.$site.'")
									LEFT JOIN products_cost AS pc ON ps.sku = pc.sku AND pc.site IN ("'.$site.'")
									LEFT JOIN products_images AS pimg ON ps.sku = pimg.sku AND pimg.active = 1 AND pimg.category != "" 											
									WHERE p.`status` = 0
									AND ps.sku IN ('.$str.')
									ORDER BY '.$order_qry.' '.$dir_qry);
		$result = $query->result_array();
		$data['products'] = $result;

		// pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().$category.'/'.$order.'/';
		$config['total_rows'] = (!empty($data['count'])) ? count($data['count']) : 0;
		$config['per_page'] = $results_per_page;
		$config['uri_segment'] = $pagination_count_url_segment;
		$config['num_links'] = 2;
		$config['page_query_string'] = FALSE;
		$config['cur_tag_open'] = '<a class="current">';
		$config['cur_tag_close'] = '</a>';
		$config['next_link'] = '>';
		$config['prev_link'] = '<';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();			

		// sort by filter
		$data['sort_by'] = base_url().$category.'/';
		$data['sort_by_selected'] = $order;

		return $data;
	}	
	
	public function get_products_reviews($site="",$product_sku="")
	{
		$this->db->cache_on();
		$query = $this->db->query('SELECT *
														FROM products_reviews 
														WHERE site = "'.$site.'"
														AND sku = "'.$product_sku.'"
														AND active = 1
														AND status = 0
														AND approved = 1
														ORDER BY date_submitted DESC ');
									
		$rows = $query->result_array();
	
		return $rows;
	}	
	
	public function get_related_attribute_products($site, $product_sku=""){
		$this->db->cache_off();
		$query = $this->db->query('SELECT *
														FROM products_related as pr
														WHERE pr.site = "'.$site.'"
														AND pr.sku = "'.$product_sku.'"
														AND pr.active = 1
														AND pr.status = 0
														ORDER BY pr.id ASC ');
									
		$rows = $query->result_array();

		if(!empty($rows)) {
			
			// start: create a string of SKUs as this is the fastest way to search
			$str = "";
			foreach($rows as $r){
				$str.= '"'.addslashes($r['sku_related']).'",' ;
			}
			$str = rtrim($str,", ");
			$str = (!empty($str))? $str : '""';
			// end: create a string of SKUs as this is the fastest way to search			
		
			$this->db->cache_off();
			$query = $this->db->query('SELECT 
										p.id, p.sku, p.slug, p.brand, p.color, p.size, p.dimensions, p.net_weight, p.prod_weight, p.product_attribute, p.product_attribute_option,
										p.stock, p.stock_on_hold, p.stock_minimum, p.stock_in_stock, p.featured, p.active, 
										pc.cost, pc.special_offer_cost, pc.special_offer_expires, pc.vat_rate,
										pt.title, pt.`text`, pt.meta_title, pt.meta_description, pt.dispatch_time, pt.short_description 
										FROM products AS p 
										LEFT JOIN products_text AS pt ON p.sku = pt.sku AND pt.site = "'.$site.'" 
										LEFT JOIN products_sites AS ps ON p.sku = ps.sku AND ps.site = "'.$site.'"
										LEFT JOIN products_cost AS pc ON ps.sku = pc.sku AND pc.site = "'.$site.'" 
										WHERE ps.site IN ("'.$site.'")
										AND p.active = 1
										AND p.`status` = 0
										AND p.sku IN ('.$str.')');
										
			$rows = $query->result_array();	
		
		}
		else {
			$rows = "";
		}
		
		return $rows;		
		
	}
	
	public function add_notification_of_item_back_in_stock () {
		
		// get site id
		$site = $this->config->item('template'); // config	
	
		// get review data
		$data['back_in_of_stock_notification_email'] =  ( ! empty( $_POST['back_in_of_stock_notification_email'] ) ) ? $_POST['back_in_of_stock_notification_email'] : "";				
		$data['back_in_of_stock_notification_sku'] =  ( ! empty( $_POST['back_in_of_stock_notification_sku'] ) ) ? $_POST['back_in_of_stock_notification_sku'] : "";	

		// make sure all field data is provided
		if( empty($data['back_in_of_stock_notification_email']) || empty($data['back_in_of_stock_notification_sku']) ) {
			echo "Please enter your email address";
			return;
		}

		// load spam helper
		$this->load->helper('spam_helper');
		
		// check for spam in Name field
		if(spam_filter($data['back_in_of_stock_notification_email'])) {
			echo "Spam found in Email field";
			return;			
		}
		
		// validate user email
		if ( ! filter_var($data['back_in_of_stock_notification_email'], FILTER_VALIDATE_EMAIL)) {
			echo "Invalid email address";
			return;					
		}

		// if we have got this far then the form data is good to add to database
		// ( mark the review as pending so it needs admin approval before its displayed on the website )
		$ins = array(
				'site' => $site,
				'user_email' => $data['back_in_of_stock_notification_email'],				
				'sku' => $data['back_in_of_stock_notification_sku'],		
				'date_added' => date("Y-m-d H:i:s")						
		);

		$this->db->insert('products_back_in_stock_notifications', $ins);
		
		// return success
		echo "SUCCESS";
	}
	
	public function get_product_by_sku( $sku = "" )
	{
		// get site id
		$site = $this->config->item('template'); // config	

		// get sku
		$sku =  ( ! empty( $_POST['sku'] ) ) ? $_POST['sku']  : $sku;			
		
		$this->db->cache_off();
		$query = $this->db->query('SELECT 
									p.id, p.sku, p.slug, p.brand, p.color, p.size, p.dimensions, p.net_weight, p.prod_weight, p.product_attribute, p.product_attribute_option,
									p.stock, p.stock_on_hold, p.stock_minimum, p.stock_in_stock, p.featured, p.active, 
									pc.cost, pc.special_offer_cost, pc.special_offer_expires, pc.vat_rate,
									pt.title, pt.`text`, pt.meta_title, pt.meta_description, pt.dispatch_time, pt.short_description 
									FROM products AS p 
									LEFT JOIN products_text AS pt ON p.sku = pt.sku AND pt.site = "'.$site.'" 
									LEFT JOIN products_sites AS ps ON p.sku = ps.sku AND ps.site = "'.$site.'"
									LEFT JOIN products_cost AS pc ON ps.sku = pc.sku AND pc.site = "'.$site.'" 								
									WHERE ps.site IN ("'.$site.'")
									AND p.active = 1
									AND p.`status` = 0
									AND p.sku = "'.$sku.'"');
									
		$rows = $query->result_array();

		return $rows;
	}	
	
	function get_product_images ( $sku = "" ) {
		$this->db->cache_off();
		$query = $this->db->query('SELECT *
														FROM products_images
														WHERE active = 1
														AND `status` = 0
														AND sku = "'.$sku.'"');
									
		$rows = $query->result_array();
	
		return (!empty($rows)) ? $rows : array();			
	}		
	
	function get_product_mouseover_image ( $sku = "" ) {
		$this->db->cache_off();
		$query = $this->db->query('SELECT mouseover
														FROM products_images
														WHERE mouseover != ""
														AND active = 1
														AND `status` = 0
														AND sku = "'.$sku.'"');
									
		$rows = $query->result_array();

		return $rows;			
	}	
	
	public function get_related_product_images()
	{
		// get site id
		$site = $this->config->item('template'); // config	

		// get sku
		$sku =  ( ! empty( $_POST['sku'] ) ) ? $_POST['sku']  : "";	
		
		// get data from table
		$this->db->cache_off();
		$query = $this->db->query('SELECT *
														FROM products_images
														WHERE active = 1
														AND `status` = 0
														AND sku = "'.$sku.'"');
									
		$rows = $query->result_array();
		
		if(!empty($rows)) {
			// product category image							
				foreach($rows as $product_images) {
					if(!empty($product_images['category'])){ // category
					?>
					<li data-thumb="<?php echo base_url().$product_images['category'];?>" data-src="<?php echo base_url().$product_images['category'];?>"  class="main-product-image">
						<img alt="" src="<?php echo base_url().$product_images['category'];?>" style="height:365px;width:auto;" />
					</li>
					<?php 
					}
					if(!empty($product_images['mouseover'])){ // hover
					?>
					<li data-thumb="<?php echo base_url().$product_images['mouseover'];?>" data-src="<?php echo base_url().$product_images['mouseover'];?>"  class="main-product-image">
						<img alt="" src="<?php echo base_url().$product_images['mouseover'];?>" style="height:365px;width:auto;" />
				    </li>
					<?php 
					}								
				}
				// product other  images
				foreach($rows as $product_images) {
					if(!empty($product_images['image'])){ // other
					?>
					<li data-thumb="<?php echo base_url().$product_images['thumbnail'];?>" data-src="<?php echo base_url().$product_images['image'];?>"  class="main-product-image">
						<img alt="" src="<?php echo base_url().$product_images['image'];?>" style="height:365px;width:auto;" />
					</li>
					<?php 
					}
				}
		}

	}	
		
}
