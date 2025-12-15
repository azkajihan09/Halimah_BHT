<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model untuk sistem authentication dan user management
 * 
 * Fungsi utama:
 * - Authentication login/logout
 * - User management (CRUD)
 * - Role-based access control
 * - Session management
 */
class User_model extends CI_Model
{
    private $notelen_db;

    public function __construct()
    {
        parent::__construct();
        // Menggunakan database notelen yang sama dengan berkas_masuk
        $this->notelen_db = $this->load->database('notelen_db', TRUE);
    }

    // ===============================================
    // AUTHENTICATION FUNCTIONS
    // ===============================================

    /**
     * Validasi login user
     * @param string $username
     * @param string $password
     * @return array|false
     */
    public function validate_login($username, $password)
    {
        // Get user by username
        $this->notelen_db->where('username', $username);
        $this->notelen_db->where('is_active', 1);
        $user = $this->notelen_db->get('users')->row();

        if ($user && password_verify($password, $user->password)) {
            // Update last login
            $this->update_last_login($user->id);

            // Return user data (tanpa password)
            return array(
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'full_name' => $user->full_name,
                'user_level' => $user->user_level,
                'last_login' => $user->last_login
            );
        }

        return false;
    }

    /**
     * Update last login time
     * @param int $user_id
     */
    private function update_last_login($user_id)
    {
        $this->notelen_db->where('id', $user_id);
        $this->notelen_db->update('users', array('last_login' => date('Y-m-d H:i:s')));
    }

    /**
     * Check if username exists
     * @param string $username
     * @param int $exclude_id (untuk update)
     * @return bool
     */
    public function username_exists($username, $exclude_id = null)
    {
        $this->notelen_db->where('username', $username);

        if ($exclude_id) {
            $this->notelen_db->where('id !=', $exclude_id);
        }

        $count = $this->notelen_db->count_all_results('users');
        return $count > 0;
    }

    /**
     * Check if email exists
     * @param string $email
     * @param int $exclude_id (untuk update)
     * @return bool
     */
    public function email_exists($email, $exclude_id = null)
    {
        if (empty($email)) return false;

        $this->notelen_db->where('email', $email);

        if ($exclude_id) {
            $this->notelen_db->where('id !=', $exclude_id);
        }

        $count = $this->notelen_db->count_all_results('users');
        return $count > 0;
    }

    // ===============================================
    // USER CRUD FUNCTIONS
    // ===============================================

    /**
     * Get user by ID
     * @param int $user_id
     * @return object|null
     */
    public function get_user_by_id($user_id)
    {
        $this->notelen_db->select('id, username, email, full_name, user_level, is_active, last_login, created_at, updated_at');
        $this->notelen_db->where('id', $user_id);
        return $this->notelen_db->get('users')->row();
    }

    /**
     * Get user by username
     * @param string $username
     * @return object|null
     */
    public function get_user_by_username($username)
    {
        $this->notelen_db->select('id, username, email, full_name, user_level, is_active, last_login, created_at, updated_at');
        $this->notelen_db->where('username', $username);
        return $this->notelen_db->get('users')->row();
    }

