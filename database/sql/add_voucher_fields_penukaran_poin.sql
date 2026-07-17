ALTER TABLE penukaran_poin
MODIFY status_redeem VARCHAR(50) NOT NULL DEFAULT 'Success';

ALTER TABLE penukaran_poin
ADD COLUMN voucher_code VARCHAR(80) NULL UNIQUE AFTER status_redeem,
ADD COLUMN claimed_at TIMESTAMP NULL AFTER voucher_code,
ADD COLUMN claimed_by INT UNSIGNED NULL AFTER claimed_at;

UPDATE penukaran_poin
SET voucher_code = CONCAT('PIM-', id_redeem, '-', DATE_FORMAT(tanggal_redeem, '%Y%m%d%H%i%s'))
WHERE voucher_code IS NULL;
