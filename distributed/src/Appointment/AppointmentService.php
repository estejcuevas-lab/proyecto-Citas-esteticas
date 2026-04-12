<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Appointment;

use Distributed\Dto\RemoteAppointment;

class AppointmentService
{
    // ======================================================================
    // GUIA 5 - ACTIVIDAD 5: TRANSPARENCIA
    // Esta operacion de negocio es la que el cliente remoto invoca como si fuera una llamada local.
    // ======================================================================
    public function reserve(RemoteAppointment $appointment): array
    {
        return [
            'status' => 'ok',
            'message' => 'Cita remota procesada correctamente.',
            'appointment' => $appointment->toArray(),
        ];
    }
}
