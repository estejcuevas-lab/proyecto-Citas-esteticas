@extends('layouts.app')

@section('title', 'Editar cita')
@section('hide_errors')

@section('content')
    <section class="surface">
        <div class="page-topbar">
            <div>
                <h1 class="page-title">Editar cita</h1>
                <p class="muted mb-0">Actualiza reserva, negocio, servicio, horario y pago.</p>
            </div>
            <a class="button secondary" href="{{ route('appointments.index') }}">Volver al listado</a>
        </div>

        <div class="form-layout">
            <article class="form-card">
                <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                    @csrf
                    @method('PUT')
                    <div class="grid">
                        <div class="field full">
                            <label for="business_id">Negocio</label>
                            <select id="business_id" name="business_id" required>
                                @foreach ($businesses as $business)
                                    <option value="{{ $business->id }}" @selected(old('business_id', $appointment->business_id) == $business->id)>{{ $business->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field full">
                            <div class="schedule" id="business-schedule">Selecciona un negocio para ver sus horarios.</div>
                        </div>
                        <div class="field full">
                            <label for="service_id">Servicio</label>
                            <select id="service_id" name="service_id" required>
                                @foreach ($services as $service)
                                    <option
                                        value="{{ $service->id }}"
                                        data-business-id="{{ $service->business_id }}"
                                        data-duration="{{ $service->duration_minutes }}"
                                        data-price="{{ $service->price }}"
                                        @selected(old('service_id', $appointment->service_id) == $service->id)
                                    >
                                        {{ $service->name }} - {{ $service->business->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="hint">Solo servicios del negocio elegido.</p>
                        </div>
                        <div class="field">
                            <label for="appointment_date">Fecha</label>
                            <input id="appointment_date" name="appointment_date" type="date" value="{{ old('appointment_date', $appointment->appointment_date?->format('Y-m-d')) }}" required>
                        </div>
                        <div class="field">
                            <label for="start_time">Hora de inicio</label>
                            <input id="start_time" name="start_time" type="time" value="{{ old('start_time', $appointment->start_time) }}" required>
                        </div>
                        <div class="field">
                            @if ($user->isClient())
                                <label>Estado actual</label>
                                <input type="text" value="{{ old('status', $appointment->status) }}" disabled>
                                <input type="hidden" name="status" value="{{ old('status', $appointment->status) }}">
                                <p class="hint">Se actualiza al cancelar o pagar anticipo.</p>
                            @else
                                <label for="status">Estado</label>
                                <select id="status" name="status" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected(old('status', $appointment->status) === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="field">
                            @if ($user->isClient())
                                <label>Estado del pago</label>
                                <input type="text" value="{{ old('payment_status', $appointment->payment_status) }}" disabled>
                                <input type="hidden" name="payment_status" value="{{ old('payment_status', $appointment->payment_status) }}">
                                <p class="hint">Paga el anticipo desde el boton inferior si aplica.</p>
                            @else
                                <label for="payment_status">Estado del pago</label>
                                <select id="payment_status" name="payment_status">
                                    @foreach ($paymentStatuses as $paymentStatus)
                                        <option value="{{ $paymentStatus }}" @selected(old('payment_status', $appointment->payment_status) === $paymentStatus)>{{ $paymentStatus }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="field full">
                            <label for="notes">Notas</label>
                            <textarea id="notes" name="notes">{{ old('notes', $appointment->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="summary" id="appointment-summary">Recalculo automatico de hora final.</div>

                    @if ($errors->any())
                        <div class="error-list">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="button-row">
                        <button type="submit">Guardar cambios</button>
                        @if ($user->isClient() && $appointment->payment_status !== 'paid' && ! $appointment->isClosed())
                            <a class="button payment" href="{{ route('appointments.payment.show', $appointment) }}">Pagar anticipo</a>
                        @endif
                        <a class="button secondary" href="{{ route('appointments.index') }}">Cancelar</a>
                    </div>
                </form>
            </article>

            <aside class="info-card">
                <h2 class="section-title text-xl">Estado actual</h2>
                <div class="note">
                    <strong>Servicio</strong>
                    <p class="muted mb-0">{{ $appointment->service->name }} en {{ $appointment->business->name }}.</p>
                </div>
                <div class="note">
                    <strong>Pago</strong>
                    <p class="muted mb-0">Adelanto: ${{ number_format((float) $appointment->advance_amount, 2) }} · {{ $appointment->payment_status }}</p>
                </div>
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
    @include('appointments.partials.scheduling-script-edit')
@endpush
