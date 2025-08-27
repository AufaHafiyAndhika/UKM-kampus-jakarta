-- Assign Leaders to UKMs
-- This will assign some students as UKM leaders and leave some without leaders

-- First, let's see available students
SELECT 'Available Students:' as info;
SELECT id, name, email, nim FROM users WHERE role = 'student' ORDER BY name;

-- Assign leaders to some UKMs (using student IDs)
-- Note: Adjust the user IDs based on your actual student data

-- Get the first few student IDs
SET @student1 = (SELECT id FROM users WHERE role = 'student' ORDER BY name LIMIT 1);
SET @student2 = (SELECT id FROM users WHERE role = 'student' ORDER BY name LIMIT 1,1);
SET @student3 = (SELECT id FROM users WHERE role = 'student' ORDER BY name LIMIT 2,1);
SET @student4 = (SELECT id FROM users WHERE role = 'student' ORDER BY name LIMIT 3,1);
SET @student5 = (SELECT id FROM users WHERE role = 'student' ORDER BY name LIMIT 4,1);
SET @student6 = (SELECT id FROM users WHERE role = 'student' ORDER BY name LIMIT 5,1);

-- Assign leaders to UKMs
UPDATE ukms SET leader_id = @student1, updated_at = NOW() WHERE slug = 'badminton';
UPDATE ukms SET leader_id = @student2, updated_at = NOW() WHERE slug = 'dpm';
UPDATE ukms SET leader_id = @student3, updated_at = NOW() WHERE slug = 'esport';
UPDATE ukms SET leader_id = @student4, updated_at = NOW() WHERE slug = 'futsal';
UPDATE ukms SET leader_id = @student5, updated_at = NOW() WHERE slug = 'imma';
UPDATE ukms SET leader_id = @student6, updated_at = NOW() WHERE slug = 'mapala';

-- Leave some UKMs without leaders to demonstrate the fallback message
-- pmk, seni-budaya, sistem-informasi will not have leaders

-- Show results
SELECT 'UKM Leadership Status:' as info;
SELECT 
    u.name as 'UKM Name',
    u.slug as 'Slug',
    CASE 
        WHEN us.name IS NOT NULL THEN us.name
        ELSE 'Belum ada ketua'
    END as 'Leader Name',
    CASE 
        WHEN us.nim IS NOT NULL THEN us.nim
        ELSE '-'
    END as 'Leader NIM',
    CASE 
        WHEN us.email IS NOT NULL THEN us.email
        ELSE '-'
    END as 'Leader Email'
FROM ukms u
LEFT JOIN users us ON u.leader_id = us.id
ORDER BY u.name;

-- Summary
SELECT 
    'Summary' as 'Info',
    COUNT(CASE WHEN leader_id IS NOT NULL THEN 1 END) as 'UKMs with Leader',
    COUNT(CASE WHEN leader_id IS NULL THEN 1 END) as 'UKMs without Leader',
    COUNT(*) as 'Total UKMs'
FROM ukms;
