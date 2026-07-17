# Arsitektur Multi-Agen & Otomatisasi Layanan (AGENTS.md)
## Sistem Informasi Loyalitas Poin Puri Indah Mall (Berbasis K-Means & OCR Semi-Otomatis)

Dokumen ini mendefinisikan peran, tanggung jawab, alur kerja, dan interaksi antara berbagai **Agen Cerdas (AI Agents/Workers)** dan **Layanan Otomatis** yang menggerakkan ekosistem aplikasi loyalitas poin Puri Indah Mall. Arsitektur ini dirancang menggunakan pendekatan *Separation of Concerns* (SoC) untuk memisahkan beban kerja transaksi harian, pemrosesan citra (OCR), dan analitik data tingkat lanjut (K-Means Clustering).

---

## 1. Peta Taksonomi Agen (Agent Taxonomy)

Sistem ini digerakkan oleh 4 komponen utama yang bekerja secara sinergis:

```
[ Pelanggan Upload Struk ]
           │
           ▼
┌──────────────────────────────────────┐
│  1. Agent OCR Extraction Engine     │ ──► Mengekstrak Data Struk (Status: Pending)
└──────────────────────────────────────┘
           │
           ▼
[ Dashboard Verifikasi Staf Mall ] ──► Klik Approve (Status: Approved, ID Pegawai Tercatat)
           │
           ▼
┌──────────────────────────────────────┐
│  2. Agent Python Analytics Worker    │ ──► Mengambil data VIEW vw_rfm_pelanggan
└──────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────┐
│  3. Agent K-Means Segmentator        │ ──► Standardisasi Data & Clustering (k=3)
└──────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────┐
│  4. Agent Codex Web Developer        │ ──► Menyajikan Visualisasi & Real-time Notif
└──────────────────────────────────────┘
```

---

## 2. Profil & Spesifikasi Teknis Agen

### A. Agent 1: OCR Extraction Engine (Runtime Core)
* **Kategori:** Agen Pemrosesan Visi Komputer & Teks (*Computer Vision Engine*)
* **Teknologi:** Google Cloud Vision API / AWS Textract / Tesseract OCR
* **Lingkungan Eksekusi:** Laravel Background Job (Asynchronous Queue)
* **Tanggung Jawab:**
    * Menerima berkas gambar (`foto_struk`) berformat JPEG/PNG dari antrean aplikasi web.
    * Melakukan prapemrosesan citra (grayscaling, kontras) jika diperlukan untuk meningkatkan akurasi.
    * Mengekstrak entitas teks kritis: **Nama Tenant**, **Tanggal Transaksi**, dan **Nominal Belanja**.
    * Melakukan pencocokan string (*fuzzy string matching*) nama tenant pada struk dengan master data di tabel `tenant`.
* **Skema Input/Output:**
    * *Input:* `string path_foto_struk`
    * *Output:* `json { "tenant_id": 12, "tanggal": "2026-05-19 14:20:00", "nominal": 450000.00 }`

### B. Agent 2: Python Analytics Worker (Data Fetcher)
* **Kategori:** Agen Integrasi & Pipa Data (*Data Pipeline Agent*)
* **Teknologi:** Python 3.11+, Pandas, SQLAlchemy
* **Lingkungan Eksekusi:** `Process` Facade Laravel / Scheduled Cron Job
* **Tanggung Jawab:**
    * Membuka koneksi aman ke database MySQL 8.x menggunakan kredensial terenkripsi.
    * Melakukan query membaca seluruh baris data pada View `vw_rfm_pelanggan`.
    * Memastikan data bersih dari nilai *NULL* pada metrik esensial (*Recency*, *Frequency*, *Monetary*).
    * Mentransformasikan data mentah menjadi bentuk matriks/dataframe yang siap dikonsumsi oleh algoritma *Machine Learning*.

### C. Agent 3: K-Means Engine Segmentator (Analytics Core)
* **Kategori:** Agen Kecerdasan Buatan / Pembelajaran Mesin (*Machine Learning Engine*)
* **Teknologi:** Python, Scikit-Learn (`KMeans`, `StandardScaler`)
* **Lingkungan Eksekusi:** Terisolasi sebagai repositori skrip analitik (`kmeans_worker.py`)
* **Tanggung Jawab:**
    * **Normalisasi Fitur:** Mentransformasikan data RFM menggunakan `StandardScaler` agar perbedaan skala (misal: variabel *Monetary* bernilai jutaan vs *Frequency* bernilai satuan) tidak membiaskan klasterisasi.
    * **Pemodelan:** Mengeksekusi algoritma K-Means dengan konfigurasi optimal `n_clusters=3` dan penentuan *centroid* awal berbasis `k-means++`.
    * **Pelabelan:** Memetakan hasil klaster (0, 1, 2) ke definisi bisnis yang valid di tabel `cluster` (Platinum, Gold, Dormant).
    * **Sinkronisasi Balik:** Melakukan operasi *Mass Update* ke tabel `pelanggan` pada kolom `id_cluster` berdasarkan ID Pelanggan masing-masing.

