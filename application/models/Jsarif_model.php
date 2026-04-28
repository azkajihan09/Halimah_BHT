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

    public function get_sipp_perkara_detail($nomor_perkara)
    {
        $nomor_perkara = trim((string) $nomor_perkara);
        if ($nomor_perkara === '') {
            return null;
        }

        $select_cols = 'perkara_id, nomor_perkara, jenis_perkara_nama, pihak1_text, pihak2_text';

        $perkara = $this->db_jsarif
            ->select($select_cols)
            ->from('perkara')
            ->where('nomor_perkara', $nomor_perkara)
            ->limit(1)
            ->get()
            ->row();

        if (!$perkara) {
            $perkara = $this->db_jsarif
                ->select($select_cols)
                ->from('perkara')
                ->like('nomor_perkara', $nomor_perkara)
                ->limit(1)
                ->get()
                ->row();
        }

        if (!$perkara) {
            return null;
        }

        $klasifikasi = '';
        if (stripos($perkara->nomor_perkara, 'Pdt.G') !== false) {
            $klasifikasi = 'Gugatan';
        } elseif (stripos($perkara->nomor_perkara, 'Pdt.P') !== false) {
            $klasifikasi = 'Permohonan';
        }

        $nama1 = $this->_clean_html_text(isset($perkara->pihak1_text) ? $perkara->pihak1_text : '');
        $nama2 = $this->_clean_html_text(isset($perkara->pihak2_text) ? $perkara->pihak2_text : '');

        $nama_parts = array();
        if ($nama1 !== '') $nama_parts[] = $nama1;
        if ($nama2 !== '') $nama_parts[] = $nama2;
        $pihak_text = implode(' vs ', $nama_parts);

        $alamat1 = $this->_fetch_alamat_from_table('perkara_pihak1', $perkara->perkara_id);
        $alamat2 = $this->_fetch_alamat_from_table('perkara_pihak2', $perkara->perkara_id);

        $alamat_parts = array();
        if ($alamat1 !== '') $alamat_parts[] = $alamat1;
        if ($alamat2 !== '') $alamat_parts[] = $alamat2;
        $alamat_text = implode(' ; ', $alamat_parts);

        return array(
            'nomor_perkara' => $perkara->nomor_perkara,
            'jenis_perkara_nama' => $perkara->jenis_perkara_nama,
            'klasifikasi' => $klasifikasi,
            'pihak' => $pihak_text,
            'alamat' => $alamat_text
        );
    }

    private function _clean_html_text($text)
    {
        $text = (string) $text;
        $text = preg_replace('/<br\s*\/?>/i', ' ', $text);
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function _fetch_alamat_from_table($table, $perkara_id)
    {
        $db = $this->db_jsarif;
        $original_debug = isset($db->db_debug) ? $db->db_debug : false;
        $db->db_debug = false;

        if (!$db->table_exists($table)) {
            $db->db_debug = $original_debug;
            return '';
        }

        $fields = array_map('strtolower', $db->list_fields($table));
        $candidates = array('alamat', 'alamat_pihak', 'alamat_lengkap', 'tempat_tinggal');
        $alamat_col = null;
        foreach ($candidates as $c) {
            if (in_array($c, $fields, true)) {
                $alamat_col = $c;
                break;
            }
        }

        if (!$alamat_col) {
            $db->db_debug = $original_debug;
            return '';
        }

        $db->select($alamat_col . ' AS alamat');
        $db->from($table);
        $db->where('perkara_id', (int) $perkara_id);
        $rows = $db->get();
        $db->db_debug = $original_debug;

        if (!$rows) return '';

        $list = array();
        foreach ($rows->result() as $row) {
            $a = $this->_clean_html_text($row->alamat);
            if ($a !== '' && !in_array($a, $list, true)) $list[] = $a;
        }
        return implode(', ', $list);
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
