<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/



$route['default_controller'] = 'home/index';
$route['404_override'] = '404';
$route['translate_uri_dashes'] = FALSE;

$route['user/confirm_email_address/(:any)/(:any)'] = 'user/confirm_user_email/$2/$3';
$route['user/(:any)/(:any)/(:any)'] = 'user/$1/$2/$3';
$route['user/(:any)/(:any)'] = 'user/$1/$2';
$route['user/(:any)'] = 'user/$1';
$route['user'] = 'user/index';

$route['admin/sales/all/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'admin_sales/all/$1/$2/$3/$4/$5/$6/$7/$8';
$route['admin/sales/all/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'admin_sales/all/$1/$2/$3/$4/$5/$6/$7';
$route['admin/sales/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'admin_sales/$1/$2/$3/$4/$5';
$route['admin/sales/(:any)/(:any)/(:any)/(:any)'] = 'admin_sales/$1/$2/$3/$4';
$route['admin/sales/(:any)/(:any)'] = 'admin_sales/$1/$2';
$route['admin/sales/(:any)'] = 'admin_sales/$1';
$route['admin/sales/'] = 'admin_sales/index';

$route['admin/products/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'admin_products/$1/$2/$3/$4/$5';
$route['admin/products/(:any)/(:any)/(:any)/(:any)'] = 'admin_products/$1/$2/$3/$4';
$route['admin/products/(:any)/(:any)/(:any)'] = 'admin_products/$1/$2/$3';
$route['admin/products/(:any)/(:any)'] = 'admin_products/$1/$2';
$route['admin/products/(:any)'] = 'admin_products/$1';

$route['admin/ebay/(:any)/(:any)'] = 'admin_ebay/$1/$2';
$route['admin/ebay/(:any)'] = 'admin_ebay/$1';

$route['admin/amazon/(:any)/(:any)'] = 'admin_amazon/$1/$2';
$route['admin/amazon/(:any)'] = 'admin_amazon/$1';

$route['admin/blogs/(:any)/(:any)'] = 'admin_blog/$1/$2';
$route['admin/blogs/(:any)'] = 'admin_blog/$1';

$route['admin/cms/(:any)/(:any)'] = 'admin_cms/$1/$2';
$route['admin/cms/(:any)'] = 'admin_cms/$1';

$route['admin/import/(:any)'] = 'admin_import/$1';
$route['admin/import/'] = 'admin_import/index';

$route['admin/(:any)'] = 'admin/$1';
$route['admin'] = 'admin/index';

$route['ajax/(:any)/(:any)'] = 'ajax/$1/$2';
$route['ajax/(:any)'] = 'ajax/$1';

$route['blog/all'] = 'blog/all';
$route['blog/search/(:any)'] = 'blog/search/$1';
$route['blog/search'] = 'blog/search/$1';
$route['blog/all/(:any)'] = 'blog/all/$1';
$route['blog/(:any)'] = 'blog/view/$1';
$route['blog'] = 'blog';

$route['admin/vouchers/(:any)/(:any)'] = 'admin_vouchers/$1/$2';
$route['admin/vouchers/(:any)'] = 'admin_vouchers/$1';
$route['admin/vouchers/'] = 'admin_vouchers/index';

$route['admin/reviews/(:any)/(:any)'] = 'admin_reviews/$1/$2';
$route['admin/reviews/(:any)'] = 'admin_reviews/$1';
$route['admin/reviews/'] = 'admin_reviews/index';

$route['admin/users/(:any)/(:any)'] = 'admin_users/$1/$2';
$route['admin/users/(:any)'] = 'admin_users/$1';
$route['admin/users/'] = 'admin_users/index';

$route['admin/administrators/(:any)/(:any)'] = 'admin_administrators/$1/$2';
$route['admin/administrators/(:any)'] = 'admin_administrators/$1';
$route['admin/administrators/'] = 'admin_administrators/index';

$route['admin/currency/(:any)/(:any)'] = 'admin_currency/$1/$2';
$route['admin/currency/(:any)'] = 'admin_currency/$1';
$route['admin/currency/'] = 'admin_currency/index';

$route['admin/categories/(:any)'] = 'admin_categories/$1';
$route['admin/categories/'] = 'admin_categories/index';

$route['admin/menu/(:any)/(:any)'] = 'admin_menu/$1/$2';
$route['admin/menu/(:any)'] = 'admin_menu/$1';
$route['admin/menu/'] = 'admin_menu/index';

$route['admin/delivery/(:any)/(:any)'] = 'admin_delivery/$1/$2';
$route['admin/delivery/(:any)'] = 'admin_delivery/$1';
$route['admin/delivery/'] = 'admin_delivery/index';

$route['admin/payment/(:any)'] = 'admin_payment/$1';
$route['admin/payment/'] = 'admin_payment/index';

$route['admin/sitemap/(:any)'] = 'admin_sitemap/$1';
$route['admin/sitemap/'] = 'admin_sitemap/index';

$route['admin/reports/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'admin_reports/$1/$2/$3/$4/$5/$6';
$route['admin/reports/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'admin_reports/$1/$2/$3/$4/$5';

$route['admin/reports/(:any)/(:any)/(:any)/(:any)'] = 'admin_reports/$1/$2/$3/$4';
$route['admin/reports/(:any)/(:any)/(:any)'] = 'admin_reports/$1/$2/$3';
$route['admin/reports/(:any)/(:any)'] = 'admin_reports/$1/$2';
$route['admin/reports/(:any)'] = 'admin_reports/$1';
$route['admin/reports/'] = 'admin_reports/index';

$route['checkout/result/(:any)/(:any)'] = 'checkout/result/$1';
$route['checkout/result/(:any)'] = 'checkout/result/$1';
$route['checkout/result'] = 'checkout/result';
$route['checkout/payment'] = 'checkout/payment';
$route['checkout/details'] = 'checkout/details';
$route['checkout/basket/add/(:any)/redirect/(:any)'] = 'checkout/add_to_basket';
$route['checkout/basket/view/redirect/(:any)'] = 'checkout/view_basket';
$route['checkout/basket/(:any)'] = 'checkout/basket/$1';
$route['checkout/basket'] = 'checkout/basket';
$route['checkout'] = 'checkout/basket';

$route['search/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'search';
$route['search/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'search';
$route['search/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'search';
$route['search/(:any)/(:any)/(:any)/(:any)'] = 'search';
$route['search/(:any)/(:any)/(:any)'] = 'search';
$route['search/(:any)/(:any)'] = 'search';
$route['search/(:any)'] = 'search';
$route['search'] = 'search';

// leave the following at the end as it is a catchall.

$route['(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'cms/index/$1/$2/$3/$4/$5/$6';
$route['(:any)/(:any)/(:any)/(:any)/(:any)'] = 'cms/index/$1/$2/$3/$4/$5';
$route['(:any)/(:any)/(:any)/(:any)'] = 'cms/index/$1/$2/$3/$4';
$route['(:any)/(:any)/(:any)'] = 'cms/index/$1/$2/$3';
$route['(:any)/(:any)'] = 'cms/index/$1/$2';
$route['(:any)'] = 'cms/index/$1';


// $route['default_controller'] = 'home/index';
// $route['admin'] = 'admin/index';
// $route['404_override'] = '';
// $route['translate_uri_dashes'] = FALSE;
