<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Citas</title>
    <style>
        :root {
            --bg: #f4efe8;
            --panel: rgba(255, 250, 245, 0.92);
            --panel-strong: #fffaf5;
            --line: #d8c4af;
            --text: #2f241f;
            --muted: #776655;
            --brand: #6d4c35;
            --brand-deep: #4d3322;
            --soft: #efe2d6;
            --shadow: 0 24px 50px rgba(84, 58, 39, 0.14);
            --accent: #c98e57;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background:
                radial-gradient(circle at top left, rgba(201, 142, 87, 0.22), transparent 22%),
                linear-gradient(135deg, #f5efe6 0%, #e8d8c4 100%);
            color: var(--text);
        }
        .page {
            min-height: 100vh;
            padding: 2rem;
        }
        .shell {
            max-width: 1180px;
            margin: 0 auto;
            display: grid;
            gap: 1.5rem;
        }
        .hero, .catalog {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
        }
        .hero {
            padding: 2rem;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 1.25rem;
        }
        .hero h1 {
            margin: .25rem 0 1rem;
            font-size: clamp(2.2rem, 5vw, 4rem);
            line-height: 1.02;
        }
        .eyebrow {
            display: inline-block;
            padding: .45rem .8rem;
            border-radius: 999px;
            background: #f3e3d1;
            color: var(--brand-deep);
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            font-size: .8rem;
        }
        .muted {
            color: var(--muted);
            line-height: 1.7;
            font-size: 1.02rem;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: .9rem;
            margin-top: 1.8rem;
        }
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            padding: .9rem 1.3rem;
            border-radius: 999px;
            border: 1px solid var(--brand);
            color: var(--text);
            font-weight: 700;
        }
        .button.primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            color: #fff7f1;
        }
        .hero-side {
            background: linear-gradient(155deg, rgba(109, 76, 53, 0.97), rgba(77, 51, 34, 0.92));
            color: #fff8f2;
            border-radius: 24px;
            padding: 1.6rem;
        }
        .hero-side h2 {
            margin-top: 0;
            font-size: 1.2rem;
        }
        .hero-side p {
            color: rgba(255, 248, 242, 0.82);
            line-height: 1.7;
        }
        .hero-side ul {
            margin: 1rem 0 0;
            padding-left: 1.1rem;
            color: rgba(255, 248, 242, 0.92);
        }
        .catalog {
            padding: 1.8rem;
        }
        .catalog-header {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: end;
            flex-wrap: wrap;
            margin-bottom: 1.4rem;
        }
        .catalog-header h2 {
            margin: 0;
            font-size: clamp(1.6rem, 3vw, 2.4rem);
        }
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
            gap: 1rem;
        }
        .business-card {
            background: var(--panel-strong);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 1.25rem;
        }
        .business-card h3 {
            margin: 0 0 .4rem;
            font-size: 1.45rem;
        }
        .type-pill {
            display: inline-flex;
            padding: .35rem .7rem;
            border-radius: 999px;
            background: #f2e4d7;
            color: var(--brand-deep);
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .description {
            margin: .9rem 0 1rem;
            color: var(--muted);
            line-height: 1.65;
        }
        .meta {
            display: grid;
            gap: .55rem;
            margin-bottom: 1rem;
        }
        .meta span {
            color: var(--muted);
            font-size: .95rem;
        }
        details {
            border-radius: 18px;
            border: 1px solid #e5d8ca;
            background: #fcf8f4;
            overflow: hidden;
        }
        summary {
            cursor: pointer;
            list-style: none;
            padding: .95rem 1rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        summary::-webkit-details-marker { display: none; }
        .service-list {
            padding: 0 1rem 1rem;
            display: grid;
            gap: .75rem;
        }
        .service-item {
            padding: .9rem;
            border-radius: 16px;
            background: #fffdf9;
            border: 1px solid #efe3d8;
        }
        .service-item strong {
            display: block;
            margin-bottom: .25rem;
        }
        .empty {
            padding: 1.35rem;
            border-radius: 20px;
            border: 1px dashed var(--line);
            background: #fbf6f1;
            color: var(--muted);
        }
        @media (max-width: 900px) {
            .page { padding: 1rem; }
            .hero { grid-template-columns: 1fr; padding: 1.3rem; }
            .catalog { padding: 1.3rem; }
        }
    </style>
</head>
<body>
    <main class="page">
        <div class="shell">
            <section class="hero">
                <article>
                    <span class="eyebrow">Sistema multinegocio</span>
                    <h1>Encuentra negocios, revisa tratamientos y agenda con una experiencia más clara.</h1>
                    <p class="muted">
                        La portada ahora sirve como vitrina para el cliente: muestra los negocios registrados,
                        una descripción rápida de cada uno y los tratamientos disponibles antes de iniciar sesión.
                    </p>

                    <div class="actions">
                        @auth
                            <a class="button primary" href="{{ route('dashboard') }}">Ir al dashboard</a>
                        @else
                            <a class="button primary" href="{{ route('login') }}">Iniciar sesión</a>
                            <a class="button" href="{{ route('register') }}">Crear cuenta</a>
                        @endauth
                    </div>
                </article>

                <aside class="hero-side">
                    <h2>Qué puede hacer el cliente aquí</h2>
                    <p>Antes de reservar, ya puede explorar la oferta real del sistema y tomar una decisión más informada.</p>
                    <ul>
                        <li>Ver negocios disponibles.</li>
                        <li>Leer una descripción rápida de cada atención.</li>
                        <li>Desplegar tratamientos activos por negocio.</li>
                    </ul>
                </aside>
            </section>

            <section class="catalog">
                <div class="catalog-header">
                    <div>
                        <h2>Negocios registrados</h2>
                        <p class="muted" style="margin: .35rem 0 0;">Cada tarjeta resume el perfil del negocio y permite desplegar sus tratamientos activos.</p>
                    </div>
                    <span class="type-pill">{{ $businesses->count() }} disponibles</span>
                </div>

                @if ($businesses->isEmpty())
                    <div class="empty">
                        Todavía no hay negocios registrados para mostrar en portada. Cuando se creen, aparecerán aquí con sus tratamientos.
                    </div>
                @else
                    <div class="catalog-grid">
                        @foreach ($businesses as $business)
                            <article class="business-card">
                                <span class="type-pill">{{ $business->type }}</span>
                                <h3>{{ $business->name }}</h3>

                                <p class="description">
                                    {{ $business->name }} es un negocio del tipo {{ strtolower($business->type) }}
                                    @if ($business->address)
                                        ubicado en {{ $business->address }}
                                    @endif
                                    @if ($business->phone || $business->email)
                                        , con canales de contacto activos para coordinar la atención
                                    @endif.
                                </p>

                                <div class="meta">
                                    @if ($business->address)
                                        <span><strong>Dirección:</strong> {{ $business->address }}</span>
                                    @endif
                                    @if ($business->phone)
                                        <span><strong>Teléfono:</strong> {{ $business->phone }}</span>
                                    @endif
                                    @if ($business->email)
                                        <span><strong>Correo:</strong> {{ $business->email }}</span>
                                    @endif
                                </div>

                                <details>
                                    <summary>
                                        <span>Tratamientos disponibles</span>
                                        <span>{{ $business->services->count() }}</span>
                                    </summary>

                                    <div class="service-list">
                                        @forelse ($business->services as $service)
                                            <div class="service-item">
                                                <strong>{{ $service->name }}</strong>
                                                <span class="muted">
                                                    Duración: {{ $service->duration_minutes }} min ·
                                                    Precio: ${{ number_format((float) $service->price, 2) }}
                                                </span>
                                                @if ($service->description)
                                                    <p class="muted" style="margin: .55rem 0 0;">{{ $service->description }}</p>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="service-item">
                                                <span class="muted">Este negocio aún no tiene tratamientos activos registrados.</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </details>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </main>
</body>
</html>
