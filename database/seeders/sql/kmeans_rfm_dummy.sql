-- ============================================================
-- K-Means RFM deterministic dummy dataset
-- MySQL 8.x | Re-runnable | 18 pelanggan | 108 transaksi
-- Jalankan setelah master tenant dan minimal satu pegawai tersedia.
-- Password seluruh akun dummy: password
-- bcrypt hash di bawah sesuai Hash::make('password').
-- ============================================================

SET @tenant_id := (SELECT id_tenant FROM tenant WHERE is_active = 1 ORDER BY id_tenant LIMIT 1);
SET @pegawai_id := (SELECT id_pegawai FROM pegawai WHERE is_active = 1 ORDER BY id_pegawai LIMIT 1);
SET @password_hash := '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';

DELETE r
FROM penukaran_poin r
JOIN pelanggan p ON p.id_pelanggan = r.id_pelanggan
WHERE p.email_pelanggan LIKE 'rfm.%@example.test';

DELETE t
FROM transaksi t
JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
WHERE p.email_pelanggan LIKE 'rfm.%@example.test';

DELETE pk
FROM parkir pk
JOIN pelanggan p ON p.id_pelanggan = pk.id_pelanggan
WHERE p.email_pelanggan LIKE 'rfm.%@example.test';

DELETE FROM pelanggan
WHERE email_pelanggan LIKE 'rfm.%@example.test';

