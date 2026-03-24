<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro</title>
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
            width: min(520px, 100%);
            background: #fffaf5;
            border: 1px solid #d6c1ad;
            border-radius: 18px;
            padding: 2rem;
        }

        label, input, select {
            display: block;
            width: 100%;
        }

        label {
            margin-top: 1rem;
            margin-bottom: 0.45rem;
            font-weight: 700;
        }

        input, select {
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

        .error-list {
            margin-top: 1rem;
            color: #a11a1a;
        }
    </style>
</head>
<body>
    <main class="wrap">
        <section class="panel">
            <h1>Crear cuenta</h1>
            <p>Selecciona el perfil con el que vas a usar el sistema.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <label for="name">Nombre</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>

                <label for="email">Correo electronico</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>

                <label for="role">Rol</label>
                <select id="role" name="role" required>
                    <option value="client" @selected(old('role') === 'client')>Cliente</option>
                    <option value="business" @selected(old('role') === 'business')>Negocio</option>
                </select>

                <label for="password">Contrasena</label>
                <input id="password" name="password" type="password" required>

                <label for="password_confirmation">Confirmar contrasena</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>

                @if ($errors->any())
                    <div class="error-list">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <button class="submit" type="submit">Registrarme</button>
            </form>
        </section>
    </main>
</body>
</html>
