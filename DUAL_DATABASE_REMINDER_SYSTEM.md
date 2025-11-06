# SISTEM PENCATATAN REMINDER BHT - DUAL DATABASE

## 📋 OVERVIEW

Sistem pencatatan reminder BHT dengan **dual database architecture** yang memisahkan data operasional SIPP dengan data pencatatan reminder. Sistem ini memungkinkan tracking dan monitoring perkara yang belum ada PBT dan BHT tanpa mengganggu database utama SIPP.

### 🎯 Tujuan Sistem
- **Pencatatan terpisah** untuk reminder BHT/PBT
- **Tracking otomatis** status perkara dari database SIPP
- **Dashboard khusus** untuk monitoring reminder
- **Sistem peringatan** berlevel (NORMAL → PERINGATAN → KRITIS → CRITICAL)
- **Sinkronisasi otomatis** data dari SIPP ke database reminder

## 🏗️ ARSITEKTUR SISTEM

### Database Architecture
```
┌─────────────────┐    ┌───────────────────────┐
│   DATABASE 1    │    │     DATABASE 2       │
│     (SIPP)      │◄──►│  (BHT_REMINDER)      │
│  sipp_tebaru4   │    │ bht_reminder_system   │
│                 │    │                       │
│ • perkara       │    │ • perkara_reminder    │
│ • perkara_putusan│   │ • reminder_log        │
│ • perkara_biaya │    │ • pbt_tracking        │
│ • dll (readonly)│    │ • reminder_statistics │
└─────────────────┘    │ • reminder_config     │
                       └───────────────────────┘
```

### 🗃️ STRUKTUR DATABASE REMINDER

#### 1. **perkara_reminder** (Tabel Utama)
- `id` - Primary key
- `nomor_perkara` - Nomor perkara (unique)
- `perkara_id_sipp` - Reference ke database SIPP
- `jenis_perkara` - Jenis perkara
- `tanggal_putusan` - Tanggal putusan
- `status_reminder` - Status: BELUM_PBT, SUDAH_PBT_BELUM_BHT, SELESAI, CANCELLED
- `level_prioritas` - Level: NORMAL, PERINGATAN, KRITIS, CRITICAL
- `hari_sejak_putusan` - Auto calculated field
- `tanggal_target_bht` - Generated field (putusan + 14 hari)
- `catatan_reminder` - Catatan khusus
- `last_sync_sipp` - Timestamp sync terakhir

#### 2. **pbt_tracking** (Tracking PBT)
- `perkara_reminder_id` - Foreign key
- `tanggal_bayar_pbt` - Tanggal bayar biaya PBT
- `jumlah_biaya` - Jumlah biaya PBT
- `tanggal_pemberitahuan_putusan` - Tanggal PBT
- `status_pbt` - Status PBT

#### 3. **reminder_log** (Activity Log)
- `perkara_reminder_id` - Foreign key
- `activity_type` - CREATED, STATUS_CHANGE, PBT_UPDATE, dll
- `description` - Deskripsi aktivitas
- `user_id` - User yang melakukan

#### 4. **reminder_statistics** (Statistik Harian)
- `tanggal_laporan` - Tanggal laporan
- `total_perkara_reminder` - Total perkara
- `total_belum_pbt`, `total_sudah_pbt_belum_bht`, dll
- Auto generated daily stats

#### 5. **reminder_config** (Konfigurasi Sistem)
- `config_key` - Nama konfigurasi
- `config_value` - Nilai konfigurasi
- System settings dan threshold

## 🚀 INSTALASI DAN SETUP

### Step 1: Database Configuration
```php
// File: application/config/database.php

// Database SIPP (existing)
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'sipp_tebaru4',
    // ... other configs
);

// Database Reminder (new)
$db['reminder_db'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'bht_reminder_system',
    // ... other configs
);
```

### Step 2: Install Database Reminder
1. Jalankan: `http://localhost/Halimah_BHT/install_reminder_database.php`
2. Test koneksi: `http://localhost/Halimah_BHT/test_reminder_connection.php`

