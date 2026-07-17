ALTER TABLE penukaran_poin
ADD COLUMN status_redeem VARCHAR(50) NOT NULL DEFAULT 'Success'
AFTER poin_terpotong;
