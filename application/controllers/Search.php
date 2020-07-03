<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
	
	public function index()
	{
		// enable profiler
		// $this->output->enable_profiler(TRUE);
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);	

		// load products
		$this->load->model('search_model');
		$data['products'] = $this->search_model->get_category_products($site);
		$total_products = (!empty($data['products']['products'])) ? count($data['products']['products']) : 0;

		// load filters
		$this->load->model('filter_model');		
		$data['filter']['departments'] = $this->filter_model->product_categories($site);
		$data['filter']['brands'] = $this->filter_model->product_brands($site);
		$data['filter']['cost'] = $this->filter_model->product_cost($site, $total_products);
		
		// load template
		$this->load->view($site.'/header', $data);
		$this->load->view($site.'/search/index', $data);
		$this->load->view($site.'/footer', $data);
	}
}
