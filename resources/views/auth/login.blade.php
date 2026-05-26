<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresar a citas-app</title>
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

        .field-list {
            display: grid;
            gap: 1rem;
        }

        label {
            display: grid;
            gap: 0.45rem;
            font-weight: 700;
        }

        input {
            width: 100%;
            border: 1px solid #c7b29f;
            border-radius: 14px;
            padding: 0.9rem 1rem;
            font: inherit;
            background: #fffdf9;
            color: var(--text);
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
                        <span class="eyebrow">Login first</span>
                        <h1 class="page-title">Ingresa a citas-app desde Google o continua con tu acceso clasico.</h1>
                        <p class="muted">
                            La entrada principal ahora prioriza Google OAuth para clientes y futuros negocios.
                            Si eres nuevo, entraras al flujo de onboarding antes de usar el panel.
                        </p>

                        <div class="actions" style="margin-top: 1.5rem;">
                            <a class="btn btn-secondary" href="{{ route('auth.google.redirect') }}">Continuar con Google</a>
                            <a class="btn btn-secondary" href="{{ route('public.businesses.index') }}">Explorar negocios</a>
                        </div>

                        <div class="card" style="margin-top: 1.5rem; background: rgba(255, 250, 245, 0.18); border-color: rgba(255, 248, 243, 0.22);">
                            <strong>Flujos principales</strong>
                            <p class="muted" style="margin: 0.75rem 0 0;">
                                Cliente nuevo: Google -> completar perfil -> dashboard.<br>
                                Negocio nuevo: Google -> solicitar acceso -> aprobacion admin -> panel business.
                            </p>
                        </div>
                    </article>

                    <aside class="card">
                        <span class="eyebrow">Acceso alterno</span>
                        <h2 class="section-title" style="margin-top: 0.9rem;">Email y contrasena</h2>
                        <p class="muted" style="margin-bottom: 0;">
                            Este acceso se mantiene como fallback para cuentas existentes y pruebas internas.
                        </p>

                        @if ($errors->any())
                            <div class="flash flash-error" style="margin-top: 1rem;">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" style="margin-top: 1.25rem;">
                            @csrf

                            <div class="field-list">
                                <label for="email">
                                    Correo electronico
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                                </label>

                                <label for="password">
                                    Contrasena
                                    <input id="password" name="password" type="password" required>
                                </label>
                            </div>

                            <label for="remember" style="margin-top: 1rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                                <input id="remember" name="remember" type="checkbox" value="1" style="width: auto; margin: 0;">
                                Recordarme
                            </label>

                            <div class="actions" style="margin-top: 1.25rem;">
                                <button class="btn btn-primary" type="submit">Entrar</button>
                                <a class="btn btn-secondary" href="{{ route('register') }}">Crear cuenta cliente</a>
                            </div>
                        </form>
                    </aside>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
