<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // ======================================================================
        // GUIA 4 - ACTIVIDAD 2: GESTION DE PAYLOAD
        // El servidor transforma las entidades en tramas JSON claras para consumo del cliente.
        // ======================================================================
        $user = $request->user();

        if ($user->isAdmin()) {
            $appointments = Appointment::query()
                ->with(['user', 'business', 'service'])
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        } elseif ($user->isBusiness()) {
            $appointments = Appointment::query()
                ->with(['user', 'business', 'service'])
                ->whereIn('business_id', $user->businesses()->pluck('id'))
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        } else {
            $appointments = $user->appointments()
                ->with(['business', 'service'])
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        }

        return response()->json([
            'data' => $appointments->map(fn (Appointment $appointment) => $this->transform($appointment)),
        ]);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $appointment = $request->user()->appointments()->create($request->validated());
        $appointment->load(['user', 'business', 'service']);

        // ======================================================================
        // GUIA 1 - ACTIVIDAD 5: DISENO DE PAYLOAD
        // La respuesta JSON devuelve una trama estructurada con los datos principales de la cita.
        // ======================================================================
        return response()->json([
            'message' => 'Cita creada correctamente.',
            'data' => $this->transform($appointment),
        ], 201);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $this->ensureAppointmentAccess($request, $appointment);

        $appointment->update($request->validated());
        $appointment->load(['user', 'business', 'service']);

        return response()->json([
            'message' => 'Cita actualizada correctamente.',
            'data' => $this->transform($appointment),
        ]);
    }

    private function ensureAppointmentAccess(Request $request, Appointment $appointment): void
    {
        $user = $request->user();

        $ownsAppointment = $appointment->user_id === $user->id;
        $ownsBusiness = $user->isAdmin() || $user->businesses()->whereKey($appointment->business_id)->exists();

        abort_unless($ownsAppointment || $ownsBusiness, 403, 'No puedes gestionar esta cita.');
    }

    private function transform(Appointment $appointment): array
    {
        return [
            'id' => $appointment->id,
            'appointment_date' => optional($appointment->appointment_date)->format('Y-m-d'),
            'start_time' => $appointment->start_time,
            'end_time' => $appointment->end_time,
            'status' => $appointment->status,
            'notes' => $appointment->notes,
            'user' => $appointment->relationLoaded('user') && $appointment->user ? [
                'id' => $appointment->user->id,
                'name' => $appointment->user->name,
                'email' => $appointment->user->email,
            ] : null,
            'business' => $appointment->business ? [
                'id' => $appointment->business->id,
                'name' => $appointment->business->name,
                'type' => $appointment->business->type,
            ] : null,
            'service' => $appointment->service ? [
                'id' => $appointment->service->id,
                'name' => $appointment->service->name,
                'duration_minutes' => $appointment->service->duration_minutes,
                'price' => $appointment->service->price,
            ] : null,
        ];
    }
}
