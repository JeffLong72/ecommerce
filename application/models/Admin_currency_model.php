<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_currency_model extends CI_Model
{
    public function set_currency($data = "")
    {
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // create delivery
        $status = (!empty($data['currency']['status'])) ? 1 : 0;
        $insert_currency_rates = array(
            'currency_text' => $site,
            'currency_text' => $data['currency']['currency_text'],
            'currency_html' => $data['currency']['currency_html'],
            'currency_rate' => $data['currency']['currency_rate'],
            'active' => $status
        );
        $this->db->insert('currency_converter', $insert_currency_rates);
		
		// log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_currency_model', 'set_currency', 'insert',  print_r($insert_currency_rates, TRUE));			

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_currency_model', 'set_currency', 'insert',  print_r($insert_currency_rates, TRUE));

        return "success";
    }

    public function update_currency($data = "")
    {

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('url');

        // update currency
        $active = (!empty($data['currency']['active'])) ? 1 : 0;
        $update_currency_rates = array(
            'currency_text' => $site,
            'currency_text' => $data['currency']['currency_text'],
            'currency_html' => $data['currency']['currency_html'],
            'currency_rate' => $data['currency']['currency_rate'],
            'active' => $active
        );
        $this->db->where('id', $data['currency']['id']);
        $this->db->update('currency_converter', $update_currency_rates);

		// log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_currency_model', 'update_currency', 'update',  print_r($update_currency_rates, TRUE));		
	}
}