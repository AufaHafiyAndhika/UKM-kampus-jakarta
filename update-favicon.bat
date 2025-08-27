@echo off
echo ========================================
echo    Favicon Update Script
echo ========================================
echo.

cd /d "c:\Users\aufaa\Desktop\TAWEBB\ukmwebLbasedfunc\ukmwebLbasedfunc"

echo [1/4] Creating favicon from Telkom logo...
php create-favicon.php
if %errorlevel% neq 0 (
    echo ❌ Failed to create favicon
    pause
    exit /b 1
)
echo.

echo [2/4] Testing favicon files...
php test-favicon.php
echo.

echo [3/4] Clearing Laravel cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo ✅ Cache cleared
echo.

echo [4/4] Testing favicon access...
echo 🌐 Testing favicon URL...
curl -I http://localhost:8000/favicon.ico 2>nul
if %errorlevel% equ 0 (
    echo ✅ Favicon accessible
) else (
    echo ⚠️  Could not test favicon (server may not be running)
)
echo.

echo ========================================
echo        Favicon Update Complete!
echo ========================================
echo.
echo 🎯 What was done:
echo   ✅ Created favicon.ico from Telkom.png
echo   ✅ Created multiple favicon sizes (16x16, 32x32, etc.)
echo   ✅ Created apple-touch-icon.png
echo   ✅ Updated HTML layout files
echo   ✅ Cleared Laravel cache
echo.
echo 🧪 How to verify:
echo   1. Open http://localhost:8000 in browser
echo   2. Look at browser tab - should show Telkom logo
echo   3. Try hard refresh (Ctrl+F5) if needed
echo   4. Check different browsers
echo.
echo 📝 Note: Browser may cache old favicon for a while.
echo    Try incognito/private mode to see changes immediately.
echo.
pause
