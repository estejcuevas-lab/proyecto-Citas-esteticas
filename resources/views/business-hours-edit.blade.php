<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar horario</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f7f2ec; color: #2a211c; }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel { max-width: 720px; margin: 0 auto; background: #fff; border: 1px solid #dccab8; border-radius: 20px; padding: 2rem; }
        label, input, select { display: block; width: 100%; }
        label { margin-top: 1rem; margin-bottom: 0.45rem; font-weight: 700; }
        input, select { box-sizing: border-box; padding: 0.85rem 1rem; border-radius: 12px; border: 1px solid #bca58f; }
        .checkbox { display: flex; gap: 0.75rem; align-items: center; margin-top: 1rem; }
        .checkbox input { width: auto; }
        .actions { display: flex; gap: 1rem; margin-top: 1.5rem; }
        .button, button { display: inline-block; text-decoration: none; padding: 0.9rem 1rem; border-radius: 12px; border: 0; background: #6a4730; color: white; font-weight: 700; cursor: pointer; }
        .secondary { background: #efe1d4; color: #2a211c; }
        .status { margin-top: 1rem; color: #1f6b36; font-weight: 700; }
        .error-list { margin-top: 1rem; color: #a11a1a; }
    </style>
</head>
<body>
<main class="shell">
    <section class="panel">
        <h1>Editar horario de {{ $business->name }}</h1>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('businesses.hours.update', [$business, $hour]) }}">
            @csrf
            @method('PUT')

            <label for="day_of_week">Dia</label>
            <select id="day_of_week" name="day_of_week" required>
                @foreach ($days as $key => $day)
                    <option value="{{ $key }}" @selected(old('day_of_week', $hour->day_of_week) == $key)>{{ $day }}</option>
                @endforeach
            </select>

            <label for="opens_at">Hora de apertura</label>
            <input id="opens_at" name="opens_at" type="time" value="{{ old('opens_at', $hour->opens_at) }}" required>

            <label for="closes_at">Hora de cierre</label>
            <input id="closes_at" name="closes_at" type="time" value="{{ old('closes_at', $hour->closes_at) }}" required>

            <label class="checkbox" for="is_active">
                <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $hour->is_active))>
                Dia habilitado para citas
            </label>

            @if ($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="actions">
                <button type="submit">Guardar cambios</button>
                <a class="button secondary" href="{{ route('businesses.hours.index', $business) }}">Volver al listado</a>
            </div>
        </form>
    </section>
</main>
</body>
</html>
