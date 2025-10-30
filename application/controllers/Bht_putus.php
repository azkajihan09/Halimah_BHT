<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bht_putus extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_bht_putus');
	}

	public function index()
	{
		// Set default values
		$jenis_perkara = $this->input->post('jenis_perkara') ?: 'Pdt.G';
		$lap_bulan = $this->input->post('lap_bulan') ?: date('m');
		$lap_tahun = $this->input->post('lap_tahun') ?: date('Y');

		$data['bht_putus'] = $this->M_bht_putus->get_bht_putus($jenis_perkara, $lap_bulan, $lap_tahun);
		$data['statistik'] = $this->M_bht_putus->get_statistik_bht($jenis_perkara, $lap_bulan, $lap_tahun);
		$data['jenis_perkara'] = $jenis_perkara;
		$data['lap_bulan'] = $lap_bulan;
		$data['lap_tahun'] = $lap_tahun;

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_bht_putus', $data);
		$this->load->view('template/new_footer');
	}
}
