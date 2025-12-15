<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Dashboard Controller
 * Main dashboard after login
 */
class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper(array('url', 'auth'));

		// Require login
		require_login();
	}

	/**
	 * Main dashboard
	 */
	public function index()
	{
		// Get current user info
		$current_user = get_current_user();

		// Load appropriate dashboard based on user level
		switch ($current_user['user_level']) {
			case 'admin':
				$this->admin_dashboard();
				break;
			case 'panitera_pengganti':
				$this->panitera_dashboard();
				break;
			case 'staff':
				$this->staff_dashboard();
				break;
			default:
				redirect('auth/logout');
		}
	}

	/**
	 * Admin dashboard method for /admin/dashboard route
	 */
	public function admin()
	{
		// Check admin permission
		require_min_level('admin');

		// Load admin dashboard
		$this->admin_dashboard();
	}

	/**
	 * Admin Dashboard
	 */
	private function admin_dashboard()
	{
		// Load models needed for admin dashboard
		$this->load->model('Notelen_model', 'notelen');
		$this->load->model('User_model', 'user');

		try {
			$dashboard_stats = $this->notelen->get_dashboard_stats();
			$user_stats = $this->user->get_user_stats();
		} catch (Exception $e) {
			$dashboard_stats = $this->get_default_stats();
			$user_stats = array();
		}

		$data = array(
			'title' => 'Admin Dashboard - Sistem Notelen BHT',
			'current_user' => get_current_user(),
			'dashboard_stats' => $dashboard_stats,
			'user_stats' => $user_stats,
			'page' => 'admin_dashboard'
		);

		$this->load->view('dashboard/admin_dashboard', $data);
	}

	/**
	 * Panitera Pengganti Dashboard
	 */
	private function panitera_dashboard()
	{
		$this->load->model('Notelen_model', 'notelen');

		try {
			$dashboard_stats = $this->notelen->get_dashboard_stats();
		} catch (Exception $e) {
			$dashboard_stats = $this->get_default_stats();
		}

		$data = array(
			'title' => 'Dashboard Panitera - Sistem Notelen BHT',
			'current_user' => get_current_user(),
			'dashboard_stats' => $dashboard_stats,
			'page' => 'panitera_dashboard'
		);

		$this->load->view('dashboard/panitera_dashboard', $data);
	}

	/**
	 * Staff Dashboard
	 */
	private function staff_dashboard()
	{
		$this->load->model('Notelen_model', 'notelen');

		try {
			$dashboard_stats = $this->notelen->get_dashboard_stats();
		} catch (Exception $e) {
			$dashboard_stats = $this->get_default_stats();
		}

		$data = array(
			'title' => 'Dashboard Staff - Sistem Notelen BHT',
			'current_user' => get_current_user(),
			'dashboard_stats' => $dashboard_stats,
			'page' => 'staff_dashboard'
		);

		$this->load->view('dashboard/staff_dashboard', $data);
	}

	/**
	 * Get default stats when database is not available
	 */
	private function get_default_stats()
	{
		return (object) array(
			'total_berkas_masuk' => 0,
			'berkas_panitera_pengganti' => 0,
			'berkas_alih_media' => 0,
			'berkas_belum_ada_pbt' => 0,
			'berkas_menunggu_bht' => 0,
			'berkas_selesai_arsip' => 0,
			'berkas_hari_ini' => 0,
			'berkas_minggu_ini' => 0,
			'total_berkas_pbt' => 0,
			'pbt_belum_proses' => 0,
			'pbt_menunggu_bht' => 0,
			'pbt_selesai' => 0
		);
	}

	/**
	 * AJAX endpoint for dashboard data refresh
	 */
	public function ajax_refresh_stats()
	{
		header('Content-Type: application/json');

		// Check authentication
		$auth_check = check_ajax_auth();
		if ($auth_check) {
			echo json_encode($auth_check);
			return;
		}

		$this->load->model('Notelen_model', 'notelen');

		try {
			$dashboard_stats = $this->notelen->get_dashboard_stats();

			echo json_encode(array(
				'success' => true,
				'data' => $dashboard_stats
			));
		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Gagal mengambil data statistik'
			));
		}
	}

	/**
	 * Profile page
	 */
	public function profile()
	{
		$data = array(
			'title' => 'Profil Pengguna - Sistem Notelen BHT',
			'current_user' => get_current_user(),
			'page' => 'profile'
		);

		$this->load->view('dashboard/profile', $data);
	}

	/**
	 * Update profile via AJAX
	 */
	public function ajax_update_profile()
	{
		header('Content-Type: application/json');

		// Check authentication
		$auth_check = check_ajax_auth();
		if ($auth_check) {
			echo json_encode($auth_check);
			return;
		}

		$this->load->model('User_model', 'user');

		$user_id = $this->session->userdata('user_id');
		$data = array(
			'full_name' => $this->input->post('full_name'),
			'email' => $this->input->post('email')
		);

		// Add password if provided
		$password = $this->input->post('password');
		if (!empty($password)) {
			$data['password'] = $password;
		}

		$result = $this->user->update_user($user_id, $data);

		if ($result) {
			// Update session data
			$this->session->set_userdata('full_name', $data['full_name']);
			if (!empty($data['email'])) {
				$this->session->set_userdata('email', $data['email']);
			}

			echo json_encode(array(
				'success' => true,
				'message' => 'Profil berhasil diperbarui'
			));
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Gagal memperbarui profil'
			));
		}
	}
}
