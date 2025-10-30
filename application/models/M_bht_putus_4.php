<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model untuk BHT Perkara Putus 4
 * Focus: Pengurutan berdasarkan tanggal putus yang lebih optimal
 * Compatible dengan PHP 5.6
 */
class M_bht_putus_4 extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fungsi untuk mendapatkan data BHT perkara putus dengan pengurutan tanggal
     * Diurutkan berdasarkan: tanggal_putusan DESC (terbaru dulu)
     */
    public function get_bht_putus($jenis_perkara, $lap_bulan, $lap_tahun, $nomor_perkara = '', $order_by = 'tanggal_putus', $order_dir = 'DESC')
    {
        $where_nomor = '';
        if (!empty($nomor_perkara)) {
            // Escape string untuk keamanan
            $nomor_perkara_safe = $this->db->escape_like_str($nomor_perkara);
            $where_nomor = " AND p.nomor_perkara LIKE '%$nomor_perkara_safe%'";
        }

        // Validasi order_by untuk keamanan
        $allowed_order = array('tanggal_putus', 'nomor_perkara', 'jenis_perkara', 'status_bht', 'bht');
        if (!in_array($order_by, $allowed_order)) {
            $order_by = 'tanggal_putus';
        }

        // Validasi order_dir
        $order_dir = strtoupper($order_dir);
        if (!in_array($order_dir, array('ASC', 'DESC'))) {
            $order_dir = 'DESC';
        }

        $query = $this->db->query(
            "SELECT 
            p.perkara_id,
            p.nomor_perkara,
            DATE(pp.tanggal_putusan) as tanggal_putus,
            p.jenis_perkara_nama as jenis_perkara,
            COALESCE(pen.panitera_pengganti_text, '-') as panitera_pengganti_nama,
            COALESCE(pen.jurusita_text, '-') as jurusita_pengganti_nama,
            
            -- Multiple tanggal sidang diformat lebih rapi
            CASE 
                WHEN COUNT(DISTINCT pjs.tanggal_sidang) > 1 THEN 
                    CONCAT(COUNT(DISTINCT pjs.tanggal_sidang), ' sidang: ', 
                           GROUP_CONCAT(DISTINCT DATE_FORMAT(pjs.tanggal_sidang, '%d/%m/%Y') 
                           ORDER BY pjs.tanggal_sidang ASC SEPARATOR ', '))
                WHEN COUNT(DISTINCT pjs.tanggal_sidang) = 1 THEN 
                    DATE_FORMAT(MIN(pjs.tanggal_sidang), '%d/%m/%Y')
                ELSE '-'
            END as pbt_formatted,
            
            -- Tanggal BHT
            DATE(pp.tanggal_bht) as bht,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN DATE_FORMAT(pp.tanggal_bht, '%d/%m/%Y')
                ELSE '-'
            END as bht_formatted,
            
            -- Tanggal Ikrar Talak
            DATE(pit.tgl_ikrar_talak) as ikrar,
            CASE 
                WHEN pit.tgl_ikrar_talak IS NOT NULL THEN DATE_FORMAT(pit.tgl_ikrar_talak, '%d/%m/%Y')
                ELSE '-'
            END as ikrar_formatted,
            
            -- Status BHT dengan logika yang jelas
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SUDAH BHT'
                WHEN pp.tanggal_putusan IS NOT NULL THEN 'BELUM BHT'
                ELSE 'BELUM PUTUS'
            END as status_bht,
            
            -- Status keseluruhan
            CASE 
                WHEN pp.tanggal_putusan IS NOT NULL THEN 'SELESAI'
                ELSE 'PROSES'
            END as status,
            
            -- Hitung selisih hari dari putusan ke BHT
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL AND pp.tanggal_putusan IS NOT NULL THEN 
                    DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan)
                ELSE NULL
            END as selisih_hari_bht,
            
            -- Format tanggal putus untuk display
            DATE_FORMAT(pp.tanggal_putusan, '%d/%m/%Y') as tanggal_putus_formatted,
            
            -- Kategori berdasarkan waktu pembuatan BHT
            CASE 
                WHEN pp.tanggal_bht IS NULL THEN 'Belum Ada BHT'
                WHEN DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) <= 14 THEN 'BHT Tepat Waktu'
                WHEN DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) > 14 THEN 'BHT Terlambat'
                ELSE 'Tidak Diketahui'
            END as kategori_bht

        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
        LEFT JOIN perkara_ikrar_talak pit ON p.perkara_id = pit.perkara_id
        LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
        
        WHERE YEAR(pp.tanggal_putusan) = ? 
        AND MONTH(pp.tanggal_putusan) = ?
        AND p.nomor_perkara LIKE ?
        $where_nomor
        
        GROUP BY p.perkara_id, p.nomor_perkara, pp.tanggal_putusan, p.jenis_perkara_nama, 
                 pen.panitera_pengganti_text, pen.jurusita_text, pp.tanggal_bht, pit.tgl_ikrar_talak
        
        ORDER BY $order_by $order_dir, p.nomor_perkara ASC",
            array($lap_tahun, $lap_bulan, "%$jenis_perkara%")
        );

        return $query->result();
    }

    /**
     * Fungsi untuk mendapatkan data BHT berdasarkan range tanggal
     * Dengan pengurutan yang lebih fleksibel
     */
    public function get_bht_putus_by_date_range($jenis_perkara, $tanggal_awal, $tanggal_akhir, $nomor_perkara = '', $order_by = 'tanggal_putus', $order_dir = 'DESC')
    {
        $where_nomor = '';
        if (!empty($nomor_perkara)) {
            $nomor_perkara_safe = $this->db->escape_like_str($nomor_perkara);
            $where_nomor = " AND p.nomor_perkara LIKE '%$nomor_perkara_safe%'";
        }

        // Validasi parameter sorting
        $allowed_order = array('tanggal_putus', 'nomor_perkara', 'jenis_perkara', 'status_bht', 'bht');
        if (!in_array($order_by, $allowed_order)) {
            $order_by = 'tanggal_putus';
        }

        $order_dir = strtoupper($order_dir);
        if (!in_array($order_dir, array('ASC', 'DESC'))) {
            $order_dir = 'DESC';
        }

        $query = $this->db->query(
            "SELECT 
            p.perkara_id,
            p.nomor_perkara,
            DATE(pp.tanggal_putusan) as tanggal_putus,
            p.jenis_perkara_nama as jenis_perkara,
            COALESCE(pen.panitera_pengganti_text, '-') as panitera_pengganti_nama,
            COALESCE(pen.jurusita_text, '-') as jurusita_pengganti_nama,
            
            -- Multiple tanggal sidang dengan format yang lebih baik
            CASE 
                WHEN COUNT(DISTINCT pjs.tanggal_sidang) > 1 THEN 
                    CONCAT(COUNT(DISTINCT pjs.tanggal_sidang), ' sidang: ', 
                           GROUP_CONCAT(DISTINCT DATE_FORMAT(pjs.tanggal_sidang, '%d/%m/%Y') 
                           ORDER BY pjs.tanggal_sidang ASC SEPARATOR ', '))
                WHEN COUNT(DISTINCT pjs.tanggal_sidang) = 1 THEN 
                    DATE_FORMAT(MIN(pjs.tanggal_sidang), '%d/%m/%Y')
                ELSE '-'
            END as pbt_formatted,
            
            DATE(pp.tanggal_bht) as bht,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN DATE_FORMAT(pp.tanggal_bht, '%d/%m/%Y')
                ELSE '-'
            END as bht_formatted,
            
            DATE(pit.tgl_ikrar_talak) as ikrar,
            CASE 
                WHEN pit.tgl_ikrar_talak IS NOT NULL THEN DATE_FORMAT(pit.tgl_ikrar_talak, '%d/%m/%Y')
                ELSE '-'
            END as ikrar_formatted,
            
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SUDAH BHT'
                WHEN pp.tanggal_putusan IS NOT NULL THEN 'BELUM BHT'
                ELSE 'BELUM PUTUS'
            END as status_bht,
            
            CASE 
                WHEN pp.tanggal_putusan IS NOT NULL THEN 'SELESAI'
                ELSE 'PROSES'
            END as status,
            
            -- Selisih hari
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL AND pp.tanggal_putusan IS NOT NULL THEN 
                    DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan)
                ELSE NULL
            END as selisih_hari_bht,
            
            DATE_FORMAT(pp.tanggal_putusan, '%d/%m/%Y') as tanggal_putus_formatted,
            
            CASE 
                WHEN pp.tanggal_bht IS NULL THEN 'Belum Ada BHT'
                WHEN DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) <= 14 THEN 'BHT Tepat Waktu'
                WHEN DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) > 14 THEN 'BHT Terlambat'
                ELSE 'Tidak Diketahui'
            END as kategori_bht

        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
        LEFT JOIN perkara_ikrar_talak pit ON p.perkara_id = pit.perkara_id
        LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
        
        WHERE DATE(pp.tanggal_putusan) BETWEEN ? AND ?
        AND p.nomor_perkara LIKE ?
        $where_nomor
        
        GROUP BY p.perkara_id, p.nomor_perkara, pp.tanggal_putusan, p.jenis_perkara_nama, 
                 pen.panitera_pengganti_text, pen.jurusita_text, pp.tanggal_bht, pit.tgl_ikrar_talak
        
        ORDER BY $order_by $order_dir, p.nomor_perkara ASC",
            array($tanggal_awal, $tanggal_akhir, "%$jenis_perkara%")
        );

        return $query->result();
    }

    /**
     * Statistik BHT dengan perhitungan yang lebih detail
     */
    public function get_statistik_bht($jenis_perkara, $lap_bulan, $lap_tahun, $nomor_perkara = '')
    {
        $where_nomor = '';
        if (!empty($nomor_perkara)) {
            $nomor_perkara_safe = $this->db->escape_like_str($nomor_perkara);
            $where_nomor = " AND p.nomor_perkara LIKE '%$nomor_perkara_safe%'";
        }

        // Query komprehensif untuk semua statistik sekaligus
        $query = $this->db->query(
            "SELECT 
            COUNT(*) as total_putus,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as sudah_bht,
            SUM(CASE WHEN pp.tanggal_putusan IS NOT NULL AND pp.tanggal_bht IS NULL THEN 1 ELSE 0 END) as belum_bht,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL AND DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) <= 14 THEN 1 ELSE 0 END) as bht_tepat_waktu,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL AND DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) > 14 THEN 1 ELSE 0 END) as bht_terlambat,
            ROUND(AVG(CASE WHEN pp.tanggal_bht IS NOT NULL THEN DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) ELSE NULL END), 1) as rata_rata_hari_bht
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE YEAR(pp.tanggal_putusan) = ? 
        AND MONTH(pp.tanggal_putusan) = ?
        AND p.nomor_perkara LIKE ?
        $where_nomor",
            array($lap_tahun, $lap_bulan, "%$jenis_perkara%")
        );

        $result_data = $query->row();

        if ($result_data) {
            $total_putus = (int)$result_data->total_putus;
            $sudah_bht = (int)$result_data->sudah_bht;

            $result = array(
                'total_putus' => $total_putus,
                'sudah_bht' => $sudah_bht,
                'belum_bht' => (int)$result_data->belum_bht,
                'bht_tepat_waktu' => (int)$result_data->bht_tepat_waktu,
                'bht_terlambat' => (int)$result_data->bht_terlambat,
                'rata_rata_hari_bht' => $result_data->rata_rata_hari_bht ? (float)$result_data->rata_rata_hari_bht : 0,
                'persentase_bht' => $total_putus > 0 ? round(($sudah_bht / $total_putus) * 100, 2) : 0,
                'persentase_tepat_waktu' => $sudah_bht > 0 ? round(((int)$result_data->bht_tepat_waktu / $sudah_bht) * 100, 2) : 0
            );
        } else {
            $result = array(
                'total_putus' => 0,
                'sudah_bht' => 0,
                'belum_bht' => 0,
                'bht_tepat_waktu' => 0,
                'bht_terlambat' => 0,
                'rata_rata_hari_bht' => 0,
                'persentase_bht' => 0,
                'persentase_tepat_waktu' => 0
            );
        }

        return $result;
    }

    /**
     * Statistik BHT berdasarkan range tanggal
     */
    public function get_statistik_bht_by_date_range($jenis_perkara, $tanggal_awal, $tanggal_akhir, $nomor_perkara = '')
    {
        $where_nomor = '';
        if (!empty($nomor_perkara)) {
            $nomor_perkara_safe = $this->db->escape_like_str($nomor_perkara);
            $where_nomor = " AND p.nomor_perkara LIKE '%$nomor_perkara_safe%'";
        }

        $query = $this->db->query(
            "SELECT 
            COUNT(*) as total_putus,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as sudah_bht,
            SUM(CASE WHEN pp.tanggal_putusan IS NOT NULL AND pp.tanggal_bht IS NULL THEN 1 ELSE 0 END) as belum_bht,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL AND DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) <= 14 THEN 1 ELSE 0 END) as bht_tepat_waktu,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL AND DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) > 14 THEN 1 ELSE 0 END) as bht_terlambat,
            ROUND(AVG(CASE WHEN pp.tanggal_bht IS NOT NULL THEN DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) ELSE NULL END), 1) as rata_rata_hari_bht
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE DATE(pp.tanggal_putusan) BETWEEN ? AND ?
        AND p.nomor_perkara LIKE ?
        $where_nomor",
            array($tanggal_awal, $tanggal_akhir, "%$jenis_perkara%")
        );

        $result_data = $query->row();

        if ($result_data) {
            $total_putus = (int)$result_data->total_putus;
            $sudah_bht = (int)$result_data->sudah_bht;

            $result = array(
                'total_putus' => $total_putus,
                'sudah_bht' => $sudah_bht,
                'belum_bht' => (int)$result_data->belum_bht,
                'bht_tepat_waktu' => (int)$result_data->bht_tepat_waktu,
                'bht_terlambat' => (int)$result_data->bht_terlambat,
                'rata_rata_hari_bht' => $result_data->rata_rata_hari_bht ? (float)$result_data->rata_rata_hari_bht : 0,
                'persentase_bht' => $total_putus > 0 ? round(($sudah_bht / $total_putus) * 100, 2) : 0,
                'persentase_tepat_waktu' => $sudah_bht > 0 ? round(((int)$result_data->bht_tepat_waktu / $sudah_bht) * 100, 2) : 0
            );
        } else {
            $result = array(
                'total_putus' => 0,
                'sudah_bht' => 0,
                'belum_bht' => 0,
                'bht_tepat_waktu' => 0,
                'bht_terlambat' => 0,
                'rata_rata_hari_bht' => 0,
                'persentase_bht' => 0,
                'persentase_tepat_waktu' => 0
            );
        }

        return $result;
    }

    /**
     * Mendapatkan data untuk chart timeline
     */
    public function get_chart_timeline_data($jenis_perkara, $lap_bulan, $lap_tahun)
    {
        $query = $this->db->query(
            "SELECT 
            DATE(pp.tanggal_putusan) as tanggal,
            COUNT(*) as jumlah_putus,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as jumlah_bht
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE YEAR(pp.tanggal_putusan) = ? 
        AND MONTH(pp.tanggal_putusan) = ?
        AND p.nomor_perkara LIKE ?
        GROUP BY DATE(pp.tanggal_putusan)
        ORDER BY DATE(pp.tanggal_putusan) ASC",
            array($lap_tahun, $lap_bulan, "%$jenis_perkara%")
        );

        return $query->result();
    }
}
