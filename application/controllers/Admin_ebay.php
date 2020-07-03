<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_ebay extends CI_Controller {
	
	public function index()
	{
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// data to be passed to view file
		$data['foo'] = "";		
		
		// load template
		$this->load->view('admin/header');
		$this->load->view('admin/ebay/index', $data);
		$this->load->view('admin/footer');				
	}

}
