<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| BHT Putus 4 Configuration
|--------------------------------------------------------------------------
| Konfigurasi khusus untuk sistem BHT Putus 4 dengan fitur sorting
|
*/

// Default sorting options
$config['bht_putus_4_default_sort'] = 'tanggal_putus';
$config['bht_putus_4_default_direction'] = 'DESC';

// Available sort fields
$config['bht_putus_4_sort_fields'] = array(
    'tanggal_putus' => 'Tanggal Putus',
    'nomor_perkara' => 'Nomor Perkara',
    'jenis_perkara' => 'Jenis Perkara',
    'status_bht' => 'Status BHT',
    'bht' => 'Tanggal BHT'
);

// Available sort directions
$config['bht_putus_4_sort_directions'] = array(
    'DESC' => 'Terbaru ke Terlama',
    'ASC' => 'Terlama ke Terbaru'
);

// Export settings
$config['bht_putus_4_export_limit'] = 5000; // Maximum rows for export
$config['bht_putus_4_export_formats'] = array('excel', 'pdf', 'csv');

// Pagination settings
$config['bht_putus_4_per_page'] = 25;
$config['bht_putus_4_max_per_page'] = 100;

// Date range limits (months)
$config['bht_putus_4_max_date_range'] = 12;

// Cache settings for performance
$config['bht_putus_4_cache_enabled'] = FALSE;
$config['bht_putus_4_cache_duration'] = 300; // 5 minutes

/* End of file bht_putus_4.php */
/* Location: ./application/config/bht_putus_4.php */
