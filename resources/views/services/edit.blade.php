@extends('layouts.app')

@section('title', 'Editar servicio')
@section('hide_errors')

@section('content')
    <section class="surface" style="max-width: 45rem; margin: 0 auto;">
        <h1 class="page-title">Editar servicio</h1>
        <p class="muted">{{ $business->name }}</p>

        <form method="POST" action="{{ route('businesses.services.update', [$business, $service]) }}" class="mt-6 field-list" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="name">Nombre del servicio
                <input id="name" name="name" type="text" value="{{ old('name', $service->name) }}" required>
            </label>

            <label for="description">Descripcion
                <textarea id="description" name="description">{{ old('description', $service->description) }}</textarea>
            </label>

            <label for="duration_minutes">Duracion (minutos)
                <input id="duration_minutes" name="duration_minutes" type="number" min="15" max="480" step="15" value="{{ old('duration_minutes', $service->duration_minutes) }}" required>
            </label>

            <label for="price">Precio
                <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $service->price) }}" required>
            </label>

            <label class="checkbox" for="active">
                <input id="active" name="active" type="checkbox" value="1" @checked(old('active', $service->active))>
                Servicio activo
            </label>

            <label for="images">
                Agregar nuevas imagenes (opcional)
                <input id="images" name="images[]" type="file" accept=".jpg,.jpeg,.png,.webp,image/*" multiple>
                <span class="hint">Puedes sumar mas resultados de este servicio sin perder los actuales.</span>
            </label>

            @if ($service->galleryImagePaths() !== [])
                <div class="field-list">
                    <p class="m-0 text-sm font-semibold">Imagenes actuales</p>
                    <div class="service-image-grid">
                        @foreach ($service->galleryImagePaths() as $index => $imagePath)
                            <label class="service-image-card" for="remove_image_{{ $index }}">
                                <img src="{{ Storage::url($imagePath) }}" alt="Imagen del servicio {{ $service->name }} {{ $index + 1 }}">
                                <span class="service-image-card__remove">
                                    <input id="remove_image_{{ $index }}" name="remove_images[]" type="checkbox" value="{{ $imagePath }}">
                                    Quitar
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="actions">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a class="btn btn-secondary" href="{{ route('businesses.services.index', $business) }}">Volver</a>
            </div>
        </form>
    </section>
@endsection
