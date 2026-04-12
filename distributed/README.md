# Modulo Cliente-Servidor Distribuido

Este modulo implementa una version minima de:

- `Registry`
- `Bind`
- `Lookup`
- `Stub`
- `Marshalling`
- `Unmarshalling`
- `Transparencia de invocacion remota`

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
4. El `stub` serializa la solicitud y la envia al servidor.
5. El servidor reconstruye el objeto y responde.
