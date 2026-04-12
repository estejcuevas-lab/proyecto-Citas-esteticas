<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace App\Http\Requests;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Service;
use App\Services\AppointmentAvailabilityService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 5: DISENO DE PAYLOAD
        // La trama de datos de actualizacion conserva tipos y formato esperados para la cita.
        // ======================================================================
        return [
            'business_id' => ['required', 'exists:businesses,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'status' => ['required', 'in:'.implode(',', Appointment::statuses())],
            'payment_status' => ['nullable', 'in:'.implode(',', Appointment::paymentStatuses())],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // ======================================================================
        // GUIA 4 - ACTIVIDAD 3: CAPA DE VALIDACION
        // La validacion prepara los datos antes de que pasen a la logica principal de agenda.
        // ======================================================================
        $service = Service::query()->find($this->input('service_id'));

        if ($service && $this->filled('start_time')) {
            $endTime = app(AppointmentAvailabilityService::class)
                ->calculateEndTime($service, $this->input('start_time'));

            $this->merge([
                'end_time' => $endTime,
            ]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // ======================================================================
            // GUIA 4 - ACTIVIDAD 2: GESTION DE PAYLOAD
            // El flujo de lectura y ajuste del payload asegura consistencia antes de actualizar la reserva.
            // ======================================================================
            // ======================================================================
            // GUIA 4 - ACTIVIDAD 3: CAPA DE VALIDACION
            // Aqui se aplican controles de seguridad e integridad para las entradas del usuario.
            // ======================================================================
            $service = Service::query()->find($this->input('service_id'));
            $business = Business::query()->find($this->input('business_id'));

            if (! $service || ! $business) {
                return;
            }

            if ((int) $service->business_id !== (int) $this->input('business_id')) {
                $validator->errors()->add('service_id', 'El servicio seleccionado no pertenece al negocio.');
            }

            if (! $service->active) {
                $validator->errors()->add('service_id', 'El servicio seleccionado no esta activo.');
            }

            $availability = app(AppointmentAvailabilityService::class);

            if ($availability->isHoliday($this->input('appointment_date'))) {
                $validator->errors()->add('appointment_date', 'No se pueden agendar citas en un dia festivo sincronizado.');
            } elseif (! $availability->isWithinBusinessHours(
                $business,
                $this->input('appointment_date'),
                $this->input('start_time'),
                $this->input('end_time')
            )) {
                $validator->errors()->add('start_time', 'La cita esta fuera del horario configurado del negocio.');
            }

            if ($availability->hasOverlap(
                $business,
                $this->input('appointment_date'),
                $this->input('start_time'),
                $this->input('end_time'),
                $this->route('appointment')->id
            )) {
                $validator->errors()->add('start_time', 'Ya existe una cita en ese rango de tiempo.');
            }
        });
    }
}
