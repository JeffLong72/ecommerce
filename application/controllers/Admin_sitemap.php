<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_sitemap extends CI_Controller {

    public function index()
    {
        echo "nothing here yet";
    }

    public function pages()
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
        $this->load->view('admin/sitemap/pages',$data);
        $this->load->view('admin/footer');

    }

    public function images()
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
        $this->load->view('admin/sitemap/images',$data);
        $this->load->view('admin/footer');

    }

    public function video()
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
        $this->load->view('admin/sitemap/video',$data);
        $this->load->view('admin/footer');

    }

    public function content()
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
        $this->load->view('admin/sitemap/content',$data);
        $this->load->view('admin/footer');

    }
}