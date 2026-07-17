-- ============================================================
-- K-Means RFM polarized dataset: 15 pelanggan, 125 transaksi
-- MySQL 8.x | Re-runnable
-- Platinum : 5 member x 16 transaksi, monetary > Rp 5.000.000, recency < 3 hari
-- Gold     : 5 member x  7 transaksi, monetary Rp 1.000.000 - Rp 3.000.000, recency 14 hari
-- Dormant  : 5 member x  2 transaksi, monetary < Rp 200.000, recency > 3 bulan
-- Password seluruh akun dummy: password
-- ============================================================

SET @tenant_id := (SELECT id_tenant FROM tenant WHERE is_active = 1 ORDER BY id_tenant LIMIT 1);
SET @pegawai_id := (SELECT id_pegawai FROM pegawai WHERE is_active = 1 ORDER BY id_pegawai LIMIT 1);
SET @password_hash := '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';

DELETE r
FROM penukaran_poin r
JOIN pelanggan p ON p.id_pelanggan = r.id_pelanggan
WHERE p.email_pelanggan LIKE 'rfm15.%@example.test';

DELETE t
FROM transaksi t
JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
WHERE p.email_pelanggan LIKE 'rfm15.%@example.test';

DELETE pk
FROM parkir pk
JOIN pelanggan p ON p.id_pelanggan = pk.id_pelanggan
WHERE p.email_pelanggan LIKE 'rfm15.%@example.test';

DELETE FROM pelanggan
WHERE email_pelanggan LIKE 'rfm15.%@example.test';

