@extends('layouts.app')

@section('title', 'Horarios · '.$business->name)

@section('content')
    <section class="surface" style="max-width: 56rem; margin: 0 auto;">
        <div class="page-topbar">
            <div>
                <h1 class="page-title">Horarios</h1>
                <p class="muted mb-0">{{ $business->name }} — disponibilidad para citas.</p>
            </div>
            <div class="button-row">
                <a class="button secondary" href="{{ route('businesses.index') }}">Volver</a>
                <a class="button primary" href="{{ route('businesses.hours.create', $business) }}">Nuevo horario</a>
            </div>
        </div>

        <div class="list">
            @forelse ($hours as $hour)
                <article class="card row-between">
                    <div>
                        <h2 class="section-title text-xl">{{ $days[$hour->day_of_week] }}</h2>
                        <p class="muted m-0">{{ $hour->opens_at }} – {{ $hour->closes_at }}</p>
                        <p class="muted m-0 mt-1">{{ $hour->is_active ? 'Activo' : 'Inactivo' }}</p>
                    </div>
                    <a class="button secondary" href="{{ route('businesses.hours.edit', [$business, $hour]) }}">Editar</a>
                </article>
            @empty
                <article class="empty-state">
                    <p class="m-0">Todavia no hay horarios configurados.</p>
                </article>
            @endforelse
        </div>
    </section>
@endsection
