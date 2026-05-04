<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Appointment;

use Distributed\Dto\RemoteAppointment;
use Distributed\Protocol\XmlAppointmentProtocol;
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
        private readonly string $serviceName,
        private readonly XmlAppointmentProtocol $protocol = new XmlAppointmentProtocol()
    ) {
    }

    public function reserveAppointment(RemoteAppointment $appointment): array
    {
        return $this->reserveAppointmentDetailed($appointment)['payload'];
    }

    public function reserveAppointmentDetailed(RemoteAppointment $appointment): array
    {
        // ======================================================================
        // GUIA 8 - ACTIVIDAD 3: INTEGRACION DEL PROTOCOLO XML EN EL SISTEMA
        // El cliente ya no envia estructuras serializadas libres, sino una solicitud XML definida por el protocolo.
        // ======================================================================
        // ======================================================================
        // GUIA 6 - ACTIVIDAD 4: ELIMINACION DE ACOPLAMIENTO
        // El cliente evita depender de IPs fijas porque resuelve el servicio por nombre mediante lookup.
        // ======================================================================
        $service = $this->registryClient->lookup($this->serviceName);
        $socket = SocketTransport::connect($service['host'], (int) $service['port']);

        try {
            $requestXml = $this->protocol->buildRequest('reserve_appointment', $appointment->toArray());
            SocketTransport::writeRawMessage($socket, $requestXml);

            $responseXml = SocketTransport::readRawMessage($socket);
            $response = $this->protocol->parseResponse($responseXml);

            if (($response['type'] ?? 'error') === 'error') {
                throw new RuntimeException($response['payload']['message'] ?? 'Fallo la invocacion remota.');
            }

            return [
                'xml' => $responseXml,
                'payload' => $response['payload'],
            ];
        } finally {
            SocketTransport::close($socket);
        }
    }
}
