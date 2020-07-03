<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends CI_Model {
	
	public function get_category_products($site = "")
	{
		// get keyword
		$keyword = $this->uri->segment(3);
		$keyword = (empty($keyword)) ? "-" : $keyword;
		$keyword = (!empty($_POST['keyword'])) ? $_POST['keyword'] : $keyword;
		$keyword = urldecode ($keyword);			
		$data['keyword'] = ($keyword == "-") ? "" : $keyword;
		$data['search'] = ($keyword == "-") ? "" : ' AND ( t2.sku LIKE "%'.$this->db->escape_like_str($keyword).'%" OR t2.title LIKE "%'.$this->db->escape_like_str($keyword).'%" ) ';
		$data['extra_search'] = "";
		$data['extra_table'] = "";
		
		// set root category details
		// category
		$category = $this->uri->segment(1);
		// order
		$order = $this->uri->segment(2);
		// gender
		$gender = $this->uri->segment(4);
		if(!empty($gender) && $gender != "-") {
			$data['extra_table'].= " LEFT JOIN products AS t1 ON t2.sku = t1.sku ";
			$data['extra_search'].= " AND t1.gender = '".$this->db->escape_like_str($gender)."' ";
		}
		else {
			$gender = "-";
		}
		// department
		$dept = $this->uri->segment(5);
		if(!empty($dept) && $dept != "-") {
			$dept = urldecode ($dept);
			$data['extra_table'].= " LEFT JOIN products_category AS t4 ON t2.sku = t4.sku ";
			$data['extra_search'].= " AND t4.menu_id = '".$this->db->escape_like_str($dept)."' ";
		}
		else {
			$dept = "-";
		}		
		// brands
		$brands = $this->uri->segment(6);
		if(!empty($brands) && $brands != "-") {
			$brands = urldecode ($brands);		
			$data['extra_table'].= " LEFT JOIN products AS t7 ON t2.sku = t7.sku ";
			$data['extra_search'].= " AND t7.brand LIKE '%".$this->db->escape_like_str($brands)."%' ";
		}
		else {
			$brands = "-";
		}
		// cost
		$cost = $this->uri->segment(7);	
		if(!empty($cost) && $cost != "-") {
			$data['extra_table'].= " LEFT JOIN products_cost AS t5 ON t2.sku = t5.sku ";			
			switch($cost) {
				case 1: // less than 10
					$data['extra_search'].= " AND ( t5.cost BETWEEN 0 AND 9.99 ) ";
					break;
				case 2: // 10 - 20
					$data['extra_search'].= " AND ( t5.cost BETWEEN 10 AND 19.99 ) ";
					break;
				case 3: // 20 - 30
					$data['extra_search'].= " AND ( t5.cost BETWEEN 20 AND 29.99 ) ";
					break;
				case 4: // 30 - 40
					$data['extra_search'].= " AND ( t5.cost BETWEEN 30 AND 39.99 ) ";
					break;	
				case 5: // 40 - 60
					$data['extra_search'].= " AND ( t5.cost BETWEEN 40 AND 59.99 ) ";
					break;
				case 6: // 60 - 80
					$data['extra_search'].= " AND ( t5.cost BETWEEN 60 AND 79.99 ) ";
					break;
				case 7: // 80 - 100
					$data['extra_search'].= " AND ( t5.cost BETWEEN 80 AND 99.99 ) ";
					break;
				case 8: // 100 - 250
					$data['extra_search'].= " AND ( t5.cost BETWEEN 100 AND 249.99 ) ";
					break;
				case 9: // 250+
					$data['extra_search'].= " AND ( t5.cost > 250 OR t5.special_offer_cost > 250 ) ";
					break;
			}
		}
		else {
			$cost = "-";
		}

		// id & pagination 
		$id = $this->uri->segment(8);
		$pagination_count_url_segment = 8;
		
		// get all url segments
		$url_segments = $this->uri->segment_array();
		
		// set order and id values 
		$order = (!empty($order)) ? $order : "all";
		$id = (!empty($id)) ? $id : 0;
		
		// default variables
		$limit = 18;
		$per_page = 18;
		
		// get results count
		$this->db->cache_off();
		$query = $this->db->query('SELECT t2.sku
									FROM products_text AS t2
									LEFT JOIN products as p6 ON t2.sku = p6.sku
									'.$data['extra_table'].'
									WHERE 1 = 1
									'.$data['search'].'
									'.$data['extra_search'].'
									AND p6.restrict_direct_access = 0
									AND t2.site = "'.$site.'"');
		$result = $query->result_array();		
		$data['count'] = $result;	
		
		if(!empty($data['count'])) {
		
			// get results data
			switch($order) {
				case "all":		
					$this->db->cache_on();
					$query = $this->db->query('SELECT t2.sku
												FROM products_text AS t2
												'.$data['extra_table'].'
												WHERE 1 = 1
												'.$data['search'].'
												'.$data['extra_search'].'
												AND t2.site = "'.$site.'"
												ORDER BY t2.id DESC 
												LIMIT '.(int)$id.','.(int)$limit);											
					$result = $query->result_array();
					$order_qry = "pc.id";	
					$dir_qry = "DESC";				
					break;
				case "price-low-high":	
					$this->db->cache_on();			
					$query = $this->db->query('SELECT t2.sku
												FROM products_text AS t2
												LEFT JOIN products_cost AS t3 ON t2.sku = t3.sku
												'.$data['extra_table'].'
												WHERE 1 = 1
												'.$data['search'].'
												'.$data['extra_search'].'
												AND t2.site = "'.$site.'" 
												ORDER BY t3.cost ASC
												LIMIT '.(int)$id.','.(int)$limit);
					$result = $query->result_array();
					$order_qry = "pc.cost";	
					$dir_qry = "ASC";				
					break;
				case "price-high-low":
					$this->db->cache_on();					
					$query = $this->db->query('SELECT t2.sku
												FROM products_text AS t2
												LEFT JOIN products_cost AS t3 ON t2.sku = t3.sku
												'.$data['extra_table'].'
												WHERE 1 = 1
												'.$data['search'].'
												'.$data['extra_search'].'
												AND t2.site = "'.$site.'" 
												ORDER BY t3.cost DESC
												LIMIT '.(int)$id.','.(int)$limit);
					$result = $query->result_array();
					$order_qry = "pc.cost";	
					$dir_qry = "DESC";
					break;
				case "discount":
					$this->db->cache_on();					
					$query = $this->db->query('SELECT t2.sku, (t3.cost - t3.special_offer_cost) AS discount
												FROM products_text t2
												'.$data['extra_table'].'
												WHERE 1 = 1
												'.$data['search'].' 
												'.$data['extra_search'].'
												AND t2.site = "'.$site.'"  
												ORDER BY discount DESC 
												LIMIT '.(int)$id.','.(int)$limit);
					$result = $query->result_array();
					$order_qry = "discount";	
					$dir_qry = "DESC";
					break;
				case "most-popular":
					$this->db->cache_on();		
					$query = $this->db->query('SELECT t2.sku
												FROM products_text AS t2
												'.$data['extra_table'].'
												WHERE 1 = 1
												'.$data['search'].'
												'.$data['extra_search'].'
												AND t2.site = "'.$site.'" 
												ORDER BY t2.page_views DESC
												LIMIT '.(int)$id.','.(int)$limit);	
					$result = $query->result_array();
					$order_qry = "pt.page_views";	
					$dir_qry = "DESC";
					break;
				case "brand-a-z":
					$this->db->cache_on();		
					$query = $this->db->query('SELECT t2.sku
												FROM products_text AS t2
												LEFT JOIN products AS t1 ON t2.sku = t1.sku
												'.$data['extra_table'].'
												WHERE 1 = 1
												'.$data['search'].'
												'.$data['extra_search'].'
												AND t2.site = "'.$site.'" 
												ORDER BY t1.brand ASC
												LIMIT '.(int)$id.','.(int)$limit);			
					$result = $query->result_array();
					$order_qry = "p.brand";	
					$dir_qry = "ASC";
					break;
				case "brand-z-a":
					$this->db->cache_on();		
					$query = $this->db->query('SELECT t2.sku
												FROM products_text AS t2
												LEFT JOIN products AS t1 ON t2.sku = t1.sku
												'.$data['extra_table'].'												
												WHERE 1 = 1
												'.$data['search'].'
												'.$data['extra_search'].'
												AND t2.site = "'.$site.'" 
												ORDER BY t1.brand DESC
												LIMIT '.(int)$id.','.(int)$limit);		
					$result = $query->result_array();
					$order_qry = "p.brand";	
					$dir_qry = "DESC";
					break;
				case "newest":
					$this->db->cache_on();					
					$query = $this->db->query('SELECT t2.sku
												FROM products_text AS t2
												'.$data['extra_table'].'
												WHERE 1 = 1
												'.$data['search'].'
												'.$data['extra_search'].'
												AND t2.site = "'.$site.'" 
												ORDER BY t2.date_added DESC
												LIMIT '.(int)$id.','.(int)$limit);											
					$result = $query->result_array();
					$order_qry = "pt.date_added";	
					$dir_qry = "DESC";
					break;	
				default:
					$this->db->cache_on();		
					$query = $this->db->query('SELECT t2.sku
												FROM products_text AS t2
												'.$data['extra_table'].'
												WHERE 1 = 1
												'.$data['search'].'
												'.$data['extra_search'].'
												AND t2.site = "'.$site.'"
												ORDER BY t2.id DESC 
												LIMIT '.(int)$id.','.(int)$limit);											
					$result = $query->result_array();
					$order_qry = "pc.id";	
					$dir_qry = "DESC";
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
			$this->db->cache_off();
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
										AND p.restrict_direct_access = 0
										AND ps.sku IN ('.$str.')
										ORDER BY '.$order_qry.' '.$dir_qry);
			$result = $query->result_array();
			$data['products'] = $result;
		}

		// pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'search/'.$order.'/'.urlencode($keyword).'/'.$gender.'/'.$dept.'/'.$brands.'/'.$cost.'/';
		$config['total_rows'] = (!empty($data['count'])) ? count($data['count']) : 0;
		$config['per_page'] = $per_page;
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
	
}
