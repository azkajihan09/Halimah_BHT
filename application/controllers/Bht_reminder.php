<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bht_reminder extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_baru_model');
        $this->load->library('session');
        $this->load->helper(array('url', 'form', 'date'));
        
        // Check if user is logged in (optional security)
        // if (!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
    }

    public function index()
    {
        $data['title'] = 'BHT Reminder System';
        
        // Get current date parameters
        $tanggal = $this->input->get('tanggal') ? $this->input->get('tanggal') : date('Y-m-d');
        $periode = $this->input->get('periode') ? $this->input->get('periode') : date('Y-m');
        $jenis = $this->input->get('jenis') ? $this->input->get('jenis') : 'semua';
        
        $data['tanggal'] = $tanggal;
        $data['periode'] = $periode;
        $data['jenis'] = $jenis;
        
        // Get reminder data
        $data['jadwal_bht_hari_ini'] = $this->Menu_baru_model->get_jadwal_bht_harian($tanggal);
        $data['perkara_tanpa_pbt'] = $this->Menu_baru_model->get_perkara_putus_tanpa_pbt($tanggal);
        $data['berkas_pending'] = $this->Menu_baru_model->get_berkas_pending_bht();
        $data['statistik_reminder'] = $this->Menu_baru_model->get_reminder_statistics($periode);
        
        // Get categories for filter
        $data['kategori_jenis'] = $this->Menu_baru_model->get_jenis_perkara_kategori();
        
        // Priority reminders (urgent cases)
        $data['urgent_reminders'] = $this->get_urgent_reminders($tanggal);
        
        $this->load->view('template/header', $data);
        $this->load->view('template/new_sidebar', $data);
        $this->load->view('bht_reminder/dashboard', $data);
        $this->load->view('template/footer');
    }
    
    private function get_urgent_reminders($tanggal)
    {
        // Get cases that are overdue or need immediate attention
        $urgent = array();
        
        // Cases without PBT after more than 7 days
        $overdue_pbt = $this->Menu_baru_model->get_overdue_pbt_cases(7);
        foreach ($overdue_pbt as $case) {
            $urgent[] = array(
                'type' => 'overdue_pbt',
                'priority' => 'high',
                'message' => 'Perkara ' . $case->nomor_perkara . ' belum PBT selama ' . $case->hari_tertunda . ' hari',
                'data' => $case
            );
        }
        
        // Cases with PBT but no BHT after more than 14 days
        $overdue_bht = $this->Menu_baru_model->get_overdue_bht_cases(14);
        foreach ($overdue_bht as $case) {
            $urgent[] = array(
                'type' => 'overdue_bht',
                'priority' => 'medium',
                'message' => 'Perkara ' . $case->nomor_perkara . ' belum BHT selama ' . $case->hari_tertunda . ' hari setelah PBT',
                'data' => $case
            );
        }
        
        return $urgent;
    }
    
    public function get_filtered_reminders()
    {
        $tanggal = $this->input->post('tanggal') ? $this->input->post('tanggal') : date('Y-m-d');
        $jenis = $this->input->post('jenis') ? $this->input->post('jenis') : 'semua';
        $priority = $this->input->post('priority') ? $this->input->post('priority') : 'semua';
        
        $data = array();
        $data['jadwal_bht'] = $this->Menu_baru_model->get_jadwal_bht_harian($tanggal, $jenis);
        $data['perkara_tanpa_pbt'] = $this->Menu_baru_model->get_perkara_putus_tanpa_pbt($tanggal, $jenis);
        $data['urgent_reminders'] = $this->get_urgent_reminders($tanggal);
        
        // Filter by priority if specified
        if ($priority != 'semua') {
            $data['urgent_reminders'] = array_filter($data['urgent_reminders'], function($reminder) use ($priority) {
                return $reminder['priority'] == $priority;
            });
        }
        
        $data['success'] = true;
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    public function get_monthly_report()
    {
        $periode = $this->input->post('periode') ? $this->input->post('periode') : date('Y-m');
        
        $data = array();
        $data['statistik'] = $this->Menu_baru_model->get_reminder_statistics($periode);
        $data['trend_harian'] = $this->Menu_baru_model->get_daily_trend($periode);
        $data['top_jenis_tertunda'] = $this->Menu_baru_model->get_top_delayed_case_types($periode);
        $data['success'] = true;
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    public function get_chart_data()
    {
        $periode = $this->input->post('periode') ? $this->input->post('periode') : date('Y-m');
        
        $data = array();
        $data['daily_completion'] = $this->Menu_baru_model->get_daily_completion_chart($periode);
        $data['case_type_distribution'] = $this->Menu_baru_model->get_case_type_distribution($periode);
        $data['delay_analysis'] = $this->Menu_baru_model->get_delay_analysis_chart($periode);
        $data['success'] = true;
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    public function mark_handled()
    {
        $perkara_id = $this->input->post('perkara_id');
        $action_type = $this->input->post('action_type'); // 'pbt_done', 'bht_done', 'reminder_acknowledged'
        $notes = $this->input->post('notes') ? $this->input->post('notes') : '';
        
        $result = array('success' => false);
        
        if ($perkara_id && $action_type) {
            switch ($action_type) {
                case 'pbt_done':
                    $update_data = array(
                        'tanggal_pbt' => date('Y-m-d'),
                        'status_pbt' => 'SELESAI'
                    );
                    $updated = $this->Menu_baru_model->update_perkara_pbt($perkara_id, $update_data);
                    break;
                    
                case 'bht_done':
                    $update_data = array(
                        'tanggal_bht' => date('Y-m-d'),
                        'status_bht' => 'SELESAI'
                    );
                    $updated = $this->Menu_baru_model->update_perkara_bht($perkara_id, $update_data);
                    break;
                    
                case 'reminder_acknowledged':
                    // Log the acknowledgment (could be stored in a separate table)
                    $log_data = array(
                        'perkara_id' => $perkara_id,
                        'action' => 'reminder_acknowledged',
                        'notes' => $notes,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $updated = $this->Menu_baru_model->log_reminder_action($log_data);
                    break;
                    
                default:
                    $updated = false;
            }
            
            if ($updated) {
                $result['success'] = true;
                $result['message'] = 'Action berhasil disimpan';
            } else {
                $result['message'] = 'Gagal menyimpan action';
            }
        } else {
            $result['message'] = 'Parameter tidak lengkap';
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function export_report($format = 'excel')
    {
        $periode = $this->input->get('periode') ? $this->input->get('periode') : date('Y-m');
        $jenis = $this->input->get('jenis') ? $this->input->get('jenis') : 'semua';
        
        if ($format == 'excel') {
            $this->load->library('PHPExcel');
            
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet = $objPHPExcel->getActiveSheet();
            
            // Set document properties
            $objPHPExcel->getProperties()
                ->setCreator("BHT Reminder System")
                ->setLastModifiedBy("System")
                ->setTitle("Laporan BHT Reminder - " . date('F Y', strtotime($periode . '-01')))
                ->setSubject("BHT Reminder Report")
                ->setDescription("Laporan reminder BHT periode " . date('F Y', strtotime($periode . '-01')));
            
            // Header
            $sheet->setCellValue('A1', 'LAPORAN BHT REMINDER');
            $sheet->setCellValue('A2', 'Periode: ' . date('F Y', strtotime($periode . '-01')));
            $sheet->setCellValue('A3', 'Tanggal Export: ' . date('d F Y H:i:s'));
            
            // Table headers
            $headers = array('No', 'Nomor Perkara', 'Jenis Perkara', 'Tanggal Putus', 'Tanggal PBT', 'Tanggal BHT', 'Status', 'Hari Tertunda', 'Priority');
            $col = 'A';
            $row = 5;
            
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $row, $header);
                $col++;
            }
            
            // Get data
            $reminders = $this->Menu_baru_model->get_all_reminders_for_period($periode, $jenis);
            
            $row = 6;
            $no = 1;
            foreach ($reminders as $reminder) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $reminder->nomor_perkara);
                $sheet->setCellValue('C' . $row, $reminder->jenis_perkara);
                $sheet->setCellValue('D' . $row, $reminder->tanggal_putusan ? date('d/m/Y', strtotime($reminder->tanggal_putusan)) : '-');
                $sheet->setCellValue('E' . $row, $reminder->tanggal_pbt ? date('d/m/Y', strtotime($reminder->tanggal_pbt)) : '-');
                $sheet->setCellValue('F' . $row, $reminder->tanggal_bht ? date('d/m/Y', strtotime($reminder->tanggal_bht)) : '-');
                $sheet->setCellValue('G' . $row, $reminder->status_reminder);
                $sheet->setCellValue('H' . $row, $reminder->hari_tertunda);
                $sheet->setCellValue('I' . $row, $reminder->priority_level);
                $row++;
            }
            
            // Auto-size columns
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Output
            $filename = 'BHT_Reminder_Report_' . $periode . '_' . date('YmdHis') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        }
    }
    
    public function notification_api()
    {
        // API endpoint for real-time notifications
        $data = array();
        $data['urgent_count'] = count($this->get_urgent_reminders(date('Y-m-d')));
        $data['today_jadwal'] = count($this->Menu_baru_model->get_jadwal_bht_harian(date('Y-m-d')));
        $data['pending_pbt'] = count($this->Menu_baru_model->get_perkara_putus_tanpa_pbt(date('Y-m-d')));
        $data['last_updated'] = date('Y-m-d H:i:s');
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}