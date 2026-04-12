<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
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
            max-width: 760px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #dccab8;
            border-radius: 20px;
            padding: 2rem;
        }

        .badge {
            display: inline-block;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            background: #efe1d4;
            font-weight: 700;
        }

        .logout {
            margin-top: 2rem;
        }

        .flash {
            margin-top: 1rem;
            padding: 0.85rem 1rem;
            border-radius: 12px;
        }

        .flash.success {
            background: #dff2e2;
            color: #1f5d2a;
        }

        .flash.error {
            background: #f9dcdc;
            color: #7a1d1d;
        }

        .actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat {
            padding: 1rem;
            border-radius: 14px;
            background: #f3e7db;
        }

        .stat strong {
            display: block;
            font-size: 1.8rem;
            margin-top: 0.35rem;
        }

        .link-button,
        .actions button,
        .logout button {
            padding: 0.85rem 1rem;
            border: 0;
            border-radius: 12px;
            background: #6a4730;
            color: white;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="panel">
            <p class="badge">Ruta protegida</p>
            <h1>Bienvenido, {{ $user->name }}</h1>
            <p>Correo: {{ $user->email }}</p>
            <p>Rol actual: <strong>{{ $user->role }}</strong></p>
            <p>
                Esta vista confirma que la autenticacion y las rutas base ya funcionan:
                solo un usuario autenticado puede entrar aqui.
            </p>

            @if (session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="flash error">{{ session('error') }}</div>
            @endif

            <div class="stats">
                @if ($user->isAdmin() || $user->isBusiness())
                    <div class="stat">
                        Negocios
                        <strong>{{ $stats['businesses'] }}</strong>
                    </div>
                    <div class="stat">
                        Servicios
                        <strong>{{ $stats['services'] }}</strong>
                    </div>
                @endif
                <div class="stat">
                    Citas
                    <strong>{{ $stats['appointments'] }}</strong>
                </div>
                <div class="stat">
                    Pendientes
                    <strong>{{ $stats['pending_appointments'] }}</strong>
                </div>
            </div>

            <div class="actions">
                <a class="link-button" href="{{ route('businesses.index') }}">Gestionar negocios</a>
                <a class="link-button" href="{{ route('appointments.index') }}">Gestionar citas</a>
                @if ($user->isAdmin() || $user->isBusiness())
                    <form method="POST" action="{{ route('holidays.sync') }}">
                        @csrf
                        <button type="submit">Sincronizar festivos</button>
                    </form>
                @endif
            </div>

            <form class="logout" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesion</button>
            </form>
        </section>
    </main>
</body>
</html>
