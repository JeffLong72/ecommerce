<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	
	public function check_login($site="") {
		if( empty( $_SESSION['customer']['details'] ) ) {
			$this->session->set_flashdata('msg', 'Please login to access this feature');
			redirect( base_url()."user/login");
		}
	}

	public function do_login($site = "", $data = "", $login_area = "")
	{
		$result = array();	
		
		// get this users details
		$this->db->cache_off();
		$query = $this->db->query('SELECT * 
														FROM users 
														WHERE email = "'.$data['email'].'"
														AND site = "'.$site.'"
														AND active = 1
														AND status = 0');
		$rows = $query->result_array();

		if(!empty($rows)) {

			// update login attempt for this user
			$update = array(
							'login_attempts' => $rows[0]['login_attempts']+1,
							'last_login_attempt' => date("Y-m-d H:i:s"),								
						);
			$this->db->where('email', $data['email']);
			$this->db->where('site', $site);					
			$this->db->update('users', $update);	

			// if user still has login attempts remaining
			// if($rows[0]['login_attempts'] < 5) {
				// check for correct password
				// security: password_hash()
				// http://php.net/manual/en/function.password-hash.php
				// http://php.net/manual/en/function.password-verify.php			
				$hash = $rows[0]['password'];
				if (password_verify($data['password'], $hash)) {
					// success
					$result = $rows[0];
					$result["result"] = "login_true";					
					unset($result["password"]); // we dont want to return this in the array!
					$_SESSION['customer']['details'] = $result;
					$_SESSION['customer']['logged_in'] = TRUE;
					// reset login attempts on successful login and update last login time/date
					$update = array(			
									'login_attempts' => 0,	
									'last_login' => date("Y-m-d H:i:s"),												
								);
					$this->db->where('email', $data['email']);
					$this->db->where('site', $site);		
					$this->db->update('users', $update);					
				} else {
					// fail
					$result = array("error" => "login_false");	
				}
			// }
			// else, user has exceeded all login attempts, 
			// ( change user to inactive to prevent further login attempts )
			// ( todo: we could add send email alert here too in the future )
			// else {
			//	$update = array(		
			//					'active' => 0
			//				);
			//	$this->db->where('email', $data['email']);
			//	$this->db->where('site', $site);				
			//	$this->db->update('users', $update);
			//	
			//	$result = array("error" => "login_attempts_exceeded");				
			// }

		}

		// return result
		if($login_area == "customer-area") {
			return $result;		
		}
		else {
			echo json_encode($result);	
		}
	}
	
	function get_user_orders() {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// user email
		$email = $_SESSION['customer']['details']['email'];
		
		// get data 
		$this->db->cache_off();
		$query = $this->db->query('SELECT t1.* , t2.currency_html
														FROM orders AS t1
														LEFT JOIN currency_converter AS t2 ON t1.order_currency = t2.currency_text
														WHERE t1.email = "'.$email.'"
														AND t1.site = "'.$site.'"
														AND t1.active = 1
														AND t1.status = 0
														AND t1.order_paid = 1
														AND t1.order_status = 3													
														ORDER BY id DESC');
		$rows = $query->result_array();

		if(!empty($rows)) {	
			return $rows;
		}
		
	}
	
	function get_user_orders_products($order_id = "") {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// get data 
		$this->db->cache_off();
		$query = $this->db->query('SELECT t1.* , t2.currency_html
														FROM orders_products AS t1
														LEFT JOIN currency_converter AS t2 ON t1.currency = t2.currency_text
														WHERE t1.order_id = "'.$order_id.'"
														AND t1.site = "'.$site.'"
														AND t1.active = 1
														AND t1.status = 0
														ORDER BY id DESC');
		$rows = $query->result_array();

		if(!empty($rows)) {	
			return $rows;
		}
		
	}	
	
	
	public function get_wishlist_items() {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// user id
		$user_id = $_SESSION['customer']['details']['id'];			
	
		// get data 
		$this->db->cache_off();
		$query = $this->db->query('SELECT t1.*, t1.date_added AS date_item_added, t2.title, t3.slug, t4.cost, t4.special_offer_cost
														FROM users_wishlist AS t1
														LEFT JOIN products_text as t2 ON t1.sku = t2.sku
														LEFT JOIN products as t3 ON t1.sku = t3.sku				
														LEFT JOIN products_cost as t4 ON t1.sku = t4.sku																	
														WHERE t1.user_id = "'.$user_id.'"
														AND t1.site = "'.$site.'"
														AND t1.active = 1
														AND t1.status = 0
														ORDER BY date_added DESC');
		$rows = $query->result_array();

		if(!empty($rows)) {	
			return $rows;
		}		
	}

	public function newsletter() {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// user id
		$user_id = $_SESSION['customer']['details']['id'];		
		
		// get data 
		$this->db->cache_off();
		$query = $this->db->query('SELECT user_email, user_confirmed_date
														FROM newsletter_subscriptions
														WHERE user_id = "'.$user_id.'"
														AND site = "'.$site.'"
														AND active = 1
														AND status = 0
														LIMIT 1');
		$rows = $query->result_array();

		if(!empty($rows)) {	
			return $rows;
		}		
	}

	public function personal() {

		// get site id
		$site = $this->config->item('template'); // config
	
		// get submitted data
		$user_details = $this->input->post();
		
		// user id
		$user_id = $_SESSION['customer']['details']['id'];	
		
		// update users details
		if(!empty($user_details['customer_password'])) 
		{
			$password = password_hash($user_details['customer_password'], PASSWORD_DEFAULT); // lets encrypt the password!
			
			$update = array(
							'first_name' => $user_details['billing_first_name'],
							'last_name' => $user_details['billing_last_name'],
							'house_number' => $user_details['billing_house_number'],
							'street_name' => $user_details['billing_street_name'],		
							'town_city' => $user_details['billing_town_city'],
							'county' => $user_details['billing_county'],		
							'country' => $user_details['billing_country'],									
							'postcode' => $user_details['billing_postcode'],
							'telephone' => $user_details['billing_telephone'],		
							'email' => $user_details['email'],
							'password' => $password,									
						);
		}
		else
		{
			$update = array(
							'first_name' => $user_details['billing_first_name'],
							'last_name' => $user_details['billing_last_name'],
							'house_number' => $user_details['billing_house_number'],
							'street_name' => $user_details['billing_street_name'],		
							'town_city' => $user_details['billing_town_city'],
							'county' => $user_details['billing_county'],		
							'country' => $user_details['billing_country'],									
							'postcode' => $user_details['billing_postcode'],
							'telephone' => $user_details['billing_telephone'],		
							'email' => $user_details['email'],			
						);			
		}
		
		$this->db->where('id', $user_id);
		$this->db->where('site', $site);					
		$this->db->update('users', $update);	
		
		// get users details based on whats in the db and update users session
		// IMPORTANT - cache must be off or the users session wont update!
		$this->db->cache_off();
		$query = $this->db->query('SELECT * 
														FROM users 
														WHERE id= "'.$user_id.'"
														AND site = "'.$site.'"
														AND active = 1
														AND status = 0');
		$rows = $query->result_array();	
	
		// success
		$result = $rows[0];			
		unset($result["password"]); // we don't want to return this in the array!
		$_SESSION['customer']['details'] = $result;
		
		return TRUE;
	}		
	
	public function store_credit() {
		
		// get site id
		$site = $this->config->item('template'); // config
		
		// user id
		$user_id = $_SESSION['customer']['details']['id'];		
		
		// get data 
		$this->db->cache_off();
		$query = $this->db->query('SELECT store_credit
														FROM users
														WHERE id = "'.$user_id.'"
														AND site = "'.$site.'"
														AND active = 1
														AND status = 0
														LIMIT 1');
		$rows = $query->result_array();

		if(!empty($rows)) {	
			return $rows[0];
		}		
		
	}
	
	public function register() {

		// get site id
		$site = $this->config->item('template'); // config
	
		// get submitted data
		$user_details = $this->input->post();
		
		// does a user with this email already exist?
		$this->db->cache_off();
		$query = $this->db->query('SELECT store_credit
														FROM users
														WHERE email = "'.$user_details['email'].'"
														AND site = "'.$site.'"
														AND active = 1
														AND status = 0
														LIMIT 1');
		$rows = $query->result_array();	

		// if user exists
		if(!empty($rows)) {
			return FALSE;
		}
		
		// else register this user
		if(!empty($user_details['customer_password'])) 
		{
			$password = password_hash($user_details['customer_password'], PASSWORD_DEFAULT); // lets encrypt the password!
			
			$insert = array( 
								'site' => $site,
								'email' => $user_details['email'],
								'password' => $password,
							);
							
			$this->db->insert('users', $insert);	

			// load token helper
			$this->load->helper('token');
			$token = realuniqid ();
			
			// save token to db
			$update = array(
							'token' => $token				
						);
			$this->db->where('email', $user_details['email']);
			$this->db->where('site', $site);		
			$this->db->where('active', '1');		
			$this->db->where('status', '0');					
			$this->db->update('users', $update);				
			
			// send mail
			$this->load->library('email');
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from('noreply@unineedgroup.com', "New account registration");
			$this->email->to($user_details['email']);
			// $this->email->cc('scorpio.72@live.com');
			// $this->email->bcc('them@their-example.com');
			$this->email->subject('New account registration');
			$data = array(
								'email'=> urlencode($user_details['email']),
								'token'=> $token,
								);		 
			$body = $this->load->view($site.'/emails/account_register',$data,TRUE);
			$this->email->message($body);
			$sent = $this->email->send();					
		
			return TRUE;
		}
			
	}

	function confirm_user_email () {
		
		// get site id
		$site = $this->config->item('template'); // config

		$email_address = $this->uri->segment(3); 
		$token = $this->uri->segment(4); 	
		$token = str_replace(".html", "", $token);
				
		// check if we have a valid token
		$this->db->cache_off();
		$query = $this->db->query('SELECT token
														FROM users
														WHERE email = "'.urldecode($email_address).'"
														AND token = "'.$token.'"
														AND site = "'.$site.'"
														AND active = 1
														AND status = 0
														LIMIT 1');
		$rows = $query->result_array();	
	
		if(!empty($rows)) {

			// update user and set confirmed email address
			// save token to db
			$update = array(
							'confirmed_email' => '1',		
							'confirmed_email_date' => date("Y-m-d H:i:s"),										
							'token' => '',				
						);
			$this->db->where('email', urldecode($email_address));
			$this->db->where('token', $token);					
			$this->db->where('site', $site);		
			$this->db->where('active', '1');		
			$this->db->where('status', '0');					
			$this->db->update('users', $update);				

			return TRUE;
		}		
	}
	
	function forgot_password() {
		
		// get site id
		$site = $this->config->item('template'); // config

		// get submitted data
		$user_details = $this->input->post();
		
		// load token helper
		$this->load->helper('token');
		$token = realuniqid ();
				
		// check if we have a valid token
		$this->db->cache_off();
		$query = $this->db->query('SELECT email
														FROM users
														WHERE email = "'.$user_details['email'].'"
														AND site = "'.$site.'"
														AND active = 1
														AND status = 0
														LIMIT 1');
		$rows = $query->result_array();	

		if(!empty($rows)) {

			// save token to db
			$update = array(								
							'token' => $token,				
						);
			$this->db->where('email', $user_details['email']);			
			$this->db->where('site', $site);		
			$this->db->where('active', '1');		
			$this->db->where('status', '0');					
			$this->db->update('users', $update);	

			// send mail to user with link to reset password
			$this->load->library('email');
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from('noreply@unineedgroup.com', "Reset password");
			$this->email->to($user_details['email']);
			// $this->email->cc('scorpio.72@live.com');
			// $this->email->bcc('them@their-example.com');
			$this->email->subject('Reset password');
			$data = array(
								'token'=> $token,
								);		 
			$body = $this->load->view($site.'/emails/forgot_password',$data,TRUE);
			$this->email->message($body);
			$sent = $this->email->send();						

			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	function reset_password () {
		
		// get site id
		$site = $this->config->item('template'); // config

		// get url params
		$token = $this->uri->segment(3); 	
		$token = str_replace(".html", "", $token);

		// check if we have a valid token
		$this->db->cache_off();
		$query = $this->db->query('SELECT token
														FROM users
														WHERE token = "'.$token.'"
														AND site = "'.$site.'"
														AND active = 1
														AND status = 0
														LIMIT 1');
		$rows = $query->result_array();	
		
		if(!empty($rows)) {
			
			// get submitted data
			$user_details = $this->input->post();
			
			// update users password
			$password = password_hash($user_details['customer_password'], PASSWORD_DEFAULT); // lets encrypt the password!
			
			// save token to db
			$update = array(
							'token' => '',					
							'password' => $password				
						);
			$this->db->where('token', $token);
			$this->db->where('site', $site);		
			$this->db->where('active', '1');		
			$this->db->where('status', '0');					
			$this->db->update('users', $update);	

			return TRUE;

		}
		else {
			
			return FALSE;
			
		}
	
	}
	
	function add_to_wishlist () {
		
		// enable profiler
		//$this->output->enable_profiler(TRUE);	

		// if user is not logged in they need to login to add/view their wishlist
		if( empty( $_SESSION['customer']['details'] ) ) {
			echo "not_logged_in";
			return;
		}
		
		// get site id
		$site = $this->config->item('template'); // config

		// user id
		$user_id = $_SESSION['customer']['details']['id'];				

		// get submitted data
		$wishlist_item = $this->input->post();		

		if(!empty($wishlist_item['sku'])) {
			
			// check if item is already in users wishlist
			$this->db->cache_off();
			$query = $this->db->query('SELECT sku
															FROM users_wishlist
															WHERE sku = "'.$wishlist_item['sku'].'"
															AND user_id = "'.$user_id.'"															
															AND site = "'.$site.'"
															AND active = 1
															AND status = 0
															LIMIT 1');
			$rows = $query->result_array();	
			
			if(!empty($rows)) {
				echo "product_exists";
				return;			
			}
			
			// add product to users wishlist
			$insert = array( 
									'site' => $site,
									'user_id' => $user_id,
									'sku' => $wishlist_item['sku'],
									'date_added' => date("Y-m-d H:i:s"),								
								);
								
			$this->db->insert('users_wishlist', $insert);	

			echo "product_added";
			return;

		}

	}	

	function get_wishlist_images ( $sku = "" ) {
		$this->db->cache_off();
		$query = $this->db->query('SELECT *
														FROM products_images
														WHERE category != ""
														AND active = 1
														AND `status` = 0
														AND sku = "'.$sku.'"');
									
		$rows = $query->result_array();
		
		return (!empty($rows)) ? $rows : array();			
	}		

	function update_newsletter_subscription () {
		
		// get site id
		$site = $this->config->item('template'); // config

		// get submitted data
		$user = $this->input->post();	

		// user email
		$user_email = $user['newsletter_email'];
		
		// user unsubscribed reason
		$user_unsubscribed_reason = (!empty($user['unsubscription_reason'])) ? $user['unsubscription_reason'] : "";		
		
		// user id
		$user_id = $_SESSION['customer']['details']['id'];			

		// unsubscribe user from newsletter
		$update = array(
							'user_email' => '--unsubscribed--',
							'unsubscription_date' => date("Y-m-d H:i:s"),					
							'unsubscription_reason' => $user_unsubscribed_reason,					
							'active' => '0',					
							'status' => '1'	// set as deleted record			 
						);
						
		$this->db->where('user_email', $user_email);									
		$this->db->where('user_id', $user_id);								
		$this->db->where('site', $site);		
		$this->db->where('active', '1');		
		$this->db->where('status', '0');					
		$this->db->update('newsletter_subscriptions', $update);	
		
		return TRUE;
		
	}

    public function update_customer_user($data = "") {

        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('url');

        // set active value
        $active = (!empty($data['users']['active'])) ? 1 : 0;

        // create password
        $password = password_hash($data['users']['password'], PASSWORD_DEFAULT); // lets encrypt the password!

        $update_users = array(
            'first_name' => $site,
            'first_name' => $data['users']['first_name'],
            'last_name' => $data['users']['last_name'],
            'email' => $data['users']['email'],
            'password' => $password,
            'house_number' => $data['users']['house_number'],
            'street_name' => $data['users']['street_name'],
            'town_city' => $data['users']['town_city'],
            'county' => $data['users']['county'],
            'country' => $data['users']['country'],
            'postcode' => $data['users']['postcode'],
            'telephone' => $data['users']['telephone'],
            'store_credit' => $data['users']['store_credit'],

            'active' => $active
        );
        $this->db->where('id', $data['users']['id']);
        $this->db->update('users', $update_users);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Users_model', 'update_customer_user', 'update',  print_r($update_users, TRUE));

    }
}
