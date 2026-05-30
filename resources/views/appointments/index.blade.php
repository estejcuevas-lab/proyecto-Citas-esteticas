@extends('layouts.app')

@section('title', 'Citas')

@section('content')
    <section class="surface">
        <div class="page-topbar">
            <div>
                <h1 class="page-title">Agenda de citas</h1>
                <p class="muted mb-0">Reservas, pagos, estados y acciones rapidas.</p>
            </div>
            <div class="button-row">
                <a class="button secondary" href="{{ route('dashboard') }}">Volver al panel</a>
                <a class="button primary" href="{{ route('appointments.create') }}">Nueva cita</a>
            </div>
        </div>

        <div class="module-hero">
            <article class="module-hero-card">
                <p class="m-0 text-xs font-bold tracking-widest uppercase opacity-90">Vista operativa</p>
                <p class="muted mt-3 mb-0">
                    Cada tarjeta resume servicio, negocio, horario, pago y estado. Las acciones respetan el flujo de reserva.
                </p>
            </article>
            <aside class="module-aside-card">
                <strong class="text-[var(--primary-color-deep)]">Total visible</strong>
                <p class="muted mt-2 mb-0">{{ $appointments->count() }} citas para tu perfil.</p>
            </aside>
        </div>

        <div class="list">
            @forelse ($appointments as $appointment)
                <article class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="section-title text-xl">{{ $appointment->service->name }}</h2>
                            <p class="muted m-0">
                                {{ $appointment->business->name }}
                                @if ($user->isAdmin() || $user->isBusiness())
                                    · Cliente: {{ $appointment->user->name }}
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="pill {{ $appointment->status }}">{{ str_replace('_', ' ', $appointment->status) }}</span>
                            <span class="pill {{ $appointment->payment_status }}">{{ str_replace('_', ' ', $appointment->payment_status) }}</span>
                        </div>
                    </div>

                    <div class="info-grid mt-4">
                        <div class="info-box"><span>Fecha</span>{{ $appointment->appointment_date }}</div>
                        <div class="info-box"><span>Horario</span>{{ $appointment->start_time }} - {{ $appointment->end_time }}</div>
                        <div class="info-box"><span>Precio</span>${{ number_format((float) $appointment->service_price, 2) }}</div>
                        <div class="info-box"><span>Adelanto</span>${{ number_format((float) $appointment->advance_amount, 2) }}</div>
                        <div class="info-box"><span>Porcentaje</span>{{ number_format((float) $appointment->advance_percentage, 0) }}%</div>
                        <div class="info-box"><span>Notas</span>{{ $appointment->notes ?: 'Sin notas' }}</div>
                    </div>

                    <div class="actions">
                        <a class="button ghost" href="{{ route('appointments.edit', $appointment) }}">Editar</a>
                        @if ($appointment->user_id === $user->id && $appointment->payment_status !== 'paid' && ! in_array($appointment->status, ['cancelled', 'completed']))
                            <a class="button info" href="{{ route('appointments.payment.show', $appointment) }}">Pagar anticipo</a>
                        @endif
                        @if (($user->isAdmin() || $user->isBusiness()) && $appointment->status === 'pending')
                            <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button class="confirm" type="submit">Confirmar</button>
                            </form>
                        @endif
                        @if (($user->isAdmin() || $user->isBusiness()) && $appointment->status === 'confirmed')
                            <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button class="confirm" type="submit">Completar</button>
                            </form>
                        @endif
                        @if (($user->isAdmin() || $user->isBusiness()) && $appointment->payment_status !== 'paid')
                            <form method="POST" action="{{ route('appointments.payment', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="payment_status" value="paid">
                                <button class="info" type="submit">Marcar pago</button>
                            </form>
                        @endif
                        @if ((($user->isAdmin() || $user->isBusiness()) || $appointment->user_id === $user->id) && ! in_array($appointment->status, ['cancelled', 'completed']))
                            <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button class="warn" type="submit">Cancelar</button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <article class="empty-state">
                    <h2 class="section-title text-xl">Sin citas registradas</h2>
                    <p class="muted mb-0">Cuando se creen reservas, apareceran aqui.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection
