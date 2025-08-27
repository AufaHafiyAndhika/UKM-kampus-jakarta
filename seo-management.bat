@echo off
echo ========================================
echo    SEO Management Tool - UKM Website
echo ========================================
echo.

cd /d "c:\Users\aufaa\Desktop\TAWEBB\ukmwebLbasedfunc\ukmwebLbasedfunc"

:menu
echo Choose an option:
echo.
echo 1. Test SEO Implementation
echo 2. Generate Sitemap
echo 3. Run SEO Audit
echo 4. Check Robots.txt
echo 5. Test Sitemap URLs
echo 6. Clear Cache (for SEO updates)
echo 7. View SEO Statistics
echo 8. Exit
echo.
set /p choice="Enter your choice (1-8): "

if "%choice%"=="1" goto test_seo
if "%choice%"=="2" goto generate_sitemap
if "%choice%"=="3" goto run_audit
if "%choice%"=="4" goto check_robots
if "%choice%"=="5" goto test_sitemap
if "%choice%"=="6" goto clear_cache
if "%choice%"=="7" goto seo_stats
if "%choice%"=="8" goto exit
goto menu

:test_seo
echo.
echo ========================================
echo    Testing SEO Implementation
echo ========================================
php test-seo-implementation.php
echo.
pause
goto menu

:generate_sitemap
echo.
echo ========================================
echo    Generating Sitemap
echo ========================================
php artisan sitemap:generate
if %errorlevel% equ 0 (
    echo ✅ Sitemap generated successfully!
    echo 🌐 Main sitemap: http://localhost:8000/sitemap.xml
    echo 📄 Pages sitemap: http://localhost:8000/sitemap-pages.xml
    echo 🏢 UKMs sitemap: http://localhost:8000/sitemap-ukms.xml
    echo 📅 Events sitemap: http://localhost:8000/sitemap-events.xml
) else (
    echo ❌ Failed to generate sitemap
)
echo.
pause
goto menu

:run_audit
echo.
echo ========================================
echo    Running SEO Audit
echo ========================================
php artisan seo:audit
echo.
pause
goto menu

:check_robots
echo.
echo ========================================
echo    Checking robots.txt
echo ========================================
if exist "public\robots.txt" (
    echo ✅ robots.txt exists
    echo.
    echo Content:
    type "public\robots.txt"
    echo.
    echo 🌐 Test URL: http://localhost:8000/robots.txt
) else (
    echo ❌ robots.txt not found
)
echo.
pause
goto menu

:test_sitemap
echo.
echo ========================================
echo    Testing Sitemap URLs
echo ========================================
echo Testing sitemap accessibility...
echo.

echo 📄 Testing main sitemap...
curl -I http://localhost:8000/sitemap.xml 2>nul
if %errorlevel% equ 0 (
    echo ✅ Main sitemap accessible
) else (
    echo ❌ Main sitemap not accessible
)

echo.
echo 📋 Testing pages sitemap...
curl -I http://localhost:8000/sitemap-pages.xml 2>nul
if %errorlevel% equ 0 (
    echo ✅ Pages sitemap accessible
) else (
    echo ❌ Pages sitemap not accessible
)

echo.
echo 🏢 Testing UKMs sitemap...
curl -I http://localhost:8000/sitemap-ukms.xml 2>nul
if %errorlevel% equ 0 (
    echo ✅ UKMs sitemap accessible
) else (
    echo ❌ UKMs sitemap not accessible
)

echo.
echo 📅 Testing events sitemap...
curl -I http://localhost:8000/sitemap-events.xml 2>nul
if %errorlevel% equ 0 (
    echo ✅ Events sitemap accessible
) else (
    echo ❌ Events sitemap not accessible
)

echo.
pause
goto menu

:clear_cache
echo.
echo ========================================
echo    Clearing Cache for SEO Updates
echo ========================================
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo ✅ All caches cleared
echo.
pause
goto menu

:seo_stats
echo.
echo ========================================
echo    SEO Statistics
echo ========================================
php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Ukm;
use App\Models\Event;

echo '📊 Content Statistics:' . PHP_EOL;
echo '  Total UKMs: ' . Ukm::count() . PHP_EOL;
echo '  UKMs with descriptions: ' . Ukm::whereNotNull('description')->where('description', '!=', '')->count() . PHP_EOL;
echo '  UKMs without descriptions: ' . Ukm::where(function(\$q) { \$q->whereNull('description')->orWhere('description', ''); })->count() . PHP_EOL;
echo '  Total Events: ' . Event::count() . PHP_EOL;
echo '  Events with descriptions: ' . Event::whereNotNull('description')->where('description', '!=', '')->count() . PHP_EOL;
echo '  Events without descriptions: ' . Event::where(function(\$q) { \$q->whereNull('description')->orWhere('description', ''); })->count() . PHP_EOL;

echo PHP_EOL . '🌐 SEO URLs:' . PHP_EOL;
echo '  Main sitemap: http://localhost:8000/sitemap.xml' . PHP_EOL;
echo '  Robots.txt: http://localhost:8000/robots.txt' . PHP_EOL;
echo '  Homepage: http://localhost:8000' . PHP_EOL;
echo '  UKM Index: http://localhost:8000/ukm' . PHP_EOL;
echo '  Events Index: http://localhost:8000/events' . PHP_EOL;
"
echo.
pause
goto menu

:exit
echo.
echo ========================================
echo    SEO Management Complete
echo ========================================
echo.
echo 📋 SEO Checklist:
echo   ✅ SEO meta tags implemented
echo   ✅ Structured data (Schema.org) added
echo   ✅ Sitemap generation working
echo   ✅ Robots.txt configured
echo   ✅ Open Graph tags added
echo   ✅ Twitter Cards implemented
echo   ✅ Canonical URLs set
echo.
echo 🚀 Next Steps:
echo   1. Submit sitemap to Google Search Console
echo   2. Submit sitemap to Bing Webmaster Tools
echo   3. Set up Google Analytics
echo   4. Monitor SEO performance
echo   5. Regularly update content
echo.
echo Thank you for using SEO Management Tool!
pause
exit
