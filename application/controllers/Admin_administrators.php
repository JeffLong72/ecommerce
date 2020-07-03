<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_administrators extends CI_Controller {

    public function index()
    {
        // $this->output->enable_profiler(TRUE);

        $this->load->view('admin/header');
        $this->load->view('admin/administrators/index');
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
        $keyword_inc = ($keyword != "-") ? " AND ( au.username LIKE '%".$keyword."%') " : "";

        // get results count
        $query = $this->db->query('SELECT au.id
									FROM admin_users AS au 
									WHERE  au.status = 0
									AND au.site = "'.$site.'"
									'.$keyword_inc);
        $result = $query->result_array();
        $data['count'] = $result;

        // get this site administrators
        // add limit so we can use pagination
        $query = $this->db->query('SELECT *
									FROM admin_users AS au
									WHERE au.id IS NOT NULL
									AND au.status = 0
									AND au.site = "'.$site.'"
									'. $keyword_inc .'
									LIMIT '.(int)$id.','.(int)$limit);
        $result = $query->result_array();
        $data['administrators'] = $result;

        // pagination
        $this->load->library('pagination');
        $keyword = (!empty($keyword)) ? $keyword : "-";
        $config['base_url'] = base_url().'admin/administrators/all/'.$keyword.'/'.$dir.'/';
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
        $this->load->view('admin/administrators/all', $data);
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
        $this->load->model('admin_administrators_model');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Password Confirm', 'trim|required|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('security_pass', 'Security Pass', 'trim|required|min_length[7]');

        $data['administrators'] = $this->input->post();

        if ($this->form_validation->run() === FALSE)
        {
            // this is only called if theres no post data ( edit only )
            if(empty($data['administrators'])) {
                // get administrators data
                $query = $this->db->query('SELECT * 
                                        FROM admin_users 
                                        WHERE id = '.(int)$id);
                $result = $query->result_array();
                $data['administrators'] = $result[0];
            }

            // get administrators options
            $query = $this->db->query('SELECT * 
                                        FROM admin_users 
                                        WHERE id = '.(int)$id);
            $result = $query->result_array();
            $data['admin_users'] = $result;

            $this->load->view('admin/header');
            $this->load->view('admin/administrators/edit', $data);
            $this->load->view('admin/footer');
            return;
        }
        else
        {
            $this->admin_administrators_model->update_administrators($data);
            $this->session->set_flashdata('msg', 'Success: administrator has been updated');
            redirect('/admin/administrators/edit/'.(int)$id);
        }
    }

    public function admin_permissions($id="")
    {
        // check admin is logged in
        $this->load->model('admin_global');
        $this->admin_global->check_status();

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('admin_administrators_model');

        $post = $this->input->post();

        $data['admin_id'] = ( !empty($id) ) ? $id : $post['admin_id'];

        if (empty($post))
        {
            // this is only called if theres no post data
            if(empty($data['admin_permissions'])) {
                // get admin_permissions data

                $this->load->model('admin_administrators_model');
                $data['admin_permissions'] = $this->admin_administrators_model->admin_permissions();
                $data['admin_current_permissions'] = $this->admin_administrators_model->admin_current_permissions( $id );
            }

            $this->load->view('admin/header');
            $this->load->view('admin/administrators/admin_permissions', $data);
            $this->load->view('admin/footer');
            return;
        }
        else
        {
            $this->admin_administrators_model->update_admin_permissions();
            $this->session->set_flashdata('msg', 'Success: admin_credentials has been updated');
            redirect('/admin/administrators/all');
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
        $this->load->model('admin_administrators_model');

        $data['administrators'] = $this->input->post();

        $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Password Confirm', 'trim|required|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('security_pass', 'Security Pass', 'trim|required|min_length[7]');

        if ($this->form_validation->run() === FALSE) {

            // load template
            $data = "";
            $this->load->view('admin/header');
            $this->load->view('admin/administrators/create', $data);
            $this->load->view('admin/footer');
        }
        else
        {
            $this->admin_administrators_model->set_administrators($data);
            $this->session->set_flashdata('msg', 'Success: New administrator user created');
            redirect('/admin/administrators/all');
        }
    }
}