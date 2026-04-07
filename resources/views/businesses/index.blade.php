<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Negocios</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f2ec;
            color: #2a211c;
        }

        .shell {
            min-height: 100vh;
            padding: 2rem;
        }

        .panel {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #dccab8;
            border-radius: 20px;
            padding: 2rem;
        }

        .topbar, .row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: center;
        }

        .topbar {
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .button {
            display: inline-block;
            text-decoration: none;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            background: #6a4730;
            color: white;
            font-weight: 700;
        }

        .secondary {
            background: #efe1d4;
            color: #2a211c;
        }

        .list {
            display: grid;
            gap: 1rem;
        }

        .card {
            border: 1px solid #e3d6ca;
            border-radius: 16px;
            padding: 1rem 1.2rem;
        }

        .muted {
            color: #6d5b4d;
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="panel">
            <div class="topbar">
                <div>
                    <h1>Negocios registrados</h1>
                    <p class="muted">Aqui se gestiona la informacion base de cada negocio del sistema.</p>
                </div>

                <div class="topbar">
                    <a class="button secondary" href="{{ route('dashboard') }}">Volver</a>
                    @if ($user->isBusiness() || $user->isAdmin())
                        <a class="button" href="{{ route('businesses.create') }}">Nuevo negocio</a>
                    @endif
                </div>
            </div>

            <div class="list">
                @forelse ($businesses as $business)
                    <article class="card">
                        <div class="row">
                            <div>
                                <h2>{{ $business->name }}</h2>
                                <p class="muted">Tipo: {{ $business->type }}</p>
                                <p>Correo: {{ $business->email ?: 'Sin registrar' }}</p>
                                <p>Telefono: {{ $business->phone ?: 'Sin registrar' }}</p>
                                <p>Direccion: {{ $business->address ?: 'Sin registrar' }}</p>
                            </div>

                            <div>
                                <a class="button" href="{{ route('businesses.services.index', $business) }}">Servicios</a>
                                <a class="button" href="{{ route('businesses.hours.index', $business) }}">Horarios</a>
                                <a class="button secondary" href="{{ route('businesses.edit', $business) }}">Editar</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="card">
                        <p>Todavia no hay negocios registrados para este usuario.</p>
                    </article>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
