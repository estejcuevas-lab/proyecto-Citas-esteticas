<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar cita</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f7f2ec; color: #2a211c; }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel { max-width: 760px; margin: 0 auto; background: #fff; border: 1px solid #dccab8; border-radius: 20px; padding: 2rem; }
        label, input, select, textarea { display: block; width: 100%; }
        label { margin-top: 1rem; margin-bottom: 0.45rem; font-weight: 700; }
        input, select, textarea { box-sizing: border-box; padding: 0.85rem 1rem; border-radius: 12px; border: 1px solid #bca58f; }
        textarea { min-height: 120px; resize: vertical; }
        .hint, .schedule { margin-top: 0.75rem; color: #6d5b4d; }
        .schedule { padding: 1rem; border-radius: 12px; background: #f4ebe1; }
        .summary { margin-top: 1rem; padding: 1rem; border-radius: 12px; background: #efe1d4; font-weight: 700; }
        .actions { display: flex; gap: 1rem; margin-top: 1.5rem; }
        .button, button { display: inline-block; text-decoration: none; padding: 0.9rem 1rem; border-radius: 12px; border: 0; background: #6a4730; color: white; font-weight: 700; cursor: pointer; }
        .secondary { background: #efe1d4; color: #2a211c; }
        .status-banner { margin-top: 1rem; color: #1f6b36; font-weight: 700; }
        .error-list { margin-top: 1rem; color: #a11a1a; }
    </style>
</head>
<body>
<main class="shell">
    <section class="panel">
        <h1>Editar cita</h1>
        <p>Desde aqui puedes actualizar la reserva usando el horario del negocio y la duracion del servicio para validar la agenda.</p>

        @if (session('status'))
            <div class="status-banner">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('appointments.update', $appointment) }}">
            @csrf
            @method('PUT')

            <label for="business_id">Negocio</label>
            <select id="business_id" name="business_id" required>
                @foreach ($businesses as $business)
                    <option value="{{ $business->id }}" @selected(old('business_id', $appointment->business_id) == $business->id)>{{ $business->name }}</option>
                @endforeach
            </select>

            <div class="schedule" id="business-schedule">Selecciona un negocio para ver sus horarios disponibles.</div>

            <label for="service_id">Servicio</label>
            <select id="service_id" name="service_id" required>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" data-business-id="{{ $service->business_id }}" data-duration="{{ $service->duration_minutes }}" @selected(old('service_id', $appointment->service_id) == $service->id)>
                        {{ $service->name }} - {{ $service->business->name }}
                    </option>
                @endforeach
            </select>
            <div class="hint">Solo se mostraran los servicios del negocio seleccionado.</div>

            <label for="appointment_date">Fecha</label>
            <input id="appointment_date" name="appointment_date" type="date" value="{{ old('appointment_date', $appointment->appointment_date) }}" required>

            <label for="start_time">Hora de inicio</label>
            <input id="start_time" name="start_time" type="time" value="{{ old('start_time', $appointment->start_time) }}" required>

            <div class="summary" id="appointment-summary">La hora de finalizacion se recalcula automaticamente a partir de la hora inicial y la duracion del servicio.</div>

            <label for="status">Estado</label>
            <select id="status" name="status" required>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $appointment->status) === $status)>{{ $status }}</option>
                @endforeach
            </select>

            <label for="notes">Notas</label>
            <textarea id="notes" name="notes">{{ old('notes', $appointment->notes) }}</textarea>

            @if ($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="actions">
                <button type="submit">Guardar cambios</button>
                <a class="button secondary" href="{{ route('appointments.index') }}">Volver al listado</a>
            </div>
        </form>
    </section>
</main>
<script>
    const businessSelect = document.getElementById('business_id');
    const serviceSelect = document.getElementById('service_id');
    const startTimeInput = document.getElementById('start_time');
    const scheduleBox = document.getElementById('business-schedule');
    const summaryBox = document.getElementById('appointment-summary');
    const serviceOptions = Array.from(serviceSelect.querySelectorAll('option[data-business-id]'));
    const schedules = @json(
        $businesses->mapWithKeys(fn ($business) => [
            $business->id => $business->hours->map(fn ($hour) => [
                'day' => $dayOptions[$hour->day_of_week],
                'opens_at' => $hour->opens_at,
                'closes_at' => $hour->closes_at,
                'is_active' => $hour->is_active,
            ])->values(),
        ])
    );

    function updateServices() {
        const selectedBusinessId = businessSelect.value;
        const currentServiceId = serviceSelect.value;

        serviceOptions.forEach((option) => {
            const visible = !selectedBusinessId || option.dataset.businessId === selectedBusinessId;
            option.hidden = !visible;
            option.disabled = !visible;
        });

        const selectedOption = serviceSelect.querySelector(`option[value="${currentServiceId}"]`);

        if (!selectedOption || selectedOption.hidden) {
            serviceSelect.value = '';
        } else {
            serviceSelect.value = currentServiceId;
        }
    }

    function updateSchedule() {
        const selectedBusinessId = businessSelect.value;
        const hours = schedules[selectedBusinessId] || [];

        if (!selectedBusinessId) {
            scheduleBox.textContent = 'Selecciona un negocio para ver sus horarios disponibles.';
            return;
        }

        if (!hours.length) {
            scheduleBox.textContent = 'Este negocio todavia no tiene horarios configurados.';
            return;
        }

        scheduleBox.innerHTML = hours
            .map((hour) => `${hour.day}: ${hour.is_active ? `${hour.opens_at} - ${hour.closes_at}` : 'No disponible'}`)
            .join('<br>');
    }

    function updateSummary() {
        const selectedOption = serviceSelect.selectedOptions[0];
        const startTime = startTimeInput.value;

        if (!selectedOption || !selectedOption.dataset.duration || !startTime) {
            summaryBox.textContent = 'La hora de finalizacion se recalcula automaticamente a partir de la hora inicial y la duracion del servicio.';
            return;
        }

        const [hours, minutes] = startTime.split(':').map(Number);
        const duration = Number(selectedOption.dataset.duration);
        const date = new Date();
        date.setHours(hours, minutes + duration, 0, 0);

        const endHours = String(date.getHours()).padStart(2, '0');
        const endMinutes = String(date.getMinutes()).padStart(2, '0');

        summaryBox.textContent = `Duracion del servicio: ${duration} minutos. Hora estimada de finalizacion: ${endHours}:${endMinutes}.`;
    }

    businessSelect.addEventListener('change', () => {
        updateServices();
        updateSchedule();
        updateSummary();
    });
    serviceSelect.addEventListener('change', updateSummary);
    startTimeInput.addEventListener('input', updateSummary);

    updateServices();
    updateSchedule();
    updateSummary();
</script>
</body>
</html>
