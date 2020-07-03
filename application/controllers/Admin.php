<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index() {

		// profiler
		// $this->output->enable_profiler(TRUE);
		
		$_SESSION['admin']['logged_in'] = FALSE;
		
		// set site
		$site = $this->config->item('template'); // config
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin_model');		
		
		$data['admin_login'] = $this->input->post();
		
		if(!empty($data['admin_login'])) {

			$this->form_validation->set_rules('login_username', 'Username', 'required');
			$this->form_validation->set_rules('login_password', 'Password', 'required');
		
			if ($this->form_validation->run() === FALSE)
			{	
				// highlite errors
				$this->form_validation->set_error_delimiters('<p class="required_field">', '</p>');
			}
			else 
			{
				// no errors, lets check this user exists and log them in
				$this->load->model('admin_model');
				$result = $this->admin_model->do_login($data);
				$admin_details = $this->admin_model->get_admin_details($data);
						
				// successful login
				if($result == "login_true") {
					$_SESSION['admin']['id'] = $admin_details[0]['id'];
					redirect('/admin/security_check');	
				}
				// unsuccessful login				
				elseif ($result == "login_false") {
					$this->session->set_flashdata('msg', 'Login error: Incorrect login details');
					redirect('/admin');							
				}
				// exceeded login attempts
				elseif ($result == "login_attempts_exceeded") {
					$this->session->set_flashdata('msg', 'Login error: Max login attempts exceeded.');
					redirect('/admin');					
				}
				// no active account
				else {
					$this->session->set_flashdata('msg', 'Login error: No active account found.');
					redirect('/admin');							
				}
			}
		}
		
		$this->load->view('admin/home/index');

	}
	
	public function security_check () {
		
		// profiler
		// $this->output->enable_profiler(TRUE);
		
		$_SESSION['admin']['logged_in'] = FALSE;
		
		// set site
		$site = $this->config->item('template'); // config
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin_model');		
		
		$data['admin_security_check'] = $this->input->post();

		if( ! empty( $data['admin_security_check'] ) ) 
		{
			$this->load->model('admin_model');
			$result = $this->admin_model->get_security_word();

			// if security check is passed
			if( $result == "success" ) 
			{
				$_SESSION['admin']['logged_in'] = TRUE;
				redirect('/admin/dashboard');
			}
			else 
			{	
				// inform user that they failed security checks and lock down account ( set status to inactive )
				$this->session->set_flashdata('msg', 'Security check failed. Please contact an administrator for assistance.');
						
				// redirect to login
				redirect('/admin/security_check');				
			}
		}
		// load template files			
		$this->load->view('admin/home/security_check');

		// else clear all session values
		unset( $_SESSION );		
	}
	
	public function dashboard() {
		
		// profiler
		// $this->output->enable_profiler(TRUE);
		
		// check admin is logged in
		$this->load->model('admin_global');
		$this->admin_global->check_status();
		
		// switch between sites
		$switch_site = $this->input->get('site');
		if(!empty($switch_site)) {
			$_SESSION['admin']['site'] = $switch_site;
		}
		
		// default site
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// data 
		$data['site'] = strtoupper($site);
		
		// load template files
		$this->load->view('admin/header');
		$this->load->view('admin/dashboard/index', $data);
		$this->load->view('admin/footer');		
	}
	
	public function login() {
		$this->index();
	}
	
	public function logout () {
		
		// clear all session values
		unset( $_SESSION );
		
		// redirect to login
		redirect('/admin');				
	}	
	
	
}
