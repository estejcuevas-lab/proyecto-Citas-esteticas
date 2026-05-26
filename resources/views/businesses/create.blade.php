@extends('layouts.app')

@section('title', 'Crear negocio')

@section('content')
    <section class="surface" style="max-width: 900px; margin: 0 auto;">
        <div class="grid">
            <div>
                <span class="eyebrow">Nuevo negocio</span>
                <h1 class="page-title">Crea un negocio listo para branding y pagina publica.</h1>
                <p class="muted">
                    Define el slug publico y el color principal desde el inicio para que la landing del negocio
                    salga alineada con su futura identidad visual.
                </p>
            </div>

            <section class="card">
                <form method="POST" action="{{ route('businesses.store') }}">
                    @csrf

                    <div class="field-list">
                        <label for="name">
                            Nombre del negocio
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                        </label>

                        <div class="two-col">
                            <label for="slug">
                                Slug publico
                                <input id="slug" name="slug" type="text" value="{{ old('slug') }}" placeholder="mi-negocio">
                            </label>

                            <label for="primary_color">
                                Color principal
                                <input id="primary_color" name="primary_color" type="text" value="{{ old('primary_color', '#994b35') }}" placeholder="#994b35">
                            </label>
                        </div>

                        <label for="type">
                            Tipo de negocio
                            <select id="type" name="type" required>
                                <option value="barberia" @selected(old('type') === 'barberia')>Barberia</option>
                                <option value="estetica" @selected(old('type') === 'estetica')>Estetica</option>
                                <option value="odontologia" @selected(old('type') === 'odontologia')>Odontologia</option>
                                <option value="consultorio" @selected(old('type') === 'consultorio')>Consultorio</option>
                            </select>
                        </label>

                        <div class="two-col">
                            <label for="email">
                                Correo
                                <input id="email" name="email" type="email" value="{{ old('email') }}">
                            </label>

                            <label for="phone">
                                Telefono
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
                            </label>
                        </div>

                        <label for="address">
                            Direccion
                            <input id="address" name="address" type="text" value="{{ old('address') }}">
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
                        <button class="btn btn-primary" type="submit">Guardar negocio</button>
                        <a class="btn btn-secondary" href="{{ route('businesses.index') }}">Cancelar</a>
                    </div>
                </form>
            </section>
        </div>
    </section>
@endsection
