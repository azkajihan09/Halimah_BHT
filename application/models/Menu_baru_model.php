<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_baru_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _filter_perkara_dicabut()
	{
		// No specific filter needed - using basic perkara table
		// All active cases with putusan will be included
	}

	public function get_jadwal_bht_harian($tanggal, $jenis = 'semua', $tahun_filter = '2025')
	{
		$this->db->select("
            p.nomor_perkara,
            p.jenis_perkara_nama as jenis_perkara,
            DATE(pp.tanggal_putusan) as tanggal_putusan,
            pp.tanggal_bht,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SUDAH BHT'
                ELSE 'BELUM BHT'
            END as status_pengisian_bht,
            DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY) as perkiraan_bht,
            CASE 
                WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 21 THEN 'CRITICAL'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 14 THEN 'TERLAMBAT'
                WHEN DATEDIFF(CURDATE(), pp.tanggal_putusan) > 10 THEN 'URGENT'
                ELSE 'NORMAL'
            END as status_bht,
            'Normal' as keterangan_perkara
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('p.nomor_perkara NOT LIKE', '%/Pdt.P/%');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		// Add year filter - only show cases from specified year onwards
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		// Add LIMIT to prevent memory issues
		$this->db->limit(100);

		// Add case type filter if specified
		if ($jenis != 'semua') {
			$this->db->where('p.jenis_perkara_nama', $jenis);
		}

		$this->db->order_by('pp.tanggal_putusan', 'DESC');

		return $this->db->get()->result();
	}

	public function get_pengingat_urgent($tanggal, $jenis = 'semua', $tahun_filter = '2025')
	{
		$this->db->select('COUNT(*) as total');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('pp.tanggal_bht IS NULL');
		$this->db->where('DATEDIFF(CURDATE(), pp.tanggal_putusan) > 10');

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

		return $this->db->get()->row()->total;
	}

	public function get_chart_data($tahun_filter = '2025')
	{
		$this->db->select("
            p.jenis_perkara_nama,
            COUNT(*) as total_perkara,
            SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as selesai_bht,
            SUM(CASE WHEN pp.tanggal_bht IS NULL THEN 1 ELSE 0 END) as belum_bht
        ");
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('p.nomor_perkara NOT LIKE', '%/Pdt.P/%');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		// Add year filter
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		$this->db->group_by('p.jenis_perkara_nama');
		$this->db->order_by('total_perkara', 'DESC');

		return $this->db->get()->result();
	}

	public function count_jadwal_bht_harian($tanggal, $jenis = 'semua', $tahun_filter = '2025')
	{
		$this->db->select('COUNT(*) as total');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('p.nomor_perkara NOT LIKE', '%/Pdt.P/%');

		// Filter untuk tidak menampilkan perkara yang dicabut
		$this->_filter_perkara_dicabut();

		// Add year filter
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		// Add case type filter if specified
		if ($jenis != 'semua') {
			$this->db->where('p.jenis_perkara_nama', $jenis);
		}

		return $this->db->get()->row()->total;
	}

	public function get_jenis_perkara_kategori($tahun_filter = '2025')
	{
		$this->db->select('p.jenis_perkara_nama');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->where('p.nomor_perkara NOT LIKE', '%/Pdt.P/%');

		// Add year filter
		if ($tahun_filter) {
			$this->db->where('YEAR(pp.tanggal_putusan) >=', $tahun_filter);
		}

		$this->db->group_by('p.jenis_perkara_nama');
		$this->db->order_by('p.jenis_perkara_nama', 'ASC');

		return $this->db->get()->result();
	}

	public function get_available_years()
	{
		$this->db->select('YEAR(pp.tanggal_putusan) as year');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->where('pp.tanggal_putusan IS NOT NULL');
		$this->db->group_by('year');
		$this->db->order_by('year', 'DESC');

		return $this->db->get()->result();
	}
}
