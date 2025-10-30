<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Load necessary models
		$this->load->model('Dashboard_model');
	}

	public function index()
	{
		// Get current year for default data
		$year = date('Y');

		// Get dashboard data from model
		$data = array(
			'year' => $year,
			'perkara_diterima' => $this->Dashboard_model->get_perkara_diterima($year),
			'perkara_putus' => $this->Dashboard_model->get_perkara_putus($year),
			'perkara_minutasi' => $this->Dashboard_model->get_perkara_minutasi($year),
			'perkara_sisa' => $this->Dashboard_model->get_perkara_sisa($year),
			'monthly_stats' => $this->Dashboard_model->get_monthly_stats($year),
			'case_types' => $this->Dashboard_model->get_case_type_stats($year)
		);

		// Available years for filter
		$data['available_years'] = range(2016, date('Y'));

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('dashboard', $data);
		$this->load->view('template/new_footer');
	}
}
