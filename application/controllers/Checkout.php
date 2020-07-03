<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {
	
	public function basket()
	{
		// enable profiler
		// $this->output->enable_profiler(TRUE);

		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);	
		
		//get products in basket
		$this->load->model('checkout_model');
		
		// update basket
		$update_basket = $this->input->post('update_basket');
		if(!empty($update_basket)) {
			$data['products'] = $this->checkout_model->update_products_in_basket($site);	
		}		
		
		// show basket contents
		$data['products'] = $this->checkout_model->get_products_in_basket($site);
		
		// set costs
		$total_cost = 0;
		$total_items = 0;

		if(!empty($data['products'])) {
			foreach($data['products'] as $product) {
				
				$product_cost = ( $product['cost'] * $_SESSION['site']['currency_rate'] );
				$product_cost = number_format((float)($product_cost), 2, '.', '');
				
				$total_cost += number_format((float)( $product['qty'] * $product_cost), 2, '.', '');
				$total_items += $product['qty'];
			}
		}
		
		// set totals
		$data['sub_total'] = number_format((float)$total_cost, 2, '.', '');
		$data['less_discount'] = number_format((float) 0 , 2, '.', '');		
		$data['total_cost'] = number_format((float)$total_cost, 2, '.', '');
		$data['total_items'] = $total_items;			
		
		// if voucher has been used apply discount
		if ( ! empty( $_SESSION['customer']['voucher_is_valid'] ) ) {
			
			// money off
			if( $_SESSION['customer']['voucher_discount_type'] == 1 ) {
				$less_discount = $_SESSION['customer']['voucher_discount'];
				$data['less_discount'] = "-".number_format( (float) $less_discount, 2, '.', '');
			}
			// percentage off
			if( $_SESSION['customer']['voucher_discount_type'] == 2 ) {
				$less_discount = $total_cost * ( $_SESSION['customer']['voucher_discount'] / 100 );
				$data['less_discount'] = "-".number_format( (float) $less_discount, 2, '.', '');		
			}

			// set new total cost
			$data['total_cost'] = number_format( (float) ( $total_cost - $less_discount ), 2, '.', '');			
		}

		// load template
		$this->load->view($site.'/header', $data);
		$this->load->view($site.'/checkout/basket', $data);
		$this->load->view($site.'/footer', $data);
	}
	
	public function add_to_basket()
	{
		// get site id
		$site = $this->config->item('template'); // config
		
		// log interaction
		$this->load->model('global_model');
		$this->global_model->log_interactions();	
		
		// add to order
		$this->load->model('checkout_model');
		$product_id = $this->uri->segment(4, 0);
		$result = $this->checkout_model->add_product_to_basket($site, $product_id);

		// get redirect page
		$_SESSION['redirect_continue_shopping'] = $this->uri->segment(6)."=="; // base64
		redirect('checkout/basket');
	}
	
	public function view_basket()
	{
		// get redirect page
		$_SESSION['redirect_continue_shopping'] = $this->uri->segment(5)."=="; // base64
		redirect('checkout/basket');
	}
	
	public function details()
	{
		// enable profiler
		// $this->output->enable_profiler(TRUE);
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);	
		
		// load checkout model
		$this->load->model('checkout_model');
		$data['delivery_rates'] = $this->checkout_model->delivery_rates($site);
		
		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');				

		// if we have post values
		$checkout_details_form = $this->input->post('checkout_details_form_submit');
		if( isset( $checkout_details_form ) ) {

			// form validation rules
			
			// do we have a guest email address?
			// ( note that if the user is logged in this wont be an option )
			$guest_email = $this->input->post('guest_email');
			if(!empty($guest_email)) {
				$this->form_validation->set_rules('guest_email', 'Email Address', 'required|valid_email');	
			}	
			
			// billing details
			$this->form_validation->set_rules('billing_first_name', 'First Name', 'required');
			$this->form_validation->set_rules('billing_last_name', 'Last Name', 'required');			
			$this->form_validation->set_rules('billing_house_number', 'House Number', 'required');
			$this->form_validation->set_rules('billing_street_name', 'Street Name', 'required');
			$this->form_validation->set_rules('billing_town_city', 'Town/City', 'required');
			$this->form_validation->set_rules('billing_country', 'Country', 'required');
			$this->form_validation->set_rules('billing_postcode', 'Postcode', 'required');
			$this->form_validation->set_rules('billing_telephone', 'Telephone', 'required');
			
			// optional validation
			$this->form_validation->set_rules('order_store_credit', 'Store credit', 'numeric');
			
			// delivery method
			$this->form_validation->set_rules('delivery_rates', 'Delivery Method', 'required');	
			
			// terms and conditions
			$this->form_validation->set_rules('agree_to_terms', 'Terms & Conditions', 'required');	
			
			if ($this->form_validation->run() === FALSE)
			{	
				// highlite errors
				$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
			}
			else 
			{
				// alls good if we are here
				// add customer details to order
				$customer_details = $this->input->post();
				$result = $this->checkout_model->set_customer_details($customer_details);
				$data['payment_data'] = $result ;		

				// load template		
				$this->output->enable_profiler(FALSE);		
				$this->load->view($site.'/header', $data);				
				$this->load->view($site.'/checkout/confirm', $data);
				$this->load->view($site.'/footer', $data);						
				return;
			}
		}

		// show basket contents
		$data['products'] = $this->checkout_model->get_products_in_basket($site);
		
		$total_cost = 0;
		$total_items = 0;
		
		if(!empty($data['products'])) {
			foreach($data['products'] as $product) {
				
				$product_cost = ( $product['cost'] * $_SESSION['site']['currency_rate'] );
				$product_cost = number_format((float)($product_cost), 2, '.', '');
				
				$total_cost += number_format((float)( $product['qty'] * $product_cost), 2, '.', '');
				$total_items += $product['qty'];
			}
		}
		
		$data['total_cost'] = number_format((float)$total_cost, 2, '.', '');
		$data['total_items'] = $total_items;			

		// if voucher has been used apply discount
		if ( ! empty( $_SESSION['customer']['voucher_is_valid'] ) ) {
			
			// money off
			if( $_SESSION['customer']['voucher_discount_type'] == 1 ) {
				$less_discount = $_SESSION['customer']['voucher_discount'];
				$data['less_discount'] = "-".number_format( (float) $less_discount, 2, '.', '');
			}
			// percentage off
			if( $_SESSION['customer']['voucher_discount_type'] == 2 ) {
				$less_discount = $total_cost * ( $_SESSION['customer']['voucher_discount'] / 100 );
				$data['less_discount'] = "-".number_format( (float) $less_discount, 2, '.', '');		
			}

			// set new total cost
			$data['total_cost'] = number_format( (float) ( $total_cost - $less_discount ), 2, '.', '');			
		}		
		
		// load template
		$this->load->view($site.'/header', $data);
		$this->load->view($site.'/checkout/details', $data);
		$this->load->view($site.'/footer', $data);		
	}
	
	function result ($id = "") {
		
		// enable profiler
		$this->output->enable_profiler(TRUE);
		
		// get site id
		$site = $this->config->item('template'); // config	

		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);		

		// load checkout model
		$this->load->model('checkout_model');	
		
		// get the order id for this payment
		$order_number = (!empty($_POST['order_id'])) ? $_POST['order_id'] : 0;		

		/**** DEBUG - TESTING ONLY ****/
		$result = "SUCCESS"; // for debug only, we need to replace this value with the actual result		
		$order_number = "153985418988870";
		/***********************************/
		
		// check if we have a successful payment	
		$payment_status = (!empty($result) && $result == "SUCCESS") ? TRUE : FALSE;

		// we should also check to see if the total the customer paid is the 
		// the same amount as the order that was created ( before we sent 
		// the data to the payment processor ) this ensures no fraud payment!
		
		// [ -- do your checks here -- ]
		
		// if payment status is success
		if($payment_status && $order_number > 0) 
		{			
			// update all products stock_on_hold values for each product purchased			
			$this->checkout_model->update_stock_on_hold_for_order($order_number);	
			
			// update the order status from "pending payment" to "confirming order"
			$this->checkout_model->update_order_status($order_number);
			
			// send the customer an email with details of their purchase
			$data['email_sent'] = $this->checkout_model->send_order_confirmation_email($order_number);			
		}
		else 
		{
			$payment_status = FALSE;
		}

		// display a template page dependent on payment status
		$status_page  = ($payment_status) ? "success" : "failed";		
		
		// finally, load template
		$this->load->view($site.'/header', $data);
		$this->load->view($site.'/checkout/'.$status_page, $data);
		$this->load->view($site.'/footer', $data);				
		
	}
	
}
