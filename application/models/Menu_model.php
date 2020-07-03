<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {
	
	public function get_menu($template)
	{
		$template = $this->config->item('template');
		
		$this->db->cache_off();
		$query = $this->db->query('SELECT id, category_name, url, menu_html FROM menu WHERE level = 0 AND active = 1 AND site = "'.$template.'" ORDER BY menu_order ASC');
		$level_zero = $query->result_array();
		
		$menu = "<p>No menu found</p>";

		if(!empty($level_zero)) {
			
			$menu = "<div class='navbar'>";
		
			foreach($level_zero as $zero) {
				
				$this->db->cache_on();
				$query = $this->db->query('SELECT id, category_name, url FROM menu WHERE level = 1 AND parent_id = '.$zero['id'].' AND active = 1 AND site = "'.$template.'" ORDER BY menu_order ASC');
				$level_one = $query->result_array();					

				if(empty($level_one)) {
					$menu.= "<a class='nav_no_dropdown' href='".base_url().$zero['url'].".html'>".$zero['category_name']."</a>";
				}
				else {
					$menu.= '
						<div class="dropdown">
							<button class="dropbtn"><a class="data-tag" data-tag="menu-category-level0-'.$zero['category_name'].'" href="'.base_url().$zero['url'].'.html">'.$zero['category_name'].'</a> 
								 <i class="fa fa-caret-down"></i>
							</button>
							<div class="dropdown-content">
								<div class="header"></div>
								<div class="row">
									<div style="float:left;width:70%;" class="menu_links_container">';	

									if(!empty($level_one)) {
										foreach($level_one as $one) {
											
											$this->db->cache_on();
											$query = $this->db->query('SELECT id, category_name, url FROM menu WHERE level = 2 AND parent_id = '.$one['id'].' AND active = 1 AND site = "'.$template.'" ORDER BY menu_order ASC');
											$level_two = $query->result_array();
											
											$menu.= '<div class="column" style="border-left: 1px solid #ccc;">
														<h3><a class="level_one data-tag" data-tag="menu-category-level1-'.$one['category_name'].'" href="'.base_url().$zero['url'].'/'.$one['url'].'.html">'.$one['category_name'].'</a></h3>';

											if(!empty($level_two)) {
						
												foreach($level_two as $two) {

													$menu.= '<a class="level_two data-tag" data-tag="menu-category-level2-'.$two['category_name'].'"  href="'.base_url().$zero['url'].'/'.$one['url'].'/'.$two['url'].'.html">'.$two['category_name'].'</a>';
												}
												
											}

											$menu.= '</div>';	
											
										}
										
									}
										
									$menu.= '
								
									</div>
									<div style="float:right;width:30%;" class="menu_promo_container">
										<div class="column menu_promo data-tag" data-tag="menu-promo-item-'.$zero['category_name'].'" >
											'.$zero['menu_html'].'
										</div>
									</div>								
								</div>						
							</div>
						</div>	
					';
				}
			}
			$menu.= "</div>";
		}
        return $menu;
	}

    public function update_menu_promo($data = "")
    {

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('url');

        // update menus
        $active = (!empty($data['menu_promo']['active'])) ? 1 : 0;
        $update_menu = array(
            'menu_html' => $data['menu_promo']['menu_html'],
            'menu_html_active' => $active
        );

        $this->db->where('id', $data['menu_promo']['id']);
        $result = $this->db->update('menu', $update_menu);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Menu_model', 'update_menu_promo', 'update',  print_r($update_menu, TRUE));

        return $result;
    }
}
