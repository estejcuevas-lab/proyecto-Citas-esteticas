@extends('layouts.app')

@section('title', 'Negocios publicos')

@section('content')
    <section class="surface">
        <div class="hero-grid">
            <article class="card card-accent">
                <span class="eyebrow">Vitrina publica</span>
                <h1 class="page-title">Explora negocios activos antes de iniciar sesion.</h1>
                <p class="muted">
                    Cada negocio cuenta con una pagina propia y color principal configurable
                    para presentar servicios, contacto y disponibilidad base.
                </p>
            </article>

            <aside class="card">
                <h2 class="section-title">Entrar a la plataforma</h2>
                <p class="muted" style="margin-top: 0.75rem;">
                    Cuando quieras reservar o administrar, vuelve al acceso principal.
                </p>
                <div class="actions" style="margin-top: 1rem;">
                    <a class="btn btn-primary" href="{{ route('home') }}">Iniciar sesion</a>
                </div>
            </aside>
        </div>

        <div class="list" style="margin-top: 1.5rem;">
            @forelse ($businesses as $business)
                <article class="card" style="--primary-color: {{ $business->brandColor() }}; --primary-color-deep: #6a2d1e; --primary-soft: #f1e3d7;">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; align-items: flex-start;">
                        <div>
                            <div class="actions" style="margin-bottom: 0.6rem;">
                                <span class="pill">{{ $business->type }}</span>
                                <span class="pill">{{ $business->services->count() }} servicios</span>
                            </div>
                            <h2 class="section-title">{{ $business->name }}</h2>
                            <p class="muted" style="margin: 0.5rem 0 0;">
                                {{ $business->address ?: 'Direccion disponible al abrir la pagina del negocio.' }}
                            </p>
                        </div>

                        <div class="actions">
                            <a class="btn btn-primary" href="{{ route('public.businesses.show', ['business' => $business->slug]) }}">Ver pagina</a>
                            <a class="btn btn-secondary" href="{{ route('home') }}">Ingresar</a>
                        </div>
                    </div>
                </article>
            @empty
                <article class="empty-state">
                    No hay negocios publicos disponibles todavia.
                </article>
            @endforelse
        </div>
    </section>
@endsection
