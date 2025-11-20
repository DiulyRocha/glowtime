<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'GlowTime') }}</title>

    @php
        $manifestPath = public_path('build/manifest.json');

        $cssFile = null;
        $jsFile = null;
        $calendarJs = null;

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);

            $cssFile      = $manifest['resources/css/app.css']['file'] ?? null;
            $jsFile       = $manifest['resources/js/app.js']['file'] ?? null;
            $calendarJs   = $manifest['resources/js/calendar.js']['file'] ?? null;
        }
    @endphp

    @if ($cssFile)
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    @endif
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen bg-gray-100 flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-black text-white flex flex-col">

            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b border-pink-600">
                <img src="{{ asset('imagens/logo/logo.png') }}" alt="Logo" class="h-16 mr-2">
                <span class="font-bold text-pink-500 text-lg">
                    Glow<span class="text-purple-500">Time</span>
                </span>
            </div>

            <!-- Menu -->
            <nav class="flex-1 p-4 space-y-2">

                <a href="{{ route('dashboard') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->routeIs('dashboard') ? 'bg-pink-600' : '' }}">
                    Painel
                </a>

                <a href="{{ route('reports.appointments') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('reports/appointments') ? 'bg-pink-600' : '' }}">
                    Relatório de Agendamentos
                </a>

                <hr class="border-gray-700 my-2">
                <span class="text-gray-400 text-xs uppercase px-4">Cadastros</span>

                <a href="{{ route('clients.index') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('clients*') ? 'bg-pink-600' : '' }}">
                    Clientes
                </a>

                <a href="{{ route('services.index') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('services*') ? 'bg-pink-600' : '' }}">
                    Serviços
                </a>

                <a href="{{ route('professionals.index') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('professionals*') ? 'bg-pink-600' : '' }}">
                    Profissionais
                </a>

                <hr class="border-gray-700 my-2">
                <span class="text-gray-400 text-xs uppercase px-4">Relatórios</span>

                <a href="{{ route('reports.finance.daily') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('reports/finance/daily') ? 'bg-pink-600' : '' }}">
                    Diário
                </a>

                <a href="{{ route('reports.finance.monthly') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('reports/finance/monthly') ? 'bg-pink-600' : '' }}">
                    Mensal
                </a>

                <a href="{{ route('reports.finance.yearly') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('reports/finance/yearly') ? 'bg-pink-600' : '' }}">
                    Anual
                </a>

                <hr class="border-gray-700 my-2">
                <span class="text-gray-400 text-xs uppercase px-4">Configurações</span>

                <a href="{{ route('settings.index') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('settings') ? 'bg-pink-600' : '' }}">
                    ⚙️ Descontos e Preferências
                </a>

                <hr class="border-gray-700 my-2">
                <span class="text-gray-400 text-xs uppercase px-4">Alertas</span>

                <a href="{{ route('reports.birthdays') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('reports/birthdays') ? 'bg-pink-600' : '' }}">
                    🎂 Aniversariantes
                    @if(isset($countBirthdays) && $countBirthdays > 0)
                        <span class="ml-2 bg-pink-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $countBirthdays }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('reports.inactive') }}"
                    class="block px-4 py-2 rounded hover:bg-pink-600 {{ request()->is('reports/inactive-clients') ? 'bg-pink-600' : '' }}">
                    💤 Clientes Inativas
                    @if(isset($countInactiveClients) && $countInactiveClients > 0)
                        <span class="ml-2 bg-purple-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $countInactiveClients }}
                        </span>
                    @endif
                </a>

            </nav>

            <div class="p-4 border-t border-gray-700">
                <div class="font-medium">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-red-500 hover:underline">Sair</button>
                </form>
            </div>

        </aside>

        <!-- Conteúdo principal -->
        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    {{-- Adiciona scripts NO FINAL --}}
    @if ($jsFile)
        <script type="module" src="{{ asset('build/' . $jsFile) }}"></script>
    @endif

    @if ($calendarJs)
        <script type="module" src="{{ asset('build/' . $calendarJs) }}"></script>
    @endif

</body>
</html>
