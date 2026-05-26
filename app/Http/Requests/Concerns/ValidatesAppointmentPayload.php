<?php

namespace App\Http\Requests\Concerns;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Service;
use App\Services\AppointmentAvailabilityService;
use Illuminate\Validation\Validator;

trait ValidatesAppointmentPayload
{
    protected function appointmentRules(): array
    {
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

    protected function prepareAppointmentForValidation(): void
    {
        $availability = app(AppointmentAvailabilityService::class);
        $normalizedStartTime = $this->filled('start_time')
            ? $availability->normalizeTime((string) $this->input('start_time'))
            : null;

        $service = Service::query()->find($this->input('service_id'));

        if ($normalizedStartTime !== null) {
            $this->merge([
                'start_time' => $normalizedStartTime,
            ]);
        }

        if ($service && $normalizedStartTime && $this->hasValidTimeShape($normalizedStartTime)) {
            $this->merge([
                'end_time' => $availability->calculateEndTime($service, $normalizedStartTime),
            ]);
        }
    }

    protected function canScheduleForRequestedBusiness(): bool
    {
        $user = $this->user();

        if ($user === null) {
            return false;
        }

        if ($user->isClient() || $user->isAdmin()) {
            return true;
        }

        $businessId = $this->input('business_id');

        if ($businessId === null || $businessId === '') {
            return true;
        }

        return $user->businesses()->whereKey($businessId)->exists();
    }

    protected function validateAppointmentPayload(Validator $validator, ?int $ignoreAppointmentId = null): void
    {
        $validator->after(function (Validator $validator) use ($ignoreAppointmentId) {
            if ($validator->errors()->any()) {
                return;
            }

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
            $appointmentDate = (string) $this->input('appointment_date');
            $startTime = (string) $this->input('start_time');
            $endTime = (string) $this->input('end_time');

            if ($availability->isHoliday($appointmentDate)) {
                $validator->errors()->add('appointment_date', 'No se pueden agendar citas en un dia festivo sincronizado.');
            } elseif (! $availability->isWithinBusinessHours(
                $business,
                $appointmentDate,
                $startTime,
                $endTime,
                false
            )) {
                $validator->errors()->add('start_time', 'La cita esta fuera del horario configurado del negocio.');
            }

            if ($availability->hasOverlap(
                $business,
                $appointmentDate,
                $startTime,
                $endTime,
                $ignoreAppointmentId
            )) {
                $validator->errors()->add('start_time', 'Ya existe una cita en ese rango de tiempo.');
            }
        });
    }

    private function hasValidTimeShape(string $time): bool
    {
        return preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $time) === 1;
    }
}