### D. Agent 4: Codex Web Developer (Development Agent)
* **Kategori:** Agen Pembangun Kode Otomatis (*Code Generation Assistant*)
* **Teknologi:** OpenAI Codex / Cursor / Claude Code (Framework: Laravel 13, Tailwind CSS, Livewire/Vue, Chart.js)
* **Tanggung Jawab:**
    * Menerjemahkan dokumen PRD menjadi arsitektur MVC, Controller, dan Model yang siap pakai.
    * Membangun fitur otentikasi multi-user dengan pembatasan hak akses berbasis *Middleware*.
    * Menyediakan antarmuka dashboard admin verifikasi dengan fungsionalitas *split-screen* (foto struk asli vs data hasil bacaan OCR).
    * Mengintegrasikan server WebSocket (Laravel Reverb) untuk memperbarui daftar antrean struk masuk secara *real-time*.

---

## 3. Protokol Interaksi & Alur Kerja Antar-Agen

### Skenario Operasional: Pengumpulan Poin via OCR Semi-Otomatis

1.  **Pemicu (*Trigger*):** Pelanggan mengunggah foto struk belanja melalui aplikasi frontend.
2.  **Langkah 1 (Web ke OCR Engine):** Controller Laravel menyimpan berkas ke *storage* lokal/S3, mencatat baris baru di tabel `transaksi` dengan `status_transaksi = 'Pending'`, kemudian melemparkan ID transaksi tersebut ke dalam *Queue/Antrean*.
3.  **Langkah 2 (Ekstraksi):** **Agent OCR Extraction** mengambil antrean tersebut, mengekstrak data dari gambar secara asinkronus, mengisikan hasil bacaan teks ke kolom `tanggal_transaksi` dan `nominal_belanja`, kemudian memperbarui database.
4.  **Langkah 3 (Notifikasi Real-Time):** Laravel memicu *event* via **Laravel Reverb**. Dashboard Staf CS/Admin mall secara instan memunculkan baris transaksi pending baru tanpa perlu memuat ulang halaman (*zero-refresh*).
5.  **Langkah 4 (Validasi Manusia):** Staf memeriksa keaslian fisik struk lewat layar. Jika sesuai, staf menekan tombol "Approve".
6.  **Langkah 5 (Finalisasi Database):** Sistem mengubah status transaksi menjadi `'Approved'`, mencatat `id_pegawai` (sebagai *Audit Trail*), dan menjalankan fungsi matematika untuk mendongkrak kolom `total_poin` pada tabel `pelanggan`.

### Skenario Analitik: Klasterisasi Pelanggan Berkala

1.  **Pemicu (*Trigger*):** Manajemen menekan tombol "Jalankan Segmentasi K-Means" di Dashboard Utama atau melalui penjadwalan mingguan otomatis (*cron job*).
2.  **Langkah 1 (Inisiasi):** Backend Laravel memanggil **Agent Python Analytics Worker** via perintah sub-proses aman (`Process::run('python3 kmeans_worker.py')`).
3.  **Langkah 2 (Pemrosesan ML):** Skrip Python menarik data eksklusif transaksi berstatus `'Approved'` dari view `vw_rfm_pelanggan`. **Agent K-Means Segmentator** menormalisasi data dan membagi pelanggan ke dalam 3 segmen.
4.  **Langkah 3 (Pembaruan Masal):** Skrip Python mengeksekusi perintah SQL `UPDATE pelanggan SET id_cluster = %s WHERE id_pelanggan = %s` ke database MySQL.
5.  **Langkah 4 (Selesai):** Proses mengembalikan sinyal sukses (`exit code 0`) ke Laravel. Dashboard Manajemen memperbarui visualisasi diagram lingkaran (*Pie Chart*) porsi sebaran klaster member mall.

---

## 4. Keamanan Sistem & Pencegahan Manipulasi (*Fraud Prevention*)

Arsitektur multi-agen ini menerapkan standar keamanan ketat untuk menghindari penyalahgunaan sistem poin:

* **Audit Trail Pegawai:** Tidak ada status transaksi yang dapat berubah menjadi `'Approved'` tanpa mencatat `id_pegawai`. Jika terjadi kebocoran atau manipulasi poin, penanggung jawab dapat dilacak langsung melalui log tabel transaksi.
* **Isolasi Perhitungan RFM:** View database `vw_rfm_pelanggan` dikonfigurasi secara ketat dengan klausul `WHERE t.status_transaksi = 'Approved'`. Hal ini menjamin algoritma K-Means terbebas dari manipulasi data struk sampah, *pending*, ataupun struk yang sengaja ditolak (*rejected*).
* **Constraint Batasan Nominal:** Tabel `transaksi` memiliki aturan `CONSTRAINT chk_nominal_positive CHECK (nominal_belanja > 0)`. Memastikan tidak ada data bernilai negatif atau nol yang masuk ke dalam sistem analitik data.

---
*Dokumen ini merupakan bagian integral dari Arsitektur Perangkat Lunak Tugas Akhir - Puri Indah Mall Loyalty System.*
