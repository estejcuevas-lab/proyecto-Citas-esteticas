<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\BusinessHour;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $appointments = Appointment::query()
                ->with(['user', 'business', 'service'])
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        } elseif ($user->isBusiness()) {
            $businessIds = $user->businesses()->pluck('id');

            $appointments = Appointment::query()
                ->with(['user', 'business', 'service'])
                ->whereIn('business_id', $businessIds)
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

        return view('appointments.index', [
            'appointments' => $appointments,
            'user' => $user,
        ]);
    }

    public function create(Request $request): View
    {
        return view('appointments.create', [
            'businesses' => Business::query()
                ->with(['hours' => fn ($query) => $query->orderBy('day_of_week')])
                ->orderBy('name')
                ->get(),
            'services' => Service::query()
                ->where('active', true)
                ->with('business')
                ->orderBy('name')
                ->get(),
            'dayOptions' => BusinessHour::dayOptions(),
            'statuses' => Appointment::statuses(),
            'user' => $request->user(),
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $appointment = $request->user()->appointments()->create($request->validated());

        return redirect()
            ->route('appointments.edit', $appointment)
            ->with('status', 'Cita registrada correctamente.');
    }

    public function edit(Request $request, Appointment $appointment): View
    {
        $this->ensureAppointmentAccess($request, $appointment);

        return view('appointments.edit', [
            'appointment' => $appointment,
            'businesses' => Business::query()
                ->with(['hours' => fn ($query) => $query->orderBy('day_of_week')])
                ->orderBy('name')
                ->get(),
            'services' => Service::query()
                ->where('active', true)
                ->with('business')
                ->orderBy('name')
                ->get(),
            'dayOptions' => BusinessHour::dayOptions(),
            'statuses' => Appointment::statuses(),
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->ensureAppointmentAccess($request, $appointment);

        $appointment->update($request->validated());

        return redirect()
            ->route('appointments.edit', $appointment)
            ->with('status', 'Cita actualizada correctamente.');
    }

    private function ensureAppointmentAccess(Request $request, Appointment $appointment): void
    {
        $user = $request->user();

        $ownsAppointment = $appointment->user_id === $user->id;
        $ownsBusiness = $user->isAdmin() || $user->businesses()->whereKey($appointment->business_id)->exists();

        abort_unless(
            $ownsAppointment || $ownsBusiness,
            403,
            'No puedes gestionar esta cita.'
        );
    }
}
