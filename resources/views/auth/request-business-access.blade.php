@extends('layouts.app')

@section('title', 'Solicitar acceso business')

@section('content')
    <section class="surface" style="max-width: 860px; margin: 0 auto;">
        <div class="hero-grid">
            <article class="card">
                <span class="eyebrow">Escalar tu cuenta</span>
                <h1 class="page-title">Solicita acceso para administrar un negocio.</h1>
                <p class="muted">
                    Al enviar esta solicitud, tu cuenta quedara marcada para revision
                    administrativa antes de habilitar el panel business.
                </p>

                <div class="meta-grid" style="margin-top: 1.25rem;">
                    <div class="meta-box">
                        <span>Usuario</span>
                        {{ $user->name }}
                    </div>
                    <div class="meta-box">
                        <span>Correo</span>
                        {{ $user->email }}
                    </div>
                    <div class="meta-box">
                        <span>Telefono</span>
                        {{ $user->phone ?: 'Completa tu perfil antes de solicitar acceso.' }}
                    </div>
                </div>
            </article>

            <aside class="card">
                <h2 class="section-title">Confirmar solicitud</h2>
                <p class="muted" style="margin-top: 0.75rem;">
                    Cuando un administrador apruebe tu acceso, podras crear tu negocio,
                    personalizar colores y entrar al panel de gestion.
                </p>

                <form method="POST" action="{{ route('business-access.store') }}" style="margin-top: 1.25rem;">
                    @csrf
                    <div class="actions">
                        <button class="btn btn-primary" type="submit">Solicitar acceso business</button>
                        <a class="btn btn-secondary" href="{{ route('dashboard') }}">Volver al dashboard</a>
                    </div>
                </form>
            </aside>
        </div>
    </section>
@endsection
