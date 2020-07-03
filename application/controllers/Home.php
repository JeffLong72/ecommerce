<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function index()
	{
		// enable profiler
		// $this->output->enable_profiler(TRUE);
		
		// cache web page ( 15 mins )
		// $this->output->cache( 15 );
		
		$site = $this->config->item('template'); // config

		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);
		
		// get trending products
		$this->load->model('products_model');
		$data['products'] = $this->products_model->get_trending_products($site);
		
		// meta details
		$this->load->model('cms_model');		
		$data['meta'] = $this->cms_model->get_home_meta($site);
		$data['site_name'] =$this->config->item('site_name');
		$data['title'] = (!empty($data['meta'][0]['meta_title'])) ?  $data['meta'][0]['meta_title'] : "";	
		$data['description'] = (!empty($data['meta'][0]['meta_description'])) ? $data['meta'][0]['meta_description'] : "";	
		
		// load template 
		$this->load->view($site.'/header', $data);
		$this->load->view($site.'/home/index', $data);
		$this->load->view($site.'/footer', $data);
	}
}
