<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pagar anticipo</title>
    <style>
        :root {
            --bg: #f4efe8;
            --panel: rgba(255, 251, 247, 0.96);
            --panel-strong: #fffaf5;
            --line: #ddcdbd;
            --text: #2f241c;
            --muted: #776655;
            --brand: #994b35;
            --brand-deep: #6a2d1e;
            --accent: #2f6b48;
            --accent-deep: #245639;
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
                radial-gradient(circle at right top, rgba(153, 75, 53, 0.18), transparent 22%),
                linear-gradient(180deg, #f8f4ef 0%, var(--bg) 100%);
        }
        .shell { min-height: 100vh; padding: 2rem; }
        .panel {
            max-width: 1100px;
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
            grid-template-columns: 1.1fr 0.9fr;
            gap: 1.2rem;
        }
        .form-card, .summary-card {
            border-radius: 24px;
            border: 1px solid var(--line);
            background: var(--panel-strong);
            padding: 1.5rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
        .field.full { grid-column: 1 / -1; }
        label { display: block; margin-bottom: .45rem; font-weight: 700; }
        input, select {
            width: 100%;
            padding: .9rem 1rem;
            border-radius: 14px;
            border: 1px solid #c4ad95;
            background: #fffdfa;
            color: var(--text);
            font: inherit;
        }
        .info-box, .note, .success-box, .error-list {
            padding: 1rem;
            border-radius: 18px;
            border: 1px solid #eadfd4;
            background: #fcf8f4;
        }
        .success-box {
            background: var(--success-bg);
            color: var(--success-text);
            border-color: #c7e4cf;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        .error-list {
            margin-top: 1rem;
            background: var(--danger-bg);
            color: var(--danger-text);
            border-color: #efc6ca;
        }
        .button-row {
            display: flex;
            flex-wrap: wrap;
            gap: .8rem;
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
        button { background: linear-gradient(135deg, var(--accent), var(--accent-deep)); color: #fffaf5; }
        .button.secondary { background: var(--soft); color: var(--text); }
        .method-fields { display: none; }
        .method-fields.active { display: block; }
        .summary-card h2 { margin-top: 0; color: var(--brand-deep); }
        .amount {
            font-size: clamp(2rem, 5vw, 3rem);
            color: var(--accent-deep);
            margin: .35rem 0 1rem;
        }
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
                <h1>Pagar anticipo</h1>
                <p class="muted">Esta pantalla simula una compra real para confirmar la cita cuando el cliente completa el 50% del servicio.</p>
            </div>
            <a class="button secondary" href="{{ route('appointments.edit', $appointment) }}">Volver a la cita</a>
        </div>

        @if (session('status'))
            <div class="success-box">{{ session('status') }}</div>
        @endif

        <div class="layout">
            <article class="form-card">
                <form method="POST" action="{{ route('appointments.payment.process', $appointment) }}">
                    @csrf
                    <div class="grid">
                        <div class="field full">
                            <label for="payment_method">Método de pago</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="">Selecciona una opción</option>
                                @foreach ($paymentMethods as $key => $label)
                                    <option value="{{ $key }}" @selected(old('payment_method') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="account_holder">Titular</label>
                            <input id="account_holder" name="account_holder" type="text" value="{{ old('account_holder', $appointment->user->name) }}" required>
                        </div>
                        <div class="field">
                            <label for="reference">Referencia o comprobante</label>
                            <input id="reference" name="reference" type="text" value="{{ old('reference') }}" required>
                        </div>
                    </div>

                    <div id="nequi-fields" class="method-fields note">
                        <label for="phone_number">Número Nequi</label>
                        <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number') }}" placeholder="3001234567">
                    </div>

                    <div id="bancolombia_transfer-fields" class="method-fields note">
                        <label for="account_number">Cuenta Bancolombia</label>
                        <input id="account_number" name="account_number" type="text" value="{{ old('account_number') }}" placeholder="Ahorros o corriente">
                    </div>

                    <div id="credit_card-fields" class="method-fields note">
                        <div class="grid">
                            <div class="field full">
                                <label for="card_number">Número de tarjeta</label>
                                <input id="card_number" name="card_number" type="text" value="{{ old('card_number') }}" placeholder="4111 1111 1111 1111">
                            </div>
                            <div class="field full">
                                <label for="card_name">Nombre impreso</label>
                                <input id="card_name" name="card_name" type="text" value="{{ old('card_name') }}">
                            </div>
                            <div class="field">
                                <label for="expiry_date">Vence</label>
                                <input id="expiry_date" name="expiry_date" type="text" value="{{ old('expiry_date') }}" placeholder="12/30">
                            </div>
                            <div class="field">
                                <label for="cvv">CVV</label>
                                <input id="cvv" name="cvv" type="text" value="{{ old('cvv') }}" placeholder="123">
                            </div>
                        </div>
                    </div>

                    <div id="debit_card-fields" class="method-fields note">
                        <div class="grid">
                            <div class="field full">
                                <label for="debit_card_number">Número de tarjeta</label>
                                <input id="debit_card_number" type="text" value="{{ old('card_number') }}" placeholder="5222 2222 2222 2222" data-mirror="card_number">
                            </div>
                            <div class="field full">
                                <label for="debit_card_name">Nombre impreso</label>
                                <input id="debit_card_name" type="text" value="{{ old('card_name') }}" data-mirror="card_name">
                            </div>
                            <div class="field">
                                <label for="debit_expiry_date">Vence</label>
                                <input id="debit_expiry_date" type="text" value="{{ old('expiry_date') }}" placeholder="12/30" data-mirror="expiry_date">
                            </div>
                            <div class="field">
                                <label for="debit_cvv">CVV</label>
                                <input id="debit_cvv" type="text" value="{{ old('cvv') }}" placeholder="123" data-mirror="cvv">
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="error-list">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="button-row">
                        <button type="submit">Pagar anticipo</button>
                        <a class="button secondary" href="{{ route('appointments.edit', $appointment) }}">Volver</a>
                    </div>
                </form>
            </article>

            <aside class="summary-card">
                <h2>Resumen de la reserva</h2>
                <div class="info-box">
                    <strong>Servicio</strong>
                    <p class="muted">{{ $appointment->service->name }} en {{ $appointment->business->name }}</p>
                </div>
                <div class="info-box" style="margin-top:1rem;">
                    <strong>Fecha y hora</strong>
                    <p class="muted">{{ $appointment->appointment_date?->format('Y-m-d') }} · {{ $appointment->start_time }} - {{ $appointment->end_time }}</p>
                </div>
                <div class="amount">${{ number_format((float) $appointment->advance_amount, 2) }}</div>
                <div class="note">
                    <strong>Qué pasa al pagar</strong>
                    <p class="muted">El anticipo cambiará a pagado y la cita pasará automáticamente a confirmada.</p>
                </div>
                <div class="note" style="margin-top:1rem;">
                    <strong>Importante</strong>
                    <p class="muted">Los datos no se validan contra bancos reales. Esta pantalla solo simula la experiencia de pago para la demo.</p>
                </div>
            </aside>
        </div>
    </section>
</main>
<script>
    const paymentMethodSelect = document.getElementById('payment_method');
    const methodBlocks = document.querySelectorAll('.method-fields');
    const debitMirrors = document.querySelectorAll('[data-mirror]');

    function syncDebitMirrors() {
        debitMirrors.forEach((input) => {
            input.addEventListener('input', () => {
                const target = document.getElementById(input.dataset.mirror);
                if (target) {
                    target.value = input.value;
                }
            });
        });
    }

    function updatePaymentFields() {
        const selectedMethod = paymentMethodSelect.value;

        methodBlocks.forEach((block) => {
            block.classList.toggle('active', block.id === `${selectedMethod}-fields`);
        });
    }

    paymentMethodSelect.addEventListener('change', updatePaymentFields);
    syncDebitMirrors();
    updatePaymentFields();
</script>
</body>
</html>
