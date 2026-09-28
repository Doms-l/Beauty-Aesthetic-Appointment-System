$ErrorActionPreference = 'Stop'

Write-Host '=== M. Cares Beauty Services setup ===' -ForegroundColor Magenta

Write-Host 'Checking required commands...' -ForegroundColor Cyan
php -v
composer --version
node -v
npm -v

if (-not (Test-Path '.env')) {
    Copy-Item '.env.example' '.env'
    Write-Host 'Created .env from .env.example' -ForegroundColor Green
}

Write-Host 'Installing Laravel PHP dependencies...' -ForegroundColor Cyan
composer install

Write-Host 'Generating application key...' -ForegroundColor Cyan
php artisan key:generate

Write-Host 'Running database migrations and demo seed data...' -ForegroundColor Cyan
php artisan migrate --seed

Write-Host 'Installing React/Vite dependencies...' -ForegroundColor Cyan
npm install

Write-Host 'Building frontend assets...' -ForegroundColor Cyan
npm run build

Write-Host ''
Write-Host 'Setup completed.' -ForegroundColor Green
Write-Host 'Start the application with .\start.ps1' -ForegroundColor Yellow
