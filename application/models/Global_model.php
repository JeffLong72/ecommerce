<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Global_model extends CI_Model {	
	
	function breadcrumb()
	{
		$url = uri_string();
		$url = explode("/", $url);

		if(!empty($url[0])) {
		
			$url_href = substr(base_url(), 0, -1);
			
			$breadcrumb_ul = "<ul class='breadcrumbs'>";
			$breadcrumb_ul.= "<li><a href='".base_url()."'>Home</a></li>";
		
			foreach($url as $u) {
				$url_href.= "/".$u;
				if($u != end($url)) {
					$breadcrumb_ul.="<li><span>></span>";
					if($u == "all") {
						$breadcrumb_ul.=ucwords(str_replace("_", " ", str_replace("-", " ", urldecode($u))));
					}
					else {
						$breadcrumb_ul.="<a href='".$url_href.".html'>".ucwords(str_replace("_", " ", str_replace("-", " ", urldecode($u))))."</a>";
					}				
					$breadcrumb_ul.="</li>";
				}
				else {
					if(!is_numeric($u)) {
						$breadcrumb_ul.= "<li><span>></span>".ucwords(str_replace("_", " ", str_replace("-", " ", urldecode($u))))."</li>";
					}
				}
			}
		
			$category = $this->uri->segment(1);
			
			if($category == "search") {
				$breadcrumb_ul = "<ul class='breadcrumbs'>";
				$breadcrumb_ul.= "<li><a href='".base_url()."'>Home</a></li>";
				$breadcrumb_ul.="<li><span>></span><a href='".base_url()."search.html'>Search</a></li>";
				$breadcrumb_ul.= "<li><span>></span>All</li>";
			}
			
			// we dont want to show the full breadcrumbs if this is a confirm email address or a news letter confirm url, etc
			$controller = $this->uri->segment(1); 
			$method = $this->uri->segment(2); 
			
			if( $method == "confirm_email_address" || $method == "reset_password" || $controller == "newsletter_confirm" || $controller == "unsubscribe"  || $controller == "newsletter_submit" ) {
				$breadcrumb_ul = "<ul class='breadcrumbs'>";
				$breadcrumb_ul.= "<li><a href='".base_url()."'>Home</a></li>";
				$breadcrumb_ul.= "<li><span>></span>User</li>";				
			}
			
			$breadcrumb_ul.= "</ul>";
			
			return $breadcrumb_ul;
		}

	}

	function show_items_in_basket()
	{
		$site = $this->config->item('template'); // config
		
		// set default currency rate for site
		// e.g. en site is GBP, so exchange rate would be 1
		if($site == "en") {	
			if(empty($_SESSION['site']['currency_id'])) { // on first page load only
				$_SESSION['site']['currency_id'] = 1;		
				$_SESSION['site']['currency_html'] = "£";							
				$_SESSION['site']['currency_rate'] = 1;
				$_SESSION['site']['currency_text'] = "GBP";						
			}
		}		

		$this->load->model('checkout_model');
		$data['products'] = $this->checkout_model->get_products_in_basket($site);
		
		$total_cost = 0;
		$total_items = 0;
		
		if(!empty($data['products'])) {
			foreach($data['products'] as $product) {
				
				$product_cost = ( $product['cost'] * $_SESSION['site']['currency_rate'] );
				$product_cost = number_format((float)($product_cost), 2, '.', '');
				
				$total_cost += number_format((float)( $product['qty'] * $product_cost), 2, '.', '');
				$total_items += $product['qty'];
			}
		}

		return array( 
					'total_cost' => number_format((float)$total_cost, 2, '.', ''), 
					'total_items' => $total_items
				);

	}	
	
	function switch_currency_html () {
		
		$site = $this->config->item('template'); // config
		
		// set default currency rate for site
		// e.g. en site is GBP, so exchange rate would be 1
		// if($site == "en") {	
			if(empty($_SESSION['site']['currency_id'])) { // on first page load only
				$_SESSION['site']['currency_id'] = 1;		
				$_SESSION['site']['currency_html'] = "£";							
				$_SESSION['site']['currency_rate'] = 1;
				$_SESSION['site']['currency_text'] = "GBP";				
			}
		// }		
	
		// create html
		$query = $this->db->query('SELECT * 
														FROM currency_converter 
														WHERE active = 1');
		$rows = $query->result_array();	
		
		if(!empty($rows)) {
			$html = "<select id='switch_currency' class='switch_currency'>";
			foreach($rows as $row) {
				$selected = (!empty($_SESSION['site']['currency_id']) && $row['id'] == $_SESSION['site']['currency_id']) ? " selected='selected' " : "";
				$html.="<option value='".$row['id']."' ".$selected.">".$row['currency_html']." ".$row['currency_text']."</option>";
			}
			$html.= "</select>";
		}

		echo $html;
		
	}
	
	function switch_currency ($site="", $data="") {

		$query = $this->db->query('SELECT * 
														FROM currency_converter 
														WHERE 
														id = "'.$data['currency'].'"
														AND active = 1');
		$rows = $query->result_array();	
		
		if(!empty($rows)) {
			$_SESSION['site']['currency_id'] = $rows[0]['id'];
			$_SESSION['site']['currency_html'] = $rows[0]['currency_html'];		
			$_SESSION['site']['currency_rate'] = $rows[0]['currency_rate'];
			$_SESSION['site']['currency_text'] = $rows[0]['currency_text'];					
		}
		
	}
	
	function log_interactions ($data = "") {

		// return ""; // JL - temp function disabled 11/10/2018, Google chrome started freezing, not sure if this function is the issue
		
		$site = $this->config->item('template'); // config
	
		if(empty($_SESSION['site']['user_session'])) {
			$_SESSION['site']['user_session'] = date("U");
		}
		
		$user_id = (!empty($_SESSION['customer']['details']['id'])) ? $_SESSION['customer']['details']['id'] : 0;
		
		$data_tag = (!empty($data['data_tag'])) ? $data['data_tag'] : "";
		
		$current_url = (!empty($data['current_url'])) ? $data['current_url'] : current_url();
		
		$page_title = (!empty($data['page_title'])) ? $data['page_title'] : "";		
		
		$insert = array( 
						'site' => $site,
						'user_session' => $_SESSION['site']['user_session'],
						'user_id' => $user_id,
						'user_ip' => $this->get_ip_address(),
						'page_title' => $page_title,						
						'page_url' => $current_url,
						'tag_clicked' => $data_tag,
						'date_time' => date("Y-m-d H:i:s")
					);
			
		// some strange bug shows the light gallery css map as a page click on each interaction?
		// lets ignore the light gallery css map interaction when adding new interactions to table
		if (strpos($current_url, 'lightgallery.css.map.html') === false) {
			$this->db->insert('log_interactions', $insert);	
		}					
			
	}
	
	function get_ip_address(){
		
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function admin_action($controller="", $method="", $admin_action="", $admin_data = "") {
	
		// get site id
		$site = $this->config->item('template'); // config
		$site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// get admin id 
		$admin_id = $_SESSION['admin']['id'];
		
		// insert data
		$insert = array(
						'site' => $site,			
						'admin_id' => $admin_id,
						'admin_controller' => $controller,
						'admin_method' => $method,						
						'admin_action' => $admin_action,
						'admin_data' => $admin_data,
					);		
		
		$this->db->insert('admin_actions', $insert);	
	}
	
	function debug ($data) {
		
		if( ! empty ( $data ) ) {
			echo "<p style='margin-top: 70px;'><strong>DEBUG</strong></p>";
			echo "<pre>";
			print_r( $data );
			echo "</pre>";
		}
		
	}
	
	function detect_mobile () {
		
		// load model
		$this->load->model('mobile_detect_model');
		
		// detect if mobile
		$mobile = $this->mobile_detect_model->isMobile();
		$mobile = ( ! empty( $mobile ) ) ? "mobile" : "";
		
		// detect if tablet
		$tablet = $this->mobile_detect_model->isTablet();
		$result = ( ! empty( $tablet ) ) ? "tablet" : $mobile;
		
		// if results empty, its a desktop
		$result = ( ! empty( $result ) ) ? $result : "desktop";

		// return result
		return $result;
	}
	
	function set_referral_details () {

		// get site id
		$site = $this->config->item('template'); // config
		
		// get referral url
		$referring_url =  ( ! empty ( $_SERVER['HTTP_REFERER'] ) ) ? $_SERVER['HTTP_REFERER'] : "Unknown";	

		// get user session
		$user_session = ( ! empty ( $_SESSION['site']['user_session'] ) ) ? $_SESSION['site']['user_session'] : "";
		
		// get affiliate id 
		$affiliate_id = ( ! empty ( $_GET['aff'] ) ) ? $_GET['aff'] : ""; // get affiliate id from url
		$affiliate_id = ( ctype_alnum ( $affiliate_id ) == 1 ) ? $affiliate_id : ""; // affiliate ids should only contain characters azAZ09

		// lets make sure this entry hasn't already been added
		if( ! isset ( $_SESSION['site']['referral_url'] ) ) {
			
			// insert data 
			// ( we only want to log incoming referrals from other websites )
			if (strpos(base_url(), $_SERVER['SERVER_NAME']) === false) {
				$insert = array(
								'site' => $site,
								'user_session' => $user_session,
								'affiliate_id' => $affiliate_id,
								'referral_url' => $referring_url , 
								'landing_page' => current_url(),						
							);		
				$this->db->insert('referrals', $insert);	
			}

		}

	}
	
    function create_invoice_pdf( $order_id = "" ) {
		
		// get site id
        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session
		
		// PDF - http://www.tcpdf.org
		$this->load->library("Pdf");
	  
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
	  
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// remove black line spacer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);		
	  
		// Add a page
		$pdf->AddPage(); 

		// set page data
		// get company details
		$query = $this->db->query('select * 
														from cms_sites 
														where template = "'.$site.'"
														and active = 1');
		$result = $query->result_array();
		$data['site'] = (!empty($result[0])) ? $result[0] : "";	



		// get order details
		$query = $this->db->query('select * 
														from orders  
														where order_id = "'.$order_id.'"
														and active = 1');
		$result = $query->result_array();	
		$data['order']= (!empty($result[0])) ? $result[0] : "";	
		
		// get order products 
		$query = $this->db->query('select * 
														from orders_products 
														where order_id = "'.$order_id.'"
														and active = 1');
		$result = $query->result_array();	
		$data['products']= (!empty($result)) ? $result : "";			
		
		// get order currency 
		$query = $this->db->query('select currency_html
														from  currency_converter
														where currency_text = "'.$data['order']['order_currency'].'"
														and active = 1');
		$result = $query->result_array();	
		$data['currency']= (!empty($result[0])) ? $result[0] : "";			
		
		// load template
		$html = $this->load->view('admin/pdf/'.$site.'/invoice',$data,TRUE);
	  
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);   
	  
		// Close and output PDF document
		$pdf->Output('OrderID_'.$order_id.'.pdf', 'I');    

	}		
		
}
