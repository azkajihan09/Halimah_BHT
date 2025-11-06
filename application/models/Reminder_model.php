<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model untuk sistem pencatatan reminder BHT
 * Menggunakan database terpisah: bht_reminder_system
 * 
 * Fungsi utama:
 * - Mengelola data perkara yang perlu reminder PBT/BHT
 * - Sinkronisasi data dari database SIPP
 * - Tracking status dan prioritas reminder
 * - Generate statistik dan laporan
 */
class Reminder_model extends CI_Model
{
    private $reminder_db;
    private $sipp_db;

    public function __construct()
    {
        parent::__construct();

        // Load database reminder (database kedua)
        $this->reminder_db = $this->load->database('reminder_db', TRUE);

        // Load database SIPP (database utama)
        $this->sipp_db = $this->load->database('default', TRUE);
    }

    // ===============================================
    // CRUD OPERATIONS - PERKARA REMINDER
    // ===============================================

    /**
     * Ambil semua perkara reminder dengan filter
     */
    public function get_perkara_reminder($limit = null, $offset = 0, $filters = array())
    {
        $this->reminder_db->select('
            pr.*,
            pt.status_pbt,
            pt.tanggal_bayar_pbt,
            pt.tanggal_pemberitahuan_putusan,
            CASE 
                WHEN pr.hari_sejak_putusan > 21 THEN "CRITICAL"
                WHEN pr.hari_sejak_putusan > 14 THEN "KRITIS"
                WHEN pr.hari_sejak_putusan > 10 THEN "PERINGATAN"
                ELSE "NORMAL"
            END as level_urgency
        ', FALSE); // FALSE prevents escaping

        $this->reminder_db->from('perkara_reminder pr');
        $this->reminder_db->join('pbt_tracking pt', 'pr.id = pt.perkara_reminder_id', 'left');

        // Apply filters
        if (!empty($filters['status_reminder'])) {
            $this->reminder_db->where('pr.status_reminder', $filters['status_reminder']);
        }

        if (!empty($filters['level_prioritas'])) {
            $this->reminder_db->where('pr.level_prioritas', $filters['level_prioritas']);
        }

        if (!empty($filters['jenis_perkara'])) {
            $this->reminder_db->like('pr.jenis_perkara', $filters['jenis_perkara']);
        }

        if (!empty($filters['tanggal_dari']) && !empty($filters['tanggal_sampai'])) {
            $this->reminder_db->where('pr.tanggal_putusan >=', $filters['tanggal_dari']);
            $this->reminder_db->where('pr.tanggal_putusan <=', $filters['tanggal_sampai']);
        }

        $this->reminder_db->order_by('pr.hari_sejak_putusan', 'DESC');

        if ($limit) {
            $this->reminder_db->limit($limit, $offset);
        }

        return $this->reminder_db->get()->result();
    }

    /**
     * Hitung total perkara reminder
     */
    public function count_perkara_reminder($filters = array())
    {
        $this->reminder_db->from('perkara_reminder pr');

        // Apply same filters as get_perkara_reminder
        if (!empty($filters['status_reminder'])) {
            $this->reminder_db->where('pr.status_reminder', $filters['status_reminder']);
        }

        if (!empty($filters['level_prioritas'])) {
            $this->reminder_db->where('pr.level_prioritas', $filters['level_prioritas']);
        }

        if (!empty($filters['jenis_perkara'])) {
            $this->reminder_db->like('pr.jenis_perkara', $filters['jenis_perkara']);
        }

        if (!empty($filters['tanggal_dari']) && !empty($filters['tanggal_sampai'])) {
            $this->reminder_db->where('pr.tanggal_putusan >=', $filters['tanggal_dari']);
            $this->reminder_db->where('pr.tanggal_putusan <=', $filters['tanggal_sampai']);
        }

        return $this->reminder_db->count_all_results();
    }

    /**
     * Ambil perkara reminder berdasarkan nomor perkara
     */
    public function get_perkara_by_nomor($nomor_perkara)
    {
        $this->reminder_db->select('
            pr.*,
            pt.status_pbt,
            pt.tanggal_bayar_pbt,
            pt.tanggal_pemberitahuan_putusan,
            pt.jumlah_biaya,
            pt.uraian_biaya
        ');

        $this->reminder_db->from('perkara_reminder pr');
        $this->reminder_db->join('pbt_tracking pt', 'pr.id = pt.perkara_reminder_id', 'left');
        $this->reminder_db->where('pr.nomor_perkara', $nomor_perkara);

        return $this->reminder_db->get()->row();
    }

    /**
     * Get perkara by ID
     */
    public function get_perkara_by_id($id)
    {
        $this->reminder_db->select('
            pr.*,
            pt.status_pbt,
            pt.tanggal_bayar_pbt,
            pt.tanggal_pemberitahuan_putusan,
            pt.jumlah_biaya,
            pt.uraian_biaya
        ');

        $this->reminder_db->from('perkara_reminder pr');
        $this->reminder_db->join('pbt_tracking pt', 'pr.id = pt.perkara_reminder_id', 'left');
        $this->reminder_db->where('pr.id', $id);

        return $this->reminder_db->get()->row();
    }

    /**
     * Insert perkara reminder baru
     */
    public function insert_perkara_reminder($data)
    {
        // Check if perkara already exists
        $existing = $this->reminder_db->get_where('perkara_reminder', array('nomor_perkara' => $data['nomor_perkara']))->row();
        if ($existing) {
            // If exists, just return existing ID (skip insert)
            return $existing->id;
        }

        // Insert ke tabel perkara_reminder
        $reminder_data = array(
            'nomor_perkara' => $data['nomor_perkara'],
            'perkara_id_sipp' => $data['perkara_id_sipp'],
            'jenis_perkara' => $data['jenis_perkara'],
            'tanggal_putusan' => $data['tanggal_putusan'],
            'tanggal_registrasi' => $data['tanggal_registrasi'],
            'status_reminder' => isset($data['status_reminder']) ? $data['status_reminder'] : 'BELUM_PBT',
            'level_prioritas' => $this->calculate_priority_level($data['tanggal_putusan']),
            'hari_sejak_putusan' => $this->calculate_days_since_decision($data['tanggal_putusan']),
            'majelis_hakim' => isset($data['majelis_hakim']) ? $data['majelis_hakim'] : null,
            'jurusita_1' => isset($data['jurusita_1']) ? $data['jurusita_1'] : null,
            'jurusita_2' => isset($data['jurusita_2']) ? $data['jurusita_2'] : null,
            'catatan_reminder' => isset($data['catatan_reminder']) ? $data['catatan_reminder'] : null,
            'last_sync_sipp' => date('Y-m-d H:i:s')
        );

        $this->reminder_db->insert('perkara_reminder', $reminder_data);
        $perkara_reminder_id = $this->reminder_db->insert_id();

        // Insert ke tabel PBT tracking jika ada data biaya
        if (!empty($data['pbt_data'])) {
            $pbt_data = array(
                'perkara_reminder_id' => $perkara_reminder_id,
                'nomor_perkara' => $data['nomor_perkara'],
                'tanggal_bayar_pbt' => isset($data['pbt_data']['tanggal_bayar_pbt']) ? $data['pbt_data']['tanggal_bayar_pbt'] : null,
                'jumlah_biaya' => isset($data['pbt_data']['jumlah_biaya']) ? $data['pbt_data']['jumlah_biaya'] : null,
                'uraian_biaya' => isset($data['pbt_data']['uraian_biaya']) ? $data['pbt_data']['uraian_biaya'] : null,
                'pihak_id' => isset($data['pbt_data']['pihak_id']) ? $data['pbt_data']['pihak_id'] : null,
                'tanggal_pemberitahuan_putusan' => isset($data['pbt_data']['tanggal_pemberitahuan_putusan']) ? $data['pbt_data']['tanggal_pemberitahuan_putusan'] : null,
                'status_pbt' => isset($data['pbt_data']['status_pbt']) ? $data['pbt_data']['status_pbt'] : 'BELUM_BAYAR'
            );

            $this->reminder_db->insert('pbt_tracking', $pbt_data);
        }

        // Log activity
        $this->log_reminder_activity($perkara_reminder_id, $data['nomor_perkara'], 'CREATED', null, null, 'Perkara ditambahkan ke sistem reminder');

        return $perkara_reminder_id;
    }

    /**
     * Update status perkara reminder
     */
    public function update_status_reminder($id, $new_status, $catatan = null)
    {
        // Get current data
        $current = $this->reminder_db->get_where('perkara_reminder', array('id' => $id))->row();

        if (!$current) {
            return false;
        }

        // Update data
        $update_data = array(
            'status_reminder' => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($catatan) {
            $update_data['catatan_reminder'] = $catatan;
        }

        // Update prioritas jika status berubah
        if ($new_status != 'SELESAI') {
            $update_data['hari_sejak_putusan'] = $this->calculate_days_since_decision($current->tanggal_putusan);
            $update_data['level_prioritas'] = $this->calculate_priority_level($current->tanggal_putusan);
        }

        $this->reminder_db->where('id', $id);
        $result = $this->reminder_db->update('perkara_reminder', $update_data);

        if ($result) {
            // Log activity (trigger akan handle ini)
            $this->log_reminder_activity($id, $current->nomor_perkara, 'STATUS_CHANGE', $current->status_reminder, $new_status, $catatan);
        }

        return $result;
    }

    // ===============================================
    // SINKRONISASI DATA DARI SIPP
    // ===============================================

    /**
     * Sinkronisasi data perkara dari database SIPP ke reminder database
     */
    public function sync_from_sipp($limit = 100)
    {
        // Get perkara yang perlu reminder dari database SIPP
        $query = "
            SELECT 
                p.perkara_id,
                p.nomor_perkara,
                p.jenis_perkara_nama as jenis_perkara,
                p.tanggal_pendaftaran as tanggal_registrasi,
                pp.tanggal_putusan,
                COALESCE(pen.majelis_hakim_nama, '-') as majelis_hakim,
                pj.jurusita_1,
                pj.jurusita_2,
                pb.tanggal_transaksi as tanggal_bayar_pbt,
                pb.jumlah as jumlah_biaya,
                pb.uraian as uraian_biaya,
                pb.pihak_id,
                pppp_check.tanggal_pbt as tanggal_pemberitahuan_putusan,
                CASE 
                    WHEN pb.tanggal_transaksi IS NOT NULL AND pppp_check.tanggal_pbt IS NULL THEN 'SUDAH_BAYAR_BELUM_PBT'
                    WHEN pb.tanggal_transaksi IS NULL AND pppp_check.tanggal_pbt IS NULL THEN 'BELUM_BAYAR'
                    WHEN pppp_check.tanggal_pbt IS NOT NULL AND pp.tanggal_bht IS NULL THEN 'SUDAH_PBT'
                    ELSE 'SELESAI'
                END as status_pbt_sipp
            FROM perkara p
            INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
            LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
            LEFT JOIN perkara_biaya pb ON p.perkara_id = pb.perkara_id AND pb.kategori_id = 6
            LEFT JOIN (
                SELECT perkara_id, MIN(tanggal_pemberitahuan_putusan) as tanggal_pbt 
                FROM perkara_putusan_pemberitahuan_putusan 
                WHERE pihak = '2' 
                GROUP BY perkara_id
            ) pppp_check ON p.perkara_id = pppp_check.perkara_id
            LEFT JOIN (
                SELECT perkara_id,
                       MAX(CASE WHEN urutan = '1' THEN jurusita_nama ELSE NULL END) as jurusita_1,
                       MAX(CASE WHEN urutan = '2' THEN jurusita_nama ELSE NULL END) as jurusita_2
                FROM perkara_jurusita 
                WHERE aktif = 'Y' AND urutan IN ('1', '2')
                GROUP BY perkara_id
            ) pj ON p.perkara_id = pj.perkara_id
            WHERE pp.tanggal_putusan IS NOT NULL
            AND pp.tanggal_cabut IS NULL
            AND YEAR(pp.tanggal_putusan) >= 2024
            AND (
                (pb.tanggal_transaksi IS NOT NULL AND pppp_check.tanggal_pbt IS NULL) OR
                (pb.tanggal_transaksi IS NULL AND pppp_check.tanggal_pbt IS NULL) OR
                (pppp_check.tanggal_pbt IS NOT NULL AND pp.tanggal_bht IS NULL)
            )
            AND p.nomor_perkara NOT IN (
                SELECT nomor_perkara FROM bht_reminder_system.perkara_reminder
            )
            ORDER BY pp.tanggal_putusan DESC
            LIMIT ?
        ";

        $sipp_data = $this->sipp_db->query($query, array($limit))->result();

        $synced_count = 0;
        $errors = array();

        foreach ($sipp_data as $row) {
            try {
                // Prepare data untuk insert
                $reminder_data = array(
                    'nomor_perkara' => $row->nomor_perkara,
                    'perkara_id_sipp' => $row->perkara_id,
                    'jenis_perkara' => $row->jenis_perkara,
                    'tanggal_putusan' => $row->tanggal_putusan,
                    'tanggal_registrasi' => $row->tanggal_registrasi,
                    'majelis_hakim' => $row->majelis_hakim,
                    'jurusita_1' => $row->jurusita_1,
                    'jurusita_2' => $row->jurusita_2,
                    'status_reminder' => $this->map_status_sipp_to_reminder($row->status_pbt_sipp),
                    'pbt_data' => array(
                        'tanggal_bayar_pbt' => $row->tanggal_bayar_pbt,
                        'jumlah_biaya' => $row->jumlah_biaya,
                        'uraian_biaya' => $row->uraian_biaya,
                        'pihak_id' => $row->pihak_id,
                        'tanggal_pemberitahuan_putusan' => $row->tanggal_pemberitahuan_putusan,
                        'status_pbt' => $row->status_pbt_sipp
                    )
                );

                // Insert ke reminder database
                $this->insert_perkara_reminder($reminder_data);
                $synced_count++;
            } catch (Exception $e) {
                $errors[] = "Error sync perkara {$row->nomor_perkara}: " . $e->getMessage();
            }
        }

        // Update timestamp sync terakhir
        $this->update_config('last_sync_timestamp', date('Y-m-d H:i:s'));

        return array(
            'synced_count' => $synced_count,
            'total_processed' => count($sipp_data),
            'errors' => $errors
        );
    }

    /**
     * Update existing reminder data dari SIPP
     */
    public function update_from_sipp($nomor_perkara = null)
    {
        // Query untuk update data yang sudah ada
        $where_clause = $nomor_perkara ? "AND p.nomor_perkara = ?" : "";
        $params = $nomor_perkara ? array($nomor_perkara) : array();

        $query = "
            SELECT 
                p.perkara_id,
                p.nomor_perkara,
                pp.tanggal_putusan,
                pp.tanggal_bht,
                pb.tanggal_transaksi as tanggal_bayar_pbt,
                pppp_check.tanggal_pbt as tanggal_pemberitahuan_putusan,
                CASE 
                    WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI'
                    WHEN pb.tanggal_transaksi IS NOT NULL AND pppp_check.tanggal_pbt IS NULL THEN 'SUDAH_PBT_BELUM_BHT'
                    WHEN pb.tanggal_transaksi IS NOT NULL AND pppp_check.tanggal_pbt IS NOT NULL THEN 'SUDAH_PBT_BELUM_BHT'
                    ELSE 'BELUM_PBT'
                END as new_status
            FROM perkara p
            INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
            LEFT JOIN perkara_biaya pb ON p.perkara_id = pb.perkara_id AND pb.kategori_id = 6
            LEFT JOIN (
                SELECT perkara_id, MIN(tanggal_pemberitahuan_putusan) as tanggal_pbt 
                FROM perkara_putusan_pemberitahuan_putusan 
                WHERE pihak = '2' 
                GROUP BY perkara_id
            ) pppp_check ON p.perkara_id = pppp_check.perkara_id
            WHERE p.nomor_perkara IN (
                SELECT nomor_perkara FROM bht_reminder_system.perkara_reminder 
                WHERE status_reminder != 'SELESAI'
            )
            {$where_clause}
        ";

        $sipp_updates = $this->sipp_db->query($query, $params)->result();

        $updated_count = 0;

        foreach ($sipp_updates as $row) {
            // Update status di reminder database
            $this->reminder_db->where('nomor_perkara', $row->nomor_perkara);
            $this->reminder_db->update('perkara_reminder', array(
                'status_reminder' => $row->new_status,
                'hari_sejak_putusan' => $this->calculate_days_since_decision($row->tanggal_putusan),
                'level_prioritas' => $this->calculate_priority_level($row->tanggal_putusan),
                'last_sync_sipp' => date('Y-m-d H:i:s')
            ));

            // Update PBT tracking
            $this->reminder_db->where('nomor_perkara', $row->nomor_perkara);
            $this->reminder_db->update('pbt_tracking', array(
                'tanggal_bayar_pbt' => $row->tanggal_bayar_pbt,
                'tanggal_pemberitahuan_putusan' => $row->tanggal_pemberitahuan_putusan,
                'status_pbt' => $this->map_status_sipp_to_pbt($row->new_status)
            ));

            $updated_count++;
        }

        return $updated_count;
    }

    // ===============================================
    // HELPER FUNCTIONS
    // ===============================================

    /**
     * Hitung level prioritas berdasarkan tanggal putusan
     */
    private function calculate_priority_level($tanggal_putusan)
    {
        $days = $this->calculate_days_since_decision($tanggal_putusan);

        if ($days > 21) return 'CRITICAL';
        if ($days > 14) return 'KRITIS';
        if ($days > 10) return 'PERINGATAN';
        return 'NORMAL';
    }

    /**
     * Hitung hari sejak putusan
     */
    private function calculate_days_since_decision($tanggal_putusan)
    {
        $now = new DateTime();
        $decision_date = new DateTime($tanggal_putusan);
        return $now->diff($decision_date)->days;
    }

    /**
     * Map status dari SIPP ke reminder status
     */
    private function map_status_sipp_to_reminder($status_sipp)
    {
        switch ($status_sipp) {
            case 'BELUM_BAYAR':
            case 'SUDAH_BAYAR_BELUM_PBT':
                return 'BELUM_PBT';
            case 'SUDAH_PBT':
                return 'SUDAH_PBT_BELUM_BHT';
            case 'SELESAI':
                return 'SELESAI';
            default:
                return 'BELUM_PBT';
        }
    }

    /**
     * Map status reminder ke PBT status
     */
    private function map_status_sipp_to_pbt($status_reminder)
    {
        switch ($status_reminder) {
            case 'BELUM_PBT':
                return 'BELUM_BAYAR';
            case 'SUDAH_PBT_BELUM_BHT':
                return 'SUDAH_PBT';
            case 'SELESAI':
                return 'SUDAH_PBT';
            default:
                return 'BELUM_BAYAR';
        }
    }

    /**
     * Log activity reminder
     */
    private function log_reminder_activity($perkara_reminder_id, $nomor_perkara, $activity_type, $old_status = null, $new_status = null, $description = null)
    {
        $log_data = array(
            'perkara_reminder_id' => $perkara_reminder_id,
            'activity_type' => $activity_type,
            'description' => $description,
            'old_value' => $old_status,
            'new_value' => $new_status,
            'user_name' => 'SYSTEM'
        );

        $this->reminder_db->insert('reminder_log', $log_data);
    }

    // ===============================================
    // STATISTIK DAN DASHBOARD
    // ===============================================

    /**
     * Get dashboard statistics
     */
    public function get_dashboard_stats()
    {
        // Statistics dari view
        $stats = $this->reminder_db->get('v_reminder_dashboard')->result();

        // Summary total
        $summary = $this->reminder_db->select('
            COUNT(*) as total_perkara,
            SUM(CASE WHEN status_reminder = "BELUM_PBT" THEN 1 ELSE 0 END) as total_belum_pbt,
            SUM(CASE WHEN status_reminder = "SUDAH_PBT_BELUM_BHT" THEN 1 ELSE 0 END) as total_sudah_pbt_belum_bht,
            SUM(CASE WHEN status_reminder = "SELESAI" THEN 1 ELSE 0 END) as total_selesai,
            SUM(CASE WHEN level_prioritas = "CRITICAL" THEN 1 ELSE 0 END) as total_critical,
            SUM(CASE WHEN level_prioritas = "KRITIS" THEN 1 ELSE 0 END) as total_kritis,
            SUM(CASE WHEN level_prioritas = "PERINGATAN" THEN 1 ELSE 0 END) as total_peringatan,
            SUM(CASE WHEN level_prioritas = "NORMAL" THEN 1 ELSE 0 END) as total_normal
        ', FALSE)->get('perkara_reminder')->row();

        return array(
            'details' => $stats,
            'summary' => $summary
        );
    }

    /**
     * Get perkara urgent untuk alert
     */
    public function get_urgent_alerts($limit = 10)
    {
        return $this->reminder_db->select('*')
            ->from('v_perkara_urgent')
            ->limit($limit)
            ->get()->result();
    }

    /**
     * Get/Set config
     */
    public function get_config($key)
    {
        $result = $this->reminder_db->get_where('reminder_config', array('config_key' => $key))->row();
        return $result ? $result->config_value : null;
    }

    public function update_config($key, $value)
    {
        $data = array(
            'config_key' => $key,
            'config_value' => $value,
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->reminder_db->replace('reminder_config', $data);
    }
}
