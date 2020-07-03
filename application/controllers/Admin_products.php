<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_products extends CI_Controller {

	public function index()
	{
		// $this->output->enable_profiler(TRUE);
		
		$this->load->view('admin/header');
		$this->load->view('admin/products/index');
		$this->load->view('admin/footer');
	}
	
	public function all($keyword = "-", $order = "title", $dir = "asc", $id = 0)
	{
		// $this->output->enable_profiler(TRUE);
		
		// check admin is logged in
		$this->load->model('admin_global');
		$this->admin_global->check_status();		
	
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		$query = $this->db->query('SELECT domain 
									FROM cms_sites 
									WHERE template = "'.$site.'"
									AND active = 1');
		$result = $query->result_array();
		$data['domain'] = (!empty($result[0]['domain'])) ? $result[0]['domain'] : "";

		// default variables
		$limit = 20;
		$per_page = 20;
		$dir = ($dir != "asc") ? "desc" : "asc";
		$order = $this->db->escape_like_str($order);
		$keyword = (preg_match('/^[A-Za-z0-9_]+$/',$keyword)) ? $this->uri->segment(4) : ""; // get keyword from url
		$keyword = (!empty($_POST['keyword'])) ? $_POST['keyword'] : $keyword; // get keyword from post	
		$data['keyword'] =	$keyword; // set keyword for view page	
		// $keyword = $this->db->escape_like_str($keyword);
		$keyword_inc = ($keyword != "-") ? " AND ( pt.sku LIKE '%".$keyword."%' OR pt.title LIKE '%".$keyword."%' ) " : "";		
		
		// get results count
		$query = $this->db->query('SELECT id
									FROM products_text AS pt 
									WHERE pt.site = "'.$site.'"
									AND pt.active = 1
									'.$keyword_inc);
		$result = $query->result_array();		
		$data['count'] = $result;	

		// get this site products
		// add limit so we can use pagination
		$query = $this->db->query('SELECT sku 
									FROM products_text AS pt
									WHERE pt.site = "'.$site.'" 
									AND pt.active = 1
									'.$keyword_inc.'
									ORDER BY pt.'.$order.' '.$dir.'
									LIMIT '.(int)$id.','.(int)$limit);
		$result = $query->result_array();
		
		$str = "";
		foreach($result as $r){
			$str.= '"'.addslashes($r['sku']).'",' ;
		}
		$str = rtrim($str,", ");
		$str = (!empty($str))? $str : '""';

		// get data from tables based on the get site products query above
		// lets assume we have too much data to simply run a basic sql query to get all products 
		// (eg. 8000 products) and split the results into bite size chunks using the query above with
		// a limit and return the results from the database using where product sku "in" (fastest solution)
		$query = $this->db->query('SELECT 
									p.id, p.sku, p.slug, p.brand, p.color, p.size, p.dimensions, p.net_weight, p.prod_weight, 
									p.stock, p.stock_on_hold, p.stock_minimum, p.stock_in_stock, p.featured, p.active, 
									pc.cost, pc.special_offer_cost, pc.special_offer_expires, pc.vat_rate,
									pt.title, pt.`text`, pt.meta_title, pt.meta_description, pt.dispatch_time
									FROM products AS p 
									LEFT JOIN products_text AS pt ON p.sku = pt.sku AND pt.site IN ("'.$site.'")
									LEFT JOIN products_sites AS ps ON p.sku = ps.sku AND ps.site IN ("'.$site.'")
									LEFT JOIN products_cost AS pc ON ps.sku = pc.sku AND pc.site IN ("'.$site.'")
									WHERE p.`status` = 0
									AND ps.sku IN ('.$str.')
									ORDER BY pt.'.$order.' '.$dir);
		$result = $query->result_array();
		$data['products'] = $result;
		
		// pagination
		$this->load->library('pagination');
		$keyword = (!empty($keyword)) ? $keyword : "-";
		$config['base_url'] = base_url().'admin/products/all/'.$keyword.'/'.$order.'/'.$dir.'/';
		$config['total_rows'] = (!empty($data['count'])) ? count($data['count']) : 0;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 7;
		$config['num_links'] = 2;
		$config['page_query_string'] = FALSE;
		$config['cur_tag_open'] = '&nbsp;<a class="current">';
		$config['cur_tag_close'] = '</a>';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();				
		
		$this->load->view('admin/header');
		$this->load->view('admin/products/all', $data);
		$this->load->view('admin/footer');		
	}
	
	public function create()
	{
	
		// $this->output->enable_profiler(TRUE);

		// check admin is logged in
		$this->load->model('admin_global');
		$this->admin_global->check_status();				
	
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// get product attributes from folder config/attributes.php
		$data['product_attributes'] = $this->config->item('product_attributes'); 		
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin_products_model');

		$data['product'] = $this->input->post();

		$this->form_validation->set_rules('sku', 'Sku', 'required');
		$this->form_validation->set_rules('title', 'Name', 'required');
		$this->form_validation->set_rules('slug', 'Url', 'required');
		$this->form_validation->set_rules('cost', 'Cost', 'required');
				
		if ($this->form_validation->run() === FALSE)
		{
			// get delivery options
			$query = $this->db->query('select * from delivery_rates order by id asc');
			$result = $query->result_array();
			$data['product']['delivery_options'] = $result;
				
			// get vat rates
			$query = $this->db->query('select * from vat_rates order by id asc');
			$result = $query->result_array();
			$data['product']['vat_rates'] = $result;
			
			// get products category menu
			$data['product']['category_menu'] = $this->admin_products_model->product_categories();
			
			// get this site products
			$query = $this->db->query('SELECT sku, title
										FROM products_text
										WHERE site = "'.$site.'" 
										ORDER BY title ASC');			
			$result = $query->result_array();
			$data['product']['products'] = $result;

			// get related products for this product
			$data['product']['related_products'] = "";
			
			$this->load->view('admin/header');
			$this->load->view('admin/products/create', $data);
			$this->load->view('admin/footer');

		}
		else
		{
			$image = $this->uploadImage();
			if(!empty($image['file_name'])) {
				$data['product']['image'] = $image['file_name'];
			}
			
			$this->admin_products_model->set_product($data);
			$this->session->set_flashdata('msg', 'Success: New blog created');
			redirect('/admin/products/all');
		}
	}
	
	public function edit($id="")
	{
	
		// $this->output->enable_profiler(TRUE);
		
		// check admin is logged in
		$this->load->model('admin_global');
		$this->admin_global->check_status();				
		
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		$id = str_replace(".html", "", $id);
		
		// get product attributes from folder config/attributes.php
		$data['product_attributes'] = $this->config->item('product_attributes'); 

		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin_products_model');

		$this->form_validation->set_rules('sku', 'Sku', 'required');
		$this->form_validation->set_rules('title', 'Name', 'required');
		$this->form_validation->set_rules('slug', 'Url', 'required');
		$this->form_validation->set_rules('cost', 'Cost', 'required');
		
		$data['product'] = $this->input->post();

		if ($this->form_validation->run() === FALSE)
		{
			if(empty($data['product'])) {
				// get product data
				$query = $this->db->query('select 
											p.id, p.sku, p.slug, p.brand, p.color, p.size, p.dimensions, p.net_weight, p.prod_weight, p.stock, p.stock_on_hold, 
											p.stock_minimum, p.stock_in_stock, p.featured, p.active, p.product_attribute, p.product_attribute_option, p.restrict_direct_access,
											pc.base_cost, pc.cost, pc.special_offer_cost, pc.special_offer_expires, pc.vat_rate,
											pt.title, pt.short_description, pt.`text`, pt.meta_title, pt.meta_description, pt.dispatch_time, pt.canonical
											from products as p 
											left join products_text as pt on p.sku = pt.sku and pt.site IN ("'.$site.'")
											left join products_sites as ps on p.sku = ps.sku and ps.site IN ("'.$site.'")
											left join products_cost as pc on ps.sku = pc.sku and pc.site IN ("'.$site.'")
											where ps.site IN ("'.$site.'")
											and p.`status` = 0
											and p.id = '.(int)$id);
				$result = $query->result_array();
				$data['product'] = $result[0];
			}
				
			// get delivery options
			$query = $this->db->query('select * from delivery_rates order by id asc');
			$result = $query->result_array();
			$data['product']['delivery_options'] = $result;

			$product_sku = (!empty($data['product']['original_sku'])) ? $data['product']['original_sku'] : $data['product']['sku'];
			$query = $this->db->query('select * from products_delivery where sku = "'.$product_sku.'"');
			$result = $query->result_array();
			$data['product']['delivery_selected'] = $result;
				
			// get vat rates
			$query = $this->db->query('select * from vat_rates order by id asc');
			$result = $query->result_array();
			$data['product']['vat_rates'] = $result;
			
			// get products category menu
			$data['product']['category_menu'] = $this->admin_products_model->product_categories($product_sku);
			
			// get products category menu
			$data['product']['images'] = $this->admin_products_model->product_images($product_sku);			
			
			// get this site products
			$query = $this->db->query('SELECT sku, title
										FROM products_text
										WHERE site = "'.$site.'" 
										ORDER BY title ASC');	
			$result = $query->result_array();
			$data['product']['products'] = ""; // $result;

			// get related products for this product
			$query = $this->db->query('SELECT * 
															FROM products_related 
															WHERE sku = "'.$product_sku.'" 
															AND site = "'.$site.'"
															AND active = 1
															AND status = 0');
			$result = $query->result_array();
			$data['product']['related_products'] = $result;
			
			// get product stock movement
			$data['product']['stock_movements'] = $this->admin_products_model->get_stock_movement ($product_sku);
			
			$this->load->view('admin/header');
			$this->load->view('admin/products/edit', $data);
			$this->load->view('admin/footer');

		}
		else
		{
			$image = $this->uploadImage();
			if(!empty($image['file_name'])) {
				$data['products']['image'] = $image['file_name'];
			}
			
			$this->admin_products_model->update_product($data);
			$this->session->set_flashdata('msg', 'Success: Product has been updated');
			redirect('/admin/products/edit/'.(int)$id);
		}
	}

    public function edit_image_details($id="")
    {

        // $this->output->enable_profiler(TRUE);

        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $id = str_replace(".html", "", $id);

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('admin_products_model');

        $this->form_validation->set_rules('alt', 'Alt', 'required');
        $this->form_validation->set_rules('title', 'Title', 'required');

        $data['image'] = $this->input->post();

        if ($this->form_validation->run() === FALSE)
        {

            // this is only called if theres no post data ( edit only )
            if(empty($data['image'])) {
                // get image data
                $query = $this->db->query('SELECT * 
                                        FROM products_images 
                                        WHERE id = '.(int)$id);
                $result = $query->result_array();
                $data['image'] = $result[0];
            }

            // get image options
            $query = $this->db->query('SELECT * 
                                        FROM products_images 
                                        WHERE id = '.(int)$id);
            $result = $query->result_array();
            $data['products_images'] = $result;

            $this->load->view('admin/header');
            $this->load->view('admin/products/edit_image_details', $data);
            $this->load->view('admin/footer');
            return;
        }
        else
        {
			// we need to return the user back to the product edit page
            $query = $this->db->query('SELECT t1.id
															FROM products as t1
															LEFT JOIN products_images as t2 ON t1.sku = t2.sku
															WHERE t2.id = "'.$id.'"');
            $result = $query->result_array();
 
            $this->admin_products_model->update_image_details($data);
            $this->session->set_flashdata('msg', 'Success: Image details has been updated <br /><br /><a style="color:#333;" href="'.base_url().'admin/products/edit/'. $result[0]['id'].'">Return to Edit Product page</a>');
            redirect('/admin/products/edit_image_details/'.(int)$id);
        }
    }
	
   public function uploadImage() {
	   
	  $site = $this->config->item('template'); // config
	  $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	   
   
	  $this->load->helper(array('form', 'url')); 
   
      $config['upload_path']   = './uploads/'.$site.'/products/'; 
      $config['allowed_types'] = 'gif|jpg|jpeg|png'; 
      $config['max_size']      = 1024;
      $this->load->library('upload', $config);

      if ( ! $this->upload->do_upload('image')) {
		 // we could log this instead
		 print_r("uploadImage function: <br />");
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

	  // fix for installing software in sub-folders
	  $_SERVER['DOCUMENT_ROOT'] = dirname(dirname(dirname(__FILE__)));
	  // end of fix

      $source_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/'.$site.'/products/' . $filename;
      $target_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/'.$site.'/products/thumbs/';
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
		 print_r("resizeImage function: <br />");
          print_r(__FILE__);


          die($this->image_lib->display_errors());
      }

      $this->image_lib->clear();
   }
}
