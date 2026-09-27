# Windows step-by-step setup

## A. Install the requirements

Install:
1. XAMPP with MySQL.
2. PHP 8.3 or newer.
3. Composer.
4. Node.js LTS.
5. Git (recommended).
6. VS Code (recommended).

Verify in PowerShell:

```powershell
php -v
composer --version
node -v
npm -v
```

If `php` points to an older PHP version, fix the Windows PATH so the PHP version used by Composer is PHP 8.3+.

## B. Create the database

Open XAMPP and start MySQL.
Open:
http://localhost/phpmyadmin

Create a database named:
macayla_cares

## C. Open the project

Unzip the project.
Example:
C:\Users\YOUR_NAME\Desktop\Macayla-Cares

Open that folder in VS Code.

## D. Install PHP packages

PowerShell:

```powershell
composer install
```

## E. Create .env

```powershell
Copy-Item .env.example .env
```

Then:

```powershell
php artisan key:generate
```

Check these values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=macayla_cares
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

## F. Create database tables and demo data

```powershell
php artisan migrate --seed
```

If you already ran migrations and want to reset the development database:

```powershell
php artisan migrate:fresh --seed
```

Do NOT use `migrate:fresh` on a real production database.

## G. Install React/Vite packages

```powershell
npm install
```

## H. Start the website

Terminal 1:

```powershell
php artisan serve
```

Terminal 2:

```powershell
npm run dev
```

Open:

http://127.0.0.1:8000

## I. Test the client flow

1. Open Register.
2. Create a client account.
3. You should be redirected to the client dashboard.
4. Open Book Now.
5. Select a service.
6. Select a date and time.
7. Submit the appointment.
8. Open Appointments.
9. The appointment should show Pending.

## J. Test the admin flow

Open Login and use:

Email: admin@mcares.test
Password: Admin@12345

Then test:
- Admin dashboard
- Appointment management
- Service management
- Staff management

## K. Production build

When development is finished:

```powershell
npm run build
```

The production assets will be created under `public/build`.

## Optional: automatic setup

After creating the MySQL database, you can run:

```powershell
powershell -ExecutionPolicy Bypass -File .\setup.ps1
```

Then:

```powershell
powershell -ExecutionPolicy Bypass -File .\start.ps1
```

The scripts only automate the same commands described above. You still need to create the MySQL database and verify `.env` first.
