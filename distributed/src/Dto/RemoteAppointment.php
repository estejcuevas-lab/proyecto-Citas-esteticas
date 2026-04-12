<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Dto;

use Distributed\Contracts\RemoteSerializable;

class RemoteAppointment implements RemoteSerializable
{
    // ======================================================================
    // GUIA 5 - ACTIVIDAD 1: CLASES DE NEGOCIO
    // Esta clase serializable representa la cita remota que viaja entre cliente y servidor.
    // ======================================================================
    public function __construct(
        public string $clientName,
        public string $businessName,
        public string $serviceName,
        public string $appointmentDate,
        public string $startTime
    ) {
    }

    public function toArray(): array
    {
        return [
            'client_name' => $this->clientName,
            'business_name' => $this->businessName,
            'service_name' => $this->serviceName,
            'appointment_date' => $this->appointmentDate,
            'start_time' => $this->startTime,
        ];
    }

    public static function fromArray(array $payload): static
    {
        return new static(
            $payload['client_name'],
            $payload['business_name'],
            $payload['service_name'],
            $payload['appointment_date'],
            $payload['start_time'],
        );
    }
}