INSERT INTO pelanggan (
    no_pelanggan, id_cluster, nama_pelanggan, alamat, no_ktp_pelanggan,
    no_whatsapp_pelanggan, email_pelanggan, password, total_poin,
    tgl_daftar, is_active, created_at, updated_at
) VALUES
    ('PIM-RFM-P001', NULL, 'RFM Platinum 01', 'Jakarta Barat', '3173000000001001', '081300001001', 'rfm.platinum.01@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-P002', NULL, 'RFM Platinum 02', 'Jakarta Barat', '3173000000001002', '081300001002', 'rfm.platinum.02@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-P003', NULL, 'RFM Platinum 03', 'Jakarta Barat', '3173000000001003', '081300001003', 'rfm.platinum.03@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-P004', NULL, 'RFM Platinum 04', 'Jakarta Barat', '3173000000001004', '081300001004', 'rfm.platinum.04@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-P005', NULL, 'RFM Platinum 05', 'Jakarta Barat', '3173000000001005', '081300001005', 'rfm.platinum.05@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-P006', NULL, 'RFM Platinum 06', 'Jakarta Barat', '3173000000001006', '081300001006', 'rfm.platinum.06@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 12 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-G001', NULL, 'RFM Gold 01', 'Tangerang', '3173000000002001', '081300002001', 'rfm.gold.01@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-G002', NULL, 'RFM Gold 02', 'Tangerang', '3173000000002002', '081300002002', 'rfm.gold.02@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-G003', NULL, 'RFM Gold 03', 'Tangerang', '3173000000002003', '081300002003', 'rfm.gold.03@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-G004', NULL, 'RFM Gold 04', 'Tangerang', '3173000000002004', '081300002004', 'rfm.gold.04@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-G005', NULL, 'RFM Gold 05', 'Tangerang', '3173000000002005', '081300002005', 'rfm.gold.05@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-G006', NULL, 'RFM Gold 06', 'Tangerang', '3173000000002006', '081300002006', 'rfm.gold.06@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 8 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-D001', NULL, 'RFM Dormant 01', 'Jakarta Selatan', '3173000000003001', '081300003001', 'rfm.dormant.01@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-D002', NULL, 'RFM Dormant 02', 'Jakarta Selatan', '3173000000003002', '081300003002', 'rfm.dormant.02@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-D003', NULL, 'RFM Dormant 03', 'Jakarta Selatan', '3173000000003003', '081300003003', 'rfm.dormant.03@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-D004', NULL, 'RFM Dormant 04', 'Jakarta Selatan', '3173000000003004', '081300003004', 'rfm.dormant.04@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-D005', NULL, 'RFM Dormant 05', 'Jakarta Selatan', '3173000000003005', '081300003005', 'rfm.dormant.05@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW()),
    ('PIM-RFM-D006', NULL, 'RFM Dormant 06', 'Jakarta Selatan', '3173000000003006', '081300003006', 'rfm.dormant.06@example.test', @password_hash, 0, DATE_SUB(CURDATE(), INTERVAL 10 MONTH), 1, NOW(), NOW());

-- Platinum: 10 transaksi/member, recency 1-3 hari, monetary sangat tinggi.
INSERT INTO transaksi (
    id_pelanggan, id_tenant, id_pegawai, tanggal_transaksi, nominal_belanja,
    poin_yang_didapat, foto_struk, status_transaksi, catatan_tolak, created_at, updated_at
)
SELECT
    p.id_pelanggan, @tenant_id, @pegawai_id,
    DATE_SUB(NOW(), INTERVAL (MOD(n.seq, 3) + 1) DAY),
    (650000 + (n.seq * 35000)),
    FLOOR((650000 + (n.seq * 35000)) / 10000),
    CONCAT('seed/platinum-', p.id_pelanggan, '-', n.seq, '.jpg'),
    'Approved', NULL, NOW(), NOW()
FROM pelanggan p
CROSS JOIN (
    SELECT 1 seq UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
    UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
) n
WHERE p.email_pelanggan LIKE 'rfm.platinum.%@example.test';

-- Gold: 6 transaksi/member, recency 14-21 hari, monetary menengah.
INSERT INTO transaksi (
    id_pelanggan, id_tenant, id_pegawai, tanggal_transaksi, nominal_belanja,
    poin_yang_didapat, foto_struk, status_transaksi, catatan_tolak, created_at, updated_at
)
SELECT
    p.id_pelanggan, @tenant_id, @pegawai_id,
    DATE_SUB(NOW(), INTERVAL (14 + n.seq) DAY),
    (110000 + (n.seq * 20000)),
    FLOOR((110000 + (n.seq * 20000)) / 10000),
    CONCAT('seed/gold-', p.id_pelanggan, '-', n.seq, '.jpg'),
    'Approved', NULL, DATE_SUB(NOW(), INTERVAL (14 + n.seq) DAY), NOW()
FROM pelanggan p
CROSS JOIN (
    SELECT 1 seq UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
) n
WHERE p.email_pelanggan LIKE 'rfm.gold.%@example.test';

-- Dormant: 2 transaksi/member, recency 90-180 hari, monetary rendah.
INSERT INTO transaksi (
    id_pelanggan, id_tenant, id_pegawai, tanggal_transaksi, nominal_belanja,
    poin_yang_didapat, foto_struk, status_transaksi, catatan_tolak, created_at, updated_at
)
SELECT
    p.id_pelanggan, @tenant_id, @pegawai_id,
    DATE_SUB(NOW(), INTERVAL (90 + (n.seq * 30)) DAY),
    (25000 + (n.seq * 15000)),
    FLOOR((25000 + (n.seq * 15000)) / 10000),
    CONCAT('seed/dormant-', p.id_pelanggan, '-', n.seq, '.jpg'),
    'Approved', NULL, DATE_SUB(NOW(), INTERVAL (90 + (n.seq * 30)) DAY), NOW()
FROM pelanggan p
CROSS JOIN (SELECT 1 seq UNION ALL SELECT 2) n
WHERE p.email_pelanggan LIKE 'rfm.dormant.%@example.test';

UPDATE pelanggan p
SET p.total_poin = (
    SELECT COALESCE(SUM(t.poin_yang_didapat), 0)
    FROM transaksi t
    WHERE t.id_pelanggan = p.id_pelanggan
      AND t.status_transaksi = 'Approved'
)
WHERE p.email_pelanggan LIKE 'rfm.%@example.test';

-- Jalankan worker setelah import:
-- C:\Users\yells\AppData\Local\Python\pythoncore-3.14-64\python.exe analytics\kmeans_worker.py
