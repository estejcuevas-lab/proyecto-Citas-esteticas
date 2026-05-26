@extends('layouts.app')

@section('title', 'Acceso business pendiente')

@section('content')
    <section class="surface" style="max-width: 860px; margin: 0 auto;">
        <div class="hero-grid">
            <article class="card card-accent">
                <span class="eyebrow">Revision administrativa</span>
                <h1 class="page-title">Tu acceso business sigue pendiente.</h1>
                <p class="muted">
                    Ya identificamos tu cuenta como futura administradora de negocio.
                    Un administrador debe aprobarla antes de habilitar negocios, servicios y horarios.
                </p>
            </article>

            <aside class="card">
                <h2 class="section-title">Que sigue ahora</h2>
                <div class="list" style="margin-top: 1rem;">
                    <div class="meta-box">
                        <span>Estado</span>
                        Solicitud enviada
                    </div>
                    <div class="meta-box">
                        <span>Cuenta</span>
                        {{ $user->email }}
                    </div>
                    <div class="meta-box">
                        <span>Siguiente paso</span>
                        Esperar aprobacion admin y volver a ingresar.
                    </div>
                </div>

                <div class="actions" style="margin-top: 1.25rem;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-dark" type="submit">Cerrar sesion</button>
                    </form>
                </div>
            </aside>
        </div>
    </section>
@endsection
