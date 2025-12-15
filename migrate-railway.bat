@echo off
echo ========================================
echo Railway Migration Script
echo ========================================
echo.

echo Step 1: Checking Railway login...
railway whoami
if errorlevel 1 (
    echo Please run: railway login
    pause
    exit /b 1
)

echo.
echo Step 2: Running migrations...
echo.
railway run php artisan migrate --force --verbose

echo.
echo Step 3: Checking migration status...
echo.
railway run php artisan migrate:status

echo.
echo ========================================
echo Migration complete!
echo ========================================
pause
