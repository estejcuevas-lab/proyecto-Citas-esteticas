# Modulo Cliente-Servidor Distribuido

Este modulo implementa una version minima de:

- `Registry`
- `Bind`
- `Lookup`
- `Stub`
- `Marshalling`
- `Unmarshalling`
- `Transparencia de invocacion remota`

Y ahora ademas incorpora la base de la Guia 8:

- `Protocolo XML para request, response y error`
- `Validacion estructural con XSD`
- `Extraccion de datos con XPath`
- `Transformacion de respuestas con XSLT`

## Ejecutar

Abre tres terminales en la raiz del proyecto:

```powershell
php .\distributed\registry.php
```

```powershell
php .\distributed\server.php
```

```powershell
php .\distributed\client.php
```

## Flujo

1. `registry.php` arranca el directorio de servicios.
2. `server.php` registra `appointment_service` usando `BIND`.
3. `client.php` consulta el `registry` usando `LOOKUP`.
4. El `stub` construye un mensaje XML de solicitud y lo envia al servidor.
5. El servidor valida el XML, extrae la operacion y responde tambien en XML.

## Esquema XML

El esquema del protocolo quedo en:

```text
distributed/schemas/appointment-protocol.xsd
```

La implementacion central del protocolo esta en:

```text
distributed/src/Protocol/XmlAppointmentProtocol.php
```

La hoja de transformacion XSLT quedo en:

```text
distributed/xslt/appointment-response.xsl
```

## Inicio de Guia 9

Se dejo publicada la base del contrato SOAP/WSDL en:

```text
distributed/soap/appointment-service.wsdl
distributed/soap-service.php
```

### Probar publicacion del WSDL

Desde la raiz del proyecto:

```powershell
php -S 127.0.0.1:9200 -t distributed
```

Luego abre:

```text
http://127.0.0.1:9200/soap-service.php?wsdl
```

Ese endpoint ya devuelve:

- contrato `WSDL`
- `types`
- `message`
- `portType`
- `binding`
- `service`

Si se hace una llamada SOAP directa sin el manejador final, el script responde con un `SOAP Fault` controlado.

### Cobertura actual de la Guia 9

- `Actividad 1`: contrato WSDL base ya publicado
- `Actividad 2`: respuesta SOAP Fault controlada como base de mensajeria estructurada
- `Actividad 3`: registro y descubrimiento simulado con el registry existente
- `Actividad 4`: comparacion con el modelo anterior, pendiente para informe
