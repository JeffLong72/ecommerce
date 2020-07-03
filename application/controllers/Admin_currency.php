<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_currency extends CI_Controller {

    public function index()
    {
        // $this->output->enable_profiler(TRUE);

        $this->load->view('admin/header');
        $this->load->view('admin/currency/index');
        $this->load->view('admin/footer');
    }

    public function all($keyword = "-", $dir = "asc", $id = 0)
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
        $keyword = (preg_match('/^[A-Za-z0-9_]+$/',$keyword)) ? $this->uri->segment(4) : ""; // get keyword from url
        $keyword = (!empty($_POST['keyword'])) ? $_POST['keyword'] : $keyword; // get keyword from post
        $data['keyword'] =	$keyword; // set keyword for view page
        $keyword = $this->db->escape_like_str($keyword);
        $keyword_inc = ($keyword != "-") ? " AND ( cc.currency_text LIKE '%".$keyword."%') " : "";

        // get results count
        $query = $this->db->query('SELECT id
									FROM currency_converter AS cc 
									WHERE cc.id = "'.(int)$id.'"
									AND site = "'.$site.'" 
									AND cc.status = 0
									'.$keyword_inc);
        $result = $query->result_array();
        $data['count'] = $result;

        // get this site currency
        // add limit so we can use pagination
        $query = $this->db->query('SELECT *
									FROM currency_converter AS cc
									WHERE cc.id IS NOT NULL
									AND site = "'.$site.'"
									AND cc.status = 0
									'. $keyword_inc .'
									LIMIT '.(int)$id.','.(int)$limit);
        $result = $query->result_array();
        $data['currency'] = $result;

        // pagination
        $this->load->library('pagination');
        $keyword = (!empty($keyword)) ? $keyword : "-";
        $config['base_url'] = base_url().'admin/currency/all/'.$keyword.'/'.$dir.'/';
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
        $this->load->view('admin/currency/all', $data);
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
        $this->load->model('admin_currency_model');

        $this->form_validation->set_rules('currency_text', 'Currency Code', 'required');
        $this->form_validation->set_rules('currency_html', 'Currency Symbol', 'required');
        $this->form_validation->set_rules('currency_rate', 'Currency Rate', 'required');

        $data['currency'] = $this->input->post();

        if ($this->form_validation->run() === FALSE)
        {
            // this is only called if theres no post data ( edit only )
            if(empty($data['currency'])) {
                // get currency data
                $query = $this->db->query('SELECT * 
                                        FROM currency_converter 
                                        WHERE id = '.(int)$id);
                $result = $query->result_array();
                $data['currency'] = $result[0];
            }

            // get currency options
            $query = $this->db->query('SELECT * 
                                        FROM currency_converter 
                                        WHERE id = '.(int)$id);
            $result = $query->result_array();
            $data['currency_converter'] = $result;

            $this->load->view('admin/header');
            $this->load->view('admin/currency/edit', $data);
            $this->load->view('admin/footer');
            return;
        }
        else
        {
            $this->admin_currency_model->update_currency($data);
            $this->session->set_flashdata('msg', 'Success: currency has been updated');
            redirect('/admin/currency/edit/'.(int)$id);
        }
    }

    public function create()
    {
        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        // enable profiler
        // $this->output->enable_profiler(TRUE);

        // get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('admin_currency_model');

        $data['currency'] = $this->input->post();

        $this->form_validation->set_rules('currency_text', 'Currency Code', 'required');
        $this->form_validation->set_rules('currency_html', 'Currency Symbol', 'required');
        $this->form_validation->set_rules('currency_rate', 'Currency Rate', 'required');

        if ($this->form_validation->run() === FALSE) {

            // load template
            $data = "";
            $this->load->view('admin/header');
            $this->load->view('admin/currency/create', $data);
            $this->load->view('admin/footer');
        }
        else
        {
            $this->admin_currency_model->set_currency($data);
            $this->session->set_flashdata('msg', 'Success: New Currency Option created');
            redirect('/admin/currency/all');
        }
    }
}