<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar negocio</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f2ec;
            color: #2a211c;
        }

        .shell {
            min-height: 100vh;
            padding: 2rem;
        }

        .panel {
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #dccab8;
            border-radius: 20px;
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

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .button, button {
            display: inline-block;
            text-decoration: none;
            padding: 0.9rem 1rem;
            border-radius: 12px;
            border: 0;
            background: #6a4730;
            color: white;
            font-weight: 700;
            cursor: pointer;
        }

        .secondary {
            background: #efe1d4;
            color: #2a211c;
        }

        .status {
            margin-top: 1rem;
            color: #1f6b36;
            font-weight: 700;
        }

        .error-list {
            margin-top: 1rem;
            color: #a11a1a;
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="panel">
            <h1>Editar negocio</h1>
            <p>Actualiza la informacion base del negocio para seguir construyendo agenda, servicios y citas.</p>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('businesses.update', $business) }}">
                @csrf
                @method('PUT')

                <label for="name">Nombre del negocio</label>
                <input id="name" name="name" type="text" value="{{ old('name', $business->name) }}" required>

                <label for="type">Tipo de negocio</label>
                <select id="type" name="type" required>
                    <option value="barberia" @selected(old('type', $business->type) === 'barberia')>Barberia</option>
                    <option value="estetica" @selected(old('type', $business->type) === 'estetica')>Estetica</option>
                    <option value="odontologia" @selected(old('type', $business->type) === 'odontologia')>Odontologia</option>
                    <option value="consultorio" @selected(old('type', $business->type) === 'consultorio')>Consultorio</option>
                </select>

                <label for="email">Correo</label>
                <input id="email" name="email" type="email" value="{{ old('email', $business->email) }}">

                <label for="phone">Telefono</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $business->phone) }}">

                <label for="address">Direccion</label>
                <input id="address" name="address" type="text" value="{{ old('address', $business->address) }}">

                @if ($errors->any())
                    <div class="error-list">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="actions">
                    <button type="submit">Guardar cambios</button>
                    <a class="button secondary" href="{{ route('businesses.index') }}">Volver al listado</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
