<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_cms extends CI_Controller {

	public function index()
	{
		$this->output->enable_profiler(TRUE);


		
		$this->load->view('admin/header');
		$this->load->view('admin/cms/index'); 
		$this->load->view('admin/footer');
	}
	
	public function all()
    {
        // enable profiler
        // $this->output->enable_profiler(TRUE);

        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        $site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

		$query = $this->db->query('select domain 
									from cms_sites 
									where template = "'.$site.'"
									and active = 1');
		$result = $query->result_array();
		$data['domain'] = (!empty($result[0]['domain'])) ? $result[0]['domain'] : "";
		
		$query = $this->db->query('SELECT * FROM cms_pages a
									WHERE id = (
										SELECT max(ID) FROM cms_pages b WHERE a.revision = b.revision AND site = "'.$site.'"
									)
									AND site = "'.$site.'"
									ORDER BY id DESC');
		$result = $query->result_array();
		$data['cms_pages'] = $result;

		$this->load->view('admin/header');
		$this->load->view('admin/cms/all', $data);
		$this->load->view('admin/footer');		
	}
	
	public function create()
	{
        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin_cms_model');

		$data['cms_pages'] = $this->input->post();

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('slug', 'Url', 'required');
				
		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('admin/header');
			$this->load->view('admin/cms/create', $data);
			$this->load->view('admin/footer');

		}
		else
		{
			$this->admin_cms_model->set_cms_page($data);
			$this->session->set_flashdata('msg', 'Success: New CMS page created');
			redirect('/admin/cms/all');
		}
	}
	
	public function edit($id="")
	{
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		$query = $this->db->query('select domain 
									from cms_sites 
									where template = "'.$site.'"
									and active = 1');
		$result = $query->result_array();
		$data['domain'] = $result[0]['domain'];
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin_cms_model');

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('slug', 'Url', 'required');
		
		$data['cms_pages'] = $this->input->post();
		
		if ($this->form_validation->run() === FALSE)
		{
			if(empty($data['cms_pages'])) {
				
				// get current data 
				$query = $this->db->query('SELECT * FROM cms_pages WHERE id = '.(int)$id.' AND site = "'.$site.'" ORDER BY id DESC');
				$result = $query->result_array();
				$data['cms_pages'] = $result[0];
				
				// get revisions
				$query = $this->db->query('SELECT * FROM cms_pages WHERE id != '.(int)$id.' AND revision = '.(int)$data['cms_pages']['revision'].' AND site = "'.$site.'" ORDER BY last_updated DESC');
				$result = $query->result_array();
				$data['cms_pages']['revisions'] = $result;
			}
		
			$data['cms_pages']['id'] = (!empty($data['cms_pages']['id'])) ? $data['cms_pages']['id'] : $id;
			
			$this->load->view('admin/header');
			$this->load->view('admin/cms/edit', $data);
			$this->load->view('admin/footer');

		}
		else
		{
			$this->admin_cms_model->update_cms_page($data);
			$this->session->set_flashdata('msg', 'Success: CMS page has been updated');
			redirect('/admin/cms/all');
		}
	}
}
