<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model untuk sistem notelen (berkas masuk perkara putus)
 * Menggunakan database terpisah: notelen_system
 * 
 * Fungsi utama:
 * - Mengelola berkas masuk perkara yang sudah putus
 * - Master data barang untuk inventaris
 * - Tracking inventaris per berkas
 * - Sinkronisasi dari database SIPP
 */
class Notelen_model extends CI_Model
{
	private $notelen_db;
	private $sipp_db;

	public function __construct()
	{
		parent::__construct();

		// Load database notelen (database ketiga)
		$this->notelen_db = $this->load->database('notelen_db', TRUE);

		// Load database SIPP (database utama)
		$this->sipp_db = $this->load->database('default', TRUE);
	}

    // ===============================================
    // CRUD BERKAS MASUK
    // ===============================================

	/**
	 * Get berkas masuk dengan filter dan pagination
	 */
	public function get_berkas_masuk($limit = null, $offset = 0, $filters = array())
	{
		$this->notelen_db->select('bm.*');
		$this->notelen_db->from('berkas_masuk bm');

		// Apply filters
		if (!empty($filters['status_berkas'])) {
			$this->notelen_db->where('bm.status_berkas', $filters['status_berkas']);
		}

		if (!empty($filters['nomor_perkara'])) {
			$this->notelen_db->like('bm.nomor_perkara', $filters['nomor_perkara']);
		}

		if (!empty($filters['tanggal_dari']) && !empty($filters['tanggal_sampai'])) {
			$this->notelen_db->where('bm.tanggal_putusan >=', $filters['tanggal_dari']);
			$this->notelen_db->where('bm.tanggal_putusan <=', $filters['tanggal_sampai']);
		}

		$this->notelen_db->group_by('bm.id');
		$this->notelen_db->order_by('bm.tanggal_masuk_notelen', 'DESC');

		if ($limit) {
			$this->notelen_db->limit($limit, $offset);
		}

		return $this->notelen_db->get()->result();
	}

	/**
	 * Count berkas masuk
	 */
	public function count_berkas_masuk($filters = array())
	{
		$this->notelen_db->from('berkas_masuk bm');

		// Apply same filters
		if (!empty($filters['status_berkas'])) {
			$this->notelen_db->where('bm.status_berkas', $filters['status_berkas']);
		}

		if (!empty($filters['nomor_perkara'])) {
			$this->notelen_db->like('bm.nomor_perkara', $filters['nomor_perkara']);
		}

		if (!empty($filters['tanggal_dari']) && !empty($filters['tanggal_sampai'])) {
			$this->notelen_db->where('bm.tanggal_putusan >=', $filters['tanggal_dari']);
			$this->notelen_db->where('bm.tanggal_putusan <=', $filters['tanggal_sampai']);
		}

		return $this->notelen_db->count_all_results();
	}

	/**
	 * Get berkas by ID
	 */
	public function get_berkas_by_id($id)
	{
		return $this->notelen_db->get_where('berkas_masuk', array('id' => $id))->row();
	}

	/**
	 * Get berkas by nomor perkara
	 */
	public function get_berkas_by_nomor($nomor_perkara)
	{
		return $this->notelen_db->get_where('berkas_masuk', array('nomor_perkara' => $nomor_perkara))->row();
	}

