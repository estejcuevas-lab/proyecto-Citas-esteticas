<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

$config = require __DIR__.'/bootstrap.php';

use Distributed\Appointment\AppointmentServiceStub;
use Distributed\Dto\RemoteAppointment;
use Distributed\Registry\RegistryClient;

$registryClient = new RegistryClient(
    $config['registry']['host'],
    $config['registry']['port']
);

// ======================================================================
// GUIA 6 - ACTIVIDAD 3: OPERACION LOOKUP
// El cliente localiza dinamicamente el servicio consultando al registry por nombre.
// ======================================================================
// ======================================================================
// GUIA 5 - ACTIVIDAD 2: IMPLEMENTACION DEL STUB
// El stub encapsula la logica de conexion para que el cliente invoque el servicio como si fuera local.
// ======================================================================
$stub = new AppointmentServiceStub($registryClient, 'appointment_service');

// ======================================================================
// GUIA 5 - ACTIVIDAD 1: CLASES DE NEGOCIO
// Se construye un objeto serializable coherente con el MVP del sistema de citas.
// ======================================================================
$appointment = new RemoteAppointment(
    'Camilo Ramirez',
    'Estetica Viva',
    'Limpieza facial',
    '2026-04-20',
    '09:00'
);

// ======================================================================
// GUIA 5 - ACTIVIDAD 3: MARSHALLING
// El objeto se convierte en payload serializable para su transporte por red dentro del stub.
// ======================================================================
$response = $stub->reserveAppointment($appointment);

// ======================================================================
// GUIA 5 - ACTIVIDAD 5: TRANSPARENCIA
// La invocacion remota se usa desde el cliente como si fuera una llamada local.
// ======================================================================
echo "[CLIENT] Respuesta remota recibida:\n";
print_r($response);
