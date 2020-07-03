<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_cms_model extends CI_Model {

	public function set_cms_page($data = "")
	{
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session		
		
		$this->load->helper('url');
		
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		
		$insert = array(
						'site' => $site,
						'title' => $this->input->post('title'),
						'slug' => $slug,
						'text' => $this->input->post('text'),
						'meta_title' => $this->input->post('meta_title'),
						'meta_description' => $this->input->post('meta_description'),
						'redirect' => $this->input->post('redirect'),
						'canonical' => $this->input->post('canonical'),						
						'active' => $this->input->post('status')						
					);

		$this->db->insert('cms_pages', $insert);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_cms_model', 'set_cms_page', 'insert',  print_r($insert, TRUE));

		// add revision
		$insert_id = $this->db->insert_id();
		$update = array(
						'revision' => $insert_id
					);
		$this->db->where('id', $insert_id);
		$this->db->update('cms_pages', $update);

        // log admin action
        $this->global_model->admin_action('Admin_cms_model', 'set_cms_page', 'update',  print_r($update, TRUE));

		return "success";
	}
	
	public function update_cms_page($data = "")
	{
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		$this->load->helper('url');
		
		$slug = url_title($data['cms_pages']['slug'], 'dash', TRUE);

		$insert = array( 
						'revision' => $this->input->post('revision'),
						'site' => $site,
						'title' => $this->input->post('title'),
						'slug' => $slug,
						'last_updated' => date("Y-m-d H:i:s"),
						'text' => $this->input->post('text'),
						'meta_title' => $this->input->post('meta_title'),
						'meta_description' => $this->input->post('meta_description'),
						'redirect' => $this->input->post('redirect'),
						'canonical' => $this->input->post('canonical'),								
						'active' => $this->input->post('status')						
					);

		$result = $this->db->insert('cms_pages', $insert);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_cms_model', 'update_cms_page', 'insert',  print_r($insert, TRUE));

        return $result;
	}
}
