@extends('layouts.app')

@section('title', 'Editar negocio')
@section('theme_style', '--primary-color: '.old('primary_color', $business->brandColor()).'; --primary-color-deep: #6a2d1e; --primary-soft: #f1e3d7;')

@section('content')
    <section class="surface" style="max-width: 960px; margin: 0 auto;">
        <div class="hero-grid">
            <article class="card">
                <span class="eyebrow">Branding y datos base</span>
                <h1 class="page-title">{{ $business->name }}</h1>
                <p class="muted">
                    Ajusta identidad visual, slug publico y canales de contacto para que la pagina del negocio
                    sea clara tanto para clientes como para la operacion interna.
                </p>

                <div class="meta-grid" style="margin-top: 1.25rem;">
                    <div class="meta-box">
                        <span>Slug actual</span>
                        {{ $business->slug ?: 'Pendiente de generar' }}
                    </div>
                    <div class="meta-box">
                        <span>Color principal</span>
                        {{ $business->brandColor() }}
                    </div>
                    <div class="meta-box">
                        <span>Pagina publica</span>
                        <a href="{{ route('public.businesses.show', ['business' => $business->slug]) }}" target="_blank">Abrir pagina</a>
                    </div>
                </div>
            </article>

            <aside class="card card-accent">
                <span class="eyebrow" style="background: rgba(255, 250, 245, 0.18); color: #fffaf5;">Preview rapido</span>
                <h2 class="section-title" style="margin-top: 0.9rem;">{{ $business->name }}</h2>
                <p class="muted" style="margin-top: 0.75rem;">
                    Este bloque usa el color activo del negocio para anticipar como se sentira la pagina publica.
                </p>
            </aside>
        </div>

        <section class="card" style="margin-top: 1.5rem;">
            <form method="POST" action="{{ route('businesses.update', $business) }}">
                @csrf
                @method('PUT')

                <div class="field-list">
                    <label for="name">
                        Nombre del negocio
                        <input id="name" name="name" type="text" value="{{ old('name', $business->name) }}" required>
                    </label>

                    <div class="two-col">
                        <label for="slug">
                            Slug publico
                            <input id="slug" name="slug" type="text" value="{{ old('slug', $business->slug) }}" required>
                        </label>

                        <label for="primary_color">
                            Color principal
                            <input id="primary_color" name="primary_color" type="text" value="{{ old('primary_color', $business->brandColor()) }}" required>
                        </label>
                    </div>

                    <label for="type">
                        Tipo de negocio
                        <select id="type" name="type" required>
                            <option value="barberia" @selected(old('type', $business->type) === 'barberia')>Barberia</option>
                            <option value="estetica" @selected(old('type', $business->type) === 'estetica')>Estetica</option>
                            <option value="odontologia" @selected(old('type', $business->type) === 'odontologia')>Odontologia</option>
                            <option value="consultorio" @selected(old('type', $business->type) === 'consultorio')>Consultorio</option>
                        </select>
                    </label>

                    <div class="two-col">
                        <label for="email">
                            Correo
                            <input id="email" name="email" type="email" value="{{ old('email', $business->email) }}">
                        </label>

                        <label for="phone">
                            Telefono
                            <input id="phone" name="phone" type="text" value="{{ old('phone', $business->phone) }}">
                        </label>
                    </div>

                    <label for="address">
                        Direccion
                        <input id="address" name="address" type="text" value="{{ old('address', $business->address) }}">
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
                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
                    <a class="btn btn-secondary" href="{{ route('businesses.index') }}">Volver al listado</a>
                </div>
            </form>
        </section>
    </section>
@endsection
