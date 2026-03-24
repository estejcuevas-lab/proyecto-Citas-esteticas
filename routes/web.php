<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('appointments', AppointmentController::class)->except(['show', 'destroy']);
    Route::resource('businesses', BusinessController::class)->except(['show', 'destroy']);
    Route::get('/businesses/{business}/services', [ServiceController::class, 'index'])->name('businesses.services.index');
    Route::get('/businesses/{business}/services/create', [ServiceController::class, 'create'])->name('businesses.services.create');
    Route::post('/businesses/{business}/services', [ServiceController::class, 'store'])->name('businesses.services.store');
    Route::get('/businesses/{business}/services/{service}/edit', [ServiceController::class, 'edit'])->name('businesses.services.edit');
    Route::put('/businesses/{business}/services/{service}', [ServiceController::class, 'update'])->name('businesses.services.update');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
