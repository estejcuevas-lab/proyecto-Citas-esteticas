<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Appointment;

use Distributed\Dto\RemoteAppointment;
use Distributed\Registry\RegistryClient;
use Distributed\Support\SocketTransport;
use RuntimeException;

class AppointmentServiceStub
{
    // ======================================================================
    // GUIA 5 - ACTIVIDAD 2: IMPLEMENTACION DEL STUB
    // El stub encapsula la conexion, la serializacion y la invocacion remota del servicio.
    // ======================================================================
    public function __construct(
        private readonly RegistryClient $registryClient,
        private readonly string $serviceName
    ) {
    }

    public function reserveAppointment(RemoteAppointment $appointment): array
    {
        // ======================================================================
        // GUIA 6 - ACTIVIDAD 4: ELIMINACION DE ACOPLAMIENTO
        // El cliente evita depender de IPs fijas porque resuelve el servicio por nombre mediante lookup.
        // ======================================================================
        $service = $this->registryClient->lookup($this->serviceName);
        $socket = SocketTransport::connect($service['host'], (int) $service['port']);

        try {
            SocketTransport::writeMessage($socket, [
                'operation' => 'reserve_appointment',
                'payload' => $appointment->toArray(),
            ]);

            $response = SocketTransport::readMessage($socket);

            if (($response['status'] ?? 'error') !== 'ok') {
                throw new RuntimeException($response['message'] ?? 'Fallo la invocacion remota.');
            }

            return $response;
        } finally {
            SocketTransport::close($socket);
        }
    }
}
