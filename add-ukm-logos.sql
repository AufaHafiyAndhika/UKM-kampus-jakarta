-- Add Logo Paths to UKMs
-- This will add sample logo paths to existing UKMs

-- Update UKM Badminton
UPDATE ukms SET 
    logo = 'ukms/logos/badminton-logo.png',
    updated_at = NOW()
WHERE slug = 'badminton';

-- Update UKM DPM
UPDATE ukms SET 
    logo = 'ukms/logos/dpm-logo.png',
    updated_at = NOW()
WHERE slug = 'dpm';

-- Update UKM Esport
UPDATE ukms SET 
    logo = 'ukms/logos/esport-logo.png',
    updated_at = NOW()
WHERE slug = 'esport';

-- Update UKM Futsal
UPDATE ukms SET 
    logo = 'ukms/logos/futsal-logo.png',
    updated_at = NOW()
WHERE slug = 'futsal';

-- Update UKM IMMA
UPDATE ukms SET 
    logo = 'ukms/logos/imma-logo.png',
    updated_at = NOW()
WHERE slug = 'imma';

-- Update UKM Mapala
UPDATE ukms SET 
    logo = 'ukms/logos/mapala-logo.png',
    updated_at = NOW()
WHERE slug = 'mapala';

-- Update UKM PMK
UPDATE ukms SET 
    logo = 'ukms/logos/pmk-logo.png',
    updated_at = NOW()
WHERE slug = 'pmk';

-- Update UKM Seni Budaya
UPDATE ukms SET 
    logo = 'ukms/logos/seni-budaya-logo.png',
    updated_at = NOW()
WHERE slug = 'seni-budaya';

-- Update UKM Sistem Informasi
UPDATE ukms SET 
    logo = 'ukms/logos/sistem-informasi-logo.png',
    updated_at = NOW()
WHERE slug = 'sistem-informasi';

-- Check the results
SELECT 
    name as 'Nama UKM',
    slug as 'Slug',
    logo as 'Logo Path',
    CASE 
        WHEN logo IS NOT NULL THEN 'Ada Logo'
        ELSE 'Belum Ada Logo'
    END as 'Status Logo'
FROM ukms 
ORDER BY name;

-- Count UKMs with and without logos
SELECT 
    'UKMs dengan Logo' as 'Status',
    COUNT(*) as 'Jumlah'
FROM ukms 
WHERE logo IS NOT NULL
UNION ALL
SELECT 
    'UKMs tanpa Logo' as 'Status',
    COUNT(*) as 'Jumlah'
FROM ukms 
WHERE logo IS NULL;
