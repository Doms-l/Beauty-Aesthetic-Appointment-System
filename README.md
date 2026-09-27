# M. Cares Beauty Services

Web-Based Aesthetic Clinic Appointment and Management System built with Laravel, Blade, React, Vite, and MySQL.

## Main modules
- Public home page
- Services catalog
- Client registration/login/logout
- Client profile
- Client appointment requests
- Appointment history and cancellation
- Admin dashboard
- Admin service management
- Admin staff management
- Admin appointment management
- Staff appointment dashboard
- Role-based access control
- CSRF protection, validation, throttling, password hashing, session regeneration

## Logo and colors
The supplied M. Cares logo is stored at `public/images/logo.png`.

Palette based on the supplied logo:
- Soft Blush: #F3BCD2
- Pink: #FFAFF1
- Rose: #E8AECF
- Cream: #FDEEBB
- Champagne Gold: #D6B36A
- Mauve: #62444D
- Deep Charcoal: #222222

## Demo accounts
Admin:
- Email: admin@mcares.test
- Password: Admin@12345

Staff:
- Email: staff@mcares.test
- Password: Staff@12345

Change these credentials before real deployment.

## Local setup
1. Install PHP 8.3+, Composer, Node.js/npm, and MySQL/XAMPP.
2. Create a MySQL database named `macayla_cares`.
3. Copy `.env.example` to `.env`.
4. Run `composer install`.
5. Run `php artisan key:generate`.
6. Check `.env` database credentials.
7. Run `php artisan migrate --seed`.
8. Run `npm install`.
9. Run `npm run dev` in one terminal.
10. Run `php artisan serve` in another terminal.
11. Open http://127.0.0.1:8000.

For a production build, run `npm run build` and configure a production web server.
