<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\BusinessHourController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

// ======================================================================
// GUIA 2 - ACTIVIDAD 1: INTEROPERABILIDAD
// Las rutas API permiten intercambio de informacion JSON entre clientes externos y el servidor.
// ======================================================================
Route::get('/businesses', [BusinessController::class, 'index']);
Route::get('/businesses/{business}', [BusinessController::class, 'show']);
Route::get('/businesses/{business}/services', [ServiceController::class, 'index']);
Route::get('/businesses/{business}/hours', [BusinessHourController::class, 'index']);
Route::get('/businesses/{business}/availability', [AvailabilityController::class, 'show']);

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
});
