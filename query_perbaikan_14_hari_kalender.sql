-- ==========================================
-- QUERY PERBAIKAN - ATURAN 14 HARI KALENDER
-- ==========================================
-- Berdasarkan aturan hukum acara perdata:
-- BHT dihitung 14 hari kalender dari TANGGAL PBT (bukan tanggal putusan)

-- ==========================================
-- 1. QUERY PBT MASUK - PERHITUNGAN YANG BENAR
-- ==========================================
SELECT
    p.nomor_perkara,
    p.jenis_perkara_nama AS jenis_perkara,
    DATE(pp.tanggal_putusan) AS tanggal_putusan,
    DATE(pjs.tanggal_sidang) AS tanggal_pbt,
    DATE(pp.tanggal_bht) AS tanggal_bht,
    COALESCE(pen.majelis_hakim_nama, '-') AS hakim,

-- ✅ BENAR: Target BHT = PBT + 14 hari kalender
DATE_ADD( pjs.tanggal_sidang, INTERVAL 14 DAY ) AS target_bht,

-- ✅ BENAR: Selisih hari dari PBT ke BHT
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN DATEDIFF(
        pp.tanggal_bht,
        pjs.tanggal_sidang
    )
    ELSE DATEDIFF(CURDATE(), pjs.tanggal_sidang)
END AS selisih_hari_pbt_ke_bht,

-- Status berdasarkan aturan 14 hari kalender
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN 'Sudah BHT'
    ELSE 'Belum BHT'
END AS status_bht,

-- Klasifikasi berdasarkan 14 hari kalender
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'TERLAMBAT'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'PERINGATAN'
    ELSE 'NORMAL'
END AS kategori_waktu
FROM
    perkara p
    LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
    LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
WHERE
    DATE(pjs.tanggal_sidang) = '2025-10-28' -- Tanggal PBT
    AND pjs.tanggal_sidang IS NOT NULL
    AND pp.tanggal_putusan IS NOT NULL
    AND p.jenis_perkara_nama NOT LIKE '%Cabut%'
ORDER BY pjs.tanggal_sidang DESC;

-- ==========================================
-- 2. QUERY MONITORING BHT - SEMUA STATUS
-- ==========================================
SELECT
    p.nomor_perkara,
    p.jenis_perkara_nama AS jenis_perkara,
    DATE(pp.tanggal_putusan) AS tanggal_putusan,
    DATE(pjs.tanggal_sidang) AS tanggal_pbt,
    DATE(pp.tanggal_bht) AS tanggal_bht,

-- Target BHT berdasarkan aturan 14 hari kalender
DATE_ADD( pjs.tanggal_sidang, INTERVAL 14 DAY ) AS target_bht,

-- Hari sejak PBT (yang benar untuk monitoring)
DATEDIFF(CURDATE(), pjs.tanggal_sidang) AS hari_sejak_pbt,

-- Selisih hari PBT ke BHT
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN DATEDIFF(
        pp.tanggal_bht,
        pjs.tanggal_sidang
    )
    ELSE DATEDIFF(CURDATE(), pjs.tanggal_sidang)
END AS selisih_hari,

-- Sisa hari sampai target (14 hari)
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN 0
    ELSE GREATEST(
        0,
        14 - DATEDIFF(CURDATE(), pjs.tanggal_sidang)
    )
END AS sisa_hari_target,

-- Status sesuai aturan 14 hari kalender
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI BHT'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 21 THEN 'CRITICAL (>21 hari)'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'TERLAMBAT (>14 hari)'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'PERINGATAN (>10 hari)'
    ELSE 'NORMAL (≤10 hari)'
END AS status_monitoring,

-- Progress percentage (14 hari = 100%)
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN 100
    ELSE LEAST(
        100,
        ROUND(
            (
                DATEDIFF(CURDATE(), pjs.tanggal_sidang) / 14
            ) * 100,
            1
        )
    )
END AS progress_percentage
FROM
    perkara p
    INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
    LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
WHERE
    pp.tanggal_putusan IS NOT NULL
    AND pjs.tanggal_sidang IS NOT NULL
    AND p.jenis_perkara_nama NOT LIKE '%Cabut%'
ORDER BY
    CASE
        WHEN pp.tanggal_bht IS NULL THEN 0
        ELSE 1
    END, -- Belum BHT dulu
    hari_sejak_pbt DESC;

-- ==========================================
-- 3. ANALISIS KINERJA BHT
-- ==========================================
SELECT 'Analisis Kinerja BHT Berdasarkan 14 Hari Kalender' AS keterangan, COUNT(*) AS total_kasus,

