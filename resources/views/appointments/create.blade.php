@extends('layouts.app')

@section('title', 'Crear cita')
@section('hide_errors')
@section('theme_style', $preselectedBusiness ? '--primary-color: '.$preselectedBusiness->brandColor().';' : '')

@section('content')
    <section class="surface">
        <div class="page-topbar">
            <div>
                <h1 class="page-title">Registrar cita</h1>
                <p class="muted mb-0">Duracion, adelanto y validacion de horario, traslapes y festivos.</p>
                @if ($preselectedBusiness)
                    <p class="muted mb-0 mt-2">Reservando en: <strong>{{ $preselectedBusiness->name }}</strong></p>
                @endif
            </div>
            <div class="button-row">
                @if ($preselectedBusiness)
                    <a class="button secondary" href="{{ route('public.businesses.show', $preselectedBusiness->slug) }}">Volver al negocio</a>
                @endif
                <a class="button secondary" href="{{ route('appointments.index') }}">Volver al listado</a>
            </div>
        </div>

        <div class="form-layout">
            <article class="form-card">
                <form method="POST" action="{{ route('appointments.store') }}">
                    @csrf
                    <div class="grid">
                        <div class="field full">
                            <label for="business_id">Negocio</label>
                            <select
                                id="business_id"
                                name="business_id"
                                @disabled($lockBusinessSelection)
                                required
                            >
                                <option value="">Selecciona un negocio</option>
                                @foreach ($businesses as $business)
                                    <option
                                        value="{{ $business->id }}"
                                        @selected(old('business_id', $preselectedBusiness?->id) == $business->id)
                                    >
                                        {{ $business->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($lockBusinessSelection)
                                <input type="hidden" name="business_id" value="{{ $preselectedBusiness->id }}">
                                <p class="hint">Esta cita queda bloqueada al negocio desde el cual iniciaste la reserva.</p>
                            @endif
                        </div>
                        <div class="field full">
                            <div class="schedule" id="business-schedule">Selecciona un negocio para ver sus horarios disponibles.</div>
                        </div>
                        <div class="field full">
                            <label for="service_id">Servicio</label>
                            <select id="service_id" name="service_id" required>
                                <option value="">Selecciona un servicio</option>
                                @foreach ($services as $service)
                                    <option
                                        value="{{ $service->id }}"
                                        data-business-id="{{ $service->business_id }}"
                                        data-duration="{{ $service->duration_minutes }}"
                                        data-price="{{ $service->price }}"
                                        @selected(old('service_id') == $service->id)
                                    >
                                        {{ $service->name }} - {{ $service->business->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="hint">Solo se mostraran servicios del negocio elegido.</p>
                        </div>
                        <div class="field">
                            <label for="appointment_date">Fecha</label>
                            <input id="appointment_date" name="appointment_date" type="date" value="{{ old('appointment_date') }}" required>
                        </div>
                        <div class="field">
                            <label for="start_time">Hora de inicio</label>
                            <input id="start_time" name="start_time" type="time" value="{{ old('start_time') }}" required>
                        </div>
                        <div class="field">
                            @if ($user->isClient())
                                <label>Estado inicial</label>
                                <input type="text" value="pending" disabled>
                                <input type="hidden" name="status" value="{{ old('status', 'pending') }}">
                                <p class="hint">La cita se confirmara al completar el anticipo.</p>
                            @else
                                <label for="status">Estado</label>
                                <select id="status" name="status" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected(old('status', 'confirmed') === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="field">
                            @if ($user->isClient())
                                <label>Anticipo</label>
                                <input type="text" value="pending_advance" disabled>
                                <input type="hidden" name="payment_status" value="{{ old('payment_status', 'pending_advance') }}">
                                <p class="hint">Despues de guardar, simulacion de pago del 50%.</p>
                            @else
                                <label for="payment_status">Estado del pago</label>
                                <select id="payment_status" name="payment_status">
                                    @foreach ($paymentStatuses as $paymentStatus)
                                        <option value="{{ $paymentStatus }}" @selected(old('payment_status', 'pending_advance') === $paymentStatus)>{{ $paymentStatus }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="field full">
                            <label for="notes">Notas</label>
                            <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="summary" id="appointment-summary">La hora de finalizacion se calcula segun la duracion del servicio.</div>

                    @if ($errors->any())
                        <div class="error-list">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="button-row">
                        <button type="submit">Guardar cita</button>
                        <a class="button secondary" href="{{ route('appointments.index') }}">Cancelar</a>
                    </div>
                </form>
            </article>

            <aside class="info-card">
                <h2 class="section-title text-xl">Resumen del flujo</h2>
                <div class="note">
                    <strong>Validaciones activas</strong>
                    <p class="muted mb-0">Horario, servicio activo, conflictos y festivos sincronizados.</p>
                </div>
                <div class="note">
                    <strong>Calculo automatico</strong>
                    <p class="muted mb-0">Hora final y adelanto segun duracion y precio.</p>
                </div>
                @if ($user->isClient())
                    <div class="note">
                        <strong>Pago posterior</strong>
                        <p class="muted mb-0">Simulacion Nequi, Bancolombia o tarjeta tras guardar.</p>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
    @include('appointments.partials.scheduling-script')
@endpush
