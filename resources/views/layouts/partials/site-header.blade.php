<header class="site-header page-width">
    <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide text-white no-underline">
        citas<span class="text-accent">-app</span>
    </a>

    <nav class="hidden items-center gap-5 text-sm font-medium md:flex">
        <a href="{{ route('home') }}#catalogo" class="muted no-underline transition hover:text-white">
            Negocios
        </a>

        @auth
            <a href="{{ route('dashboard') }}" class="muted no-underline transition hover:text-white">Panel</a>
            <a href="{{ route('appointments.index') }}" class="muted no-underline transition hover:text-white">Citas</a>
            @if (auth()->user()->canManageBusinesses())
                <a href="{{ route('businesses.index') }}" class="muted no-underline transition hover:text-white">Administrar</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-secondary text-sm">Salir</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="brand-button no-underline">Ingresar</a>
        @endauth
    </nav>

    <button
        type="button"
        class="inline-flex rounded-lg border border-white/10 bg-white/5 p-2 md:hidden"
        @click="mobileNavOpen = ! mobileNavOpen"
        :aria-expanded="mobileNavOpen"
        aria-controls="mobile-nav"
        aria-label="Abrir menu"
    >
        <x-heroicon-o-bars-3 class="h-6 w-6 text-zinc-100" x-show="! mobileNavOpen" />
        <x-heroicon-o-x-mark class="h-6 w-6 text-zinc-100" x-show="mobileNavOpen" x-cloak />
    </button>

    <div
        id="mobile-nav"
        class="fixed inset-x-4 top-20 z-50 rounded-2xl border border-white/10 bg-zinc-900/95 p-4 shadow-2xl backdrop-blur-xl md:hidden"
        style="box-shadow: 0 0 0 1px color-mix(in srgb, var(--primary-color) 30%, transparent);"
        x-show="mobileNavOpen"
        x-transition
        @click.outside="mobileNavOpen = false"
        x-cloak
    >
        <nav class="grid gap-2">
            <a href="{{ route('home') }}#catalogo" class="rounded-lg px-3 py-2 text-zinc-200 no-underline hover:bg-white/5">
                Negocios
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 text-zinc-200 no-underline hover:bg-white/5">Panel</a>
                <a href="{{ route('appointments.index') }}" class="rounded-lg px-3 py-2 text-zinc-200 no-underline hover:bg-white/5">Citas</a>
                @if (auth()->user()->canManageBusinesses())
                    <a href="{{ route('businesses.index') }}" class="rounded-lg px-3 py-2 text-zinc-200 no-underline hover:bg-white/5">Administrar</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary w-full">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="brand-button justify-center no-underline">Ingresar</a>
            @endauth
        </nav>
    </div>
</header>
