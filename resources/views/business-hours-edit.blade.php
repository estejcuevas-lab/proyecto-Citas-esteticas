@extends('layouts.app')

@section('title', 'Editar horario')
@section('hide_errors')

@section('content')
    <section class="surface" style="max-width: 45rem; margin: 0 auto;">
        <h1 class="page-title">Editar horario</h1>
        <p class="muted">{{ $business->name }}</p>

        <form method="POST" action="{{ route('businesses.hours.update', [$business, $hour]) }}" class="mt-6 field-list">
            @csrf
            @method('PUT')

            <label for="day_of_week">Dia
                <select id="day_of_week" name="day_of_week" required>
                    @foreach ($days as $key => $day)
                        <option value="{{ $key }}" @selected(old('day_of_week', $hour->day_of_week) == $key)>{{ $day }}</option>
                    @endforeach
                </select>
            </label>

            <label for="opens_at">Apertura
                <input id="opens_at" name="opens_at" type="time" value="{{ old('opens_at', $hour->opens_at) }}" required>
            </label>

            <label for="closes_at">Cierre
                <input id="closes_at" name="closes_at" type="time" value="{{ old('closes_at', $hour->closes_at) }}" required>
            </label>

            <label class="checkbox" for="is_active">
                <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $hour->is_active))>
                Dia habilitado para citas
            </label>

            @if ($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="actions">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a class="btn btn-secondary" href="{{ route('businesses.hours.index', $business) }}">Volver</a>
            </div>
        </form>
    </section>
@endsection
