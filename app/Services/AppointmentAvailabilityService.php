<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Services;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Service;
use Carbon\Carbon;

class AppointmentAvailabilityService
{
    // ======================================================================
    // GUIA 2 - ACTIVIDAD 3: ESTRUCTURA DE CAPAS
    // Este servicio concentra reglas de agenda para mantener separada la logica de negocio.
    // ======================================================================
    public function calculateEndTime(Service $service, string $startTime): string
    {
        return Carbon::createFromFormat('H:i', $startTime)
            ->addMinutes($service->duration_minutes)
            ->format('H:i');
    }

    public function isWithinBusinessHours(Business $business, string $date, string $startTime, string $endTime): bool
    {
        // ======================================================================
        // GUIA 2 - ACTIVIDAD 4: INTEGRACION INICIAL
        // Aqui se verifica la conexion funcional entre datos del negocio, horarios y agenda.
        // ======================================================================
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        $businessHour = $business->hours()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (! $businessHour) {
            return false;
        }

        return $startTime >= $businessHour->opens_at && $endTime <= $businessHour->closes_at;
    }

    public function hasOverlap(
        Business $business,
        string $date,
        string $startTime,
        string $endTime,
        ?int $ignoreAppointmentId = null
    ): bool {
        // ======================================================================
        // GUIA 4 - ACTIVIDAD 1: ATRIBUTOS DE CALIDAD
        // Centralizar esta regla mejora mantenibilidad y consistencia del comportamiento.
        // ======================================================================
        return Appointment::query()
            ->where('business_id', $business->id)
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
