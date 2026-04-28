<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jsarif extends CI_Controller
{
    private $status_options = array('Dipanggil', 'Sidang Pertama', 'Sidang Lanjutan', 'Putusan', 'PBT');

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->model('Jsarif_model', 'jsarif');
    }

    public function index()
    {
        $data = array(
            'title' => 'SI-JSP | Monitoring Perkara',
            'page_title' => 'Ekspor & Pantau Data',
            'status_options' => $this->status_options
        );

        $this->load->view('template/new_header');
        $this->load->view('template/new_sidebar');
        $this->load->view('jsarif/index', $data);
        $this->load->view('template/new_footer');
    }

    public function ajax_list()
    {
        $this->_json_response(array(
            'success' => true,
            'data' => $this->jsarif->get_all()
        ));
    }

    public function ajax_save()
    {
        $id = (int) $this->input->post('id');
        $payload = array(
            'no_perkara' => trim((string) $this->input->post('no_perkara')),
            'klasifikasi' => trim((string) $this->input->post('klasifikasi')),
            'jenis' => trim((string) $this->input->post('jenis')),
            'pihak' => trim((string) $this->input->post('pihak')),
            'alamat' => trim((string) $this->input->post('alamat'))
        );

        $validation = $this->_validate_payload($payload);
        if ($validation !== true) {
            $this->_json_response(array('success' => false, 'message' => $validation), 422);
            return;
        }

        $result = $this->jsarif->save($payload, $id ?: null);
        if ($result['success']) {
            $this->_json_response(array(
                'success' => true,
                'message' => $id ? 'Data perkara berhasil diperbarui' : 'Data perkara berhasil disimpan',
                'data' => $result['data']
            ));
            return;
        }

        $this->_json_response(array(
            'success' => false,
            'message' => $result['message']
        ), 422);
    }

    public function ajax_update_status()
    {
        $id = (int) $this->input->post('id');
        $status = trim((string) $this->input->post('status'));

        if (!$id || !in_array($status, $this->status_options, true)) {
            $this->_json_response(array('success' => false, 'message' => 'Data status tidak valid'), 422);
            return;
        }

        $result = $this->jsarif->update_status($id, $status);
        if (!$result['success']) {
            $this->_json_response($result, 404);
            return;
        }

        $this->_json_response(array(
            'success' => true,
            'message' => 'Status berhasil diperbarui',
            'data' => $result['data']
        ));
    }

    public function ajax_delete()
    {
        $id = (int) $this->input->post('id');

        if (!$id) {
            $this->_json_response(array('success' => false, 'message' => 'ID perkara tidak valid'), 422);
            return;
        }

        $result = $this->jsarif->delete($id);
        if (!$result) {
            $this->_json_response(array('success' => false, 'message' => 'Data perkara tidak ditemukan'), 404);
            return;
        }

        $this->_json_response(array('success' => true, 'message' => 'Data perkara berhasil dihapus'));
    }

    public function export_excel()
    {
        require_once APPPATH . 'PHPExcel-1.8/Classes/PHPExcel.php';
        require_once APPPATH . 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';

        $rows = $this->jsarif->get_all();

        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Monitoring JSP');

        $sheet->setCellValue('A1', 'Laporan Monitoring Perkara SI-JSP');
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d/m/Y H:i'));
        $sheet->mergeCells('A2:F2');

        $headers = array('No. Perkara', 'Klasifikasi', 'Jenis', 'Para Pihak', 'Alamat', 'Status', 'Update');
        $columns = array('A', 'B', 'C', 'D', 'E', 'F', 'G');

        foreach ($headers as $index => $header) {
            $sheet->setCellValue($columns[$index] . '4', $header);
        }

        $rowNumber = 5;
        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $rowNumber, $row->no_perkara);
            $sheet->setCellValue('B' . $rowNumber, $row->klasifikasi);
            $sheet->setCellValue('C' . $rowNumber, $row->jenis);
            $sheet->setCellValue('D' . $rowNumber, $row->pihak);
            $sheet->setCellValue('E' . $rowNumber, $row->alamat);
            $sheet->setCellValue('F' . $rowNumber, $row->status);
            $sheet->setCellValue('G' . $rowNumber, $row->last_update_label);
            $rowNumber++;
        }

        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Monitoring_JSP_' . date('Ymd_His') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    private function _validate_payload($payload)
    {
        if ($payload['no_perkara'] === '' || $payload['jenis'] === '' || $payload['pihak'] === '' || $payload['alamat'] === '') {
            return 'Semua field input wajib diisi';
        }

        if (!in_array($payload['klasifikasi'], array('Gugatan', 'Permohonan'), true)) {
            return 'Klasifikasi tidak valid';
        }

        return true;
    }

    private function _json_response($payload, $status_code = 200)
    {
        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }
}
