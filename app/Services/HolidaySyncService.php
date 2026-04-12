<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Services;

use App\Data\HolidayRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class HolidaySyncService
{
    // ======================================================================
    // GUIA 5 - ACTIVIDAD 4: ENCAPSULAMIENTO AVANZADO
    // La dependencia se encapsula con visibilidad privada y solo se expone la operacion necesaria del servicio.
    // ======================================================================
    public function __construct(
        private readonly HolidayRepository $holidayRepository
    ) {
    }

    public function sync(int $year, string $countryCode = 'CO'): array
    {
        // ======================================================================
        // GUIA 7 - ACTIVIDAD 1: INTEROPERABILIDAD WEB
        // Este servicio consume un servicio web externo para integrar informacion al sistema.
        // ======================================================================
        try {
            $response = Http::baseUrl('https://date.nager.at/api/v3')
                ->timeout(10)
                ->acceptJson()
                ->get("/PublicHolidays/{$year}/{$countryCode}");
        } catch (ConnectionException $exception) {
            // ======================================================================
            // GUIA 7 - ACTIVIDAD 3: TRATAMIENTO DE ERRORES
            // Se captura la falla de conexion y se transforma en un error controlado para la aplicacion.
            // ======================================================================
            throw new RuntimeException('No fue posible conectar con el servicio externo de festivos.', 0, $exception);
        }

        if ($response->failed()) {
            // ======================================================================
            // GUIA 7 - ACTIVIDAD 3: TRATAMIENTO DE ERRORES
            // Se valida la respuesta externa para evitar persistir datos invalidos o incompletos.
            // ======================================================================
            throw new RuntimeException('El servicio externo de festivos respondio con un error.');
        }

        $holidays = collect($response->json())
            ->map(fn (array $holiday) => [
                'holiday_date' => $holiday['date'],
                'name' => $holiday['localName'] ?? $holiday['name'],
                'country_code' => $countryCode,
                'source' => 'nager_date',
            ]);

        // ======================================================================
        // GUIA 7 - ACTIVIDAD 2: SINCRONIZACION DE DATOS
        // El servicio transforma el JSON recibido y delega su persistencia a la capa de acceso.
        // ======================================================================
        $this->holidayRepository->syncMany($holidays);

        return [
            'count' => $holidays->count(),
            'year' => $year,
            'country_code' => $countryCode,
        ];
    }
}
