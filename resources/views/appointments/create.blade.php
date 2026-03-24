<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cita</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f7f2ec; color: #2a211c; }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel { max-width: 760px; margin: 0 auto; background: #fff; border: 1px solid #dccab8; border-radius: 20px; padding: 2rem; }
        label, input, select, textarea { display: block; width: 100%; }
        label { margin-top: 1rem; margin-bottom: 0.45rem; font-weight: 700; }
        input, select, textarea { box-sizing: border-box; padding: 0.85rem 1rem; border-radius: 12px; border: 1px solid #bca58f; }
        textarea { min-height: 120px; resize: vertical; }
        .actions { display: flex; gap: 1rem; margin-top: 1.5rem; }
        .button, button { display: inline-block; text-decoration: none; padding: 0.9rem 1rem; border-radius: 12px; border: 0; background: #6a4730; color: white; font-weight: 700; cursor: pointer; }
        .secondary { background: #efe1d4; color: #2a211c; }
        .error-list { margin-top: 1rem; color: #a11a1a; }
    </style>
</head>
<body>
<main class="shell">
    <section class="panel">
        <h1>Registrar cita</h1>
        <p>Esta pantalla cubre el flujo de reserva entre cliente y servidor mientras seguimos construyendo disponibilidad avanzada.</p>

        <form method="POST" action="{{ route('appointments.store') }}">
            @csrf

            <label for="business_id">Negocio</label>
            <select id="business_id" name="business_id" required>
                <option value="">Selecciona un negocio</option>
                @foreach ($businesses as $business)
                    <option value="{{ $business->id }}" @selected(old('business_id') == $business->id)>{{ $business->name }}</option>
                @endforeach
            </select>

            <label for="service_id">Servicio</label>
            <select id="service_id" name="service_id" required>
                <option value="">Selecciona un servicio</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                        {{ $service->name }} - {{ $service->business->name }}
                    </option>
                @endforeach
            </select>

            <label for="appointment_date">Fecha</label>
            <input id="appointment_date" name="appointment_date" type="date" value="{{ old('appointment_date') }}" required>

            <label for="start_time">Hora de inicio</label>
            <input id="start_time" name="start_time" type="time" value="{{ old('start_time') }}" required>

            <label for="end_time">Hora de finalizacion</label>
            <input id="end_time" name="end_time" type="time" value="{{ old('end_time') }}" required>

            <label for="status">Estado</label>
            <select id="status" name="status" required>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $user->isClient() ? 'pending' : 'confirmed') === $status)>{{ $status }}</option>
                @endforeach
            </select>

            <label for="notes">Notas</label>
            <textarea id="notes" name="notes">{{ old('notes') }}</textarea>

            @if ($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="actions">
                <button type="submit">Guardar cita</button>
                <a class="button secondary" href="{{ route('appointments.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
</main>
</body>
</html>
