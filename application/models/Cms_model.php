<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_model extends CI_Model {

	public function get_cms_page($site="",$slug="")
	{
		$this->db->cache_off();					
		$query = $this->db->query('SELECT * 
									FROM cms_pages 
									WHERE site = "'.$site.'" 
									AND slug = "'.$slug.'" 
									ORDER BY id DESC
									LIMIT 1
									');
		$rows = $query->result_array();

		return $rows;
	}
	
	public function get_home_meta($site="")
	{
		$this->db->cache_on();					
		$query = $this->db->query('SELECT * 
									FROM cms_sites 
									WHERE template = "'.$site.'" 
									AND active = 1 
									ORDER BY id DESC
									LIMIT 1
									');
		$rows = $query->result_array();

		return $rows;
	}	
	
	public function get_main_category_page($site="",$slug="")
	{
		$this->db->cache_on();					
		$query = $this->db->query('SELECT id, category_name, category_intro, url, meta_title, meta_description
									FROM menu 
									WHERE site = "'.$site.'" 
									AND level = 0 
									AND url = "'.$slug.'" 
									ORDER BY menu_order ASC
									');
		$rows = $query->result_array();

		return $rows;
	}
	
	public function get_sub_level_one_category_page($site="",$slug="")
	{
		$this->db->cache_on();					
		$query = $this->db->query('SELECT id, category_name, category_intro, url, meta_title, meta_description
									FROM menu
									WHERE site = "'.$site.'" 
									AND level = 1 
									AND url = "'.$slug.'" 
									ORDER BY menu_order ASC
									');
		$rows = $query->result_array();

		return $rows;
	}
	
	public function get_sub_level_two_category_page($site="",$slug="")
	{
		$this->db->cache_on();					
		$query = $this->db->query('SELECT id, category_name, category_intro, url, meta_title, meta_description
									FROM menu
									WHERE site = "'.$site.'" 
									AND level = 2 
									AND url = "'.$slug.'" 
									ORDER BY menu_order ASC
									');
		$rows = $query->result_array();

		return $rows;
	}	
}