    /**
     * Get all users with pagination
     * @param int $limit
     * @param int $offset
     * @param array $filters
     * @return array
     */
    public function get_all_users($limit = null, $offset = 0, $filters = array())
    {
        $this->notelen_db->select('id, username, email, full_name, user_level, is_active, last_login, created_at, updated_at');

        // Apply filters
        if (!empty($filters['user_level'])) {
            $this->notelen_db->where('user_level', $filters['user_level']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $this->notelen_db->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->notelen_db->group_start();
            $this->notelen_db->like('username', $search);
            $this->notelen_db->or_like('full_name', $search);
            $this->notelen_db->or_like('email', $search);
            $this->notelen_db->group_end();
        }

        $this->notelen_db->order_by('created_at', 'DESC');

        if ($limit) {
            $this->notelen_db->limit($limit, $offset);
        }

        return $this->notelen_db->get('users')->result();
    }

    /**
     * Count total users with filters
     * @param array $filters
     * @return int
     */
    public function count_users($filters = array())
    {
        // Apply same filters as get_all_users
        if (!empty($filters['user_level'])) {
            $this->notelen_db->where('user_level', $filters['user_level']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $this->notelen_db->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->notelen_db->group_start();
            $this->notelen_db->like('username', $search);
            $this->notelen_db->or_like('full_name', $search);
            $this->notelen_db->or_like('email', $search);
            $this->notelen_db->group_end();
        }

        return $this->notelen_db->count_all_results('users');
    }

    /**
     * Insert new user
     * @param array $data
     * @return int|false
     */
    public function create_user($data)
    {
        // Validate required fields
        if (empty($data['username']) || empty($data['password']) || empty($data['full_name'])) {
            return false;
        }

        // Check if username or email already exists
        if ($this->username_exists($data['username'])) {
            return false;
        }

        if (!empty($data['email']) && $this->email_exists($data['email'])) {
            return false;
        }

        // Prepare data for insert
        $insert_data = array(
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'email' => !empty($data['email']) ? $data['email'] : null,
            'full_name' => $data['full_name'],
            'user_level' => !empty($data['user_level']) ? $data['user_level'] : 'staff',
            'is_active' => isset($data['is_active']) ? $data['is_active'] : 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->notelen_db->insert('users', $insert_data);
        return $this->notelen_db->insert_id();
    }

    /**
     * Update user data
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_user($user_id, $data)
    {
        // Check if user exists
        $user = $this->get_user_by_id($user_id);
        if (!$user) {
            return false;
        }

        // Check username conflicts
        if (!empty($data['username']) && $this->username_exists($data['username'], $user_id)) {
            return false;
        }

        // Check email conflicts
        if (!empty($data['email']) && $this->email_exists($data['email'], $user_id)) {
            return false;
        }

        // Prepare update data
        $update_data = array();

        if (!empty($data['username'])) {
            $update_data['username'] = $data['username'];
        }

        if (!empty($data['password'])) {
            $update_data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (isset($data['email'])) {
            $update_data['email'] = !empty($data['email']) ? $data['email'] : null;
        }

        if (!empty($data['full_name'])) {
            $update_data['full_name'] = $data['full_name'];
        }

        if (!empty($data['user_level'])) {
            $update_data['user_level'] = $data['user_level'];
        }

        if (isset($data['is_active'])) {
            $update_data['is_active'] = $data['is_active'];
        }

        $update_data['updated_at'] = date('Y-m-d H:i:s');

        $this->notelen_db->where('id', $user_id);
        return $this->notelen_db->update('users', $update_data);
    }

    /**
     * Delete user (soft delete by setting is_active = 0)
     * @param int $user_id
     * @return bool
     */
    public function delete_user($user_id)
    {
        // Don't allow deleting admin users
        $user = $this->get_user_by_id($user_id);
        if (!$user || $user->user_level === 'admin') {
            return false;
        }

        $this->notelen_db->where('id', $user_id);
        return $this->notelen_db->update('users', array(
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }

    /**
     * Hard delete user (permanent)
     * @param int $user_id
     * @return bool
     */
    public function hard_delete_user($user_id)
    {
        // Don't allow deleting admin users
        $user = $this->get_user_by_id($user_id);
        if (!$user || $user->user_level === 'admin') {
            return false;
        }

        $this->notelen_db->where('id', $user_id);
        return $this->notelen_db->delete('users');
    }

    // ===============================================
    // ROLE-BASED ACCESS FUNCTIONS
    // ===============================================

    /**
     * Check if user has permission for specific action
     * @param string $user_level
     * @param string $action
     * @return bool
     */
    public function has_permission($user_level, $action)
    {
        $permissions = array(
            'admin' => array('*'), // Admin has all permissions
            'panitera_pengganti' => array(
                'view_berkas',
                'edit_berkas',
                'view_pbt',
                'edit_pbt',
                'export_data',
                'view_reports',
                'manage_status'
            ),
            'staff' => array(
                'view_berkas',
                'add_berkas',
                'edit_berkas',
                'view_pbt',
                'add_pbt'
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

    /**
     * Get user statistics
     * @return array
     */
    public function get_user_stats()
    {
        $stats = array();

        // Total users
        $stats['total_users'] = $this->notelen_db->count_all('users');

        // Active users
        $this->notelen_db->where('is_active', 1);
        $stats['active_users'] = $this->notelen_db->count_all_results('users');

        // Users by level
        $levels = array('admin', 'panitera_pengganti', 'staff');
        foreach ($levels as $level) {
            $this->notelen_db->where('user_level', $level);
            $this->notelen_db->where('is_active', 1);
            $stats['users_' . $level] = $this->notelen_db->count_all_results('users');
        }

        // Recent logins (last 7 days)
        $this->notelen_db->where('last_login >=', date('Y-m-d H:i:s', strtotime('-7 days')));
        $stats['recent_logins'] = $this->notelen_db->count_all_results('users');

        return $stats;
    }
}
