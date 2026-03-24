<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesion</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3ede6;
            color: #2a211c;
        }

        .wrap {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem;
        }

        .panel {
            width: min(460px, 100%);
            background: #fffaf5;
            border: 1px solid #d6c1ad;
            border-radius: 18px;
            padding: 2rem;
        }

        label, input {
            display: block;
            width: 100%;
        }

        label {
            margin-top: 1rem;
            margin-bottom: 0.45rem;
            font-weight: 700;
        }

        input {
            box-sizing: border-box;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            border: 1px solid #bca58f;
        }

        .submit {
            margin-top: 1.5rem;
            width: 100%;
            padding: 0.95rem 1rem;
            border: 0;
            border-radius: 12px;
            background: #6a4730;
            color: white;
            font-weight: 700;
            cursor: pointer;
        }

        .error {
            margin-top: 1rem;
            color: #a11a1a;
        }

        .links {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <main class="wrap">
        <section class="panel">
            <h1>Iniciar sesion</h1>
            <p>Accede al sistema para administrar tus funciones segun el rol asignado.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label for="email">Correo electronico</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

                <label for="password">Contrasena</label>
                <input id="password" name="password" type="password" required>

                <label for="remember">
                    <input id="remember" name="remember" type="checkbox" value="1">
                    Recordarme
                </label>

                @if ($errors->any())
                    <div class="error">{{ $errors->first() }}</div>
                @endif

                <button class="submit" type="submit">Entrar</button>
            </form>

            <div class="links">
                <a href="{{ route('register') }}">Crear una cuenta</a>
            </div>
        </section>
    </main>
</body>
</html>
