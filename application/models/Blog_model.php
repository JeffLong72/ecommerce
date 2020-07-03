<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model {
	
	public function count_all_blogs($site = "")
	{
		$today = date("Y-m-d 00:00:00");
		$query = $this->db->query('SELECT * FROM blog a
									WHERE id = (
										SELECT max(ID) FROM blog b WHERE a.revision = b.revision AND site = "'.$site.'"
									)
									AND site = "'.$site.'"
									AND active = 1 
									AND blog_date <= "'.$today.'"
									ORDER BY blog_date DESC');
		$rows = $query->result_array();
		
		// echo $this->db->last_query();
		
		return $rows;
	}
	
	public function get_all_blogs($site = "", $id = 0, $limit = 10)
	{
		$today = date("Y-m-d 00:00:00");
		$query = $this->db->query('SELECT * FROM blog a
									WHERE id = (
										SELECT max(ID) FROM blog b WHERE a.revision = b.revision AND site = "'.$site.'"
									)
									AND site = "'.$site.'" 
									AND active = 1 
									AND blog_date <= "'.$today.'"
									ORDER BY blog_date DESC
									LIMIT '.(int)$id.','.(int)$limit);
		$rows = $query->result_array();

		return $rows;
	}

	public function get_blog_page($site="",$slug="")
	{
		$today = date("Y-m-d 00:00:00");
		$query = $this->db->query('SELECT * FROM  blog 
									WHERE site = "'.$site.'" 
									AND slug = "'.$slug.'"
									AND active = 1
									AND blog_date <= "'.$today.'"
									ORDER BY id DESC
									LIMIT 1');
		$rows = $query->result_array();
		
		// echo $this->db->last_query();
		
		return $rows;
	}
	
	public function get_latest_blogs($site = "")
	{
		$today = date("Y-m-d 00:00:00");
		
		$this->db->cache_off();
		$query = $this->db->query('SELECT * FROM blog a
									WHERE id = (
										SELECT max(ID) FROM blog b WHERE a.revision = b.revision AND site = "'.$site.'"
									)
									AND site = "'.$site.'" 
									AND active = 1 
									AND blog_date <= "'.$today.'"
									ORDER BY blog_date DESC
									LIMIT 3');
		$rows = $query->result_array();

		return $rows;
	}
	
	public function get_all_blogs_by_date($site = "")
	{
		$today = date("Y-m-d 00:00:00");
		
		$this->db->cache_off();
		$query = $this->db->query('SELECT * FROM blog a
									WHERE id = (
										SELECT max(ID) FROM blog b WHERE a.revision = b.revision AND site = "'.$site.'"
									)
									AND site = "'.$site.'" 
									AND active = 1 
									AND blog_date <= "'.$today.'"
									GROUP BY YEAR(blog_date), MONTH(blog_date)
									ORDER BY blog_date DESC');
		$rows = $query->result_array();

		return $rows;
	}	
	
	public function count_all_blogs_by_date_range($site = "", $date_range = "")
	{
		$this->db->cache_off();		
		$query = $this->db->query('SELECT * FROM blog a
									WHERE id = (
										SELECT max(ID) FROM blog b WHERE a.revision = b.revision AND site = "'.$site.'"
									)
									AND site = "'.$site.'"
									AND active = 1 
									AND blog_date <= "'.$date_range.'%"
									ORDER BY blog_date DESC');
		$rows = $query->result_array();
		
		// echo $this->db->last_query();
		
		return $rows;
	}	
	
	public function get_all_blogs_by_date_range($site = "", $date_range = "")
	{
		$this->db->cache_off();
		$query = $this->db->query('SELECT * FROM blog a
									WHERE id = (
										SELECT max(ID) FROM blog b WHERE a.revision = b.revision AND site = "'.$site.'"
									)
									AND site = "'.$site.'" 
									AND active = 1 
									AND blog_date LIKE "'.$date_range.'%"
									ORDER BY blog_date DESC');
		$rows = $query->result_array();
		
		// echo $this->db->last_query();		

		return $rows;
	}		
	
}
