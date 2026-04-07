<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Horarios</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f7f2ec; color: #2a211c; }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel { max-width: 900px; margin: 0 auto; background: #fff; border: 1px solid #dccab8; border-radius: 20px; padding: 2rem; }
        .topbar, .row { display: flex; justify-content: space-between; gap: 1rem; align-items: center; }
        .topbar { margin-bottom: 2rem; flex-wrap: wrap; }
        .button { display: inline-block; text-decoration: none; padding: 0.85rem 1rem; border-radius: 12px; background: #6a4730; color: white; font-weight: 700; }
        .secondary { background: #efe1d4; color: #2a211c; }
        .list { display: grid; gap: 1rem; }
        .card { border: 1px solid #e3d6ca; border-radius: 16px; padding: 1rem 1.2rem; }
        .status { margin-bottom: 1rem; color: #1f6b36; font-weight: 700; }
    </style>
</head>
<body>
<main class="shell">
    <section class="panel">
        <div class="topbar">
            <div>
                <h1>Horarios de {{ $business->name }}</h1>
                <p>Estos horarios definen cuando el negocio puede recibir citas.</p>
            </div>
            <div class="topbar">
                <a class="button secondary" href="{{ route('businesses.index') }}">Volver a negocios</a>
                <a class="button" href="{{ route('businesses.hours.create', $business) }}">Nuevo horario</a>
            </div>
        </div>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <div class="list">
            @forelse ($hours as $hour)
                <article class="card">
                    <div class="row">
                        <div>
                            <h2>{{ $days[$hour->day_of_week] }}</h2>
                            <p>Apertura: {{ $hour->opens_at }}</p>
                            <p>Cierre: {{ $hour->closes_at }}</p>
                            <p>Estado: {{ $hour->is_active ? 'Activo' : 'Inactivo' }}</p>
                        </div>
                        <div>
                            <a class="button secondary" href="{{ route('businesses.hours.edit', [$business, $hour]) }}">Editar</a>
                        </div>
                    </div>
                </article>
            @empty
                <article class="card">
                    <p>Todavia no hay horarios configurados.</p>
                </article>
            @endforelse
        </div>
    </section>
</main>
</body>
</html>
