<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ========== CUSTOM ROUTES UNTUK SISTEM BHT ==========

// Route untuk BHT Reminder System
$route['bht-reminder'] = 'bht_reminder/index';
$route['bht-reminder/(:any)'] = 'bht_reminder/$1';

// Route untuk AJAX calls (lebih SEO friendly)
$route['api/bht/reminders'] = 'bht_reminder/get_filtered_reminders';
$route['api/bht/report'] = 'bht_reminder/get_monthly_report';
$route['api/bht/charts'] = 'bht_reminder/get_chart_data';
$route['api/bht/mark-handled'] = 'bht_reminder/mark_handled';

// Route untuk Export
$route['bht-reminder/export/(:any)'] = 'bht_reminder/export_report/$1';

// Route untuk BHT Putus 4 System (Enhanced Date Sorting)
$route['bht-putus-4'] = 'bht_putus_4/index';
$route['bht-putus-4/(:any)'] = 'bht_putus_4/$1';
$route['api/bht-putus-4/data'] = 'bht_putus_4/get_data_ajax';
$route['api/bht-putus-4/stats'] = 'bht_putus_4/get_quick_stats';
$route['export/bht-putus-4/excel'] = 'bht_putus_4/export_excel';

// Route untuk Testing (hanya untuk development)
$route['test/bht'] = 'bht_reminder_test/index';
$route['test/bht/template'] = 'bht_reminder_test/test_with_template';
