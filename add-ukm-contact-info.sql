-- Add Contact Information to UKMs
-- This will add email, phone, instagram, and website to each UKM

-- Update UKM Badminton
UPDATE ukms SET 
    contact_info = JSON_OBJECT(
        'email', 'badminton@ukm.telkomuniversity.ac.id',
        'phone', '081234567801',
        'instagram', '@badminton_telkomjkt',
        'website', 'https://badminton.telkomuniversity.ac.id'
    ),
    updated_at = NOW()
WHERE slug = 'badminton';

-- Update UKM DPM
UPDATE ukms SET 
    contact_info = JSON_OBJECT(
        'email', 'dpm@ukm.telkomuniversity.ac.id',
        'phone', '081234567802',
        'instagram', '@dpm_telkomjkt',
        'website', 'https://dpm.telkomuniversity.ac.id'
    ),
    updated_at = NOW()
WHERE slug = 'dpm';

-- Update UKM Esport
UPDATE ukms SET 
    contact_info = JSON_OBJECT(
        'email', 'esport@ukm.telkomuniversity.ac.id',
        'phone', '081234567803',
        'instagram', '@esport_telkomjkt',
        'website', 'https://esport.telkomuniversity.ac.id'
    ),
    updated_at = NOW()
WHERE slug = 'esport';

-- Update UKM Futsal
UPDATE ukms SET 
    contact_info = JSON_OBJECT(
        'email', 'futsal@ukm.telkomuniversity.ac.id',
        'phone', '081234567804',
        'instagram', '@futsal_telkomjkt',
        'website', 'https://futsal.telkomuniversity.ac.id'
    ),
    updated_at = NOW()
WHERE slug = 'futsal';

-- Update UKM IMMA
UPDATE ukms SET 
    contact_info = JSON_OBJECT(
        'email', 'imma@ukm.telkomuniversity.ac.id',
        'phone', '081234567805',
        'instagram', '@imma_telkomjkt',
        'website', 'https://imma.telkomuniversity.ac.id'
    ),
    updated_at = NOW()
WHERE slug = 'imma';

-- Update UKM Mapala
UPDATE ukms SET 
    contact_info = JSON_OBJECT(
        'email', 'mapala@ukm.telkomuniversity.ac.id',
        'phone', '081234567806',
        'instagram', '@mapala_telkomjkt',
        'website', 'https://mapala.telkomuniversity.ac.id'
    ),
    updated_at = NOW()
WHERE slug = 'mapala';

-- Update UKM PMK
UPDATE ukms SET 
    contact_info = JSON_OBJECT(
        'email', 'pmk@ukm.telkomuniversity.ac.id',
        'phone', '081234567807',
        'instagram', '@pmk_telkomjkt',
        'website', 'https://pmk.telkomuniversity.ac.id'
    ),
    updated_at = NOW()
WHERE slug = 'pmk';

-- Update UKM Seni Budaya
UPDATE ukms SET 
    contact_info = JSON_OBJECT(
        'email', 'senibudaya@ukm.telkomuniversity.ac.id',
        'phone', '081234567808',
        'instagram', '@senibudaya_telkomjkt',
        'website', 'https://senibudaya.telkomuniversity.ac.id'
    ),
    updated_at = NOW()
WHERE slug = 'seni-budaya';

-- Update UKM Sistem Informasi
UPDATE ukms SET 
    contact_info = JSON_OBJECT(
        'email', 'si@ukm.telkomuniversity.ac.id',
        'phone', '081234567809',
        'instagram', '@si_telkomjkt',
        'website', 'https://si.telkomuniversity.ac.id'
    ),
    updated_at = NOW()
WHERE slug = 'sistem-informasi';

-- Check the results
SELECT 'UKM Contact Information:' as info;
SELECT 
    name as 'UKM Name',
    slug as 'Slug',
    JSON_EXTRACT(contact_info, '$.email') as 'Email',
    JSON_EXTRACT(contact_info, '$.phone') as 'Phone',
    JSON_EXTRACT(contact_info, '$.instagram') as 'Instagram',
    JSON_EXTRACT(contact_info, '$.website') as 'Website'
FROM ukms 
WHERE contact_info IS NOT NULL
ORDER BY name;

-- Show UKMs with both contact info and leaders
SELECT 'UKMs with Contact Info and Leaders:' as info;
SELECT 
    u.name as 'UKM Name',
    JSON_EXTRACT(u.contact_info, '$.email') as 'UKM Email',
    JSON_EXTRACT(u.contact_info, '$.phone') as 'UKM Phone',
    JSON_EXTRACT(u.contact_info, '$.instagram') as 'UKM Instagram',
    CASE 
        WHEN us.name IS NOT NULL THEN us.name
        ELSE 'Belum ada ketua'
    END as 'Leader Name',
    CASE 
        WHEN us.nim IS NOT NULL THEN us.nim
        ELSE '-'
    END as 'Leader NIM'
FROM ukms u
LEFT JOIN users us ON u.leader_id = us.id
WHERE u.contact_info IS NOT NULL
ORDER BY u.name;
