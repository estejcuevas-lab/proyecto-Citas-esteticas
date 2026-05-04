<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

declare(strict_types=1);

$wsdlPath = __DIR__.'/soap/appointment-service.wsdl';

// ======================================================================
// GUIA 9 - ACTIVIDAD 1: DEFINICION DEL CONTRATO DE SERVICIO (WSDL)
// Este publicador expone el contrato WSDL por URL para que cualquier cliente conozca operaciones y tipos.
// ======================================================================
if (isset($_GET['wsdl'])) {
    header('Content-Type: text/xml; charset=UTF-8');
    readfile($wsdlPath);

    return;
}

// ======================================================================
// GUIA 9 - ACTIVIDAD 2: IMPLEMENTACION DE MENSAJERIA ESTRUCTURADA (SOAP)
// Mientras se completa el servidor SOAP, el endpoint responde con un SOAP Fault estandarizado.
// ======================================================================
// ======================================================================
// GUIA 9 - ACTIVIDAD 2: IMPLEMENTACION DE MENSAJERIA ESTRUCTURADA (SOAP)
// El Fault conserva una estructura formal de error para clientes SOAP ante operaciones no disponibles.
// ======================================================================
http_response_code(500);
header('Content-Type: text/xml; charset=UTF-8');

echo <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <soap:Fault>
            <faultcode>soap:Server</faultcode>
            <faultstring>El servicio SOAP aun no ha sido habilitado completamente en este entorno.</faultstring>
            <detail>
                <serviceFault xmlns="http://citas-app.local/soap/appointment-service">
                    <fault>
                        <code>SOAP_NOT_READY</code>
                        <message>El contrato WSDL ya esta publicado, pero falta conectar el manejador SOAP definitivo.</message>
                    </fault>
                </serviceFault>
            </detail>
        </soap:Fault>
    </soap:Body>
</soap:Envelope>
XML;
