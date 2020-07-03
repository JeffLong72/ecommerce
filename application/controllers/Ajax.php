<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {
	
	public function get_products()
	{
		// enable profiler
		//$this->output->enable_profiler(TRUE);

		// get site id
		$site = $this->config->item('template'); // config

		// load products
		$keyword = ( ! empty( $_GET['keyword'] ) ) ? $_GET['keyword'] : "";
		$this->load->model('ajax_model');
		$this->ajax_model->get_products($site, $keyword);
	}
	
	public function check_login () {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load products
		$data['email'] =  ( ! empty( $_POST['login_email'] ) ) ? $_POST['login_email'] : "";
		$data['password'] =  ( ! empty( $_POST['login_password'] ) ) ? $_POST['login_password'] : "";
		
		// login user
		$this->load->model('user_model');
		$this->user_model->do_login($site, $data);	
		
	}
	
	public function update_currency () {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);

		// get site id
		$site = $this->config->item('template'); // config
		
		// load currency
		$data['currency'] =  ( ! empty( $_POST['currency'] ) ) ? $_POST['currency'] : "";
		
		// update currency
		$this->load->model('global_model');
		$this->global_model->switch_currency($site, $data);	
		
	}	
	
	public function update_log_interactions () {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);		
		
		// get data tag
		$data['data_tag'] =  ( ! empty( $_POST['data_tag'] ) ) ? $_POST['data_tag'] : "";
		$data['current_url'] =  ( ! empty( $_POST['current_url'] ) ) ? $_POST['current_url'] : "";
		$data['page_title'] =  ( ! empty( $_POST['page_title'] ) ) ? $_POST['page_title'] : "";
		
		// update log interactions
		$this->load->model('global_model');
		$this->global_model->log_interactions($data);	
		
	}
	
	public function add_review () {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);			
		
		// update log interactions
		$this->load->model('review_model');
		$this->review_model->add_new_review();			
	}
	
	public function add_notification_of_item_back_in_stock () {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);			
		
		// update log interactions
		$this->load->model('products_model');
		$this->products_model->add_notification_of_item_back_in_stock();			
	}	
	
	public function get_product_attributes () {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);	
		
		// get specific post attribute
		$attribute =  ( ! empty( $_POST['attribute'] ) ) ? $_POST['attribute']  : "";	
		
		// get all attributes from config
		$data['product_attributes'] = $this->config->item('product_attributes');		
		
		// display specific array
		if( array_key_exists($attribute, $data['product_attributes']['all']) ) 
		{
			echo json_encode($data['product_attributes'][$attribute]);
		}
		
	}		
	
	public function get_product_by_sku() {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);	

		// update log interactions
		$this->load->model('products_model');
		$data = $this->products_model->get_product_by_sku();

		// return JSON array
		echo json_encode($data);
		
	}
	
	public function get_related_product_images () {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);			
		
		// update log interactions
		$this->load->model('products_model');
		$data = $this->products_model->get_related_product_images();		
	}
	
	public function add_to_wishlist () {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);			
		
		// update log interactions
		$this->load->model('user_model');
		$data = $this->user_model->add_to_wishlist();		
	}		
	
}
