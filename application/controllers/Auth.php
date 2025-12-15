<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller untuk sistem authentication
 * 
 * Fungsi utama:
 * - Login/Logout
 * - Session management
 * - Role-based access control
 * - User management (admin only)
 */
class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('User_model', 'user');
		$this->load->helper(array('url', 'form', 'security'));
	}

    // ===============================================
    // AUTHENTICATION FUNCTIONS
    // ===============================================

	/**
	 * Login page
	 */
	public function index()
	{
		// Redirect if already logged in
		if ($this->is_logged_in()) {
			redirect('dashboard');
		}

		$this->login();
	}

	/**
	 * Login form and process
	 */
	public function login()
	{
		// Redirect if already logged in
		if ($this->is_logged_in()) {
			redirect('dashboard');
		}

		if ($this->input->method() === 'post') {
			$this->process_login();
		} else {
			$this->show_login_form();
		}
	}

	/**
	 * Show login form
	 */
	private function show_login_form()
	{
		$data = array(
			'title' => 'Login - Sistem Notelen BHT',
			'error_message' => $this->session->flashdata('error_message'),
			'success_message' => $this->session->flashdata('success_message')
		);

		$this->load->view('auth/login', $data);
	}

	/**
	 * Process login attempt
	 */
	private function process_login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$remember_me = $this->input->post('remember_me');

		// Validation
		if (empty($username) || empty($password)) {
			$this->session->set_flashdata('error_message', 'Username dan password harus diisi');
			redirect('auth/login');
		}

		// Try to authenticate
		$user = $this->user->validate_login($username, $password);

		if ($user) {
			// Set session data
			$session_data = array(
				'user_id' => $user['id'],
				'username' => $user['username'],
				'full_name' => $user['full_name'],
				'user_level' => $user['user_level'],
				'email' => $user['email'],
				'logged_in' => TRUE,
				'login_time' => date('Y-m-d H:i:s')
			);

			$this->session->set_userdata($session_data);

			// Remember me functionality
			if ($remember_me) {
				$this->set_remember_me_cookie($user['id']);
			}

			// Log successful login
			log_message('info', 'User login successful: ' . $username . ' (' . $user['user_level'] . ')');

			// Redirect based on user level
			$redirect_url = $this->get_redirect_url_by_level($user['user_level']);
			redirect($redirect_url);
		} else {
			// Log failed login attempt
			log_message('warning', 'Failed login attempt for username: ' . $username . ' from IP: ' . $this->input->ip_address());

			$this->session->set_flashdata('error_message', 'Username atau password salah');
			redirect('auth/login');
		}
	}

	/**
	 * Get redirect URL based on user level
	 */
	private function get_redirect_url_by_level($user_level)
	{
		switch ($user_level) {
			case 'admin':
				return 'admin/dashboard';
			case 'panitera_pengganti':
				return 'notelen/index';
			case 'staff':
				return 'notelen/index';
			default:
				return 'dashboard';
		}
	}

	/**
	 * Set remember me cookie
	 */
	private function set_remember_me_cookie($user_id)
	{
		$token = bin2hex(random_bytes(32));
		$expiry = time() + (30 * 24 * 60 * 60); // 30 days

		// Store token in database (optional - implement if needed)
		// $this->user->save_remember_token($user_id, $token, $expiry);

		$cookie = array(
			'name' => 'remember_token',
			'value' => $user_id . ':' . $token,
			'expire' => $expiry,
			'secure' => FALSE, // Set to TRUE if using HTTPS
			'httponly' => TRUE
		);

		$this->input->set_cookie($cookie);
	}

	/**
	 * Logout
	 */
	public function logout()
	{
		// Log logout
		log_message('info', 'User logout: ' . $this->session->userdata('username'));

		// Clear remember me cookie
		delete_cookie('remember_token');

		// Clear session
		$this->session->sess_destroy();

		$this->session->set_flashdata('success_message', 'Anda telah berhasil logout');
		redirect('auth/login');
	}

    // ===============================================
    // AJAX LOGIN (for modal login)
    // ===============================================

	/**
	 * AJAX login process
	 */
	public function ajax_login()
	{
		header('Content-Type: application/json');

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if (empty($username) || empty($password)) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Username dan password harus diisi'
			));
			return;
		}

		$user = $this->user->validate_login($username, $password);

		if ($user) {
			// Set session data
			$session_data = array(
				'user_id' => $user['id'],
				'username' => $user['username'],
				'full_name' => $user['full_name'],
				'user_level' => $user['user_level'],
				'email' => $user['email'],
				'logged_in' => TRUE,
				'login_time' => date('Y-m-d H:i:s')
			);

			$this->session->set_userdata($session_data);

			echo json_encode(array(
				'success' => true,
				'message' => 'Login berhasil',
				'redirect' => $this->get_redirect_url_by_level($user['user_level'])
			));
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Username atau password salah'
			));
		}
	}

    // ===============================================
    // USER MANAGEMENT (ADMIN ONLY)
    // ===============================================

	/**
	 * User management dashboard
	 */
	public function users()
	{
		// Check admin access
		if (!$this->is_logged_in() || !$this->is_admin()) {
			show_404();
		}

		// Get filters
		$filters = array(
			'user_level' => $this->input->get('user_level'),
			'is_active' => $this->input->get('is_active'),
			'search' => $this->input->get('search')
		);

		// Pagination
		$page = $this->input->get('page') ?: 1;
		$limit = 20;
		$offset = ($page - 1) * $limit;

		// Get data
		$users = $this->user->get_all_users($limit, $offset, $filters);
		$total_users = $this->user->count_users($filters);
		$user_stats = $this->user->get_user_stats();

		$data = array(
			'title' => 'Manajemen User',
			'users' => $users,
			'total_users' => $total_users,
			'current_page' => $page,
			'total_pages' => ceil($total_users / $limit),
			'filters' => $filters,
			'user_stats' => $user_stats,
			'current_user' => $this->get_current_user()
		);

		$this->load->view('auth/users', $data);
	}

	/**
	 * Add new user (AJAX)
	 */
	public function ajax_add_user()
	{
		header('Content-Type: application/json');

		// Check admin access
		if (!$this->is_logged_in() || !$this->is_admin()) {
			echo json_encode(array('success' => false, 'message' => 'Akses ditolak'));
			return;
		}

		$data = array(
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password'),
			'email' => $this->input->post('email'),
			'full_name' => $this->input->post('full_name'),
			'user_level' => $this->input->post('user_level'),
			'is_active' => $this->input->post('is_active') ? 1 : 0
		);

		// Validation
		if (empty($data['username']) || empty($data['password']) || empty($data['full_name'])) {
			echo json_encode(array('success' => false, 'message' => 'Data tidak lengkap'));
			return;
		}

		$user_id = $this->user->create_user($data);

		if ($user_id) {
			echo json_encode(array('success' => true, 'message' => 'User berhasil ditambahkan'));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Gagal menambahkan user. Username atau email sudah digunakan.'));
		}
	}

	/**
	 * Edit user (AJAX)
	 */
	public function ajax_edit_user()
	{
		header('Content-Type: application/json');

		// Check admin access
		if (!$this->is_logged_in() || !$this->is_admin()) {
			echo json_encode(array('success' => false, 'message' => 'Akses ditolak'));
			return;
		}

		$user_id = $this->input->post('user_id');
		$data = array(
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'full_name' => $this->input->post('full_name'),
			'user_level' => $this->input->post('user_level'),
			'is_active' => $this->input->post('is_active') ? 1 : 0
		);

		// Add password if provided
		$password = $this->input->post('password');
		if (!empty($password)) {
			$data['password'] = $password;
		}

		$result = $this->user->update_user($user_id, $data);

		if ($result) {
			echo json_encode(array('success' => true, 'message' => 'User berhasil diupdate'));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Gagal mengupdate user'));
		}
	}

	/**
	 * Delete user (AJAX)
	 */
	public function ajax_delete_user()
	{
		header('Content-Type: application/json');

		// Check admin access
		if (!$this->is_logged_in() || !$this->is_admin()) {
			echo json_encode(array('success' => false, 'message' => 'Akses ditolak'));
			return;
		}

		$user_id = $this->input->post('user_id');
		$hard_delete = $this->input->post('hard_delete');

		if ($hard_delete) {
			$result = $this->user->hard_delete_user($user_id);
		} else {
			$result = $this->user->delete_user($user_id);
		}

		if ($result) {
			echo json_encode(array('success' => true, 'message' => 'User berhasil dihapus'));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Gagal menghapus user'));
		}
	}

    // ===============================================
    // HELPER FUNCTIONS
    // ===============================================

	/**
	 * Check if user is logged in
	 */
	public function is_logged_in()
	{
		return $this->session->userdata('logged_in') === TRUE;
	}

	/**
	 * Check if user is admin
	 */
	public function is_admin()
	{
		return $this->session->userdata('user_level') === 'admin';
	}

	/**
	 * Check if user has specific level
	 */
	public function has_user_level($required_level)
	{
		$user_level = $this->session->userdata('user_level');

		if ($user_level === 'admin') {
			return true; // Admin has access to everything
		}

		return $user_level === $required_level;
	}

	/**
	 * Get current user data
	 */
	public function get_current_user()
	{
		if (!$this->is_logged_in()) {
			return null;
		}

		return array(
			'id' => $this->session->userdata('user_id'),
			'username' => $this->session->userdata('username'),
			'full_name' => $this->session->userdata('full_name'),
			'user_level' => $this->session->userdata('user_level'),
			'email' => $this->session->userdata('email'),
			'login_time' => $this->session->userdata('login_time')
		);
	}

	/**
	 * Require login - use this before accessing protected pages
	 */
	public function require_login()
	{
		if (!$this->is_logged_in()) {
			$this->session->set_flashdata('error_message', 'Silakan login terlebih dahulu');
			redirect('auth/login');
		}
	}

	/**
	 * Require admin access
	 */
	public function require_admin()
	{
		$this->require_login();

		if (!$this->is_admin()) {
			show_404();
		}
	}

	/**
	 * Check permission for specific action
	 */
	public function check_permission($action)
	{
		if (!$this->is_logged_in()) {
			return false;
		}

		$user_level = $this->session->userdata('user_level');
		return $this->user->has_permission($user_level, $action);
	}
}
