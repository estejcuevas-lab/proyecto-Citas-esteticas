@props([
    'inputId' => 'primary_color',
    'value' => '#994b35',
])

@php
    $normalizedValue = preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $value) ? strtoupper((string) $value) : '#994B35';
    $palette = ['#F54927', '#994B35', '#6A2D1E', '#C98E57', '#D4AF37', '#2F6B48', '#1E40AF', '#7C3AED'];
@endphp

<label for="{{ $inputId }}" class="grid gap-3">
    Color principal
    <div class="brand-color-picker" x-data="{ color: '{{ $normalizedValue }}' }">
        <div class="brand-color-picker__control">
            <input
                id="{{ $inputId }}_picker"
                class="brand-color-picker__native"
                type="color"
                x-model="color"
                aria-label="Selector visual de color"
            >
            <div class="grid gap-1">
                <span class="hint">Selecciona o pega un color HEX</span>
                <span class="brand-color-picker__value" x-text="color"></span>
            </div>
        </div>

        <input
            id="{{ $inputId }}"
            name="primary_color"
            type="text"
            x-model="color"
            pattern="^#[0-9A-Fa-f]{6}$"
            maxlength="7"
            placeholder="#994B35"
            required
        >

        <div class="brand-color-picker__swatches" role="list" aria-label="Colores sugeridos">
            @foreach ($palette as $swatch)
                <button
                    type="button"
                    class="brand-color-picker__swatch"
                    style="background: {{ $swatch }};"
                    @click="color='{{ $swatch }}'"
                    aria-label="Aplicar color {{ $swatch }}"
                ></button>
            @endforeach
        </div>

        <div class="brand-color-picker__preview">
            <span>Vista previa de marca</span>
            <span class="inline-flex items-center gap-2">
                <span class="h-5 w-5 rounded-full border border-black/10" :style="{ backgroundColor: color }"></span>
                <span x-text="color"></span>
            </span>
        </div>
    </div>
</label>
