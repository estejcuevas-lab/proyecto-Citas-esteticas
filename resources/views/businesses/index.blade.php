@extends('layouts.app')

@section('title', 'Negocios')

@section('content')
    <section class="surface">
        <div class="hero-grid">
            <article class="card">
                <span class="eyebrow">Administracion business</span>
                <h1 class="page-title">Negocios registrados</h1>
                <p class="muted">
                    Desde aqui controlas slug, branding, servicios, horarios y la pagina publica de cada negocio.
                </p>
            </article>

            <aside class="card">
                <h2 class="section-title">Acciones rapidas</h2>
                <div class="actions" style="margin-top: 1rem;">
                    <a class="btn btn-secondary" href="{{ route('dashboard') }}">Volver al panel</a>
                    <a class="btn btn-primary" href="{{ route('businesses.create') }}">Nuevo negocio</a>
                </div>
            </aside>
        </div>

        <div class="list" style="margin-top: 1.5rem;">
            @forelse ($businesses as $business)
                <article class="card" style="--primary-color: {{ $business->brandColor() }};">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; align-items: flex-start;">
                        <div>
                            <div class="actions" style="margin-bottom: 0.65rem;">
                                <span class="pill">{{ $business->type }}</span>
                                <span class="pill">{{ $business->slug }}</span>
                            </div>
                            <h2 class="section-title">{{ $business->name }}</h2>
                            <p class="muted" style="margin: 0.45rem 0 0;">Color principal: {{ $business->brandColor() }}</p>
                        </div>

                        <div class="actions">
                            <a class="btn btn-primary" href="{{ route('businesses.services.index', $business) }}">Servicios</a>
                            <a class="btn btn-primary" href="{{ route('businesses.hours.index', $business) }}">Horarios</a>
                            <a class="btn btn-secondary" href="{{ route('businesses.edit', $business) }}">Editar</a>
                            <a class="btn btn-secondary" href="{{ route('public.businesses.show', ['business' => $business->slug]) }}" target="_blank">Pagina publica</a>
                        </div>
                    </div>

                    <div class="meta-grid" style="margin-top: 1rem;">
                        <div class="meta-box"><span>Correo</span>{{ $business->email ?: 'Sin registrar' }}</div>
                        <div class="meta-box"><span>Telefono</span>{{ $business->phone ?: 'Sin registrar' }}</div>
                        <div class="meta-box"><span>Direccion</span>{{ $business->address ?: 'Sin registrar' }}</div>
                    </div>
                </article>
            @empty
                <article class="empty-state">
                    Todavia no hay negocios registrados. Crea el primero para habilitar branding, rutas publicas y gestion interna.
                </article>
            @endforelse
        </div>
    </section>
@endsection
