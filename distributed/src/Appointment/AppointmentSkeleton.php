<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Appointment;

use Distributed\Dto\RemoteAppointment;
use Distributed\Protocol\XmlAppointmentProtocol;
use RuntimeException;

class AppointmentSkeleton
{
    // ======================================================================
    // GUIA 5 - ACTIVIDAD 4: UNMARSHALLING
    // El skeleton recibe el payload, reconstruye el objeto remoto y delega la logica al servicio de negocio.
    // ======================================================================
    public function __construct(
        private readonly AppointmentService $service = new AppointmentService(),
        private readonly XmlAppointmentProtocol $protocol = new XmlAppointmentProtocol()
    ) {
    }

    public function handle(string $requestXml): string
    {
        // ======================================================================
        // GUIA 8 - ACTIVIDAD 3: INTEGRACION DEL PROTOCOLO XML EN EL SISTEMA
        // El servidor interpreta solicitudes XML y genera respuestas bajo el mismo formato estandarizado.
        // ======================================================================
        $request = $this->protocol->parseRequest($requestXml);
        $operation = $request['operation'] ?? null;

        if ($operation !== 'reserve_appointment') {
            throw new RuntimeException('Operacion remota no soportada.');
        }

        $payload = $request['payload'] ?? null;

        if (! is_array($payload)) {
            throw new RuntimeException('Payload remoto invalido.');
        }

        $appointment = RemoteAppointment::fromArray($payload);

        return $this->protocol->buildResponse(
            $operation,
            $this->service->reserve($appointment)
        );
    }
}