-- Sudah BHT
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL THEN 1
        ELSE 0
    END
) AS sudah_bht,

-- Belum BHT
SUM( CASE WHEN pp.tanggal_bht IS NULL THEN 1 ELSE 0 END ) AS belum_bht,

-- Kategori waktu untuk yang sudah BHT
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL
        AND DATEDIFF(
            pp.tanggal_bht,
            pjs.tanggal_sidang
        ) <= 7 THEN 1
        ELSE 0
    END
) AS bht_cepat_1_7_hari,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL
        AND DATEDIFF(
            pp.tanggal_bht,
            pjs.tanggal_sidang
        ) BETWEEN 8 AND 14  THEN 1
        ELSE 0
    END
) AS bht_normal_8_14_hari,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL
        AND DATEDIFF(
            pp.tanggal_bht,
            pjs.tanggal_sidang
        ) > 14 THEN 1
        ELSE 0
    END
) AS bht_terlambat_lebih_14_hari,

-- Kategori waktu untuk yang belum BHT
SUM(
    CASE
        WHEN pp.tanggal_bht IS NULL
        AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) <= 10 THEN 1
        ELSE 0
    END
) AS belum_bht_normal,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NULL
        AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) BETWEEN 11 AND 14  THEN 1
        ELSE 0
    END
) AS belum_bht_peringatan,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NULL
        AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) BETWEEN 15 AND 21  THEN 1
        ELSE 0
    END
) AS belum_bht_terlambat,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NULL
        AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 21 THEN 1
        ELSE 0
    END
) AS belum_bht_critical,

-- Rata-rata hari untuk yang sudah BHT
ROUND(
    AVG(
        CASE
            WHEN pp.tanggal_bht IS NOT NULL THEN DATEDIFF(
                pp.tanggal_bht,
                pjs.tanggal_sidang
            )
        END
    ),
    1
) AS rata_rata_hari_bht,

-- Persentase ketepatan waktu (≤14 hari)
ROUND(
    (
        SUM(
            CASE
                WHEN pp.tanggal_bht IS NOT NULL
                AND DATEDIFF(
                    pp.tanggal_bht,
                    pjs.tanggal_sidang
                ) <= 14 THEN 1
                ELSE 0
            END
        ) / SUM(
            CASE
                WHEN pp.tanggal_bht IS NOT NULL THEN 1
                ELSE 0
            END
        )
    ) * 100,
    1
) AS persentase_tepat_waktu
FROM
    perkara p
    INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE
    pp.tanggal_putusan IS NOT NULL
    AND pjs.tanggal_sidang IS NOT NULL
    AND p.jenis_perkara_nama NOT LIKE '%Cabut%'
    AND pjs.tanggal_sidang >= '2025-01-01';
-- Filter tahun 2025

-- ==========================================
-- 4. CONTOH KASUS UNTUK VALIDASI
-- ==========================================
-- Contoh: PBT tanggal 1 Oktober 2025
-- Target BHT: 15 Oktober 2025 (14 hari kalender)
-- Jika BHT tanggal 12 Oktober = 11 hari (NORMAL)
-- Jika BHT tanggal 20 Oktober = 19 hari (TERLAMBAT)

SELECT
    'Contoh Validasi Perhitungan' AS contoh,
    '2025-10-01' AS tanggal_pbt,
    DATE_ADD('2025-10-01', INTERVAL 14 DAY) AS target_bht_14_hari,
    '2025-10-12' AS contoh_bht_cepat,
    DATEDIFF('2025-10-12', '2025-10-01') AS selisih_hari_cepat,
    '2025-10-20' AS contoh_bht_terlambat,
    DATEDIFF('2025-10-20', '2025-10-01') AS selisih_hari_terlambat;

-- ==========================================
-- PENJELASAN PERBEDAAN
-- ==========================================
/*
❌ SALAH (Query lama):
DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) 
→ Menghitung dari TANGGAL PUTUSAN ke BHT

✅ BENAR (Query baru):
DATEDIFF(pp.tanggal_bht, pjs.tanggal_sidang)
→ Menghitung dari TANGGAL PBT ke BHT

KENAPA HARUS PBT?
- Aturan hukum: 14 hari dihitung dari PBT, bukan putusan
- PBT = Pemberitahuan kepada pihak yang tidak hadir
- Dari PBT itulah dimulai perhitungan 14 hari untuk banding
- Jika tidak ada banding dalam 14 hari → otomatis BHT
*/
