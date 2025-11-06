<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller untuk sistem pencatatan reminder BHT
 * Mengelola data perkara reminder di database terpisah
 * 
 * Features:
 * - Dashboard reminder dengan dual database
 * - Sinkronisasi otomatis dari SIPP
 * - Management perkara reminder
 * - Tracking status dan prioritas
 * - Export dan reporting
 */
class Reminder_logging extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reminder_model');
        $this->load->model('Menu_baru_model'); // Untuk akses data SIPP
        $this->load->library('pagination');
        $this->load->helper('url');
        $this->load->helper('form');
    }

    // ===============================================
    // DASHBOARD REMINDER SYSTEM
    // ===============================================

    /**
     * Dashboard utama sistem reminder
     */
    public function index()
    {
        $data['title'] = 'Dashboard Sistem Reminder BHT';

        // Get dashboard statistics
        $stats = $this->Reminder_model->get_dashboard_stats();
        $data['stats'] = $stats['summary'];
        $data['details'] = $stats['details'];

        // Get urgent alerts
        $data['urgent_alerts'] = $this->Reminder_model->get_urgent_alerts(5);

        // Get config info
        $data['last_sync'] = $this->Reminder_model->get_config('last_sync_timestamp');
        $data['auto_sync_enabled'] = $this->Reminder_model->get_config('auto_sync_enabled');
        $data['sync_interval'] = $this->Reminder_model->get_config('sync_interval_minutes');

        // Recent activities (limit 10)
        $data['recent_activities'] = $this->get_recent_activities(10);

        $this->load->view('reminder_logging/dashboard', $data);
    }

    /**
     * Halaman daftar perkara reminder dengan pagination
     */
    public function perkara_list()
    {
        $data['title'] = 'Daftar Perkara Reminder';

        // Get filters from GET/POST
        $filters = array(
            'status_reminder' => $this->input->get('status') ? $this->input->get('status') : '',
            'level_prioritas' => $this->input->get('prioritas') ? $this->input->get('prioritas') : '',
            'jenis_perkara' => $this->input->get('jenis') ? $this->input->get('jenis') : '',
            'tanggal_dari' => $this->input->get('tanggal_dari') ? $this->input->get('tanggal_dari') : '',
            'tanggal_sampai' => $this->input->get('tanggal_sampai') ? $this->input->get('tanggal_sampai') : ''
        );

        // Pagination setup
        $config['base_url'] = base_url('reminder_logging/perkara_list');
        $config['total_rows'] = $this->Reminder_model->count_perkara_reminder($filters);
        $config['per_page'] = 20;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';

        // Pagination styling
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        // Get data
        $page = $this->input->get('per_page') ? $this->input->get('per_page') : 0;
        $data['perkara_list'] = $this->Reminder_model->get_perkara_reminder($config['per_page'], $page, $filters);
        $data['pagination'] = $this->pagination->create_links();
        $data['filters'] = $filters;
        $data['total_rows'] = $config['total_rows'];
        $data['total_records'] = $config['total_rows'];

        // Get summary stats for the cards
        $stats = $this->Reminder_model->get_dashboard_stats();
        $data['summary'] = $stats['summary'];

        $this->load->view('reminder_logging/perkara_list', $data);
    }

    /**
     * Detail perkara reminder
     */
    public function perkara_detail($nomor_perkara)
    {
        $nomor_perkara = urldecode($nomor_perkara);

        $data['title'] = 'Detail Perkara Reminder';
        $data['perkara'] = $this->Reminder_model->get_perkara_by_nomor($nomor_perkara);

        if (!$data['perkara']) {
            show_404();
            return;
        }

        // Get activity log for this perkara
        $data['activities'] = $this->get_perkara_activities($nomor_perkara);

        // Get data terbaru dari SIPP untuk comparison
        $data['sipp_data'] = $this->Menu_baru_model->get_perkara_detail_by_nomor($nomor_perkara);

        $this->load->view('reminder_logging/perkara_detail', $data);
    }

    // ===============================================
    // SINKRONISASI DATA
    // ===============================================

    /**
     * Manual sync dari SIPP ke reminder database
     */
    public function sync_manual()
    {
        $limit = $this->input->post('limit') ? (int)$this->input->post('limit') : 100;

        $result = $this->Reminder_model->sync_from_sipp($limit);

        $response = array(
            'success' => true,
            'message' => "Sinkronisasi berhasil: {$result['synced_count']} dari {$result['total_processed']} perkara diproses",
            'data' => $result
        );

        if (!empty($result['errors'])) {
            $response['warnings'] = $result['errors'];
        }

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            $this->session->set_flashdata('success', $response['message']);
            redirect('reminder_logging');
        }
    }

    /**
     * Update data existing dari SIPP
     */
    public function update_from_sipp()
    {
        $nomor_perkara = $this->input->post('nomor_perkara');

        $updated_count = $this->Reminder_model->update_from_sipp($nomor_perkara);

        $message = $nomor_perkara ?
            "Data perkara {$nomor_perkara} berhasil diupdate dari SIPP" :
            "Berhasil update {$updated_count} perkara dari SIPP";

        $response = array(
            'success' => true,
            'message' => $message,
            'updated_count' => $updated_count
        );

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            $this->session->set_flashdata('success', $message);
            redirect('reminder_logging/perkara_list');
        }
    }

    /**
     * Auto sync (untuk cron job)
     */
    public function auto_sync()
    {
        // Check if auto sync is enabled
        $auto_sync_enabled = $this->Reminder_model->get_config('auto_sync_enabled');

        if (!$auto_sync_enabled) {
            $response = array('success' => false, 'message' => 'Auto sync is disabled');
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }

        // Run sync
        $result = $this->Reminder_model->sync_from_sipp(50); // Limit untuk auto sync

        // Update existing data
        $updated_count = $this->Reminder_model->update_from_sipp();

        $response = array(
            'success' => true,
            'message' => "Auto sync completed: {$result['synced_count']} new, {$updated_count} updated",
            'sync_result' => $result,
            'updated_count' => $updated_count,
            'timestamp' => date('Y-m-d H:i:s')
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // ===============================================
    // MANAGEMENT PERKARA
    // ===============================================

    /**
     * Update status perkara reminder
     */
    public function update_status()
    {
        $id = $this->input->post('id');
        $new_status = $this->input->post('status');
        $catatan = $this->input->post('catatan');

        if (!$id || !$new_status) {
            $response = array('success' => false, 'message' => 'Data tidak lengkap');
        } else {
            $result = $this->Reminder_model->update_status_reminder($id, $new_status, $catatan);

            if ($result) {
                $response = array('success' => true, 'message' => 'Status berhasil diupdate');
            } else {
                $response = array('success' => false, 'message' => 'Gagal update status');
            }
        }

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            if ($response['success']) {
                $this->session->set_flashdata('success', $response['message']);
            } else {
                $this->session->set_flashdata('error', $response['message']);
            }
            redirect('reminder_logging/perkara_list');
        }
    }

    /**
     * Tambah catatan manual ke perkara
     */
    public function add_note()
    {
        $nomor_perkara = $this->input->post('nomor_perkara');
        $catatan = $this->input->post('catatan');

        if (!$nomor_perkara || !$catatan) {
            $response = array('success' => false, 'message' => 'Data tidak lengkap');
        } else {
            // Get perkara data
            $perkara = $this->Reminder_model->get_perkara_by_nomor($nomor_perkara);

            if ($perkara) {
                // Add note to log
                $this->Reminder_model->log_reminder_activity(
                    $perkara->id,
                    $nomor_perkara,
                    'MANUAL_NOTE',
                    null,
                    null,
                    $catatan
                );

                $response = array('success' => true, 'message' => 'Catatan berhasil ditambahkan');
            } else {
                $response = array('success' => false, 'message' => 'Perkara tidak ditemukan');
            }
        }

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            if ($response['success']) {
                $this->session->set_flashdata('success', $response['message']);
            } else {
                $this->session->set_flashdata('error', $response['message']);
            }
            redirect('reminder_logging/perkara_detail/' . urlencode($nomor_perkara));
        }
    }

    // ===============================================
    // EXPORT DAN REPORTING
    // ===============================================

    /**
     * Export data reminder ke Excel
     */
    public function export_excel()
    {
        $this->load->library('excel');

        // Get filters
        $filters = array(
            'status_reminder' => $this->input->get('status') ? $this->input->get('status') : '',
            'level_prioritas' => $this->input->get('prioritas') ? $this->input->get('prioritas') : '',
            'jenis_perkara' => $this->input->get('jenis') ? $this->input->get('jenis') : '',
            'tanggal_dari' => $this->input->get('tanggal_dari') ? $this->input->get('tanggal_dari') : '',
            'tanggal_sampai' => $this->input->get('tanggal_sampai') ? $this->input->get('tanggal_sampai') : ''
        );

        // Get all data (no limit)
        $data = $this->Reminder_model->get_perkara_reminder(null, 0, $filters);

        // Create Excel file
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet = $objPHPExcel->getActiveSheet();

        // Set headers
        $activeSheet->setCellValue('A1', 'No');
        $activeSheet->setCellValue('B1', 'Nomor Perkara');
        $activeSheet->setCellValue('C1', 'Jenis Perkara');
        $activeSheet->setCellValue('D1', 'Tanggal Putusan');
        $activeSheet->setCellValue('E1', 'Status Reminder');
        $activeSheet->setCellValue('F1', 'Level Prioritas');
        $activeSheet->setCellValue('G1', 'Hari Sejak Putusan');
        $activeSheet->setCellValue('H1', 'Target BHT');
        $activeSheet->setCellValue('I1', 'Majelis Hakim');
        $activeSheet->setCellValue('J1', 'Status PBT');
        $activeSheet->setCellValue('K1', 'Tanggal Bayar PBT');
        $activeSheet->setCellValue('L1', 'Last Update');

        // Set data
        $row = 2;
        foreach ($data as $i => $perkara) {
            $activeSheet->setCellValue('A' . $row, $i + 1);
            $activeSheet->setCellValue('B' . $row, $perkara->nomor_perkara);
            $activeSheet->setCellValue('C' . $row, $perkara->jenis_perkara);
            $activeSheet->setCellValue('D' . $row, date('d/m/Y', strtotime($perkara->tanggal_putusan)));
            $activeSheet->setCellValue('E' . $row, $perkara->status_reminder);
            $activeSheet->setCellValue('F' . $row, $perkara->level_prioritas);
            $activeSheet->setCellValue('G' . $row, $perkara->hari_sejak_putusan);
            $activeSheet->setCellValue('H' . $row, date('d/m/Y', strtotime($perkara->tanggal_target_bht)));
            $activeSheet->setCellValue('I' . $row, $perkara->majelis_hakim);
            $activeSheet->setCellValue('J' . $row, $perkara->status_pbt ?: '-');
            $activeSheet->setCellValue('K' . $row, $perkara->tanggal_bayar_pbt ? date('d/m/Y', strtotime($perkara->tanggal_bayar_pbt)) : '-');
            $activeSheet->setCellValue('L' . $row, date('d/m/Y H:i', strtotime($perkara->updated_at)));
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'L') as $column) {
            $activeSheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set filename and download
        $filename = 'reminder_bht_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    // ===============================================
    // AJAX ENDPOINTS
    // ===============================================

    /**
     * Get dashboard stats via AJAX
     */
    public function ajax_dashboard_stats()
    {
        $stats = $this->Reminder_model->get_dashboard_stats();

        header('Content-Type: application/json');
        echo json_encode($stats);
    }

    /**
     * Get urgent alerts via AJAX
     */
    public function ajax_urgent_alerts()
    {
        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
        $alerts = $this->Reminder_model->get_urgent_alerts($limit);

        header('Content-Type: application/json');
        echo json_encode($alerts);
    }

    // ===============================================
    // HELPER FUNCTIONS
    // ===============================================

    /**
     * Get recent activities
     */
    private function get_recent_activities($limit = 10)
    {
        // This would be implemented in the model
        // For now, return empty array
        return array();
    }

    /**
     * Get activities for specific perkara
     */
    private function get_perkara_activities($nomor_perkara)
    {
        // This would be implemented in the model
        // For now, return empty array
        return array();
    }

    /**
     * Configuration management
     */
    public function config()
    {
        if ($this->input->post()) {
            // Update configurations
            $configs = array(
                'auto_sync_enabled' => $this->input->post('auto_sync_enabled') ? '1' : '0',
                'sync_interval_minutes' => $this->input->post('sync_interval_minutes'),
                'critical_days_threshold' => $this->input->post('critical_days_threshold'),
                'kritis_days_threshold' => $this->input->post('kritis_days_threshold'),
                'peringatan_days_threshold' => $this->input->post('peringatan_days_threshold'),
                'enable_email_notification' => $this->input->post('enable_email_notification') ? '1' : '0',
                'admin_email' => $this->input->post('admin_email')
            );

            foreach ($configs as $key => $value) {
                $this->Reminder_model->update_config($key, $value);
            }

            $this->session->set_flashdata('success', 'Konfigurasi berhasil disimpan');
            redirect('reminder_logging/config');
        }

        // Get current configs
        $data['title'] = 'Konfigurasi Sistem Reminder';
        $data['config'] = array();

        $config_keys = array(
            'auto_sync_enabled',
            'sync_interval_minutes',
            'critical_days_threshold',
            'kritis_days_threshold',
            'peringatan_days_threshold',
            'enable_email_notification',
            'admin_email'
        );

        foreach ($config_keys as $key) {
            $data['config'][$key] = $this->Reminder_model->get_config($key);
        }

        $this->load->view('reminder_logging/config', $data);
    }
}
