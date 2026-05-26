@extends('layouts.app')

@section('title', $business->name)
@section('theme_style', '--primary-color: '.$business->brandColor().'; --primary-color-deep: #6a2d1e; --primary-soft: #f1e3d7;')

@section('content')
    <section class="surface">
        <div class="hero-grid">
            <article class="card card-accent">
                <span class="eyebrow">{{ $business->type }}</span>
                <h1 class="page-title">{{ $business->name }}</h1>
                <p class="muted">
                    Una pagina publica simple y tematizada para presentar el negocio, sus servicios activos
                    y sus datos de contacto antes del login.
                </p>

                <div class="actions" style="margin-top: 1.25rem;">
                    <a class="btn btn-secondary" href="{{ route('home') }}">Ingresar para reservar</a>
                    <a class="btn btn-secondary" href="{{ route('public.businesses.index') }}">Volver al catalogo</a>
                </div>
            </article>

            <aside class="card">
                <h2 class="section-title">Contacto</h2>
                <div class="list" style="margin-top: 1rem;">
                    <div class="meta-box"><span>Correo</span>{{ $business->email ?: 'Sin registrar' }}</div>
                    <div class="meta-box"><span>Telefono</span>{{ $business->phone ?: 'Sin registrar' }}</div>
                    <div class="meta-box"><span>Direccion</span>{{ $business->address ?: 'Sin registrar' }}</div>
                </div>
            </aside>
        </div>

        <section class="card" style="margin-top: 1.5rem;">
            <h2 class="section-title">Servicios activos</h2>
            <div class="list" style="margin-top: 1rem;">
                @forelse ($business->services as $service)
                    <article class="meta-box">
                        <span>{{ $service->duration_minutes }} min</span>
                        <strong>{{ $service->name }}</strong>
                        <p class="muted" style="margin: 0.45rem 0 0;">
                            {{ $service->description ?: 'Servicio activo disponible para reserva dentro de la plataforma.' }}
                        </p>
                        <p style="margin: 0.8rem 0 0; font-weight: 700;">${{ number_format((float) $service->price, 2) }}</p>
                    </article>
                @empty
                    <div class="empty-state">Este negocio todavia no tiene servicios activos publicados.</div>
                @endforelse
            </div>
        </section>

        <section class="card" style="margin-top: 1.5rem;">
            <h2 class="section-title">Horarios base</h2>
            <div class="meta-grid" style="margin-top: 1rem;">
                @forelse ($business->hours as $hour)
                    <div class="meta-box">
                        <span>{{ $dayLabels[$hour->day_of_week] ?? 'Dia '.$hour->day_of_week }}</span>
                        <strong>{{ $hour->opens_at }} - {{ $hour->closes_at }}</strong>
                    </div>
                @empty
                    <div class="empty-state">No hay horarios activos publicados para este negocio.</div>
                @endforelse
            </div>
        </section>
    </section>
@endsection