INSERT INTO pelanggan (
    no_pelanggan, id_cluster, nama_pelanggan, alamat, no_ktp_pelanggan,
    no_whatsapp_pelanggan, email_pelanggan, password, total_poin,
    tgl_daftar, is_active, created_at, updated_at
) VALUES
    ('PIM-RFM15-P01', NULL, 'RFM15 Platinum 01', 'Jakarta Barat', '3173000000011001', '081311001001', 'rfm15.platinum.01@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-P02', NULL, 'RFM15 Platinum 02', 'Jakarta Barat', '3173000000011002', '081311001002', 'rfm15.platinum.02@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-P03', NULL, 'RFM15 Platinum 03', 'Jakarta Barat', '3173000000011003', '081311001003', 'rfm15.platinum.03@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-P04', NULL, 'RFM15 Platinum 04', 'Jakarta Barat', '3173000000011004', '081311001004', 'rfm15.platinum.04@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-P05', NULL, 'RFM15 Platinum 05', 'Jakarta Barat', '3173000000011005', '081311001005', 'rfm15.platinum.05@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-G01', NULL, 'RFM15 Gold 01', 'Tangerang', '3173000000012001', '081311002001', 'rfm15.gold.01@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-G02', NULL, 'RFM15 Gold 02', 'Tangerang', '3173000000012002', '081311002002', 'rfm15.gold.02@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-G03', NULL, 'RFM15 Gold 03', 'Tangerang', '3173000000012003', '081311002003', 'rfm15.gold.03@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-G04', NULL, 'RFM15 Gold 04', 'Tangerang', '3173000000012004', '081311002004', 'rfm15.gold.04@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-G05', NULL, 'RFM15 Gold 05', 'Tangerang', '3173000000012005', '081311002005', 'rfm15.gold.05@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-D01', NULL, 'RFM15 Dormant 01', 'Jakarta Selatan', '3173000000013001', '081311003001', 'rfm15.dormant.01@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-D02', NULL, 'RFM15 Dormant 02', 'Jakarta Selatan', '3173000000013002', '081311003002', 'rfm15.dormant.02@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-D03', NULL, 'RFM15 Dormant 03', 'Jakarta Selatan', '3173000000013003', '081311003003', 'rfm15.dormant.03@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-D04', NULL, 'RFM15 Dormant 04', 'Jakarta Selatan', '3173000000013004', '081311003004', 'rfm15.dormant.04@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM15-D05', NULL, 'RFM15 Dormant 05', 'Jakarta Selatan', '3173000000013005', '081311003005', 'rfm15.dormant.05@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW());

-- Platinum: Rp 400.000 - Rp 475.000 x 16, total > Rp 5 juta.
INSERT INTO transaksi (
    id_pelanggan, id_tenant, id_pegawai, tanggal_transaksi, nominal_belanja,
    poin_yang_didapat, foto_struk, status_transaksi, catatan_tolak, created_at, updated_at
)
SELECT
    p.id_pelanggan, @tenant_id, @pegawai_id,
    DATE_SUB(NOW(), INTERVAL (MOD(n.seq, 2) + 1) DAY),
    (395000 + (n.seq * 5000)),
    FLOOR((395000 + (n.seq * 5000)) / 10000),
    CONCAT('seed/rfm15-platinum-', p.id_pelanggan, '-', n.seq, '.jpg'),
    'Approved', NULL, DATE_SUB(NOW(), INTERVAL (MOD(n.seq, 2) + 1) DAY), NOW()
FROM pelanggan p
CROSS JOIN (
    SELECT 1 seq UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
    UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
    UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL SELECT 16
) n
WHERE p.email_pelanggan LIKE 'rfm15.platinum.%@example.test';

-- Gold: Rp 230.000 - Rp 290.000 x 7, total Rp 1,82 juta, transaksi terakhir 14 hari.
INSERT INTO transaksi (
    id_pelanggan, id_tenant, id_pegawai, tanggal_transaksi, nominal_belanja,
    poin_yang_didapat, foto_struk, status_transaksi, catatan_tolak, created_at, updated_at
)
SELECT
    p.id_pelanggan, @tenant_id, @pegawai_id,
    DATE_SUB(NOW(), INTERVAL (13 + n.seq) DAY),
    (220000 + (n.seq * 10000)),
    FLOOR((220000 + (n.seq * 10000)) / 10000),
    CONCAT('seed/rfm15-gold-', p.id_pelanggan, '-', n.seq, '.jpg'),
    'Approved', NULL, DATE_SUB(NOW(), INTERVAL (13 + n.seq) DAY), NOW()
FROM pelanggan p
CROSS JOIN (
    SELECT 1 seq UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
) n
WHERE p.email_pelanggan LIKE 'rfm15.gold.%@example.test';

-- Dormant: Rp 45.000 + Rp 55.000, total Rp 100.000, terakhir 120 hari lalu.
INSERT INTO transaksi (
    id_pelanggan, id_tenant, id_pegawai, tanggal_transaksi, nominal_belanja,
    poin_yang_didapat, foto_struk, status_transaksi, catatan_tolak, created_at, updated_at
)
SELECT
    p.id_pelanggan, @tenant_id, @pegawai_id,
    DATE_SUB(NOW(), INTERVAL (90 + (n.seq * 30)) DAY),
    (35000 + (n.seq * 10000)),
    FLOOR((35000 + (n.seq * 10000)) / 10000),
    CONCAT('seed/rfm15-dormant-', p.id_pelanggan, '-', n.seq, '.jpg'),
    'Approved', NULL, DATE_SUB(NOW(), INTERVAL (90 + (n.seq * 30)) DAY), NOW()
FROM pelanggan p
CROSS JOIN (SELECT 1 seq UNION ALL SELECT 2) n
WHERE p.email_pelanggan LIKE 'rfm15.dormant.%@example.test';

UPDATE pelanggan p
SET p.total_poin = (
    SELECT COALESCE(SUM(t.poin_yang_didapat), 0)
    FROM transaksi t
    WHERE t.id_pelanggan = p.id_pelanggan
      AND t.status_transaksi = 'Approved'
)
WHERE p.email_pelanggan LIKE 'rfm15.%@example.test';

-- Jalankan worker setelah import:
-- C:\Users\yells\AppData\Local\Python\pythoncore-3.14-64\python.exe analytics\kmeans_worker.py
