@php
    $manifestPath = public_path('build/manifest.json');
    $manifest = file_exists($manifestPath)
        ? json_decode((string) file_get_contents($manifestPath), true)
        : null;
@endphp

@if (is_array($manifest))
    @if (isset($manifest['resources/css/app.css']['file']))
        <link rel="stylesheet" href="{{ asset('build/'.$manifest['resources/css/app.css']['file']) }}">
    @endif

    @if (isset($manifest['resources/js/app.js']['file']))
        <script type="module" src="{{ asset('build/'.$manifest['resources/js/app.js']['file']) }}"></script>
    @endif
@else
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
