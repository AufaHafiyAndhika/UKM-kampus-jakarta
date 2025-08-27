@echo off
echo ========================================
echo   SETUP NEW WORKFLOW SYSTEM
echo ========================================
echo.

echo [1/6] Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo.
echo [2/6] Running migrations...
php artisan migrate --force

echo.
echo [3/6] Creating admin user...
php create-admin-user.php

echo.
echo [4/6] Creating sample ketua UKM users...
php artisan tinker --execute="
\$ketua1 = App\Models\User::updateOrCreate(['email' => 'ketua1@student.telkomuniversity.ac.id'], [
    'nim' => '1234567890',
    'name' => 'Ahmad Ketua UKM',
    'email' => 'ketua1@student.telkomuniversity.ac.id',
    'password' => Hash::make('password123'),
    'phone' => '081234567890',
    'gender' => 'male',
    'faculty' => 'Fakultas Informatika',
    'major' => 'Sistem Informasi',
    'batch' => '2022',
    'role' => 'ketua_ukm',
    'status' => 'active',
    'email_verified_at' => now(),
]);

\$ketua2 = App\Models\User::updateOrCreate(['email' => 'ketua2@student.telkomuniversity.ac.id'], [
    'nim' => '1234567891',
    'name' => 'Siti Ketua UKM',
    'email' => 'ketua2@student.telkomuniversity.ac.id',
    'password' => Hash::make('password123'),
    'phone' => '081234567891',
    'gender' => 'female',
    'faculty' => 'Fakultas Teknik Elektro',
    'major' => 'Teknik Informatika',
    'batch' => '2022',
    'role' => 'ketua_ukm',
    'status' => 'active',
    'email_verified_at' => now(),
]);

echo 'Sample Ketua UKM users created successfully!';
"

echo.
echo [5/6] Testing system...
php test-unified-login.php

echo.
echo [6/6] Starting server...
echo.
echo ========================================
echo   NEW WORKFLOW SYSTEM READY!
echo ========================================
echo.
echo CHANGES IMPLEMENTED:
echo ✓ Registration requires admin approval
echo ✓ Success page with contact info (WA: 081382640946)
echo ✓ Role "Ketua UKM" added
echo ✓ UKM can have assigned leader (Ketua UKM)
echo ✓ Status "Pending" for new registrations
echo ✓ Clean role system: Mahasiswa, Ketua UKM, Admin
echo.
echo LOGIN INFORMATION:
echo Admin: admin@telkomuniversity.ac.id / admin123
echo Ketua UKM 1: ketua1@student.telkomuniversity.ac.id / password123
echo Ketua UKM 2: ketua2@student.telkomuniversity.ac.id / password123
echo.
echo WORKFLOW:
echo 1. Guest registers → Status: Pending
echo 2. Admin approves → Status: Active
echo 3. Admin assigns Ketua UKM role
echo 4. Admin assigns Ketua UKM to UKM
echo.
echo Starting Laravel server...
echo.

php artisan serve --host=127.0.0.1 --port=8000