### Step 3: Initial Sync
1. Buka dashboard: `http://localhost/Halimah_BHT/index.php/reminder_logging`
2. Klik "Sync Manual" untuk mengisi data awal

### Step 4: Setup Auto-Sync (Optional)
```bash
# Cron job untuk auto sync setiap jam
0 * * * * /usr/bin/php /path/to/Halimah_BHT/auto_sync_cron.php

# Atau via URL (jika web server accessible)
0 * * * * wget -q -O - http://localhost/Halimah_BHT/auto_sync_cron.php
```

## 📊 FITUR SISTEM

### 🏠 Dashboard Reminder System
- **Real-time statistics** dari database reminder
- **Urgent alerts** dengan animasi
- **Color-coded priority levels**
- **Quick sync actions**
- **Connection status monitoring**

### 📋 Management Perkara
- **Daftar perkara** dengan pagination & filter
- **Detail perkara** dengan activity log
- **Update status manual**
- **Add notes** untuk tracking internal
- **Search & filter** multi-criteria

### 🔄 Sinkronisasi Data
- **Manual sync** - On-demand dari dashboard
- **Auto sync** - Via cron job
- **Update existing** - Refresh data dari SIPP
- **Selective sync** - Berdasarkan kriteria tertentu

### 📈 Reporting & Export
- **Excel export** dengan filter
- **Daily statistics** auto-generated
- **Trend analysis** (future enhancement)
- **Performance metrics**

## 🎯 SISTEM PERINGATAN

### Level Prioritas (Updated & Consistent)
- **NORMAL**: 0-10 hari sejak putusan
- **PERINGATAN**: 11-14 hari sejak putusan ⚠️
- **KRITIS**: 15-21 hari sejak putusan 🟠
- **CRITICAL**: >21 hari sejak putusan 🔴

### Status Reminder
- **BELUM_PBT**: Belum ada bayar biaya PBT
- **SUDAH_PBT_BELUM_BHT**: Sudah PBT tapi belum BHT
- **SELESAI**: Sudah selesai BHT
- **CANCELLED**: Dibatalkan/dicabut

## 🔧 KONFIGURASI SISTEM

### Database Config (reminder_config table)
```sql
INSERT INTO reminder_config VALUES
('auto_sync_enabled', '1', 'Enable/disable auto sync'),
('sync_interval_minutes', '60', 'Interval sync dalam menit'),
('critical_days_threshold', '21', 'Batas hari CRITICAL'),
('kritis_days_threshold', '14', 'Batas hari KRITIS'),
('peringatan_days_threshold', '10', 'Batas hari PERINGATAN'),
('target_bht_days', '14', 'Target hari BHT'),
('enable_email_notification', '0', 'Email notification'),
('admin_email', 'admin@pengadilan.com', 'Admin email');
```

### Customizable Settings
- Threshold hari untuk setiap level
- Auto-sync interval
- Email notifications
- Export format options

## 📁 STRUKTUR FILE

```
Halimah_BHT/
├── application/
│   ├── config/
│   │   └── database.php ✅ (Updated dual DB)
│   ├── controllers/
│   │   ├── Bht_reminder.php (Existing SIPP)
│   │   └── Reminder_logging.php ✅ (New reminder system)
│   ├── models/
│   │   ├── Menu_baru_model.php (Existing SIPP)
│   │   └── Reminder_model.php ✅ (New reminder system)
│   └── views/
│       ├── bht_reminder/ (Existing SIPP views)
│       └── reminder_logging/ ✅ (New reminder views)
│           └── dashboard.php
├── database_reminder_setup.sql ✅ (Complete SQL setup)
├── install_reminder_database.php ✅ (Setup script)
├── test_reminder_connection.php ✅ (Connection test)
├── auto_sync_cron.php ✅ (Cron job script)
└── logs/ (Auto-created for error logs)
```

## 🔗 NAVIGATION & ACCESS

