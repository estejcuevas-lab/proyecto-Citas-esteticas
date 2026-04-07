<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Citas</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f7f2ec; color: #2a211c; }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel { max-width: 980px; margin: 0 auto; background: #fff; border: 1px solid #dccab8; border-radius: 20px; padding: 2rem; }
        .topbar, .row { display: flex; justify-content: space-between; gap: 1rem; align-items: center; }
        .topbar { margin-bottom: 2rem; flex-wrap: wrap; }
        .button { display: inline-block; text-decoration: none; padding: 0.85rem 1rem; border-radius: 12px; background: #6a4730; color: white; font-weight: 700; }
        .secondary { background: #efe1d4; color: #2a211c; }
        .list { display: grid; gap: 1rem; }
        .card { border: 1px solid #e3d6ca; border-radius: 16px; padding: 1rem 1.2rem; }
        .muted { color: #6d5b4d; }
        .status { margin-bottom: 1rem; color: #1f6b36; font-weight: 700; }
    </style>
</head>
<body>
<main class="shell">
    <section class="panel">
        <div class="topbar">
            <div>
                <h1>Gestion de citas</h1>
                <p class="muted">Este modulo conecta clientes, negocios y servicios dentro del flujo principal del sistema.</p>
            </div>
            <div class="topbar">
                <a class="button secondary" href="{{ route('dashboard') }}">Volver</a>
                <a class="button" href="{{ route('appointments.create') }}">Nueva cita</a>
            </div>
        </div>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <div class="list">
            @forelse ($appointments as $appointment)
                <article class="card">
                    <div class="row">
                        <div>
                            <h2>{{ $appointment->service->name }}</h2>
                            <p class="muted">Negocio: {{ $appointment->business->name }}</p>
                            @if ($user->isAdmin() || $user->isBusiness())
                                <p>Cliente: {{ $appointment->user->name }}</p>
                            @endif
                            <p>Fecha: {{ $appointment->appointment_date }}</p>
                            <p>Hora: {{ $appointment->start_time }} - {{ $appointment->end_time }}</p>
                            <p>Estado: {{ $appointment->status }}</p>
                            <p>Precio del servicio: ${{ number_format((float) $appointment->service_price, 2) }}</p>
                            <p>Adelanto requerido: ${{ number_format((float) $appointment->advance_amount, 2) }} ({{ number_format((float) $appointment->advance_percentage, 0) }}%)</p>
                            <p>Estado del pago: {{ $appointment->payment_status }}</p>
                            <p>Notas: {{ $appointment->notes ?: 'Sin notas' }}</p>
                        </div>
                        <div>
                            <a class="button secondary" href="{{ route('appointments.edit', $appointment) }}">Editar</a>
                        </div>
                    </div>
                </article>
            @empty
                <article class="card">
                    <p>Todavia no hay citas registradas.</p>
                </article>
            @endforelse
        </div>
    </section>
</main>
</body>
</html>
