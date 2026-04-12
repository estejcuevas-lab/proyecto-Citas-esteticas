<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Registry;

use Distributed\Support\SocketTransport;
use RuntimeException;

class RegistryServer
{
    // ======================================================================
    // GUIA 6 - ACTIVIDAD 1: MODULO REGISTRY
    // Este servidor implementa el directorio central para registro y descubrimiento de servicios remotos.
    // ======================================================================
    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly ServiceRegistry $registry = new ServiceRegistry()
    ) {
    }

    public function start(): void
    {
        $server = SocketTransport::createServer($this->host, $this->port);

        echo "[REGISTRY] Escuchando en {$this->host}:{$this->port}\n";

        while (true) {
            $client = @socket_accept($server);

            if (! $client) {
                continue;
            }

            try {
                $request = SocketTransport::readMessage($client);
                $response = $this->handle($request);
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

    private function handle(array $request): array
    {
        return match ($request['action'] ?? null) {
            'BIND' => $this->bind($request),
            'LOOKUP' => $this->lookup($request),
            default => throw new RuntimeException('Operacion no soportada por el registry.'),
        };
    }

    private function bind(array $request): array
    {
        $name = (string) ($request['service'] ?? '');
        $host = (string) ($request['host'] ?? '');
        $port = (int) ($request['port'] ?? 0);

        if ($name === '' || $host === '' || $port === 0) {
            throw new RuntimeException('Solicitud BIND incompleta.');
        }

        $this->registry->bind($name, $host, $port);

        return [
            'status' => 'ok',
            'message' => "Servicio {$name} registrado correctamente.",
        ];
    }

    private function lookup(array $request): array
    {
        $name = (string) ($request['service'] ?? '');
        $service = $this->registry->lookup($name);

        if (! $service) {
            throw new RuntimeException("No se encontro el servicio {$name}.");
        }

        return [
            'status' => 'ok',
            'service' => $service,
        ];
    }
}
