<div class="mb-4 grid gap-3" x-data>
    @if (session('status'))
        <div
            class="flash-success flex items-start justify-between gap-3"
            x-data="{ show: true }"
            x-show="show"
            x-transition
        >
            <span>{{ session('status') }}</span>
            <button type="button" class="shrink-0 opacity-70 hover:opacity-100" @click="show = false" aria-label="Cerrar">
                <x-heroicon-o-x-mark class="h-5 w-5" />
            </button>
        </div>
    @endif

    @if (session('error'))
        <div
            class="flash-error flex items-start justify-between gap-3"
            x-data="{ show: true }"
            x-show="show"
            x-transition
        >
            <span>{{ session('error') }}</span>
            <button type="button" class="shrink-0 opacity-70 hover:opacity-100" @click="show = false" aria-label="Cerrar">
                <x-heroicon-o-x-mark class="h-5 w-5" />
            </button>
        </div>
    @endif
</div>
