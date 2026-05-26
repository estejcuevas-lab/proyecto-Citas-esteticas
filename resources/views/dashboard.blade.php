<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <style>
        :root {
            --primary-color: #994b35;
            --primary-color-deep: #6a2d1e;
            --primary-soft: #f1e3d7;
            --bg: #f6f1eb;
            --surface: rgba(255, 251, 247, 0.94);
            --surface-strong: #fffaf5;
            --line: #e1d5c8;
            --text: #2f241c;
            --muted: #746252;
            --success-bg: #e3f3e7;
            --success-text: #1f5e37;
            --error-bg: #fae3e3;
            --error-text: #8c2430;
            --shadow: 0 24px 64px rgba(89, 61, 43, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(153, 75, 53, 0.15), transparent 24%),
                linear-gradient(180deg, #fbf7f2 0%, var(--bg) 100%);
        }

        .page-shell {
            min-height: 100vh;
            padding: 1.25rem;
        }

        .page-width {
            max-width: 1120px;
            margin: 0 auto;
        }

        .surface {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.35fr 0.95fr;
            gap: 1rem;
        }

        .card {
            border-radius: 24px;
            border: 1px solid var(--line);
            background: var(--surface-strong);
            padding: 1.25rem;
        }

        .card-accent {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-color-deep));
            color: #fff8f3;
            border-color: transparent;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 0.9rem;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary-color-deep);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .card-accent .eyebrow {
            background: rgba(255, 250, 245, 0.18);
            color: #fffaf5;
        }

        .page-title {
            margin: 0.5rem 0 0.75rem;
            font-size: clamp(2rem, 4vw, 3.5rem);
            line-height: 1.02;
        }

        .section-title {
            margin: 0;
            font-size: clamp(1.35rem, 3vw, 2rem);
        }

        .muted {
            color: var(--muted);
            line-height: 1.65;
        }

        .card-accent .muted {
            color: rgba(255, 248, 243, 0.82);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .btn,
        button.btn {
            appearance: none;
            border: 0;
            border-radius: 14px;
            padding: 0.9rem 1.1rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-color-deep));
            color: #fffaf5;
        }

        .btn-secondary {
            background: var(--primary-soft);
            color: var(--text);
        }

        .btn-dark {
            background: #201713;
            color: #fffaf5;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary-color-deep);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .flash {
            padding: 0.95rem 1rem;
            border-radius: 16px;
            font-weight: 700;
            border: 1px solid transparent;
            margin-bottom: 1rem;
        }

        .flash-success {
            background: var(--success-bg);
            color: var(--success-text);
            border-color: #cde3d3;
        }

        .flash-error {
            background: var(--error-bg);
            color: var(--error-text);
            border-color: #f0cacc;
        }

        .list {
            display: grid;
            gap: 1rem;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.85rem;
        }

        .meta-box {
            padding: 1rem;
            border-radius: 18px;
            background: #fcf8f4;
            border: 1px solid #e9ddd1;
        }

        .meta-box strong,
        .meta-box span {
            display: block;
        }

        .meta-box span {
            color: var(--muted);
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.35rem;
        }

        @media (max-width: 900px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .surface {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <main class="page-shell">
        <div class="page-width">
            @if (session('status'))
                <div class="flash flash-success">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="flash flash-error">{{ session('error') }}</div>
            @endif

            <section class="surface">
                <div class="hero-grid">
                    <article class="card card-accent">
                        <span class="eyebrow">Centro de control</span>
                        <h1 class="page-title">{{ $user->name }}</h1>
                        <p class="muted">
                            Este panel resume citas, accesos rapidos y el estado actual del flujo de autenticacion,
                            onboarding y aprobacion del negocio.
                        </p>
                        <div class="actions" style="margin-top: 1.25rem;">
                            <span class="pill">Rol: {{ $user->role }}</span>
                            <span class="pill">Correo: {{ $user->email }}</span>
                        </div>
                    </article>

                    <aside class="card">
                        <h2 class="section-title">Atajos principales</h2>
                        <p class="muted" style="margin: 0.75rem 0 0;">
                            Usa este bloque para entrar rapido a tu agenda, explorar negocios publicos o avanzar a la administracion.
                        </p>

                        <div class="actions" style="margin-top: 1.25rem;">
                            <a class="btn btn-primary" href="{{ route('appointments.index') }}">Ver citas</a>
                            <a class="btn btn-secondary" href="{{ route('public.businesses.index') }}">Ver negocios publicos</a>
                            @if ($user->canManageBusinesses())
                                <a class="btn btn-secondary" href="{{ route('businesses.index') }}">Gestionar negocios</a>
                            @endif
                        </div>
                    </aside>
                </div>

                <section style="margin-top: 1.5rem;">
                    <div style="margin-bottom: 1rem;">
                        <h2 class="section-title">Metricas principales</h2>
                        <p class="muted" style="margin: 0.4rem 0 0;">Una lectura rapida antes de entrar a cada modulo.</p>
                    </div>

                    <div class="meta-grid">
                        @if ($user->isAdmin() || $user->isBusiness())
                            <article class="meta-box">
                                <span>Negocios</span>
                                <strong>{{ $stats['businesses'] }}</strong>
                                <p class="muted" style="margin: 0.45rem 0 0;">Negocios visibles para tu perfil actual.</p>
                            </article>

                            <article class="meta-box">
                                <span>Servicios</span>
                                <strong>{{ $stats['services'] }}</strong>
                                <p class="muted" style="margin: 0.45rem 0 0;">Oferta activa administrada desde el panel.</p>
                            </article>
                        @endif

                        <article class="meta-box">
                            <span>Citas</span>
                            <strong>{{ $stats['appointments'] }}</strong>
                            <p class="muted" style="margin: 0.45rem 0 0;">Reservas disponibles para tu cuenta.</p>
                        </article>

                        <article class="meta-box">
                            <span>Pendientes</span>
                            <strong>{{ $stats['pending_appointments'] }}</strong>
                            <p class="muted" style="margin: 0.45rem 0 0;">Citas que requieren seguimiento.</p>
                        </article>
                    </div>
                </section>

                <section style="margin-top: 1.5rem;">
                    <div style="margin-bottom: 1rem;">
                        <h2 class="section-title">Acciones por rol</h2>
                        <p class="muted" style="margin: 0.4rem 0 0;">El panel adapta las opciones segun tu estado actual.</p>
                    </div>

                    <div class="list">
                        @if ($user->isClient() && ! $user->hasPendingBusinessRequest())
                            <article class="card">
                                <strong>Convertir esta cuenta en business</strong>
                                <p class="muted" style="margin: 0.6rem 0 0;">
                                    Si planeas administrar un negocio, puedes solicitar acceso business y esperar aprobacion administrativa.
                                </p>
                                <div class="actions" style="margin-top: 1rem;">
                                    <a class="btn btn-primary" href="{{ route('business-access.create') }}">Solicitar acceso business</a>
                                </div>
                            </article>
                        @endif

                        @if ($user->hasPendingBusinessRequest())
                            <article class="card">
                                <strong>Solicitud business en revision</strong>
                                <p class="muted" style="margin: 0.6rem 0 0;">
                                    Tu cuenta sigue operando como cliente mientras el admin revisa la solicitud business.
                                </p>
                                <div class="actions" style="margin-top: 1rem;">
                                    <a class="btn btn-primary" href="{{ route('business-access.pending') }}">Ver estado de la solicitud</a>
                                </div>
                            </article>
                        @endif

                        @if ($user->canManageBusinesses())
                            <article class="card">
                                <strong>Gestion de negocios</strong>
                                <p class="muted" style="margin: 0.6rem 0 0;">
                                    Crea negocios, ajusta slug, branding, horarios y servicios desde una sola vista.
                                </p>
                                <div class="actions" style="margin-top: 1rem;">
                                    <a class="btn btn-primary" href="{{ route('businesses.index') }}">Abrir modulo</a>
                                    <a class="btn btn-secondary" href="{{ route('businesses.create') }}">Nuevo negocio</a>
                                </div>
                            </article>
                        @endif

                        @if ($user->isAdmin())
                            <article class="card">
                                <strong>Solicitudes business pendientes</strong>

                                @if ($pendingBusinessRequests->isEmpty())
                                    <p class="muted" style="margin: 0.75rem 0 0;">No hay solicitudes business pendientes en este momento.</p>
                                @else
                                    <div class="list" style="margin-top: 1rem;">
                                        @foreach ($pendingBusinessRequests as $pendingUser)
                                            <div class="meta-box">
                                                <span>{{ $pendingUser->email }}</span>
                                                <strong>{{ $pendingUser->name }}</strong>
                                                <p class="muted" style="margin: 0.45rem 0 0;">
                                                    Solicitado: {{ optional($pendingUser->business_requested_at)->format('Y-m-d H:i') }}
                                                </p>
                                                <form method="POST" action="{{ route('business-access.approve', $pendingUser) }}" style="margin-top: 0.9rem;">
                                                    @csrf
                                                    <button class="btn btn-primary" type="submit">Aprobar acceso</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </article>
                        @endif

                        @if ($user->canManageBusinesses())
                            <article class="card">
                                <strong>Sincronizar festivos</strong>
                                <p class="muted" style="margin: 0.6rem 0 0;">
                                    Trae festivos externos para bloquear fechas no laborables en la agenda.
                                </p>
                                <form method="POST" action="{{ route('holidays.sync') }}" style="margin-top: 1rem;">
                                    @csrf
                                    <button class="btn btn-secondary" type="submit">Sincronizar festivos</button>
                                </form>
                            </article>
                        @endif
                    </div>
                </section>

                <div class="actions" style="justify-content: flex-end; margin-top: 1.5rem;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-dark" type="submit">Cerrar sesion</button>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
