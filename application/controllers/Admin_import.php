<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_import extends CI_Controller {
	
	public function index () {
		
		$this->load->view('admin/header');
		$this->load->view('admin/import/index');
		$this->load->view('admin/footer');			
	}

	public function products () {

        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();
		
		// $this->output->enable_profiler(TRUE);
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session		

		$this->load->helper(array('form', 'url'));	

        $config['upload_path'] = './uploads/'.$site.'/imports/products_data/';
        $config['allowed_types'] = 'csv';

        $this->load->library('upload', $config);

		$data = array();
		
        if ( ! $this->upload->do_upload('import_file') ) 
		{
            $data['error'] = $this->upload->display_errors();
        }
        else 
		{
			$this->load->model('admin_import_model');
			$data['result'] = $this->admin_import_model->import_products($this->upload->data());
        }
		
		$this->load->view('admin/header');
		$this->load->view('admin/import/products', $data);
		$this->load->view('admin/footer');		
		
	}
	
	public function stock () {

        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        // enable profiler
        //$this->output->enable_profiler(TRUE);

        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

		$this->load->helper(array('form', 'url'));	

        $config['upload_path'] = './uploads/'.$site.'/imports/stock_data/';
        $config['allowed_types'] = 'csv';

        $this->load->library('upload', $config);

		$data = array();
		
        if ( ! $this->upload->do_upload('import_file') ) 
		{
            $data['error'] = $this->upload->display_errors();
        }
        else 
		{
			$this->load->model('admin_import_model');
			$data['result'] = $this->admin_import_model->import_stock($this->upload->data());
        }		
	
        // load template
        $this->load->view('admin/header');
        $this->load->view('admin/import/stock',$data);
        $this->load->view('admin/footer');
	}

}
