@echo off
echo Clearing Laravel caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo Starting Laravel server...
php artisan serve --host=127.0.0.1 --port=8000
