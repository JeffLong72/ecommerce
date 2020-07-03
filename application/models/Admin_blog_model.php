<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_blog_model extends CI_Model {

	public function set_blog($data = "")
	{
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session		
		
		$this->load->helper('url');
		
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		
		$date = explode("/", $data['blog']['blog_date']);
		$blog_date = $date[2]."/".$date[1]."/".$date[0]." 00:00:00";
		
		$insert = array(
						'site' => $site,
						'title' => $this->input->post('title'),
						'slug' => $slug,
						'blog_date' => $blog_date,
						'text' => $this->input->post('text'),
						'image' => $data['blog']['image'],
						'image_alt' => $this->input->post('image_alt'),
						'image_title' => $this->input->post('image_title'),							
						'meta_title' => $this->input->post('meta_title'),
						'meta_description' => $this->input->post('meta_description'),
						'redirect' => $this->input->post('redirect'),
						'canonical' => $this->input->post('canonical'),							
						'active' => $this->input->post('status')						
					);

		$this->db->insert('blog', $insert);
		$insert_id = $this->db->insert_id();
		
        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_blog_model', 'set_blog', 'insert',  print_r($insert, TRUE));
		
		// add revision
		$update = array(
						'revision' => $insert_id
					);
		$this->db->where('id', $insert_id);
		$this->db->update('blog', $update);
		
        // log admin action
        $this->global_model->admin_action('Admin_blog_model', 'set_blog', 'update',  print_r($update, TRUE));
		
		$related_products = $this->input->post('selected_products');
		if(!empty($related_products)) {
			// $this->db->delete('blog_related_products', array('blog_id' => $insert_id));
			foreach($related_products as $related_product) {
				$insert = array( 
								'site' => $site,
								'blog_id' => $insert_id,
								'sku' => $related_product
							);
				$this->db->insert('blog_related_products', $insert);

                $this->global_model->admin_action('Admin_blog_model', 'set_blog', 'insert',  print_r($insert, TRUE));
			}
		}		

		return "success";
	}
	
	public function update_blog($data = "")
	{
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		$this->load->helper('url');
		
		$slug = url_title($data['blog']['slug'], 'dash', TRUE);
		
		$date = explode("/", $data['blog']['blog_date']);
		$blog_date = $date[2]."/".$date[1]."/".$date[0]." 00:00:00";
		
		$image = (!empty($data['blog']['image'])) ? $data['blog']['image'] : $this->input->post('original_image');
		$insert = array( 
						'revision' => $this->input->post('revision'),
						'site' => $site,
						'title' => $this->input->post('title'),
						'slug' => $slug,
						'blog_date' => $blog_date,
						'last_updated' => date("Y-m-d H:i:s"),
						'text' => $this->input->post('text'),
						'image' => $image,
						'image_alt' => $this->input->post('image_alt'),
						'image_title' => $this->input->post('image_title'),						
						'meta_title' => $this->input->post('meta_title'),
						'meta_description' => $this->input->post('meta_description'),
						'redirect' => $this->input->post('redirect'),
						'canonical' => $this->input->post('canonical'),							
						'active' => $this->input->post('status')						
					);
					
		$this->db->insert('blog', $insert);	
		$insert_id = $this->db->insert_id();

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_blog_model', 'update_blog', 'insert',  print_r($insert, TRUE));
					
		$related_products = $this->input->post('selected_products');
		if(!empty($related_products)) {
			$this->db->delete('blog_related_products', array('blog_id' => $this->input->post('id')));
			foreach($related_products as $related_product) {
				$insert = array( 
								'site' => $site,
								'blog_id' => $insert_id,
								'sku' => $related_product
							);
				$this->db->insert('blog_related_products', $insert);

                // log admin action
                $this->global_model->admin_action('Admin_blog_model', 'update_blog', 'insert',  print_r($insert, TRUE));
			}
		}

		return "success";
	}
}
