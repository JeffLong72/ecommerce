<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_reports extends CI_Controller {

    public function index()
    {
        echo "nothing here yet";
    }

    public function orders()
    {
        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        // enable profiler
        //$this->output->enable_profiler(TRUE);

        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // load template
        $data = "";
        $this->load->view('admin/header');
        $this->load->view('admin/reports/orders',$data);
        $this->load->view('admin/footer');

    }

    public function vouchers()
    {
        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        // enable profiler
        //$this->output->enable_profiler(TRUE);

        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // load template
        $data = "";
        $this->load->view('admin/header');
        $this->load->view('admin/reports/vouchers',$data);
        $this->load->view('admin/footer');

    }

    public function best_sellers()
    {
        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        // enable profiler
        //$this->output->enable_profiler(TRUE);

        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // load template
        $data = "";
        $this->load->view('admin/header');
        $this->load->view('admin/reports/best_sellers',$data);
        $this->load->view('admin/footer');

    }

    public function most_viewed()
    {
        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        // enable profiler
        //$this->output->enable_profiler(TRUE);

        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // load template
        $data = "";
        $this->load->view('admin/header');
        $this->load->view('admin/reports/most_viewed',$data);
        $this->load->view('admin/footer');

    }

    public function keywords($keyword = "-", $dir = "asc", $start_date="", $end_date="", $id = 0)
    {
        // $this->output->enable_profiler(TRUE);

        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $query = $this->db->query('SELECT domain 
									FROM cms_sites 
									WHERE template = "' . $site . '"
									AND active = 1');
        $result = $query->result_array();
        $data['domain'] = (!empty($result[0]['domain'])) ? $result[0]['domain'] : "";

        // default variables
        $limit = 20;
        $per_page = 20;
        $dir = ($dir != "asc") ? "desc" : "asc";
        $keyword = (preg_match('/^[A-Za-z0-9_]+$/', $keyword)) ? $this->uri->segment(4) : ""; // get keyword from url
        $keyword = (!empty($_POST['keyword'])) ? $_POST['keyword'] : $keyword; // get keyword from post
        $data['keyword'] = $keyword; // set keyword for view page
        $keyword = $this->db->escape_like_str($keyword);
        $keyword_inc = ($keyword != "-") ? " AND ( lst.keyword LIKE '%" . $keyword . "%') " : "";

        $search_date_start = $this->input->post('txtdate1'); // 19/09/2018
        if ( !empty($search_date_start) ) {
            $s = explode("/", $search_date_start);
            $search_date_start = $s[2] . "-" . $s[1] . "-" . $s[0] . " 00:00:00"; //  "2018-09-19 00:00:00"
			$start_date = $s[0] . "-" . $s[1] . "-" . $s[2] ; 
        }
		
		if(!empty($start_date) && $start_date != "-") {
            $s = explode("-", $start_date);
            $search_date_start = $s[2] . "-" . $s[1] . "-" . $s[0] . " 00:00:00"; //  "2018-09-19 00:00:00"
			$start_date = $s[0] . "-" . $s[1] . "-" . $s[2] ; 			
		}

        $search_date_end = $this->input->post('txtdate2'); // 25/09/2018
        if ( !empty($search_date_end) ) {
            $s2 = explode("/", $search_date_end);
            $search_date_end = $s2[2] . "-" . $s2[1] . "-" . $s2[0] . " 23:59:59"; //  "2018-09-19 23:59:59"
			$end_date = $s2[0] . "-" . $s2[1] . "-" . $s2[2] ; 
        }
		
		if(!empty($end_date) && $end_date != "-") {
            $s2 = explode("-", $end_date);
            $search_date_end = $s2[2] . "-" . $s2[1] . "-" . $s2[0] . " 23:59:59"; //  "2018-09-19 23:59:59"
			$end_date = $s2[0] . "-" . $s2[1] . "-" . $s2[2] ; 	
		}

        $search_dates_inc = ( ! empty($s) && ! empty($s2) ) ? " AND (lst.date_added BETWEEN \"$search_date_start\" AND \"$search_date_end\") " : "";
		

        // get results count
        $query = $this->db->query('SELECT id
									FROM log_search_terms AS lst
									WHERE lst.site = "'.$site.'" 
									AND lst.active = 1
									'.$search_dates_inc.'
									'.$keyword_inc);
        $result = $query->result_array();
        $data['count'] = $result;

        // get this site search terms
        // add limit so we can use pagination
        $query = $this->db->query('SELECT *
									FROM log_search_terms AS lst 
									WHERE lst.id IS NOT NULL
									AND lst.active = 1
									AND  lst.site = "'.$site.'"
									'.$search_dates_inc.'
									'. $keyword_inc .' 
									ORDER BY lst.id DESC
									LIMIT '.(int)$id.','.(int)$limit);
        $result = $query->result_array();
        $data['keywords'] = $result;

        // pagination
        $this->load->library('pagination');
        $keyword = (!empty($keyword)) ? $keyword : "-";
        $start_date_url = (!empty($start_date)) ? str_replace("/", "-", $start_date) : "-";
        $end_date_url = (!empty($end_date)) ? str_replace("/", "-", $end_date) : "-";		
        $config['base_url'] = base_url().'admin/reports/keywords/'.$keyword.'/'.$dir.'/'.$start_date_url.'/'.$end_date_url.'/';
        $config['total_rows'] = (!empty($data['count'])) ? count($data['count']) : 0;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 8;
        $config['num_links'] = 2;
        $config['page_query_string'] = FALSE;
        $config['cur_tag_open'] = '&nbsp;<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
		
        $data['start_date_value'] = ( $start_date != "-" ) ? str_replace("-", "/", $start_date) : "";
        $data['end_date_value'] =  ( $end_date != "-" ) ? str_replace("-", "/", $end_date) : "";	
        
        $this->load->view('admin/header');
        $this->load->view('admin/reports/keywords', $data);
        $this->load->view('admin/footer');

    }

    public function admin_updates()
    {
        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        // enable profiler
        //$this->output->enable_profiler(TRUE);

        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // load template
        $data = "";
        $this->load->view('admin/header');
        $this->load->view('admin/reports/admin_updates',$data);
        $this->load->view('admin/footer');

    }
}