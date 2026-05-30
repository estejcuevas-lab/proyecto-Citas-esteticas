@extends('layouts.app')

@section('title', 'Ingresar a citas-app')
@section('hide_errors')

@section('content')
    <section class="surface">
        <div class="hero-grid">
            <article class="card card-accent">
                <span class="eyebrow">Login first</span>
                <h1 class="page-title">Ingresa a citas-app desde Google o continua con tu acceso clasico.</h1>
                <p class="muted">
                    La entrada principal prioriza Google OAuth para clientes y futuros negocios.
                    Si eres nuevo, entraras al flujo de onboarding antes de usar el panel.
                </p>

                <div class="actions mt-6">
                    <a class="btn btn-secondary inline-flex items-center gap-2" href="{{ route('auth.google.redirect') }}">
                        <x-heroicon-o-globe-alt class="h-5 w-5" />
                        Continuar con Google
                    </a>
                    <a class="btn btn-secondary inline-flex items-center gap-2" href="{{ route('public.businesses.index') }}">
                        <x-heroicon-o-building-storefront class="h-5 w-5" />
                        Explorar negocios
                    </a>
                </div>

                <div class="card mt-6 border-white/20 bg-white/10">
                    <strong>Flujos principales</strong>
                    <p class="muted mt-3 mb-0">
                        Cliente nuevo: Google → completar perfil → dashboard.<br>
                        Negocio nuevo: Google → solicitar acceso → aprobacion admin → panel business.
                    </p>
                </div>
            </article>

            <aside class="card">
                <span class="eyebrow">Acceso alterno</span>
                <h2 class="section-title mt-3">Email y contrasena</h2>
                <p class="muted mb-0">
                    Fallback para cuentas existentes y pruebas internas.
                </p>

                @if ($errors->any())
                    <div class="flash-error mt-4">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-5">
                    @csrf

                    <div class="field-list">
                        <label for="email">
                            Correo electronico
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                        </label>

                        <label for="password">
                            Contrasena
                            <input id="password" name="password" type="password" required>
                        </label>
                    </div>

                    <label for="remember" class="checkbox-inline mt-4">
                        <input id="remember" name="remember" type="checkbox" value="1">
                        Recordarme
                    </label>

                    <div class="actions mt-5">
                        <button class="btn btn-primary" type="submit">Entrar</button>
                        <a class="btn btn-secondary" href="{{ route('register') }}">Crear cuenta cliente</a>
                    </div>
                </form>
            </aside>
        </div>
    </section>
@endsection
