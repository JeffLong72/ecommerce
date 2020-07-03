<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model {
	
	public function add_new_review ()
	{
		// get site id
		$site = $this->config->item('template'); // config	
	
		// get review data
		$data['review_sku'] =  ( ! empty( $_POST['review_sku'] ) ) ? $_POST['review_sku'] : "";				
		$data['review_rating'] =  ( ! empty( $_POST['review_rating'] ) ) ? $_POST['review_rating'] : "";
		$data['review_name'] =  ( ! empty( $_POST['review_name'] ) ) ? $_POST['review_name'] : "";
		$data['review_headline'] =  ( ! empty( $_POST['review_headline'] ) ) ? $_POST['review_headline'] : "";		
		$data['review_comments'] =  ( ! empty( $_POST['review_comments'] ) ) ? $_POST['review_comments'] : "";
		$data['spam_timer'] =  ( ! empty( $_POST['spam_timer'] ) ) ? $_POST['spam_timer'] : "";	
		
		// make sure all field data is provided
		if( empty($data['review_rating']) || empty($data['review_name']) || empty($data['review_headline']) || empty($data['review_comments']) || empty($data['spam_timer']) ) {
			echo "Please enter all required fields";
			return;
		}
		
		// make sure the form hasnt been submitted too fast ( possible bot )
		// ( lets set the time to 15 seconds min )
		if( ( date("U") - $data['spam_timer'] )  < 15 ) {
			echo "You are submitting the form too fast! Please slow down.";
			return;
		}

		// load spam helper
		$this->load->helper('spam_helper');
		
		// check for spam in Name field
		if(spam_filter($data['review_name'])) {
			echo "Spam found in Name field";
			return;			
		}
		
		// check for spam in Headline field		
		if(spam_filter($data['review_headline'])) {
			echo "Spam found in Headline field";
			return;			
		}
		
		// check for spam in Comments field		
		if(spam_filter($data['review_comments'])) {
			echo "Spam found in Comments field";
			return;			
		}	

		// if we have got this far then the form data is good to add to database
		// ( mark the review as pending so it needs admin approval before its displayed on the website )
		$ins = array(
				'site' => $site,
				'sku' => $data['review_sku'],
				'rating' => $data['review_rating'],
				'name' => $data['review_name'],
				'headline' => $data['review_headline'],
				'comments' => $data['review_comments'],
				'date_submitted' => date("Y-m-d H:i:s"),
				'approved' => 0
		);

		$this->db->insert('products_reviews', $ins);

        // log admin action
        $this->load->model('global_model');
        $this->global_model->admin_action('Review_model', 'add_new_review', 'insert',  print_r($ins, TRUE));

		// return success
		echo "SUCCESS";
	}


    public function update_reviews($data = "")
    {



        $site = $this->config->item('template'); // config
        $site = (!empty($_SESSION['admin']['site'])) ? $_SESSION['admin']['site'] : $site; // session

        $this->load->helper('url');

        // update reviews
        $active = (!empty($data['reviews']['active'])) ? 1 : 0;
        $update_reviews_rates = array(
            'site' => $site,
            'name' => $data['reviews']['name'],
            'comments' => $data['reviews']['comments'],
            'admin_id' => $_SESSION['admin']['id'],
            'date_approved' => date("Y-m-d H:i:s"),
            'approved' => $data['reviews']['approved'],
            'active' => $active
        );
        $this->db->where('id', $data['reviews']['id']);
        $result = $this->db->update('products_reviews', $update_reviews_rates);

        $this->global_model->admin_action('Review_model', 'update_reviews', 'update',  print_r($update_reviews_rates, TRUE));

        return $result;
    }
}
