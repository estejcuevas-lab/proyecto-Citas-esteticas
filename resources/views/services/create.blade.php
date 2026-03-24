<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear servicio</title>
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

        label, input, textarea {
            display: block;
            width: 100%;
        }

        label {
            margin-top: 1rem;
            margin-bottom: 0.45rem;
            font-weight: 700;
        }

        input, textarea {
            box-sizing: border-box;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            border: 1px solid #bca58f;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .checkbox {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-top: 1rem;
        }

        .checkbox input {
            width: auto;
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

        .error-list {
            margin-top: 1rem;
            color: #a11a1a;
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="panel">
            <h1>Crear servicio para {{ $business->name }}</h1>
            <p>Los servicios representan la oferta real del negocio y su duracion se usa luego para la agenda.</p>

            <form method="POST" action="{{ route('businesses.services.store', $business) }}">
                @csrf

                <label for="name">Nombre del servicio</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>

                <label for="description">Descripcion</label>
                <textarea id="description" name="description">{{ old('description') }}</textarea>

                <label for="duration_minutes">Duracion en minutos</label>
                <input id="duration_minutes" name="duration_minutes" type="number" min="15" max="480" step="15" value="{{ old('duration_minutes', 60) }}" required>

                <label for="price">Precio</label>
                <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', 0) }}" required>

                <label class="checkbox" for="active">
                    <input id="active" name="active" type="checkbox" value="1" @checked(old('active', true))>
                    Servicio activo
                </label>

                @if ($errors->any())
                    <div class="error-list">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="actions">
                    <button type="submit">Guardar servicio</button>
                    <a class="button secondary" href="{{ route('businesses.services.index', $business) }}">Cancelar</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
