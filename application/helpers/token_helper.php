<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('realuniqid'))
{
    function realuniqid ($length=13)
    {
	// randomer than uniqid() for use with db when it may happen within the millisecond, creating identical tokens.
    // uniqid gives 13 chars.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($length / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $length);

	}	
}