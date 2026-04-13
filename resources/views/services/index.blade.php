<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servicios</title>
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
            --shadow: 0 20px 50px rgba(87, 56, 36, 0.14);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background: linear-gradient(180deg, #f8f4ef 0%, var(--bg) 100%);
            color: var(--text);
        }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel {
            max-width: 1120px;
            margin: 0 auto;
            padding: 2rem;
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: start;
            margin-bottom: 1.5rem;
        }
        h1 { margin: 0; font-size: clamp(2rem, 4vw, 3rem); }
        .muted { color: var(--muted); line-height: 1.6; }
        .button-row { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .button {
            text-decoration: none;
            padding: 0.9rem 1.1rem;
            border-radius: 14px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .button.primary { background: linear-gradient(135deg, var(--brand), var(--brand-deep)); color: #fffaf5; }
        .button.secondary { background: var(--soft); color: var(--text); }
        .status-banner {
            margin-bottom: 1rem;
            padding: 0.95rem 1rem;
            border-radius: 16px;
            background: var(--success-bg);
            color: var(--success-text);
            font-weight: 700;
        }
        .list { display: grid; gap: 1rem; }
        .card {
            padding: 1.25rem;
            border-radius: 22px;
            border: 1px solid var(--line);
            background: var(--panel-strong);
        }
        .row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: start;
            flex-wrap: wrap;
        }
        .meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.85rem;
            margin-top: 1rem;
        }
        .meta-box {
            padding: 0.95rem;
            border-radius: 18px;
            background: #fcf8f4;
            border: 1px solid #eadfd4;
        }
        .meta-box span {
            display: block;
            color: var(--muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.35rem;
        }
        .pill {
            display: inline-flex;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .pill.active { background: var(--success-bg); color: var(--success-text); }
        .pill.inactive { background: var(--warn-bg); color: var(--warn-text); }
        @media (max-width: 900px) {
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
                    <h1>Servicios de {{ $business->name }}</h1>
                    <p class="muted">Controla duración, precio y estado operativo de la oferta del negocio.</p>
                </div>
                <div class="button-row">
                    <a class="button secondary" href="{{ route('businesses.index') }}">Volver a negocios</a>
                    <a class="button primary" href="{{ route('businesses.services.create', $business) }}">Nuevo servicio</a>
                </div>
            </div>

            @if (session('status'))
                <div class="status-banner">{{ session('status') }}</div>
            @endif

            <div class="list">
                @forelse ($services as $service)
                    <article class="card">
                        <div class="row">
                            <div>
                                <h2 style="margin:0 0 .35rem;">{{ $service->name }}</h2>
                                <p class="muted" style="margin:0;">{{ $service->description ?: 'Sin descripción registrada' }}</p>
                            </div>
                            <div class="button-row">
                                <span class="pill {{ $service->active ? 'active' : 'inactive' }}">{{ $service->active ? 'Activo' : 'Inactivo' }}</span>
                                <a class="button secondary" href="{{ route('businesses.services.edit', [$business, $service]) }}">Editar</a>
                            </div>
                        </div>
                        <div class="meta">
                            <div class="meta-box"><span>Duración</span>{{ $service->duration_minutes }} minutos</div>
                            <div class="meta-box"><span>Precio</span>${{ number_format((float) $service->price, 2) }}</div>
                        </div>
                    </article>
                @empty
                    <article class="card">
                        <h2 style="margin-top:0;">Todavía no hay servicios registrados</h2>
                        <p class="muted">Cuando agregues servicios, aquí verás su duración, precio y estado operativo.</p>
                    </article>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
