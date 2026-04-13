<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <style>
        :root {
            --bg: #f4efe8;
            --panel: rgba(255, 251, 247, 0.92);
            --panel-strong: #fffaf5;
            --line: #dbcab9;
            --text: #2f241c;
            --muted: #786555;
            --brand: #9a4d36;
            --brand-deep: #6f2f1f;
            --success: #dff2e2;
            --success-text: #235433;
            --danger: #f7dddd;
            --danger-text: #7c2431;
            --shadow: 0 24px 60px rgba(87, 56, 36, 0.14);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(217, 163, 95, 0.24), transparent 28%),
                radial-gradient(circle at right center, rgba(154, 77, 54, 0.16), transparent 24%),
                linear-gradient(180deg, #f8f3ed 0%, var(--bg) 100%);
        }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel {
            max-width: 1120px;
            margin: 0 auto;
            padding: 2rem;
            border: 1px solid rgba(219, 202, 185, 0.92);
            border-radius: 28px;
            background: var(--panel);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }
        .hero {
            display: grid;
            grid-template-columns: 1.35fr 0.95fr;
            gap: 1.5rem;
            align-items: stretch;
        }
        .hero-card,
        .quick-card,
        .stat,
        .note {
            border-radius: 24px;
            border: 1px solid var(--line);
            background: var(--panel-strong);
        }
        .hero-card {
            padding: 2rem;
            background: linear-gradient(135deg, rgba(154, 77, 54, 0.96), rgba(111, 47, 31, 0.94));
            color: #fff7f2;
        }
        .hero-card h1 {
            margin: 0.5rem 0 0.75rem;
            font-size: clamp(2rem, 4vw, 3.4rem);
            line-height: 1;
        }
        .eyebrow,
        .role-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.55rem 0.95rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .eyebrow {
            background: rgba(255, 244, 234, 0.16);
            border: 1px solid rgba(255, 235, 219, 0.24);
        }
        .role-pill {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
            margin-top: 1rem;
        }
        .hero-card p {
            max-width: 44rem;
            margin: 0;
            color: rgba(255, 247, 242, 0.84);
            line-height: 1.65;
        }
        .quick-card { padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; gap: 1rem; }
        .quick-card h2, .section-title { margin: 0; font-size: 1.1rem; }
        .quick-card p, .section-copy, .note p { margin: 0; color: var(--muted); line-height: 1.6; }
        .flash-wrap { display: grid; gap: 0.85rem; margin-top: 1.5rem; }
        .flash { padding: 0.95rem 1rem; border-radius: 16px; font-weight: 700; border: 1px solid transparent; }
        .flash.success { background: var(--success); color: var(--success-text); border-color: #c0dfc6; }
        .flash.error { background: var(--danger); color: var(--danger-text); border-color: #efc2c7; }
        .section { margin-top: 1.7rem; }
        .section-head { display: flex; justify-content: space-between; gap: 1rem; align-items: end; margin-bottom: 1rem; flex-wrap: wrap; }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(205px, 1fr));
            gap: 1rem;
        }
        .stat { padding: 1.3rem; }
        .stat-label {
            display: block;
            color: var(--muted);
            font-size: 0.92rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .stat strong {
            display: block;
            margin-top: 0.45rem;
            font-size: clamp(2rem, 3vw, 2.6rem);
            color: var(--brand-deep);
        }
        .stat-note { margin-top: 0.6rem; color: var(--muted); font-size: 0.95rem; }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }
        .note { padding: 1.25rem; }
        .note strong { display: block; margin-bottom: 0.45rem; color: var(--brand-deep); }
        .button-row { display: flex; flex-wrap: wrap; gap: 0.85rem; }
        .button, .button-row button, .logout button {
            appearance: none;
            border: 0;
            border-radius: 14px;
            padding: 0.95rem 1.15rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.16s ease;
        }
        .button:hover, .button-row button:hover, .logout button:hover { transform: translateY(-1px); }
        .button.primary, .button-row button.primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            color: #fffaf5;
        }
        .button.secondary { background: #efe1d4; color: var(--text); }
        .logout { margin-top: 1.8rem; display: flex; justify-content: flex-end; }
        .logout button { background: #201713; color: #fffaf5; }
        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
            .panel { padding: 1.25rem; }
            .shell { padding: 1rem; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="panel">
            <div class="hero">
                <article class="hero-card">
                    <span class="eyebrow">Centro de control</span>
                    <h1>{{ $user->name }}</h1>
                    <p>Este panel resume el estado operativo del sistema de citas: negocios, servicios, reservas activas y tareas rápidas para continuar el flujo.</p>
                    <span class="role-pill">Rol actual: {{ $user->role }}</span>
                </article>
                <aside class="quick-card">
                    <div>
                        <h2>Resumen del acceso</h2>
                        <p>Correo: {{ $user->email }}. La sesión está activa y las rutas protegidas ya separan permisos por perfil.</p>
                    </div>
                    <div class="button-row">
                        <a class="button primary" href="{{ route('appointments.index') }}">Ver citas</a>
                        <a class="button secondary" href="{{ route('businesses.index') }}">Ver negocios</a>
                    </div>
                </aside>
            </div>

            <div class="flash-wrap">
                @if (session('success'))
                    <div class="flash success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="flash error">{{ session('error') }}</div>
                @endif
            </div>

            <section class="section">
                <div class="section-head">
                    <div>
                        <h2 class="section-title">Métricas principales</h2>
                        <p class="section-copy">Una vista rápida para saber qué está pasando antes de entrar a cada módulo.</p>
                    </div>
                </div>
                <div class="stats">
                    @if ($user->isAdmin() || $user->isBusiness())
                        <article class="stat">
                            <span class="stat-label">Negocios</span>
                            <strong>{{ $stats['businesses'] }}</strong>
                            <div class="stat-note">Sedes o negocios gestionados en el sistema.</div>
                        </article>
                        <article class="stat">
                            <span class="stat-label">Servicios</span>
                            <strong>{{ $stats['services'] }}</strong>
                            <div class="stat-note">Oferta activa disponible para agendamiento.</div>
                        </article>
                    @endif
                    <article class="stat">
                        <span class="stat-label">Citas</span>
                        <strong>{{ $stats['appointments'] }}</strong>
                        <div class="stat-note">Reservas visibles para tu perfil actual.</div>
                    </article>
                    <article class="stat">
                        <span class="stat-label">Pendientes</span>
                        <strong>{{ $stats['pending_appointments'] }}</strong>
                        <div class="stat-note">Citas que todavía requieren confirmación o seguimiento.</div>
                    </article>
                </div>
            </section>

            <section class="section">
                <div class="section-head">
                    <div>
                        <h2 class="section-title">Acciones rápidas</h2>
                        <p class="section-copy">Atajos para navegar sin pasar por menús largos.</p>
                    </div>
                </div>
                <div class="actions-grid">
                    <article class="note">
                        <strong>Gestión de negocios</strong>
                        <p>Actualiza datos base, revisa sedes registradas y entra a servicios u horarios.</p>
                        <div class="button-row" style="margin-top: 1rem;">
                            <a class="button primary" href="{{ route('businesses.index') }}">Abrir módulo</a>
                        </div>
                    </article>
                    <article class="note">
                        <strong>Agenda y reservas</strong>
                        <p>Consulta citas, confirma estados y revisa pagos o adelantos pendientes.</p>
                        <div class="button-row" style="margin-top: 1rem;">
                            <a class="button primary" href="{{ route('appointments.index') }}">Abrir agenda</a>
                        </div>
                    </article>
                    @if ($user->isAdmin() || $user->isBusiness())
                        <article class="note">
                            <strong>Festivos sincronizados</strong>
                            <p>Trae festivos externos para bloquear fechas no laborables dentro de la agenda.</p>
                            <div class="button-row" style="margin-top: 1rem;">
                                <form method="POST" action="{{ route('holidays.sync') }}">
                                    @csrf
                                    <button class="primary" type="submit">Sincronizar festivos</button>
                                </form>
                            </div>
                        </article>
                    @endif
                </div>
            </section>

            <form class="logout" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </section>
    </main>
</body>
</html>
