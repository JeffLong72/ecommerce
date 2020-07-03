<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	public function index() {
		// is user session doesnt exist redirect to login
		if(empty($_SESSION['customer']['details'])) {
			redirect( base_url()."user/login.html");
		}
		// else redirect to account
		else {
			redirect( base_url()."user/orders.html");
		}
	}		

	public function login() {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);			

		// load products
		$user = array();
		$user['email'] =  ( ! empty( $_POST['login_email'] ) ) ? $_POST['login_email'] : "";
		$user['password'] =  ( ! empty( $_POST['login_password'] ) ) ? $_POST['login_password'] : "";
		
		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');		

		$this->form_validation->set_rules('login_email', 'Email Address', 'required|valid_email');	
		$this->form_validation->set_rules('login_password', 'Password', 'required');			
				
		if ($this->form_validation->run() === FALSE)
		{	
			// highlite errors
			$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
		}
		else 
		{
			$this->load->model('user_model');
			$result = $this->user_model->do_login($site, $user, "customer-area");	
			
			// no user details found
			if(empty($result) || ( ! empty($result['error']) && $result['error'] == "login_false" ) ) {
					$data['login_error'] = "No user details found";
			}
			
			// incorect login details
			if(!empty($result) && ! empty( $result['error'] ) == "login_false" ) {
					$data['login_error'] = "Incorrect login details";
			}
			
			// login success
			if(!empty($result) && ! empty( $result['result'] ) == "login_true" ) {
					redirect( base_url()."user/orders.html" );
			}			
		}		

		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/login',$data);
		$this->load->view($site.'/footer');			

	}
	
	public function logout() {
		// clear all session data
		$this->session->sess_destroy();
		redirect( base_url()."user/login.html");
	}	
	
	public function account() {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);

		// check user is logged in so they can access this feature
		$this->load->model('user_model');
		$this->user_model->check_login($site);

		// include user menu
		$data['user_menu'] = $this->load->view($site.'/user/menu', $data, TRUE);
		
		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/account',$data);
		$this->load->view($site.'/footer');			
	}	

	public function orders() {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);

		// check user is logged in so they can access this feature
		$this->load->model('user_model');
		$this->user_model->check_login($site);		
		
		// get user orders 
		$data['orders'] = $this->user_model->get_user_orders();				

		// include user menu
		$data['user_menu'] = $this->load->view($site.'/user/menu', $data, TRUE);
		
		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/orders',$data);
		$this->load->view($site.'/footer');			
		
	}
	
	public function wishlist() {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);

		// check user is logged in so they can access this feature
		$this->load->model('user_model');
		$this->user_model->check_login($site);		
		
		// get wishlist items
		$data['wishlist'] = $this->user_model->get_wishlist_items();		

		// include user menu
		$data['user_menu'] = $this->load->view($site.'/user/menu', $data, TRUE);
		
		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/wishlist',$data);
		$this->load->view($site.'/footer');				
		
	}
	
	public function store_credit() {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);

		// check user is logged in so they can access this feature
		$this->load->model('user_model');
		$this->user_model->check_login($site);		

		// include user menu
		$data['user_menu'] = $this->load->view($site.'/user/menu', $data, TRUE);
		
		// get store credit value
		$data['user'] = $this->user_model->store_credit();	
		
		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/store_credit',$data);
		$this->load->view($site.'/footer');				
		
	}	

	public function newsletter() {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);

		// check user is logged in so they can access this feature
		$this->load->model('user_model');
		$this->user_model->check_login($site);	

		// get user newsletter subscriptions
		$data['newsletters'] = $this->user_model->newsletter();			

		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');		

		$this->form_validation->set_rules('newsletter_email', 'Email Address', 'required|valid_email');		
				
		if ($this->form_validation->run() === FALSE)
		{	
			// highlite errors
			$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
		}
		else 
		{
			$this->load->model('user_model');
			$result = $this->user_model->update_newsletter_subscription();	

			redirect( current_url() );
		}	
		
		// include user menu
		$data['user_menu'] = $this->load->view($site.'/user/menu', $data, TRUE);
		
		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/newsletter',$data);
		$this->load->view($site.'/footer');				
		
	}

	public function personal() {

		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);

		// check user is logged in so they can access this feature
		$this->load->model('user_model');
		$this->user_model->check_login($site);	
	
		// include user menu
		$data['user_menu'] = $this->load->view($site.'/user/menu', $data, TRUE);
		
		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');		
		
		$this->form_validation->set_rules('billing_first_name', 'First Name', 'required');
		$this->form_validation->set_rules('billing_last_name', 'Last Name', 'required');			
		$this->form_validation->set_rules('billing_house_number', 'House Number', 'required');
		$this->form_validation->set_rules('billing_street_name', 'Street Name', 'required');
		$this->form_validation->set_rules('billing_town_city', 'Town/City', 'required');
		$this->form_validation->set_rules('billing_country', 'Country', 'required');
		$this->form_validation->set_rules('billing_postcode', 'Postcode', 'required');
		$this->form_validation->set_rules('billing_telephone', 'Telephone', 'required');	
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');		
		
		if ($this->form_validation->run() === FALSE)
		{	
			// highlite errors
			$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
		}
		else 
		{
			$this->load->model('user_model');
			$result = $this->user_model->personal();
			
			$this->session->set_flashdata('msg', 'Success: Your details have been updated');
			redirect( base_url()."user/personal.html" );
		}

		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/personal',$data);
		$this->load->view($site.'/footer');				
		
	}	
	
	public function register  () {
		
		// we dont want the user accessing this page after they have logged in
		if(!empty($_SESSION['customer']['details'])) {
			redirect( base_url()."user/orders.html" );	
		}
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);
		
		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');		
		
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');		
		$this->form_validation->set_rules('customer_password', 'Password', 'required|min_length[8]');		
		$this->form_validation->set_rules('confirm_customer_password', 'Confirm Password', 'required|matches[customer_password]|min_length[8]');			
		
		if ($this->form_validation->run() === FALSE)
		{	
			// highlite errors
			$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
		}
		else 
		{
			$this->load->model('user_model');
			$result = $this->user_model->register();
			
			if($result) {
				$this->session->set_flashdata('msg', 'Success: Your account has been registered, please login!');
				redirect( base_url()."user/login.html" );	
			}
			else {
				$this->session->set_flashdata('msg', 'Sorry, this email address is already registered.');
				redirect( base_url()."user/register.html" );					
			}
		}		

		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/register',$data);
		$this->load->view($site.'/footer');			
	}
	
	public function forgot_password () {
		
		// we dont want the user accessing this page after they have logged in
		if(!empty($_SESSION['customer']['details'])) {
			redirect( base_url()."user/orders.html" );	
		}		
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);
		
		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');		
		
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');		
		
		if ($this->form_validation->run() === FALSE)
		{	
			// highlite errors
			$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
		}
		else 
		{
			// TODO: 
			// user enters their email address ONLY in a form
			// an email is sent to that email address which contains a link to a reset password page
			$this->load->model('user_model');
			$result = $this->user_model->forgot_password();	
			
			if(!empty($result)) {
				$data['result'] = "sent";
			}
			else {
				$data['result'] = "not sent";				
			}
			
			// user enters a new password, 
			// user details are updated ( use update_password function below )
			// display confirmation message on page and link to login page
		}			

		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/forgot_password',$data);
		$this->load->view($site.'/footer');	
		
	}	
	
	function confirm_user_email () {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);	

		// confirm this users email address
		$this->load->model('user_model');
		$result = $this->user_model->confirm_user_email();	
		if($result) {
			$data['result'] = TRUE;
		}
		
		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/confirm_user_email',$data);
		$this->load->view($site.'/footer');			
	}
	
	function reset_password () {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);	

		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');		
		
		$this->form_validation->set_rules('customer_password', 'Password', 'required');		
		$this->form_validation->set_rules('confirm_customer_password', 'Confirm Password', 'required|matches[customer_password]');				
		
		if ($this->form_validation->run() === FALSE)
		{	
			// highlite errors
			$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
		}
		else 
		{
			$this->load->model('user_model');
			$result = $this->user_model->reset_password();	
			
			if(!empty($result)) {
				$data['result'] = "password_update_success";
			}
			else {
				$data['result'] = "password_update_failed";				
			}		
		}

		// load view files
		$this->load->view($site.'/header',$data);
		$this->load->view($site.'/user/reset_password',$data);
		$this->load->view($site.'/footer');			
		
	}
	
	function print_invoice_pdf( $order_id ="" ) {
		
		// load models
		$this->load->model('global_model');					
		
		// print invoice PDF
		$this->global_model->create_invoice_pdf( $order_id );		
		
	}

}
