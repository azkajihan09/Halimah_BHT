<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller untuk sistem notelen (berkas masuk perkara putus)
 * Fitur popup form untuk entry data inventaris
 */
class Notelen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Notelen_model', 'notelen');
        $this->load->helper(array('url', 'form'));
    }

    // ===============================================
    // MAIN BERKAS MASUK PAGE
    // ===============================================

    /**
     * Dashboard berkas masuk notelen
     */
    public function index()
    {
        try {
            // Get filters from session or request
            $filters = array(
                'status_berkas' => $this->input->get('status') ?: $this->session->userdata('notelen_filter_status') ?: '',
                'nomor_perkara' => $this->input->get('nomor') ?: $this->session->userdata('notelen_filter_nomor') ?: '',
                'tanggal_dari' => $this->input->get('dari') ?: $this->session->userdata('notelen_filter_dari') ?: '',
                'tanggal_sampai' => $this->input->get('sampai') ?: $this->session->userdata('notelen_filter_sampai') ?: ''
            );

            // Save filters to session
            $this->session->set_userdata('notelen_filter_status', $filters['status_berkas']);
            $this->session->set_userdata('notelen_filter_nomor', $filters['nomor_perkara']);
            $this->session->set_userdata('notelen_filter_dari', $filters['tanggal_dari']);
            $this->session->set_userdata('notelen_filter_sampai', $filters['tanggal_sampai']);

            // Pagination
            $page = $this->input->get('page') ?: 1;
            $limit = 20;
            $offset = ($page - 1) * $limit;

            // Get data
            $berkas_list = $this->notelen->get_berkas_masuk($limit, $offset, $filters);
            $total_berkas = $this->notelen->count_berkas_masuk($filters);

            // Dashboard stats
            $stats = $this->notelen->get_dashboard_stats();

            // Master barang untuk popup
            $master_barang = $this->notelen->get_master_barang();

            $data = array(
                'title' => 'Berkas Masuk Notelen',
                'page_title' => 'Berkas Masuk untuk Notelen',
                'berkas_list' => $berkas_list,
                'total_berkas' => $total_berkas,
                'current_page' => $page,
                'total_pages' => ceil($total_berkas / $limit),
                'limit' => $limit,
                'filters' => $filters,
                'stats' => $stats,
                'master_barang' => $master_barang,
                'sidebar_active' => 'notelen',
                'submenu_active' => 'berkas_masuk'
            );

            $this->load->view('notelen/berkas_masuk_fixed', $data);
        } catch (Exception $e) {
            echo "<div style='padding: 20px; background: #f8f9fa; border: 1px solid #ddd;'>";
            echo "<h3>Error Loading Notelen System</h3>";
            echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
            echo "<p><strong>File:</strong> " . $e->getFile() . " (Line " . $e->getLine() . ")</p>";
            echo "<p>Please check database connection and model files.</p>";
            echo "</div>";
        }

        // Original code (commented out for debugging)
        /*
        // Get filters from session or request
        $filters = array(
            'status_berkas' => $this->input->get('status') ?: $this->session->userdata('notelen_filter_status') ?: '',
            'nomor_perkara' => $this->input->get('nomor') ?: $this->session->userdata('notelen_filter_nomor') ?: '',
            'tanggal_dari' => $this->input->get('dari') ?: $this->session->userdata('notelen_filter_dari') ?: '',
            'tanggal_sampai' => $this->input->get('sampai') ?: $this->session->userdata('notelen_filter_sampai') ?: ''
        );

        // Save filters to session
        $this->session->set_userdata('notelen_filter_status', $filters['status_berkas']);
        $this->session->set_userdata('notelen_filter_nomor', $filters['nomor_perkara']);
        $this->session->set_userdata('notelen_filter_dari', $filters['tanggal_dari']);
        $this->session->set_userdata('notelen_filter_sampai', $filters['tanggal_sampai']);

        // Pagination
        $page = $this->input->get('page') ?: 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get data
        $berkas_list = $this->notelen->get_berkas_masuk($limit, $offset, $filters);
        $total_berkas = $this->notelen->count_berkas_masuk($filters);
        
        // Dashboard stats
        $stats = $this->notelen->get_dashboard_stats();
        
        // Master barang untuk popup
        $master_barang = $this->notelen->get_master_barang();

        $data = array(
            'title' => 'Berkas Masuk Notelen',
            'page_title' => 'Berkas Masuk untuk Notelen',
            'berkas_list' => $berkas_list,
            'total_berkas' => $total_berkas,
            'current_page' => $page,
            'total_pages' => ceil($total_berkas / $limit),
            'limit' => $limit,
            'filters' => $filters,
            'stats' => $stats,
            'master_barang' => $master_barang,
            'sidebar_active' => 'notelen',
            'submenu_active' => 'berkas_masuk'
        );

        $this->load->view('notelen/berkas_masuk', $data);
        */
    }

    // ===============================================
    // AJAX ENDPOINTS
    // ===============================================

    /**
     * Get berkas detail via AJAX untuk popup
     */
    public function ajax_get_berkas()
    {
        $id = $this->input->post('id');

        if (!$id) {
            echo json_encode(array('success' => false, 'message' => 'ID tidak valid'));
            return;
        }

        $berkas = $this->notelen->get_berkas_by_id($id);
        $inventaris = $this->notelen->get_inventaris_by_berkas($id);

        if (!$berkas) {
            echo json_encode(array('success' => false, 'message' => 'Berkas tidak ditemukan'));
            return;
        }

        // Format data
        $data = array(
            'success' => true,
            'berkas' => array(
                'id' => $berkas->id,
                'nomor_perkara' => $berkas->nomor_perkara,
                'jenis_perkara' => $berkas->jenis_perkara,
                'tanggal_putusan' => $berkas->tanggal_putusan,
                'tanggal_masuk_notelen' => $berkas->tanggal_masuk_notelen,
                'majelis_hakim' => $berkas->majelis_hakim ?: '-',
                'panitera_pengganti' => $berkas->panitera_pengganti ?: '-',
                'status_berkas' => $berkas->status_berkas,
                'catatan_notelen' => $berkas->catatan_notelen
            ),
            'inventaris' => array()
        );

        foreach ($inventaris as $item) {
            $data['inventaris'][] = array(
                'id' => $item->id,
                'nama_barang' => $item->nama_barang,
                'jumlah' => $item->jumlah,
                'satuan_barang' => $item->satuan_barang,
                'kondisi_barang' => $item->kondisi_barang,
                'keterangan' => $item->keterangan,
                'tanggal_masuk' => $item->tanggal_masuk
            );
        }

        echo json_encode($data);
    }

    /**
     * Insert berkas baru via AJAX
     */
    public function ajax_insert_berkas()
    {
        $nomor_perkara = trim($this->input->post('nomor_perkara'));
        $tanggal_putusan = $this->input->post('tanggal_putusan');
        $jenis_perkara = $this->input->post('jenis_perkara');
        $majelis_hakim = $this->input->post('majelis_hakim');
        $panitera_pengganti = $this->input->post('panitera_pengganti');
        $catatan = $this->input->post('catatan_notelen');

        // Validasi
        if (empty($nomor_perkara) || empty($tanggal_putusan)) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Nomor perkara dan tanggal putusan harus diisi'
            ));
            return;
        }

        // Check existing
        $existing = $this->notelen->get_berkas_by_nomor($nomor_perkara);
        if ($existing) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Berkas dengan nomor perkara ini sudah ada'
            ));
            return;
        }

        $data = array(
            'nomor_perkara' => $nomor_perkara,
            'perkara_id_sipp' => 0, // Manual entry
            'jenis_perkara' => $jenis_perkara,
            'tanggal_putusan' => $tanggal_putusan,
            'majelis_hakim' => $majelis_hakim,
            'panitera_pengganti' => $panitera_pengganti,
            'status_berkas' => 'MASUK',
            'catatan_notelen' => $catatan
        );

        $berkas_id = $this->notelen->insert_berkas_masuk($data);

        if ($berkas_id) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Berkas berhasil ditambahkan',
                'berkas_id' => $berkas_id
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Gagal menambahkan berkas'
            ));
        }
    }

    /**
     * Update status berkas
     */
    public function ajax_update_status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $catatan = $this->input->post('catatan');

        if (!$id || !$status) {
            echo json_encode(array('success' => false, 'message' => 'Data tidak lengkap'));
            return;
        }

        $result = $this->notelen->update_status_berkas($id, $status, $catatan);

        if ($result) {
            echo json_encode(array('success' => true, 'message' => 'Status berhasil diupdate'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Gagal update status'));
        }
    }

    // ===============================================
    // INVENTARIS MANAGEMENT
    // ===============================================

    /**
     * Add inventaris via AJAX
     */
    public function ajax_add_inventaris()
    {
        $berkas_id = $this->input->post('berkas_id');
        $master_barang_id = $this->input->post('master_barang_id');
        $jumlah = $this->input->post('jumlah');
        $kondisi = $this->input->post('kondisi_barang');
        $keterangan = $this->input->post('keterangan');

        // Validasi
        if (!$berkas_id || !$master_barang_id || !$jumlah || $jumlah <= 0) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Data tidak lengkap atau jumlah tidak valid'
            ));
            return;
        }

        $data = array(
            'berkas_masuk_id' => $berkas_id,
            'master_barang_id' => $master_barang_id,
            'jumlah' => $jumlah,
            'kondisi_barang' => $kondisi ?: 'BAIK',
            'keterangan' => $keterangan,
            'tanggal_masuk' => date('Y-m-d')
        );

        $result = $this->notelen->insert_inventaris($data);

        if ($result) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Inventaris berhasil ditambahkan'
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Gagal menambahkan inventaris'
            ));
        }
    }

    /**
     * Delete inventaris
     */
    public function ajax_delete_inventaris()
    {
        $id = $this->input->post('id');

        if (!$id) {
            echo json_encode(array('success' => false, 'message' => 'ID tidak valid'));
            return;
        }

        $result = $this->notelen->delete_inventaris($id);

        if ($result) {
            echo json_encode(array('success' => true, 'message' => 'Inventaris berhasil dihapus'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Gagal menghapus inventaris'));
        }
    }

    // ===============================================
    // MASTER BARANG MANAGEMENT
    // ===============================================

    /**
     * Get all master barang via AJAX
     */
    public function ajax_get_master_barang()
    {
        $master_barang = $this->notelen->get_master_barang();

        $data = array();
        foreach ($master_barang as $item) {
            $data[] = array(
                'id' => $item->id,
                'nama_barang' => $item->nama_barang,
                'barcode' => $item->barcode,
                'satuan_barang' => $item->satuan_barang
            );
        }

        echo json_encode(array('success' => true, 'data' => $data));
    }

    /**
     * Add master barang via AJAX
     */
    public function ajax_add_master_barang()
    {
        $nama_barang = trim($this->input->post('nama_barang'));
        $barcode = trim($this->input->post('barcode'));
        $satuan = trim($this->input->post('satuan_barang'));
        $peringatan_stok = $this->input->post('peringatan_stok') ?: 10;

        if (empty($nama_barang) || empty($satuan)) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Nama barang dan satuan harus diisi'
            ));
            return;
        }

        $data = array(
            'nama_barang' => $nama_barang,
            'barcode' => $barcode,
            'satuan_barang' => $satuan,
            'peringatan_stok' => $peringatan_stok
        );

        $result = $this->notelen->insert_master_barang($data);

        if ($result) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Master barang berhasil ditambahkan'
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Gagal menambahkan master barang'
            ));
        }
    }

    // ===============================================
    // SYNC & UTILITIES
    // ===============================================

    /**
     * Sync manual dari SIPP
     */
    public function ajax_sync_sipp()
    {
        // Set JSON header
        header('Content-Type: application/json');

        try {
            $limit = (int)($this->input->post('limit') ?: 50);

            // Validate limit
            if ($limit < 1 || $limit > 500) {
                $limit = 50;
            }

            $result = $this->notelen->sync_perkara_putus($limit);

            echo json_encode(array(
                'success' => true,
                'message' => "Berhasil sync {$result['synced_count']} dari {$result['total_processed']} perkara",
                'data' => $result
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Error sync: ' . $e->getMessage(),
                'error_details' => array(
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                )
            ));
        }
    }

    /**
     * Dashboard stats untuk refresh
     */
    public function ajax_get_stats()
    {
        $stats = $this->notelen->get_dashboard_stats();
        echo json_encode(array('success' => true, 'stats' => $stats));
    }

    /**
     * Recent activities untuk sidebar
     */
    public function ajax_get_activities()
    {
        $activities = $this->notelen->get_recent_activities(5);
        echo json_encode(array('success' => true, 'activities' => $activities));
    }

    // ===============================================
    // EXPORT & PRINT
    // ===============================================

    /**
     * Export berkas ke Excel/PDF
     */
    public function export()
    {
        $format = $this->input->get('format') ?: 'excel';

        // Get filters
        $filters = array(
            'status_berkas' => $this->session->userdata('notelen_filter_status') ?: '',
            'nomor_perkara' => $this->session->userdata('notelen_filter_nomor') ?: '',
            'tanggal_dari' => $this->session->userdata('notelen_filter_dari') ?: '',
            'tanggal_sampai' => $this->session->userdata('notelen_filter_sampai') ?: ''
        );

        // Get all data (no limit)
        $berkas_list = $this->notelen->get_berkas_masuk(null, 0, $filters);

        if ($format === 'excel') {
            $this->_export_excel($berkas_list);
        } else {
            echo "Format export belum didukung";
        }
    }

    /**
     * Export to Excel
     */
    private function _export_excel($data)
    {
        // Load PHPExcel
        require_once APPPATH . 'third_party/PHPExcel-1.8/Classes/PHPExcel.php';

        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nomor Perkara');
        $sheet->setCellValue('C1', 'Jenis Perkara');
        $sheet->setCellValue('D1', 'Tanggal Putusan');
        $sheet->setCellValue('E1', 'Tanggal Masuk');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Total Inventaris');
        $sheet->setCellValue('H1', 'Majelis Hakim');

        // Data
        $row = 2;
        foreach ($data as $index => $berkas) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $berkas->nomor_perkara);
            $sheet->setCellValue('C' . $row, $berkas->jenis_perkara);
            $sheet->setCellValue('D' . $row, $berkas->tanggal_putusan);
            $sheet->setCellValue('E' . $row, $berkas->tanggal_masuk_notelen);
            $sheet->setCellValue('F' . $row, $berkas->status_berkas);
            $sheet->setCellValue('G' . $row, $berkas->total_inventaris ?: 0);
            $sheet->setCellValue('H' . $row, $berkas->majelis_hakim);
            $row++;
        }

        // Output
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="berkas_notelen_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
    }

    /**
     * AJAX: Get perkara putus for dropdown
     */
    public function ajax_get_perkara_dropdown()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $search = $this->input->get('search') ?: '';
            $perkara_list = $this->notelen->get_perkara_putus_dropdown($search, 50);

            $response = array(
                'success' => true,
                'data' => $perkara_list
            );
        } catch (Exception $e) {
            $response = array(
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            );
        }

        echo json_encode($response);
    }

    /**
     * AJAX: Get perkara detail by nomor
     */
    public function ajax_get_perkara_detail()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $nomor_perkara = $this->input->post('nomor_perkara');

            if (empty($nomor_perkara)) {
                throw new Exception('Nomor perkara tidak boleh kosong');
            }

            $detail = $this->notelen->get_perkara_detail_by_nomor($nomor_perkara);

            if (!$detail) {
                throw new Exception('Data perkara tidak ditemukan');
            }

            $response = array(
                'success' => true,
                'data' => $detail
            );
        } catch (Exception $e) {
            $response = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }

        echo json_encode($response);
    }
}
