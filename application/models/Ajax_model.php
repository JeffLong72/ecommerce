<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_model extends CI_Model {
	
	public function get_products($site, $keyword = "")
	{
		$this->db->cache_on();

		$keyword_search = (!empty($keyword)) ? ' AND t1.title LIKE "%'.$this->db->escape_like_str($keyword).'%" '  : '';

		if(!empty($keyword_search)) {
			$query = $this->db->query('SELECT t3.slug, t1.id, t1.title, t2.image 
										FROM products_text AS t1
										LEFT JOIN products_images AS t2 ON t1.sku = t2.sku
										LEFT JOIN products AS t3 ON t1.sku = t3.sku										
										WHERE site = "'.$site.'"
										'.$keyword_search.'
										AND t3.restrict_direct_access = 0
										AND t1.active = 1
										LIMIT 10');
			$result = $query->result_array();

			if(!empty($result)) {
				$str = '[';
				$str.= '{';
				$str.= '"header": "Related Products"';
				$str.= '},';
				foreach($result as $product) {
					$str.= '{';
					$str.= '"header": "",';
					$str.= '"id": "'.strtolower($product['id']).'",';
					$str.= '"value": "'.htmlspecialchars(strtolower($product['title'])).'",';
					$str.= '"label": "'.htmlspecialchars($product['title']).'",';
					$str.= '"icon": "'.base_url().'assets/images/10.jpg",';
					$str.= '"url": "'.$product['slug'].'.html"';
					$str.= '},';
				}
				$str.= "]";
				$str = str_replace(",]", "]", $str);
				echo $str;
			}
			else {
				$str = '[';
				$str.= '{';
				$str.= '"header": "No products found ..."';
				$str.= '},';
				$str.= "]";
				$str = str_replace(",]", "]", $str);
				echo $str;
			}

			// add keyword to log search keyword table

            // get user id ( if user not logged in, use 0 as value
            $user_id = (!empty($_SESSION['customer']['details']['id'])) ? $_SESSION['customer']['details']['id'] : 0;
			// $userid = ( !empty( session details for user id) ) ? " user id " : 0;

            $ins = array(
                'site' => $site,
                'userid' => $user_id,
                'keyword' => $keyword,
                'date_added' => date("Y-m-d H:i:s")

            );
            $this->db->insert('log_search_terms', $ins);
		}
	}
}
