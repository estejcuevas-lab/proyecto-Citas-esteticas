<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Services;

use App\Data\AppointmentRepository;
use App\Data\HolidayRepository;
use App\Models\Business;
use App\Models\Service;
use Carbon\Carbon;

class AppointmentAvailabilityService
{
    // ======================================================================
    // GUIA 5 - ACTIVIDAD 4: ENCAPSULAMIENTO AVANZADO
    // Las dependencias quedan encapsuladas como propiedades privadas del servicio de agenda.
    // ======================================================================
    public function __construct(
        private readonly AppointmentRepository $appointmentRepository,
        private readonly HolidayRepository $holidayRepository
    ) {
    }

    // ======================================================================
    // GUIA 2 - ACTIVIDAD 3: ESTRUCTURA DE CAPAS
    // Este servicio concentra reglas de agenda para mantener separada la logica de negocio.
    // ======================================================================
    public function calculateEndTime(Service $service, string $startTime): string
    {
        return Carbon::parse($this->normalizeTime($startTime))
            ->addMinutes($service->duration_minutes)
            ->format('H:i');
    }

    public function normalizeTime(string $time): string
    {
        return substr(trim($time), 0, 5);
    }

    public function isWithinBusinessHours(Business $business, string $date, string $startTime, string $endTime): bool
    {
        // ======================================================================
        // GUIA 2 - ACTIVIDAD 4: INTEGRACION INICIAL
        // Aqui se verifica la conexion funcional entre datos del negocio, horarios y agenda.
        // ======================================================================
        if ($this->isHoliday($date)) {
            return false;
        }

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

    public function isHoliday(string $date, string $countryCode = 'CO'): bool
    {
        // ======================================================================
        // GUIA 6 - ACTIVIDAD 3: COMPONENTE DE ACCESO
        // La verificacion delega el acceso a datos a la capa app/Data en lugar de consultar directo desde el servicio.
        // ======================================================================
        return $this->holidayRepository->existsOnDate($date, $countryCode);
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
        return $this->appointmentRepository->hasOverlap(
            $business->id,
            $date,
            $startTime,
            $endTime,
            $ignoreAppointmentId
        );
    }
}
