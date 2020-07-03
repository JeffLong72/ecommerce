<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('spam_filter'))
{
    function spam_filter ($user_text = '')
    {
		$result = FALSE;
		
		$spam_filter_text = array(
												"viagra", 
												"multiple words",
												"@spam.com"
												);
		
		if(match($spam_filter_text, $user_text)){
		   $result = TRUE;
		}
		
		return $result;
    }   
		
	function match($needles, $haystack)
	{
		foreach($needles as $needle){
			if (strpos($haystack, $needle) !== false) {
				return true;
			}
		}
		return false;
	}	
}