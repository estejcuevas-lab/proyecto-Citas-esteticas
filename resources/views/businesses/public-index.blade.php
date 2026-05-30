@extends('layouts.app')

@section('title', 'citas-app · Reserva tu cita')

@php
    $categoryMap = $businesses
        ->groupBy(fn ($business) => \Illuminate\Support\Str::slug((string) $business->type))
        ->map(fn ($group) => (string) $group->first()->type)
        ->sort()
        ->all();
@endphp

@section('content')
    <section class="surface">
        <div class="hero-grid">
            <article class="card card-accent">
                <span class="eyebrow">Sistema multinegocio</span>
                <h1 class="page-title">Encuentra <span class="text-accent">salones</span> y centros de belleza con servicios y reseñas reales.</h1>
                <p class="muted">
                    Explora negocios activos por ciudad, revisa su oferta principal y entra a su perfil para reservar.
                </p>
                <div class="actions mt-6">
                    @auth
                        <a class="btn btn-secondary inline-flex items-center gap-2" href="{{ route('dashboard') }}">
                            <x-heroicon-o-squares-2x2 class="h-5 w-5" />
                            Ir al panel
                        </a>
                        <a class="btn btn-secondary inline-flex items-center gap-2" href="{{ route('appointments.index') }}">
                            <x-heroicon-o-calendar-days class="h-5 w-5" />
                            Mis citas
                        </a>
                    @else
                        <a class="btn btn-secondary inline-flex items-center gap-2" href="{{ route('login') }}">
                            <x-heroicon-o-arrow-right-end-on-rectangle class="h-5 w-5" />
                            Iniciar sesion
                        </a>
                        <a class="btn btn-secondary" href="{{ route('register') }}">Crear cuenta</a>
                    @endauth
                </div>
            </article>

            <aside class="card">
                <h2 class="section-title">Que puedes hacer aqui</h2>
                <ul class="muted mt-4 list-disc space-y-2 pl-5">
                    <li>Comparar negocios con imagenes, precios y duraciones.</li>
                    <li>Entrar al detalle para ver reseñas verificadas de clientes.</li>
                    <li>Iniciar sesion y reservar cita desde el perfil del negocio.</li>
                </ul>
            </aside>
        </div>

        <div class="section-pathline" aria-hidden="true"></div>

        <div class="mt-8" id="catalogo" x-data="{ activeType: 'all' }">
            <div class="row-between mb-4">
                <h2 class="section-title m-0">Catalogo de negocios</h2>
                <span class="type-pill">{{ $businesses->count() }} disponibles</span>
            </div>
            <div class="market-filter-row mb-4">
                <button type="button" class="market-filter-chip" :class="{ 'is-active': activeType === 'all' }" @click="activeType = 'all'">
                    Todos
                </button>
                @foreach ($categoryMap as $categoryKey => $categoryLabel)
                    <button
                        type="button"
                        class="market-filter-chip"
                        :class="{ 'is-active': activeType === '{{ $categoryKey }}' }"
                        @click="activeType = '{{ $categoryKey }}'"
                    >
                        {{ $categoryLabel }}
                    </button>
                @endforeach
            </div>
            @include('partials.business-catalog', ['businesses' => $businesses])
        </div>
    </section>
@endsection

