-- Fix tanggal_register: buat NOT NULL dengan default hari ini
-- Fix tanggal_masuk_notelen: tetap nullable untuk input manual

USE `catatan_bht`;

-- Step 1: Update data yang kosong di tanggal_register dengan tanggal hari ini
UPDATE `berkas_masuk`
SET
    `tanggal_register` = CURDATE()
WHERE
    `tanggal_register` IS NULL;

-- Step 2: Ubah tanggal_register jadi NOT NULL dengan default CURDATE()
-- Note: MySQL tidak support DEFAULT CURDATE(), jadi pakai trigger
ALTER TABLE `berkas_masuk`
MODIFY COLUMN `tanggal_register` date NOT NULL;

-- Step 3: Buat trigger untuk auto-set tanggal_register jika kosong saat insert
DELIMITER $$

CREATE TRIGGER `set_default_tanggal_register_before_insert`
BEFORE INSERT ON `berkas_masuk`
FOR EACH ROW
BEGIN
    IF NEW.tanggal_register IS NULL OR NEW.tanggal_register = '0000-00-00' THEN
        SET NEW.tanggal_register = CURDATE();
    END IF;
END$$

DELIMITER;

-- Step 4: Verifikasi perubahan
DESCRIBE `berkas_masuk`;

-- Step 5: Test insert tanpa tanggal_register (should auto-fill)
SELECT 'Schema updated successfully! tanggal_register akan otomatis terisi dengan tanggal hari ini.' as status;