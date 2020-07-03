<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_delivery_model extends CI_Model
{

    public function set_delivery($data = "")
    {
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // create delivery
        $status = (!empty($data['delivery']['status'])) ? 1 : 0;
        $insert_delivery_rates = array(
            'site' => $site,
            'option' => $data['delivery']['option'],
            'cost' => $data['delivery']['cost'],
            'active' => $status
        );
        $this->db->insert('delivery_rates', $insert_delivery_rates);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_delivery_model', 'set_delivery', 'insert',  print_r($insert_delivery_rates, TRUE));

        return "success";
    }

    public function update_delivery($data = "")
    {

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('url');

        // update delivery
        $active = (!empty($data['delivery']['active'])) ? 1 : 0;
        $update_delivery_rates = array(
            'site' => $site,
            'option' => $data['delivery']['option'],
            'cost' => $data['delivery']['cost'],
            'active' => $active
        );
        $this->db->where('id', $data['delivery']['id']);
        $this->db->update('delivery_rates', $update_delivery_rates);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_delivery_model', 'set_delivery', 'update',  print_r($update_delivery_rates, TRUE));
    }

}