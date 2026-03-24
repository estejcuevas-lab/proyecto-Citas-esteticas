<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Citas</title>
    <style>
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background: linear-gradient(135deg, #f5efe6 0%, #e8d8c4 100%);
            color: #2f241f;
        }

        .page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem;
        }

        .card {
            width: min(720px, 100%);
            background: rgba(255, 250, 245, 0.9);
            border: 1px solid #b99874;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 50px rgba(84, 58, 39, 0.15);
        }

        h1 {
            margin-top: 0;
            font-size: clamp(2rem, 4vw, 3.5rem);
        }

        p {
            line-height: 1.6;
            font-size: 1.05rem;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
        }

        .button {
            display: inline-block;
            text-decoration: none;
            padding: 0.85rem 1.3rem;
            border-radius: 999px;
            border: 1px solid #6d4c35;
            color: #2f241f;
            font-weight: 700;
        }

        .button.primary {
            background: #6d4c35;
            color: #fff7f1;
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="card">
            <p>Sistema de gestion de citas multinegocio</p>
            <h1>Base funcional para registro, acceso y control por perfiles.</h1>
            <p>
                Esta pantalla ya sirve como entrada del proyecto y da soporte a la parte
                Cliente-Servidor: el cliente navega, envia formularios y el servidor valida,
                autentica y protege rutas.
            </p>

            <div class="actions">
                @auth
                    <a class="button primary" href="{{ route('dashboard') }}">Ir al dashboard</a>
                @else
                    <a class="button primary" href="{{ route('login') }}">Iniciar sesion</a>
                    <a class="button" href="{{ route('register') }}">Crear cuenta</a>
                @endauth
            </div>
        </section>
    </main>
</body>
</html>
