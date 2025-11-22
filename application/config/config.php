<?php
defined('BASEPATH') or exit('No direct script access allowed');

// =============================================
// ENVIRONMENT DETECTION & BASE URL
// =============================================
if (isset($_SERVER['HTTP_HOST'])) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    
    // Detect environment and set base URL accordingly
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        // Local development
        $config['base_url'] = $protocol . $host . '/Halimah_BHT/';
    } else {
        // Production server - adjust path as needed
        $config['base_url'] = $protocol . $host . '/'; // or '/subfolder/' if in subfolder
    }
} else {
    // CLI or fallback
    $config['base_url'] = 'http://localhost/Halimah_BHT/';
}

$config['index_page'] = '';
$config['uri_protocol'] = 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language'] = 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = FALSE;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted in your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-,';

$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['allow_get_array'] = TRUE;

// =============================================
// LOGGING CONFIGURATION
// =============================================
$config['log_threshold'] = 1; // 0=off, 1=error, 2=debug, 3=info, 4=all
$config['log_path'] = ''; // Leave empty to use default APPPATH . 'logs/'
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';

// =============================================
// OTHER CONFIGURATIONS
// =============================================
$config['error_views_path'] = '';
$config['cache_path'] = ''; // Leave empty to use default APPPATH . 'cache/'
$config['cache_query_string'] = FALSE;

// =============================================
// SECURITY CONFIGURATION
// =============================================
$config['encryption_key'] = 'notelen_bht_secure_key_2025';

// =============================================
// SESSION CONFIGURATION
// =============================================
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'notelen_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL; // Use default system temp directory
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

// =============================================
// COOKIE CONFIGURATION
// =============================================
$config['cookie_prefix'] = 'notelen_';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE; // Set to TRUE if using HTTPS in production
$config['cookie_httponly'] = TRUE;

// =============================================
// ADDITIONAL SECURITY
// =============================================
$config['csrf_protection'] = FALSE; // Disable temporarily for testing
$config['csrf_token_name'] = 'csrf_notelen_token';
$config['csrf_cookie_name'] = 'csrf_notelen_cookie';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

// =============================================
// COMPRESSION & OPTIMIZATION
// =============================================
$config['compress_output'] = FALSE; // Enable if server supports it
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';

// =============================================
// ENVIRONMENT-SPECIFIC SETTINGS
// =============================================
if (ENVIRONMENT === 'development') {
    $config['log_threshold'] = 4; // Log everything in development
    $config['enable_hooks'] = TRUE;
} else {
    $config['log_threshold'] = 1; // Only errors in production
    $config['enable_hooks'] = FALSE;
}
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE;
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();
$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';
