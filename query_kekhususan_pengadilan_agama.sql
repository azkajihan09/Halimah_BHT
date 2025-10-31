-- ==========================================
-- QUERY KEKHUSUSAN PENGADILAN AGAMA
-- ==========================================
-- Implementasi 3 kategori BHT sesuai prosedur PA

-- ==========================================
-- 1. QUERY MONITORING SEMUA JENIS PERKARA PA
-- ==========================================
SELECT
    p.nomor_perkara,
    p.jenis_perkara_nama AS jenis_perkara,
    DATE(pp.tanggal_putusan) AS tanggal_putusan,
    DATE(pjs.tanggal_sidang) AS tanggal_pbt,
    DATE(pp.tanggal_bht) AS tanggal_bht,

-- Selisih hari PBT ke BHT
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN DATEDIFF(
        pp.tanggal_bht,
        pjs.tanggal_sidang
    )
    ELSE DATEDIFF(CURDATE(), pjs.tanggal_sidang)
END AS hari_sejak_pbt,

-- Target BHT berdasarkan jenis perkara
CASE
    WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%' THEN CONCAT(
        'Izin: ',
        DATE_FORMAT(
            DATE_ADD(
                pjs.tanggal_sidang,
                INTERVAL 14 DAY
            ),
            '%d/%m/%Y'
        ),
        ' | Max Ikrar: ',
        DATE_FORMAT(
            DATE_ADD(
                pjs.tanggal_sidang,
                INTERVAL 6 MONTH
            ),
            '%d/%m/%Y'
        )
    )
    ELSE DATE_FORMAT(
        DATE_ADD(
            pjs.tanggal_sidang,
            INTERVAL 14 DAY
        ),
        '%d/%m/%Y'
    )
END AS target_info,

-- Status BHT dengan kekhususan PA
CASE
    WHEN pp.tanggal_bht IS NOT NULL THEN 'SELESAI BHT'
    WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%' THEN CASE
        WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'TERLAMBAT - Izin Talak Belum BHT'
        ELSE 'PROSES - Menunggu BHT Izin Talak'
    END
    WHEN p.jenis_perkara_nama LIKE '%Cerai Gugat%' THEN CASE
        WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'TERLAMBAT - Belum BHT'
        ELSE 'PROSES - Menunggu BHT'
    END
    ELSE -- Kasus umum (Waris, Isbat, dll)
    CASE
        WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'TERLAMBAT - Belum BHT'
        ELSE 'PROSES - Menunggu BHT'
    END
END AS status_detail,

-- Kategori perkara PA
CASE
    WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%' THEN 'CERAI_TALAK'
    WHEN p.jenis_perkara_nama LIKE '%Cerai Gugat%' THEN 'CERAI_GUGAT'
    WHEN p.jenis_perkara_nama LIKE '%Waris%' THEN 'WARIS'
    WHEN p.jenis_perkara_nama LIKE '%Isbat%' THEN 'ISBAT_NIKAH'
    WHEN p.jenis_perkara_nama LIKE '%Wasiat%' THEN 'WASIAT'
    WHEN p.jenis_perkara_nama LIKE '%Hibah%' THEN 'HIBAH'
    ELSE 'UMUM'
END AS kategori_pa,

-- Prioritas berdasarkan kekhususan PA
CASE
    WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%'
    AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 21 THEN 'CRITICAL'
    WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%'
    AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'HIGH'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'HIGH'
    WHEN DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 10 THEN 'MEDIUM'
    ELSE 'LOW'
END AS prioritas
FROM
    perkara p
    INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE
    pp.tanggal_putusan IS NOT NULL
    AND pjs.tanggal_sidang IS NOT NULL
    AND p.jenis_perkara_nama NOT LIKE '%Cabut%'
    AND pjs.tanggal_sidang >= '2025-01-01' -- Filter tahun 2025
ORDER BY
    CASE
        WHEN pp.tanggal_bht IS NULL THEN 0
        ELSE 1
    END, -- Belum BHT dulu
    FIELD(
        kategori_pa,
        'CERAI_TALAK',
        'CERAI_GUGAT',
        'WARIS',
        'ISBAT_NIKAH',
        'UMUM'
    ),
    hari_sejak_pbt DESC;

