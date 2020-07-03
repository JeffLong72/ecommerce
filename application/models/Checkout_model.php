<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout_model extends CI_Model {
	
	public function add_product_to_basket($site, $product_id)
	{
		// set this customers order id
		// order id variable: $_SESSION['customer']['order_id']
		if(empty($_SESSION['customer']['order_id'])) {
			$_SESSION['customer']['order_id'] = date("U").rand(0, 99999); // unique value
		}
		
		$order_id = $_SESSION['customer']['order_id'];
		
		// get order details for this user session
		$this->db->cache_off();
		$query = $this->db->query('SELECT order_id
									FROM orders
									WHERE order_id = "'.$order_id.'"
									AND site = "'.$site.'"');
		$result = $query->result_array();	

		// if an order already exists for this user session
		if(!empty($result)) {
			
			// if product already exists in basket update qty
			$this->db->cache_off();
			$query = $this->db->query('SELECT product_id, qty
										FROM orders_products AS t1
										WHERE order_id = "'.$order_id.'"
										AND product_id = "'.$product_id.'"
										AND site = "'.$site.'"
										AND active = 1
										AND status = 0');
			$result = $query->result_array();
			
			// update qty for product sku
			if(!empty($result)) {
				foreach($result as $product) {
					if($product['product_id'] == $product_id) {
						$data = array(
										'qty' => $product['qty'] + 1
								);

								$this->db->where('product_id', $product_id);
								$this->db->update('orders_products', $data);
					}
				}
			
			}
			// else add product to order
			else {
				$this->db->cache_off();
				$query = $this->db->query('SELECT *
											FROM products_text AS t1
											LEFT JOIN products_cost AS t2 ON t1.sku = t2.sku
											WHERE t1.id = "'.(int)$product_id.'"
											AND t1.site = "'.$site.'"');
				$result = $query->result_array();	

				if(!empty($result)) {
					foreach($result as $product) {
						
						$cost = (empty((int)$product['special_offer_cost'])) ? $product['cost'] : $product['special_offer_cost'];					
						
						$insert = array(
										'site' => $site,
										'order_id' => $order_id,
										'product_id' => $product_id,
										'product_title' => $product['title'],											
										'sku' => $product['sku'],
										'cost' => $cost,
										'currency' => $_SESSION['site']['currency_text'],	
										'exchange_rate' => $_SESSION['site']['currency_rate'],											
										'date_added' => date("Y-m-d H:i:s")							
									);

						$this->db->insert('orders_products', $insert);
						
						// update products table, stock & stock on hold values
						$this->db->set('stock', 'stock-1', FALSE);			
						$this->db->set('stock_on_hold', 'stock_on_hold+1', FALSE);								
						$this->db->where('sku', $product['sku']);			
						$this->db->update('products');		
					}
				}
			}
			
		}
		// else create a new order
		else {

			// detect user device 
			// ( eg. mobile, tablet, desktop )
			$user_device = $this->global_model->detect_mobile();			
			
			// create new order in orders table
			$insert_new_order = array(
									'site' => $site,
									'user_session' => $_SESSION['site']['user_session'],
									'order_id' => $order_id,
									'order_date' => date("Y-m-d H:i:s"),	
									'order_currency' => $_SESSION['site']['currency_text'],
									'order_exchange_rate' => $_SESSION['site']['currency_rate'],											
									'user_device' => $user_device
								);
			$this->db->insert('orders', $insert_new_order);
			
			// add product to products_order table
			$this->db->cache_off();
			$query = $this->db->query('SELECT *
										FROM products_text AS t1
										LEFT JOIN products_cost AS t2 ON t1.sku = t2.sku
										WHERE t1.id = "'.(int)$product_id.'"
										AND t1.site = "'.$site.'"');
			$result = $query->result_array();	
			
			if(!empty($result)) {
				foreach($result as $product) {
					
					$cost = (empty((int)$product['special_offer_cost'])) ? $product['cost'] : $product['special_offer_cost'];
						
					$insert = array(
									'site' => $site,
									'order_id' => $order_id,
									'product_id' => $product_id,		
									'product_title' => $product['title'],										
									'sku' => $product['sku'],
									'cost' => $cost,
									'currency' => $_SESSION['site']['currency_text'],	
									'exchange_rate' => $_SESSION['site']['currency_rate'],										
									'date_added' => date("Y-m-d H:i:s")							
								);

					$this->db->insert('orders_products', $insert);
					
					// update products table, stock & stock on hold values
					$this->db->set('stock', 'stock-1', FALSE);			
					$this->db->set('stock_on_hold', 'stock_on_hold+1', FALSE);								
					$this->db->where('sku', $product['sku']);			
					$this->db->update('products');	
					
				}
			}
			
		}
		
		// recalculate order total
		$this->adjust_order_total ( $order_id ); 		

	}
	
	public function get_products_in_basket($site) {
		
		// get order id
		$order_id = (!empty($_SESSION['customer']['order_id'])) ? $_SESSION['customer']['order_id'] : "";
		
		// get products in basket
		if(!empty($order_id)) {
			$this->db->cache_off();
			$query = $this->db->query('SELECT product_id, product_title, sku, cost, qty
										FROM orders_products
										WHERE order_id = "'.$order_id.'"
										AND site = "'.$site.'"
										AND active = 1
										AND status = 0
										ORDER BY product_title ASC');
			return $query->result_array();
		}
		
		return NULL;
	}
	
	public function get_product_images_in_basket($sku="") {
		
		// get site id
		$site = $this->config->item('template'); // config			
		
		// get product images
		$this->db->cache_on();
		$query = $this->db->query('SELECT t1.category
														FROM products_images AS t1
														LEFT JOIN products AS t2 ON t1.sku = t2.sku
														WHERE t1.sku = "'.$sku.'"
														AND t1.category != ""
														AND t1.active = 1
														AND t1.status = 0');										
														
		$result = $query->result_array();
		
		return $result;

	}	
	
	public function update_products_in_basket () {
		
		// get post data
		$post = $this->input->post();
		
		// unset hidden field
		unset($post['update_basket']);
		
		// set voucher code
		$voucher_code = $post['order_voucher_code'];
		
		// set voucher code as session just in case we need to re-use this
		$_SESSION['customer']['voucher_code'] = $voucher_code;
		
		// unset hidden field
		unset($post['order_voucher_code']);		
		
		// get order id
		$order_id = (!empty($_SESSION['customer']['order_id'])) ? $_SESSION['customer']['order_id'] : "";		
		
		// update each product qty
		foreach($post as $key => $value) {
			
			// get product sku
			$this->db->cache_off();
			$query = $this->db->query('SELECT sku, stock, stock_on_hold, brand
																FROM products
																WHERE id= "'.$key.'"
																AND active = 1
																AND status = 0');
			$product = $query->result_array();	
			
			// get product count in basket
			$this->db->cache_off();
			$query = $this->db->query('SELECT qty
																FROM orders_products
																WHERE product_id= "'.$key.'"
																AND order_id = "'.$order_id.'"
																AND active = 1
																AND status = 0');
			$product_count = $query->result_array();	

			if( ! empty ( $product ) && ! empty ( $product_count ) ) {
				
				// if applying discount code
				
				$_SESSION['customer']['voucher_error'] = FALSE;
				$_SESSION['customer']['voucher_is_valid'] = FALSE;
				$_SESSION['customer']['voucher_result']  = FALSE;
				$_SESSION['customer']['voucher_discount']  = FALSE;	
				$_SESSION['customer']['voucher_discount_type']  = FALSE;		
				
				if( ! empty( $voucher_code ) ) {

					// check if voucher is valid and active in db
					$this->db->cache_off();
					$query = $this->db->query('SELECT *
																		FROM vouchers
																		WHERE voucher_code = "'.$voucher_code.'"
																		AND active = 1
																		AND status = 0');
					$voucher_details = $query->result_array();

					// get voucher expiry date
					$todays_date = date( "U" );
					$expire_date = ( ! empty ( $voucher_details ) ) ? date( "U", strtotime( $voucher_details[0]['voucher_expires_date'] ) ) : "";							
					
					// get product category details
					$this->db->cache_off();
					$query = $this->db->query('SELECT menu_id
																			FROM products_category
																			WHERE sku= "'.$product[0]['sku'].'"');
					$product_category = $query->result_array();	

					// if we have a voucher
					if( ! empty ( $voucher_details ) ) {
						
						// if voucher is for product
						if( $voucher_details[0]['voucher_brand'] == $product[0]['brand'] ) {
							$_SESSION['customer']['voucher_is_valid'] = TRUE;
							$_SESSION['customer']['voucher_discount_type']  = $voucher_details[0]['voucher_type'];									
							$_SESSION['customer']['voucher_discount']  = ( $voucher_details[0]['voucher_money_off'] !="0.00" ) ? $voucher_details[0]['voucher_money_off'] : $voucher_details[0]['voucher_percent_off'];								
							$_SESSION['customer']['voucher_result'] = "Discount Code has been applied.";
						}
						
						// if voucher is for category			
						if( $voucher_details[0]['voucher_category'] == $product_category[0]['menu_id'] ) {
							$_SESSION['customer']['voucher_is_valid'] = TRUE;	
							$_SESSION['customer']['voucher_discount_type']  = $voucher_details[0]['voucher_type'];									
							$_SESSION['customer']['voucher_discount']  = ( $voucher_details[0]['voucher_money_off'] !="0.00"  ) ? $voucher_details[0]['voucher_money_off'] : $voucher_details[0]['voucher_percent_off'];									
							$_SESSION['customer']['voucher_result'] = "Discount Code has been applied.";				
						}
						
						// if voucher is for sku
						if( $voucher_details[0]['voucher_product'] == $product[0]['sku'] ) {
							$_SESSION['customer']['voucher_is_valid'] = TRUE;	
							$_SESSION['customer']['voucher_discount_type']  = $voucher_details[0]['voucher_type'];									
							$_SESSION['customer']['voucher_discount']  = ( $voucher_details[0]['voucher_money_off'] !="0.00"  ) ? $voucher_details[0]['voucher_money_off'] : $voucher_details[0]['voucher_percent_off'];									
							$_SESSION['customer']['voucher_result'] = "Discount Code has been applied.";						
						}
						
						// voucher is valid for all products
						if( $voucher_details[0]['voucher_all_products'] == 1 ) {
							$_SESSION['customer']['voucher_is_valid'] = TRUE;	
							$_SESSION['customer']['voucher_discount_type']  = $voucher_details[0]['voucher_type'];									
							$_SESSION['customer']['voucher_discount']  = ( $voucher_details[0]['voucher_money_off'] !="0.00"  ) ? $voucher_details[0]['voucher_money_off'] : $voucher_details[0]['voucher_percent_off'];									
							$_SESSION['customer']['voucher_result'] = "Discount Code has been applied.";							
						}

						// if voucher has exceeded maximum uses
						if( $voucher_details[0]['voucher_max_uses'] > 0 && ( $voucher_details[0]['voucher_used'] >= $voucher_details[0]['voucher_max_uses'] ) ) {
							$_SESSION['customer']['voucher_code'] = FALSE;							
							$_SESSION['customer']['voucher_is_valid'] = FALSE;
							$_SESSION['customer']['voucher_discount_type']  = FALSE;									
							$_SESSION['customer']['voucher_discount']  = FALSE;									
							$_SESSION['customer']['voucher_result'] = "Sorry, this Discount Code has reached its maximum use.";
						}
						
						// if voucher has passed expiry date
						if( $voucher_details[0]['voucher_expires_date'] != "" && ( $todays_date > $expire_date ) ) {
							$_SESSION['customer']['voucher_code'] = FALSE;							
							$_SESSION['customer']['voucher_is_valid'] = FALSE;
							$_SESSION['customer']['voucher_discount_type']  = FALSE;									
							$_SESSION['customer']['voucher_discount']  = FALSE;										
							$_SESSION['customer']['voucher_result'] = "Sorry, this Discount Code has passed its expiry date.";
						}

					}
					else {
						// no voucher found
						$_SESSION['customer']['voucher_code'] = FALSE;
						$_SESSION['customer']['voucher_is_valid'] = FALSE;
						$_SESSION['customer']['voucher_discount_type']  = FALSE;									
						$_SESSION['customer']['voucher_discount']  = FALSE;									
						$_SESSION['customer']['voucher_result'] = "Please enter a valid Discount Code.";
					}
			
				}
				
				// else customer is updating basket qty
				// else {
					// if value > 0 update qty
					if($value > 0) {
				
						if( $product_count[0]['qty'] > $value)  // we are removing items from our basket
						{
							$update_qty = ( $product_count[0]['qty'] - $value );
							$stock = $product[0]['stock']+$update_qty;
							$stock_on_hold = $product[0]['stock_on_hold']-$update_qty;
						}
						else  // we are adding items from our basket
						{
							$update_qty = ( $value - $product_count[0]['qty'] );
							$stock = $product[0]['stock']-$update_qty;
							$stock_on_hold = $product[0]['stock_on_hold']+$update_qty;
						}

						// update products table, stock & stock on hold values
						if($product_count[0]['qty'] != $value) 
						{				
							$this->db->set('stock', $stock, FALSE);			
							$this->db->set('stock_on_hold', $stock_on_hold, FALSE);								
							$this->db->where('sku', $product[0]['sku']);			
							$this->db->update('products');	

							// update basket product
							$data = array(
											'qty' => $value
										);
										
							$this->db->where('order_id', $order_id);			
							$this->db->where('product_id', $key);	
							$this->db->update('orders_products', $data);		

						}
					}
					// else set product in basket to inactive/deleted 
					// ( item is removed from order )
					// ( display to user as removed from basket )
					else {
					
						// set values
						$update_qty = $product_count[0]['qty'];
						$stock = $product[0]['stock']+$update_qty;
						$stock_on_hold = $product[0]['stock_on_hold']-$update_qty;				
						
						// update products table, stock & stock on hold values
						$this->db->set('stock', $stock, FALSE);			
						$this->db->set('stock_on_hold', $stock_on_hold, FALSE);								
						$this->db->where('sku', $product[0]['sku']);			
						$this->db->update('products');					
						
						// remove basket product
						$data = array(
										'qty' => 0,				
										'active' => 0,
										'status' => 1,								
									);
									
						$this->db->where('order_id', $order_id);			
						$this->db->where('product_id', $key);
						$this->db->update('orders_products', $data);			

					}
				// }
			}
		}
		
		// recalculate order total
		$this->adjust_order_total ( $order_id ); 				
	}
	
	public function adjust_order_total ( $order_id = "" ) {
		
		// get site id
		$site = $this->config->item('template'); // config			
		
		// select all products from our basket
		$this->db->cache_off();
		$query = $this->db->query('SELECT *
															FROM orders_products
															WHERE order_id = "'.$order_id.'"
															AND site = "'.$site.'"
															AND active = 1
															AND status = 0
															ORDER BY id ASC');
		$rows = $query->result_array();	
			
		// calculate the new sub total based on the products in our basket	
		$order_sub_total_cost = 0;
		if(!empty($rows)) {
			foreach($rows as $row) {
					
				$value = ( $row['cost'] * $row['qty'] ) * $row['exchange_rate'];
				$order_sub_total_cost += number_format((float)($value), 2, '.', '');
					
			}
		}

		// update our sub total for this order
		$this->db->cache_off();
		$this->db->set('order_sub_total_cost', $order_sub_total_cost);	
		$this->db->set('order_total_cost', $order_sub_total_cost);			
		$this->db->where('order_id', $order_id);
		$this->db->update('orders');		
		
		// $this->db->last_query();

	}
	
	public function delivery_rates($site = "" ) {
			$this->db->cache_off();
			$query = $this->db->query('SELECT *
															FROM delivery_rates
															WHERE site = "'.$site.'"
															ORDER BY id ASC');
			return $query->result_array();		
	}
	
	public function set_customer_details ($data) {
		
		// get site id
		$site = $this->config->item('template'); // config	

		// customer email
		if(!empty($data['guest_email'])) {
			$email = $data['guest_email'];
			$user_checkout_status = 0;
		}
		else {
			$email = $data['login_email'];
			$user_checkout_status = 1;			
		}
		
		// we need to convert all costs back to our base rate 
		// so we can save the order in the database as GBP,
		// store credit
		$data['order_store_credit'] = ( $data['order_store_credit'] / $_SESSION['site']['currency_rate'] );
		$data['order_store_credit'] = number_format((float)($data['order_store_credit']), 2, '.', '');
		// sub total
		$data['order_sub_total_cost'] = ( $data['order_sub_total_cost'] / $_SESSION['site']['currency_rate'] );
		$data['order_sub_total_cost'] = number_format((float)($data['order_sub_total_cost']), 2, '.', '');
		// total cost
		$data['order_total_cost'] = ( $data['order_total_cost'] / $_SESSION['site']['currency_rate'] );
		$data['order_total_cost'] = number_format((float)($data['order_total_cost']), 2, '.', '');		
		// delivery costs
		// get current cost as this could change over time
		$query = $this->db->query('SELECT cost
									FROM delivery_rates
									WHERE site = "'.$site.'"
									AND id = "'.$data['delivery_rates'].'"');
		$rows = $query->result_array();			
		$data['delivery_cost'] = $rows[0]['cost'];
		
		// if voucher has been used apply discount
		if ( ! empty( $_SESSION['customer']['voucher_is_valid'] ) ) {
			
			// money off
			if( $_SESSION['customer']['voucher_discount_type'] == 1 ) {
				$less_discount = $_SESSION['customer']['voucher_discount'];
			}
			
			// percentage off
			if( $_SESSION['customer']['voucher_discount_type'] == 2 ) {
				$less_discount = $data['order_sub_total_cost'] * ( $_SESSION['customer']['voucher_discount'] / 100 );
			}
			
			// update voucher use count	
			$this->db->set('voucher_used', 'voucher_used+1', FALSE);			
			$this->db->where('voucher_code', $_SESSION['customer']['voucher_code']);
			$this->db->update('vouchers');	
		}	

		$order_voucher_code = ( ! empty( $_SESSION['customer']['voucher_code'] ) ) ? $_SESSION['customer']['voucher_code'] : "";
		$order_voucher_discount = ( ! empty ( $less_discount ) ) ? $less_discount : "";
		
		// save details to orders table
		$upd = array(
								'site' => $site,				
								'user_checkout_status' => $user_checkout_status,		
								'email' => $email,		
								'billing_first_name' => $data['billing_first_name'],	
								'billing_last_name' => $data['billing_last_name'],		
								'billing_house_number' => $data['billing_house_number'],		
								'billing_street_name' => $data['billing_street_name'],		
								'billing_town_city' => $data['billing_town_city'],		
								'billing_county' => $data['billing_county'],		
								'billing_country' => $data['billing_country'],	
								'billing_postcode' => $data['billing_postcode'],					
								'billing_telephone' => $data['billing_telephone'],		
								'use_billing_address' => $data['use_billing_address'],		
								'shipping_first_name' => $data['shipping_first_name'],			
								'shipping_last_name' => $data['shipping_last_name'],		
								'shipping_house_number' => $data['shipping_house_number'],	
								'shipping_street_name' => $data['shipping_street_name'],		
								'shipping_town_city' => $data['shipping_town_city'],		
								'shipping_county' => $data['shipping_county'],			
								'shipping_country' => $data['shipping_country'],	
								'shipping_postcode' => $data['shipping_postcode'],					
								'shipping_telephone' => $data['shipping_telephone'],
								'order_voucher_code' => $order_voucher_code,	
								'order_voucher_discount' => $order_voucher_discount,									
								'order_delivery_cost' => $data['delivery_rates'],									
								'order_payment_method' => $data['order_payment_method'],										
								'order_store_credit' => $data['order_store_credit'],									
								'order_sub_total_cost' => $data['order_sub_total_cost'],		
								'order_delivery_rate' => $data['delivery_rates'],
								'order_delivery_cost' => $data['delivery_cost'],									
								'order_total_cost' => $data['order_total_cost'],	
								'order_currency' => $_SESSION['site']['currency_text'],	
								'order_exchange_rate' => $_SESSION['site']['currency_rate'],									
								'agree_to_terms' => $data['agree_to_terms'],	
								'order_status' => 1,									
							);
							
				$this->db->where('order_id', $data['order_id']);
				$this->db->update('orders', $upd);	

		// if customer wants to optin to newsletter
		if(!empty($data['optin_newsletter'])) {
			
			$user_id = ( ! empty( $_SESSION['customer']['details']['id'] ) ) ? $_SESSION['customer']['details']['id'] : 0;
			
			$ins = array(
					'site' => $site,				
					'user_id' => $user_id,			
					'user_email' => $email,		
					'user_subscribed_date' => date("Y-m-d H:i:s"),
					'user_confirmed_date' => date("Y-m-d H:i:s"),					
					'active' => 1,					
			);
			$this->db->insert('newsletter_subscriptions', $ins);			
		}
		
		// return the order update array for payment processing
		return $upd;
	}

	public function update_stock_on_hold_for_order($order_id = "") {
		
		// get site id
		$site = $this->config->item('template'); // config			

		// for each product in order, update stock on hold	
		$this->db->cache_off();
		$query = $this->db->query('SELECT t1.product_id, t1.qty, t2.order_paid
														FROM orders_products as t1
														LEFT JOIN orders as t2 ON t1.order_id = t2.order_id
														WHERE t1.site = "'.$site.'"
														AND t1.order_id = "'.$order_id.'"
														AND t1.active = 1
														AND t1.status = 0
														');
		$rows = $query->result_array();	
		
		if(!empty($rows)) {
			if( empty( $rows[0]['order_paid'] ) ) { // if order is marked as paid we dont need to update stock!
				foreach($rows as $row) {
					$this->db->set('stock_on_hold', 'stock_on_hold-'.$row['qty'], FALSE);		
					$this->db->where('id', $row['product_id']);		
					$this->db->update('products');					
				}
			}
		}

	}
	
	public function update_order_status($order_id = "") {
		
		// get site id
		$site = $this->config->item('template'); // config			
		
		// update orders table to show paid 
		$upd = array(
								'site' => $site,					
								'order_status' => 7,	// status: confirming order
								'order_paid' => 1,												
							);
							
		$this->db->where('order_id', $order_id);
		$this->db->where('active', '1');
		$this->db->where('status', '0');		
		$this->db->update('orders', $upd);			
	}	

	public function send_order_confirmation_email($order_id = "") {
		
		// get site id
		$site = $this->config->item('template'); // config	
		
		// send email
		// TODO....
		
		// show customer we have sent an email
		return "Order ID: ".$order_id;
	}
	
}
