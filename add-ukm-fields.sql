-- TAMBAH FIELD BARU KE TABLE UKM
-- 1. Prestasi UKM
-- 2. Struktur Organisasi

-- Tambah field achievements (Prestasi UKM)
ALTER TABLE ukms ADD COLUMN achievements TEXT NULL COMMENT 'Prestasi UKM' AFTER requirements;

-- Tambah field organization_structure (Gambar Struktur Organisasi)
ALTER TABLE ukms ADD COLUMN organization_structure VARCHAR(255) NULL COMMENT 'Gambar struktur organisasi' AFTER achievements;

-- Update sample data untuk UKM yang sudah ada
UPDATE ukms 
SET achievements = '- Juara 1 Lomba Programming 2023\n- Juara 2 Hackathon Nasional 2024\n- Best Innovation Award 2024',
    updated_at = NOW()
WHERE id = 1;

-- Set registration status ke closed untuk testing
UPDATE ukms SET registration_status = 'closed' WHERE id = 1;

-- Verifikasi struktur table
DESCRIBE ukms;

-- Cek data UKM
SELECT id, name, status, registration_status, achievements FROM ukms;
