@extends('layouts.app')

@section('title', 'Completar perfil')

@section('content')
    <section class="surface" style="max-width: 760px; margin: 0 auto;">
        <div class="grid">
            <div>
                <span class="eyebrow">Onboarding cliente</span>
                <h1 class="page-title">Completa tu perfil antes de seguir.</h1>
                <p class="muted">
                    Este paso asegura que el sistema tenga la informacion minima para citas,
                    seguimiento y futuros flujos de negocio.
                </p>
            </div>

            <section class="card">
                <form method="POST" action="{{ route('onboarding.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="field-list">
                        <label for="name">
                            Nombre visible
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                        </label>

                        <label for="email">
                            Correo
                            <input id="email" type="email" value="{{ $user->email }}" disabled>
                        </label>

                        <label for="phone">
                            Telefono de contacto
                            <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" required>
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
                        <button class="btn btn-primary" type="submit">Guardar perfil</button>
                    </div>
                </form>
            </section>
        </div>
    </section>
@endsection
