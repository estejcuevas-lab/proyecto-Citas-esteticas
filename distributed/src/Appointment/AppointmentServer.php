<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Appointment;

use Distributed\Registry\RegistryClient;
use Distributed\Support\SocketTransport;
use RuntimeException;

class AppointmentServer
{
    // ======================================================================
    // GUIA 6 - ACTIVIDAD 2: OPERACION BIND
    // El servidor registra su servicio antes de entrar al ciclo de escucha para habilitar el descubrimiento remoto.
    // ======================================================================
    public function __construct(
        private readonly string $serviceName,
        private readonly string $host,
        private readonly int $port,
        private readonly RegistryClient $registryClient,
        private readonly AppointmentSkeleton $skeleton = new AppointmentSkeleton()
    ) {
    }

    public function start(): void
    {
        $this->registryClient->bind($this->serviceName, $this->host, $this->port);

        $server = SocketTransport::createServer($this->host, $this->port);

        echo "[SERVER] Servicio {$this->serviceName} activo en {$this->host}:{$this->port}\n";

        while (true) {
            $client = @socket_accept($server);

            if (! $client) {
                continue;
            }

            try {
                $request = SocketTransport::readMessage($client);
                $response = $this->skeleton->handle($request);
                SocketTransport::writeMessage($client, $response);
            } catch (RuntimeException $exception) {
                SocketTransport::writeMessage($client, [
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                ]);
            } finally {
                SocketTransport::close($client);
            }
        }
    }
}