### URL Mapping
- **Dashboard SIPP**: `index.php/bht_reminder`
- **Dashboard Reminder**: `index.php/reminder_logging`
- **Daftar Perkara**: `index.php/reminder_logging/perkara_list`
- **Detail Perkara**: `index.php/reminder_logging/perkara_detail/{nomor}`
- **Export Excel**: `index.php/reminder_logging/export_excel`
- **Manual Sync**: `index.php/reminder_logging/sync_manual`

### Integration Points
- Seamless navigation between SIPP dan Reminder dashboard
- Cross-reference data antara kedua sistem
- Unified user experience dengan branding konsisten

## ⚡ PERFORMANCE & OPTIMIZATION

### Database Optimization
- **Indexes** pada kolom yang sering di-query
- **Generated columns** untuk calculated fields
- **Views** untuk query yang kompleks
- **Foreign key constraints** untuk data integrity

### Query Optimization
- **Selective sync** - Only relevant cases
- **Batch processing** untuk bulk operations
- **Pagination** untuk large datasets
- **Lazy loading** untuk performance UI

### Monitoring
- **Sync status tracking**
- **Error logging** untuk troubleshooting
- **Performance metrics** collection
- **Database size monitoring**

## 🛡️ SECURITY & MAINTENANCE

### Data Security
- **Read-only access** ke database SIPP
- **Full access control** untuk database reminder
- **Activity logging** untuk audit trail
- **Data validation** pada semua input

### Maintenance Tasks
- **Daily statistics** generation
- **Log cleanup** (rotate old logs)
- **Database optimization** (periodic)
- **Backup scheduling** untuk database reminder

### Error Handling
- **Graceful failures** dengan rollback
- **Error logging** dengan context
- **Fallback mechanisms** untuk sync failures
- **User-friendly error messages**

## 📋 USAGE SCENARIOS

### Scenario 1: Daily Monitoring
1. Staff membuka Dashboard Reminder
2. Melihat urgent alerts yang perlu ditindaklanjuti
3. Filter perkara berdasarkan prioritas CRITICAL
4. Update status setelah tindakan dilakukan

### Scenario 2: Weekly Reporting
1. Export data reminder ke Excel dengan filter periode
2. Analisis trend peningkatan/penurunan case
3. Generate laporan untuk manajemen
4. Plan action items untuk minggu berikutnya

### Scenario 3: System Maintenance
1. Check sync status dan error logs
2. Run manual sync jika diperlukan
3. Monitor database performance
4. Update konfigurasi threshold jika perlu

## 🎉 BENEFITS & ADVANTAGES

### ✅ Operational Benefits
- **Separated concerns** - Tidak mengganggu database SIPP
- **Dedicated tracking** - Focus pada reminder BHT/PBT
- **Enhanced monitoring** - Real-time alerts dan statistics  
- **Better workflow** - Streamlined process untuk follow-up

### ✅ Technical Benefits
- **Scalable architecture** - Mudah untuk extend
- **Data integrity** - Consistent dengan business rules
- **Performance optimized** - Targeted queries dan indexes
- **Maintainable code** - Clean separation of concerns

### ✅ User Benefits
- **Unified interface** - Single point untuk monitoring
- **Actionable insights** - Clear prioritization
- **Flexible reporting** - Export dan filter options
- **Reduced manual work** - Automated sync dan calculations

---

## 🔧 TROUBLESHOOTING

### Common Issues
1. **Connection failed** - Check database credentials
2. **Sync errors** - Check error logs di auto_sync_error.log
3. **Missing data** - Run manual sync dari dashboard
4. **Performance slow** - Check database indexes

### Support Files
- `test_reminder_connection.php` - Connection diagnostics
- `install_reminder_database.php` - Database setup
- `logs/auto_sync_error.log` - Error tracking
- `database_reminder_setup.sql` - Complete schema

---

**Status:** ✅ **READY FOR PRODUCTION**

**Dual Database System untuk Reminder BHT berhasil diimplementasikan dengan semua fitur yang dibutuhkan!**

*Last Updated: November 2025*
