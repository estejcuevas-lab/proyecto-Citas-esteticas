<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Appointment;

use Distributed\Protocol\XmlAppointmentProtocol;
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
        private readonly AppointmentSkeleton $skeleton = new AppointmentSkeleton(),
        private readonly XmlAppointmentProtocol $protocol = new XmlAppointmentProtocol()
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
                // ======================================================================
                // GUIA 8 - ACTIVIDAD 3: INTEGRACION DEL PROTOCOLO XML EN EL SISTEMA
                // El servidor recibe XML desde el socket, lo procesa y devuelve XML como respuesta oficial del protocolo.
                // ======================================================================
                $request = SocketTransport::readRawMessage($client);
                $response = $this->skeleton->handle($request);
                SocketTransport::writeRawMessage($client, $response);
            } catch (RuntimeException $exception) {
                SocketTransport::writeRawMessage(
                    $client,
                    $this->protocol->buildError('reserve_appointment', $exception->getMessage())
                );
            } finally {
                SocketTransport::close($client);
            }
        }
    }
}
