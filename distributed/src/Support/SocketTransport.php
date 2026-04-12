<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Support;

use RuntimeException;

class SocketTransport
{
    public static function createServer(string $host, int $port): \Socket
    {
        // ======================================================================
        // GUIA 6 - ACTIVIDAD 2: OPERACION BIND
        // El transporte crea el socket del servidor y lo vincula al host y puerto configurados.
        // ======================================================================
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($socket === false) {
            throw new RuntimeException('No se pudo crear el socket del servidor.');
        }

        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

        if (! socket_bind($socket, $host, $port)) {
            throw new RuntimeException("No se pudo hacer bind en {$host}:{$port}.");
        }

        if (! socket_listen($socket, 5)) {
            throw new RuntimeException('No se pudo poner el socket en modo listen.');
        }

        return $socket;
    }

    public static function connect(string $host, int $port): \Socket
    {
        // ======================================================================
        // GUIA 6 - ACTIVIDAD 3: OPERACION LOOKUP
        // Una vez resuelto el servicio, el cliente abre la conexion a su referencia fisica.
        // ======================================================================
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($socket === false) {
            throw new RuntimeException('No se pudo crear el socket del cliente.');
        }

        if (! @socket_connect($socket, $host, $port)) {
            throw new RuntimeException("No fue posible conectar con {$host}:{$port}.");
        }

        return $socket;
    }

    public static function writeMessage(\Socket $socket, array $message): void
    {
        // ======================================================================
        // GUIA 5 - ACTIVIDAD 3: MARSHALLING
        // El mensaje remoto se serializa a bytes antes de viajar por la red.
        // ======================================================================
        $payload = serialize($message)."\n";

        if (@socket_write($socket, $payload, strlen($payload)) === false) {
            throw new RuntimeException('No fue posible escribir en el socket.');
        }
    }

    public static function readMessage(\Socket $socket): array
    {
        // ======================================================================
        // GUIA 5 - ACTIVIDAD 4: UNMARSHALLING
        // El receptor reconstruye el mensaje original a partir del flujo de bytes recibido.
        // ======================================================================
        $payload = trim((string) @socket_read($socket, 4096, PHP_NORMAL_READ));

        if ($payload === '') {
            throw new RuntimeException('No se recibio payload desde el socket.');
        }

        $message = @unserialize($payload);

        if (! is_array($message)) {
            throw new RuntimeException('No fue posible reconstruir el mensaje remoto.');
        }

        return $message;
    }

    public static function close(\Socket $socket): void
    {
        @socket_close($socket);
    }
}
