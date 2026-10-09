<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\AppointmentController as ClientAppointmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');

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

        Route::get('/dashboard', [ClientDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

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

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/appointments', [AdminAppointmentController::class, 'index'])
            ->name('appointments');

        Route::patch('/appointments/{appointment}', [AdminAppointmentController::class, 'update'])
            ->name('appointments.update');
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


        // SERVICES
        Route::get('/services', [AdminServiceController::class, 'index'])
            ->name('services');

        Route::post('/services', [AdminServiceController::class, 'store'])
            ->name('services.store');

        Route::put('/services/{service}', [AdminServiceController::class, 'update'])
            ->name('services.update');

        Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])
            ->name('services.destroy');


        // STAFF
        Route::get('/staff', [AdminStaffController::class, 'index'])
            ->name('staff');

        Route::post('/staff', [AdminStaffController::class, 'store'])
            ->name('staff.store');
    });