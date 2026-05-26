@extends('layouts.app')

@section('title', 'Registro de cliente')

@section('content')
    <section class="surface" style="max-width: 760px; margin: 0 auto;">
        <div class="grid">
            <div>
                <span class="eyebrow">Cuenta cliente</span>
                <h1 class="page-title">Crea una cuenta manual para cliente.</h1>
                <p class="muted">
                    El registro clasico queda orientado a clientes. Si quieres administrar un negocio,
                    la ruta recomendada es entrar con Google y luego solicitar acceso business desde tu panel.
                </p>
            </div>

            <section class="card">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="field-list">
                        <label for="name">
                            Nombre
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                        </label>

                        <label for="email">
                            Correo electronico
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                        </label>

                        <label for="password">
                            Contrasena
                            <input id="password" name="password" type="password" required>
                        </label>

                        <label for="password_confirmation">
                            Confirmar contrasena
                            <input id="password_confirmation" name="password_confirmation" type="password" required>
                        </label>
                    </div>

                    @if ($errors->any())
                        <div class="flash flash-error" style="margin-top: 1rem;">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="actions" style="margin-top: 1.25rem;">
                        <button class="btn btn-primary" type="submit">Registrarme</button>
                        <a class="btn btn-secondary" href="{{ route('auth.google.redirect') }}">Continuar con Google</a>
                        <a class="btn btn-secondary" href="{{ route('login') }}">Ya tengo cuenta</a>
                    </div>
                </form>
            </section>
        </div>
    </section>
@endsection
