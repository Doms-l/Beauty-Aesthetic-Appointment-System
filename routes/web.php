<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\AppointmentController as ClientAppointmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\SocialLinkController as AdminSocialLinkController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\AppointmentController as StaffAppointmentController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Uploaded photos (profile-pictures/...). Normally the "public/storage" link serves
// them; if that link is missing or broken (common on Windows) this route does it.
Route::get('/storage/profile-pictures/{file}', function (string $file) {
    $path = 'profile-pictures/' . basename($file);

    abort_unless(\Illuminate\Support\Facades\Storage::disk('public')->exists($path), 404);

    return \Illuminate\Support\Facades\Storage::disk('public')->response($path);
})->where('file', '[A-Za-z0-9._-]+')->name('storage.profile-picture');

Route::get('/services', [ServiceController::class, 'index'])
    ->name('services.index');

// Chatbot (multilingual). Throttled because every call costs API usage.
Route::post('/chatbot', ChatController::class)
    ->middleware('throttle:20,1')
    ->name('chatbot');


// =========================
// AUTH
// =========================

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('register.store');

    // Email verification code (registration)
    Route::get('/register/verify', [RegisterController::class, 'showVerify'])
        ->name('register.verify');

    Route::post('/register/verify', [RegisterController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('register.verify.store');

    Route::post('/register/resend', [RegisterController::class, 'resend'])
        ->middleware('throttle:3,1')
        ->name('register.resend');

    // Forgot password (emailed code)
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])
        ->middleware('throttle:5,1')
        ->name('password.email');

    Route::get('/reset-password', [ForgotPasswordController::class, 'edit'])
        ->name('password.reset');

    Route::post('/reset-password', [ForgotPasswordController::class, 'update'])
        ->middleware('throttle:10,1')
        ->name('password.update');

    Route::post('/reset-password/resend', [ForgotPasswordController::class, 'resend'])
        ->middleware('throttle:3,1')
        ->name('password.resend');
});


Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


// =========================
// CLIENT
// =========================

Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

        Route::get('/appointments', [ClientAppointmentController::class, 'index'])
            ->name('appointments');

        Route::get('/appointments/create', [ClientAppointmentController::class, 'create'])
            ->name('appointments.create');

        Route::post('/appointments', [ClientAppointmentController::class, 'store'])
            ->name('appointments.store');

        Route::patch('/appointments/{appointment}/cancel', [ClientAppointmentController::class, 'cancel'])
            ->name('appointments.cancel');
    });


// =========================
// STAFF
// =========================

Route::middleware(['auth', 'role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/dashboard', [StaffDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/appointments', [StaffAppointmentController::class, 'index'])
            ->name('appointments');

        Route::patch('/appointments/{appointment}', [StaffAppointmentController::class, 'update'])
            ->name('appointments.update');

        Route::patch('/appointments/{appointment}/claim', [StaffAppointmentController::class, 'claim'])
            ->name('appointments.claim');
    });


// =========================
// ADMIN
// =========================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ADMIN PROFILE
        Route::get('/profile', [AdminProfileController::class, 'edit'])
            ->name('profile');

        Route::put('/profile', [AdminProfileController::class, 'update'])
            ->name('profile.update');


        // ADMIN DASHBOARD
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');


        // APPOINTMENTS
        Route::get('/appointments', [AdminAppointmentController::class, 'index'])
            ->name('appointments');

        Route::patch('/appointments/{appointment}', [AdminAppointmentController::class, 'update'])
            ->name('appointments.update');

        Route::patch('/appointments/{appointment}/unsave', [AdminAppointmentController::class, 'unsave'])
            ->name('appointments.unsave');

        Route::patch('/appointments/{appointment}/archive', [AdminAppointmentController::class, 'archive'])
            ->name('appointments.archive');

        Route::patch('/appointments/{appointment}/restore', [AdminAppointmentController::class, 'restore'])
            ->name('appointments.restore');


        // SERVICES
        Route::get('/services', [AdminServiceController::class, 'index'])
            ->name('services');

        Route::post('/services', [AdminServiceController::class, 'store'])
            ->name('services.store');

        Route::put('/services/{service}', [AdminServiceController::class, 'update'])
            ->name('services.update');

        Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])
            ->name('services.destroy');


        // PROMOS (any number of them)
        Route::get('/promo', [AdminPromoController::class, 'index'])
            ->name('promo');

        Route::post('/promo', [AdminPromoController::class, 'store'])
            ->name('promo.store');

        Route::put('/promo/{promo}', [AdminPromoController::class, 'update'])
            ->name('promo.update');

        Route::delete('/promo/{promo}', [AdminPromoController::class, 'destroy'])
            ->name('promo.destroy');


        // CLIENTS (ban / unban)
        Route::get('/clients', [AdminClientController::class, 'index'])
            ->name('clients');

        Route::patch('/clients/{user}/unban', [AdminClientController::class, 'unban'])
            ->name('clients.unban');

        Route::patch('/clients/{user}/ban', [AdminClientController::class, 'ban'])
            ->name('clients.ban');


        // SOCIAL ACCOUNTS (shown in the footer)
        Route::get('/socials', [AdminSocialLinkController::class, 'index'])
            ->name('socials');

        Route::post('/socials', [AdminSocialLinkController::class, 'store'])
            ->name('socials.store');

        Route::put('/socials/{social}', [AdminSocialLinkController::class, 'update'])
            ->name('socials.update');

        Route::delete('/socials/{social}', [AdminSocialLinkController::class, 'destroy'])
            ->name('socials.destroy');


        // STAFF
        Route::get('/staff', [AdminStaffController::class, 'index'])
            ->name('staff');

        Route::post('/staff', [AdminStaffController::class, 'store'])
            ->name('staff.store');

        Route::put('/staff/owner', [AdminStaffController::class, 'updateOwner'])
            ->name('owner.update');

        Route::put('/staff/{staff}', [AdminStaffController::class, 'update'])
            ->name('staff.update');

        Route::delete('/staff/{staff}', [AdminStaffController::class, 'destroy'])
            ->name('staff.destroy');
    });