-- ==========================================
-- 2. ANALISIS KHUSUS CERAI TALAK
-- ==========================================
SELECT
    'ANALISIS CERAI TALAK' AS keterangan,
    COUNT(*) AS total_cerai_talak,

-- Status BHT Izin Talak
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL THEN 1
        ELSE 0
    END
) AS sudah_bht_final,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NULL
        AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) <= 14 THEN 1
        ELSE 0
    END
) AS proses_izin_normal,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NULL
        AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 1
        ELSE 0
    END
) AS terlambat_izin,

-- Analisis waktu
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

-- Kasus urgent yang perlu ikrar talak segera (mendekati 6 bulan)
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL
        AND DATEDIFF(CURDATE(), pp.tanggal_bht) > 150 THEN 1
        ELSE 0
    END
) AS urgent_ikrar_5_bulan,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL
        AND DATEDIFF(CURDATE(), pp.tanggal_bht) > 180 THEN 1
        ELSE 0
    END
) AS expired_ikrar_6_bulan
FROM
    perkara p
    INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE
    p.jenis_perkara_nama LIKE '%Cerai Talak%'
    AND pp.tanggal_putusan IS NOT NULL
    AND pjs.tanggal_sidang IS NOT NULL
    AND p.jenis_perkara_nama NOT LIKE '%Cabut%'
    AND pjs.tanggal_sidang >= '2025-01-01';

-- ==========================================
-- 3. DASHBOARD MONITORING PA COMPREHENSIVE
-- ==========================================
SELECT kategori_pa, COUNT(*) AS total_kasus,

-- Status BHT
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL THEN 1
        ELSE 0
    END
) AS sudah_bht,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NULL THEN 1
        ELSE 0
    END
) AS belum_bht,

-- Ketepatan waktu (14 hari)
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL
        AND DATEDIFF(
            pp.tanggal_bht,
            pjs.tanggal_sidang
        ) <= 14 THEN 1
        ELSE 0
    END
) AS tepat_waktu,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NOT NULL
        AND DATEDIFF(
            pp.tanggal_bht,
            pjs.tanggal_sidang
        ) > 14 THEN 1
        ELSE 0
    END
) AS terlambat_bht,

-- Yang belum BHT
SUM(
    CASE
        WHEN pp.tanggal_bht IS NULL
        AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) <= 14 THEN 1
        ELSE 0
    END
) AS dalam_batas,
SUM(
    CASE
        WHEN pp.tanggal_bht IS NULL
        AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 1
        ELSE 0
    END
) AS lewat_batas,

-- Persentase kepatuhan
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
FROM (
        SELECT
            p.*, pp.*, pjs.*, CASE
                WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%' THEN 'CERAI_TALAK'
                WHEN p.jenis_perkara_nama LIKE '%Cerai Gugat%' THEN 'CERAI_GUGAT'
                WHEN p.jenis_perkara_nama LIKE '%Waris%' THEN 'WARIS'
                WHEN p.jenis_perkara_nama LIKE '%Isbat%' THEN 'ISBAT_NIKAH'
                WHEN p.jenis_perkara_nama LIKE '%Wasiat%' THEN 'WASIAT'
                WHEN p.jenis_perkara_nama LIKE '%Hibah%' THEN 'HIBAH'
                ELSE 'UMUM'
            END AS kategori_pa
        FROM
            perkara p
            INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
            LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
        WHERE
            pp.tanggal_putusan IS NOT NULL
            AND pjs.tanggal_sidang IS NOT NULL
            AND p.jenis_perkara_nama NOT LIKE '%Cabut%'
            AND pjs.tanggal_sidang >= '2025-01-01'
    ) AS data_pa
GROUP BY
    kategori_pa
ORDER BY total_kasus DESC;

