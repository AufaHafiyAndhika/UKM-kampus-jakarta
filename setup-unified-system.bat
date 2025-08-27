@echo off
echo ========================================
echo   SETUP UNIFIED LOGIN SYSTEM
echo ========================================
echo.

echo [1/5] Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo.
echo [2/5] Creating admin users...
php artisan db:seed --class=AdminUserSeeder

echo.
echo [3/5] Testing system...
php test-unified-login.php

echo.
echo [3.5/5] Testing admin routes...
php test-admin-routes.php

echo.
echo [4/5] Building assets...
npm run build

echo.
echo [5/5] Starting server...
echo.
echo ========================================
echo   UNIFIED LOGIN SYSTEM READY!
echo ========================================
echo.
echo Login URL: http://127.0.0.1:8000/login
echo.
echo ADMIN LOGIN:
echo Email: admin@telkomuniversity.ac.id
echo Password: admin123
echo.
echo STUDENT LOGIN:
echo Use any student email from database
echo.
echo Starting Laravel server...
echo.

php artisan serve --host=127.0.0.1 --port=8000
