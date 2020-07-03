<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_sales_model extends CI_Model {

	function get_order_payment_method ( $id = "" )
	{
		
		$this->db->cache_off();
		$query = $this->db->query('SELECT name 
														FROM payment_method
														WHERE id = "'.$id.'" 
														AND active = 1');
		$result = $query->result_array();

		return (!empty($result)) ? $result[0]['payment_method'] : "Store Credit";

	}
	
	function get_order_payment_status ( $id = "" ) 
	{
		switch($id) {
			case 1:
				$value = "Pending Payment";
				break;
			case 2:
				$value = "Awaiting Shipping";
				break;
			case 3:
				$value = "Completed";
				break;
			case 4:
				$value = "Cancelled";
				break;	
			default:
				$value = "";
		}
		
		return $value;
	}	
	
	function get_order_details( $id = "" ) {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	
		
		// get data 
		$this->db->cache_off();		
		$query = $this->db->query('SELECT * 
									FROM orders
									WHERE site = "'.$site.'" 
									AND id = "'.$id.'"
									AND active = 1
									AND status = 0');
		$result = $query->result_array();
		
		// return data
		return $result[0];		
	}
	
	function get_delivery_option( $id = "") {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	
		
		// get data 
		$this->db->cache_off();		
		$query = $this->db->query('SELECT * 
									FROM delivery_rates
									WHERE site = "'.$site.'" 
									AND id = "'.$id.'"
									AND active = 1
									AND status = 0');
		$result = $query->result_array();
		
		// return data
		return $result[0];				
	}
	
	function get_order_details_items ( $id = "" ) {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	
		
		// get data 
		$this->db->cache_off();		
		$query = $this->db->query('SELECT * 
									FROM orders_products
									WHERE site = "'.$site.'" 
									AND order_id = "'.$id.'"
									AND active = 1
									AND status = 0');
		$result = $query->result_array();
		
		// return data
		return $result;				
	}

	function get_order_comments ( $id = "" ) {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	
		
		// get data 
		$this->db->cache_off();		
		$query = $this->db->query('SELECT t1.*, t2.username 
									FROM orders_comments AS t1
									LEFT JOIN admin_users AS t2 on t1.admin_id = t2.id
									WHERE t1.site = "'.$site.'" 
									AND t1.order_id = "'.$id.'"
									AND t1.active = 1
									AND t1.status = 0
									ORDER BY t1.id DESC');
		$result = $query->result_array();

		// return data
		return $result;			
	}
	
	function get_last_order_comment ( $id = "" ) {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	
		
		// get data
		$this->db->cache_off();		
		$query = $this->db->query('SELECT t1.*, t2.username 
									FROM orders_comments AS t1
									LEFT JOIN admin_users AS t2 on t1.admin_id = t2.id
									WHERE t1.site = "'.$site.'" 
									AND t1.order_id = "'.$id.'"
									AND t1.active = 1
									AND t1.status = 0
									ORDER BY t1.date_added DESC
									LIMIT 1');
		$result = $query->result_array();

		// return data
		return $result;			
	}		
	
	function add_order_comment () {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	
		
		$insert = array(
							'site' => $site,
							'admin_id' => $_SESSION['admin']['id'],
							'order_id' => $this->input->post('add_comment_order_id'),
							'comments' => $this->input->post('add_order_comment'),				
						);

		$this->db->insert('orders_comments', $insert);	

		$this->global_model->admin_action("Admin_sales_model", "add_order_comment", "insert", print_r($insert, TRUE));	// record admin action			
		
	}
	
	function get_tracking_codes ( $order_id = "" ) {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session		
		
		// get data 
		$this->db->cache_off();		
		$query = $this->db->query('SELECT * 
														FROM orders_tracking
														WHERE order_id = "'.$order_id.'"
														AND site = "'.$site.'" 
														AND active = 1
														AND status = 0
														ORDER BY id ASC');
		$rows = $query->result_array();		

		return $rows;
		
	}
	
	function update_tracking_codes () {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

		// get post data 
		$order_id = $this->input->post('order_id');
		$tracking_sku = $this->input->post('tracking_sku');
		$tracking_code = $this->input->post('tracking_code');
		$email_sent_to_customer = $this->input->post('email_tracking_codes');
		$email_sent = ( ! empty( $email_sent_to_customer ) ) ? 1 : 0;
		
		// set any existing tracking codes to inactive		
		$this->db->set('active', 0);				
		$this->db->set('status', 1);				
		$this->db->where('order_id', $order_id);			
		$this->db->update('orders_tracking');				
		
		// set new tracking codes 
		if( ! empty ( $tracking_sku ) ) {
			for($x = 0; $x < count($tracking_sku); $x++) {
			
				if( ! empty( $tracking_sku[$x] ) && ! empty( $tracking_code[$x] ) )  {
					$insert = array(
										'site' => $site,
										'admin_id' => $_SESSION['admin']['id'],
										'order_id' => $order_id,
										'tracking_sku' => $tracking_sku[$x],
										'tracking_code' => $tracking_code[$x],
										'email_sent_to_customer' => $email_sent,									
									);

					$this->db->insert('orders_tracking', $insert);	
					
					$this->global_model->admin_action("Admin_sales_model", "update_tracking_codes", "insert", print_r($insert, TRUE));	// record admin action		
				}

			}
		}
		
		// send email
		if( ! empty ( $email_sent ) ) {
			
			// get order data
			$this->db->cache_off();
			$query = $this->db->query('SELECT * 
															FROM orders
															WHERE order_id = "'.$order_id.'"
															AND site = "'.$site.'" 
															AND active = 1
															AND status = 0');
			$rows = $query->result_array();					
			
			// send mail
			$this->load->library('email');
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from('noreply@unineedgroup.com', "Unineed Group");
			$this->email->to($rows[0]['email']);
			// $this->email->cc('scorpio.72@live.com');
			// $this->email->bcc('them@their-example.com');
			$this->email->subject('Order ID '.$order_id.' - Tracking codes');
			$data = array(
								'order_id' => $order_id,
								'billing_first_name'=> $rows[0]['billing_first_name'],
								'billing_last_name'=> $rows[0]['billing_last_name'],								
								'tracking_sku'=> $tracking_sku,
								'tracking_code'=> $tracking_code,
								);		 
			$body = $this->load->view('admin/emails/order_tracking_codes',$data,TRUE);
			$this->email->message($body);
			$sent = $this->email->send();	
		
		}
		
	}
	
	function update_order () {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// order id
		$order_id =  $this->input->post('order_id');

		// update orders table
		$update = array(
									'site' => $site,
									'email' => $this->input->post('email'),		
									'order_status' => $this->input->post('order_status'),											
									'billing_first_name' => $this->input->post('billing_first_name'),		
									'billing_last_name' => $this->input->post('billing_last_name'),	
									'billing_house_number' => $this->input->post('billing_house_number'),	
									'billing_street_name' => $this->input->post('billing_street_name'),										
									'billing_town_city' => $this->input->post('billing_town_city'),	
									'billing_county' => $this->input->post('billing_county'),										
									'billing_country' => $this->input->post('billing_country'),	
									'billing_postcode' => $this->input->post('billing_postcode'),	
									'billing_telephone' => $this->input->post('billing_telephone'),	
									'shipping_first_name' => $this->input->post('shipping_first_name'),		
									'shipping_last_name' => $this->input->post('shipping_last_name'),										
									'shipping_house_number' => $this->input->post('shipping_house_number'),	
									'shipping_street_name' => $this->input->post('shipping_street_name'),										
									'shipping_town_city' => $this->input->post('shipping_town_city'),	
									'shipping_county' => $this->input->post('shipping_county'),										
									'shipping_country' => $this->input->post('shipping_country'),	
									'shipping_postcode' => $this->input->post('shipping_postcode'),	
									'shipping_telephone' => $this->input->post('shipping_telephone'),	
								);
		
		$this->db->where('order_id', $order_id);			
		$this->db->update('orders', $update);

		// record admin action
		$this->global_model->admin_action("Admin_sales_model", "update_order", "update", print_r($update, TRUE));
		
		// update order_products table
		$products = $this->input->post('product_qty');
		
		foreach ( $products as $k => $v ) {
			
			// update stock on hold values
			$this->load->model('admin_products_model');

			// get product count in basket
			$this->db->cache_off();
			$query = $this->db->query('SELECT qty, sku
																FROM orders_products
																WHERE id= "'.$k.'"
																AND order_id = "'.$order_id.'"
																AND active = 1
																AND status = 0');
			$product_count = $query->result_array();	
			
			// get product details from order in table
			$this->db->cache_off();
			$query = $this->db->query('SELECT sku, stock, stock_on_hold
																FROM products
																WHERE sku= "'.$product_count[0]['sku'].'"
																AND active = 1
																AND status = 0');
			$product = $query->result_array();				
			
			// update stock movements
			$data = array();
			$data['product']['stock'] = $v;
			$data['product']['original_stock'] = $product_count[0]['qty'];
			$data['product']['sku'] = $product[0]['sku'];
			$reason = "Admin updated qty for order id ".$order_id;
			if( $product_count[0]['qty'] != $v ) {
				$this->admin_products_model->adjust_stock_values ( $data, $reason );  // admin action is logged within this function	
			}

			// update stock levels
			$math_action = "";
			if( $product_count[0]['qty'] > $v)  // we are removing items from our basket
			{
				$update_qty = ( $product_count[0]['qty'] - $v );
				$stock = $product[0]['stock']+$update_qty;
				$stock_on_hold = $product[0]['stock_on_hold']-$update_qty;
				$math_action = "-"; // reversed as we use this for order comment		
			}
			else  // we are adding items from our basket
			{
				$update_qty = ( $v - $product_count[0]['qty'] );
				$stock = $product[0]['stock']-$update_qty;
				$stock_on_hold = $product[0]['stock_on_hold']+$update_qty;
				$math_action = "+"; // reversed as we use this for order comment			
			}

			// update products table, stock & stock on hold values
			if($product_count[0]['qty'] != $v) 
			{				
				$this->db->set('stock', $stock, FALSE);			
				$this->db->set('stock_on_hold', $stock_on_hold, FALSE);								
				$this->db->where('sku', $product[0]['sku']);			
				$this->db->update('products');	

				// add order comment for qty update
				$comment  = "Changed Order Qty for ".$product_count[0]['sku']." by ".$math_action.$update_qty;
				$insert = array(
									'site' => $site,
									'admin_id' => $_SESSION['admin']['id'],
									'order_id' => $order_id,
									'comments' => $comment,				
								);
				$this->db->insert('orders_comments', $insert);	
				
				$this->global_model->admin_action("Admin_sales_model", "update_order", "insert", print_r($insert, TRUE));	// record admin action						
			}		

			// update order product qty 
			$update = array(
										'order_id' => $order_id,
										'sku' => $product[0]['sku'],
										'qty' => $v,
									);
			
			$this->db->where('id', $k);			
			$this->db->update('orders_products', $update);		
			
			// record admin action	
			if( $product_count[0]['qty'] != $v ) {
				$this->global_model->admin_action("Admin_sales_model", "update_order", "update", print_r($update, TRUE));	
			}
		}
		
		// process refund ( flag refund type )
		$order_refunded_type =  $this->input->post('order_refunded_type');
		$update = array(
										'order_id' => $order_id,
										'order_refunded_type' => $order_refunded_type,
									);
		$this->db->where('order_id', $order_id);			
		$this->db->update('orders', $update);		
		
		// recalculate order total
		$this->load->model('checkout_model');
		$this->checkout_model->adjust_order_total ( $order_id ); 
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// get order status change 
		$original_order_status = $this->input->post('original_order_status');
		$order_status = $this->input->post('order_status');
		if( $original_order_status != $order_status ) 
		{
			switch($order_status) {
				case 1:
					$comment = "Order status changed to Pending Payment";
					break;
				case 2:
					$comment = "Order status changed to Awaiting Shipping";
					break;
					case 3:
					$comment = "Order status changed to Completed";
					break;
				case 4:
					$comment = "Order status changed to Cancelled";
					break;	
				case 7:
					$comment = "Order status changed to Confirming Order";
					break;						
				default:
					$comment = "";
			}
			
			$insert = array(
								'site' => $site,
								'admin_id' => $_SESSION['admin']['id'],
								'order_id' => $order_id,
								'comments' => $comment,				
							);
			$this->db->insert('orders_comments', $insert);	
			
			$this->global_model->admin_action("Admin_sales_model", "update_order", "insert", print_r($insert, TRUE));	// record admin action			
		}	
		
	}
	
	function get_customer_status ( $email = "" ) {
		
		// if email is in user table return registered
		$this->db->cache_off();
		$query = $this->db->query('SELECT id
														FROM users 
														WHERE email = "'.$email.'"
														AND active = 1
														AND status = 0');
		$rows = $query->result_array();

		$user_status = ( ! empty ( $rows ) ) ? "Registered" : "Guest";

		return $user_status;
		
	}
	
	function get_customer_purchase_count( $email = "" ) {
		
		// count times the user has made a purchase
		$this->db->cache_off();
		$query = $this->db->query('SELECT count( id ) as total
														FROM orders 
														WHERE email = "'.$email.'"
														AND active = 1
														AND status = 0');
		$count = $query->result_array();
		
		$total = 0;
		
		if( $count[0]['total'] == 1 ) {
			$total = "New Customer ( first order using this email address )";
		}
		
		if( $count[0]['total'] > 1 ) {
			$total =  "There are  ".$count[0]['total'] ." orders using this email address";
		}

		return $total;		
		
	}
	
	function get_customer_page_view ( $user_session = "" ) {
		
		$this->db->cache_off();
		$query = $this->db->query('SELECT page_url
														FROM log_interactions 
														WHERE user_session = "'.$user_session.'"
														AND active = 1
														AND status = 0
														ORDER BY id DESC
														LIMIT 1');
		$rows = $query->result_array();	

		return ( ! empty ( $rows ) ) ? $rows[0]['page_url'] : "";
		
	}

	function update_order_totals ( $order_details ) {
		
		// get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session		


		// select the refund details so we can check if we need to update these details
		$this->db->cache_off();
		$query = $this->db->query('SELECT id, email, order_refunded_type, order_refunded_amount
														FROM orders
														WHERE order_id = "'.$order_details['order_id'].'"
														AND active = 1
														AND status = 0
														LIMIT 1');
		$rows = $query->result_array();	

		// if the refund amount is different to whats in the database
		//	e.g. is flagged as store credit and the refund amount has changed, update user details
		$comment = "";
		if( $rows[0]['order_refunded_type'] == 1 && ( $order_details['refund'] != $rows[0]['order_refunded_amount'] ) ) {
			
			if( $order_details['refund'] > $rows[0]['order_refunded_amount'] ) 
			{ // if refund is greater than whats in db table
				$refund_amount = ( $order_details['refund'] - $rows[0]['order_refunded_amount'] ) ;
				$refund = " store_credit + " .$refund_amount;
			}
			else 
			{ // refund is less than whats in db table
				$refund_amount = ( $rows[0]['order_refunded_amount'] - $order_details['refund'] ) ;		
				$refund = " store_credit - " .$refund_amount;				
			}
			// update users store credit details
			$this->db->set('store_credit', $refund, FALSE );		
			$this->db->where('email', $rows[0]['email'] );					
			$this->db->update('users');	
			
			// update comments
			$comment = "Customer refunded " .number_format((float)( $refund_amount ), 2, '.', '')." to their Store Credit";
			$insert = array(
								'site' => $site,
								'admin_id' => $_SESSION['admin']['id'],
								'order_id' => $order_details['order_id'],
								'comments' => $comment,				
							);
			$this->db->insert('orders_comments', $insert);	
			
			// log admin action
			$this->global_model->admin_action("Admin_sales_model", "update_order", "insert", print_r($insert, TRUE));	// record admin action					
			
		}

		// update order details
		$update = array(
									'order_total_tax' => $order_details['tax'],
									'order_refunded' => ( $order_details['refund'] != "0.00" ) ? 1 : 0,
									'order_refunded_amount' => $order_details['refund'],										
								);
			
		$this->db->where('order_id', $order_details['order_id']);			
		$this->db->update('orders', $update);		

		return $comment;

	}
	
	function royal_mail ( $site = "") {

		// code
		
	}
	
	function bpost ( $site = "" ) {

		// code
	
	}	
	
	function parcel_force ( $site = "" ) {
		
		// code
		
	}		
	
	

}
