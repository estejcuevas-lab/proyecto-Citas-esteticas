<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

$config = require __DIR__.'/bootstrap.php';

use Distributed\Appointment\AppointmentServer;
use Distributed\Registry\RegistryClient;

$service = $config['services']['appointment_service'];

// ======================================================================
// GUIA 6 - ACTIVIDAD 2: OPERACION BIND
// El servidor vincula el nombre logico del servicio con su referencia fisica en el registry.
// ======================================================================
$server = new AppointmentServer(
    'appointment_service',
    $service['host'],
    $service['port'],
    new RegistryClient($config['registry']['host'], $config['registry']['port'])
);

$server->start();
