<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	public function index()
	{
		// enable profiler
		// $this->output->enable_profiler(TRUE);
		
		$site = $this->config->item('template'); // config
		$data['site'] = $site;		
		
		$template = $this->config->item('template');
		
		$per_page = 10;
		
		$this->load->model('blog_model');
		$data['count'] = $this->blog_model->count_all_blogs($template);
		$data['rows'] = $this->blog_model->get_all_blogs($template, '', $per_page);
		
		$this->load->library('pagination');

		$config['base_url'] = base_url().'blog/all/';
		$config['total_rows'] = (!empty($data['count'])) ? count($data['count']) : 0;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;
		$config['page_query_string'] = FALSE;
		$config['cur_tag_open'] = '&nbsp;<a class="current">';
		$config['cur_tag_close'] = '</a>';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		
		$meta['site_name'] =$this->config->item('site_name');
		$meta['title'] = 'Blog';		
		$meta['description'] = '';
		
		// load menu
		$this->load->model('menu_model');
		$meta['menu'] = $this->menu_model->get_menu($template);
		
		// load recent blogs
		$data['latest_blogs'] = $this->blog_model->get_latest_blogs( $site );		

		// load recent blogs
		$data['blogs_by_date'] = $this->blog_model->get_all_blogs_by_date( $site );			

		$this->load->view($template.'/header',$meta);
		$this->load->view($template.'/blog/index',$data);
		$this->load->view($template.'/footer');
	}
	
	public function all($id="")
	{
		if(empty($id)){
			redirect(base_url()."blog");
		}
		
		// $this->output->enable_profiler(TRUE);
		
		$site = $this->config->item('template'); // config
		$data['site'] = $site;		
		
		$template = $this->config->item('template');
		
		$per_page = 10;

		$this->load->model('blog_model');
		$data['count'] = $this->blog_model->count_all_blogs($template);
		$data['rows'] = $this->blog_model->get_all_blogs($template, $id, $per_page);
		
		$this->load->library('pagination');

		$config['base_url'] = base_url().'blog/all/';
		$config['total_rows'] = (!empty($data['count'])) ? count($data['count']) : 0;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;
		$config['page_query_string'] = FALSE;
		$config['cur_tag_open'] = '&nbsp;<a class="current">';
		$config['cur_tag_close'] = '</a>';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$meta['site_name'] =$this->config->item('site_name');		
		$meta['title'] = 'Blog';		
		$meta['description'] = '';
		
		// load menu
		$this->load->model('menu_model');
		$meta['menu'] = $this->menu_model->get_menu($template);
		
		// load recent blogs
		$data['latest_blogs'] = $this->blog_model->get_latest_blogs( $site );	

		// load recent blogs
		$data['blogs_by_date'] = $this->blog_model->get_all_blogs_by_date( $site );			
	
		$this->load->view($template.'/header',$meta);
		$this->load->view($template.'/blog/index',$data);
		$this->load->view($template.'/footer');
	}
	
	public function view($id = "") {
		
		// $this->output->enable_profiler(TRUE);
		
		$site = $this->config->item('template'); // config
		$data['site'] = $site;
		
		$template = $this->config->item('template');
		
		$this->load->model('blog_model');
		$data['rows'] = $this->blog_model->get_blog_page($template, $id);

		$meta['site_name'] =$this->config->item('site_name');
		$meta['title'] = $data['rows'][0]['title'];		
		$meta['description'] = $data['rows'][0]['meta_description'];
		
		// load menu
		$this->load->model('menu_model');
		$meta['menu'] = $this->menu_model->get_menu($template);
		
		// load recent blogs
		$data['latest_blogs'] = $this->blog_model->get_latest_blogs( $site );		
		
		// load recent blogs
		$data['blogs_by_date'] = $this->blog_model->get_all_blogs_by_date( $site );				
		
		$this->load->view($template.'/header',$meta);
		$this->load->view($template.'/blog/view',$data);
		$this->load->view($template.'/footer');		
	}
	
	public function search()
	{
		
		// $this->output->enable_profiler(TRUE);
		
		$site = $this->config->item('template'); // config
		$data['site'] = $site;		
		
		// get post date range
		$date_range = $this->input->post('blog_select_month_year');
		$_SESSION['blog']['date_range'] = $date_range;
		
		$per_page = 10;

		$this->load->model('blog_model');
		$data['count'] = $this->blog_model->count_all_blogs_by_date_range($site, $date_range);
		$data['rows'] = $this->blog_model->get_all_blogs_by_date_range($site, $date_range);
		
		$this->load->library('pagination');

		$config['base_url'] = base_url().'blog/all/';
		$config['total_rows'] = (!empty($data['count'])) ? count($data['count']) : 0;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;
		$config['page_query_string'] = FALSE;
		$config['cur_tag_open'] = '&nbsp;<a class="current">';
		$config['cur_tag_close'] = '</a>';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$meta['site_name'] =$this->config->item('site_name');		
		$meta['title'] = 'Blog';		
		$meta['description'] = '';
		
		// load menu
		$this->load->model('menu_model');
		$meta['menu'] = $this->menu_model->get_menu($site);
		
		// load recent blogs
		$data['latest_blogs'] = $this->blog_model->get_latest_blogs( $site );		

		// load recent blogs
		$data['blogs_by_date'] = $this->blog_model->get_all_blogs_by_date( $site );			
	
		$this->load->view($site.'/header',$meta);
		$this->load->view($site.'/blog/search',$data);
		$this->load->view($site.'/footer');
	}	
}