-- ==========================================
-- 4. ALERT SYSTEM UNTUK CERAI TALAK
-- ==========================================
-- Kasus Cerai Talak yang perlu perhatian khusus
SELECT
    'ALERT CERAI TALAK' AS alert_type,
    p.nomor_perkara,
    p.jenis_perkara_nama,
    DATE(pjs.tanggal_sidang) AS tanggal_pbt,
    DATE(pp.tanggal_bht) AS tanggal_bht_izin,
    DATEDIFF(CURDATE(), pjs.tanggal_sidang) AS hari_sejak_pbt,

-- Alert message
CASE
    WHEN pp.tanggal_bht IS NULL
    AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 21 THEN 'CRITICAL: Izin Talak terlambat lebih dari 21 hari'
    WHEN pp.tanggal_bht IS NULL
    AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14 THEN 'WARNING: Izin Talak terlambat dari target 14 hari'
    WHEN pp.tanggal_bht IS NOT NULL
    AND DATEDIFF(CURDATE(), pp.tanggal_bht) > 150 THEN 'URGENT: Mendekati batas 6 bulan untuk Ikrar Talak'
    WHEN pp.tanggal_bht IS NOT NULL
    AND DATEDIFF(CURDATE(), pp.tanggal_bht) > 180 THEN 'EXPIRED: Lewat 6 bulan - Perkawinan tetap utuh'
    ELSE 'INFO: Dalam proses normal'
END AS alert_message,

-- Deadline info
CASE
    WHEN pp.tanggal_bht IS NULL THEN CONCAT(
        'Target Izin: ',
        DATE_FORMAT(
            DATE_ADD(
                pjs.tanggal_sidang,
                INTERVAL 14 DAY
            ),
            '%d/%m/%Y'
        )
    )
    ELSE CONCAT(
        'Max Ikrar: ',
        DATE_FORMAT(
            DATE_ADD(
                pp.tanggal_bht,
                INTERVAL 6 MONTH
            ),
            '%d/%m/%Y'
        )
    )
END AS deadline_info
FROM
    perkara p
    INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
    LEFT JOIN perkara_jadwal_sidang pjs ON p.perkara_id = pjs.perkara_id
WHERE
    p.jenis_perkara_nama LIKE '%Cerai Talak%'
    AND pp.tanggal_putusan IS NOT NULL
    AND pjs.tanggal_sidang IS NOT NULL
    AND p.jenis_perkara_nama NOT LIKE '%Cabut%'
    AND (
        -- Kasus yang butuh perhatian
        (
            pp.tanggal_bht IS NULL
            AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 14
        )
        OR (
            pp.tanggal_bht IS NOT NULL
            AND DATEDIFF(CURDATE(), pp.tanggal_bht) > 150
        )
    )
ORDER BY
    CASE
        WHEN pp.tanggal_bht IS NOT NULL
        AND DATEDIFF(CURDATE(), pp.tanggal_bht) > 180 THEN 1
        WHEN pp.tanggal_bht IS NULL
        AND DATEDIFF(CURDATE(), pjs.tanggal_sidang) > 21 THEN 2
        WHEN pp.tanggal_bht IS NOT NULL
        AND DATEDIFF(CURDATE(), pp.tanggal_bht) > 150 THEN 3
        ELSE 4
    END;

-- ==========================================
-- PENJELASAN IMPLEMENTASI
-- ==========================================
/*
KEKHUSUSAN PENGADILAN AGAMA:

1. KASUS UMUM (Waris, Wasiat, Hibah, Isbat):
PBT → 14 hari kalender → BHT

2. CERAI GUGAT (Diajukan Istri):
PBT → 14 hari kalender → BHT → Akta Cerai

3. CERAI TALAK (Diajukan Suami):
PBT → 14 hari → BHT Izin Talak → Ikrar (max 6 bulan) → BHT Final

ALERT LEVELS:
- INFO: Proses normal
- WARNING: Terlambat dari target 14 hari
- CRITICAL: Terlambat lebih dari 21 hari
- URGENT: Mendekati batas 6 bulan ikrar
- EXPIRED: Lewat 6 bulan tanpa ikrar (gugur)
*/