<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_sales extends CI_Controller {
	
	public function index()
	{
		// nothing here
	}

	public function all($keyword = "-", $start_date = "-", $end_date = "-", $payment_method = "-", $order_status = "-", $order = "id", $dir = "desc", $id = 0)
    {
        // enable profiler
        // $this->output->enable_profiler(TRUE);

		// check admin is logged in
		$this->load->model('admin_global');
		$this->admin_global->check_status();		

		// load models
		$this->load->model('admin_sales_model');					

        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

		// default variables
		$limit = 20;
		$per_page = 20;
		$dir = ($dir != "asc") ? "desc" : "asc";
		$order = $this->db->escape_like_str($order);
		$keyword = (preg_match('/^[A-Za-z0-9_]+$/',$keyword)) ? $this->uri->segment(4) : ""; // get keyword from url
		$keyword = (!empty($_POST['keyword'])) ? $_POST['keyword'] : $keyword; // get keyword from post	
		$data['keyword'] =	$keyword; // set keyword for view page	
		$keyword_inc = ($keyword != "-") ? " AND ( order_id LIKE '%".$keyword."%' ) " : "";
		
		// get post variables
		$post = $this->input->post();
		
		// start datews
		$start_date = ( ! empty ( $post['start_date'] ) ) ? $post['start_date'] : $this->uri->segment(5);
		if( ! empty( $start_date ) && $start_date != "-" )
		{
			$start_date_array = explode ( "-", $start_date);
			$start_date_sql = $start_date_array[2]."-".$start_date_array[1]."-".$start_date_array[0]." 00:00:00";
		}
		else 
		{
			$start_date = "-";
		}
		
		// end date
		$end_date = ( ! empty ( $post['end_date'] ) ) ? $post['end_date'] : $this->uri->segment(6);
		if( ! empty( $end_date ) && $end_date != "-" ) 
		{
			$end_date_array = explode ( "-", $end_date);
			$end_date_sql = $end_date_array[2]."-".$end_date_array[1]."-".$end_date_array[0]." 23:59:59";
		}
		else 
		{
			$end_date = "-";
		}
		
		// get results count
		$query = $this->db->query('SELECT id
									FROM orders  
									WHERE site = "'.$site.'"
									AND active = 1
									AND status = 0									
									'.$keyword_inc);
		$result = $query->result_array();		
		$data['count'] = $result;	

		// get this site products
		// add limit so we can use pagination
		$query = $this->db->query('SELECT * 
									FROM orders
									WHERE site = "'.$site.'" 
									AND active = 1
									AND status = 0
									'.$keyword_inc.'
									ORDER BY '.$order.' '.$dir.'
									LIMIT '.(int)$id.','.(int)$limit);
		$result = $query->result_array();
		$data['orders'] = $result;
		
		// pagination
		$this->load->library('pagination');
		$keyword = (!empty($keyword)) ? $keyword : "-";
		$config['base_url'] = base_url().'admin/sales/all/'.$keyword.'/'.$start_date.'/'.$end_date.'/'.$payment_method.'/'.$order_status.'/'.$order.'/'.$dir.'/';
		$config['total_rows'] = (!empty($data['count'])) ? count($data['count']) : 0;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 11;
		$config['num_links'] = 2;
		$config['page_query_string'] = FALSE;
		$config['cur_tag_open'] = '&nbsp;<a class="current">';
		$config['cur_tag_close'] = '</a>';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();				
		
		$this->load->view('admin/header');
		$this->load->view('admin/sales/all', $data);
		$this->load->view('admin/footer');				

    }
	
	public function details($id = "")
	{
        // enable profiler
        // $this->output->enable_profiler(TRUE);
		
		// check admin is logged in
		$this->load->model('admin_global');
		$this->admin_global->check_status();		

		// load models
		$this->load->model('admin_sales_model');			
		$this->load->model('products_model');
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// get order edit id
		$id = (!empty($id)) ? str_replace(".html", "", $id) : 0;
		
		// get order details
		$data['order_details'] = $this->admin_sales_model->get_order_details($id);

		//get items purchased
		$data['order_details_items'] = $this->admin_sales_model->get_order_details_items($data['order_details']['order_id']);	

		// get order comments
		$data['order_comments'] = $this->admin_sales_model->get_order_comments($data['order_details']['order_id']);		

		// add order comment
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// get comments
		$comment_order_id = $this->input->post('add_comment_order_id');
		if( ! empty ( $comment_order_id ) ) {
			
			$this->form_validation->set_rules('add_order_comment', 'Add New Comment', 'trim|required');
			
			if ($this->form_validation->run() === FALSE)
			{
					// highlite errors
					$this->form_validation->set_error_delimiters('<p style="color:red;">', '</p>');			
			}
			else 
			{	
				$this->admin_sales_model->add_order_comment();
				$this->session->set_flashdata('msg', 'Success: New comment has been added.');
				redirect('/admin/sales/details/'.(int)$id);			
			}
		}
		
		// tracking codes
		$update_tracking_codes = $this->input->post('update_tracking_codes');
		if( ! empty ( $update_tracking_codes ) ) {
			
				$this->admin_sales_model->update_tracking_codes();
				$this->session->set_flashdata('msg', 'Success: Tracking codes have been updated.');
				redirect('/admin/sales/details/'.(int)$id);	
				
		}
		
		// update order details
		$order_details = $this->input->post('order_status');
		if( ! empty ( $order_details ) ) {
			
				$this->admin_sales_model->update_order();
				$this->session->set_flashdata('msg', 'Success: Order details have been updated.');
				redirect('/admin/sales/details/'.(int)$id);	
				
		}		
		
		// echo $this->global_model->debug( $_POST );
		
		$this->load->view('admin/header');
		$this->load->view('admin/sales/details', $data);
		$this->load->view('admin/footer');				
	}	
	
	function print_invoice_pdf( $order_id ="" ) {
		
		// load models
		$this->load->model('global_model');					
		
		// print invoice PDF
		$this->global_model->create_invoice_pdf( $order_id );		
		
	}
	
	function royal_mail ( $id = "" ) {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

		// load models
		$this->load->model('admin_sales_model');						
		
		// the data we want to pass to the model
		$data['order_id'] = $id;				
		$data['royal_mail'] = $this->admin_sales_model->royal_mail( $site, $id );	
		
		$this->load->view('admin/header');
		$this->load->view('admin/sales/royal_mail', $data);
		$this->load->view('admin/footer');		
	}
	
	function bpost ( $id = "" ) {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	

		// load models
		$this->load->model('admin_sales_model');						
		
		// the data we want to pass to the model
		$data['order_id'] = $id;				
		$data['bpost'] = $this->admin_sales_model->bpost( $site, $id  );	
		
		// load template
		$this->load->view('admin/header');
		$this->load->view('admin/sales/bpost', $data);
		$this->load->view('admin/footer');		
	}	
	
	function parcel_force ( $id = "" ) {
		
        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session	

		// load models
		$this->load->model('admin_sales_model');						
		
		// the data we want to pass to the model
		$data['order_id'] = $id;			
		$data['parcel_force'] = $this->admin_sales_model->parcel_force( $site, $id );	
		
		// load template		
		$this->load->view('admin/header');
		$this->load->view('admin/sales/parcel_force', $data);
		$this->load->view('admin/footer');		
	}		
	
}
