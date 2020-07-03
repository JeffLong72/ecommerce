<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends CI_Controller {

	public function index()
	{
		// enable profiler
		// $this->output->enable_profiler(TRUE);
		
		// get site id
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	
		
		// load menu
		$this->load->model('menu_model');
		$data['menu'] = $this->menu_model->get_menu($site);		
		
		// is this a cms page?	
		$this->load->model('cms_model');
		$data['rows'] = $this->cms_model->get_cms_page($site, $this->uri->segment(1));
		if(!empty($data['rows'])) {
			// load page widgets ( if any )
			$this->load->model('widgets_model');
			$data['rows'][0]['text'] = $this->widgets_model->get_widgets_in_page($data['rows'][0]['text']);
			// set meta details
			$data['site_name'] =$this->config->item('site_name');
			$data['title'] = $data['rows'][0]['title'];		
			$data['description'] = $data['rows'][0]['meta_description'];	
			// load view
			$this->load->view($site.'/header',$data);
			$this->load->view($site.'/cms/index',$data);
			$this->load->view($site.'/footer');	
			return;
		}
		
		// or, is this a product page?	
		$this->load->model('products_model');
		$data['products'] = $this->products_model->get_products_page($site, $this->uri->segment(1));
		if(!empty($data['products'])) {
			$data['related_attributes'] = $this->products_model->get_related_attribute_products($site, $data['products'][0]['sku']);
			$data['reviews'] = $this->products_model->get_products_reviews($site, $data['products'][0]['sku']);
			$data['site_name'] =$this->config->item('site_name');
			$data['title'] = $data['products'][0]['title'];		
			$data['description'] = $data['products'][0]['meta_description'];		
			$this->load->view($site.'/header',$data);
			$this->load->view($site.'/products/index',$data);
			$this->load->view($site.'/footer');	
			return;			
		}

		// or, is this a category/sub-category page?	
		// load category page
		$this->load->helper('url');
		$url_sections = $this->uri->total_segments();
		$url_segments = $this->uri->segment_array();
		// sort by filters
		$sort_by_array = array("all", "newest", "price-low-high", "price-high-low", "discount", "most-popular", "brand-a-z", "brand-z-a");
		// set category
		if(!empty($url_segments[2]) && in_array($url_segments[2], $sort_by_array)) {
			$url_sections = 1;
		}
		// set sub category
		if(!empty($url_segments[3]) && in_array($url_segments[3], $sort_by_array)) {
			$url_sections = 2;
		}
		// set sub sub category
		if(!empty($url_segments[4]) && in_array($url_segments[4], $sort_by_array)) {
			$url_sections = 3;
		}	
		// get page data
		switch($url_sections) {
			case 1:
				$data['rows'] = $this->cms_model->get_main_category_page($site, $this->uri->segment(1));
				$menu_id = (!empty($data['rows'][0]['id'])) ? $data['rows'][0]['id'] : "";
				$data['products'] = $this->products_model->get_category_products($site, $menu_id);
				$section = "category";
				break;
			case 2:
				$data['rows'] = $this->cms_model->get_sub_level_one_category_page($site, $this->uri->segment(2));
				$menu_id = (!empty($data['rows'][0]['id'])) ? $data['rows'][0]['id'] : "";				
				$data['products'] = $this->products_model->get_category_products($site, $menu_id);				
				$section = "category";
				break;
			case 3:
				$data['rows'] = $this->cms_model->get_sub_level_two_category_page($site, $this->uri->segment(3));
				$menu_id = (!empty($data['rows'][0]['id'])) ? $data['rows'][0]['id'] : "";				
				$data['products'] = $this->products_model->get_category_products($site, $menu_id);				
				$section = "category";
				break;				
			default:
				$section = "";
		}
		
		// load categories for filter
		$this->load->model('filter_model');			
		$data['filter']['departments'] = $this->filter_model->product_categories($site);
		$data['filter']['brands'] = $this->filter_model->product_brands($site);
		$data['filter']['cost'] = $this->filter_model->product_cost($site);	
		
		// load template
		if(!empty($data['rows'])) {	
			$data['site_name'] =$this->config->item('site_name');
			$data['title'] = (!empty($data['rows'][0]['meta_title'])) ? $data['rows'][0]['meta_title'] : "";		
			$data['description'] = (!empty($data['rows'][0]['meta_description'])) ? $data['rows'][0]['meta_description'] : "";	
			$this->load->view($site.'/header',$data);
			$this->load->view($site.'/'.$section.'/index',$data);
			$this->load->view($site.'/footer');
			return;			
		}

		// No page data found? Lets show a 404 notice instead
		// ( we could also redirect here, or log url, etc )
		if(empty($data['rows'])) {
			$data['site_name'] =$this->config->item('site_name');
			$data['title'] = "404";		
			$data['description'] = "Oops, this page couldnt be found!";				
			$this->load->view($site.'/header',$data);
			$this->load->view($site.'/404/index',$data);
			$this->load->view($site.'/footer');	
		}
		
	}

}