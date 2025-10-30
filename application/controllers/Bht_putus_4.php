<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller untuk BHT Perkara Putus 4
 * Focus: Pengurutan berdasarkan tanggal putus dengan fitur lebih lengkap
 * Compatible dengan PHP 5.6
 */
class Bht_putus_4 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_bht_putus_4');
        $this->load->helper(array('url', 'date', 'form'));
        $this->load->library('session');
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

        // Parameter pengurutan (fitur baru)
        $order_by = $this->input->post('order_by');
        if (empty($order_by)) $order_by = 'tanggal_putus';

        $order_dir = $this->input->post('order_dir');
        if (empty($order_dir)) $order_dir = 'DESC';

        // Jika ada pencarian berdasarkan range tanggal
        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $data['bht_putus'] = $this->M_bht_putus_4->get_bht_putus_by_date_range(
                $jenis_perkara,
                $tanggal_awal,
                $tanggal_akhir,
                $nomor_perkara,
                $order_by,
                $order_dir
            );
            $data['statistik'] = $this->M_bht_putus_4->get_statistik_bht_by_date_range(
                $jenis_perkara,
                $tanggal_awal,
                $tanggal_akhir,
                $nomor_perkara
            );
            $data['periode_text'] = 'Periode: ' . date('d/m/Y', strtotime($tanggal_awal)) . ' - ' . date('d/m/Y', strtotime($tanggal_akhir));
        } else {
            // Pencarian berdasarkan bulan dan tahun
            $data['bht_putus'] = $this->M_bht_putus_4->get_bht_putus(
                $jenis_perkara,
                $lap_bulan,
                $lap_tahun,
                $nomor_perkara,
                $order_by,
                $order_dir
            );
            $data['statistik'] = $this->M_bht_putus_4->get_statistik_bht(
                $jenis_perkara,
                $lap_bulan,
                $lap_tahun,
                $nomor_perkara
            );

            $month_names = $this->get_month_names();
            $data['periode_text'] = 'Periode: ' . $month_names[$lap_bulan] . ' ' . $lap_tahun;
        }

        // Data untuk form
        $data['jenis_perkara'] = $jenis_perkara;
        $data['lap_bulan'] = $lap_bulan;
        $data['lap_tahun'] = $lap_tahun;
        $data['tanggal_awal'] = $tanggal_awal;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['nomor_perkara'] = $nomor_perkara;
        $data['order_by'] = $order_by;
        $data['order_dir'] = $order_dir;

        // Array bulan untuk display (PHP 5.6 compatible)
        $data['months'] = $this->get_month_names();

        // Options untuk sorting
        $data['sort_options'] = array(
            'tanggal_putus' => 'Tanggal Putus',
            'nomor_perkara' => 'Nomor Perkara',
            'jenis_perkara' => 'Jenis Perkara',
            'status_bht' => 'Status BHT',
            'bht' => 'Tanggal BHT'
        );

        $data['sort_directions'] = array(
            'DESC' => 'Terbaru ke Terlama',
            'ASC' => 'Terlama ke Terbaru'
        );

        // Informasi tambahan untuk display
        $data['total_records'] = count($data['bht_putus']);

        // Load views
        $this->load->view('template/new_header', $data);
        $this->load->view('template/new_sidebar');
        $this->load->view('v_bht_putus_4', $data);
        $this->load->view('template/new_footer');
    }

    /**
     * AJAX endpoint untuk mendapatkan data dengan sorting dinamis
     */
    public function get_data_ajax()
    {
        try {
            // Ambil parameter
            $jenis_perkara = $this->input->post('jenis_perkara');
            if (empty($jenis_perkara)) $jenis_perkara = 'Pdt.G';

            $lap_bulan = $this->input->post('lap_bulan');
            if (empty($lap_bulan)) $lap_bulan = date('m');

            $lap_tahun = $this->input->post('lap_tahun');
            if (empty($lap_tahun)) $lap_tahun = date('Y');

            $tanggal_awal = $this->input->post('tanggal_awal');
            $tanggal_akhir = $this->input->post('tanggal_akhir');
            $nomor_perkara = $this->input->post('nomor_perkara');

            $order_by = $this->input->post('order_by');
            if (empty($order_by)) $order_by = 'tanggal_putus';

            $order_dir = $this->input->post('order_dir');
            if (empty($order_dir)) $order_dir = 'DESC';

            // Get data based on search type
            if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
                $bht_data = $this->M_bht_putus_4->get_bht_putus_by_date_range(
                    $jenis_perkara,
                    $tanggal_awal,
                    $tanggal_akhir,
                    $nomor_perkara,
                    $order_by,
                    $order_dir
                );
                $statistik = $this->M_bht_putus_4->get_statistik_bht_by_date_range(
                    $jenis_perkara,
                    $tanggal_awal,
                    $tanggal_akhir,
                    $nomor_perkara
                );
            } else {
                $bht_data = $this->M_bht_putus_4->get_bht_putus(
                    $jenis_perkara,
                    $lap_bulan,
                    $lap_tahun,
                    $nomor_perkara,
                    $order_by,
                    $order_dir
                );
                $statistik = $this->M_bht_putus_4->get_statistik_bht(
                    $jenis_perkara,
                    $lap_bulan,
                    $lap_tahun,
                    $nomor_perkara
                );
            }

            // Return JSON response
            $response = array(
                'success' => true,
                'data' => $bht_data,
                'statistik' => $statistik,
                'total_records' => count($bht_data),
                'sort_info' => array(
                    'order_by' => $order_by,
                    'order_dir' => $order_dir
                )
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            $response = array(
                'success' => false,
                'error' => 'Gagal memuat data: ' . $e->getMessage()
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Export data ke Excel dengan pengurutan yang sesuai
     */
    public function export_excel()
    {
        try {
            // Ambil parameter yang sama dengan tampilan
            $jenis_perkara = $this->input->get('jenis_perkara');
            if (empty($jenis_perkara)) $jenis_perkara = 'Pdt.G';

            $lap_bulan = $this->input->get('lap_bulan');
            if (empty($lap_bulan)) $lap_bulan = date('m');

            $lap_tahun = $this->input->get('lap_tahun');
            if (empty($lap_tahun)) $lap_tahun = date('Y');

            $tanggal_awal = $this->input->get('tanggal_awal');
            $tanggal_akhir = $this->input->get('tanggal_akhir');
            $nomor_perkara = $this->input->get('nomor_perkara');

            $order_by = $this->input->get('order_by');
            if (empty($order_by)) $order_by = 'tanggal_putus';

            $order_dir = $this->input->get('order_dir');
            if (empty($order_dir)) $order_dir = 'DESC';

            // Get data dengan urutan yang sama
            if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
                $bht_data = $this->M_bht_putus_4->get_bht_putus_by_date_range(
                    $jenis_perkara,
                    $tanggal_awal,
                    $tanggal_akhir,
                    $nomor_perkara,
                    $order_by,
                    $order_dir
                );
                $periode = date('d-m-Y', strtotime($tanggal_awal)) . '_sampai_' . date('d-m-Y', strtotime($tanggal_akhir));
            } else {
                $bht_data = $this->M_bht_putus_4->get_bht_putus(
                    $jenis_perkara,
                    $lap_bulan,
                    $lap_tahun,
                    $nomor_perkara,
                    $order_by,
                    $order_dir
                );
                $month_names = $this->get_month_names();
                $periode = $month_names[$lap_bulan] . '_' . $lap_tahun;
            }

            // Load PHPExcel
            require_once APPPATH . 'PHPExcel-1.8/Classes/PHPExcel.php';

            $excel = new PHPExcel();
            $excel->setActiveSheetIndex(0);
            $sheet = $excel->getActiveSheet();

            // Set judul
            $sheet->setTitle("BHT Putus 4 - $periode");

            // Header
            $sheet->setCellValue('A1', 'LAPORAN BHT PERKARA PUTUS 4');
            $sheet->setCellValue('A2', 'Periode: ' . str_replace('_', ' ', $periode));
            $sheet->setCellValue('A3', 'Jenis Perkara: ' . $jenis_perkara);
            $sheet->setCellValue('A4', 'Diurutkan berdasarkan: ' . $order_by . ' (' . $order_dir . ')');

            // Header tabel
            $headers = array(
                'No',
                'Nomor Perkara',
                'Tanggal Putus',
                'Jenis Perkara',
                'Panitera Pengganti',
                'Jurusita',
                'PBT',
                'BHT',
                'Ikrar',
                'Status BHT',
                'Kategori BHT',
                'Selisih Hari'
            );

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '6', $header);
                $col++;
            }

            // Data
            $row = 7;
            $no = 1;
            foreach ($bht_data as $item) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $item->nomor_perkara);
                $sheet->setCellValue('C' . $row, $item->tanggal_putus_formatted);
                $sheet->setCellValue('D' . $row, $item->jenis_perkara);
                $sheet->setCellValue('E' . $row, $item->panitera_pengganti_nama);
                $sheet->setCellValue('F' . $row, $item->jurusita_pengganti_nama);
                $sheet->setCellValue('G' . $row, $item->pbt_formatted);
                $sheet->setCellValue('H' . $row, $item->bht_formatted);
                $sheet->setCellValue('I' . $row, $item->ikrar_formatted);
                $sheet->setCellValue('J' . $row, $item->status_bht);
                $sheet->setCellValue('K' . $row, $item->kategori_bht);
                $sheet->setCellValue('L' . $row, $item->selisih_hari_bht ? $item->selisih_hari_bht . ' hari' : '-');
                $row++;
            }

            // Set headers untuk download
            $filename = "BHT_Perkara_Putus_4_$periode.xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $writer->save('php://output');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal export Excel: ' . $e->getMessage());
            redirect('bht_putus_4');
        }
    }

    /**
     * Helper function untuk nama bulan
     */
    private function get_month_names()
    {
        return array(
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
    }

    /**
     * Method untuk mendapatkan summary quick stats
     */
    public function get_quick_stats()
    {
        try {
            $current_month = date('m');
            $current_year = date('Y');

            // Stats untuk bulan ini
            $stats = $this->M_bht_putus_4->get_statistik_bht('Pdt.G', $current_month, $current_year);

            $response = array(
                'success' => true,
                'stats' => $stats,
                'periode' => array(
                    'bulan' => $current_month,
                    'tahun' => $current_year,
                    'nama_bulan' => $this->get_month_names()[$current_month]
                )
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            $response = array(
                'success' => false,
                'error' => $e->getMessage()
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
}
