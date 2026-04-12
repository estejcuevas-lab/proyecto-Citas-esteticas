<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace App\Http\Controllers\Api;

use App\Data\HolidayRepository;
use App\Http\Controllers\Controller;
use App\Services\HolidaySyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class HolidayController extends Controller
{
    // ======================================================================
    // GUIA 7 - ACTIVIDAD 1: INTEROPERABILIDAD WEB
    // Este endpoint expone festivos en JSON para clientes HTTP que consultan informacion externa procesada por el sistema.
    // ======================================================================
    public function index(Request $request, HolidayRepository $holidayRepository): JsonResponse
    {
        $year = $request->integer('year');

        $holidays = $holidayRepository->allForYear($year);

        return response()->json($holidays);
    }

    public function sync(Request $request, HolidaySyncService $holidaySyncService): JsonResponse
    {
        abort_unless(
            $request->user()?->isAdmin() || $request->user()?->isBusiness(),
            403
        );

        try {
            $result = $holidaySyncService->sync(
                (int) $request->integer('year', now()->year),
                (string) $request->string('country_code', 'CO')
            );
        } catch (RuntimeException $exception) {
            // ======================================================================
            // GUIA 7 - ACTIVIDAD 3: TRATAMIENTO DE ERRORES
            // El controlador devuelve un error controlado cuando el servicio externo falla.
            // ======================================================================
            return response()->json([
                'message' => $exception->getMessage(),
            ], 502);
        }

        return response()->json([
            'message' => 'Festivos sincronizados correctamente.',
            'data' => $result,
        ]);
    }
}
