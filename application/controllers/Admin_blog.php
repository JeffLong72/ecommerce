<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_blog extends CI_Controller {

	public function index()
	{
		$this->output->enable_profiler(TRUE);
		
		$this->load->view('admin/header');
		$this->load->view('admin/blog/index');
		$this->load->view('admin/footer');
	}
	
	public function all() {

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
		
		$query = $this->db->query('SELECT * FROM blog a
									WHERE id = (
										SELECT max(ID) FROM blog b WHERE a.revision = b.revision AND site = "'.$site.'"
									)
									AND site = "'.$site.'"
									ORDER BY id DESC');
		$result = $query->result_array();
		$data['blogs'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/blog/all', $data);
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
		$this->load->model('admin_blog_model');

		$data['blog'] = $this->input->post();

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('slug', 'Url', 'required');
		$this->form_validation->set_rules('blog_date', 'Blog date', 'required');
				
		if ($this->form_validation->run() === FALSE)
		{
			
			// get this site products
			$query = $this->db->query('SELECT ps.sku, pt.title
										FROM products_sites as ps
										LEFT JOIN products as p ON ps.sku = p.sku
										LEFT JOIN products_text as pt ON ps.sku = pt.sku
										WHERE ps.active = 1 AND p.active = 1 AND ps.site = "'.$site.'" ORDER BY ps.id DESC');
			$result = $query->result_array();
			$data['blog']['products'] = $result;

			// get related products for this blog
			$data['blog']['related_products'] = "";	
			
			$this->load->view('admin/header');
			$this->load->view('admin/blog/create', $data);
			$this->load->view('admin/footer');

		}
		else
		{
			$image = $this->uploadImage();
			if(!empty($image['file_name'])) {
				$data['blog']['image'] = $image['file_name'];
			}
			
			$this->admin_blog_model->set_blog($data);
			$this->session->set_flashdata('msg', 'Success: New blog created');
			redirect('/admin/blogs/all');
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
		$this->load->model('admin_blog_model');

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('slug', 'Url', 'required');
		$this->form_validation->set_rules('blog_date', 'Blog date', 'required');
		
		$data['blog'] = $this->input->post();
		
		if ($this->form_validation->run() === FALSE)
		{
			if(empty($data['blog'])) {
				
				// get current data 
				$query = $this->db->query('SELECT * FROM blog WHERE id = '.(int)$id.' AND site = "'.$site.'" ORDER BY id DESC');
				$result = $query->result_array();
				$data['blog'] = $result[0];
				
				// get revisions
				$query = $this->db->query('SELECT * FROM blog WHERE id != '.(int)$id.' AND revision = '.(int)$data['blog']['revision'].' AND site = "'.$site.'" ORDER BY last_updated DESC');
				$result = $query->result_array();
				$data['blog']['revisions'] = $result;
			}
					
			$data['blog']['id'] = (!empty($data['blog']['id'])) ? $data['blog']['id'] : $id;
			
			// get this site products
			$query = $this->db->query('SELECT ps.sku, pt.title
										FROM products_sites as ps
										LEFT JOIN products as p ON ps.sku = p.sku
										LEFT JOIN products_text as pt ON ps.sku = pt.sku
										WHERE ps.active = 1 AND p.active = 1 AND ps.site = "'.$site.'" ORDER BY ps.id DESC');
			$result = $query->result_array();
			$data['blog']['products'] = $result;

			// get related products for this blog
			$query = $this->db->query('SELECT sku FROM blog_related_products WHERE blog_id = '.$data['blog']['id'].' AND site = "'.$site.'"');
			$result = $query->result_array();
			$data['blog']['related_products'] = $result;		

			$this->load->view('admin/header');
			$this->load->view('admin/blog/edit', $data);
			$this->load->view('admin/footer');

		}
		else
		{
			$image = $this->uploadImage();
			if(!empty($image['file_name'])) {
				$data['blog']['image'] = $image['file_name'];
			}
		
			$this->admin_blog_model->update_blog($data);
			$this->session->set_flashdata('msg', 'Success: Blog has been updated');
			redirect('/admin/blogs/all');
		}
	}
	
   public function uploadImage() { 
   
 	  $site = $this->config->item('template'); // config
	  $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session  
   
	  $this->load->helper(array('form', 'url')); 
   
      $config['upload_path']   = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$site.'/blogs/'; 
      $config['allowed_types'] = 'gif|jpg|jpeg|png'; 
      // $config['max_size']      = 1024;
      $this->load->library('upload', $config);
	  
      if ( ! $this->upload->do_upload('image')) {
		 // we could log this instead
         print_r(array('error' => $this->upload->display_errors()));
      }else { 

        $uploadedImage = $this->upload->data();
        $this->resizeImage($uploadedImage['file_name']);
		
		return $uploadedImage;
      } 
   }

   public function resizeImage($filename)
   {
	   
	  $site = $this->config->item('template'); // config
	  $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
	  $this->load->helper(array('form', 'url')); 
	   
      $source_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/'.$site.'/blogs/' . $filename;
      $target_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/'.$site.'/blogs/thumbs/';
      $config_manip = array(
          'image_library' => 'gd2',
          'source_image' => $source_path,
          'new_image' => $target_path,
          'maintain_ratio' => TRUE,
          'create_thumb' => TRUE,
          'thumb_marker' => '',
          'width' => 150, 
          'height' => 150
      );

      $this->load->library('image_lib', $config_manip);
      if (!$this->image_lib->resize()) {
		  // we could log this instead
          die($this->image_lib->display_errors());
      }

      $this->image_lib->clear();
   }
}
