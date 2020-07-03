<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_administrators_model extends CI_Model
{
    public function set_administrators($data = "")
    {
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // create password
        $password = password_hash($data['administrators']['password'], PASSWORD_DEFAULT); // lets encrypt the password!

        $active = (!empty($data['administrators']['active'])) ? 1 : 0;
        $insert_administrator = array(
            'site' => $site,
            'username' => $data['administrators']['username'],
            'password' => $password,
            'email' => $data['administrators']['email'],
            'security_pass' => $data['administrators']['security_pass'],
            'admin_created_by' => $_SESSION['admin']['id'],
            'active' => $active
        );
        $this->db->insert('admin_users', $insert_administrator);
		
        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_administrators_model', 'set_administrators', 'insert',  print_r($insert_administrator, TRUE));

        return "success";
    }

    public function update_administrators($data = "")
    {

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('url');

        // set active value
        $active = (!empty($data['administrators']['active'])) ? 1 : 0;

		// create password
        $password = password_hash($data['administrators']['password'], PASSWORD_DEFAULT); // lets encrypt the password!

        $update_administrators = array(
            'username' => $site,
            'username' =>  $data['administrators']['username'],
            'password' => $password,
            'email' => $data['administrators']['username'],
            'security_pass' => $data['administrators']['security_pass'],
            'admin_created_by' => $_SESSION['admin']['id'],
            'active' => $active
        );
        $this->db->where('id', $data['administrators']['id']);
        $this->db->update('admin_users', $update_administrators);

		// log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Admin_administrators_model', 'update_administrators', 'update',  print_r($update_administrators, TRUE));
	}

    public function admin_permissions ()
    {

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // select query to get all rows
        $this->db->cache_off();
        $query = $this->db->query('SELECT *
									FROM admin_roles
									WHERE admin_id = "-1"
									AND site = "'.$site.'"
									AND active = 1
									AND status = 0
									order by section asc, sub_section asc');
        $rows = $query->result_array();

        return $rows;

    }

    public function admin_current_permissions ($id="")
    {

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        // select query to get all rows
        $this->db->cache_off();
        $query = $this->db->query('SELECT *
									FROM admin_roles
									WHERE admin_id = '.(int)$id);
        $rows = $query->result_array();

        return $rows;

    }

    public function update_admin_permissions ()
    {

        $data = $this->input->post();

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('url');

        $active = (!empty($data['product']['active'])) ? 1 : 0;

        $update = array(
            'active' => 0
        );
        $this->db->where('admin_id', $data['admin_id']);
        $this->db->update('admin_roles', $update);
		 
		foreach( $data as $key => $value ) 
		{;

            if( is_array( $value ) )
            {
                foreach( $value as $v )
                {
                    // check if entry already exists in table
                    $this->db->cache_off();
                    $query = $this->db->query('SELECT id
                                                    FROM admin_roles
                                                    WHERE site = "'.$site.'"
                                                    AND admin_id = "'.$data['admin_id'].'"                                                    
                                                    AND `section` = "'.$key.'"
                                                    AND sub_section = "'.$v.'"
                                                    AND active = 0');
                    $rows = $query->result_array();

                    // entry not in table, so add entry
                    if( empty ( $rows ) ) {

                        $insert = array(

                            'site' => $site,
                            'admin_id' => $data['admin_id'],
                            'section' => $key,
                            'sub_section' => $v,
                            'added_by_admin_id' => $_SESSION['admin']['id'],
                            'active' => 1
                        );

                        $this->db->insert('admin_roles', $insert);
                    }
                    // else update entry
                    else {

                        $update = array(

                            'site' => $site,
                            'admin_id' => $data['admin_id'],
                            'section' => $key,
                            'sub_section' => $v,
                            'added_by_admin_id' => $_SESSION['admin']['id'],
                            'active' => 1
                        );

                        $this->db->where('id', $rows[0]['id']);
                        $this->db->update('admin_roles', $update);
                    }
                }
            }
		}
    }
}