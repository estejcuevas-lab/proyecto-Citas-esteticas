<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'business_id' => ['required', 'exists:businesses,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'status' => ['required', 'in:'.implode(',', Appointment::statuses())],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $service = Service::query()->find($this->input('service_id'));

            if ($service && (int) $service->business_id !== (int) $this->input('business_id')) {
                $validator->errors()->add('service_id', 'El servicio seleccionado no pertenece al negocio.');
            }
        });
    }
}
