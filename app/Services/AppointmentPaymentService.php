<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;

class AppointmentPaymentService
{
    // ======================================================================
    // GUIA 4 - ACTIVIDAD 1: ATRIBUTOS DE CALIDAD
    // El calculo del adelanto se centraliza para mantener consistencia en todas las capas.
    // ======================================================================
    public function buildPaymentData(Service $service, ?string $paymentStatus = null): array
    {
        $servicePrice = round((float) $service->price, 2);
        $advancePercentage = 50.00;
        $advanceAmount = round($servicePrice * ($advancePercentage / 100), 2);

        return [
            'service_price' => $servicePrice,
            'advance_percentage' => $advancePercentage,
            'advance_amount' => $advanceAmount,
            'payment_status' => $paymentStatus ?: Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
        ];
    }
}
