<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Distributed\Protocol;

use DOMDocument;
use DOMElement;
use DOMXPath;
use RuntimeException;
use XSLTProcessor;

class XmlAppointmentProtocol
{
    // ======================================================================
    // GUIA 8 - ACTIVIDAD 1: DISENO DEL PROTOCOLO DE COMUNICACION
    // Esta clase define la estructura XML de request, response y error del servicio distribuido.
    // ======================================================================
    private const ROOT = 'appointmentMessage';

    public function buildRequest(string $operation, array $payload): string
    {
        return $this->buildDocument('request', $operation, $payload);
    }

    public function buildResponse(string $operation, array $payload): string
    {
        return $this->buildDocument('response', $operation, $payload);
    }

    public function buildError(string $operation, string $message, string $code = 'REMOTE_ERROR'): string
    {
        return $this->buildDocument('error', $operation, [
            'code' => $code,
            'message' => $message,
        ]);
    }

    public function parseRequest(string $xml): array
    {
        $document = $this->loadValidatedDocument($xml);
        $xpath = new DOMXPath($document);

        return [
            'type' => $this->readNodeValue($xpath, '/'.self::ROOT.'/header/type'),
            'operation' => $this->readNodeValue($xpath, '/'.self::ROOT.'/header/operation'),
            'payload' => $this->extractPayload($xpath),
        ];
    }

    public function parseResponse(string $xml): array
    {
        $document = $this->loadValidatedDocument($xml);
        $xpath = new DOMXPath($document);
        $type = $this->readNodeValue($xpath, '/'.self::ROOT.'/header/type');
        $operation = $this->readNodeValue($xpath, '/'.self::ROOT.'/header/operation');
        $payload = $this->extractPayload($xpath);

        return [
            'type' => $type,
            'operation' => $operation,
            'payload' => $payload,
        ];
    }

    public function transformResponse(string $xml, string $stylesheetPath): string
    {
        // ======================================================================
        // GUIA 8 - ACTIVIDAD 5: TRANSFORMACION DE DATOS CON XSLT
        // La respuesta XML puede convertirse a un formato legible sin alterar la informacion original.
        // ======================================================================
        $document = $this->loadValidatedDocument($xml);
        $stylesheet = new DOMDocument();

        if (! @$stylesheet->load($stylesheetPath)) {
            throw new RuntimeException('No fue posible cargar la hoja XSLT.');
        }

        $processor = new XSLTProcessor();
        $processor->importStylesheet($stylesheet);
        $result = $processor->transformToXML($document);

        if ($result === false) {
            throw new RuntimeException('No fue posible transformar la respuesta XML.');
        }

        return $result;
    }

    private function buildDocument(string $type, string $operation, array $payload): string
    {
        // ======================================================================
        // GUIA 8 - ACTIVIDAD 1: DISENO DEL PROTOCOLO DE COMUNICACION
        // El documento XML organiza cabecera, operacion y cuerpo para solicitudes, respuestas y errores.
        // ======================================================================
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;

        $root = $document->createElement(self::ROOT);
        $document->appendChild($root);

        $header = $document->createElement('header');
        $header->appendChild($document->createElement('type', $type));
        $header->appendChild($document->createElement('operation', $operation));
        $root->appendChild($header);

        $body = $document->createElement('body');

        foreach ($payload as $key => $value) {
            $body->appendChild($this->appendPayloadNode($document, (string) $key, $value));
        }

        $root->appendChild($body);

        return $document->saveXML($root) ?: throw new RuntimeException('No fue posible construir el mensaje XML.');
    }

    private function loadValidatedDocument(string $xml): DOMDocument
    {
        // ======================================================================
        // GUIA 8 - ACTIVIDAD 2: VALIDACION ESTRUCTURAL DEL PROTOCOLO
        // Cada mensaje XML se valida contra un esquema comun antes de procesarse.
        // ======================================================================
        $document = new DOMDocument();
        $document->preserveWhiteSpace = false;

        if (! @$document->loadXML($xml)) {
            throw new RuntimeException('El mensaje XML no es valido.');
        }

        $schemaPath = dirname(__DIR__, 2).'/schemas/appointment-protocol.xsd';

        if (! @ $document->schemaValidate($schemaPath)) {
            throw new RuntimeException('El mensaje XML no cumple el esquema del protocolo.');
        }

        return $document;
    }

    private function extractPayload(DOMXPath $xpath): array
    {
        // ======================================================================
        // GUIA 8 - ACTIVIDAD 4: PROCESAMIENTO Y EXTRACCION DE DATOS CON XPATH
        // XPath permite leer operacion y parametros sin acoplar la logica a posiciones manuales.
        // ======================================================================
        $payload = [];
        $nodes = $xpath->query('/'.self::ROOT.'/body/*');

        if ($nodes === false) {
            throw new RuntimeException('No fue posible leer el cuerpo del mensaje XML.');
        }

        foreach ($nodes as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $payload[$node->nodeName] = $this->extractNodeValue($node);
        }

        return $payload;
    }

    private function appendPayloadNode(DOMDocument $document, string $key, mixed $value): DOMElement
    {
        $element = $document->createElement($key);

        if (is_array($value)) {
            foreach ($value as $childKey => $childValue) {
                $element->appendChild(
                    $this->appendPayloadNode($document, is_string($childKey) ? $childKey : 'item', $childValue)
                );
            }

            return $element;
        }

        $element->nodeValue = (string) $value;

        return $element;
    }

    private function extractNodeValue(DOMElement $element): mixed
    {
        $children = [];

        foreach ($element->childNodes as $childNode) {
            if ($childNode instanceof DOMElement) {
                $children[$childNode->nodeName] = $this->extractNodeValue($childNode);
            }
        }

        if ($children !== []) {
            return $children;
        }

        return trim((string) $element->textContent);
    }

    private function readNodeValue(DOMXPath $xpath, string $expression): string
    {
        $nodes = $xpath->query($expression);

        if ($nodes === false || $nodes->length === 0) {
            throw new RuntimeException('El mensaje XML no contiene la estructura esperada.');
        }

        return trim((string) $nodes->item(0)?->textContent);
    }
}
