<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

$config = require __DIR__.'/bootstrap.php';

use Distributed\Appointment\AppointmentServiceStub;
use Distributed\Dto\RemoteAppointment;
use Distributed\Protocol\XmlAppointmentProtocol;
use Distributed\Registry\RegistryClient;

$registryClient = new RegistryClient(
    $config['registry']['host'],
    $config['registry']['port']
);
$protocol = new XmlAppointmentProtocol();

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
// GUIA 8 - ACTIVIDAD 3: INTEGRACION DEL PROTOCOLO XML EN EL SISTEMA
// El cliente construye y envia correctamente el mensaje XML definido para la solicitud remota.
// ======================================================================
// ======================================================================
// GUIA 5 - ACTIVIDAD 3: MARSHALLING
// El objeto se convierte en payload serializable para su transporte por red dentro del stub.
// ======================================================================
$response = $stub->reserveAppointmentDetailed($appointment);

// ======================================================================
// GUIA 8 - ACTIVIDAD 5: TRANSFORMACION DE DATOS CON XSLT
// El cliente aplica una hoja XSLT para presentar la respuesta XML en un formato mas legible.
// ======================================================================
$transformedResponse = $protocol->transformResponse(
    $response['xml'],
    __DIR__.'/xslt/appointment-response.xsl'
);

// ======================================================================
// GUIA 5 - ACTIVIDAD 5: TRANSPARENCIA
// La invocacion remota se usa desde el cliente como si fuera una llamada local.
// ======================================================================
echo "[CLIENT] Respuesta remota recibida:\n";
print_r($response['payload']);

echo "\n[CLIENT] Respuesta XML:\n";
echo $response['xml']."\n";

echo "\n[CLIENT] Respuesta transformada con XSLT:\n";
echo $transformedResponse."\n";
