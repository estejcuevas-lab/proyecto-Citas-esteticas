<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesAppointmentPayload;
use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateAppointmentRequest extends FormRequest
{
    use ValidatesAppointmentPayload;

    public function authorize(): bool
    {
        $user = $this->user();

        if ($user === null) {
            return false;
        }

        $appointment = $this->route('appointment');

        if ($appointment instanceof Appointment) {
            $ownsAppointment = (int) $appointment->user_id === (int) $user->id;
            $ownsBusiness = $user->isAdmin()
                || $user->businesses()->whereKey($appointment->business_id)->exists();

            if (! $ownsAppointment && ! $ownsBusiness) {
                return false;
            }
        }

        return $this->canScheduleForRequestedBusiness();
    }

    public function rules(): array
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 5: DISENO DE PAYLOAD
        // La trama de datos de actualizacion conserva tipos y formato esperados para la cita.
        // ======================================================================
        return $this->appointmentRules();
    }

    protected function prepareForValidation(): void
    {
        // ======================================================================
        // GUIA 4 - ACTIVIDAD 3: CAPA DE VALIDACION
        // La validacion prepara los datos antes de que pasen a la logica principal de agenda.
        // ======================================================================
        $this->prepareAppointmentForValidation();
    }

    public function withValidator(Validator $validator): void
    {
        // ======================================================================
        // GUIA 4 - ACTIVIDAD 2: GESTION DE PAYLOAD
        // El flujo de lectura y ajuste del payload asegura consistencia antes de actualizar la reserva.
        // ======================================================================
        // ======================================================================
        // GUIA 4 - ACTIVIDAD 3: CAPA DE VALIDACION
        // Aqui se aplican controles de seguridad e integridad para las entradas del usuario.
        // ======================================================================
        $appointment = $this->route('appointment');

        $this->validateAppointmentPayload(
            $validator,
            $appointment instanceof Appointment ? $appointment->id : null
        );
    }
}
