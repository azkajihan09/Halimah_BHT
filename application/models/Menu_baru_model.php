<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_baru_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * 1. Get perkara putus harian
	 */
	public function get_perkara_putus_harian($tanggal)
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            DATE(pp.tanggal_putusan) as tanggal_putus,
            COALESCE(pen.majelis_hakim_nama, '-') as hakim,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'Sudah BHT'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 21 THEN 'Critical - Belum BHT'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 THEN 'Terlambat - Belum BHT'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 10 THEN 'Urgent - Belum BHT'
                ELSE 'Belum BHT'
            END as status_bht,
            p.perkara_id,
            pp.tanggal_bht,
            DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY) as target_bht,
            DATEDIFF(CURDATE(), pp.tanggal_putusan) as hari_sejak_putus,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 21 THEN 'CRITICAL'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 THEN 'TERLAMBAT'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 10 THEN 'URGENT'
                ELSE 'NORMAL'
            END as kategori_status
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('DATE(pp.tanggal_putusan)', $tanggal);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		$this->db->order_by('pp.tanggal_putusan', 'DESC');

		return $this->db->get()->result();
	}

	public function count_perkara_putus_harian($tanggal)
	{
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('DATE(pp.tanggal_putusan)', $tanggal);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		return $this->db->count_all_results();
	}

	/**
	 * 2. Get tanggal PBT dan BHT
	 */
	public function get_tanggal_pbt_bht($bulan)
	{
		$this->db->select("
            p.nomor_perkara,
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
            END as status_proses
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('DATE_FORMAT(pp.tanggal_putusan, "%Y-%m") =', $bulan);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		$this->db->order_by('pp.tanggal_putusan', 'DESC');

		return $this->db->get()->result();
	}

	public function get_kalender_pbt_bht($bulan)
	{
		// Data untuk kalender view
		$this->db->select('
            DATE(pp.tanggal_putusan) as tanggal,
            COUNT(*) as total_putus,
            SUM(CASE WHEN pjs.tanggal_sidang IS NOT NULL THEN 1 ELSE 0 END) as total_pbt,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as total_bht
        ');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('DATE_FORMAT(pp.tanggal_putusan, "%Y-%m") =', $bulan);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		$this->db->group_by('DATE(pp.tanggal_putusan)');
		$this->db->order_by('pp.tanggal_putusan', 'ASC');

		return $this->db->get()->result();
	}

	/**
	 * 3. Get jadwal BHT harian
	 */
	public function get_jadwal_bht_harian($tanggal, $jenis = 'semua', $tahun_filter = '2025')
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            DATE(pp.tanggal_putusan) as tanggal_putusan,
            DATE(pjs.tanggal_sidang) as tanggal_pbt,
            pp.tanggal_bht,
            p.perkara_id,
            COALESCE(pen.majelis_hakim_nama, '-') as hakim,
            
            -- Target BHT Calculation (Complex)
            CASE 
                WHEN pjs.tanggal_sidang IS NOT NULL THEN DATE_ADD(pjs.tanggal_sidang, INTERVAL 14 DAY)
                WHEN pp.tanggal_putusan IS NOT NULL THEN DATE_ADD(pp.tanggal_putusan, INTERVAL 28 DAY)
                ELSE NULL
            END as target_bht,
            
            -- Hari Sejak PBT (Complex Calculation)
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN DATEDIFF(pp.tanggal_bht, pjs.tanggal_sidang)
                WHEN pjs.tanggal_sidang IS NOT NULL THEN DATEDIFF(CURDATE(), pjs.tanggal_sidang)
                ELSE DATEDIFF(CURDATE(), pp.tanggal_putusan)
            END as hari_sejak_pbt,
            
            -- Remaining Days to Target BHT
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 0
                WHEN pjs.tanggal_sidang IS NOT NULL THEN 
                    GREATEST(0, DATEDIFF(DATE_ADD(pjs.tanggal_sidang, INTERVAL 14 DAY), CURDATE()))
                ELSE 
                    GREATEST(0, DATEDIFF(DATE_ADD(pp.tanggal_putusan, INTERVAL 28 DAY), CURDATE()))
            END as sisa_hari_target,
            
            -- Days Overdue from Target
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 0
                WHEN pjs.tanggal_sidang IS NOT NULL THEN 
                    GREATEST(0, DATEDIFF(CURDATE(), DATE_ADD(pjs.tanggal_sidang, INTERVAL 14 DAY)))
                ELSE 
                    GREATEST(0, DATEDIFF(CURDATE(), DATE_ADD(pp.tanggal_putusan, INTERVAL 28 DAY)))
            END as hari_terlambat,
            
            -- Status (Complex Logic)
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'Selesai BHT'
                WHEN pjs.tanggal_sidang IS NULL THEN 
                    CASE 
                        WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 21 THEN 'Critical - Menunggu PBT'
                        WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 THEN 'Urgent - Menunggu PBT'
                        ELSE 'Menunggu PBT'
                    END
                WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 21 THEN 'Critical - Terlambat BHT'
                WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'Terlambat BHT'
                WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'Urgent BHT'
                ELSE 'Normal - Menunggu BHT'
            END as status,
            
            -- Priority Level (Complex)
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'COMPLETED'
                WHEN pjs.tanggal_sidang IS NULL AND DATEDIFF(CURDATE(), pp.tanggal_putusan) > 21 THEN 'CRITICAL_PBT'
                WHEN pjs.tanggal_sidang IS NULL AND DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 THEN 'HIGH_PBT'
                WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 21 THEN 'CRITICAL_BHT'
                WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'HIGH_BHT'
                WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'MEDIUM_BHT'
                ELSE 'LOW'
            END as prioritas,
            
            -- Progress Percentage
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 100
                WHEN pjs.tanggal_sidang IS NOT NULL THEN 
                    LEAST(100, GREATEST(0, 
                        ROUND((DATEDIFF(CURDATE(), pjs.tanggal_sidang) / 14.0) * 100, 0)
                    ))
                ELSE 
                    LEAST(50, GREATEST(0, 
                        ROUND((DATEDIFF(CURDATE(), pp.tanggal_putusan) / 28.0) * 50, 0)
                    ))
            END as progress_percentage,
            
            -- Working Days Calculation (excluding weekends)
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 
                    DATEDIFF(pp.tanggal_bht, pjs.tanggal_sidang) - 
                    (WEEK(pp.tanggal_bht) - WEEK(pjs.tanggal_sidang)) * 2
                WHEN pjs.tanggal_sidang IS NOT NULL THEN 
                    DATEDIFF(CURDATE(), pjs.tanggal_sidang) - 
                    (WEEK(CURDATE()) - WEEK(pjs.tanggal_sidang)) * 2
                ELSE 
                    DATEDIFF(CURDATE(), pp.tanggal_putusan) - 
                    (WEEK(CURDATE()) - WEEK(pp.tanggal_putusan)) * 2
            END as hari_kerja_sejak_pbt,
            
            -- Efficiency Score (0-100)
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL AND pjs.tanggal_sidang IS NOT NULL THEN
                    CASE 
                        WHEN DATEDIFF(pp.tanggal_bht, pjs.tanggal_sidang) <= 7 THEN 100
                        WHEN DATEDIFF(pp.tanggal_bht, pjs.tanggal_sidang) <= 14 THEN 80
                        WHEN DATEDIFF(pp.tanggal_bht, pjs.tanggal_sidang) <= 21 THEN 60
                        ELSE 40
                    END
                WHEN pp.tanggal_bht IS NULL AND pjs.tanggal_sidang IS NOT NULL THEN
                    CASE 
                        WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) <= 7 THEN 90
                        WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) <= 14 THEN 70
                        WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) <= 21 THEN 50
                        ELSE 20
                    END
                ELSE 30
            END as efficiency_score
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		// Show both completed and pending BHT cases for comprehensive view
		// $this->db->where('pp.tanggal_bht IS NULL'); // Remove this to show all cases

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		// Add year filter - only show cases from specified year onwards
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		// Add case type filter if specified
		if ($jenis != 'semua') {
			$this->db->where('p.jenis_perkara_nama', $jenis);
		}

		$this->db->order_by('hari_sejak_pbt', 'DESC');
		$this->db->order_by('prioritas', 'DESC');

		return $this->db->get()->result();
	}

	public function get_pengingat_urgent($tanggal, $jenis = 'semua', $tahun_filter = '2025')
	{
		$this->db->select('COUNT(*) as total');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pjs.tanggal_sidang IS NULL');
		$this->db->where('pp.tanggal_bht IS NULL');
		$this->db->where('DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		// Add year filter
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		// Add case type filter
		if ($jenis != 'semua') {
			$this->db->where('p.jenis_perkara_nama', $jenis);
		}

		$result = $this->db->get()->row();
		return $result ? $result->total : 0;
	}

	public function count_jadwal_bht_harian($tanggal, $jenis = 'semua', $tahun_filter = '2025')
	{
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pjs.tanggal_sidang IS NOT NULL');
		$this->db->where('pp.tanggal_bht IS NULL');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		// Add year filter
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		// Add case type filter
		if ($jenis != 'semua') {
			$this->db->where('p.jenis_perkara_nama', $jenis);
		}

		return $this->db->count_all_results();
	}

	/**
	 * 4. Get perkara putus tanpa PBT
	 */
	public function get_perkara_putus_tanpa_pbt($tanggal)
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            DATE(pp.tanggal_putusan) as tanggal_putus,
            COALESCE(pen.majelis_hakim_nama, '-') as hakim,
            DATEDIFF(CURDATE(), pp.tanggal_putusan) as hari_sejak_putus,
            CASE 
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 21 THEN 'CRITICAL'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 THEN 'KRITIS'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 10 THEN 'PERINGATAN'
                ELSE 'NORMAL'
            END as level_peringatan
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pjs.tanggal_sidang IS NULL');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		$this->db->order_by('hari_sejak_putus', 'DESC');

		return $this->db->get()->result();
	}

	public function count_perkara_putus_tanpa_pbt($tanggal)
	{
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pjs.tanggal_sidang IS NULL');
		$this->_filter_perkara_dicabut();
		return $this->db->count_all_results();
	}

	public function get_alert_level_tanpa_pbt($tanggal)
	{
		$this->db->select('
            SUM(CASE WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 21 THEN 1 ELSE 0 END) as critical,
            SUM(CASE WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 AND DATEDIFF(CURDATE(), pp.tanggal_putusan) <= 21 THEN 1 ELSE 0 END) as kritis,
            SUM(CASE WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 10 AND DATEDIFF(CURDATE(), pp.tanggal_putusan) <= 14 THEN 1 ELSE 0 END) as peringatan,
            SUM(CASE WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) <= 10 THEN 1 ELSE 0 END) as normal
        ');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pjs.tanggal_sidang IS NULL');
		$this->_filter_perkara_dicabut();

		return $this->db->get()->row();
	}

	/**
	 * 5. Get berkas masuk
	 */
	public function get_berkas_masuk($tanggal, $status = 'semua')
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            DATE(p.tanggal_pendaftaran) as tanggal_pendaftaran,
            CASE 
                WHEN pp.tanggal_putusan IS NOT NULL THEN 'PUTUS'
                ELSE 'PROSES'
            END as status_perkara,
            COALESCE(pen.majelis_hakim_nama, '-') as hakim,
            CASE 
                WHEN pp.tanggal_putusan IS NOT NULL THEN 'Sudah Putus'
                ELSE 'Sedang Diproses'
            END as status_display
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('DATE(p.tanggal_pendaftaran)', $tanggal);

		if ($status == 'putus') {
			$this->db->where('pp.tanggal_putusan IS NOT NULL');
		} elseif ($status == 'proses') {
			$this->db->where('pp.tanggal_putusan IS NULL');
		}

		$this->db->order_by('p.tanggal_pendaftaran', 'DESC');

		return $this->db->get()->result();
	}

	public function count_berkas_masuk($tanggal, $status = 'semua')
	{
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('DATE(p.tanggal_pendaftaran)', $tanggal);

		if ($status == 'putus') {
			$this->db->where('pp.tanggal_putusan IS NOT NULL');
		} elseif ($status == 'proses') {
			$this->db->where('pp.tanggal_putusan IS NULL');
		}

		$this->_filter_perkara_dicabut();
		return $this->db->count_all_results();
	}

	public function get_statistik_berkas_masuk($tanggal)
	{
		$this->db->select("
            CASE 
                WHEN pp.tanggal_putusan IS NOT NULL THEN 'PUTUS'
                ELSE 'PROSES'
            END as status_perkara,
            COUNT(*) as jumlah
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('DATE(p.tanggal_pendaftaran)', $tanggal);
		$this->_filter_perkara_dicabut();
		$this->db->group_by("CASE WHEN pp.tanggal_putusan IS NOT NULL THEN 'PUTUS' ELSE 'PROSES' END");

		return $this->db->get()->result();
	}

	/**
	 * 6. Get PBT masuk
	 */
	public function get_pbt_masuk($tanggal, $status = 'semua')
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            DATE(pjs.tanggal_sidang) as tanggal_pbt,
            DATE(pp.tanggal_putusan) as tanggal_putusan,
            COALESCE(pen.majelis_hakim_nama, '-') as hakim,
            DATE(pp.tanggal_bht) as tanggal_bht,
            -- Status BHT dengan kekhususan Pengadilan Agama
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'Sudah BHT'
                WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%' THEN 'Belum BHT - Menunggu Ikrar Talak'
                ELSE 'Belum BHT'
            END as status_bht,
            
            -- Selisih hari PBT ke BHT (14 hari kalender)
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN DATEDIFF(pp.tanggal_bht, pjs.tanggal_sidang)
                ELSE DATEDIFF(CURDATE(), pjs.tanggal_sidang)
            END as selisih_hari,
            
            -- Target BHT berdasarkan jenis perkara
            CASE 
                WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%' THEN 
                    CONCAT('Target Izin Talak: ', DATE_FORMAT(DATE_ADD(pjs.tanggal_sidang, INTERVAL 14 DAY), '%d/%m/%Y'), 
                           ' | Max Ikrar: ', DATE_FORMAT(DATE_ADD(pjs.tanggal_sidang, INTERVAL 6 MONTH), '%d/%m/%Y'))
                ELSE DATE_FORMAT(DATE_ADD(pjs.tanggal_sidang, INTERVAL 14 DAY), '%d/%m/%Y')
            END as target_bht_info,
            
            -- Kategori khusus Pengadilan Agama
            CASE 
                WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%' AND pp.tanggal_bht IS NULL THEN 'CERAI_TALAK_PROSES'
                WHEN p.jenis_perkara_nama LIKE '%Cerai Gugat%' THEN 'CERAI_GUGAT'
                WHEN p.jenis_perkara_nama LIKE '%Waris%' THEN 'WARIS'
                WHEN p.jenis_perkara_nama LIKE '%Isbat%' THEN 'ISBAT_NIKAH'
                ELSE 'UMUM'
            END as kategori_pa
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('DATE(pjs.tanggal_sidang)', $tanggal);
		$this->db->where('pjs.tanggal_sidang IS NOT NULL');

		if ($status == 'sudah_bht') {
			$this->db->where('pp.tanggal_bht IS NOT NULL');
		} elseif ($status == 'belum_bht') {
			$this->db->where('pp.tanggal_bht IS NULL');
		}

		$this->_filter_perkara_dicabut();
		$this->db->order_by('pjs.tanggal_sidang', 'DESC');

		return $this->db->get()->result();
	}

	public function count_pbt_masuk($tanggal, $status = 'semua')
	{
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
		$this->db->where('DATE(pjs.tanggal_sidang)', $tanggal);
		$this->db->where('pjs.tanggal_sidang IS NOT NULL');

		if ($status == 'sudah_bht') {
			$this->db->where('pp.tanggal_bht IS NOT NULL');
		} elseif ($status == 'belum_bht') {
			$this->db->where('pp.tanggal_bht IS NULL');
		}

		$this->_filter_perkara_dicabut();
		return $this->db->count_all_results();
	}

	public function get_statistik_pbt_masuk($tanggal)
	{
		$this->db->select('
            COUNT(*) as total_pbt,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as sudah_bht,
            SUM(CASE WHEN pp.tanggal_bht IS NULL THEN 1 ELSE 0 END) as belum_bht
        ');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('DATE(pjs.tanggal_sidang)', $tanggal);
		$this->db->where('pjs.tanggal_sidang IS NOT NULL');
		$this->_filter_perkara_dicabut();

		return $this->db->get()->row();
	}

	/**
	 * 7. Get berkas menu BHT
	 */
	public function get_berkas_menu_bht($periode, $jenis = 'semua')
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            DATE(pp.tanggal_putusan) as tanggal_putusan,
            DATE(pjs.tanggal_sidang) as tanggal_pbt,
            DATE(pp.tanggal_bht) as tanggal_bht,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI'
                ELSE 'PROSES'
            END as status_bht,
            '-' as keterangan,
            '-' as hakim,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'Selesai'
                WHEN pjs.tanggal_sidang IS NULL THEN 'Menunggu PBT'
                ELSE 'Dalam Proses'
            END as progress_display
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('DATE_FORMAT(pp.tanggal_putusan, "%Y-%m") =', $periode);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		if ($jenis != 'semua') {
			$this->db->where('p.jenis_perkara_nama', $jenis);
		}

		$this->_filter_perkara_dicabut();
		$this->db->order_by('pp.tanggal_putusan', 'DESC');

		return $this->db->get()->result();
	}

	public function count_berkas_menu_bht($periode, $jenis = 'semua')
	{
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->where('DATE_FORMAT(pp.tanggal_putusan, "%Y-%m") =', $periode);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		if ($jenis != 'semua') {
			$this->db->where('p.jenis_perkara_nama', $jenis);
		}

		$this->_filter_perkara_dicabut();
		return $this->db->count_all_results();
	}

	public function get_kategori_berkas_bht($periode)
	{
		$this->db->select('
            p.jenis_perkara_nama,
            COUNT(*) as jumlah
        ');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->where('DATE_FORMAT(pp.tanggal_putusan, "%Y-%m") =', $periode);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->_filter_perkara_dicabut();
		$this->db->group_by('p.jenis_perkara_nama');
		$this->db->order_by('jumlah', 'DESC');

		return $this->db->get()->result();
	}

	public function get_progress_berkas_bht($periode)
	{
		$this->db->select('
            COUNT(*) as total_perkara,
            SUM(CASE WHEN pjs.tanggal_sidang IS NOT NULL THEN 1 ELSE 0 END) as sudah_pbt,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as sudah_bht,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as selesai_bht
        ');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('DATE_FORMAT(pp.tanggal_putusan, "%Y-%m") =', $periode);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->_filter_perkara_dicabut();

		return $this->db->get()->row();
	}

	/**
	 * API Helper Methods untuk notifikasi
	 */
	public function count_jadwal_bht_urgent($tanggal)
	{
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pjs.tanggal_sidang IS NOT NULL');
		$this->db->where('pp.tanggal_bht IS NULL');
		$this->db->where('DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 5');
		$this->_filter_perkara_dicabut();

		return $this->db->count_all_results();
	}

	public function count_berkas_pending($tanggal)
	{
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NULL');
		$this->_filter_perkara_dicabut();
		return $this->db->count_all_results();
	}

	public function count_pbt_menunggu($tanggal)
	{
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pjs.tanggal_sidang IS NULL');
		$this->_filter_perkara_dicabut();
		return $this->db->count_all_results();
	}

	// ===== METHODS FOR BHT REMINDER SYSTEM =====

	public function get_berkas_pending_bht($limit = null, $tahun_filter = null)
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            pp.tanggal_putusan,
            pjs.tanggal_sidang as tanggal_pbt,
            pp.tanggal_bht,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI'
                WHEN pjs.tanggal_sidang IS NOT NULL THEN 'MENUNGGU BHT'
                ELSE 'MENUNGGU PBT'
            END as status_reminder,
            CASE 
                WHEN pp.tanggal_bht IS NULL AND pjs.tanggal_sidang IS NOT NULL 
                THEN DATEDIFF(CURDATE(), pjs.tanggal_sidang)
                WHEN pjs.tanggal_sidang IS NULL 
                THEN DATEDIFF(CURDATE(), pp.tanggal_putusan)
                ELSE 0
            END as hari_tertunda
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pp.tanggal_bht IS NULL');

		// Add year filter if specified
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		$this->_filter_perkara_dicabut();
		$this->db->order_by('pp.tanggal_putusan', 'ASC');
		if ($limit) {
			$this->db->limit($limit);
		}
		return $this->db->get()->result();
	}

	public function get_reminder_statistics($periode, $tahun_filter = null)
	{
		$year = substr($periode, 0, 4);
		$month = substr($periode, 5, 2);

		$this->db->select("
            COUNT(*) as total_perkara,
            SUM(CASE WHEN pjs.tanggal_sidang IS NOT NULL THEN 1 ELSE 0 END) as sudah_pbt,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as sudah_bht,
            SUM(CASE WHEN pjs.tanggal_sidang IS NULL THEN 1 ELSE 0 END) as belum_pbt,
            SUM(CASE WHEN pjs.tanggal_sidang IS NOT NULL AND pp.tanggal_bht IS NULL THEN 1 ELSE 0 END) as belum_bht
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('YEAR(pp.tanggal_putusan)', $year);
		$this->db->where('MONTH(pp.tanggal_putusan)', $month);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		// Add year filter if specified
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		return $this->db->get()->row();
	}

	public function get_overdue_pbt_cases($days = 7, $tahun_filter = null)
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            pp.tanggal_putusan,
            DATEDIFF(CURDATE(), pp.tanggal_putusan) as hari_tertunda,
            p.perkara_id
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pjs.tanggal_sidang IS NULL');
		$this->db->where('DATEDIFF(CURDATE(), pp.tanggal_putusan) >=', $days);

		// Add year filter if specified
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		$this->db->order_by('pp.tanggal_putusan', 'ASC');

		return $this->db->get()->result();
	}

	public function get_overdue_bht_cases($days = 14, $tahun_filter = null)
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            pp.tanggal_putusan,
            pjs.tanggal_sidang as tanggal_pbt,
            DATEDIFF(CURDATE(), pjs.tanggal_sidang) as hari_tertunda,
            p.perkara_id
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pjs.tanggal_sidang IS NOT NULL');
		$this->db->where('pp.tanggal_bht IS NULL');
		$this->db->where('DATEDIFF(CURDATE(), pjs.tanggal_sidang) >=', $days);

		// Add year filter if specified
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		$this->db->order_by('pjs.tanggal_sidang', 'ASC');

		return $this->db->get()->result();
	}

	public function get_daily_trend($periode)
	{
		$year = substr($periode, 0, 4);
		$month = substr($periode, 5, 2);

		$this->db->select("
            DATE(pp.tanggal_putusan) as tanggal,
            COUNT(*) as total_putus,
            SUM(CASE WHEN pjs.tanggal_sidang IS NOT NULL THEN 1 ELSE 0 END) as total_pbt,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as total_bht
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('YEAR(pp.tanggal_putusan)', $year);
		$this->db->where('MONTH(pp.tanggal_putusan)', $month);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->group_by('DATE(pp.tanggal_putusan)');
		$this->db->order_by('pp.tanggal_putusan', 'ASC');

		return $this->db->get()->result();
	}

	public function get_top_delayed_case_types($periode, $limit = 5)
	{
		$year = substr($periode, 0, 4);
		$month = substr($periode, 5, 2);

		$this->db->select("
            p.jenis_perkara_nama as jenis_perkara,
            COUNT(*) as total_tertunda,
            AVG(DATEDIFF(CURDATE(), pp.tanggal_putusan)) as rata_hari_tertunda
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('YEAR(pp.tanggal_putusan)', $year);
		$this->db->where('MONTH(pp.tanggal_putusan)', $month);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pp.tanggal_bht IS NULL');
		$this->db->group_by('p.jenis_perkara_nama');
		$this->db->order_by('total_tertunda', 'DESC');
		$this->db->limit($limit);

		return $this->db->get()->result();
	}

	public function update_perkara_pbt($perkara_id, $data)
	{
		$this->db->where('perkara_id', $perkara_id);
		return $this->db->update('perkara_jadwal_sidang', $data);
	}

	public function update_perkara_bht($perkara_id, $data)
	{
		$this->db->where('perkara_id', $perkara_id);
		return $this->db->update('perkara_putusan', $data);
	}

	public function log_reminder_action($data)
	{
		// For now, just return true. You can create a separate table for logging if needed
		return true;
	}

	public function get_all_reminders_for_period($periode, $jenis = 'semua')
	{
		$year = substr($periode, 0, 4);
		$month = substr($periode, 5, 2);

		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            pp.tanggal_putusan,
            pjs.tanggal_sidang as tanggal_pbt,
            pp.tanggal_bht,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI BHT'
                WHEN pjs.tanggal_sidang IS NOT NULL THEN 'MENUNGGU BHT'
                ELSE 'MENUNGGU PBT'
            END as status_reminder,
            CASE 
                WHEN pp.tanggal_bht IS NULL AND pjs.tanggal_sidang IS NOT NULL 
                THEN DATEDIFF(CURDATE(), pjs.tanggal_sidang)
                WHEN pjs.tanggal_sidang IS NULL 
                THEN DATEDIFF(CURDATE(), pp.tanggal_putusan)
                ELSE 0
            END as hari_tertunda,
            CASE 
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 THEN 'high'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 7 THEN 'medium'
                ELSE 'low'
            END as priority_level
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_jadwal_sidang pjs', 'p.perkara_id = pjs.perkara_id', 'left');
		$this->db->where('YEAR(pp.tanggal_putusan)', $year);
		$this->db->where('MONTH(pp.tanggal_putusan)', $month);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		if ($jenis != 'semua') {
			$this->db->where('p.jenis_perkara_nama', $jenis);
		}

		$this->db->order_by('pp.tanggal_putusan', 'ASC');

		return $this->db->get()->result();
	}

	public function get_daily_completion_chart($periode)
	{
		$year = substr($periode, 0, 4);
		$month = substr($periode, 5, 2);

		$this->db->select("
            DATE(pp.tanggal_bht) as tanggal,
            COUNT(*) as completed_count
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->where('YEAR(pp.tanggal_bht)', $year);
		$this->db->where('MONTH(pp.tanggal_bht)', $month);
		$this->db->where('pp.tanggal_bht IS NOT NULL');
		$this->db->group_by('DATE(pp.tanggal_bht)');
		$this->db->order_by('pp.tanggal_bht', 'ASC');

		return $this->db->get()->result();
	}

	public function get_case_type_distribution($periode)
	{
		$year = substr($periode, 0, 4);
		$month = substr($periode, 5, 2);

		$this->db->select("
            p.jenis_perkara_nama as jenis_perkara,
            COUNT(*) as jumlah
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->where('YEAR(pp.tanggal_putusan)', $year);
		$this->db->where('MONTH(pp.tanggal_putusan)', $month);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->group_by('p.jenis_perkara_nama');
		$this->db->order_by('jumlah', 'DESC');

		return $this->db->get()->result();
	}

	public function get_delay_analysis_chart($periode)
	{
		$year = substr($periode, 0, 4);
		$month = substr($periode, 5, 2);

		$this->db->select("
            CASE 
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) <= 7 THEN '1-7 hari'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) <= 14 THEN '8-14 hari'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) <= 30 THEN '15-30 hari'
                ELSE 'Lebih 30 hari'
            END as kategori_delay,
            COUNT(*) as jumlah
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->where('YEAR(pp.tanggal_putusan)', $year);
		$this->db->where('MONTH(pp.tanggal_putusan)', $month);
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pp.tanggal_bht IS NULL');
		$this->db->group_by("
            CASE 
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) <= 7 THEN '1-7 hari'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) <= 14 THEN '8-14 hari'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) <= 30 THEN '15-30 hari'
                ELSE 'Lebih 30 hari'
            END
        ");
		$this->db->order_by('jumlah', 'DESC');

		return $this->db->get()->result();
	}

	public function get_jenis_perkara_kategori($tahun_filter = null)
	{
		$this->db->select("
            p.jenis_perkara_nama,
            COUNT(*) as jumlah
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');

		// Add year filter if specified
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		$this->db->group_by('p.jenis_perkara_nama');
		$this->db->order_by('jumlah', 'DESC');

		return $this->db->get()->result();
	}

	public function get_available_years()
	{
		$this->db->select('DISTINCT YEAR(pp.tanggal_putusan) as tahun');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('YEAR(pp.tanggal_putusan) >= 2020'); // Only show from 2020 onwards
		$this->db->order_by('tahun', 'DESC');

		return $this->db->get()->result();
	}

	/**
	 * Helper method untuk memfilter perkara yang dicabut
	 * Khusus untuk tabel perkara_putusan yang mengandung kata "dicabut"
	 */
	private function _filter_perkara_dicabut()
	{
		// Filter untuk tidak menampilkan perkara yang dicabut dari tabel perkara_putusan
		// Mengecek berbagai field yang mungkin mengandung kata "dicabut" atau tanggal cabut yang ada
		$this->db->where("(pp.tanggal_cabut IS NULL)", NULL, FALSE);
		$this->db->where("(pp.amar_putusan IS NULL OR (pp.amar_putusan NOT LIKE '%dicabut%' AND pp.amar_putusan NOT LIKE '%DICABUT%'))", NULL, FALSE);
		$this->db->where("(pp.status_putusan_nama IS NULL OR (pp.status_putusan_nama NOT LIKE '%dicabut%' AND pp.status_putusan_nama NOT LIKE '%DICABUT%'))", NULL, FALSE);
		$this->db->where("(pp.catatan_putusan IS NULL OR (pp.catatan_putusan NOT LIKE '%dicabut%' AND pp.catatan_putusan NOT LIKE '%DICABUT%'))", NULL, FALSE);
	}
}
