<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| ATTRIBUTES
| -------------------------------------------------------------------
*/

// parent: attributes
$config['product_attributes']['all'] = array (
								"belt_size" => "Belt Size",
								"shoe_size" => "Shoe Size",	
								"bottle_size" => "Bottle Size",	
								"color" => "Color",								
);

// child: belt size
$config['product_attributes']['belt_size'] = array (
													"0" => "--select--",
													"1" => "60",
													"2" => "65",
													"3" => "70",
													"4" => "75",
													"5" => "80"
);

// child: shoe size
$config['product_attributes']['shoe_size'] = array (
													"0" => "--select--",
													"1" => "6",
													"2" => "7",
													"3" => "8",
													"4" => "9"
);

// child: bottle size
$config['product_attributes']['bottle_size'] = array (
													"0" => "--select--",
													"1" => "330ml",
													"2" => "440ml",
);

// child: color
$config['product_attributes']['color'] = array (
													"0" => "--select--",
													"1" => "Red",
													"2" => "Yellow",
													"3" => "Blue", 
													"4" => "Green",
													"5" => "Purple"													
);
