-- ==========================================
-- MIGRATION: Add Jurusita Column and Update Status Values
-- Date: November 28, 2025
-- Purpose: Add jurusita column and update status dropdown values
-- ==========================================

USE `catatan_bht`;

-- Step 1: Add jurusita column to berkas_masuk table
ALTER TABLE `berkas_masuk`
ADD COLUMN `jurusita` varchar(255) NULL AFTER `panitera_pengganti`;

-- Step 2: Update status enum values
-- First, update existing data to new status values
UPDATE `berkas_masuk`
SET
    `status_berkas` = 'PANITERA_PENGGANTI'
WHERE
    `status_berkas` = 'MASUK';

UPDATE `berkas_masuk`
SET
    `status_berkas` = 'BELUM_ADA_PBT'
WHERE
    `status_berkas` = 'PROSES';

UPDATE `berkas_masuk`
SET
    `status_berkas` = 'SELESAI_ARSIP'
WHERE
    `status_berkas` = 'SELESAI';

UPDATE `berkas_masuk`
SET
    `status_berkas` = 'SELESAI_ARSIP'
WHERE
    `status_berkas` = 'ARSIP';

-- Step 3: Alter the enum to use new values
ALTER TABLE `berkas_masuk`
MODIFY COLUMN `status_berkas` enum(
    'PANITERA_PENGGANTI',
    'ALIH_MEDIA',
    'BELUM_ADA_PBT',
    'MENUNGGU_BHT',
    'SELESAI_ARSIP'
) NOT NULL DEFAULT 'PANITERA_PENGGANTI';

-- Step 4: Add jurusita column to berkas_pbt table as well
ALTER TABLE `berkas_pbt`
ADD COLUMN `jurusita` varchar(255) NULL AFTER `panitera_pengganti`;

-- ==========================================
-- VERIFICATION QUERIES
-- ==========================================

-- Check berkas_masuk table structure
DESCRIBE `berkas_masuk`;

-- Check berkas_pbt table structure
DESCRIBE `berkas_pbt`;

-- Check status distribution
SELECT status_berkas, COUNT(*) as jumlah
FROM berkas_masuk
GROUP BY
    status_berkas
ORDER BY jumlah DESC;

-- ==========================================
-- ROLLBACK SCRIPT (if needed)
-- ==========================================

/*
-- To rollback these changes:

-- Remove jurusita columns
ALTER TABLE `berkas_masuk` DROP COLUMN `jurusita`;
ALTER TABLE `berkas_pbt` DROP COLUMN `jurusita`;

-- Revert status values
UPDATE `berkas_masuk` SET `status_berkas` = 'MASUK' WHERE `status_berkas` = 'PANITERA_PENGGANTI';
UPDATE `berkas_masuk` SET `status_berkas` = 'PROSES' WHERE `status_berkas` IN ('ALIH_MEDIA', 'BELUM_ADA_PBT', 'MENUNGGU_BHT');
UPDATE `berkas_masuk` SET `status_berkas` = 'SELESAI' WHERE `status_berkas` = 'SELESAI_ARSIP';

-- Revert enum
ALTER TABLE `berkas_masuk` 
MODIFY COLUMN `status_berkas` enum('MASUK', 'PROSES', 'SELESAI', 'ARSIP') NOT NULL DEFAULT 'MASUK';
*/


ALTER TABLE `berkas_pbt`

ADD COLUMN `jurusita` varchar(255) NULL AFTER `panitera_pengganti`;
