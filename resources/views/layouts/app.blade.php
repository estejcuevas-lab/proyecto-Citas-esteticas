<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'citas-app')</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

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

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(153, 75, 53, 0.15), transparent 24%),
                linear-gradient(180deg, #fbf7f2 0%, var(--bg) 100%);
        }

        a {
            color: inherit;
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

        .grid {
            display: grid;
            gap: 1rem;
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

        .flash-list {
            display: grid;
            gap: 0.85rem;
            margin-bottom: 1rem;
        }

        .flash {
            padding: 0.95rem 1rem;
            border-radius: 16px;
            font-weight: 700;
            border: 1px solid transparent;
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

        .field-list {
            display: grid;
            gap: 1rem;
        }

        label {
            display: grid;
            gap: 0.45rem;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid #c7b29f;
            border-radius: 14px;
            padding: 0.9rem 1rem;
            font: inherit;
            background: #fffdf9;
            color: var(--text);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
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

        .link-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.85rem;
        }

        .empty-state {
            padding: 1.25rem;
            border-radius: 20px;
            border: 1px dashed var(--line);
            background: #fcf8f4;
            color: var(--muted);
        }

        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 900px) {
            .hero-grid,
            .two-col {
                grid-template-columns: 1fr;
            }

            .surface {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body style="@yield('theme_style')">
    <main class="page-shell">
        <div class="page-width">
            <div class="flash-list">
                @if (session('status'))
                    <div class="flash flash-success">{{ session('status') }}</div>
                @endif

                @if (session('error'))
                    <div class="flash flash-error">{{ session('error') }}</div>
                @endif
            </div>

            @yield('content')
        </div>
    </main>
</body>
</html>
