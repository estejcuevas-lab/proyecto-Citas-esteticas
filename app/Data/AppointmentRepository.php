<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Data;

use App\Models\Appointment;

class AppointmentRepository
{
    // ======================================================================
    // GUIA 6 - ACTIVIDAD 3: COMPONENTE DE ACCESO
    // Este repositorio aisla consultas de citas para mantener separada la capa de persistencia.
    // ======================================================================
    public function hasOverlap(
        int $businessId,
        string $date,
        string $startTime,
        string $endTime,
        ?int $ignoreAppointmentId = null
    ): bool {
        return Appointment::query()
            ->where('business_id', $businessId)
            ->where('appointment_date', $date)
            ->when($ignoreAppointmentId, fn ($query) => $query->where('id', '!=', $ignoreAppointmentId))
            ->whereIn('status', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_CONFIRMED,
            ])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->exists();
    }
}
