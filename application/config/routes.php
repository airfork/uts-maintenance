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
$route['default_controller'] = 'buses';
$route['buses/(:num)']['GET'] = 'buses/view/$1';
$route['buses/(:num)']['POST'] = 'buses/issue/$1';
$route['buses/(:num)/issues']['GET'] = 'buses/issue_list/$1';
$route['buses/reset']['POST'] = 'buses/reset';
$route['buses/add']['POST'] = 'buses/add';
$route['buses/completed']['GET'] = 'buses/completed';
$route['buses/delete']['POST'] = 'buses/delete';
$route['issues/master']['GET'] = 'issues/master';
$route['dashboard']['GET'] = 'user/dashboard';
$route['resolve/(:num)']['PATCH'] = 'buses/resolve_issue/$1';
$route['login']['GET'] = 'user/index';
$route['login']['POST'] = 'user/login';
$route['logout']['GET'] = 'user/logout';
$route['register']['GET'] = 'user/register';
$route['register']['POST'] = 'user/create';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
