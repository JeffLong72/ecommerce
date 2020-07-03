<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter_model extends CI_Model {
	
	function product_categories($site = "", $sku = "") {
		
		// url segments
		$keyword = (!empty($_POST['keyword'])) ? $_POST['keyword'] : "-";
		$category = $this->uri->segment(1);
		$order = $this->uri->segment(2,'all');			
		$keyword = $this->uri->segment(3,$keyword);
		$keyword = (is_numeric($keyword)) ? "-" : $keyword;
		$gender = $this->uri->segment(4,'-');
		$dept = $this->uri->segment(5,'-');
		$brands = $this->uri->segment(6,'-');
		$cost = $this->uri->segment(7,'-');

		$category_menu = "<p>No menu found</p>";	
	
		$this->db->cache_on();
		$query = $this->db->query('SELECT id, category_name, url 
									FROM menu 
									WHERE level = 0
									AND active = 1
									AND site = "'.$site.'"
									ORDER BY menu_order ASC');
		$level_zero = $query->result_array();

		if(!empty($level_zero)) {

				$category_menu = "";
				
				foreach($level_zero as $zero) {
					
					$this->db->cache_on();
					$query = $this->db->query('SELECT id, category_name, url
												FROM menu WHERE level = 1
												AND active = 1
												AND parent_id = '.$zero['id'].' 
												AND site = "'.$site.'"');
					$level_one = $query->result_array();
					
					$this->db->cache_on();					
					$query = $this->db->query('SELECT t1.menu_id, count(*) AS total
												FROM products_category t1
												LEFT JOIN menu as t2 ON t1.menu_id = t2.id
												WHERE t1.menu_id = '.$zero['id'].'
												AND t1.site = "'.$site.'"
												GROUP BY t1.menu_id');
					$count = $query->result_array();
					$count = (!empty($count)) ? $count[0]['total'] : "0";
					
					// $sub_url = base_url()."search/".$order."/".$keyword."/".$gender."/".$zero['id']."/".$brands."/".$cost;
					$sub_url = base_url()."search/".$order."/".$keyword."/-/".$zero['id']."/-/-";

					if(empty($level_one)) {
						
							$hide_menu_option  =  ( $category != "search" && $dept != "-" && $dept == $zero['id'] ) ? "" : "filter_dept_hidden";	
							$hide_menu_option = ( $dept == "-" ) ? "" : $hide_menu_option;							
							$category_menu.= "<div class='filter_dept_level filter_hover ".$hide_menu_option."'><a href='".$sub_url."'>".$zero['category_name']." <span class='filter_count'>(".$count.")</span></a></div>";
					}
					else {
						
							$hide_menu_option  =  ( $dept != "-" && $dept == $zero['id'] ) ? "" : "filter_dept_hidden";
							$hide_menu_option = ( $dept == "-" ) ? "" : $hide_menu_option;
							$category_menu.= "<div class='filter_dept_level filter_hover ".$hide_menu_option."' ><a class='data-tag' data-tag='filters-department-level0-".$zero['category_name']."' href='".$sub_url."'>".$zero['category_name']." <span class='filter_count'>(".$count.")</span></a></div>";
						
						if(!empty($level_one)) {
							foreach($level_one as $one) {
								
								$this->db->cache_on();
								$query = $this->db->query('SELECT id, category_name, url, parent_id
															FROM menu 
															WHERE level = 2 
															AND active = 1
															AND parent_id = '.$one['id'].' 
															AND site = "'.$site.'"');
								$level_two = $query->result_array();
								
								$this->db->cache_on();					
								$query = $this->db->query('SELECT t1.menu_id, count(*) AS total
															FROM products_category t1
															LEFT JOIN menu AS t2 ON t1.menu_id = t2.id
															WHERE t1.menu_id = '.$one['id'].'
															AND t1.site = "'.$site.'"
															GROUP BY t1.menu_id');
								$count = $query->result_array();
								$count = (!empty($count)) ? $count[0]['total'] : "0";
								
								// $sub_url = base_url()."search/".$order."/".$keyword."/".$gender."/".$one['id']."/".$brands."/".$cost;
								$sub_url = base_url()."search/".$order."/".$keyword."/-/".$one['id']."/-/-";
								
								if(!empty($count)) {
									$hide_menu_option  =  ( $dept != "-" && $dept == $one['id'] ) ? "" : "filter_dept_hidden";
									$hide_menu_option = ( $dept == "-" ) ? "" : $hide_menu_option;
									$category_menu.= "<div class='filter_dept_level1 filter_hover ".$hide_menu_option."'><a class='data-tag' data-tag='filters-department-level1-".$one['category_name']."' href='".$sub_url."'>".$one['category_name']." <span class='filter_count'>(".$count.")</span></a></div>";;
								}
								
								if(!empty($level_two)) {
									foreach($level_two as $two) {
										
										$this->db->cache_on();
										$query = $this->db->query('SELECT t1.menu_id, count(*) AS total
																	FROM products_category t1
																	LEFT JOIN menu AS t2 ON t1.menu_id = t2.id
																	WHERE t1.menu_id = '.$two['id'].'
																	AND t1.site = "'.$site.'"
																	GROUP BY t1.menu_id');
										$count = $query->result_array();
										$count = (!empty($count)) ? $count[0]['total'] : "0";
										
										// $sub_url = base_url()."search/".$order."/".$keyword."/".$gender."/".$two['id']."/".$brands."/".$cost;
										$sub_url = base_url()."search/".$order."/".$keyword."/-/".$two['id']."/-/-";
										
										if(!empty($count)) {
											$hide_menu_option  =  ( $dept != "-" && $dept == $two['id'] ) ? "" : "filter_dept_hidden";
											$hide_menu_option = ( $dept == "-" ) ? "" : $hide_menu_option;											
											$category_menu.= "<div class='filter_dept_level2 filter_hover ".$hide_menu_option."'><a class='data-tag' data-tag='filters-department-level2-".$two['category_name']."' href='".$sub_url."'>".$two['category_name']." <span class='filter_count'>(".$count.")</span></a></div>";;
										}
									}			
								}						
							}		
						}					
					}
				}
		
		}
		
		return $category_menu;
	}

	function product_brands($site = "", $sku = "") {
		
		// url segments
		$keyword = (!empty($_POST['keyword'])) ? $_POST['keyword'] : "-";
		$category = $this->uri->segment(1);
		$order = $this->uri->segment(2,'all');			
		$keyword = $this->uri->segment(3,$keyword);
		$keyword = (is_numeric($keyword)) ? "-" : $keyword;		
		$gender = $this->uri->segment(4,'-');
		$dept = $this->uri->segment(5,'-');
		$brands = $this->uri->segment(6,'-');
		$cost = $this->uri->segment(7,'-');

		$sub_url = base_url()."search/".$order."/".$keyword."/".$gender."/".$dept."/";		
		
		$brands_menu = "";
		
		$this->db->cache_on();					
		$query = $this->db->query('SELECT sku
									FROM products_text
									WHERE site = "en"
									AND active = 1');											
		$result = $query->result_array();

		$str = "";
		foreach($result as $r){
			$str.= '"'.addslashes($r['sku']).'",' ;
		}
		$str = rtrim($str,", ");
		$str = (!empty($str))? $str : '""';	
		
		// is category in url? 
		$inc_dept = (!empty($dept) && $dept !="-" ) ? " AND t4.menu_id = '".$dept."' " : "";
		
		// is brand in url?
		// if so , lets edit query below to include the brand name too 
		// ( as the user is looking for something brand specific )
		$brands = urldecode ($brands);
		$inc_brands = (!empty($brands) && $brands !="-" ) ? " AND brand LIKE '".$this->db->escape_like_str($brands)."' " : "";
		
		// is price in url?
		// if so , lets edit query below to include the price too 
		// ( as the user is looking for something price specific	)
		$inc_cost = "";
		switch($cost) {
			case 1:
				$inc_cost = " AND t2.cost BETWEEN 0.00 AND 9.99 ";
				break;
			case 2:
				$inc_cost = " AND t2.cost BETWEEN 10.00 AND 19.99 ";
				break;
			case 3:
				$inc_cost = " AND t2.cost BETWEEN 20.00 AND 29.99 ";
				break;										
			case 4:
				$inc_cost = " AND t2.cost BETWEEN 30.00 AND 39.99 ";
				break;
			case 5:
				$inc_cost = " AND t2.cost BETWEEN 40.00 AND 59.99";
				break;
			case 6:
				$inc_cost = " AND t2.cost BETWEEN 60.00 AND 79.99 ";
				break;		
			case 7:
				$inc_cost = " AND t2.cost BETWEEN 80.00 AND 99.99 ";
				break;
			case 8:
				$inc_cost = " AND t2.cost BETWEEN 100.00 AND 249.99  ";
				break;
			case 9:
				$inc_cost = " AND t2.cost >= 250 ";
				break;	
			default:
				$inc_cost = " ";										
		}				
		
		$this->db->cache_on();							
		$query = $this->db->query('SELECT t1.brand, count(*) AS total 
									FROM products AS t1
									LEFT JOIN products_cost AS t2 ON t1.sku = t2.sku
									LEFT JOIN products_text AS t3 ON t1.sku = t3.sku
									LEFT JOIN products_category as t4 ON t1.sku = t4.sku
									WHERE t1.sku IN ('.$str.')
									'.$inc_dept.'											
									'.$inc_brands.'
									'.$inc_cost.'							
									AND t3.site = "'.$site.'"
									AND t1.restrict_direct_access = 0
									GROUP BY t1.brand 
									ORDER BY t1.brand ASC');											
		$result = $query->result_array();

		if(!empty($result)) {
			foreach($result as $brands) {
				if(!empty($brands['brand'])) {
				$brands_menu.= "<div class='filter_brands_level filter_hover'><a class='data-tag' data-tag='filters-brands-".$brands['brand']."' href='".$sub_url.urlencode(strtolower($brands['brand']))."/".$cost."'>".$brands['brand']." <span class='filter_count'>(".$brands['total'].")</a></div>";
				}
			}
		}
		
		return $brands_menu;	
	}
	
	function product_cost($site = "", $total_products= "") {
		
		$currency = "&pound;";
		
		// url segments
		$keyword = (!empty($_POST['keyword'])) ? $_POST['keyword'] : "-";
		$category = $this->uri->segment(1);
		$order = $this->uri->segment(2,'all');		
		$keyword = $this->uri->segment(3,$keyword);
		$keyword = (is_numeric($keyword)) ? "-" : $keyword;		
		$gender = $this->uri->segment(4,'-');
		$dept = $this->uri->segment(5,'-');
		$brands = $this->uri->segment(6,'-');
		$cost = $this->uri->segment(7,'-');

		$sub_url = base_url()."search/".$order."/".$keyword."/".$gender."/".$dept."/".$brands."/";
		
		// is category in url? 
		$inc_dept = ($dept !="-" ) ? " AND t3.menu_id = '".$dept."' " : "";		
		 
		// is price in url?
		// if so , lets edit query below to include the price too 
		// ( as the user is looking for something price specific	)
		$inc_cost = "";
		switch($cost) {
			case 1:
				$inc_cost = " SUM(Case When t1.cost < 10 Then 1 Else 0 End) as `Less than |10` ";
				break;
			case 2:
				$inc_cost = " SUM(Case When t1.cost between 10 and 19.99 Then 1 Else 0 End) as `|10 to |20`  ";
				break;
			case 3:
				$inc_cost = " SUM(Case When t1.cost between 20 and 29.99 Then 1 Else 0 End) as `|20 to |30` ";
				break;										
			case 4:
				$inc_cost = " SUM(Case When t1.cost between 30 and 39.99 Then 1 Else 0 End) as `|30 to |40` ";
				break;
			case 5:
				$inc_cost = " SUM(Case When t1.cost between 40 and 59.99 Then 1 Else 0 End) as `|40 to |60` ";
				break;
			case 6:
				$inc_cost = " SUM(Case When t1.cost between 60 and 79.99 Then 1 Else 0 End) as `|60 to |80` ";
				break;		
			case 7:
				$inc_cost = " SUM(Case When t1.cost between 80 and 99.99 Then 1 Else 0 End) as `|80 to |100` ";
				break;
			case 8:
				$inc_cost = " SUM(Case When t1.cost between 100 and 249.99 Then 1 Else 0 End) as `|100 to |250`  ";
				break;
			case 9:
					$inc_cost = " SUM(Case When t1.cost > 250 Then 1 Else 0 End) as `|250+` ";
					break;	
			default:
				$inc_cost = " SUM(Case When t1.cost < 10 Then 1 Else 0 End) as `Less than |10`,      
								SUM(Case When t1.cost between 10 and 19.99 Then 1 Else 0 End) as `|10 to |20`,   
								SUM(Case When t1.cost between 20 and 29.99 Then 1 Else 0 End) as `|20 to |30`,  
								SUM(Case When t1.cost between 30 and 39.99 Then 1 Else 0 End) as `|30 to |40`, 
								SUM(Case When t1.cost between 40 and 59.99 Then 1 Else 0 End) as `|40 to |60`,  
								SUM(Case When t1.cost between 60 and 79.99 Then 1 Else 0 End) as `|60 to |80`,
								SUM(Case When t1.cost between 80 and 99.99 Then 1 Else 0 End) as `|80 to |100`, 
								SUM(Case When t1.cost between 100 and 249.99 Then 1 Else 0 End) as `|100 to |250`,    
								SUM(Case When t1.cost > 250 Then 1 Else 0 End) as `|250+` ";										
		}
		
		// is brand in url?
		// if so , lets edit query below to include the brand name too 
		// ( as the user is looking for something brand specific )		
		$brands = urldecode ($brands);
		$inc_brands = ($brands != "-") ? " AND t2.brand LIKE '%".$this->db->escape_like_str($brands)."%' " : "";
		
		$this->db->cache_on();					
		$query = $this->db->query('SELECT '.$inc_cost.'
									FROM products_cost AS t1
									LEFT JOIN products as t2 ON t1.sku = t2.sku
									LEFT JOIN products_category as t3 ON t1.sku = t3.sku
									WHERE t1.site="'.$site.'"
									'.$inc_dept.'	
									'.$inc_brands.'
									AND t2.restrict_direct_access = 0
									AND t1.active = 1');
		$result = $query->result_array();	

		$x = 1;
		$product_costs = "";
		foreach($result as $r) {
			foreach($r as $key => $value) {
					$x = ($cost != "-") ? $cost : $x;
					if( $value > 0 ) {
						
						if(empty($_SESSION['site']['currency_id'])) { // on first page load only
							$_SESSION['site']['currency_id'] = 1;		
							$_SESSION['site']['currency_html'] = "£";							
							$_SESSION['site']['currency_rate'] = 1;
							$_SESSION['site']['currency_text'] = "GBP";				
						}						
						
						$currency = $_SESSION['site']['currency_html'];

						// replace 250
						$product_cost = ( 250 * $_SESSION['site']['currency_rate'] );
						$key = str_replace ("|250", "-".$product_cost, $key);						
						// replace 100
						$product_cost = ( 100 * $_SESSION['site']['currency_rate'] );
						$key = str_replace ("|100", "-".$product_cost, $key);
						// replace 80
						$product_cost = ( 80 * $_SESSION['site']['currency_rate'] );
						$key = str_replace ("|80", "-".$product_cost, $key);						
						// replace 60
						$product_cost = ( 60 * $_SESSION['site']['currency_rate'] );
						$key = str_replace ("|60", "-".$product_cost, $key);						
						// replace 40
						$product_cost = ( 40 * $_SESSION['site']['currency_rate'] );
						$key = str_replace ("|40", "-".$product_cost, $key);							
						// replace 30
						$product_cost = ( 30 * $_SESSION['site']['currency_rate'] );
						$key = str_replace ("|30", "-".$product_cost, $key);							
						// replace 20
						$product_cost = ( 20 * $_SESSION['site']['currency_rate'] );
						$key = str_replace ("|20", "-".$product_cost, $key);							
						// replace 10
						$product_cost = ( 10 * $_SESSION['site']['currency_rate'] );
						$key = str_replace ("|10 ", "-".$product_cost." ", $key);
						$key = str_replace ("|10", "-".$product_cost." ", $key);	
						// replace any placeholders
						$key = str_replace ("-", "|", $key);							

						$product_costs.= "<div class='filter_cost_level filter_hover'><a class='data-tag' data-tag='filters-price-from-".str_replace("|", $currency, $key)."' href='".$sub_url.$x."'>".str_replace("|", $currency, $key)." <span class='filter_count'>(".$value.")</span></a></div>";
					}
					$x++;							
			}
		}

		return $product_costs;
	}
}
