<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Widgets_model extends CI_Model {
	
	function get_widgets_in_page($data)
	{	
		$l = explode("[", $data);
	  
		for($x=0;$x<count($l);$x++) 
		{
			$r = explode("]", $l[$x]);
			
			if(!empty($r[0])) 
			{
				// set array values for $w, 
				// [0] = widget_function_name, 
				// [1] = 1 enable shortcode, 0 disable shortcode
				$w = explode(",", $r[0]);

				if(!empty($w[1])) 
				{
					// first, check if widget function name exists
					// if so, get return value from widget function name and use str_replace
					// to replace the shortcode in $data with widget function return value :)
					$widget_code = "<span style='color:red;'>[Widget Not Found!]</span>"; // set default error
					if( method_exists( "Widgets_model", $w[0] ) )
					{
						$widget_code = $this->$w[0]();
					}
					
					$data = str_replace("[".$r[0]."]", $widget_code, $data);
				}      
			}
		}
/*  If it doesn't pick up the widget code, turn this on
		$class_methods = get_class_methods('Widgets_model');
			foreach ($class_methods as $method_name) {
			echo "$method_name\n";
		}  
*/

		return $data;
	}	
	
	
	
	function contact_us ( $site = "" ) {
		
		$site = $this->config->item('template'); // config

		$data = ""; // empty, we will compile this below

		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// form validation rules
		$this->form_validation->set_rules('contact_name', 'Name', 'required');	
		$this->form_validation->set_rules('contact_email', 'Email Address', 'required|valid_email');		
		$this->form_validation->set_rules('contact_message', 'Message', 'required');				

		if ($this->form_validation->run() === FALSE)
		{	
			// highlite any errors
			$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
		}
		else 
		{
			// alls good so far,
			// run user post through spam checker so we don't 
			// send admin junk mail or add junk mail to database
			
			// load spam helper
			$this->load->helper('spam_helper');	
		
			// check for spam in Name field
			$name = $this->input->post('contact_name');
			if(spam_filter($name)) {
				$data['spam'] = "Spam found in Name field";		
			}
			
			// check for spam in Email field
			$email = $this->input->post('contact_email');
			if(spam_filter($email)) {
				$data['spam'] = "Spam found in Email field";		
			}

			// check for spam in Message field
			$message = $this->input->post('contact_message');
			if(spam_filter($message)) {
				$data['spam'] = "Spam found in Message field";		
			}	

			// has form been filled in too fast? ( min 15 secs to prevent bot abuse )
			$spam_timer = $this->input->post('spam_timer');
			if( ( date("U") - $spam_timer ) < 15 ) {
				$data['spam'] = "You are submitting the form too fast! Please wait 15 seconds.";		
			}				
			
			// not spam? ok, now we can send mail, add to database, etc
			if(empty($data['spam'])) {

				// add to db table
				$insert = array(
								'site' => $site,
								'name' => $name,								
								'email' => $email,
								'message' => $message,			
							);
				$this->db->insert('log_contact_us', $insert);	
				
				// get website email address to send contact form too
				$query = $this->db->query('SELECT contact_form_email
																FROM cms_sites
																WHERE template = "'.$site.'"
																AND active = 1');
				$result = $query->result_array();	
				
				 // we need all contact form emails to go somewhere as a fall-back!
				 // ( worse case send to developer for further investigation why no contact email address )
				$email_to = (!empty($result)) ? $result[0]['contact_form_email'] : "jeff.long@unineedgroup.com";
				
				// send mail
				$this->load->library('email');
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				$this->email->from($email, $name);
				$this->email->to($email_to);
				// $this->email->cc('another@another-example.com');
				// $this->email->bcc('them@their-example.com');
				$this->email->subject('Contact Form');
				$data = array(
										'name'=> $name,
										'email'=> $email,
										'message'=> $message,										
									);		
					 
				$body = $this->load->view($site.'/emails/contact_us',$data,TRUE);
				$this->email->message($body);
				$sent = $this->email->send();	
				
				// lets look at the email data thats being sent, 
				// echo ($sent) ? $sent : "Email not sent";
				// echo "<hr>";
				// echo "<pre>";
				// print_r($this->email);
				// echo "</pre>";
				// die();
				
				// reload page and let user know the message has been sent
				$this->session->set_flashdata('msg', '<span style="color:green;">Your message has been sent successfully and a member of our team will contact you shortly.</span>');
				redirect( current_url() );				
			
			}

		}			
		
		// load widget template
		$data['spam_timer'] = date("U"); // reset on page load to prevent abuse
		$widget = $this->load->view($site.'/widgets/contact_us', $data, TRUE);
		
		return $widget;
	}	

//Newsletter widgets 

function newsletter_unsubscribe ($site = "") {
	$data = ""; // empty, we will compile this below

	$site = $this->config->item('template'); // config
	//get uri params
	$email = urldecode($this->uri->segment(2,0)); //return a zero if no email in the uri 
	$token = urldecode($this->uri->segment(3,0)); // if token = 0 then nope

//user enters via a url in their newsletter. 
//It takes them to this cms page "newsletter-unsubscribe" with widget code [newsletter_unsubscribe, 1] 

	// load form helper
	$this->load->helper('form');
	$this->load->library('form_validation');
	
	// form validation rules
	
	$this->form_validation->set_rules('newsletter_email', 'Email Address', 'required|valid_email');		

	if ($this->form_validation->run() === FALSE)
		{	
			// highlight any errors
			$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
		}
	else 
		{
			// alls good so far,
			// run user post through spam checker so we don't 
			// send admin junk mail or add junk mail to database
			
			// load spam helper
			$this->load->helper('spam_helper');	
		

			// check for spam in Email field
			$email = $this->input->post('newsletter_email');
			if(spam_filter($email)) {
				$data['spam'] = "Spam found in Email field";		
			}

			// has form been filled in too fast? ( min 15 secs to prevent bot abuse )
			$spam_timer = $this->input->post('spam_timer');
			if( ( date("U") - $spam_timer ) < 1 ) {
				$data['spam'] = "You are submitting the form too quickly! Please wait 15 seconds.";		
			}				
			
			$reason = $this->input->post('unsubscription-reason');
			//echo $reason;
			
			// not spam? 
			if(empty($data['spam'])) {
			
			//validate email
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$data["mail"]= $email." is now unsubscribed from the newsletter on ".$site;
			
			// should find subscribed users from the correct site. 
			$query = $this->db->query('SELECT id, user_id, user_email, active  
									FROM newsletter_subscriptions
									WHERE user_email ="'.$email.'" and site = "'.$site.'"');
			//todo in future: check if a user has clicked subscribe, then unsubscribed before confirming.
			$result = $query->result_array();
			$active = $result[0]['active'];
			$userid = $result[0]['user_id'];
			$newsid = $result[0]['id'];

			// unsubscribe only if they are previously subscribed and have an active subscription
			if ((!empty($result))  && ($active==1)) {
					$data['dupes'] = "You are now unsubscribed from ".$site."!";				
				//if it's a site user, maintain record:
				if ($userid != 0) {
					// update the subscription record with confirmed data
					if (isset($reason)){ 
						$this->db->set('unsubscription_reason',$reason); 
					};
					$this->db->set('active',0);
					$this->db->set('unsubscription_date',date("Y-m-d H:i:s"));
					$this->db->where('user_email', $email);  // selecting based on email. 
					$this->db->update('newsletter_subscriptions');
				} else {
				//remove record for gdpr compliance if they aren't a site user.
					$this->db->where('id',$newsid);
					$this->db->delete('newsletter_subscriptions');
				}
				
			} else { 
					if (empty($result)) {
					$data['dupes'] = "Unknown email address"; 
				}   elseif ($active==0) { //results are there but user isn't active
					$data['dupes'] = $email." is already unsubscribed on ".$site;
					//TODO in future: pretty print site names based on site code
				}
			}
				
		} else {
					if ($email == 0){
						$data['dupes'] = "No valid email address found";
					} else {
						$data['dupes'] = $email." is not a valid email address";
						echo $email;
					}
		}
			}

		}			
		
		// load widget template
		$data['spam_timer'] = date("U"); // reset on page load to prevent abuse
		$widget = $this->load->view($site.'/widgets/newsletter_unsubscribe', $data, TRUE);
		
		return $widget;
	}	

	
function newsletter_confirm ( $site = "" ) {
		$data = ""; // empty, we will compile this below

		$site = $this->config->item('template'); // config
		//get uri params
		$email = urldecode($this->uri->segment(2,0)); //return a zero if no email in the uri 
		$token = urldecode($this->uri->segment(3,0)); // if token = 0 then nope
				//check token is expired and show message: this means someone with knowledge of this code has crafted a url!
				if (substr($token,-7)=="expired"){
					$data['dupes'] = "Token expired";
				}


		//validate email
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$data["mail"]= $email." is now subscribed to the newsletter on ".$site;
			//add to db if not already there

			// should now find this inactive + token. 
			$query = $this->db->query('SELECT user_email  
									FROM newsletter_subscriptions
									WHERE user_email ="'.$email.'"
									AND token = "'.$token.'"
									AND site = "'.$site.'"'); //must be same site
			$result = $query->result_array();	
			
			// add the info only if they are not previously subscribed and have a valid token
			if ((!empty($result))  && (isset($token))) {
				// user id: find and add existing user id for that email address. 
				// if they aren't registered, user id is 0
				$query = $this->db->query('SELECT id
										FROM users 
										WHERE email = "'.$email.'"
										AND site = "'.$site.'"');
				$isuser = $query->result_array();
				if (empty($isuser)){
					$userid = 0;
				} else {
					$userid = $isuser[0]['id'];
				}
			
			// update the subscription record with confirmed data
				$this->db->set('user_id',$userid); 
				$this->db->set('active',1);
				$this->db->set('user_confirmed_date',date("Y-m-d H:i:s"));
				$this->db->set('user_email',$email);
				$this->db->set('token',$token."expired"); // expire current token just in case
				$this->db->where('token', $token);  // selecting based on token as we are sure these are unique.
				$this->db->update('newsletter_subscriptions');	
					
				
			} else { 
			// to do: check user isn't already subscribed. could have been manually subscribed somehow between subscription request and confirmation..
					if (empty($result) && isset($email)) {
						
					$data['dupes'] = "Unknown email address"; 
				} else {
					$data['dupes'] = $email." is already subscribed";
				}
			}
				
		} else {
					if ($email == 0){
						$data['dupes'] = "No valid email address found";
					} else {
						$data['dupes'] = $email." is not a valid email address";
						echo $email;
					}
		}

		$widget = $this->load->view($site.'/widgets/newsletter_confirm', $data, TRUE);
		
		return $widget;	
	
}	


function newsletter_subscribe ( $site = "" ) {
		
		$site = $this->config->item('template'); // config

		$data = ""; // empty, we will compile this below

		// load form helper
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// form validation rules
	
		$this->form_validation->set_rules('newsletter_email', 'Email Address', 'required|valid_email');		

		if ($this->form_validation->run() === FALSE)
		{	
			// highlite any errors
			$this->form_validation->set_error_delimiters('<p class="checkout_required_field">', '</p>');
		}
		else 
		{
			// alls good so far,
			// run user post through spam checker so we don't 
			// send admin junk mail or add junk mail to database
			
			// load spam helper
			$this->load->helper('spam_helper');	
		

			// check for spam in Email field
			$email = $this->input->post('newsletter_email');
			if(spam_filter($email)) {
				$data['spam'] = "Spam found in Email field";		
			}


			// has form been filled in too fast? ( min 15 secs to prevent bot abuse )
			$spam_timer = $this->input->post('spam_timer');
			if( ( date("U") - $spam_timer ) < 15 ) {
				$data['spam'] = "You are submitting the form too quickly! Please wait 15 seconds.";		
			}				
			
			// not spam? ok, now we can send a confirmation mail, etc
			if(empty($data['spam'])) {
				

				// check for dupes
				$query = $this->db->query('SELECT user_email  
											FROM newsletter_subscriptions
											WHERE user_email ="'.$email.'"');
				$result = $query->result_array();	
				if (empty($result)) {
					//get token helper as php uniqid function can generate dupes (see http://php.net/uniqid)
					$this->load->helper('token_helper');	
					$token = realuniqid();
					
					// send mail
					$this->load->library('email');
					$config['mailtype'] = 'html';
					$this->email->initialize($config);
					$this->email->from('noreply@unineedgroup.com', "New subscriber");
					$this->email->to($email);
					// $this->email->cc('another@another-example.com');
					// $this->email->bcc('them@their-example.com');
					$this->email->subject('Newsletter Subscription');
					$data = array(
										'email'=> $email,
										'token'=> $token,
									);		
					 
					$body = $this->load->view($site.'/emails/newsletter_subscribe',$data,TRUE);
					$this->email->message($body);
					$sent = $this->email->send();	
				
				// lets look at the email data thats being sent, 
				/*
				echo ($sent) ? $sent : "Email not sent";
				 echo "<hr>";
				 echo "<pre>";
				 print_r($this->email);
				 echo "</pre>";
				 die();
				*/
				
			//create initial record
			$insert = array(
				'site' => $site,
				'user_id' => 0,
				'user_email' => $email,
				'user_subscribed_date' => date("Y-m-d H:i:s"),
				'token'	=> $token,  
				'active' => 0
			);
			$this->db->insert('newsletter_subscriptions', $insert);	
					
				
				// reload page and let user know the message has been sent
					$this->session->set_flashdata('msg', '<span style="color:green;">Thanks for subscribing to our newsletter! Please check your email.</span>');
					redirect( current_url() );				
				} else {
					$data['dupes'] = "You are already subscribed!";
				}
				
			}

		}			
		
		// load widget template
		$data['spam_timer'] = date("U"); // reset on page load to prevent abuse
		$widget = $this->load->view($site.'/widgets/newsletter_subscribe', $data, TRUE);
		
		return $widget;
	}	
}


	?>	


