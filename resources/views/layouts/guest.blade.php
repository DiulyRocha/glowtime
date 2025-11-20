<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'GlowTime') }}</title>

    {{-- Carregar CSS/JS do build do Vite na produção --}}
    @php
        $manifestPath = public_path('build/manifest.json');

        $cssFile = null;
        $jsFile = null;

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);

            // Arquivos conforme manifest.json
            $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
            $jsFile  = $manifest['resources/js/app.js']['file'] ?? null;
        }
    @endphp

    {{-- CSS --}}
    @if ($cssFile)
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    @endif

</head>

<body class="font-sans bg-gray-100">

    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="w-full max-w-md p-6 bg-white shadow rounded">
            @yield('content')
        </div>
    </div>

    {{-- JS --}}
    @if ($jsFile)
        <script type="module" src="{{ asset('build/' . $jsFile) }}"></script>
    @endif

</body>
</html>
