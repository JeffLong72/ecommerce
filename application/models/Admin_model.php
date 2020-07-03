<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function do_login($data)
	{
		$result = FALSE;	
		
		// set site
		$site = $this->config->item('template'); // config
		
		// get this users details
		$query = $this->db->query('SELECT * 
														FROM admin_users 
														WHERE username = "'.$data['admin_login']['login_username'].'"
														AND active = 1
														AND status = 0');
		$rows = $query->result_array();
		
		if(!empty($rows)) {

			// update login attempt for this user
			$update = array(
							'login_attempts' => $rows[0]['login_attempts']+1,
							'last_login_attempt' => date("Y-m-d H:i:s"),								
						);
			$this->db->where('username', $data['admin_login']['login_username']);
			$this->db->update('admin_users', $update);	

			// if user still has login attempts remaining
			if($rows[0]['login_attempts'] < 5) {
				// check for correct password
				// security: password_hash()
				// http://php.net/manual/en/function.password-hash.php
				// http://php.net/manual/en/function.password-verify.php			
				$hash = $rows[0]['password'];
				if (password_verify($data['admin_login']['login_password'], $hash)) {
					// success
					$result = "login_true";
					$_SESSION['admin']['site'] = $rows[0]['site']; // set site 					
					// reset login attempts on successful login and update last login time/date
					$update = array(
									'login_attempts' => 0,	
									'last_login' => date("Y-m-d H:i:s"),												
								);
					$this->db->where('username', $data['admin_login']['login_username']);
					$this->db->update('admin_users', $update);					
				} else {
					// fail
					$result = "login_false";
				}
			}
			// else, user has exceeded all login attempts, 
			// ( change user to inactive to prevent further login attempts )
			// ( todo: we could add send email alert here too in the future )
			else {
				$update = array(
								'active' => 0
							);
				$this->db->where('username', $data['admin_login']['login_username']);
				$this->db->update('admin_users', $update);
				
				$result = "login_attempts_exceeded";				
			}

		}

		// return result
		return $result;		
	}
	
	public function get_admin_details($data) {
		
		// set site
		$site = $this->config->item('template'); // config		
		
		// get this users details
		$query = $this->db->query('SELECT * 
														FROM admin_users 
														WHERE username = "'.$data['admin_login']['login_username'].'"													
														AND active = 1
														AND status = 0');
		$rows = $query->result_array();
		
		if(!empty($rows)) {
			return $rows;
		}
	}
	
	public function get_security_word() {
		
		// set site
		$site = $this->config->item('template'); // config		
		
		// get this secret word
		$query = $this->db->query('SELECT security_pass
									FROM admin_users 
									WHERE id = "'.$_SESSION['admin']['id'].'"
									AND site = "'.$site.'"
									AND active = 1
									AND status = 0');
		$rows = $query->result_array();
		
		if(!empty($rows)) {
			
			// the users secret word
			$secret_word = str_split($rows[0]['security_pass']);
			array_unshift($secret_word, ""); // as our post array starts at 1 we need to add a null value for zero in our $secret_word array

			// get users post
			$values = $this->input->post();
			
			// loop for each post character and compare character position to secret word character position
			$result = FALSE;
			foreach( $values['values'] as $key => $value ) {
				
				if( $value == $secret_word[$key] ) {
					$result = TRUE;
				}
				else {
					$result = FALSE;
				}
				
			}
			
			// if result is false, lock down the users account ( set to inactive )
			if( empty ( $result ) ) 
			{
				$update = array(
								'active' => 0
							);
				$this->db->where('id', $_SESSION['admin']['id']);
				$this->db->update('admin_users', $update);			
			}
			
			// return result
			return ( ! empty ( $result ) ) ? "success" : "fail";

		}
		
	
	}	

}
