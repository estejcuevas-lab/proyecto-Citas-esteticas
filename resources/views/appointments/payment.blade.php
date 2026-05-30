@extends('layouts.app')

@section('title', 'Pagar anticipo')
@section('hide_errors')
@section('hide_flash')

@section('content')
    <section class="surface">
        <div class="page-topbar">
            <div>
                <h1 class="page-title">Pagar anticipo</h1>
                <p class="muted mb-0">Simulacion de pago del 50% para confirmar la cita.</p>
            </div>
            <a class="button secondary" href="{{ route('appointments.edit', $appointment) }}">Volver a la cita</a>
        </div>

        @if (session('status'))
            <div class="success-box">{{ session('status') }}</div>
        @endif

        <div class="form-layout">
            <article class="form-card">
                <form method="POST" action="{{ route('appointments.payment.process', $appointment) }}">
                    @csrf
                    <div class="grid">
                        <div class="field full">
                            <label for="payment_method">Metodo de pago</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="">Selecciona una opcion</option>
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
                        <label for="phone_number">Numero Nequi</label>
                        <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number') }}" placeholder="3001234567">
                    </div>

                    <div id="bancolombia_transfer-fields" class="method-fields note">
                        <label for="account_number">Cuenta Bancolombia</label>
                        <input id="account_number" name="account_number" type="text" value="{{ old('account_number') }}" placeholder="Ahorros o corriente">
                    </div>

                    <div id="credit_card-fields" class="method-fields note">
                        <div class="grid">
                            <div class="field full">
                                <label for="card_number">Numero de tarjeta</label>
                                <input id="card_number" name="card_number" type="text" value="" placeholder="4111 1111 1111 1111">
                            </div>
                            <div class="field full">
                                <label for="card_name">Nombre impreso</label>
                                <input id="card_name" name="card_name" type="text" value="">
                            </div>
                            <div class="field">
                                <label for="expiry_date">Vence</label>
                                <input id="expiry_date" name="expiry_date" type="text" value="" placeholder="12/30">
                            </div>
                            <div class="field">
                                <label for="cvv">CVV</label>
                                <input id="cvv" name="cvv" type="text" value="" placeholder="123">
                            </div>
                        </div>
                    </div>

                    <div id="debit_card-fields" class="method-fields note">
                        <div class="grid">
                            <div class="field full">
                                <label for="debit_card_number">Numero de tarjeta</label>
                                <input id="debit_card_number" type="text" value="" placeholder="5222 2222 2222 2222" data-mirror="card_number">
                            </div>
                            <div class="field full">
                                <label for="debit_card_name">Nombre impreso</label>
                                <input id="debit_card_name" type="text" value="" data-mirror="card_name">
                            </div>
                            <div class="field">
                                <label for="debit_expiry_date">Vence</label>
                                <input id="debit_expiry_date" type="text" value="" placeholder="12/30" data-mirror="expiry_date">
                            </div>
                            <div class="field">
                                <label for="debit_cvv">CVV</label>
                                <input id="debit_cvv" type="text" value="" placeholder="123" data-mirror="cvv">
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
                    <p class="muted mb-0">{{ $appointment->service->name }} en {{ $appointment->business->name }}</p>
                </div>
                <div class="info-box mt-4">
                    <strong>Fecha y hora</strong>
                    <p class="muted mb-0">{{ $appointment->appointment_date?->format('Y-m-d') }} · {{ $appointment->start_time }} - {{ $appointment->end_time }}</p>
                </div>
                <div class="amount">${{ number_format((float) $appointment->advance_amount, 2) }}</div>
                <div class="note">
                    <strong>Al pagar</strong>
                    <p class="muted mb-0">Anticipo pagado y cita confirmada automaticamente.</p>
                </div>
                <div class="note">
                    <strong>Demo</strong>
                    <p class="muted mb-0">Sin validacion bancaria real.</p>
                </div>
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
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
@endpush
