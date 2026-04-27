<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar cita</title>
    <style>
        :root {
            --bg: #f4efe8;
            --panel: rgba(255, 251, 247, 0.94);
            --panel-strong: #fffaf5;
            --line: #ddcdbd;
            --text: #2f241c;
            --muted: #776655;
            --brand: #994b35;
            --brand-deep: #6a2d1e;
            --soft: #efe2d6;
            --success-bg: #e1f4e5;
            --success-text: #245639;
            --danger-bg: #f8dede;
            --danger-text: #7a2430;
            --shadow: 0 20px 50px rgba(87, 56, 36, 0.14);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            color: var(--text);
            background:
                radial-gradient(circle at left top, rgba(217, 163, 95, 0.24), transparent 24%),
                linear-gradient(180deg, #f8f4ef 0%, var(--bg) 100%);
        }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel {
            max-width: 1080px;
            margin: 0 auto;
            padding: 2rem;
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: start;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        h1 { margin: 0; font-size: clamp(2rem, 4vw, 3rem); }
        .muted { color: var(--muted); line-height: 1.6; }
        .layout {
            display: grid;
            grid-template-columns: 1.25fr 0.85fr;
            gap: 1.2rem;
        }
        .form-card, .info-card {
            border-radius: 24px;
            border: 1px solid var(--line);
            background: var(--panel-strong);
        }
        .form-card { padding: 1.5rem; }
        .info-card { padding: 1.35rem; }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
        .field.full { grid-column: 1 / -1; }
        label { display: block; margin-bottom: .45rem; font-weight: 700; }
        input, select, textarea {
            width: 100%;
            padding: .9rem 1rem;
            border-radius: 14px;
            border: 1px solid #c4ad95;
            background: #fffdfa;
            color: var(--text);
            font: inherit;
        }
        textarea { min-height: 130px; resize: vertical; }
        .hint { margin-top: .45rem; color: var(--muted); font-size: .93rem; }
        .schedule, .summary, .note {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 18px;
            background: #fcf8f4;
            border: 1px solid #eadfd4;
        }
        .summary { background: linear-gradient(135deg, #f4e7d9, #f9f2ea); font-weight: 700; }
        .status-banner {
            margin-bottom: 1rem;
            padding: .95rem 1rem;
            border-radius: 16px;
            background: var(--success-bg);
            color: var(--success-text);
            font-weight: 700;
        }
        .error-list {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 16px;
            background: var(--danger-bg);
            color: var(--danger-text);
            border: 1px solid #efc6ca;
        }
        .button-row {
            display: flex;
            gap: .8rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }
        .button, button {
            appearance: none;
            border: 0;
            border-radius: 14px;
            padding: .9rem 1.1rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        button { background: linear-gradient(135deg, var(--brand), var(--brand-deep)); color: #fffaf5; }
        .button.secondary { background: var(--soft); color: var(--text); }
        .info-card h2 { margin-top: 0; margin-bottom: .6rem; color: var(--brand-deep); }
        @media (max-width: 900px) {
            .layout, .grid { grid-template-columns: 1fr; }
            .shell { padding: 1rem; }
            .panel { padding: 1.25rem; }
        }
    </style>
</head>
<body>
<main class="shell">
    <section class="panel">
        <div class="topbar">
            <div>
                <h1>Editar cita</h1>
                <p class="muted">Actualiza la reserva y revisa de un vistazo qué negocio, servicio, horario y pago están vinculados.</p>
            </div>
            <a class="button secondary" href="{{ route('appointments.index') }}">Volver al listado</a>
        </div>

        @if (session('status'))
            <div class="status-banner">{{ session('status') }}</div>
        @endif

        <div class="layout">
            <article class="form-card">
                <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                    @csrf
                    @method('PUT')
                    <div class="grid">
                        <div class="field full">
                            <label for="business_id">Negocio</label>
                            <select id="business_id" name="business_id" required>
                                @foreach ($businesses as $business)
                                    <option value="{{ $business->id }}" @selected(old('business_id', $appointment->business_id) == $business->id)>{{ $business->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field full">
                            <div class="schedule" id="business-schedule">Selecciona un negocio para ver sus horarios disponibles.</div>
                        </div>
                        <div class="field full">
                            <label for="service_id">Servicio</label>
                            <select id="service_id" name="service_id" required>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" data-business-id="{{ $service->business_id }}" data-duration="{{ $service->duration_minutes }}" data-price="{{ $service->price }}" @selected(old('service_id', $appointment->service_id) == $service->id)>
                                        {{ $service->name }} - {{ $service->business->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="hint">Solo se mostrarán servicios del negocio elegido.</div>
                        </div>
                        <div class="field">
                            <label for="appointment_date">Fecha</label>
                            <input id="appointment_date" name="appointment_date" type="date" value="{{ old('appointment_date', $appointment->appointment_date) }}" required>
                        </div>
                        <div class="field">
                            <label for="start_time">Hora de inicio</label>
                            <input id="start_time" name="start_time" type="time" value="{{ old('start_time', $appointment->start_time) }}" required>
                        </div>
                        <div class="field">
                            <label for="status">Estado</label>
                            <select id="status" name="status" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(old('status', $appointment->status) === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="payment_status">Estado del pago</label>
                            <select id="payment_status" name="payment_status">
                                @foreach ($paymentStatuses as $paymentStatus)
                                    <option value="{{ $paymentStatus }}" @selected(old('payment_status', $appointment->payment_status) === $paymentStatus)>{{ $paymentStatus }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field full">
                            <label for="notes">Notas</label>
                            <textarea id="notes" name="notes">{{ old('notes', $appointment->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="summary" id="appointment-summary">La hora de finalización se recalcula automáticamente a partir de la hora inicial y la duración del servicio.</div>

                    @if ($errors->any())
                        <div class="error-list">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="button-row">
                        <button type="submit">Guardar cambios</button>
                        <a class="button secondary" href="{{ route('appointments.index') }}">Cancelar</a>
                    </div>
                </form>
            </article>

            <aside class="info-card">
                <h2>Estado actual</h2>
                <div class="note">
                    <strong>Servicio actual</strong>
                    <p class="muted">{{ $appointment->service->name }} en {{ $appointment->business->name }}.</p>
                </div>
                <div class="note">
                    <strong>Pago actual</strong>
                    <p class="muted">Adelanto: ${{ number_format((float) $appointment->advance_amount, 2) }} · Estado: {{ $appointment->payment_status }}</p>
                </div>
                <div class="note">
                    <strong>Consejo</strong>
                    <p class="muted">Si cambias el servicio o la hora, el sistema recalcula duración y validaciones de agenda.</p>
                </div>
            </aside>
        </div>
    </section>
</main>
<script>
    const businessSelect = document.getElementById('business_id');
    const serviceSelect = document.getElementById('service_id');
    const startTimeInput = document.getElementById('start_time');
    const scheduleBox = document.getElementById('business-schedule');
    const summaryBox = document.getElementById('appointment-summary');
    const serviceOptions = Array.from(serviceSelect.querySelectorAll('option[data-business-id]'));
    const schedules = @json($businesses->mapWithKeys(function ($business) use ($dayOptions) {
        return [
            $business->id => $business->hours->map(function ($hour) use ($dayOptions) {
                return [
                    'day' => $dayOptions[$hour->day_of_week],
                    'opens_at' => $hour->opens_at,
                    'closes_at' => $hour->closes_at,
                    'is_active' => $hour->is_active,
                ];
            })->values(),
        ];
    })->toArray());
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
            scheduleBox.textContent = 'Este negocio todavía no tiene horarios configurados.';
            return;
        }
        scheduleBox.innerHTML = hours.map((hour) => `${hour.day}: ${hour.is_active ? `${hour.opens_at} - ${hour.closes_at}` : 'No disponible'}`).join('<br>');
    }
    function updateSummary() {
        const selectedOption = serviceSelect.selectedOptions[0];
        const startTime = startTimeInput.value;
        if (!selectedOption || !selectedOption.dataset.duration || !startTime) {
            summaryBox.textContent = 'La hora de finalización se recalcula automáticamente a partir de la hora inicial y la duración del servicio.';
            return;
        }
        const [hours, minutes] = startTime.split(':').map(Number);
        const duration = Number(selectedOption.dataset.duration);
        const price = Number(selectedOption.dataset.price || 0);
        const advanceAmount = (price * 0.5).toFixed(2);
        const date = new Date();
        date.setHours(hours, minutes + duration, 0, 0);
        const endHours = String(date.getHours()).padStart(2, '0');
        const endMinutes = String(date.getMinutes()).padStart(2, '0');
        summaryBox.textContent = `Duración del servicio: ${duration} minutos. Precio: $${price.toFixed(2)}. Adelanto requerido: $${advanceAmount}. Hora estimada de finalización: ${endHours}:${endMinutes}.`;
    }
    businessSelect.addEventListener('change', () => { updateServices(); updateSchedule(); updateSummary(); });
    serviceSelect.addEventListener('change', updateSummary);
    startTimeInput.addEventListener('input', updateSummary);
    updateServices();
    updateSchedule();
    updateSummary();
</script>
</body>
</html>
