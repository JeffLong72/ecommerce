<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_global extends CI_Model {

	public function check_status()
	{
		// check user is logged in
		if(empty($_SESSION['admin']['logged_in'])){
			$this->session->set_flashdata('msg', 'You must be logged in to access this section');
			redirect('/admin');		
		}
		
		// check user is allowed to access this section of the website
		$section = $this->uri->segment(2);
		$sub_section = $this->uri->segment(3);
		$sub_section_qry = (!empty($sub_section)) ? " and sub_section = '".$sub_section."' " : "";
		$sub_section_msg = (!empty($sub_section)) ? " > ".ucfirst($sub_section) : "";
		$query = $this->db->query('select id
														from admin_roles
														where section= "'.$section.'"
														'.$sub_section_qry.'
														and admin_id = '.$_SESSION['admin']['id'].'	
														and active = 1
														and status = 0');
		$result = $query->result_array();
		
		if(empty($result) && (!empty($section) && $section != "dashboard")) {
			$this->session->set_flashdata('msg', 'User not authorised: You do not have access to '.ucfirst($section).' '.$sub_section_msg);
			redirect('/admin/dashboard');				
		}		
	}
}
