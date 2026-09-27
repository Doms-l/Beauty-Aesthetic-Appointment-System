$ErrorActionPreference = 'Stop'

Write-Host 'Starting Laravel server and Vite...' -ForegroundColor Magenta

Start-Process powershell -ArgumentList '-NoExit', '-Command', 'php artisan serve'
Start-Process powershell -ArgumentList '-NoExit', '-Command', 'npm run dev'

Start-Sleep -Seconds 2
Start-Process 'http://127.0.0.1:8000'

Write-Host 'Laravel: http://127.0.0.1:8000' -ForegroundColor Green
Write-Host 'Two terminal windows were opened for Laravel and Vite.' -ForegroundColor Cyan
