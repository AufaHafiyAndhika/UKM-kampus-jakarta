@echo off
echo ========================================
echo    UKM Web Laravel Project Setup
echo ========================================
echo.

echo [1/6] Checking PHP version...
php --version
if %errorlevel% neq 0 (
    echo ERROR: PHP not found! Please install PHP 8.2+ or add it to PATH
    pause
    exit /b 1
)
echo.

echo [2/6] Checking Composer...
composer --version
if %errorlevel% neq 0 (
    echo ERROR: Composer not found! Please install Composer
    pause
    exit /b 1
)
echo.

echo [3/6] Installing PHP dependencies...
composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo ERROR: Failed to install PHP dependencies
    pause
    exit /b 1
)
echo.

echo [4/6] Installing Node.js dependencies...
npm install
if %errorlevel% neq 0 (
    echo WARNING: Failed to install Node.js dependencies
    echo You may need to install Node.js first
)
echo.

echo [5/6] Setting up Laravel...
php artisan key:generate --force
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo.

echo [6/6] Building frontend assets...
npm run build
if %errorlevel% neq 0 (
    echo WARNING: Failed to build frontend assets
    echo You can run 'npm run dev' later for development
)
echo.

echo ========================================
echo           Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Make sure database 'ukmwebv' is created in phpMyAdmin
echo 2. Import the ukmwebv.sql file into the database
echo 3. Run: php artisan serve
echo 4. Open: http://localhost:8000
echo.
echo For development with hot reload:
echo Run: npm run dev (in separate terminal)
echo.
pause
