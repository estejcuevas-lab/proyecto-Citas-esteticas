<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Service;
use App\Services\AppointmentAvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function __construct(
        private readonly AppointmentAvailabilityService $availability
    ) {
    }

    public function show(Request $request, Business $business): JsonResponse
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 4: LOGICA DE NODOS
        // El cliente consulta disponibilidad y el servidor procesa la logica antes de responder en JSON.
        // ======================================================================
        // ======================================================================
        // GUIA 3 - ACTIVIDAD 3: CONSUMO DE DATOS
        // Este endpoint responde en JSON para interoperabilidad con clientes externos.
        // ======================================================================
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
        ]);

        $service = Service::query()->findOrFail($validated['service_id']);

        if ((int) $service->business_id !== (int) $business->id) {
            return response()->json([
                'message' => 'El servicio no pertenece al negocio indicado.',
            ], 422);
        }

        $endTime = $this->availability->calculateEndTime($service, $validated['start_time']);

        $withinHours = $this->availability->isWithinBusinessHours(
            $business,
            $validated['appointment_date'],
            $validated['start_time'],
            $endTime
        );

        $hasOverlap = $this->availability->hasOverlap(
            $business,
            $validated['appointment_date'],
            $validated['start_time'],
            $endTime
        );

        return response()->json([
            'data' => [
                'business_id' => $business->id,
                'service_id' => $service->id,
                'appointment_date' => $validated['appointment_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $endTime,
                'within_business_hours' => $withinHours,
                'has_overlap' => $hasOverlap,
                'available' => $withinHours && ! $hasOverlap && $service->active,
                'service_active' => $service->active,
            ],
        ]);
    }
}
