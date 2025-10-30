<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_bht_putus_2 extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// Fungsi untuk mendapatkan data BHT perkara putus
	function get_bht_putus($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		$query = $this->db->query("SELECT 
            p.nomor_perkara,
            DATE(pp.tanggal_putusan) as tanggal_putus,
            p.jenis_perkara_nama as jenis_perkara,
            COALESCE(pen.panitera_pengganti_text, '-') as panitera_pengganti_nama,
            COALESCE(pen.jurusita_text, '-') as jurusita_pengganti_nama,
            GROUP_CONCAT(DISTINCT DATE(pjs.tanggal_sidang) ORDER BY pjs.tanggal_sidang SEPARATOR '<br>') as pbt,
            DATE(pp.tanggal_bht) as bht,
            DATE(pit.tgl_ikrar_talak) as ikrar,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SUDAH BHT'
                WHEN pp.tanggal_putusan IS NOT NULL THEN 'BELUM BHT'
                ELSE 'BELUM PUTUS'
            END as status_bht,
            CASE 
                WHEN pp.tanggal_putusan IS NOT NULL THEN 'SELESAI'
                ELSE 'PROSES'
            END as status
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
        LEFT JOIN perkara_ikrar_talak pit ON p.perkara_id = pit.perkara_id
        LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
        WHERE YEAR(pp.tanggal_putusan) = '$lap_tahun' 
        AND MONTH(pp.tanggal_putusan) = '$lap_bulan'
        AND p.nomor_perkara LIKE '%$jenis_perkara%'
        GROUP BY p.perkara_id, p.nomor_perkara, pp.tanggal_putusan, p.jenis_perkara_nama, 
                 pen.panitera_pengganti_text, pen.jurusita_text, pp.tanggal_bht, pit.tgl_ikrar_talak
        ORDER BY pp.tanggal_bht DESC, pp.tanggal_putusan DESC, p.nomor_perkara ASC");

		return $query->result();
	}

	// Fungsi untuk statistik BHT
	function get_statistik_bht($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Total perkara putus
		$total_putus = $this->db->query("SELECT COUNT(*) as total
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE YEAR(pp.tanggal_putusan) = '$lap_tahun' 
        AND MONTH(pp.tanggal_putusan) = '$lap_bulan'
        AND p.nomor_perkara LIKE '%$jenis_perkara%'");

		// Total sudah BHT
		$sudah_bht = $this->db->query("SELECT COUNT(*) as total
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE YEAR(pp.tanggal_putusan) = '$lap_tahun' 
        AND MONTH(pp.tanggal_putusan) = '$lap_bulan'
        AND p.nomor_perkara LIKE '%$jenis_perkara%'
        AND pp.tanggal_bht IS NOT NULL");

		// Total belum BHT
		$belum_bht = $this->db->query("SELECT COUNT(*) as total
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE YEAR(pp.tanggal_putusan) = '$lap_tahun' 
        AND MONTH(pp.tanggal_putusan) = '$lap_bulan'
        AND p.nomor_perkara LIKE '%$jenis_perkara%'
        AND pp.tanggal_putusan IS NOT NULL
        AND pp.tanggal_bht IS NULL");
		$total_putus_result = $total_putus->row();
		$sudah_bht_result = $sudah_bht->row();
		$belum_bht_result = $belum_bht->row();

		$result = array(
			'total_putus' => $total_putus_result ? $total_putus_result->total : 0,
			'sudah_bht' => $sudah_bht_result ? $sudah_bht_result->total : 0,
			'belum_bht' => $belum_bht_result ? $belum_bht_result->total : 0,
			'persentase_bht' => $total_putus_result && $total_putus_result->total > 0 ?
				round(($sudah_bht_result->total / $total_putus_result->total) * 100, 2) : 0
		);

		return $result;
	}
}
