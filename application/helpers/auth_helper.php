<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Helper untuk authentication dan authorization
 * 
 * File: application/helpers/auth_helper.php
 */

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     * @return bool
     */
    function is_logged_in()
    {
        $CI = &get_instance();
        return $CI->session->userdata('logged_in') === TRUE;
    }
}

if (!function_exists('require_login')) {
    /**
     * Require user to be logged in, redirect to login if not
     * @param string $redirect_url Optional redirect URL after login
     */
    function require_login($redirect_url = null)
    {
        if (!is_logged_in()) {
            $CI = &get_instance();

            if ($redirect_url) {
                $CI->session->set_userdata('redirect_after_login', $redirect_url);
            }

            $CI->session->set_flashdata('error_message', 'Silakan login terlebih dahulu untuk mengakses halaman ini');
            redirect('auth/login');
        }
    }
}

if (!function_exists('get_user_level')) {
    /**
     * Get current user level
     * @return string|null
     */
    function get_user_level()
    {
        if (!is_logged_in()) {
            return null;
        }

        $CI = &get_instance();
        return $CI->session->userdata('user_level');
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if current user is admin
     * @return bool
     */
    function is_admin()
    {
        return get_user_level() === 'admin';
    }
}

if (!function_exists('is_panitera')) {
    /**
     * Check if current user is panitera pengganti
     * @return bool
     */
    function is_panitera()
    {
        return get_user_level() === 'panitera_pengganti';
    }
}

if (!function_exists('is_staff')) {
    /**
     * Check if current user is staff
     * @return bool
     */
    function is_staff()
    {
        return get_user_level() === 'staff';
    }
}

if (!function_exists('require_admin')) {
    /**
     * Require admin access
     */
    function require_admin()
    {
        require_login();

        if (!is_admin()) {
            show_404();
        }
    }
}

if (!function_exists('require_min_level')) {
    /**
     * Require minimum user level
     * @param string $min_level Minimum required level (staff|panitera_pengganti|admin)
     */
    function require_min_level($min_level)
    {
        require_login();

        $current_level = get_user_level();
        $levels = array('staff' => 1, 'panitera_pengganti' => 2, 'admin' => 3);

        $current_rank = isset($levels[$current_level]) ? $levels[$current_level] : 0;
        $required_rank = isset($levels[$min_level]) ? $levels[$min_level] : 999;

        if ($current_rank < $required_rank) {
            show_404();
        }
    }
}

if (!function_exists('get_current_user')) {
    /**
     * Get current logged in user data
     * @return array|null
     */
    function get_current_user()
    {
        if (!is_logged_in()) {
            return null;
        }

        $CI = &get_instance();
        return array(
            'id' => $CI->session->userdata('user_id'),
            'username' => $CI->session->userdata('username'),
            'full_name' => $CI->session->userdata('full_name'),
            'user_level' => $CI->session->userdata('user_level'),
            'email' => $CI->session->userdata('email'),
            'login_time' => $CI->session->userdata('login_time')
        );
    }
}

if (!function_exists('get_username')) {
    /**
     * Get current username
     * @return string|null
     */
    function get_username()
    {
        if (!is_logged_in()) {
            return null;
        }

        $CI = &get_instance();
        return $CI->session->userdata('username');
    }
}

if (!function_exists('get_full_name')) {
    /**
     * Get current user full name
     * @return string|null
     */
    function get_full_name()
    {
        if (!is_logged_in()) {
            return null;
        }

        $CI = &get_instance();
        return $CI->session->userdata('full_name');
    }
}

if (!function_exists('has_permission')) {
    /**
     * Check if current user has specific permission
     * @param string $action Permission action to check
     * @return bool
     */
    function has_permission($action)
    {
        if (!is_logged_in()) {
            return false;
        }

        $user_level = get_user_level();

        // Permission mapping
        $permissions = array(
            'admin' => array('*'), // Admin has all permissions
            'panitera_pengganti' => array(
                'view_berkas',
                'edit_berkas',
                'view_pbt',
                'edit_pbt',
                'export_data',
                'view_reports',
                'manage_status',
                'view_dashboard',
                'approve_berkas'
            ),
            'staff' => array(
                'view_berkas',
                'add_berkas',
                'edit_berkas',
                'view_pbt',
                'add_pbt',
                'view_dashboard'
            )
        );

        if (!isset($permissions[$user_level])) {
            return false;
        }

        // Admin has all permissions
        if (in_array('*', $permissions[$user_level])) {
            return true;
        }

        return in_array($action, $permissions[$user_level]);
    }
}

if (!function_exists('require_permission')) {
    /**
     * Require specific permission
     * @param string $action Permission action required
     */
    function require_permission($action)
    {
        require_login();

        if (!has_permission($action)) {
            show_404();
        }
    }
}

if (!function_exists('check_ajax_auth')) {
    /**
     * Check authentication for AJAX requests
     * @param string $required_level Optional required level
     * @return array|null Returns error array if not authorized, null if authorized
     */
    function check_ajax_auth($required_level = null)
    {
        if (!is_logged_in()) {
            return array('success' => false, 'message' => 'Sesi login telah berakhir');
        }

        if ($required_level) {
            $current_level = get_user_level();
            $levels = array('staff' => 1, 'panitera_pengganti' => 2, 'admin' => 3);

            $current_rank = isset($levels[$current_level]) ? $levels[$current_level] : 0;
            $required_rank = isset($levels[$required_level]) ? $levels[$required_level] : 999;

            if ($current_rank < $required_rank) {
                return array('success' => false, 'message' => 'Akses ditolak');
            }
        }

        return null; // Authorized
    }
}

if (!function_exists('format_user_level')) {
    /**
     * Format user level for display
     * @param string $level User level
     * @return string Formatted level
     */
    function format_user_level($level)
    {
        $levels = array(
            'admin' => 'Administrator',
            'panitera_pengganti' => 'Panitera Pengganti',
            'staff' => 'Staff'
        );

        return isset($levels[$level]) ? $levels[$level] : ucfirst($level);
    }
}

if (!function_exists('get_user_badge')) {
    /**
     * Get Bootstrap badge for user level
     * @param string $level User level
     * @return string HTML badge
     */
    function get_user_badge($level)
    {
        $badges = array(
            'admin' => '<span class="badge badge-danger"><i class="fas fa-user-shield"></i> Admin</span>',
            'panitera_pengganti' => '<span class="badge badge-warning"><i class="fas fa-user-tie"></i> Panitera Pengganti</span>',
            'staff' => '<span class="badge badge-info"><i class="fas fa-user"></i> Staff</span>'
        );

        return isset($badges[$level]) ? $badges[$level] : '<span class="badge badge-secondary">' . ucfirst($level) . '</span>';
    }
}

if (!function_exists('can_access_admin_menu')) {
    /**
     * Check if user can access admin menu
     * @return bool
     */
    function can_access_admin_menu()
    {
        return is_admin();
    }
}

if (!function_exists('can_manage_users')) {
    /**
     * Check if user can manage other users
     * @return bool
     */
    function can_manage_users()
    {
        return is_admin();
    }
}

if (!function_exists('can_delete_berkas')) {
    /**
     * Check if user can delete berkas
     * @return bool
     */
    function can_delete_berkas()
    {
        return is_admin() || is_panitera();
    }
}

if (!function_exists('can_export_data')) {
    /**
     * Check if user can export data
     * @return bool
     */
    function can_export_data()
    {
        return is_admin() || is_panitera();
    }
}
