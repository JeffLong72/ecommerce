<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_vouchers extends CI_Controller {

    public function index()
    {
        echo "nothing here yet";
    }

    public function all($keyword = "-", $dir = "asc", $id = 0)
    {
        // $this->output->enable_profiler(TRUE);

        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
	
        // default variables
        $limit = 20;
        $per_page = 20;
        $dir = ($dir != "asc") ? "desc" : "asc";
        $keyword = (preg_match('/^[A-Za-z0-9_]+$/',$keyword)) ? $this->uri->segment(4) : ""; // get keyword from url
        $keyword = (!empty($_POST['keyword'])) ? $_POST['keyword'] : $keyword; // get keyword from post
        $data['keyword'] =	$keyword; // set keyword for view page
        $keyword = $this->db->escape_like_str($keyword);
        $keyword_inc = ($keyword != "") ? " AND ( vo.voucher_code LIKE '%".$keyword."%') " : "";

        // get results count
        $query = $this->db->query('SELECT id
									FROM vouchers AS vo
									WHERE  vo.status = 0
									AND vo.site = "'.$site.'"
									AND status = 0
									'.$keyword_inc);
        $result = $query->result_array();
        $data['count'] = $result;

        // get this site vouchers
        // add limit so we can use pagination
        $query = $this->db->query('SELECT *
									FROM vouchers AS vo
									WHERE vo.id IS NOT NULL
									AND vo.site = "'.$site.'"
									AND status = 0									
									'.$keyword_inc.'
									ORDER BY vo.id DESC');
        $result = $query->result_array();
        $data['vouchers'] = $result;

        // pagination
        $this->load->library('pagination');
        $keyword = (!empty($keyword)) ? $keyword : "-";
        $config['base_url'] = base_url().'admin/vouchers/all/'.$keyword.'/'.$dir.'/';
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
        $this->load->view('admin/vouchers/all', $data);
        $this->load->view('admin/footer');

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

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('admin_vouchers_model');

        $this->form_validation->set_rules('active', 'Active', 'trim');

        if ( empty( $_POST ))
        {
            // this is only called if theres no post data ( edit only )
            if(empty($data['vouchers'])) {
                // get vouchers data
                $query = $this->db->query('SELECT * 
                                        FROM vouchers 
                                        WHERE id = '.(int)$id);
                $result = $query->result_array();
                $data['vouchers'] = $result[0];
            }

            // get vouchers options
            $query = $this->db->query('SELECT * 
                                        FROM vouchers 
                                        WHERE id = '.(int)$id);
            $result = $query->result_array();
            $data['vouchers'] = $result;

            // load template
            $this->load->view('admin/header');
            $this->load->view('admin/vouchers/edit', $data);
            $this->load->view('admin/footer');
            return;
        }
        else
        {
            $this->admin_vouchers_model->update_voucher();
            $this->session->set_flashdata('msg', 'Success: voucher has been updated');
            redirect('/admin/vouchers/all');
        }
    }


    public function create()
    {
        // enable profiler
        //$this->output->enable_profiler(TRUE);

        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');	
		
		// load helper
        $this->load->model('admin_vouchers_model');

		// if we have post values
		$create_voucher_form = $this->input->post('create_voucher_submit');
		
		if( isset( $create_voucher_form ) ) {

			// form validation rules
			$this->form_validation->set_rules('voucher_code', 'Voucher Code', 'trim|required|alpha_numeric');			
			$this->form_validation->set_rules('voucher_money_off', 'Voucher Money Off', 'trim|decimal');			
			$this->form_validation->set_rules('voucher_percent_off', 'Voucher Percent Off', 'trim|numeric');
			$this->form_validation->set_rules('voucher_refund_total', 'Voucher Refund Total', 'trim|decimal');
			$this->form_validation->set_rules('voucher_max_uses', 'Voucher Max Uses', 'trim|numeric');

			if ($this->form_validation->run() === FALSE)
			{	
				// highlite errors
				$this->form_validation->set_error_delimiters('<p style="color:red;">', '</p>');
			}
			else 
			{
				// alls good, add voucher to vouchers table
				$voucher_details = $this->input->post();
				$result = $this->admin_vouchers_model->save_new_voucher( $site, $voucher_details );	

				$this->session->set_flashdata('msg', 'Success: Voucher has been created');
				redirect('/admin/vouchers/all');				
				
			}
		}	

		// get brands
		$data['brands'] = $this->admin_vouchers_model->get_product_brands( $site );	

		// get category names
		$data['category_names'] = $this->admin_vouchers_model->get_category_names( $site );	
		
		// get product skus
		$data['product_skus'] = $this->admin_vouchers_model->get_product_skus( $site );	
		
        // load template
        $this->load->view('admin/header');
        $this->load->view('admin/vouchers/create',$data);
        $this->load->view('admin/footer');

    }
}