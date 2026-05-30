<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'citas-app')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased" style="@yield('theme_style')" x-data="{ mobileNavOpen: false }">
    @hasSection('hide_header')
    @else
        @include('layouts.partials.site-header')
    @endif

    <main class="@yield('main_class', 'page-shell')">
        <div class="@yield('container_class', 'page-width')">
            @unless(View::hasSection('hide_flash'))
                @include('layouts.partials.flash-messages')
            @endunless

            @unless(View::hasSection('hide_errors'))
                @include('layouts.partials.validation-errors')
            @endunless

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
