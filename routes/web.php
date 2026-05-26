<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\ProfileOnboardingController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BusinessAccessRequestController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BusinessHourController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\PublicBusinessController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

// ======================================================================
// GUIA 1 - ACTIVIDAD 3: ANALISIS DE CAPAS
// Las rutas separan la entrada HTTP de la logica manejada por controladores y vistas.
// ======================================================================
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('home');
Route::get('/negocios', [PublicBusinessController::class, 'index'])->name('public.businesses.index');
Route::get('/negocios/{business:slug}', [PublicBusinessController::class, 'show'])->name('public.businesses.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/onboarding/profile', [ProfileOnboardingController::class, 'edit'])->name('onboarding.profile.edit');
    Route::put('/onboarding/profile', [ProfileOnboardingController::class, 'update'])->name('onboarding.profile.update');

    Route::get('/business-access/request', [BusinessAccessRequestController::class, 'create'])->name('business-access.create');
    Route::post('/business-access/request', [BusinessAccessRequestController::class, 'store'])->name('business-access.store');
    Route::get('/business-access/pending', [BusinessAccessRequestController::class, 'pending'])->name('business-access.pending');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::middleware('profile.completed')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::post('/business-access/{user}/approve', [BusinessAccessRequestController::class, 'approve'])->name('business-access.approve');
        Route::resource('appointments', AppointmentController::class)->except(['show', 'destroy']);
        Route::get('/appointments/{appointment}/payment', [AppointmentController::class, 'showPayment'])->name('appointments.payment.show');
        Route::post('/appointments/{appointment}/payment', [AppointmentController::class, 'processPayment'])->name('appointments.payment.process');
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::patch('/appointments/{appointment}/payment', [AppointmentController::class, 'updatePaymentStatus'])->name('appointments.payment');

        Route::middleware('business.approved')->group(function () {
            Route::post('/holidays/sync', [HolidayController::class, 'sync'])->name('holidays.sync');
            Route::resource('businesses', BusinessController::class)->except(['show', 'destroy']);
            Route::get('/businesses/{business}/hours', [BusinessHourController::class, 'index'])->name('businesses.hours.index');
            Route::get('/businesses/{business}/hours/create', [BusinessHourController::class, 'create'])->name('businesses.hours.create');
            Route::post('/businesses/{business}/hours', [BusinessHourController::class, 'store'])->name('businesses.hours.store');
            Route::get('/businesses/{business}/hours/{hour}/edit', [BusinessHourController::class, 'edit'])->name('businesses.hours.edit');
            Route::put('/businesses/{business}/hours/{hour}', [BusinessHourController::class, 'update'])->name('businesses.hours.update');
            Route::get('/businesses/{business}/services', [ServiceController::class, 'index'])->name('businesses.services.index');
            Route::get('/businesses/{business}/services/create', [ServiceController::class, 'create'])->name('businesses.services.create');
            Route::post('/businesses/{business}/services', [ServiceController::class, 'store'])->name('businesses.services.store');
            Route::get('/businesses/{business}/services/{service}/edit', [ServiceController::class, 'edit'])->name('businesses.services.edit');
            Route::put('/businesses/{business}/services/{service}', [ServiceController::class, 'update'])->name('businesses.services.update');
        });
    });
});
