<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Registry;

class ServiceRegistry
{
    private array $services = [];

    // ======================================================================
    // GUIA 9 - ACTIVIDAD 3: SIMULACION DE REGISTRO Y DESCUBRIMIENTO DE SERVICIOS
    // Este directorio central simula un modelo tipo UDDI para registrar nombres logicos y endpoints.
    // ======================================================================
    // ======================================================================
    // GUIA 6 - ACTIVIDAD 2: OPERACION BIND
    // El registry asocia el nombre logico del servicio con su direccion fisica.
    // ======================================================================
    public function bind(string $serviceName, string $host, int $port): void
    {
        $this->services[$serviceName] = [
            'host' => $host,
            'port' => $port,
        ];
    }

    // ======================================================================
    // GUIA 9 - ACTIVIDAD 3: SIMULACION DE REGISTRO Y DESCUBRIMIENTO DE SERVICIOS
    // El cliente descubre el servicio desde este punto central antes de consumirlo.
    // ======================================================================
    // ======================================================================
    // GUIA 6 - ACTIVIDAD 3: OPERACION LOOKUP
    // El cliente consulta este directorio para descubrir el servicio sin depender de IPs fijas en su codigo.
    // ======================================================================
    public function lookup(string $serviceName): ?array
    {
        return $this->services[$serviceName] ?? null;
    }
}
