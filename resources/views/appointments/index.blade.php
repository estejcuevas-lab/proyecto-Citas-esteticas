<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Citas</title>
    <style>
        :root {
            --bg: #f4efe8;
            --panel: rgba(255, 251, 247, 0.94);
            --panel-strong: #fffaf5;
            --line: #ddcdbd;
            --text: #2f241c;
            --muted: #776655;
            --brand: #994b35;
            --brand-deep: #6a2d1e;
            --soft: #efe2d6;
            --success-bg: #e1f4e5;
            --success-text: #245639;
            --warn-bg: #fff0df;
            --warn-text: #91561c;
            --danger-bg: #f8dede;
            --danger-text: #7a2430;
            --shadow: 0 20px 50px rgba(87, 56, 36, 0.14);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background:
                radial-gradient(circle at left top, rgba(217, 163, 95, 0.24), transparent 24%),
                linear-gradient(180deg, #f8f4ef 0%, var(--bg) 100%);
            color: var(--text);
        }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel {
            max-width: 1180px;
            margin: 0 auto;
            padding: 2rem;
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
        }
        .topbar, .card-header {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .topbar { align-items: center; margin-bottom: 1.5rem; }
        .topbar h1 { margin: 0; font-size: clamp(2rem, 4vw, 3rem); }
        .muted { color: var(--muted); }
        .hero {
            display: grid;
            grid-template-columns: 1.3fr 0.9fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .hero-card, .summary-card, .card {
            border-radius: 24px;
            border: 1px solid var(--line);
            background: var(--panel-strong);
        }
        .hero-card {
            padding: 1.75rem;
            background: linear-gradient(135deg, rgba(153, 75, 53, 0.96), rgba(106, 45, 30, 0.94));
            color: #fff8f4;
        }
        .hero-card p { margin: .75rem 0 0; max-width: 42rem; color: rgba(255,248,244,.85); line-height: 1.6; }
        .summary-card { padding: 1.5rem; }
        .summary-card strong { display: block; margin-bottom: .3rem; color: var(--brand-deep); }
        .button-row, .actions { display: flex; flex-wrap: wrap; gap: .75rem; }
        .button, .actions button {
            appearance: none;
            border: 0;
            border-radius: 14px;
            padding: .8rem 1rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .button.primary { background: linear-gradient(135deg, var(--brand), var(--brand-deep)); color: #fffaf5; }
        .button.secondary { background: var(--soft); color: var(--text); }
        .button.ghost { background: #fffaf5; border: 1px solid var(--line); color: var(--brand-deep); }
        .status-banner {
            margin-bottom: 1rem;
            padding: .95rem 1rem;
            border-radius: 16px;
            background: var(--success-bg);
            color: var(--success-text);
            font-weight: 700;
        }
        .list { display: grid; gap: 1rem; }
        .card { padding: 1.25rem; }
        .card h2 { margin: 0 0 .35rem; font-size: 1.45rem; }
        .pills { display: flex; flex-wrap: wrap; gap: .55rem; }
        .pill {
            display: inline-flex;
            align-items: center;
            padding: .42rem .78rem;
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .pill.pending { background: var(--warn-bg); color: var(--warn-text); }
        .pill.confirmed, .pill.paid, .pill.completed { background: var(--success-bg); color: var(--success-text); }
        .pill.cancelled { background: var(--danger-bg); color: var(--danger-text); }
        .pill.pending_advance, .pill.partially_paid { background: #efe2d6; color: var(--brand-deep); }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: .9rem;
        }
        .info-box {
            padding: .95rem;
            border-radius: 18px;
            background: #fcf8f4;
            border: 1px solid #eadfd4;
        }
        .info-box span {
            display: block;
            color: var(--muted);
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .35rem;
        }
        .actions { margin-top: 1rem; }
        .actions form { margin: 0; }
        .actions .confirm { background: #2f6b48; color: white; }
        .actions .warn { background: #ae4d40; color: white; }
        .actions .info { background: #e5c6a8; color: #352218; }
        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
            .shell { padding: 1rem; }
            .panel { padding: 1.25rem; }
        }
    </style>
</head>
<body>
<main class="shell">
    <section class="panel">
        <div class="topbar">
            <div>
                <h1>Agenda de citas</h1>
                <p class="muted">Consulta reservas, pagos, estados y acciones rápidas sin salir del mismo módulo.</p>
            </div>
            <div class="button-row">
                <a class="button secondary" href="{{ route('dashboard') }}">Volver al panel</a>
                <a class="button primary" href="{{ route('appointments.create') }}">Nueva cita</a>
            </div>
        </div>

        <div class="hero">
            <article class="hero-card">
                <h2 style="margin:0; font-size:1.15rem; text-transform:uppercase; letter-spacing:.08em;">Vista operativa</h2>
                <p>Cada tarjeta resume servicio, negocio, horario, pago y estado actual. Las acciones cambian según el perfil y respetan las reglas del flujo de reserva.</p>
            </article>
            <aside class="summary-card">
                <strong>Total visible</strong>
                <p class="muted">{{ $appointments->count() }} citas disponibles para tu perfil actual.</p>
            </aside>
        </div>

        @if (session('status'))
            <div class="status-banner">{{ session('status') }}</div>
        @endif

        <div class="list">
            @forelse ($appointments as $appointment)
                <article class="card">
                    <div class="card-header">
                        <div>
                            <h2>{{ $appointment->service->name }}</h2>
                            <p class="muted" style="margin:0;">
                                {{ $appointment->business->name }}
                                @if ($user->isAdmin() || $user->isBusiness())
                                    · Cliente: {{ $appointment->user->name }}
                                @endif
                            </p>
                        </div>
                        <div class="pills">
                            <span class="pill {{ $appointment->status }}">{{ str_replace('_', ' ', $appointment->status) }}</span>
                            <span class="pill {{ $appointment->payment_status }}">{{ str_replace('_', ' ', $appointment->payment_status) }}</span>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-box"><span>Fecha</span>{{ $appointment->appointment_date }}</div>
                        <div class="info-box"><span>Horario</span>{{ $appointment->start_time }} - {{ $appointment->end_time }}</div>
                        <div class="info-box"><span>Precio</span>${{ number_format((float) $appointment->service_price, 2) }}</div>
                        <div class="info-box"><span>Adelanto</span>${{ number_format((float) $appointment->advance_amount, 2) }}</div>
                        <div class="info-box"><span>Porcentaje</span>{{ number_format((float) $appointment->advance_percentage, 0) }}%</div>
                        <div class="info-box"><span>Notas</span>{{ $appointment->notes ?: 'Sin notas registradas' }}</div>
                    </div>

                    <div class="actions">
                        <a class="button ghost" href="{{ route('appointments.edit', $appointment) }}">Editar</a>
                        @if (($user->isAdmin() || $user->isBusiness()) && $appointment->status === 'pending')
                            <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button class="confirm" type="submit">Confirmar</button>
                            </form>
                        @endif
                        @if (($user->isAdmin() || $user->isBusiness()) && $appointment->status === 'confirmed')
                            <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button class="confirm" type="submit">Completar</button>
                            </form>
                        @endif
                        @if (($user->isAdmin() || $user->isBusiness()) && $appointment->payment_status !== 'paid')
                            <form method="POST" action="{{ route('appointments.payment', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="payment_status" value="paid">
                                <button class="info" type="submit">Marcar pago</button>
                            </form>
                        @endif
                        @if ((($user->isAdmin() || $user->isBusiness()) || $appointment->user_id === $user->id) && !in_array($appointment->status, ['cancelled', 'completed']))
                            <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button class="warn" type="submit">Cancelar</button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <article class="card">
                    <h2 style="margin-top:0;">Todavía no hay citas registradas</h2>
                    <p class="muted">Cuando se creen reservas, aparecerán aquí con su estado, pago y acciones disponibles.</p>
                </article>
            @endforelse
        </div>
    </section>
</main>
</body>
</html>
