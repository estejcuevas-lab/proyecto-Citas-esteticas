@extends('layouts.app')

@section('title', 'Servicios · '.$business->name)

@section('content')
    <section class="surface">
        <div class="page-topbar">
            <div>
                <h1 class="page-title">Servicios</h1>
                <p class="muted mb-0">{{ $business->name }} — duracion, precio y estado operativo.</p>
            </div>
            <div class="button-row">
                <a class="button secondary" href="{{ route('businesses.index') }}">Volver a negocios</a>
                <a class="button primary" href="{{ route('businesses.services.create', $business) }}">Nuevo servicio</a>
            </div>
        </div>

        <div class="list">
            @forelse ($services as $service)
                <article class="card">
                    <div class="row-between">
                        <div>
                            <h2 class="section-title text-xl">{{ $service->name }}</h2>
                            <p class="muted m-0">{{ $service->description ?: 'Sin descripcion' }}</p>
                        </div>
                        <div class="button-row">
                            <span class="pill {{ $service->active ? 'active' : 'inactive' }}">
                                {{ $service->active ? 'Activo' : 'Inactivo' }}
                            </span>
                            <a class="button secondary" href="{{ route('businesses.services.edit', [$business, $service]) }}">Editar</a>
                        </div>
                    </div>
                    <div class="meta-grid mt-4">
                        <div class="meta-box"><span>Duracion</span>{{ $service->duration_minutes }} min</div>
                        <div class="meta-box"><span>Precio</span>${{ number_format((float) $service->price, 2) }}</div>
                        <div class="meta-box"><span>Galeria</span>{{ count($service->galleryImagePaths()) }} imagenes</div>
                    </div>

                    @if ($service->galleryImageUrls() !== [])
                        <div class="service-image-grid mt-4">
                            @foreach (array_slice($service->galleryImageUrls(), 0, 4) as $imageUrl)
                                <div class="service-image-card">
                                    <img src="{{ $imageUrl }}" alt="Imagen de {{ $service->name }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </article>
            @empty
                <article class="empty-state">
                    <h2 class="section-title text-xl">Sin servicios</h2>
                    <p class="muted mb-0">Agrega servicios para ver duracion, precio y estado.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection
