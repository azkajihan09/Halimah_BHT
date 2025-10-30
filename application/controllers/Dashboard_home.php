<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_home_model');
		$this->load->helper(['url', 'date']);
	}

	public function index()
	{
		// Get current year and month for default data
		$year = $this->input->get('year') ?: date('Y');
		$month = $this->input->get('month') ?: date('m');
		$tahun_filter = $this->input->get('tahun_filter') ?: '2024'; // Default filter tahun 2024 ke atas

		// Get comprehensive dashboard data
		$data = array(
			'title' => 'Dashboard Home - Modern Analytics',
			'year' => $year,
			'month' => $month,
			'tahun_filter' => $tahun_filter,

			// Main statistics
			'perkara_diterima' => $this->Dashboard_home_model->get_perkara_diterima($year, $tahun_filter),
			'perkara_putus' => $this->Dashboard_home_model->get_perkara_putus($year, $tahun_filter),
			'perkara_minutasi' => $this->Dashboard_home_model->get_perkara_minutasi($year, $tahun_filter),
			'perkara_sisa' => $this->Dashboard_home_model->get_perkara_sisa($year, $tahun_filter),

			// Advanced statistics
			'total_hakim' => $this->Dashboard_home_model->get_total_hakim(),
			'perkara_hari_ini' => $this->Dashboard_home_model->get_perkara_hari_ini($tahun_filter),
			'perkara_bulan_ini' => $this->Dashboard_home_model->get_perkara_bulan_ini($year, $month, $tahun_filter),
			'tingkat_penyelesaian' => $this->Dashboard_home_model->get_tingkat_penyelesaian($year, $tahun_filter),

			// Chart data
			'monthly_stats' => $this->Dashboard_home_model->get_monthly_stats($year, $tahun_filter),
			'case_types' => $this->Dashboard_home_model->get_case_type_stats($year, $tahun_filter),
			'hakim_performance' => $this->Dashboard_home_model->get_hakim_performance($year, $tahun_filter),
			'weekly_trends' => $this->Dashboard_home_model->get_weekly_trends($tahun_filter),

			// Comparative data
			'comparison_last_year' => $this->Dashboard_home_model->get_yearly_comparison($year, $tahun_filter),
			'top_case_types' => $this->Dashboard_home_model->get_top_case_types($year, $tahun_filter),

			// Recent activities
			'recent_cases' => $this->Dashboard_home_model->get_recent_cases($tahun_filter, 10),
			'pending_cases' => $this->Dashboard_home_model->get_pending_cases($tahun_filter, 5),

			// Available years for filter
			'available_years' => range(2020, date('Y'))
		);

		// Calculate performance metrics
		$data['case_completion_rate'] = $data['perkara_diterima'] > 0 ?
			round(($data['perkara_putus'] / $data['perkara_diterima']) * 100, 1) : 0;
		$data['minutasi_rate'] = $data['perkara_putus'] > 0 ?
			round(($data['perkara_minutasi'] / $data['perkara_putus']) * 100, 1) : 0;

		// Load views
		$this->load->view('template/new_header', $data);
		$this->load->view('template/new_sidebar', $data);
		$this->load->view('dashboard_home', $data);
		$this->load->view('template/new_footer');
	}

	// AJAX endpoints for dynamic data
	public function ajax_update_stats()
	{
		$year = $this->input->post('year') ?: date('Y');
		$month = $this->input->post('month') ?: date('m');
		$tahun_filter = $this->input->post('tahun_filter') ?: '2024';

		$data = array(
			'perkara_diterima' => $this->Dashboard_home_model->get_perkara_diterima($year, $tahun_filter),
			'perkara_putus' => $this->Dashboard_home_model->get_perkara_putus($year, $tahun_filter),
			'perkara_minutasi' => $this->Dashboard_home_model->get_perkara_minutasi($year, $tahun_filter),
			'perkara_sisa' => $this->Dashboard_home_model->get_perkara_sisa($year, $tahun_filter),
			'perkara_hari_ini' => $this->Dashboard_home_model->get_perkara_hari_ini($tahun_filter),
			'perkara_bulan_ini' => $this->Dashboard_home_model->get_perkara_bulan_ini($year, $month, $tahun_filter)
		);

		// Calculate rates
		$data['case_completion_rate'] = $data['perkara_diterima'] > 0 ?
			round(($data['perkara_putus'] / $data['perkara_diterima']) * 100, 1) : 0;
		$data['minutasi_rate'] = $data['perkara_putus'] > 0 ?
			round(($data['perkara_minutasi'] / $data['perkara_putus']) * 100, 1) : 0;

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function ajax_chart_data()
	{
		$year = $this->input->post('year') ?: date('Y');
		$tahun_filter = $this->input->post('tahun_filter') ?: '2024';

		$data = array(
			'monthly_stats' => $this->Dashboard_home_model->get_monthly_stats($year, $tahun_filter),
			'case_types' => $this->Dashboard_home_model->get_case_type_stats($year, $tahun_filter),
			'hakim_performance' => $this->Dashboard_home_model->get_hakim_performance($year, $tahun_filter),
			'weekly_trends' => $this->Dashboard_home_model->get_weekly_trends($tahun_filter)
		);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_recent_activities()
	{
		$tahun_filter = $this->input->post('tahun_filter') ?: '2024';

		$data = array(
			'recent_cases' => $this->Dashboard_home_model->get_recent_cases($tahun_filter, 10),
			'pending_cases' => $this->Dashboard_home_model->get_pending_cases($tahun_filter, 5)
		);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function export_summary()
	{
		$year = $this->input->get('year') ?: date('Y');
		$tahun_filter = $this->input->get('tahun_filter') ?: '2024';

		$this->load->library('PHPExcel');

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();

		// Set document properties
		$objPHPExcel->getProperties()
			->setCreator("PA Amuntai Dashboard System")
			->setLastModifiedBy("System")
			->setTitle("Ringkasan Dashboard - Tahun " . $year)
			->setSubject("Dashboard Summary Report")
			->setDescription("Laporan ringkasan dashboard tahun " . $year);

		// Header
		$sheet->setCellValue('A1', 'RINGKASAN DASHBOARD PA AMUNTAI');
		$sheet->setCellValue('A2', 'Tahun: ' . $year . ' (Filter: ' . $tahun_filter . ' ke atas)');
		$sheet->setCellValue('A3', 'Tanggal Export: ' . date('d F Y H:i:s'));

		// Statistics summary
		$row = 5;
		$sheet->setCellValue('A' . $row, 'STATISTIK UMUM');
		$row += 2;

		$perkara_diterima = $this->Dashboard_home_model->get_perkara_diterima($year, $tahun_filter);
		$perkara_putus = $this->Dashboard_home_model->get_perkara_putus($year, $tahun_filter);
		$perkara_minutasi = $this->Dashboard_home_model->get_perkara_minutasi($year, $tahun_filter);
		$perkara_sisa = $this->Dashboard_home_model->get_perkara_sisa($year, $tahun_filter);

		$sheet->setCellValue('A' . $row, 'Perkara Diterima');
		$sheet->setCellValue('B' . $row, $perkara_diterima);
		$row++;
		$sheet->setCellValue('A' . $row, 'Perkara Putus');
		$sheet->setCellValue('B' . $row, $perkara_putus);
		$row++;
		$sheet->setCellValue('A' . $row, 'Perkara Minutasi');
		$sheet->setCellValue('B' . $row, $perkara_minutasi);
		$row++;
		$sheet->setCellValue('A' . $row, 'Perkara Sisa');
		$sheet->setCellValue('B' . $row, $perkara_sisa);

		// Auto-size columns
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);

		// Output
		$filename = 'Dashboard_Summary_' . $year . '_' . date('YmdHis') . '.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
}
