<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_baru extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Load necessary models
		$this->load->model('Dashboard_model');
		$this->load->model('Menu_baru_model');
		$this->load->helper(['url', 'date']);
	}

	/**
	 * 1. Perkara Putus Tiap Hari
	 */
	public function perkara_putus_harian()
	{
		$tanggal = $this->input->get('tanggal') ?: date('Y-m-d');

		$data = array(
			'title' => 'Perkara Putus Tiap Hari',
			'tanggal' => $tanggal,
			'perkara_putus' => $this->Menu_baru_model->get_perkara_putus_harian($tanggal),
			'total_putus' => $this->Menu_baru_model->count_perkara_putus_harian($tanggal)
		);

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('menu_baru/perkara_putus_harian', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * 2. Tanggal PBT dan BHT
	 */
	public function tanggal_pbt_bht()
	{
		$bulan = $this->input->get('bulan') ?: date('Y-m');

		$data = array(
			'title' => 'Tanggal PBT dan BHT',
			'bulan' => $bulan,
			'data_pbt_bht' => $this->Menu_baru_model->get_tanggal_pbt_bht($bulan),
			'kalender_data' => $this->Menu_baru_model->get_kalender_pbt_bht($bulan)
		);

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('menu_baru/tanggal_pbt_bht', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * 3. Jadwal BHT Per Hari (Pengingat/Alarm)
	 */
	public function jadwal_bht_harian()
	{
		$tanggal = $this->input->get('tanggal') ?: date('Y-m-d');

		$data = array(
			'title' => 'Jadwal BHT Per Hari - Pengingat',
			'tanggal' => $tanggal,
			'jadwal_bht' => $this->Menu_baru_model->get_jadwal_bht_harian($tanggal),
			'pengingat_urgent' => $this->Menu_baru_model->get_pengingat_urgent($tanggal),
			'total_jadwal' => $this->Menu_baru_model->count_jadwal_bht_harian($tanggal)
		);

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('menu_baru/jadwal_bht_harian', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * 4. Perkara Putus Tanpa PBT Hari Ini (Pengingat/Alarm)
	 */
	public function perkara_putus_tanpa_pbt()
	{
		$tanggal = $this->input->get('tanggal') ?: date('Y-m-d');

		$data = array(
			'title' => 'Perkara Putus Tanpa PBT - Pengingat',
			'tanggal' => $tanggal,
			'perkara_tanpa_pbt' => $this->Menu_baru_model->get_perkara_putus_tanpa_pbt($tanggal),
			'total_tanpa_pbt' => $this->Menu_baru_model->count_perkara_putus_tanpa_pbt($tanggal),
			'alert_level' => $this->Menu_baru_model->get_alert_level_tanpa_pbt($tanggal)
		);

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('menu_baru/perkara_putus_tanpa_pbt', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * 5. Berkas Masuk
	 */
	public function berkas_masuk()
	{
		$tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
		$status = $this->input->get('status') ?: 'semua';

		$data = array(
			'title' => 'Berkas Masuk',
			'tanggal' => $tanggal,
			'status' => $status,
			'berkas_masuk' => $this->Menu_baru_model->get_berkas_masuk($tanggal, $status),
			'total_berkas' => $this->Menu_baru_model->count_berkas_masuk($tanggal, $status),
			'statistik_berkas' => $this->Menu_baru_model->get_statistik_berkas_masuk($tanggal)
		);

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('menu_baru/berkas_masuk', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * 6. PBT Masuk
	 */
	public function pbt_masuk()
	{
		$tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
		$status = $this->input->get('status') ?: 'semua';

		$data = array(
			'title' => 'PBT Masuk',
			'tanggal' => $tanggal,
			'status' => $status,
			'pbt_masuk' => $this->Menu_baru_model->get_pbt_masuk($tanggal, $status),
			'total_pbt' => $this->Menu_baru_model->count_pbt_masuk($tanggal, $status),
			'statistik_pbt' => $this->Menu_baru_model->get_statistik_pbt_masuk($tanggal)
		);

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('menu_baru/pbt_masuk', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * 7. Berkas Menu BHT
	 */
	public function berkas_menu_bht()
	{
		$periode = $this->input->get('periode') ?: date('Y-m');
		$jenis = $this->input->get('jenis') ?: 'semua';

		$data = array(
			'title' => 'Berkas Menu BHT',
			'periode' => $periode,
			'jenis' => $jenis,
			'berkas_bht' => $this->Menu_baru_model->get_berkas_menu_bht($periode, $jenis),
			'total_berkas_bht' => $this->Menu_baru_model->count_berkas_menu_bht($periode, $jenis),
			'kategori_berkas' => $this->Menu_baru_model->get_kategori_berkas_bht($periode),
			'progress_bht' => $this->Menu_baru_model->get_progress_berkas_bht($periode)
		);

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('menu_baru/berkas_menu_bht', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * API untuk notifikasi real-time
	 */
	public function api_notifikasi()
	{
		$tanggal = date('Y-m-d');

		$notifikasi = array(
			'perkara_putus_tanpa_pbt' => $this->Menu_baru_model->count_perkara_putus_tanpa_pbt($tanggal),
			'jadwal_bht_urgent' => $this->Menu_baru_model->count_jadwal_bht_urgent($tanggal),
			'berkas_pending' => $this->Menu_baru_model->count_berkas_pending($tanggal),
			'pbt_menunggu' => $this->Menu_baru_model->count_pbt_menunggu($tanggal)
		);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => true,
				'data' => $notifikasi,
				'timestamp' => date('Y-m-d H:i:s'),
				'total_alert' => array_sum($notifikasi)
			)));
	}

	/**
	 * Export data ke Excel
	 */
	public function export_excel($menu = 'perkara_putus_harian')
	{
		$tanggal = $this->input->get('tanggal') ?: date('Y-m-d');

		// Load PHPExcel library
		require_once APPPATH . 'PHPExcel-1.8/Classes/PHPExcel.php';
		require_once APPPATH . 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';

		$excel = new PHPExcel();
		$excel->setActiveSheetIndex(0);
		$sheet = $excel->getActiveSheet();

		// Header Excel berdasarkan menu
		switch ($menu) {
			case 'perkara_putus_harian':
				$data = $this->Menu_baru_model->get_perkara_putus_harian($tanggal);
				$filename = 'Perkara_Putus_Harian_' . $tanggal . '.xlsx';
				$this->_export_perkara_putus_harian($sheet, $data, $tanggal);
				break;

			case 'jadwal_bht_harian':
				$data = $this->Menu_baru_model->get_jadwal_bht_harian($tanggal);
				$filename = 'Jadwal_BHT_Harian_' . $tanggal . '.xlsx';
				$this->_export_jadwal_bht_harian($sheet, $data, $tanggal);
				break;

			case 'perkara_tanpa_pbt':
				$data = $this->Menu_baru_model->get_perkara_putus_tanpa_pbt($tanggal);
				$filename = 'Perkara_Tanpa_PBT_' . $tanggal . '.xlsx';
				$this->_export_perkara_tanpa_pbt($sheet, $data, $tanggal);
				break;

			default:
				show_404();
		}

		// Output Excel file
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Helper functions untuk export Excel
	 */
	private function _export_perkara_putus_harian($sheet, $data, $tanggal)
	{
		$sheet->setTitle('Perkara Putus Harian');
		$sheet->setCellValue('A1', 'LAPORAN PERKARA PUTUS HARIAN');
		$sheet->setCellValue('A2', 'Tanggal: ' . date('d/m/Y', strtotime($tanggal)));

		// Header tabel
		$sheet->setCellValue('A4', 'No');
		$sheet->setCellValue('B4', 'Nomor Perkara');
		$sheet->setCellValue('C4', 'Jenis Perkara');
		$sheet->setCellValue('D4', 'Tanggal Putus');
		$sheet->setCellValue('E4', 'Hakim');
		$sheet->setCellValue('F4', 'Status BHT');

		// Data
		$row = 5;
		$no = 1;
		foreach ($data as $item) {
			$sheet->setCellValue('A' . $row, $no++);
			$sheet->setCellValue('B' . $row, isset($item->nomor_perkara) ? $item->nomor_perkara : '');
			$sheet->setCellValue('C' . $row, isset($item->jenis_perkara) ? $item->jenis_perkara : '');
			$sheet->setCellValue('D' . $row, isset($item->tanggal_putus) ? $item->tanggal_putus : '');
			$sheet->setCellValue('E' . $row, isset($item->hakim) ? $item->hakim : '');
			$sheet->setCellValue('F' . $row, isset($item->status_bht) ? $item->status_bht : '');
			$row++;
		}
	}

	private function _export_jadwal_bht_harian($sheet, $data, $tanggal)
	{
		$sheet->setTitle('Jadwal BHT Harian');
		$sheet->setCellValue('A1', 'JADWAL BHT HARIAN');
		$sheet->setCellValue('A2', 'Tanggal: ' . date('d/m/Y', strtotime($tanggal)));

		// Header tabel
		$sheet->setCellValue('A4', 'No');
		$sheet->setCellValue('B4', 'Nomor Perkara');
		$sheet->setCellValue('C4', 'Jenis Perkara');
		$sheet->setCellValue('D4', 'Target BHT');
		$sheet->setCellValue('E4', 'Status');
		$sheet->setCellValue('F4', 'Prioritas');

		// Data
		$row = 5;
		$no = 1;
		foreach ($data as $item) {
			$sheet->setCellValue('A' . $row, $no++);
			$sheet->setCellValue('B' . $row, isset($item->nomor_perkara) ? $item->nomor_perkara : '');
			$sheet->setCellValue('C' . $row, isset($item->jenis_perkara) ? $item->jenis_perkara : '');
			$sheet->setCellValue('D' . $row, isset($item->target_bht) ? $item->target_bht : '');
			$sheet->setCellValue('E' . $row, isset($item->status) ? $item->status : '');
			$sheet->setCellValue('F' . $row, isset($item->prioritas) ? $item->prioritas : '');
			$row++;
		}
	}

	private function _export_perkara_tanpa_pbt($sheet, $data, $tanggal)
	{
		$sheet->setTitle('Perkara Tanpa PBT');
		$sheet->setCellValue('A1', 'PERKARA PUTUS TANPA PBT');
		$sheet->setCellValue('A2', 'Tanggal: ' . date('d/m/Y', strtotime($tanggal)));

		// Header tabel
		$sheet->setCellValue('A4', 'No');
		$sheet->setCellValue('B4', 'Nomor Perkara');
		$sheet->setCellValue('C4', 'Jenis Perkara');
		$sheet->setCellValue('D4', 'Tanggal Putus');
		$sheet->setCellValue('E4', 'Hari Sejak Putus');
		$sheet->setCellValue('F4', 'Level Peringatan');

		// Data
		$row = 5;
		$no = 1;
		foreach ($data as $item) {
			$sheet->setCellValue('A' . $row, $no++);
			$sheet->setCellValue('B' . $row, isset($item->nomor_perkara) ? $item->nomor_perkara : '');
			$sheet->setCellValue('C' . $row, isset($item->jenis_perkara) ? $item->jenis_perkara : '');
			$sheet->setCellValue('D' . $row, isset($item->tanggal_putus) ? $item->tanggal_putus : '');
			$sheet->setCellValue('E' . $row, isset($item->hari_sejak_putus) ? $item->hari_sejak_putus : '');
			$sheet->setCellValue('F' . $row, isset($item->level_peringatan) ? $item->level_peringatan : '');
			$row++;
		}
	}
}
