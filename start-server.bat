@echo off
echo ========================================
echo    Starting UKM Web Laravel Server
echo ========================================
echo.

cd /d "c:\Users\aufaa\Desktop\TAWEBB\ukmwebLbasedfunc\ukmwebLbasedfunc"

echo Current directory: %CD%
echo.

echo Clearing Laravel cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo.

echo Starting Laravel development server...
echo Server will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.

php artisan serve --host=127.0.0.1 --port=8000

pause
