-- =====================================================
-- CONTOH QUERY UNTUK MEMAHAMI PERHITUNGAN HARI SEJAK PBT
-- =====================================================

-- 1. QUERY SEDERHANA untuk melihat perhitungan dasar
SELECT
    p.nomor_perkara,
    DATE(pjs.tanggal_sidang) as tanggal_pbt,
    CURDATE() as hari_ini,
    DATEDIFF(CURDATE(), pjs.tanggal_sidang) as hari_sejak_pbt,
    CASE
        WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 7 THEN 'TERLAMBAT'
        WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 5 THEN 'URGENT'
        ELSE 'NORMAL'
    END as status
FROM
    perkara p
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE
    pjs.tanggal_sidang IS NOT NULL
ORDER BY hari_sejak_pbt DESC
LIMIT 10;

-- =====================================================

-- 2. QUERY LENGKAP seperti yang digunakan sistem
SELECT
    p.nomor_perkara,
    p.jenis_perkara_nama as jenis_perkara,
    DATE(pp.tanggal_putusan) as tanggal_putusan,
    DATE(pjs.tanggal_sidang) as tanggal_pbt,
    DATE_ADD(
        pjs.tanggal_sidang,
        INTERVAL 14 DAY
    ) as target_bht,
    DATE(pp.tanggal_bht) as tanggal_bht,
    COALESCE(pen.majelis_hakim_nama, '-') as hakim,

-- Perhitungan hari sejak PBT
DATEDIFF(CURDATE(), pjs.tanggal_sidang) as hari_sejak_pbt,

-- Status berdasarkan hari sejak PBT (ATURAN RESMI 14 HARI)
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN 'Selesai BHT'
    WHEN pjs.tanggal_sidang IS NULL THEN 'Menunggu PBT'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'Terlambat'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'Urgent'
    ELSE 'Normal'
END as status,

-- Prioritas berdasarkan hari sejak PBT (ATURAN RESMI 14 HARI)
CASE
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 21 THEN 'CRITICAL'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'HIGH'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'MEDIUM'
    ELSE 'LOW'
END as prioritas,

-- Berapa hari terlambat dari target (14 hari)
CASE
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) - 14
    ELSE 0
END as hari_terlambat
FROM
    perkara p
    LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
    LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
WHERE
    pp.tanggal_putusan IS NOT NULL -- Sudah ada putusan
    AND pjs.tanggal_sidang IS NOT NULL -- Sudah ada PBT
    AND pp.tanggal_bht IS NULL -- Belum selesai BHT
    AND YEAR(pp.tanggal_putusan) >= 2024 -- Filter tahun 2024 ke atas
ORDER BY hari_sejak_pbt DESC;

-- =====================================================

-- 3. QUERY untuk STATISTIK hari sejak PBT
SELECT 'Normal (0-5 hari)' as kategori, COUNT(*) as jumlah
FROM
    perkara p
    LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE
    pp.tanggal_putusan IS NOT NULL
    AND pjs.tanggal_sidang IS NOT NULL
    AND pp.tanggal_bht IS NULL
    AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) BETWEEN 0 AND 5
UNION ALL
SELECT 'Urgent (6-7 hari)' as kategori, COUNT(*) as jumlah
FROM
    perkara p
    LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE
    pp.tanggal_putusan IS NOT NULL
    AND pjs.tanggal_sidang IS NOT NULL
    AND pp.tanggal_bht IS NULL
    AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) BETWEEN 6 AND 7
UNION ALL
SELECT 'Terlambat (>7 hari)' as kategori, COUNT(*) as jumlah
FROM
    perkara p
    LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE
    pp.tanggal_putusan IS NOT NULL
    AND pjs.tanggal_sidang IS NOT NULL
    AND pp.tanggal_bht IS NULL
    AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 7;

-- =====================================================

-- 4. QUERY untuk melihat PERKARA PALING LAMA (seperti di screenshot)
SELECT
    p.nomor_perkara,
    DATE(pjs.tanggal_sidang) as tanggal_pbt,
    DATEDIFF(CURDATE(), pjs.tanggal_sidang) as hari_sejak_pbt,
    CONCAT(
        FLOOR(
            DATEDIFF(CURDATE(), pjs.tanggal_sidang) / 30
        ),
        ' bulan ',
        DATEDIFF(CURDATE(), pjs.tanggal_sidang) % 30,
        ' hari'
    ) as durasi_readable
FROM
    perkara p
    LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE
    pp.tanggal_putusan IS NOT NULL
    AND pjs.tanggal_sidang IS NOT NULL
    AND pp.tanggal_bht IS NULL
ORDER BY hari_sejak_pbt DESC
LIMIT 10;

-- =====================================================

-- 5. QUERY untuk TEST MANUAL perhitungan dengan tanggal spesifik
SELECT
    '2025-03-10' as tanggal_pbt,
    CURDATE() as hari_ini,
    DATEDIFF(CURDATE(), '2025-03-10') as hari_sejak_pbt,
    '235 hari seperti di screenshot' as keterangan
UNION ALL
SELECT
    '2025-03-20' as tanggal_pbt,
    CURDATE() as hari_ini,
    DATEDIFF(CURDATE(), '2025-03-20') as hari_sejak_pbt,
    '225 hari seperti di screenshot' as keterangan;