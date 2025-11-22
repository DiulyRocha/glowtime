<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'GlowTime') }}</title>

    {{-- CORREÇÃO: carregar CSS/JS via Vite de forma nativa --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="font-sans bg-gray-100">

    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="w-full max-w-md p-6 bg-white shadow rounded">
            @yield('content')
        </div>
    </div>

</body>
</html>
