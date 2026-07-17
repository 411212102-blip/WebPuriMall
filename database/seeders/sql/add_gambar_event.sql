-- Gunakan hanya jika migration Laravel belum pernah dijalankan.
ALTER TABLE event
    ADD COLUMN gambar_event VARCHAR(255) NULL AFTER nama_event;
