<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\BusinessHour;
use App\Models\Service;
use App\Services\AppointmentPaymentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        // ======================================================================
        // GUIA 3 - ACTIVIDAD 1: DIAGRAMA DE COMUNICACION
        // El controlador recibe la solicitud del cliente, consulta el modelo y responde con una vista.
        // ======================================================================
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
        $businesses = Business::query()
            ->with(['hours' => fn ($query) => $query->orderBy('day_of_week')])
            ->orderBy('name')
            ->get();

        $dayOptions = BusinessHour::dayOptions();

        return view('appointments.create', [
            'businesses' => $businesses,
            'services' => Service::query()
                ->where('active', true)
                ->with('business')
                ->orderBy('name')
                ->get(),
            'dayOptions' => $dayOptions,
            'schedules' => $this->buildSchedules($businesses, $dayOptions),
            'statuses' => Appointment::statuses(),
            'paymentStatuses' => Appointment::paymentStatuses(),
            'user' => $request->user(),
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated = $this->normalizeAppointmentInput($request, $validated);
        $service = Service::query()->findOrFail($validated['service_id']);
        $paymentData = app(AppointmentPaymentService::class)->buildPaymentData(
            $service,
            $validated['payment_status'] ?? null
        );

        $appointment = $request->user()->appointments()->create([
            ...$validated,
            ...$paymentData,
        ]);

        if ($request->user()->isClient()) {
            return redirect()
                ->route('appointments.payment.show', $appointment)
                ->with('status', 'Cita registrada. Solo falta pagar el anticipo para confirmarla.');
        }

        return redirect()
            ->route('appointments.edit', $appointment)
            ->with('status', 'Cita registrada correctamente.');
    }

    public function edit(Request $request, Appointment $appointment): View
    {
        $this->ensureAppointmentAccess($request, $appointment);

        $businesses = Business::query()
            ->with(['hours' => fn ($query) => $query->orderBy('day_of_week')])
            ->orderBy('name')
            ->get();

        $dayOptions = BusinessHour::dayOptions();

        return view('appointments.edit', [
            'appointment' => $appointment,
            'businesses' => $businesses,
            'services' => Service::query()
                ->where('active', true)
                ->with('business')
                ->orderBy('name')
                ->get(),
            'dayOptions' => $dayOptions,
            'schedules' => $this->buildSchedules($businesses, $dayOptions),
            'statuses' => Appointment::statuses(),
            'paymentStatuses' => Appointment::paymentStatuses(),
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->ensureAppointmentAccess($request, $appointment);
        $this->ensureAppointmentCanBeEdited($request, $appointment);

        $validated = $request->validated();
        $validated = $this->normalizeAppointmentInput($request, $validated, $appointment);
        $service = Service::query()->findOrFail($validated['service_id']);
        $paymentData = app(AppointmentPaymentService::class)->buildPaymentData(
            $service,
            $validated['payment_status'] ?? $appointment->payment_status
        );

        $appointment->update([
            ...$validated,
            ...$paymentData,
        ]);

        return redirect()
            ->route('appointments.edit', $appointment)
            ->with('status', 'Cita actualizada correctamente.');
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->ensureAppointmentAccess($request, $appointment);

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', Appointment::statuses())],
        ]);

        $newStatus = $validated['status'];
        $user = $request->user();

        if ($user->isClient()) {
            abort_unless(
                $appointment->user_id === $user->id && $newStatus === Appointment::STATUS_CANCELLED,
                403,
                'Como cliente solo puedes cancelar tus propias citas.'
            );
        }

        if (($user->isAdmin() || $user->isBusiness()) && $newStatus === Appointment::STATUS_COMPLETED) {
            abort_unless(
                $appointment->status === Appointment::STATUS_CONFIRMED,
                422,
                'Solo puedes completar citas que ya esten confirmadas.'
            );
        }

        if ($appointment->isClosed() && $newStatus !== $appointment->status) {
            abort(422, 'No puedes cambiar el estado de una cita cerrada.');
        }

        $appointment->update([
            'status' => $newStatus,
        ]);

        return back()->with('status', 'Estado de la cita actualizado correctamente.');
    }

    public function updatePaymentStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->ensureAppointmentAccess($request, $appointment);

        abort_unless(
            $request->user()->isAdmin() || $request->user()->isBusiness(),
            403,
            'Solo el negocio o el administrador pueden actualizar pagos.'
        );

        $validated = $request->validate([
            'payment_status' => ['required', 'in:'.implode(',', Appointment::paymentStatuses())],
        ]);

        $appointment->update([
            'payment_status' => $validated['payment_status'],
        ]);

        return back()->with('status', 'Estado del pago actualizado correctamente.');
    }

    public function showPayment(Request $request, Appointment $appointment): View
    {
        $this->ensureAppointmentAccess($request, $appointment);

        return view('appointments.payment', [
            'appointment' => $appointment->load(['business', 'service', 'user']),
            'user' => $request->user(),
            'paymentMethods' => $this->paymentMethods(),
        ]);
    }

    public function processPayment(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->ensureAppointmentAccess($request, $appointment);

        abort_unless(
            $appointment->user_id === $request->user()->id || $request->user()->isAdmin(),
            403,
            'Solo el cliente titular o el administrador pueden procesar este anticipo.'
        );

        abort_if(
            $appointment->isClosed(),
            422,
            'No puedes registrar pagos en una cita cancelada o completada.'
        );

        $validated = $request->validate([
            'payment_method' => ['required', Rule::in(array_keys($this->paymentMethods()))],
            'account_holder' => ['required', 'string', 'max:120'],
            'reference' => ['required', 'string', 'max:60'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'account_number' => ['nullable', 'string', 'max:30'],
            'card_number' => ['nullable', 'string', 'max:25'],
            'card_name' => ['nullable', 'string', 'max:120'],
            'expiry_date' => ['nullable', 'string', 'max:10'],
            'cvv' => ['nullable', 'string', 'max:4'],
        ]);

        $this->validatePaymentMethodPayload($request, $validated['payment_method']);

        $appointment->update([
            'payment_status' => Appointment::PAYMENT_STATUS_PAID,
            'status' => Appointment::STATUS_CONFIRMED,
            'notes' => $this->appendPaymentNote($appointment->notes, $validated['payment_method'], $validated['reference']),
        ]);

        return redirect()
            ->route('appointments.edit', $appointment)
            ->with('status', 'Pago simulado correctamente. La cita ya quedo confirmada.');
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

    private function ensureAppointmentCanBeEdited(Request $request, Appointment $appointment): void
    {
        if ($appointment->isClosed()) {
            throw new HttpResponseException(
                back()->withErrors([
                    'status' => 'Las citas canceladas o completadas ya no se pueden editar.',
                ])->redirectTo(route('appointments.edit', $appointment))
            );
        }

        if ($request->user()->isClient()) {
            abort_unless(
                $appointment->user_id === $request->user()->id,
                403,
                'No puedes editar una cita que no te pertenece.'
            );
        }
    }

    private function normalizeAppointmentInput(Request $request, array $validated, ?Appointment $appointment = null): array
    {
        if ($request->user()->isClient()) {
            $validated['status'] = $appointment?->status === Appointment::STATUS_CONFIRMED
                ? Appointment::STATUS_CONFIRMED
                : Appointment::STATUS_PENDING;

            $validated['payment_status'] = $appointment?->payment_status
                ?? Appointment::PAYMENT_STATUS_PENDING_ADVANCE;
        }

        return $validated;
    }

    private function paymentMethods(): array
    {
        return [
            'nequi' => 'Transferencia por Nequi',
            'bancolombia_transfer' => 'Transferencia Bancolombia',
            'credit_card' => 'Tarjeta de credito',
            'debit_card' => 'Tarjeta debito',
        ];
    }

    private function validatePaymentMethodPayload(Request $request, string $paymentMethod): void
    {
        $fieldsByMethod = [
            'nequi' => ['phone_number'],
            'bancolombia_transfer' => ['account_number'],
            'credit_card' => ['card_number', 'card_name', 'expiry_date', 'cvv'],
            'debit_card' => ['card_number', 'card_name', 'expiry_date', 'cvv'],
        ];

        foreach ($fieldsByMethod[$paymentMethod] ?? [] as $field) {
            if (! $request->filled($field)) {
                throw new HttpResponseException(
                    back()->withErrors([
                        $field => 'Completa los datos del metodo de pago seleccionado.',
                    ])->withInput()
                );
            }
        }
    }

    private function appendPaymentNote(?string $notes, string $paymentMethod, string $reference): string
    {
        $baseNotes = trim((string) $notes);
        $paymentNote = sprintf(
            'Anticipo simulado por %s. Referencia: %s.',
            $this->paymentMethods()[$paymentMethod] ?? $paymentMethod,
            $reference
        );

        return $baseNotes === ''
            ? $paymentNote
            : $baseNotes.' '.$paymentNote;
    }

    private function buildSchedules($businesses, array $dayOptions): array
    {
        return $businesses->mapWithKeys(function ($business) use ($dayOptions) {
            return [
                $business->id => $business->hours->map(function ($hour) use ($dayOptions) {
                    return [
                        'day' => $dayOptions[$hour->day_of_week] ?? 'Desconocido',
                        'opens_at' => $hour->opens_at,
                        'closes_at' => $hour->closes_at,
                        'is_active' => $hour->is_active,
                    ];
                })->values()->all(),
            ];
        })->all();
    }
}
