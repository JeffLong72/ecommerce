<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_reviews extends CI_Controller {

    public function index()
    {
        // $this->output->enable_profiler(TRUE);

        $this->load->view('admin/header');
        $this->load->view('admin/reviews/index');
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
        $keyword_inc = ($keyword != "-") ? " AND ( rev.comments LIKE '%".$keyword."%') " : "";

        // get results count
        $query = $this->db->query('SELECT id
									FROM products_reviews AS rev 
									WHERE rev.site = "'.$site.'"
									AND rev.status = 0
									'.$keyword_inc);
        $result = $query->result_array();
        $data['count'] = $result;

        // get this site reviews
        // add limit so we can use pagination
        $query = $this->db->query('SELECT *
									FROM products_reviews AS rev
									WHERE rev.site = "'.$site.'"
									AND rev.status = 0
									'. $keyword_inc .'
									LIMIT '.(int)$id.','.(int)$limit);
        $result = $query->result_array();
        $data['reviews'] = $result;

        // pagination
        $this->load->library('pagination');
        $keyword = (!empty($keyword)) ? $keyword : "-";
        $config['base_url'] = base_url().'admin/reviews/all/'.$keyword.'/'.$dir.'/';
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
        $this->load->view('admin/reviews/all', $data);
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
        $this->load->model('review_model');

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('comments', 'Comments', 'required');

        $data['reviews'] = $this->input->post();

        if ($this->form_validation->run() === FALSE)
        {
            // this is only called if theres no post data ( edit only )
            if(empty($data['reviews'])) {
                // get reviews data
                $query = $this->db->query('SELECT * 
                                        FROM products_reviews 
                                        WHERE id = '.(int)$id);
                $result = $query->result_array();
                $data['reviews'] = $result[0];
            }

            // get reviews options
            $query = $this->db->query('SELECT * 
                                        FROM products_reviews 
                                        WHERE id = '.(int)$id);
            $result = $query->result_array();
            $data['products_reviews'] = $result;

            $this->load->view('admin/header');
            $this->load->view('admin/reviews/edit', $data);
            $this->load->view('admin/footer');
            return;
        }
        else
        {
            $this->review_model->update_reviews($data);
            $this->session->set_flashdata('msg', 'Success: Review id '.(int)$id.' has been updated');
			redirect('/admin/reviews/all');
        }
    }
}