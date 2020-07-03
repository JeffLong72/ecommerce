<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_vouchers_model extends CI_Model {

	function get_product_brands ( $site = "" )
	{
		$this->db->cache_off();
		$query = $this->db->query('SELECT t1.brand 
														FROM products as t1
														LEFT JOIN products_sites AS t2 on t1.sku = t2.sku
														WHERE t2.site = "'.$site.'"
														AND t1.active = 1
														AND t2.active = 1
														AND t1.brand != ""
														GROUP BY t1.brand');
		$rows = $query->result_array();
		
		return (!empty($rows)) ? $rows : "";

	}

	function get_category_names ( $site = "" )
	{
		$this->db->cache_off();
		$query = $this->db->query('SELECT t1.id, t1.category_name 
														FROM menu as t1
														WHERE t1.site = "'.$site.'"
														AND t1.active = 1
														ORDER BY category_name ASC');
		$rows = $query->result_array();
		
		return (!empty($rows)) ? $rows : "";

	}
	
	function get_product_skus ( $site = "" )
	{
		$this->db->cache_off();
		$query = $this->db->query('SELECT t1.sku 
														FROM products as t1
														LEFT JOIN products_sites AS t2 on t1.sku = t2.sku
														WHERE t2.site = "'.$site.'"
														AND t1.active = 1
														AND t2.active = 1
														ORDER BY t1.sku ASC');
														
		$rows = $query->result_array();
		
		return (!empty($rows)) ? $rows : "";

	}	
	
	function save_new_voucher ( $site = "", $voucher_details = "" ) {

		// set voucher expires date
		$voucher_expires_date = "";
		if( ! empty ( $voucher_details['voucher_expires_date'] ) ) {
			$str = explode( "/", $voucher_details['voucher_expires_date'] );
			$voucher_expires_date = $str[2]."-".$str[1]."-".$str[0]." 00:00:00";
		}
	
		// save new voucher
		$insert = array(
								'site' => $site,
								'voucher_code' => ( ! empty ( $voucher_details['voucher_code'] ) ) ? $voucher_details['voucher_code'] : "",
								'voucher_description' => ( ! empty ( $voucher_details['voucher_description'] ) ) ? $voucher_details['voucher_description'] : "",
								'voucher_type' => ( ! empty ( $voucher_details['voucher_type'] ) ) ? $voucher_details['voucher_type'] : "",
								'voucher_money_off' => ( ! empty ( $voucher_details['voucher_money_off'] ) ) ? $voucher_details['voucher_money_off'] : "",
								'voucher_percent_off' => ( ! empty ( $voucher_details['voucher_percent_off'] ) ) ? $voucher_details['voucher_percent_off'] : "",
								'voucher_all_products' => ( ! empty ( $voucher_details['voucher_all_products'] ) ) ? 1 : 0,								
								'voucher_category' => ( ! empty ( $voucher_details['voucher_category'] ) ) ? $voucher_details['voucher_category'] : "",							
								'voucher_product' => ( ! empty ( $voucher_details['voucher_product'] ) ) ? $voucher_details['voucher_product'] : "",
								'voucher_brand' => ( ! empty ( $voucher_details['voucher_brand'] ) ) ? $voucher_details['voucher_brand'] : "",				
								'voucher_max_uses' => ( ! empty ( $voucher_details['voucher_max_uses'] ) ) ? $voucher_details['voucher_max_uses'] : "",
								'voucher_expires_date' => $voucher_expires_date,
								'admin_id' => $_SESSION['admin']['id'],
								'date_created' => date("Y-m-d H:i:s"),					
								'active' => ( ! empty ( $voucher_details['active'] ) ) ? 1 : 0,					
								);
        $this->db->insert('vouchers', $insert);		
		
        // log admin action
        $this->load->model('global_model');
       $this->global_model->admin_action('Admin_vouchers_model', 'save_new_voucher', 'insert',  print_r($insert, TRUE));	

		return TRUE;

	}

    public function update_voucher($data = "")
    {
        // update vouchers
        $voucher_id = $this->input->post('id');
        $active = $this->input->post('active');
        $active = (!empty($active)) ? 1 : 0;

        $update_vouchers_rates = array(
            'active' => $active,
        );

        $this->db->where('id', $voucher_id);
        $this->db->update('vouchers', $update_vouchers_rates);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_vouchers_model', 'update_voucher', 'update',  print_r($update_vouchers_rates, TRUE));
    }
}
