<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_users extends CI_Controller {

    public function index()
    {
        // $this->output->enable_profiler(TRUE);

        $this->load->view('admin/header');
        $this->load->view('admin/users/index');
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
        $keyword_inc = ($keyword != "-") ? " AND ( cu.first_name LIKE '%".$keyword."%') " : "";

        // get results count
        $query = $this->db->query('SELECT id
									FROM users AS cu 
									WHERE  cu.status = 0
									AND cu.site = "'.$site.'"
									'.$keyword_inc);
        $result = $query->result_array();
        $data['count'] = $result;

//        echo "<pre>";
//        print_r($data['count']);
//        echo "</pre>";

        // get this site users
        // add limit so we can use pagination
        $query = $this->db->query('SELECT *
									FROM users AS cu
									WHERE cu.id IS NOT NULL
									AND cu.site = "'.$site.'"
									'. $keyword_inc .'
									LIMIT '.(int)$id.','.(int)$limit);
        $result = $query->result_array();
        $data['users'] = $result;

        // pagination
        $this->load->library('pagination');
        $keyword = (!empty($keyword)) ? $keyword : "-";
        $config['base_url'] = base_url().'admin/users/all/'.$keyword.'/'.$dir.'/';
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
        $this->load->view('admin/users/all', $data);
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
        $this->load->model('user_model');

        $this->form_validation->set_rules('email', 'Email', 'required');

        $data['users'] = $this->input->post();

        if ($this->form_validation->run() === FALSE)
        {
            // this is only called if theres no post data ( edit only )
            if(empty($data['users'])) {
                // get users data
                $query = $this->db->query('SELECT * 
                                        FROM users 
                                        WHERE id = '.(int)$id);
                $result = $query->result_array();
                $data['users'] = $result[0];
            }

            // get users options
            $query = $this->db->query('SELECT * 
                                        FROM users 
                                        WHERE id = '.(int)$id);
            $result = $query->result_array();
            $data['users'] = $result;

            $this->load->view('admin/header');
            $this->load->view('admin/users/edit', $data);
            $this->load->view('admin/footer');
            return;
        }
        else
        {
            $this->user_model->update_customer_user($data);
            $this->session->set_flashdata('msg', 'Success: user has been updated');
            redirect('/admin/users/edit/'.(int)$id);
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
        $this->load->model('user_model');

        $data['users'] = $this->input->post();

        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Second Name', 'required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Password Confirm', 'trim|required|matches[password]');

        if ($this->form_validation->run() === FALSE) {

            // load template
            $data = "";
            $this->load->view('admin/header');
            $this->load->view('admin/users/create', $data);
            $this->load->view('admin/footer');
        }
        else
        {
            $this->admin_customer_user_model->set_customer_user($data);
            $this->session->set_flashdata('msg', 'Success: New User created');
            redirect('/admin/users/all');
        }
    }
}