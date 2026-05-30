@extends('layouts.app')

@section('title', 'Crear servicio')
@section('hide_errors')

@section('content')
    <section class="surface" style="max-width: 45rem; margin: 0 auto;">
        <h1 class="page-title">Crear servicio</h1>
        <p class="muted">{{ $business->name }} — la duracion define la agenda de citas.</p>

        <form method="POST" action="{{ route('businesses.services.store', $business) }}" class="mt-6 field-list" enctype="multipart/form-data">
            @csrf

            <label for="name">Nombre del servicio
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
            </label>

            <label for="description">Descripcion
                <textarea id="description" name="description">{{ old('description') }}</textarea>
            </label>

            <label for="duration_minutes">Duracion (minutos)
                <input id="duration_minutes" name="duration_minutes" type="number" min="15" max="480" step="15" value="{{ old('duration_minutes', 60) }}" required>
            </label>

            <label for="price">Precio
                <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', 0) }}" required>
            </label>

            <label class="checkbox" for="active">
                <input id="active" name="active" type="checkbox" value="1" @checked(old('active', true))>
                Servicio activo
            </label>

            <label for="images">
                Imagenes del resultado (opcional)
                <input id="images" name="images[]" type="file" accept=".jpg,.jpeg,.png,.webp,image/*" multiple>
                <span class="hint">Puedes subir hasta 10 imagenes (max 5MB c/u) para mostrar ejemplos del servicio.</span>
            </label>

            @if ($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="actions">
                <button type="submit" class="btn btn-primary">Guardar servicio</button>
                <a class="btn btn-secondary" href="{{ route('businesses.services.index', $business) }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
