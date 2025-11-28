<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller untuk sistem notelen (berkas masuk perkara putus)
 * Sistem berkas masuk dengan autocomplete SIPP dan analytics
 */
class Notelen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Notelen_model', 'notelen');
		$this->load->helper(array('url', 'form'));
	}

    // ===============================================
    // MAIN BERKAS MASUK PAGE
    // ===============================================

	/**
	 * Dashboard berkas masuk notelen
	 */
	public function index()
	{
		try {
			// Get filters from session or request
			$filters = array(
				'status_berkas' => $this->input->get('status') ?: $this->session->userdata('notelen_filter_status') ?: '',
				'nomor_perkara' => $this->input->get('nomor') ?: $this->session->userdata('notelen_filter_nomor') ?: '',
				'tanggal_dari' => $this->input->get('dari') ?: $this->session->userdata('notelen_filter_dari') ?: '',
				'tanggal_sampai' => $this->input->get('sampai') ?: $this->session->userdata('notelen_filter_sampai') ?: ''
			);

			// Save filters to session
			$this->session->set_userdata('notelen_filter_status', $filters['status_berkas']);
			$this->session->set_userdata('notelen_filter_nomor', $filters['nomor_perkara']);
			$this->session->set_userdata('notelen_filter_dari', $filters['tanggal_dari']);
			$this->session->set_userdata('notelen_filter_sampai', $filters['tanggal_sampai']);

			// Pagination
			$page = $this->input->get('page') ?: 1;
			$limit = 20;
			$offset = ($page - 1) * $limit;

			// Get data
			$berkas_list = $this->notelen->get_berkas_masuk($limit, $offset, $filters);
			$total_berkas = $this->notelen->count_berkas_masuk($filters);

			// Dashboard stats
			$stats = $this->notelen->get_dashboard_stats();

			$data = array(
				'title' => 'Berkas Masuk Notelen',
				'page_title' => 'Berkas Masuk untuk Notelen',
				'berkas_list' => $berkas_list,
				'total_berkas' => $total_berkas,
				'current_page' => $page,
				'total_pages' => ceil($total_berkas / $limit),
				'limit' => $limit,
				'filters' => $filters,
				'stats' => $stats,
				'sidebar_active' => 'notelen',
				'submenu_active' => 'berkas_masuk'
			);

			$this->load->view('notelen/berkas_masuk_fixed', $data);
		} catch (Exception $e) {
			echo "<div style='padding: 20px; background: #f8f9fa; border: 1px solid #ddd;'>";
			echo "<h3>Error Loading Notelen System</h3>";
			echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
			echo "<p><strong>File:</strong> " . $e->getFile() . " (Line " . $e->getLine() . ")</p>";
			echo "<p>Please check database connection and model files.</p>";
			echo "</div>";
		}

		// Original code (commented out for debugging)
		/*
        // Get filters from session or request
        $filters = array(
            'status_berkas' => $this->input->get('status') ?: $this->session->userdata('notelen_filter_status') ?: '',
            'nomor_perkara' => $this->input->get('nomor') ?: $this->session->userdata('notelen_filter_nomor') ?: '',
            'tanggal_dari' => $this->input->get('dari') ?: $this->session->userdata('notelen_filter_dari') ?: '',
            'tanggal_sampai' => $this->input->get('sampai') ?: $this->session->userdata('notelen_filter_sampai') ?: ''
        );

        // Save filters to session
        $this->session->set_userdata('notelen_filter_status', $filters['status_berkas']);
        $this->session->set_userdata('notelen_filter_nomor', $filters['nomor_perkara']);
        $this->session->set_userdata('notelen_filter_dari', $filters['tanggal_dari']);
        $this->session->set_userdata('notelen_filter_sampai', $filters['tanggal_sampai']);

        // Pagination
        $page = $this->input->get('page') ?: 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get data
        $berkas_list = $this->notelen->get_berkas_masuk($limit, $offset, $filters);
        $total_berkas = $this->notelen->count_berkas_masuk($filters);
        
        // Dashboard stats
        $stats = $this->notelen->get_dashboard_stats();

        $data = array(
            'title' => 'Berkas Masuk Notelen',
            'page_title' => 'Berkas Masuk untuk Notelen',
            'berkas_list' => $berkas_list,
            'total_berkas' => $total_berkas,
            'current_page' => $page,
            'total_pages' => ceil($total_berkas / $limit),
            'limit' => $limit,
            'filters' => $filters,
            'stats' => $stats,
            'sidebar_active' => 'notelen',
            'submenu_active' => 'berkas_masuk'
        );

        $this->load->view('notelen/berkas_masuk', $data);
        */
	}

	/**
	 * Berkas Masuk 2 - Alternatif layout
	 */
	public function berkas_masuk2()
	{
		try {
			// Get filters from session or request
			$filters = array(
				'status_berkas' => $this->input->get('status') ?: $this->session->userdata('notelen_filter_status') ?: '',
				'nomor_perkara' => $this->input->get('nomor') ?: $this->session->userdata('notelen_filter_nomor') ?: '',
				'tanggal_dari' => $this->input->get('dari') ?: $this->session->userdata('notelen_filter_dari') ?: '',
				'tanggal_sampai' => $this->input->get('sampai') ?: $this->session->userdata('notelen_filter_sampai') ?: ''
			);

			// Save filters to session
			$this->session->set_userdata('notelen_filter_status', $filters['status_berkas']);
			$this->session->set_userdata('notelen_filter_nomor', $filters['nomor_perkara']);
			$this->session->set_userdata('notelen_filter_dari', $filters['tanggal_dari']);
			$this->session->set_userdata('notelen_filter_sampai', $filters['tanggal_sampai']);

			// Pagination
			$page = $this->input->get('page') ?: 1;
			$limit = 20;
			$offset = ($page - 1) * $limit;

			// Get data
			$berkas_list = $this->notelen->get_berkas_masuk($limit, $offset, $filters);
			$total_berkas = $this->notelen->count_berkas_masuk($filters);

			// Dashboard stats
			$stats = $this->notelen->get_dashboard_stats();

			$data = array(
				'title' => 'Berkas Masuk Notelen 2',
				'page_title' => 'Berkas Masuk untuk Notelen - Tampilan 2',
				'berkas_list' => $berkas_list,
				'total_berkas' => $total_berkas,
				'current_page' => $page,
				'total_pages' => ceil($total_berkas / $limit),
				'limit' => $limit,
				'filters' => $filters,
				'stats' => $stats,
				'sidebar_active' => 'notelen',
				'submenu_active' => 'berkas_masuk2'
			);

			$this->load->view('notelen/berkas_masuk2', $data);
		} catch (Exception $e) {
			echo "<div style='padding: 20px; background: #f8f9fa; border: 1px solid #ddd;'>";
			echo "<h3>Error Loading Notelen System</h3>";
			echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
			echo "<p><strong>File:</strong> " . $e->getFile() . " (Line " . $e->getLine() . ")</p>";
			echo "<p>Please check database connection and model files.</p>";
			echo "</div>";
		}
	}

	/**
	 * Berkas Template - Menggunakan template system
	 */
	public function berkas_template()
	{
		try {
			// Get filters from session or request
			$filters = array(
				'status_berkas' => $this->input->get('status') ?: $this->session->userdata('notelen_filter_status') ?: '',
				'nomor_perkara' => $this->input->get('nomor') ?: $this->session->userdata('notelen_filter_nomor') ?: '',
				'tanggal_dari' => $this->input->get('dari') ?: $this->session->userdata('notelen_filter_dari') ?: '',
				'tanggal_sampai' => $this->input->get('sampai') ?: $this->session->userdata('notelen_filter_sampai') ?: ''
			);

			// Save filters to session
			$this->session->set_userdata('notelen_filter_status', $filters['status_berkas']);
			$this->session->set_userdata('notelen_filter_nomor', $filters['nomor_perkara']);
			$this->session->set_userdata('notelen_filter_dari', $filters['tanggal_dari']);
			$this->session->set_userdata('notelen_filter_sampai', $filters['tanggal_sampai']);

			// Pagination
			$page = $this->input->get('page') ?: 1;
			$limit = 20;
			$offset = ($page - 1) * $limit;

			// Get data
			$berkas_list = $this->notelen->get_berkas_masuk($limit, $offset, $filters);
			$total_berkas = $this->notelen->count_berkas_masuk($filters);

			// Dashboard stats
			$stats = $this->notelen->get_dashboard_stats();

			$data = array(
				'title' => 'Berkas Masuk Notelen - Template',
				'page_title' => 'Berkas Masuk Notelen - Template System',
				'berkas_list' => $berkas_list,
				'total_berkas' => $total_berkas,
				'current_page' => $page,
				'total_pages' => ceil($total_berkas / $limit),
				'limit' => $limit,
				'filters' => $filters,
				'stats' => $stats,
				'sidebar_active' => 'notelen',
				'submenu_active' => 'berkas_template'
			);

			$this->load->view('notelen/berkas_masuk_template', $data);
		} catch (Exception $e) {
			echo "<div style='padding: 20px; background: #f8f9fa; border: 1px solid #ddd;'>";
			echo "<h3>Error Loading Notelen System</h3>";
			echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
			echo "<p><strong>File:</strong> " . $e->getFile() . " (Line " . $e->getLine() . ")</p>";
			echo "<p>Please check database connection and model files.</p>";
			echo "</div>";
		}
	}

    // ===============================================
    // AJAX ENDPOINTS
    // ===============================================

	/**
	 * Get berkas detail via AJAX untuk popup
	 */
	public function ajax_get_berkas()
	{
		$id = $this->input->post('id');

		if (!$id) {
			echo json_encode(array('success' => false, 'message' => 'ID tidak valid'));
			return;
		}

		$berkas = $this->notelen->get_berkas_by_id($id);

		if (!$berkas) {
			echo json_encode(array('success' => false, 'message' => 'Berkas tidak ditemukan'));
			return;
		}

		// Format data
		echo json_encode(array(
			'success' => true,
			'berkas' => array(
				'id' => $berkas->id,
				'nomor_perkara' => $berkas->nomor_perkara,
				'perkara_id_sipp' => isset($berkas->perkara_id_sipp) ? $berkas->perkara_id_sipp : '',
				'jenis_perkara' => $berkas->jenis_perkara,
				'tanggal_putusan' => $berkas->tanggal_putusan,
				'tanggal_masuk_notelen' => $berkas->tanggal_masuk_notelen,
				'majelis_hakim' => $berkas->majelis_hakim ? $berkas->majelis_hakim : '',
				'panitera_pengganti' => $berkas->panitera_pengganti ? $berkas->panitera_pengganti : '',
				'status_berkas' => $berkas->status_berkas,
				'catatan_notelen' => $berkas->catatan_notelen ? $berkas->catatan_notelen : '',
				'created_at' => isset($berkas->created_at) ? $berkas->created_at : '',
				'updated_at' => isset($berkas->updated_at) ? $berkas->updated_at : ''
			),
			'message' => 'Data berkas berhasil dimuat'
		));
	}

	/**
	 * Test server connection for AJAX
	 */
	public function test_server_connection()
	{
		header('Content-Type: application/json');
		header('Cache-Control: no-cache, must-revalidate');

		// Test configuration
		$config_status = array(
			'log_path' => $this->config->item('log_path') ?: 'default',
			'log_threshold' => $this->config->item('log_threshold'),
			'cache_path' => $this->config->item('cache_path') ?: 'default',
			'base_url' => $this->config->item('base_url')
		);

		// Test logging
		log_message('info', 'Testing server connection from: ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'CLI'));

		echo json_encode(array(
			'success' => true,
			'message' => 'Server connection OK',
			'timestamp' => date('Y-m-d H:i:s'),
			'server' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'unknown',
			'environment' => ENVIRONMENT,
			'config' => $config_status
		));
		exit();
	}

	/**
	 * Test AJAX response page
	 */
	public function test_ajax()
	{
		$data = array(
			'page_title' => 'Test AJAX Response'
		);
		$this->load->view('notelen/test_ajax_response', $data);
	}

	/**
	 * Test database connection
	 */
	public function test_db_connection()
	{
		header('Content-Type: application/json');

		try {
			// Load database directly
			$notelen_db = $this->load->database('notelen_db', TRUE);

			if (!$notelen_db) {
				echo json_encode(array(
					'success' => false,
					'message' => 'Failed to load notelen database'
				));
				exit();
			}

			// Test basic query
			$result = $notelen_db->query("SELECT 1 as test")->row();

			// Test berkas table
			$count = $notelen_db->query("SELECT COUNT(*) as total FROM berkas_masuk")->row();

			echo json_encode(array(
				'success' => true,
				'message' => 'Database connection OK',
				'test_result' => $result,
				'berkas_count' => $count,
				'database_name' => $notelen_db->database
			));
		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Database error: ' . $e->getMessage()
			));
		}
		exit();
	}

	/**
	 * Insert berkas baru via AJAX - Direct database approach
	 */
	public function ajax_insert_berkas_direct()
	{
		// Clear any output buffer
		while (ob_get_level()) {
			ob_end_clean();
		}

		// Disable error display for AJAX
		ini_set('display_errors', 0);
		error_reporting(0);

		// Set response headers
		header('Content-Type: application/json');
		header('Cache-Control: no-cache, must-revalidate');

		try {
			// Load database directly
			$notelen_db = $this->load->database('notelen_db', TRUE);

			if (!$notelen_db) {
				echo json_encode(array(
					'success' => false,
					'message' => 'Database connection failed'
				));
				exit();
			}

			$nomor_perkara = trim($this->input->post('nomor_perkara'));
			$tanggal_putusan = $this->input->post('tanggal_putusan');
			$jenis_perkara = $this->input->post('jenis_perkara');
			$majelis_hakim = $this->input->post('majelis_hakim');
			$panitera_pengganti = $this->input->post('panitera_pengganti');
			$jurusita = $this->input->post('jurusita');
			$status_berkas = $this->input->post('status_berkas');
			$catatan = $this->input->post('catatan_notelen');

			// Validasi
			if (empty($nomor_perkara) || empty($tanggal_putusan)) {
				echo json_encode(array(
					'success' => false,
					'message' => 'Nomor perkara dan tanggal putusan harus diisi'
				));
				exit();
			}

			// Check existing - direct query
			$existing = $notelen_db->query("SELECT id FROM berkas_masuk WHERE nomor_perkara = ?", array($nomor_perkara))->row();
			if ($existing) {
				echo json_encode(array(
					'success' => false,
					'message' => 'Berkas dengan nomor perkara ini sudah ada'
				));
				exit();
			}

			// Insert data directly
			$data = array(
				'nomor_perkara' => $nomor_perkara,
				'perkara_id_sipp' => 0,
				'jenis_perkara' => $jenis_perkara,
				'tanggal_putusan' => $tanggal_putusan,
				'tanggal_masuk_notelen' => date('Y-m-d'),
				'majelis_hakim' => $majelis_hakim,
				'panitera_pengganti' => $panitera_pengganti,
				'jurusita' => $jurusita,
				'status_berkas' => $status_berkas ?: 'PANITERA_PENGGANTI',
				'catatan_notelen' => $catatan,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			);

			$notelen_db->insert('berkas_masuk', $data);
			$berkas_id = $notelen_db->insert_id();

			if ($berkas_id) {
				echo json_encode(array(
					'success' => true,
					'message' => 'Berkas berhasil ditambahkan',
					'berkas_id' => $berkas_id
				));
			} else {
				echo json_encode(array(
					'success' => false,
					'message' => 'Gagal menambahkan berkas'
				));
			}
			exit();
		} catch (Exception $e) {
			// Log the actual error for debugging
			log_message('error', 'AJAX Insert Berkas Error: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());

			echo json_encode(array(
				'success' => false,
				'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
				'debug' => ENVIRONMENT !== 'production' ? $e->getMessage() : null
			));
			exit();
		}
	}

	/**
	 * Insert berkas baru via AJAX
	 */
	public function ajax_insert_berkas()
	{
		// Clear any output buffer
		while (ob_get_level()) {
			ob_end_clean();
		}

		// Disable error display for AJAX
		ini_set('display_errors', 0);
		error_reporting(0);

		// Set response headers
		header('Content-Type: application/json');
		header('Cache-Control: no-cache, must-revalidate');

		try {
			// Test database connection first
			if (!$this->notelen->notelen_db) {
				$response = array(
					'success' => false,
					'message' => 'Database notelen tidak terhubung'
				);
				echo json_encode($response);
				exit();
			}

			$nomor_perkara = trim($this->input->post('nomor_perkara'));
			$tanggal_putusan = $this->input->post('tanggal_putusan');
			$jenis_perkara = $this->input->post('jenis_perkara');
			$majelis_hakim = $this->input->post('majelis_hakim');
			$panitera_pengganti = $this->input->post('panitera_pengganti');
			$catatan = $this->input->post('catatan_notelen');

			// Validasi
			if (empty($nomor_perkara) || empty($tanggal_putusan)) {
				$response = array(
					'success' => false,
					'message' => 'Nomor perkara dan tanggal putusan harus diisi'
				);
				echo json_encode($response);
				exit();
			}

			// Check existing
			$existing = $this->notelen->get_berkas_by_nomor($nomor_perkara);
			if ($existing) {
				$response = array(
					'success' => false,
					'message' => 'Berkas dengan nomor perkara ini sudah ada'
				);
				echo json_encode($response);
				exit();
			}

			$data = array(
				'nomor_perkara' => $nomor_perkara,
				'perkara_id_sipp' => 0, // Manual entry
				'jenis_perkara' => $jenis_perkara,
				'tanggal_putusan' => $tanggal_putusan,
				'majelis_hakim' => $majelis_hakim,
				'panitera_pengganti' => $panitera_pengganti,
				'status_berkas' => 'MASUK',
				'catatan_notelen' => $catatan
			);

			$berkas_id = $this->notelen->insert_berkas_masuk($data);

			if ($berkas_id) {
				$response = array(
					'success' => true,
					'message' => 'Berkas berhasil ditambahkan',
					'berkas_id' => $berkas_id
				);
			} else {
				$response = array(
					'success' => false,
					'message' => 'Gagal menambahkan berkas'
				);
			}

			echo json_encode($response);
			exit();
		} catch (Exception $e) {
			// Log the actual error for debugging
			log_message('error', 'AJAX Insert Berkas Error: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());

			$response = array(
				'success' => false,
				'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
				'debug' => ENVIRONMENT !== 'production' ? $e->getMessage() : null
			);
			echo json_encode($response);
			exit();
		}
	}

	/**
	 * Update status berkas
	 */
	public function ajax_update_status()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$catatan = $this->input->post('catatan');

		if (!$id || !$status) {
			echo json_encode(array('success' => false, 'message' => 'Data tidak lengkap'));
			return;
		}

		$result = $this->notelen->update_status_berkas($id, $status, $catatan);

		if ($result) {
			echo json_encode(array('success' => true, 'message' => 'Status berhasil diupdate'));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Gagal update status'));
		}
	}

	/**
	 * Update berkas via AJAX
	 */
	public function ajax_update_berkas()
	{
		// Set JSON header
		header('Content-Type: application/json; charset=utf-8');

		// Clear any output buffer
		while (ob_get_level()) {
			ob_end_clean();
		}

		try {
			$berkas_id = $this->input->post('berkas_id');
			$nomor_perkara = trim($this->input->post('nomor_perkara'));
			$tanggal_putusan = $this->input->post('tanggal_putusan');
			$jenis_perkara = $this->input->post('jenis_perkara');
			$majelis_hakim = $this->input->post('majelis_hakim');
			$panitera_pengganti = $this->input->post('panitera_pengganti');
			$jurusita = $this->input->post('jurusita');
			$status_berkas = $this->input->post('status_berkas');
			$catatan = $this->input->post('catatan_notelen');

			// Validasi input
			if (empty($berkas_id) || empty($nomor_perkara) || empty($tanggal_putusan)) {
				echo json_encode(array(
					'success' => false,
					'message' => 'Data tidak lengkap: ID berkas, nomor perkara dan tanggal putusan harus diisi'
				));
				exit();
			}

			// Check if berkas exists
			$existing_berkas = $this->notelen->get_berkas_by_id($berkas_id);
			if (!$existing_berkas) {
				echo json_encode(array(
					'success' => false,
					'message' => 'Berkas tidak ditemukan'
				));
				exit();
			}

			// Prepare update data
			$update_data = array(
				'tanggal_putusan' => $tanggal_putusan,
				'jenis_perkara' => $jenis_perkara,
				'majelis_hakim' => $majelis_hakim,
				'panitera_pengganti' => $panitera_pengganti,
				'jurusita' => $jurusita,
				'status_berkas' => $status_berkas ? $status_berkas : 'PANITERA_PENGGANTI',
				'catatan_notelen' => $catatan,
				'updated_at' => date('Y-m-d H:i:s')
			);

			// Update berkas using model
			$result = $this->notelen->update_berkas_masuk($berkas_id, $update_data);

			if ($result) {
				echo json_encode(array(
					'success' => true,
					'message' => 'Berkas ' . $nomor_perkara . ' berhasil diupdate',
					'berkas_id' => $berkas_id
				));
			} else {
				echo json_encode(array(
					'success' => false,
					'message' => 'Gagal mengupdate berkas'
				));
			}
		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			));
		}
		exit();
	}

	/**
	 * Delete berkas via AJAX
	 */
	public function ajax_delete_berkas()
	{
		// Set JSON header
		header('Content-Type: application/json; charset=utf-8');

		// Handle both GET and POST
		$id = $this->input->post('id') ?: $this->input->get('id');
		$redirect = $this->input->get('redirect');

		if (!$id) {
			if ($redirect) {
				redirect('notelen/berkas_template');
				return;
			}
			echo json_encode(array('success' => false, 'message' => 'ID tidak valid'));
			return;
		}

		try {
			// Get berkas info before delete
			$berkas = $this->notelen->get_berkas_by_id($id);
			if (!$berkas) {
				if ($redirect) {
					$this->session->set_flashdata('error', 'Berkas tidak ditemukan');
					redirect('notelen/berkas_template');
					return;
				}
				echo json_encode(array('success' => false, 'message' => 'Berkas tidak ditemukan'));
				return;
			}

			// Enable CI database debug for better error messages
			$this->notelen->notelen_db->db_debug = TRUE;

			$result = $this->notelen->delete_berkas_masuk($id);

			if ($result) {
				if ($redirect) {
					$this->session->set_flashdata('success', 'Berkas ' . $berkas->nomor_perkara . ' berhasil dihapus');
					redirect('notelen/berkas_template');
					return;
				}
				echo json_encode(array(
					'success' => true,
					'message' => 'Berkas ' . $berkas->nomor_perkara . ' berhasil dihapus'
				));
			} else {
				// Check database error
				$db_error = $this->notelen->notelen_db->error();
				$error_message = 'Gagal menghapus berkas';
				if (!empty($db_error['message'])) {
					$error_message = 'Database Error: ' . $db_error['message'];
				}

				if ($redirect) {
					$this->session->set_flashdata('error', $error_message);
					redirect('notelen/berkas_template');
					return;
				}
				echo json_encode(array('success' => false, 'message' => $error_message));
			}
		} catch (Exception $e) {
			$error_message = 'Error: ' . $e->getMessage();

			if ($redirect) {
				$this->session->set_flashdata('error', $error_message);
				redirect('notelen/berkas_template');
				return;
			}
			echo json_encode(array(
				'success' => false,
				'message' => $error_message
			));
		}
	}

    // ===============================================
    // SYNC & UTILITIES
    // ===============================================

	/**
	 * Sync manual dari SIPP
	 */
	public function ajax_sync_sipp()
	{
		// Set JSON header
		header('Content-Type: application/json');

		try {
			$limit = (int)($this->input->post('limit') ?: 50);

			// Validate limit
			if ($limit < 1 || $limit > 500) {
				$limit = 50;
			}

			$result = $this->notelen->sync_perkara_putus($limit);

			echo json_encode(array(
				'success' => true,
				'message' => "Berhasil sync {$result['synced_count']} dari {$result['total_processed']} perkara",
				'data' => $result
			));
		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Error sync: ' . $e->getMessage(),
				'error_details' => array(
					'file' => $e->getFile(),
					'line' => $e->getLine()
				)
			));
		}
	}

	/**
	 * Dashboard stats untuk refresh
	 */
	public function ajax_get_stats()
	{
		$stats = $this->notelen->get_dashboard_stats();
		echo json_encode(array('success' => true, 'stats' => $stats));
	}

	/**
	 * Recent activities untuk sidebar
	 */
	public function ajax_get_activities()
	{
		$activities = $this->notelen->get_recent_activities(5);
		echo json_encode(array('success' => true, 'activities' => $activities));
	}

    // ===============================================
    // EXPORT & PRINT
    // ===============================================

	/**
	 * Export berkas ke Excel/PDF
	 */
	public function export()
	{
		$format = $this->input->get('format') ?: 'excel';

		// Get filters
		$filters = array(
			'status_berkas' => $this->session->userdata('notelen_filter_status') ?: '',
			'nomor_perkara' => $this->session->userdata('notelen_filter_nomor') ?: '',
			'tanggal_dari' => $this->session->userdata('notelen_filter_dari') ?: '',
			'tanggal_sampai' => $this->session->userdata('notelen_filter_sampai') ?: ''
		);

		// Get all data (no limit)
		$berkas_list = $this->notelen->get_berkas_masuk(null, 0, $filters);

		if ($format === 'excel') {
			$this->_export_excel($berkas_list);
		} else {
			echo "Format export belum didukung";
		}
	}

	/**
	 * Export to Excel
	 */
	private function _export_excel($data)
	{
		// Load PHPExcel
		require_once APPPATH . 'third_party/PHPExcel-1.8/Classes/PHPExcel.php';

		$excel = new PHPExcel();
		$excel->setActiveSheetIndex(0);
		$sheet = $excel->getActiveSheet();

		// Headers
		$sheet->setCellValue('A1', 'No');
		$sheet->setCellValue('B1', 'Nomor Perkara');
		$sheet->setCellValue('C1', 'Jenis Perkara');
		$sheet->setCellValue('D1', 'Tanggal Putusan');
		$sheet->setCellValue('E1', 'Tanggal Masuk');
		$sheet->setCellValue('F1', 'Status');
		$sheet->setCellValue('G1', 'Majelis Hakim');

		// Data
		$row = 2;
		foreach ($data as $index => $berkas) {
			$sheet->setCellValue('A' . $row, $index + 1);
			$sheet->setCellValue('B' . $row, $berkas->nomor_perkara);
			$sheet->setCellValue('C' . $row, $berkas->jenis_perkara);
			$sheet->setCellValue('D' . $row, $berkas->tanggal_putusan);
			$sheet->setCellValue('E' . $row, $berkas->tanggal_masuk_notelen);
			$sheet->setCellValue('F' . $row, $berkas->status_berkas);
			$sheet->setCellValue('G' . $row, $berkas->majelis_hakim);
			$row++;
		}

		// Output
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="berkas_notelen_' . date('Y-m-d') . '.xls"');
		header('Cache-Control: max-age=0');

		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$writer->save('php://output');
	}

	/**
	 * AJAX: Get perkara putus for dropdown
	 */
	public function ajax_get_perkara_dropdown()
	{
		header('Content-Type: application/json; charset=utf-8');

		try {
			$search = $this->input->get('search') ?: '';
			$perkara_list = $this->notelen->get_perkara_putus_dropdown($search, 1000);

			$response = array(
				'success' => true,
				'data' => $perkara_list
			);
		} catch (Exception $e) {
			$response = array(
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			);
		}

		echo json_encode($response);
	}

	/**
	 * AJAX: Get perkara detail by nomor
	 */
	public function ajax_get_perkara_detail()
	{
		header('Content-Type: application/json; charset=utf-8');

		try {
			$nomor_perkara = $this->input->post('nomor_perkara');

			if (empty($nomor_perkara)) {
				throw new Exception('Nomor perkara tidak boleh kosong');
			}

			$detail = $this->notelen->get_perkara_detail_by_nomor($nomor_perkara);

			if (!$detail) {
				throw new Exception('Data perkara tidak ditemukan');
			}

			$response = array(
				'success' => true,
				'data' => $detail
			);
		} catch (Exception $e) {
			$response = array(
				'success' => false,
				'message' => $e->getMessage()
			);
		}

		echo json_encode($response);
	}

	/**
	 * Reset filters untuk berkas template
	 */
	public function reset_filters()
	{
		// Clear session filters
		$this->session->unset_userdata('notelen_filter_status');
		$this->session->unset_userdata('notelen_filter_nomor');
		$this->session->unset_userdata('notelen_filter_dari');
		$this->session->unset_userdata('notelen_filter_sampai');

		// Set success message
		$this->session->set_flashdata('success', 'Filter berhasil direset');

		// Redirect back to berkas template
		redirect('notelen/berkas_template');
	}

	/**
	 * Berkas PBT - Halaman utama PBT
	 */
	public function berkas_pbt()
	{
		try {
			// Get filters from session or request
			$filters = array(
				'status_proses' => $this->input->get('status') ?: $this->session->userdata('notelen_pbt_filter_status') ?: '',
				'nomor_perkara' => $this->input->get('nomor') ?: $this->session->userdata('notelen_pbt_filter_nomor') ?: '',
				'tanggal_dari' => $this->input->get('dari') ?: $this->session->userdata('notelen_pbt_filter_dari') ?: '',
				'tanggal_sampai' => $this->input->get('sampai') ?: $this->session->userdata('notelen_pbt_filter_sampai') ?: ''
			);

			// Save filters to session
			$this->session->set_userdata('notelen_pbt_filter_status', $filters['status_proses']);
			$this->session->set_userdata('notelen_pbt_filter_nomor', $filters['nomor_perkara']);
			$this->session->set_userdata('notelen_pbt_filter_dari', $filters['tanggal_dari']);
			$this->session->set_userdata('notelen_pbt_filter_sampai', $filters['tanggal_sampai']);

			// Pagination
			$page = $this->input->get('page') ?: 1;
			$limit = 20;
			$offset = ($page - 1) * $limit;

			// Get data
			$pbt_list = $this->notelen->get_berkas_pbt($limit, $offset, $filters);
			$total_pbt = $this->notelen->count_berkas_pbt($filters);

			// Dashboard stats
			$stats = $this->notelen->get_pbt_dashboard_stats();

			// Pagination calculation
			$total_pages = ceil($total_pbt / $limit);

			$data = array(
				'pbt_list' => $pbt_list,
				'total_pbt' => $total_pbt,
				'stats' => $stats,
				'filters' => $filters,
				'current_page' => $page,
				'total_pages' => $total_pages,
				'offset' => $offset
			);

			$this->load->view('notelen/berkas_pbt_template', $data);
		} catch (Exception $e) {
			echo "<div style='padding: 20px; background: #f8f9fa; border: 1px solid #ddd;'>";
			echo "<h3>Error Loading PBT System</h3>";
			echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
			echo "<p><strong>File:</strong> " . $e->getFile() . " (Line " . $e->getLine() . ")</p>";
			echo "<p>Please check database connection and model files.</p>";
			echo "</div>";
		}
	}

	/**
	 * Insert PBT baru via AJAX
	 */
	public function ajax_insert_pbt()
	{
		header('Content-Type: application/json; charset=utf-8');

		$nomor_perkara = trim($this->input->post('nomor_perkara'));
		$perkara_id_sipp = (int)$this->input->post('perkara_id_sipp');
		$tanggal_putusan = $this->input->post('tanggal_putusan');
		$tanggal_pbt = $this->input->post('tanggal_pbt');
		$tanggal_bht = $this->input->post('tanggal_bht');
		$jenis_perkara = $this->input->post('jenis_perkara');
		$majelis_hakim = $this->input->post('majelis_hakim');
		$panitera_pengganti = $this->input->post('panitera_pengganti');
		$catatan = $this->input->post('catatan_pbt');

		// Validasi
		if (empty($nomor_perkara) || empty($tanggal_putusan)) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Nomor perkara dan tanggal putusan harus diisi'
			));
			return;
		}

		try {
			$data = array(
				'nomor_perkara' => $nomor_perkara,
				'perkara_id_sipp' => $perkara_id_sipp ?: 0,
				'jenis_perkara' => $jenis_perkara,
				'tanggal_putusan' => $tanggal_putusan,
				'tanggal_pbt' => $tanggal_pbt ?: null,
				'tanggal_bht' => $tanggal_bht ?: null,
				'majelis_hakim' => $majelis_hakim,
				'panitera_pengganti' => $panitera_pengganti,
				'catatan_pbt' => $catatan
			);

			$pbt_id = $this->notelen->insert_berkas_pbt($data);

			if ($pbt_id) {
				echo json_encode(array(
					'success' => true,
					'message' => 'Berkas PBT berhasil ditambahkan',
					'pbt_id' => $pbt_id
				));
			} else {
				echo json_encode(array(
					'success' => false,
					'message' => 'Gagal menyimpan berkas PBT'
				));
			}
		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			));
		}
	}

	/**
	 * Sync PBT dari SIPP
	 */
	public function ajax_sync_pbt()
	{
		header('Content-Type: application/json; charset=utf-8');

		try {
			$result = $this->notelen->sync_perkara_pbt();

			if ($result['success']) {
				echo json_encode(array(
					'success' => true,
					'message' => 'Sync berhasil! ' . $result['synced_count'] . ' data PBT ditambahkan, ' . $result['duplicate_count'] . ' duplikat ditemukan'
				));
			} else {
				echo json_encode(array(
					'success' => false,
					'message' => $result['message']
				));
			}
		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			));
		}
	}

	/**
	 * AJAX - Get single PBT data for detail/edit
	 */
	public function ajax_get_pbt()
	{
		$id = $this->input->get('id');

		if (!$id) {
			echo json_encode(array(
				'success' => false,
				'message' => 'ID berkas PBT tidak valid'
			));
			return;
		}

		try {
			$pbt = $this->notelen->get_pbt_by_id($id);

			if ($pbt) {
				// Convert object to array and ensure all fields are available
				$data = array(
					'id' => $pbt->id,
					'nomor_perkara' => $pbt->nomor_perkara,
					'jenis_perkara' => $pbt->jenis_perkara,
					'tanggal_putusan' => $pbt->tanggal_putusan,
					'tanggal_pbt' => $pbt->tanggal_pbt,
					'tanggal_bht' => $pbt->tanggal_bht,
					'majelis_hakim' => $pbt->majelis_hakim,
					'panitera_pengganti' => $pbt->panitera_pengganti,
					'catatan_pbt' => $pbt->catatan_pbt,
					'status_proses' => $pbt->status_proses,
					'selisih_putus_pbt' => $pbt->selisih_putus_pbt,
					'perkara_id_sipp' => property_exists($pbt, 'perkara_id_sipp') ? $pbt->perkara_id_sipp : null,
					'created_at' => property_exists($pbt, 'created_at') ? $pbt->created_at : null,
					'updated_at' => property_exists($pbt, 'updated_at') ? $pbt->updated_at : null
				);

				echo json_encode(array(
					'success' => true,
					'data' => $data,
					'message' => 'Data PBT berhasil diambil'
				));
			} else {
				echo json_encode(array(
					'success' => false,
					'message' => 'Data PBT tidak ditemukan'
				));
			}
		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			));
		}
	}

	/**
	 * AJAX - Update berkas PBT
	 */
	public function ajax_update_pbt()
	{
		$id = $this->input->post('id');

		if (!$id) {
			echo json_encode(array(
				'success' => false,
				'message' => 'ID berkas PBT tidak valid'
			));
			return;
		}

		// Get current PBT data
		$existing_pbt = $this->notelen->get_pbt_by_id($id);
		if (!$existing_pbt) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Berkas PBT tidak ditemukan'
			));
			return;
		}

		// Prepare update data - exclude readonly fields (jenis_perkara, majelis_hakim, panitera_pengganti, tanggal_putusan)
		// Only process editable fields: tanggal_pbt, tanggal_bht, catatan_pbt
		$update_data = array(
			'tanggal_pbt' => $this->input->post('tanggal_pbt') ?: null,
			'tanggal_bht' => $this->input->post('tanggal_bht') ?: null,
			'catatan_pbt' => $this->input->post('catatan_pbt')
		);

		// Calculate selisih hari and status using existing tanggal_putusan from database
		if ($update_data['tanggal_pbt'] && $existing_pbt->tanggal_putusan) {
			$tanggal_putusan = new DateTime($existing_pbt->tanggal_putusan);
			$tanggal_pbt = new DateTime($update_data['tanggal_pbt']);
			$diff = $tanggal_putusan->diff($tanggal_pbt);
			$update_data['selisih_putus_pbt'] = $diff->days;
		} else {
			$update_data['selisih_putus_pbt'] = null;
		}

		// Update status proses
		if ($update_data['tanggal_bht']) {
			$update_data['status_proses'] = 'Selesai';
		} elseif ($update_data['tanggal_pbt']) {
			$update_data['status_proses'] = 'Sudah PBT Belum BHT';
		} else {
			$update_data['status_proses'] = 'Belum PBT';
		}

		try {
			$result = $this->notelen->update_berkas_pbt($id, $update_data);

			if ($result) {
				echo json_encode(array(
					'success' => true,
					'message' => 'Berkas PBT ' . $existing_pbt->nomor_perkara . ' berhasil diperbarui',
					'data' => $update_data
				));
			} else {
				echo json_encode(array(
					'success' => false,
					'message' => 'Gagal memperbarui berkas PBT'
				));
			}
		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			));
		}
	}

	/**
	 * Delete berkas PBT via AJAX
	 */
	public function ajax_delete_pbt()
	{
		header('Content-Type: application/json; charset=utf-8');

		$id = $this->input->post('id') ?: $this->input->get('id');
		$redirect = $this->input->get('redirect');

		if (!$id) {
			if ($redirect) {
				redirect('notelen/berkas_pbt');
				return;
			}
			echo json_encode(array('success' => false, 'message' => 'ID tidak valid'));
			return;
		}

		try {
			$pbt = $this->notelen->get_pbt_by_id($id);
			if (!$pbt) {
				if ($redirect) {
					$this->session->set_flashdata('error', 'Berkas PBT tidak ditemukan');
					redirect('notelen/berkas_pbt');
					return;
				}
				echo json_encode(array('success' => false, 'message' => 'Berkas PBT tidak ditemukan'));
				return;
			}

			$result = $this->notelen->delete_berkas_pbt($id);

			if ($result) {
				if ($redirect) {
					$this->session->set_flashdata('success', 'Berkas PBT ' . $pbt->nomor_perkara . ' berhasil dihapus');
					redirect('notelen/berkas_pbt');
					return;
				}
				echo json_encode(array(
					'success' => true,
					'message' => 'Berkas PBT ' . $pbt->nomor_perkara . ' berhasil dihapus'
				));
			} else {
				if ($redirect) {
					$this->session->set_flashdata('error', 'Gagal menghapus berkas PBT');
					redirect('notelen/berkas_pbt');
					return;
				}
				echo json_encode(array('success' => false, 'message' => 'Gagal menghapus berkas PBT'));
			}
		} catch (Exception $e) {
			if ($redirect) {
				$this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
				redirect('notelen/berkas_pbt');
				return;
			}
			echo json_encode(array(
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			));
		}
	}

	// Command Center Dashboard - Futuristic style
	public function command_center()
	{
		$data['title'] = 'Command Center - Notelen System';
		$this->load->view('notelen/command_center', $data);
	}

	// Mobile Dashboard - Mobile-first design
	public function mobile_dashboard()
	{
		$data['title'] = 'Mobile Dashboard - Notelen System';
		$this->load->view('notelen/mobile_dashboard', $data);
	}

	// Timeline Interactive - Timeline view
	public function timeline()
	{
		$data['title'] = 'Timeline Interactive - Notelen System';
		$this->load->view('notelen/timeline_interactive', $data);
	}

	// Dashboard Gallery - Choose dashboard style
	public function gallery()
	{
		$data['title'] = 'Dashboard Gallery - Notelen System';
		$this->load->view('notelen/dashboard_gallery', $data);
	}
}
