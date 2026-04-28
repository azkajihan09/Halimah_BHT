<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jsarif_model extends CI_Model
{
    private $db_jsarif;
    private $table = 'jsp_monitoring';

    public function __construct()
    {
        parent::__construct();
        $this->db_jsarif = $this->db;
        $this->ensure_table();
    }

    public function get_all()
    {
        $this->db_jsarif->from($this->table);
        $this->db_jsarif->order_by('updated_at', 'DESC');
        $this->db_jsarif->order_by('id', 'DESC');

        $rows = $this->db_jsarif->get()->result();
        foreach ($rows as $row) {
            $this->hydrate_row($row);
        }

        return $rows;
    }

    public function get_by_id($id)
    {
        $row = $this->db_jsarif->get_where($this->table, array('id' => (int) $id))->row();
        if ($row) {
            $this->hydrate_row($row);
        }

        return $row;
    }

    public function save($data, $id = null)
    {
        $duplicate = $this->find_by_nomor_perkara($data['no_perkara'], $id);
        if ($duplicate) {
            return array(
                'success' => false,
                'message' => 'Nomor perkara sudah terdaftar'
            );
        }

        $payload = array(
            'no_perkara' => $data['no_perkara'],
            'klasifikasi' => $data['klasifikasi'],
            'jenis' => $data['jenis'],
            'pihak' => $data['pihak'],
            'alamat' => $data['alamat'],
            'last_update' => date('Y-m-d H:i:s')
        );

        if ($id) {
            $current = $this->get_by_id($id);
            if (!$current) {
                return array(
                    'success' => false,
                    'message' => 'Data perkara tidak ditemukan'
                );
            }

            $this->db_jsarif->where('id', (int) $id);
            $this->db_jsarif->update($this->table, $payload);
            return array(
                'success' => true,
                'data' => $this->get_by_id($id)
            );
        }

        $payload['status'] = 'Dipanggil';
        $payload['tanggal_input'] = date('Y-m-d');
        $this->db_jsarif->insert($this->table, $payload);

        return array(
            'success' => true,
            'data' => $this->get_by_id($this->db_jsarif->insert_id())
        );
    }

    public function update_status($id, $status)
    {
        $current = $this->get_by_id($id);
        if (!$current) {
            return array(
                'success' => false,
                'message' => 'Data perkara tidak ditemukan'
            );
        }

        $this->db_jsarif->where('id', (int) $id);
        $this->db_jsarif->update($this->table, array(
            'status' => $status,
            'last_update' => date('Y-m-d H:i:s')
        ));

        return array(
            'success' => true,
            'data' => $this->get_by_id($id)
        );
    }

    public function delete($id)
    {
        $this->db_jsarif->where('id', (int) $id);
        $this->db_jsarif->limit(1);
        $this->db_jsarif->delete($this->table);

        return $this->db_jsarif->affected_rows() > 0;
    }

    public function search_sipp_perkara($keyword, $limit = 15)
    {
        $keyword = trim((string) $keyword);
        if ($keyword === '') {
            return array();
        }

        $this->db_jsarif->select('nomor_perkara, jenis_perkara_nama');
        $this->db_jsarif->from('perkara');
        $this->db_jsarif->like('nomor_perkara', $keyword);
        $this->db_jsarif->order_by('perkara_id', 'DESC');
        $this->db_jsarif->limit((int) $limit);

        $rows = $this->db_jsarif->get()->result();

        foreach ($rows as $row) {
            if (stripos($row->nomor_perkara, 'Pdt.G') !== false) {
                $row->klasifikasi = 'Gugatan';
            } elseif (stripos($row->nomor_perkara, 'Pdt.P') !== false) {
                $row->klasifikasi = 'Permohonan';
            } else {
                $row->klasifikasi = '';
            }
        }

        return $rows;
    }

    private function find_by_nomor_perkara($no_perkara, $exclude_id = null)
    {
        $this->db_jsarif->from($this->table);
        $this->db_jsarif->where('no_perkara', $no_perkara);
        if ($exclude_id) {
            $this->db_jsarif->where('id !=', (int) $exclude_id);
        }

        return $this->db_jsarif->get()->row();
    }

    private function hydrate_row(&$row)
    {
        $row->last_update_label = $row->last_update
            ? date('d/m/Y H:i', strtotime($row->last_update))
            : '-';
    }

    private function ensure_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`no_perkara` varchar(100) NOT NULL,
			`klasifikasi` enum('Gugatan','Permohonan') NOT NULL DEFAULT 'Gugatan',
			`jenis` varchar(150) NOT NULL,
			`pihak` varchar(255) NOT NULL,
			`alamat` text NOT NULL,
			`status` enum('Dipanggil','Sidang Pertama','Sidang Lanjutan','Putusan','PBT') NOT NULL DEFAULT 'Dipanggil',
			`tanggal_input` date NOT NULL,
			`last_update` datetime NOT NULL,
			`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			UNIQUE KEY `uniq_no_perkara` (`no_perkara`),
			KEY `idx_status` (`status`),
			KEY `idx_last_update` (`last_update`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db_jsarif->query($sql);
    }
}
