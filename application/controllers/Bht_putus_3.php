<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bht_putus_3 extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_bht_putus_3');
	}

	public function index()
	{
		// Set default values (PHP 5.6 compatible)
		$jenis_perkara = $this->input->post('jenis_perkara');
		if (empty($jenis_perkara)) $jenis_perkara = 'Pdt.G';

		$lap_bulan = $this->input->post('lap_bulan');
		if (empty($lap_bulan)) $lap_bulan = date('m');

		$lap_tahun = $this->input->post('lap_tahun');
		if (empty($lap_tahun)) $lap_tahun = date('Y');

		$tanggal_awal = $this->input->post('tanggal_awal');
		if (empty($tanggal_awal)) $tanggal_awal = '';

		$tanggal_akhir = $this->input->post('tanggal_akhir');
		if (empty($tanggal_akhir)) $tanggal_akhir = '';

		$nomor_perkara = $this->input->post('nomor_perkara');
		if (empty($nomor_perkara)) $nomor_perkara = '';

		// Jika ada pencarian berdasarkan range tanggal
		if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
			$data['bht_putus'] = $this->M_bht_putus_3->get_bht_putus_by_date_range($jenis_perkara, $tanggal_awal, $tanggal_akhir, $nomor_perkara);
			$data['statistik'] = $this->M_bht_putus_3->get_statistik_bht_by_date_range($jenis_perkara, $tanggal_awal, $tanggal_akhir, $nomor_perkara);
		} else {
			// Pencarian berdasarkan bulan dan tahun
			$data['bht_putus'] = $this->M_bht_putus_3->get_bht_putus($jenis_perkara, $lap_bulan, $lap_tahun, $nomor_perkara);
			$data['statistik'] = $this->M_bht_putus_3->get_statistik_bht($jenis_perkara, $lap_bulan, $lap_tahun, $nomor_perkara);
		}

		$data['jenis_perkara'] = $jenis_perkara;
		$data['lap_bulan'] = $lap_bulan;
		$data['lap_tahun'] = $lap_tahun;
		$data['tanggal_awal'] = $tanggal_awal;
		$data['tanggal_akhir'] = $tanggal_akhir;
		$data['nomor_perkara'] = $nomor_perkara;

		// Array bulan untuk display (PHP 5.6 compatible)
		$data['months'] = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember'
		);

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_bht_putus_3', $data);
		$this->load->view('template/new_footer');
	}
}
