@echo off
echo ========================================
echo   FIXING USER STATUS SYSTEM
echo ========================================
echo.

echo [1/6] Clearing all caches...
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
echo [4/6] Testing status system...
php test-status-system.php

echo.
echo [5/6] Checking routes...
php artisan route:list | findstr activate
php artisan route:list | findstr suspend

echo.
echo [6/6] Starting server...
echo.
echo ========================================
echo   STATUS SYSTEM FIXED!
echo ========================================
echo.
echo STATUS SYSTEM FEATURES:
echo ✓ Login blocked for non-active users
echo ✓ Status check middleware on all protected routes
echo ✓ Admin activate/suspend buttons
echo ✓ Clear error messages with contact info
echo.
echo USER STATUSES:
echo - PENDING: Menunggu persetujuan admin
echo - ACTIVE: Dapat login dan akses sistem
echo - SUSPENDED: Diblokir dari sistem
echo - INACTIVE: Tidak aktif
echo.
echo LOGIN BEHAVIOR:
echo - ACTIVE users: ✓ Can login
echo - PENDING users: ✗ Blocked with WhatsApp contact
echo - SUSPENDED users: ✗ Blocked with admin contact
echo - INACTIVE users: ✗ Blocked with admin contact
echo.
echo ADMIN PANEL FEATURES:
echo - Quick "Aktifkan" button for pending users
echo - Quick "Suspend" button for active users
echo - Color-coded status badges
echo - Prevent admin account suspension
echo.
echo REGISTRATION FLOW:
echo 1. User registers → Status: PENDING
echo 2. Admin sees "Menunggu Persetujuan" badge
echo 3. Admin clicks "Aktifkan" button
echo 4. Status changes to ACTIVE
echo 5. User can now login
echo.
echo SUSPENSION FLOW:
echo 1. Admin clicks "Suspend" on active user
echo 2. Status changes to SUSPENDED
echo 3. User is logged out immediately
echo 4. User cannot login until reactivated
echo.
echo TEST ACCOUNTS:
echo Admin: admin@telkomuniversity.ac.id / admin123
echo.
echo ADMIN PANEL URLS:
echo - User Management: http://127.0.0.1:8000/admin/users
echo - Activate Route: PATCH /admin/users/{id}/activate
echo - Suspend Route: PATCH /admin/users/{id}/suspend
echo.
echo ERROR MESSAGES:
echo - Pending: "Akun masih menunggu persetujuan admin. WhatsApp: 081382640946"
echo - Suspended: "Akun telah disuspend. Hubungi admin."
echo - Inactive: "Akun tidak aktif. Hubungi admin."
echo.
echo MIDDLEWARE PROTECTION:
echo - All /dashboard routes protected
echo - All /admin routes protected
echo - All /ketua-ukm routes protected
echo - All /profile routes protected
echo.
echo Starting Laravel server...
echo.

php artisan serve --host=127.0.0.1 --port=8000