	/**
	 * Insert berkas masuk baru
	 */
	public function insert_berkas_masuk($data)
	{
		try {
			// Check if already exists
			$existing = $this->get_berkas_by_nomor($data['nomor_perkara']);
			if ($existing) {
				return $existing->id;
			}

			$berkas_data = array(
				'nomor_perkara' => $data['nomor_perkara'],
				'perkara_id_sipp' => $data['perkara_id_sipp'],
				'jenis_perkara' => isset($data['jenis_perkara']) ? $data['jenis_perkara'] : null,
				'tanggal_putusan' => $data['tanggal_putusan'],
				'tanggal_masuk_notelen' => isset($data['tanggal_masuk_notelen']) ? $data['tanggal_masuk_notelen'] : date('Y-m-d'),
				'majelis_hakim' => isset($data['majelis_hakim']) ? $data['majelis_hakim'] : null,
				'panitera_pengganti' => isset($data['panitera_pengganti']) ? $data['panitera_pengganti'] : null,
				'status_berkas' => isset($data['status_berkas']) ? $data['status_berkas'] : 'MASUK',
				'catatan_notelen' => isset($data['catatan_notelen']) ? $data['catatan_notelen'] : null
			);

			// Start transaction
			$this->notelen_db->trans_start();

			$this->notelen_db->insert('berkas_masuk', $berkas_data);
			$berkas_id = $this->notelen_db->insert_id();

			if ($berkas_id) {
				// Log activity
				$this->log_notelen_activity($berkas_id, 'BERKAS_MASUK', 'Berkas masuk ke notelen', null, null);
			}

			// Complete transaction
			$this->notelen_db->trans_complete();

			if ($this->notelen_db->trans_status() === FALSE) {
				return false;
			}

			return $berkas_id;
		} catch (Exception $e) {
			// Rollback transaction on error
			$this->notelen_db->trans_rollback();
			log_message('error', 'Error inserting berkas: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Update status berkas
	 */
	public function update_status_berkas($id, $new_status, $catatan = null)
	{
		$current = $this->get_berkas_by_id($id);
		if (!$current) {
			return false;
		}

		$update_data = array(
			'status_berkas' => $new_status,
			'updated_at' => date('Y-m-d H:i:s')
		);

		if ($catatan) {
			$update_data['catatan_notelen'] = $catatan;
		}

		$this->notelen_db->where('id', $id);
		$result = $this->notelen_db->update('berkas_masuk', $update_data);

		if ($result) {
			$this->log_notelen_activity($id, 'STATUS_CHANGE', 'Status berkas diubah', $current->status_berkas, $new_status);
		}

		return $result;
	}

	/**
	 * Update berkas masuk
	 */
	public function update_berkas_masuk($id, $data)
	{
		$current = $this->get_berkas_by_id($id);
		if (!$current) {
			return false;
		}

		$this->notelen_db->where('id', $id);
		$result = $this->notelen_db->update('berkas_masuk', $data);

		if ($result) {
			$this->log_notelen_activity($id, 'UPDATE', 'Data berkas diupdate', null, null);
		}

		return $result;
	}

	/**
	 * Delete berkas masuk
	 */
	public function delete_berkas_masuk($id)
	{
		$berkas = $this->get_berkas_by_id($id);
		if (!$berkas) {
			return false;
		}

		// Start transaction
		$this->notelen_db->trans_start();

		// Delete berkas directly (no more related tables)
		$this->notelen_db->where('id', $id);
		$result = $this->notelen_db->delete('berkas_masuk');

		// Complete transaction
		$this->notelen_db->trans_complete();

		return $this->notelen_db->trans_status();
	}

    // ===============================================
    // DROPDOWN & AUTO-FILL DATA
    // ===============================================

	/**
	 * Get perkara putus untuk dropdown - SEMUA data dari SIPP
	 */
	public function get_perkara_putus_dropdown($search = '', $limit = 1000)
	{
		$query = "
            SELECT 
                p.perkara_id,
                p.nomor_perkara,
                p.jenis_perkara_nama as jenis_perkara,
                pp.tanggal_putusan,
                COALESCE(pen.majelis_hakim_nama, '-') as majelis_hakim,
                COALESCE(pen.panitera_pengganti_text, '-') as panitera_pengganti,
                COALESCE(pen.jurusita_text, '-') as jurusita
            FROM perkara p
            INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
            LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
            WHERE pp.tanggal_putusan IS NOT NULL
            AND YEAR(pp.tanggal_putusan) >= 2024
        ";

		if (!empty($search)) {
			$query .= " AND p.nomor_perkara LIKE '%" . $this->sipp_db->escape_str($search) . "%'";
		}

		$query .= " ORDER BY pp.tanggal_putusan DESC LIMIT ?";

		return $this->sipp_db->query($query, array((int)$limit))->result();
	}

	/**
	 * Get detail perkara by nomor perkara
	 */
	public function get_perkara_detail_by_nomor($nomor_perkara)
	{
		$query = "
            SELECT 
                p.perkara_id,
                p.nomor_perkara,
                p.jenis_perkara_nama as jenis_perkara,
                pp.tanggal_putusan,
                COALESCE(pen.majelis_hakim_nama, '-') as majelis_hakim,
                COALESCE(pen.panitera_pengganti_text, '-') as panitera_pengganti,
                COALESCE(pen.jurusita_text, '-') as jurusita
            FROM perkara p
            INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
            LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
            WHERE p.nomor_perkara = ?
            AND pp.tanggal_putusan IS NOT NULL
        ";

		$result = $this->sipp_db->query($query, array($nomor_perkara))->row();
		return $result;
	}

    // ===============================================
    // SINKRONISASI DARI SIPP
    // ===============================================

	/**
	 * Sync perkara putus dari SIPP
	 */
	public function sync_perkara_putus($limit = 50)
	{
		$query = "
            SELECT 
                p.perkara_id,
                p.nomor_perkara,
                p.jenis_perkara_nama as jenis_perkara,
                pp.tanggal_putusan,
                COALESCE(pen.majelis_hakim_nama, '-') as majelis_hakim,
                COALESCE(pen.panitera_pengganti_text, '-') as panitera_pengganti
            FROM perkara p
            INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
            LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
            WHERE pp.tanggal_putusan IS NOT NULL
            AND YEAR(pp.tanggal_putusan) >= 2023
            AND p.nomor_perkara NOT IN (
                SELECT nomor_perkara FROM notelen_system.berkas_masuk
            )
            ORDER BY pp.tanggal_putusan DESC
            LIMIT ?
        ";
		$sipp_data = $this->sipp_db->query($query, array((int)$limit))->result();

		$synced_count = 0;
		$errors = array();

		foreach ($sipp_data as $row) {
			try {
				$berkas_data = array(
					'nomor_perkara' => $row->nomor_perkara,
					'perkara_id_sipp' => $row->perkara_id,
					'jenis_perkara' => $row->jenis_perkara,
					'tanggal_putusan' => $row->tanggal_putusan,
					'majelis_hakim' => $row->majelis_hakim,
					'panitera_pengganti' => $row->panitera_pengganti,
					'status_berkas' => 'MASUK'
				);

				$this->insert_berkas_masuk($berkas_data);
				$synced_count++;
			} catch (Exception $e) {
				$errors[] = "Error sync perkara {$row->nomor_perkara}: " . $e->getMessage();
			}
		}

		return array(
			'synced_count' => $synced_count,
			'total_processed' => count($sipp_data),
			'errors' => $errors
		);
	}

    // ===============================================
    // DASHBOARD & STATISTIK
    // ===============================================

	/**
	 * Get dashboard stats
	 */
	public function get_dashboard_stats()
	{
		try {
			// Untuk sementara, always gunakan manual stats untuk memastikan data benar
			$dashboard = $this->get_manual_dashboard_stats();

			// Get inventaris stats (mock data untuk sekarang)
			$inventaris_stats = $this->get_inventaris_stats();

			return array(
				'berkas' => $dashboard,
				'berkas_pbt' => $dashboard,
				'inventaris' => $inventaris_stats
			);
		} catch (Exception $e) {
			// Log error untuk debugging
			log_message('error', 'Dashboard stats main error: ' . $e->getMessage());

			// Fallback ke manual stats
			$inventaris_stats = $this->get_inventaris_stats();
			return array(
				'berkas' => $this->get_manual_dashboard_stats(),
				'berkas_pbt' => $this->get_manual_dashboard_stats(),
				'inventaris' => $inventaris_stats
			);
		}
	}
	/**
	 * Get manual dashboard stats jika view tidak ada
	 */
	private function get_manual_dashboard_stats()
	{
		try {
			// Use direct queries untuk memastikan data benar
			$total_query = $this->notelen_db->query("SELECT COUNT(*) as total FROM berkas_masuk");
			$total = $total_query ? $total_query->row()->total : 0;

			$masuk_query = $this->notelen_db->query("SELECT COUNT(*) as count FROM berkas_masuk WHERE status_berkas = 'MASUK'");
			$masuk = $masuk_query ? $masuk_query->row()->count : 0;

			$proses_query = $this->notelen_db->query("SELECT COUNT(*) as count FROM berkas_masuk WHERE status_berkas = 'PROSES'");
			$proses = $proses_query ? $proses_query->row()->count : 0;

			$selesai_query = $this->notelen_db->query("SELECT COUNT(*) as count FROM berkas_masuk WHERE status_berkas = 'SELESAI'");
			$selesai = $selesai_query ? $selesai_query->row()->count : 0;

			$arsip_query = $this->notelen_db->query("SELECT COUNT(*) as count FROM berkas_masuk WHERE status_berkas = 'ARSIP'");
			$arsip = $arsip_query ? $arsip_query->row()->count : 0;

			// Log untuk debugging
			log_message('info', "Dashboard Stats - Total: $total, Masuk: $masuk, Proses: $proses, Selesai: $selesai");

			return (object)array(
				'total_berkas' => $total,
				'status_masuk' => $masuk,
				'status_proses' => $proses,
				'status_selesai' => $selesai,
				'status_arsip' => $arsip,
				'berkas_hari_ini' => 0,
				'berkas_minggu_ini' => 0,
				// Compatibility fields
				'total_berkas_masuk' => $total,
				'berkas_masuk_baru' => $masuk,
				'berkas_dalam_proses' => $proses,
				'berkas_selesai' => $selesai
			);
		} catch (Exception $e) {
			// Log error untuk debugging
			log_message('error', 'Dashboard stats error: ' . $e->getMessage());

			return (object)array(
				'total_berkas' => 0,
				'status_masuk' => 0,
				'status_proses' => 0,
				'status_selesai' => 0,
				'status_arsip' => 0,
				'berkas_hari_ini' => 0,
				'berkas_minggu_ini' => 0,
				// Compatibility fields
				'total_berkas_masuk' => 0,
				'berkas_masuk_baru' => 0,
				'berkas_dalam_proses' => 0,
				'berkas_selesai' => 0
			);
		}
	}

	/**
	 * Get inventaris statistics
	 */
	private function get_inventaris_stats()
	{
		try {
			// Coba hitung dari tabel berkas_masuk yang ada
			$total_berkas = $this->notelen_db->count_all_results('berkas_masuk', FALSE);

			// Karena tabel inventaris sudah dihapus, kita simulasi data
			// berdasarkan berkas yang ada
			return (object)array(
				'total_barang' => $total_berkas * 3, // Simulasi 3 item per berkas
				'barang_masuk' => $total_berkas,
				'barang_keluar' => intval($total_berkas * 0.2),
				'barang_rusak' => intval($total_berkas * 0.1)
			);
		} catch (Exception $e) {
			// Fallback jika ada error
			return (object)array(
				'total_barang' => 0,
				'barang_masuk' => 0,
				'barang_keluar' => 0,
				'barang_rusak' => 0
			);
		}
	}





    // ===============================================
    // HELPER FUNCTIONS
    // ===============================================

	/**
	 * Log activity - disabled (tabel notelen_log sudah dihapus)
	 */
	private function log_notelen_activity($berkas_id, $activity_type, $description, $old_value = null, $new_value = null)
	{
		// Method ini sudah tidak digunakan karena tabel notelen_log telah dihapus
		// Tetap dipertahankan untuk kompatibilitas method yang sudah ada
		return true;
	}

	/**
	 * Get/Set config - disabled (tabel notelen_config sudah dihapus)
	 */
	public function get_config($key)
	{
		// Method ini sudah tidak digunakan karena tabel notelen_config telah dihapus
		return null;
	}

	public function update_config($key, $value)
	{
		// Method ini sudah tidak digunakan karena tabel notelen_config telah dihapus
		return true;
	}

	// ===============================================
	// BERKAS PBT MANAGEMENT
	// ===============================================

	/**
	 * Get berkas PBT dengan filter dan pagination
	 */
	public function get_berkas_pbt($limit = null, $offset = 0, $filters = array())
	{
		$this->notelen_db->select('*');
		$this->notelen_db->from('berkas_pbt');

		// Apply filters
		if (!empty($filters['status_proses'])) {
			$this->notelen_db->where('status_proses', $filters['status_proses']);
		}

		if (!empty($filters['nomor_perkara'])) {
			$this->notelen_db->like('nomor_perkara', $filters['nomor_perkara']);
		}

		if (!empty($filters['tanggal_dari']) && !empty($filters['tanggal_sampai'])) {
			$this->notelen_db->where('tanggal_putusan >=', $filters['tanggal_dari']);
			$this->notelen_db->where('tanggal_putusan <=', $filters['tanggal_sampai']);
		}

		$this->notelen_db->order_by('tanggal_putusan', 'DESC');

		if ($limit) {
			$this->notelen_db->limit($limit, $offset);
		}

		return $this->notelen_db->get()->result();
	}

	/**
	 * Count berkas PBT
	 */
	public function count_berkas_pbt($filters = array())
	{
		$this->notelen_db->from('berkas_pbt');

		// Apply same filters
		if (!empty($filters['status_proses'])) {
			$this->notelen_db->where('status_proses', $filters['status_proses']);
		}

		if (!empty($filters['nomor_perkara'])) {
			$this->notelen_db->like('nomor_perkara', $filters['nomor_perkara']);
		}

		if (!empty($filters['tanggal_dari']) && !empty($filters['tanggal_sampai'])) {
			$this->notelen_db->where('tanggal_putusan >=', $filters['tanggal_dari']);
			$this->notelen_db->where('tanggal_putusan <=', $filters['tanggal_sampai']);
		}

		return $this->notelen_db->count_all_results();
	}

	/**
	 * Get PBT by ID
	 */
	public function get_pbt_by_id($id)
	{
		return $this->notelen_db->get_where('berkas_pbt', array('id' => $id))->row();
	}

	/**
	 * Get PBT by nomor perkara
	 */
	public function get_pbt_by_nomor($nomor_perkara)
	{
		return $this->notelen_db->get_where('berkas_pbt', array('nomor_perkara' => $nomor_perkara))->row();
	}

	/**
	 * Insert berkas PBT baru
	 */
	public function insert_berkas_pbt($data)
	{
		// Check duplicate in berkas_masuk
		$is_duplicate = $this->check_duplicate_berkas($data['nomor_perkara']);

		// Calculate status and selisih
		$status_proses = 'Belum PBT';
		$selisih_putus_pbt = null;
		$selisih_pbt_bht = null;

		if (!empty($data['tanggal_pbt'])) {
			if (!empty($data['tanggal_bht'])) {
				$status_proses = 'Selesai';
				$selisih_pbt_bht = $this->calculate_date_diff($data['tanggal_pbt'], $data['tanggal_bht']);
			} else {
				$status_proses = 'Sudah PBT Belum BHT';
			}
			$selisih_putus_pbt = $this->calculate_date_diff($data['tanggal_putusan'], $data['tanggal_pbt']);
		}

		$pbt_data = array(
			'nomor_perkara' => $data['nomor_perkara'],
			'perkara_id_sipp' => isset($data['perkara_id_sipp']) ? $data['perkara_id_sipp'] : 0,
			'jenis_perkara' => isset($data['jenis_perkara']) ? $data['jenis_perkara'] : null,
			'tanggal_putusan' => $data['tanggal_putusan'],
			'tanggal_pbt' => isset($data['tanggal_pbt']) ? $data['tanggal_pbt'] : null,
			'tanggal_bht' => isset($data['tanggal_bht']) ? $data['tanggal_bht'] : null,
			'selisih_putus_pbt' => $selisih_putus_pbt,
			'selisih_pbt_bht' => $selisih_pbt_bht,
			'status_proses' => $status_proses,
			'majelis_hakim' => isset($data['majelis_hakim']) ? $data['majelis_hakim'] : null,
			'panitera_pengganti' => isset($data['panitera_pengganti']) ? $data['panitera_pengganti'] : null,
			'catatan_pbt' => isset($data['catatan_pbt']) ? $data['catatan_pbt'] : null,
			'is_duplicate_berkas' => $is_duplicate ? 1 : 0
		);

		$this->notelen_db->insert('berkas_pbt', $pbt_data);
		return $this->notelen_db->insert_id();
	}

	/**
	 * Update berkas PBT
	 */
	public function update_berkas_pbt($id, $data)
	{
		// Add updated timestamp
		$data['updated_at'] = date('Y-m-d H:i:s');

		$this->notelen_db->where('id', $id);
		return $this->notelen_db->update('berkas_pbt', $data);
	}

	/**
	 * Delete berkas PBT
	 */
	public function delete_berkas_pbt($id)
	{
		$this->notelen_db->where('id', $id);
		return $this->notelen_db->delete('berkas_pbt');
	}

	/**
	 * Sync perkara PBT dari SIPP
	 */
	public function sync_perkara_pbt($limit = 100)
	{
		$query = "
			SELECT 
				p.nomor_perkara,
				p.perkara_id,
				p.jenis_perkara_nama as jenis_perkara,
				DATE(pp.tanggal_putusan) as tanggal_putusan,
				DATE(pjs.tanggal_sidang) as tanggal_pbt,
				DATE(pp.tanggal_bht) as tanggal_bht,
				DATEDIFF(pjs.tanggal_sidang, pp.tanggal_putusan) as selisih_putus_pbt,
				DATEDIFF(pp.tanggal_bht, pjs.tanggal_sidang) as selisih_pbt_bht,
				CASE 
					WHEN pjs.tanggal_sidang IS NULL THEN 'Belum PBT'
					WHEN pp.tanggal_bht IS NULL THEN 'Sudah PBT Belum BHT'
					ELSE 'Selesai'
				END as status_proses,
				pp.majelis_hakim,
				pp.panitera_pengganti
			FROM perkara p
			LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
			LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id AND pjs.jenis_sidang_nama LIKE '%PBT%'
			WHERE pp.tanggal_putusan IS NOT NULL
			AND DATE_FORMAT(pp.tanggal_putusan, '%Y-%m') >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 6 MONTH), '%Y-%m')
			ORDER BY pp.tanggal_putusan DESC
			LIMIT ?
		";

		$sipp_data = $this->sipp_db->query($query, array((int)$limit))->result();

		$synced_count = 0;
		$duplicate_count = 0;
		$errors = array();

		foreach ($sipp_data as $row) {
			try {
				// Check if already exists in berkas_pbt
				$existing = $this->get_pbt_by_nomor($row->nomor_perkara);
				if ($existing) {
					$duplicate_count++;
					continue;
				}

				$data = array(
					'nomor_perkara' => $row->nomor_perkara,
					'perkara_id_sipp' => $row->perkara_id,
					'jenis_perkara' => $row->jenis_perkara,
					'tanggal_putusan' => $row->tanggal_putusan,
					'tanggal_pbt' => $row->tanggal_pbt,
					'tanggal_bht' => $row->tanggal_bht,
					'majelis_hakim' => $row->majelis_hakim,
					'panitera_pengganti' => $row->panitera_pengganti
				);

				$result = $this->insert_berkas_pbt($data);
				if ($result) {
					$synced_count++;
				}
			} catch (Exception $e) {
				$errors[] = $row->nomor_perkara . ': ' . $e->getMessage();
			}
		}

		return array(
			'success' => true,
			'synced_count' => $synced_count,
			'duplicate_count' => $duplicate_count,
			'errors' => $errors,
			'message' => "Sync completed: {$synced_count} new, {$duplicate_count} duplicates"
		);
	}

	/**
	 * Get PBT dashboard stats
	 */
	public function get_pbt_dashboard_stats()
	{
		try {
			$stats = new stdClass();

			// Total PBT
			$stats->total_pbt = $this->notelen_db->count_all('berkas_pbt');

			// Count by status
			$this->notelen_db->where('status_proses', 'Belum PBT');
			$stats->belum_pbt = $this->notelen_db->count_all_results('berkas_pbt');

			$this->notelen_db->where('status_proses', 'Sudah PBT Belum BHT');
			$stats->sudah_pbt_belum_bht = $this->notelen_db->count_all_results('berkas_pbt');

			$this->notelen_db->where('status_proses', 'Selesai');
			$stats->selesai = $this->notelen_db->count_all_results('berkas_pbt');

			// Count duplicates
			$this->notelen_db->where('is_duplicate_berkas', 1);
			$stats->duplicate_count = $this->notelen_db->count_all_results('berkas_pbt');

			return array('pbt' => $stats);
		} catch (Exception $e) {
			return array('pbt' => null);
		}
	}

	/**
	 * Check if nomor perkara exists in berkas_masuk
	 */
	private function check_duplicate_berkas($nomor_perkara)
	{
		$result = $this->notelen_db->get_where('berkas_masuk', array('nomor_perkara' => $nomor_perkara))->row();
		return $result ? true : false;
	}

	/**
	 * Calculate date difference in days
	 */
	private function calculate_date_diff($date1, $date2)
	{
		if (empty($date1) || empty($date2)) {
			return null;
		}

		$datetime1 = new DateTime($date1);
		$datetime2 = new DateTime($date2);
		$diff = $datetime1->diff($datetime2);

		return $diff->days;
	}
}
