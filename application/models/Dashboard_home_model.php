<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_home_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Get count of cases received in a specific year with year filter
	 */
	public function get_perkara_diterima($year = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}

		try {
			$this->db->where('YEAR(tanggal_pendaftaran)', $year);

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_pendaftaran) >=', $tahun_filter);
			}

			$query = $this->db->get('perkara');
			return $query->num_rows();
		} catch (Exception $e) {
			log_message('error', 'Error in get_perkara_diterima: ' . $e->getMessage());
			return 0;
		}
	}

	/**
	 * Get count of cases decided in a specific year with year filter
	 */
	public function get_perkara_putus($year = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}

		try {
			$this->db->where('YEAR(tanggal_putusan)', $year);
			$this->db->where('tanggal_putusan IS NOT NULL');

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_putusan) >=', $tahun_filter);
			}

			$query = $this->db->get('perkara_putusan');
			return $query->num_rows();
		} catch (Exception $e) {
			log_message('error', 'Error in get_perkara_putus: ' . $e->getMessage());
			return 0;
		}
	}

	/**
	 * Get count of cases minutasi in a specific year with year filter
	 */
	public function get_perkara_minutasi($year = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}

		try {
			$this->db->where('YEAR(tanggal_minutasi)', $year);
			$this->db->where('tanggal_minutasi IS NOT NULL');

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_minutasi) >=', $tahun_filter);
			}

			$query = $this->db->get('perkara_putusan');
			return $query->num_rows();
		} catch (Exception $e) {
			log_message('error', 'Error in get_perkara_minutasi: ' . $e->getMessage());
			return 0;
		}
	}

	/**
	 * Get count of remaining cases (registered but not decided)
	 */
	public function get_perkara_sisa($year = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}

		try {
			$this->db->select('p.perkara_id');
			$this->db->from('perkara p');
			$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
			$this->db->where('YEAR(p.tanggal_pendaftaran)', $year);
			$this->db->where('pp.tanggal_putusan IS NULL');

			if ($tahun_filter) {
				$this->db->where('YEAR(p.tanggal_pendaftaran) >=', $tahun_filter);
			}

			$query = $this->db->get();
			return $query->num_rows();
		} catch (Exception $e) {
			log_message('error', 'Error in get_perkara_sisa: ' . $e->getMessage());
			return 0;
		}
	}

	/**
	 * Get total number of judges/hakim from real data
	 */
	public function get_total_hakim()
	{
		try {
			// Get distinct hakim from perkara_penetapan table
			$this->db->distinct();
			$this->db->select('majelis_hakim_nama');
			$this->db->where('majelis_hakim_nama IS NOT NULL');
			$this->db->where('majelis_hakim_nama !=', '');
			$query = $this->db->get('perkara_penetapan');

			$count = $query->num_rows();

			// If no data found, return default estimate
			return $count > 0 ? $count : 4;
		} catch (Exception $e) {
			log_message('error', 'Error in get_total_hakim: ' . $e->getMessage());
			return 4; // Default fallback
		}
	}

	/**
	 * Get cases registered today
	 */
	public function get_perkara_hari_ini($tahun_filter = '2024')
	{
		try {
			$this->db->where('DATE(tanggal_pendaftaran)', date('Y-m-d'));

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_pendaftaran) >=', $tahun_filter);
			}

			$query = $this->db->get('perkara');
			return $query->num_rows();
		} catch (Exception $e) {
			log_message('error', 'Error in get_perkara_hari_ini: ' . $e->getMessage());
			return 0;
		}
	}

	/**
	 * Get cases registered this month
	 */
	public function get_perkara_bulan_ini($year = null, $month = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}
		if (empty($month)) {
			$month = date('m');
		}

		try {
			$this->db->where('YEAR(tanggal_pendaftaran)', $year);
			$this->db->where('MONTH(tanggal_pendaftaran)', $month);

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_pendaftaran) >=', $tahun_filter);
			}

			$query = $this->db->get('perkara');
			return $query->num_rows();
		} catch (Exception $e) {
			log_message('error', 'Error in get_perkara_bulan_ini: ' . $e->getMessage());
			return 0;
		}
	}

	/**
	 * Get completion rate statistics
	 */
	public function get_tingkat_penyelesaian($year = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}

		try {
			$diterima = $this->get_perkara_diterima($year, $tahun_filter);
			$putus = $this->get_perkara_putus($year, $tahun_filter);

			return $diterima > 0 ? round(($putus / $diterima) * 100, 2) : 0;
		} catch (Exception $e) {
			log_message('error', 'Error in get_tingkat_penyelesaian: ' . $e->getMessage());
			return 0;
		}
	}

	/**
	 * Get monthly statistics for cases in a specific year with enhanced data
	 */
	public function get_monthly_stats($year = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}

		$monthly_data = [
			'received' => array_fill(0, 12, 0),
			'decided' => array_fill(0, 12, 0),
			'minutasi' => array_fill(0, 12, 0)
		];

		try {
			// Get monthly received cases
			$this->db->select('MONTH(tanggal_pendaftaran) as month, COUNT(*) as count');
			$this->db->where('YEAR(tanggal_pendaftaran)', $year);

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_pendaftaran) >=', $tahun_filter);
			}

			$this->db->group_by('MONTH(tanggal_pendaftaran)');
			$query = $this->db->get('perkara');

			foreach ($query->result() as $row) {
				$monthly_data['received'][$row->month - 1] = (int)$row->count;
			}

			// Get monthly decided cases
			$this->db->select('MONTH(tanggal_putusan) as month, COUNT(*) as count');
			$this->db->where('YEAR(tanggal_putusan)', $year);
			$this->db->where('tanggal_putusan IS NOT NULL');

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_putusan) >=', $tahun_filter);
			}

			$this->db->group_by('MONTH(tanggal_putusan)');
			$query = $this->db->get('perkara_putusan');

			foreach ($query->result() as $row) {
				$monthly_data['decided'][$row->month - 1] = (int)$row->count;
			}

			// Get monthly minutasi cases
			$this->db->select('MONTH(tanggal_minutasi) as month, COUNT(*) as count');
			$this->db->where('YEAR(tanggal_minutasi)', $year);
			$this->db->where('tanggal_minutasi IS NOT NULL');

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_minutasi) >=', $tahun_filter);
			}

			$this->db->group_by('MONTH(tanggal_minutasi)');
			$query = $this->db->get('perkara_putusan');

			foreach ($query->result() as $row) {
				$monthly_data['minutasi'][$row->month - 1] = (int)$row->count;
			}
		} catch (Exception $e) {
			log_message('error', 'Error in get_monthly_stats: ' . $e->getMessage());
		}

		return $monthly_data;
	}

	/**
	 * Get case type distribution statistics with year filter
	 */
	public function get_case_type_stats($year = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}

		try {
			$this->db->select('jenis_perkara_nama, COUNT(*) as count');
			$this->db->where('YEAR(tanggal_pendaftaran)', $year);

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_pendaftaran) >=', $tahun_filter);
			}

			$this->db->group_by('jenis_perkara_nama');
			$this->db->order_by('count', 'DESC');
			$this->db->limit(8);
			$query = $this->db->get('perkara');

			return $query->result();
		} catch (Exception $e) {
			log_message('error', 'Error in get_case_type_stats: ' . $e->getMessage());
			return [];
		}
	}

	/**
	 * Get judge performance statistics using real data from perkara_penetapan
	 */
	public function get_hakim_performance($year = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}

		try {
			// Get real hakim performance data from perkara_penetapan table
			$sql = "SELECT 
						pen.majelis_hakim_nama as hakim_nama,
						COUNT(DISTINCT p.perkara_id) as total_perkara,
						COUNT(DISTINCT pp.perkara_id) as perkara_putus,
						ROUND((COUNT(DISTINCT pp.perkara_id) / COUNT(DISTINCT p.perkara_id)) * 100, 2) as completion_rate
					FROM perkara p
					LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id  
					LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
					WHERE pen.majelis_hakim_nama IS NOT NULL 
					AND pen.majelis_hakim_nama != ''
					AND YEAR(p.tanggal_pendaftaran) = ?";

			$params = [$year];

			if ($tahun_filter) {
				$sql .= " AND YEAR(p.tanggal_pendaftaran) >= ?";
				$params[] = $tahun_filter;
			}

			$sql .= " GROUP BY pen.majelis_hakim_nama 
					  ORDER BY completion_rate DESC 
					  LIMIT 10";

			$query = $this->db->query($sql, $params);
			$result = $query->result();

			// If no real data found, return empty array instead of mock data
			if (empty($result)) {
				return [];
			}

			return $result;
		} catch (Exception $e) {
			log_message('error', 'Error in get_hakim_performance: ' . $e->getMessage());
			return [];
		}
	}

	/**
	 * Get weekly trends for current month
	 */
	public function get_weekly_trends($tahun_filter = '2024')
	{
		try {
			$this->db->select("
                WEEK(tanggal_pendaftaran) as week_number,
                COUNT(*) as count,
                DATE(tanggal_pendaftaran) as date
            ");
			$this->db->where('YEAR(tanggal_pendaftaran)', date('Y'));
			$this->db->where('MONTH(tanggal_pendaftaran)', date('m'));

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_pendaftaran) >=', $tahun_filter);
			}

			$this->db->group_by('WEEK(tanggal_pendaftaran)');
			$this->db->order_by('week_number', 'ASC');

			return $this->db->get('perkara')->result();
		} catch (Exception $e) {
			log_message('error', 'Error in get_weekly_trends: ' . $e->getMessage());
			return [];
		}
	}

	/**
	 * Get yearly comparison with previous year
	 */
	public function get_yearly_comparison($year = null, $tahun_filter = '2024')
	{
		if (empty($year)) {
			$year = date('Y');
		}

		$previous_year = $year - 1;

		try {
			$current_year_data = [
				'diterima' => $this->get_perkara_diterima($year, $tahun_filter),
				'putus' => $this->get_perkara_putus($year, $tahun_filter),
				'minutasi' => $this->get_perkara_minutasi($year, $tahun_filter)
			];

			$previous_year_data = [
				'diterima' => $this->get_perkara_diterima($previous_year, $tahun_filter),
				'putus' => $this->get_perkara_putus($previous_year, $tahun_filter),
				'minutasi' => $this->get_perkara_minutasi($previous_year, $tahun_filter)
			];

			return [
				'current_year' => $current_year_data,
				'previous_year' => $previous_year_data,
				'year' => $year,
				'previous_year_label' => $previous_year
			];
		} catch (Exception $e) {
			log_message('error', 'Error in get_yearly_comparison: ' . $e->getMessage());
			return [];
		}
	}

	/**
	 * Get top case types by volume
	 */
	public function get_top_case_types($year = null, $tahun_filter = '2024', $limit = 5)
	{
		if (empty($year)) {
			$year = date('Y');
		}

		try {
			$this->db->select('jenis_perkara_nama, COUNT(*) as count');
			$this->db->where('YEAR(tanggal_pendaftaran)', $year);

			if ($tahun_filter) {
				$this->db->where('YEAR(tanggal_pendaftaran) >=', $tahun_filter);
			}

			$this->db->group_by('jenis_perkara_nama');
			$this->db->order_by('count', 'DESC');
			$this->db->limit($limit);

			return $this->db->get('perkara')->result();
		} catch (Exception $e) {
			log_message('error', 'Error in get_top_case_types: ' . $e->getMessage());
			return [];
		}
	}

	/**
	 * Get recent cases with real hakim data
	 */
	public function get_recent_cases($tahun_filter = '2024', $limit = 10)
	{
		try {
			$this->db->select('p.nomor_perkara, p.jenis_perkara_nama, p.tanggal_pendaftaran, COALESCE(pen.majelis_hakim_nama, "-") as hakim_nama');
			$this->db->from('perkara p');
			$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
			$this->db->where('p.tanggal_pendaftaran IS NOT NULL');

			if ($tahun_filter) {
				$this->db->where('YEAR(p.tanggal_pendaftaran) >=', $tahun_filter);
			}

			$this->db->order_by('p.tanggal_pendaftaran', 'DESC');
			$this->db->limit($limit);

			return $this->db->get()->result();
		} catch (Exception $e) {
			log_message('error', 'Error in get_recent_cases: ' . $e->getMessage());
			return [];
		}
	}

	/**
	 * Get pending cases (not yet decided) with real hakim data
	 */
	public function get_pending_cases($tahun_filter = '2024', $limit = 5)
	{
		try {
			$this->db->select("p.nomor_perkara, p.jenis_perkara_nama, p.tanggal_pendaftaran, COALESCE(pen.majelis_hakim_nama, '-') as hakim_nama, DATEDIFF(CURDATE(), p.tanggal_pendaftaran) as days_pending");
			$this->db->from('perkara p');
			$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
			$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
			$this->db->where('pp.tanggal_putusan IS NULL');

			if ($tahun_filter) {
				$this->db->where('YEAR(p.tanggal_pendaftaran) >=', $tahun_filter);
			}

			$this->db->order_by('p.tanggal_pendaftaran', 'ASC');
			$this->db->limit($limit);

			return $this->db->get()->result();
		} catch (Exception $e) {
			log_message('error', 'Error in get_pending_cases: ' . $e->getMessage());
			return [];
		}
	}
}
