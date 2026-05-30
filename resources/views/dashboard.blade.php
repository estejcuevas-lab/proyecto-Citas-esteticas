@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="surface">
        <div class="hero-grid">
            <article class="card card-accent">
                <span class="eyebrow">Centro de control</span>
                <h1 class="page-title">{{ $user->name }}</h1>
                <p class="muted">
                    Resumen de citas, accesos rapidos y estado de autenticacion, onboarding y aprobacion del negocio.
                </p>
                <div class="actions mt-5">
                    <span class="pill">Rol: {{ $user->role }}</span>
                    <span class="pill">{{ $user->email }}</span>
                </div>
            </article>

            <aside class="card">
                <h2 class="section-title">Atajos principales</h2>
                <p class="muted mt-3">
                    Entra a tu agenda, explora negocios publicos o administra tu operacion.
                </p>
                <div class="actions mt-5">
                    <a class="btn btn-primary inline-flex items-center gap-2" href="{{ route('appointments.index') }}">
                        <x-heroicon-o-calendar-days class="h-5 w-5" />
                        Ver citas
                    </a>
                    <a class="btn btn-secondary" href="{{ route('public.businesses.index') }}">Ver negocios publicos</a>
                    @if ($user->canManageBusinesses())
                        <a class="btn btn-secondary" href="{{ route('businesses.index') }}">Gestionar negocios</a>
                    @endif
                </div>
            </aside>
        </div>

        <section class="mt-6">
            <h2 class="section-title">Metricas principales</h2>
            <p class="muted mt-1">Lectura rapida antes de entrar a cada modulo.</p>

            <div class="meta-grid mt-4">
                @if ($user->isAdmin() || $user->isBusiness())
                    <article class="meta-box">
                        <span>Negocios</span>
                        <strong class="text-lg">{{ $stats['businesses'] }}</strong>
                        <p class="muted mt-2 mb-0 text-sm">Visibles para tu perfil actual.</p>
                    </article>
                    <article class="meta-box">
                        <span>Servicios</span>
                        <strong class="text-lg">{{ $stats['services'] }}</strong>
                        <p class="muted mt-2 mb-0 text-sm">Oferta activa administrada.</p>
                    </article>
                @endif
                <article class="meta-box">
                    <span>Citas</span>
                    <strong class="text-lg">{{ $stats['appointments'] }}</strong>
                    <p class="muted mt-2 mb-0 text-sm">Reservas disponibles.</p>
                </article>
                <article class="meta-box">
                    <span>Pendientes</span>
                    <strong class="text-lg">{{ $stats['pending_appointments'] }}</strong>
                    <p class="muted mt-2 mb-0 text-sm">Requieren seguimiento.</p>
                </article>
            </div>
        </section>

        <section class="mt-6">
            <h2 class="section-title">Acciones por rol</h2>
            <p class="muted mt-1">Opciones segun tu estado actual.</p>

            <div class="list mt-4">
                @if ($user->isClient() && ! $user->hasPendingBusinessRequest())
                    <article class="card">
                        <strong>Convertir esta cuenta en business</strong>
                        <p class="muted mt-2">Solicita acceso business y espera aprobacion administrativa.</p>
                        <div class="actions mt-4">
                            <a class="btn btn-primary" href="{{ route('business-access.create') }}">Solicitar acceso business</a>
                        </div>
                    </article>
                @endif

                @if ($user->hasPendingBusinessRequest())
                    <article class="card">
                        <strong>Solicitud business en revision</strong>
                        <p class="muted mt-2">Tu cuenta sigue operando como cliente mientras el admin revisa.</p>
                        <div class="actions mt-4">
                            <a class="btn btn-primary" href="{{ route('business-access.pending') }}">Ver estado</a>
                        </div>
                    </article>
                @endif

                @if ($user->canManageBusinesses())
                    <article class="card">
                        <strong>Gestion de negocios</strong>
                        <p class="muted mt-2">Slug, branding, horarios y servicios desde un solo modulo.</p>
                        <div class="actions mt-4">
                            <a class="btn btn-primary" href="{{ route('businesses.index') }}">Abrir modulo</a>
                            <a class="btn btn-secondary" href="{{ route('businesses.create') }}">Nuevo negocio</a>
                        </div>
                    </article>
                @endif

                @if ($user->isAdmin())
                    <article class="card">
                        <strong>Solicitudes business pendientes</strong>
                        @if ($pendingBusinessRequests->isEmpty())
                            <p class="muted mt-3 mb-0">No hay solicitudes pendientes.</p>
                        @else
                            <div class="list mt-4">
                                @foreach ($pendingBusinessRequests as $pendingUser)
                                    <div class="meta-box">
                                        <span>{{ $pendingUser->email }}</span>
                                        <strong>{{ $pendingUser->name }}</strong>
                                        <p class="muted mt-2 mb-0 text-sm">
                                            Solicitado: {{ optional($pendingUser->business_requested_at)->format('Y-m-d H:i') }}
                                        </p>
                                        <form method="POST" action="{{ route('business-access.approve', $pendingUser) }}" class="mt-3">
                                            @csrf
                                            <button class="btn btn-primary" type="submit">Aprobar acceso</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endif

                @if ($user->canManageBusinesses())
                    <article class="card">
                        <strong>Sincronizar festivos</strong>
                        <p class="muted mt-2">Bloquea fechas no laborables en la agenda.</p>
                        <form method="POST" action="{{ route('holidays.sync') }}" class="mt-4">
                            @csrf
                            <button class="btn btn-secondary" type="submit">Sincronizar festivos</button>
                        </form>
                    </article>
                @endif
            </div>
        </section>

        <div class="actions mt-6 justify-end">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-dark" type="submit">Cerrar sesion</button>
            </form>
        </div>
    </section>
@endsection
