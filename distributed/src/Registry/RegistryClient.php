<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Registry;

use Distributed\Support\SocketTransport;
use RuntimeException;

class RegistryClient
{
    // ======================================================================
    // GUIA 6 - ACTIVIDAD 3: OPERACION LOOKUP
    // El cliente consulta el registry para ubicar dinamicamente el servicio remoto por nombre.
    // ======================================================================
    public function __construct(
        private readonly string $host,
        private readonly int $port
    ) {
    }

    public function bind(string $serviceName, string $host, int $port): void
    {
        // ======================================================================
        // GUIA 6 - ACTIVIDAD 2: OPERACION BIND
        // Esta operacion publica la referencia fisica del servicio en el registro central.
        // ======================================================================
        $response = $this->send([
            'action' => 'BIND',
            'service' => $serviceName,
            'host' => $host,
            'port' => $port,
        ]);

        if (($response['status'] ?? 'error') !== 'ok') {
            throw new RuntimeException($response['message'] ?? 'No fue posible registrar el servicio.');
        }
    }

    public function lookup(string $serviceName): array
    {
        $response = $this->send([
            'action' => 'LOOKUP',
            'service' => $serviceName,
        ]);

        if (($response['status'] ?? 'error') !== 'ok') {
            throw new RuntimeException($response['message'] ?? 'No fue posible consultar el servicio.');
        }

        return $response['service'];
    }

    private function send(array $message): array
    {
        $socket = SocketTransport::connect($this->host, $this->port);

        try {
            SocketTransport::writeMessage($socket, $message);

            return SocketTransport::readMessage($socket);
        } finally {
            SocketTransport::close($socket);
        }
    }
}
