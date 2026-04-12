<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Appointment;

use Distributed\Dto\RemoteAppointment;
use RuntimeException;

class AppointmentSkeleton
{
    // ======================================================================
    // GUIA 5 - ACTIVIDAD 4: UNMARSHALLING
    // El skeleton recibe el payload, reconstruye el objeto remoto y delega la logica al servicio de negocio.
    // ======================================================================
    public function __construct(
        private readonly AppointmentService $service = new AppointmentService()
    ) {
    }

    public function handle(array $request): array
    {
        $operation = $request['operation'] ?? null;

        if ($operation !== 'reserve_appointment') {
            throw new RuntimeException('Operacion remota no soportada.');
        }

        $payload = $request['payload'] ?? null;

        if (! is_array($payload)) {
            throw new RuntimeException('Payload remoto invalido.');
        }

        $appointment = RemoteAppointment::fromArray($payload);

        return $this->service->reserve($appointment);
    }
}
